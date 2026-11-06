<?php
namespace App\Http\Controllers;
use App\Services\EsgEvidenceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
class EsgEvidenceController extends Controller
{
    public function __construct(){ $this->middleware(['auth:web,admin,superadmin','mfa','throttle:30,1']); }
    public function index(EsgEvidenceService $service)
    {
        $ready=$service->ready();$canReview=$service->canReview();
        $records=$ready?DB::table('esg_evidence')->orderByDesc('id')->paginate(20):null;
        $events=$ready?DB::table('esg_evidence_events')->whereIn('evidence_id',$records->pluck('id'))->orderBy('id')->get()->groupBy('evidence_id'):collect();
        $actor=$service->actor();$inventory=$service->inventory();$model=\Illuminate\Support\Facades\Schema::hasTable('modenadisable')?DB::table('modenadisable')->first():null;
        return response()->view('user.esg-evidence',compact('ready','canReview','records','inventory','model','events','actor'))->header('Cache-Control','private, no-store');
    }
    public function store(Request $r,EsgEvidenceService $s)
    {
        abort_unless($s->ready(),503,'ESG database migration is required.');
        $data=$s->validate($r->all());$r->validate(['document'=>'required|file|mimes:pdf,jpg,jpeg,png|max:10240']);
        $file=$r->file('document');$path=$file->store('esg-evidence','local');$hash=hash_file('sha256',$file->getRealPath());
        try { DB::transaction(function()use($s,$data,$path,$hash){$s->insert($data,$path,$hash,$s->actor());}); }
        catch(\Throwable $e){Storage::disk('local')->delete($path);if($e instanceof \Illuminate\Database\QueryException)return back()->withErrors(['document'=>'This source record already exists or could not be saved.'])->withInput();throw $e;}
        return redirect('/user/score/evidence')->with('msg','Evidence submitted for independent internal review.');
    }
    public function review(Request $r,$id,EsgEvidenceService $s)
    {
        abort_unless($s->ready(),503);abort_unless($s->canReview(),403);
        $data=$r->validate(['decision'=>'required|in:accepted,rejected','note'=>'required|string|max:2000','assurance_provider'=>'required_with:assurance,assurance_reference|nullable|string|max:190',
            'assurance_reference'=>'required_with:assurance_provider,assurance|nullable|string|max:500','assurance'=>'required_with:assurance_provider|nullable|file|mimes:pdf|max:10240']);
        $path=$r->file('assurance')?$r->file('assurance')->store('esg-assurance','local'):null;
        try{DB::transaction(function()use($s,$data,$path,$id){
            $record=DB::table('esg_evidence')->where('id',$id)->lockForUpdate()->first();abort_unless($record,404);
            abort_if($record->submitted_by===$s->actor(),403,'A different reviewer must review your submission.');abort_unless($record->status==='pending',409,'This record has already been reviewed.');
            DB::table('esg_evidence')->where('id',$id)->update(['status'=>$data['decision'],'reviewed_by'=>$s->actor(),'reviewed_at'=>now(),'review_note'=>$data['note'],
                'assurance_provider'=>$data['assurance_provider']??null,'assurance_reference'=>$data['assurance_reference']??null,'assurance_path'=>$path,'updated_at'=>now()]);
            DB::table('esg_evidence_events')->insert(['evidence_id'=>$id,'actor'=>$s->actor(),'event'=>$data['decision'],'note'=>$data['note'],'created_at'=>now()]);
        });}catch(\Throwable $e){if($path)Storage::disk('local')->delete($path);throw $e;}
        return redirect('/user/score/evidence')->with('msg','Review recorded. External assurance is recorded evidence, not an API verification.');
    }
    public function withdraw(Request $r,$id,EsgEvidenceService $s)
    {
        abort_unless($s->ready(),503);$data=$r->validate(['note'=>'required|string|max:2000']);
        DB::transaction(function()use($s,$data,$id){
            $record=DB::table('esg_evidence')->where('id',$id)->lockForUpdate()->first();abort_unless($record,404);
            abort_unless($s->canReview() || $record->submitted_by===$s->actor(),403);
            abort_if($record->status==='withdrawn',409,'Record already withdrawn.');
            DB::table('esg_evidence')->where('id',$id)->update(['status'=>'withdrawn','updated_at'=>now()]);
            DB::table('esg_evidence_events')->insert(['evidence_id'=>$id,'actor'=>$s->actor(),'event'=>'withdrawn','note'=>$data['note'],'created_at'=>now()]);
        });
        return redirect('/user/score/evidence')->with('msg','Record withdrawn. It is excluded from accepted emissions totals; its source and review history are retained.');
    }
    public function download($id,Request $r,EsgEvidenceService $s)
    {
        abort_unless($s->ready(),503);$record=DB::table('esg_evidence')->where('id',$id)->first();abort_unless($record,404);
        $path=$r->query('assurance')?$record->assurance_path:$record->file_path;abort_unless($path && Storage::disk('local')->exists($path),404);
        return Storage::disk('local')->download($path,null,['Cache-Control'=>'private, no-store','X-Content-Type-Options'=>'nosniff']);
    }
    public function template(){return response("kind,title,site,period_start,period_end,source_reference,pillar,scope,basis,quantity,unit,factor,factor_reference,provider,rating,scale\nemission,Electricity invoice,Head office,2025-01-01,2025-01-31,Invoice-001,E,2,location,1000,kWh,0.4,Replace with published factor and year,,,\n",200,['Content-Type'=>'text/csv','Content-Disposition'=>'attachment; filename="esg-import-template.csv"']);}
    public function import(Request $r,EsgEvidenceService $s)
    {
        abort_unless($s->ready(),503);$r->validate(['csv'=>'required|file|max:1024','document'=>'required|file|mimes:pdf,jpg,jpeg,png|max:10240']);
        $handle=fopen($r->file('csv')->getRealPath(),'r');$header=fgetcsv($handle);$rows=[];
        try{abort_unless(is_array($header),422,'Missing CSV header.');$header[0]=preg_replace('/^\xEF\xBB\xBF/','',$header[0]);
            abort_unless(count($header)===count(array_unique($header)),422,'Duplicate CSV columns.');
            while(($row=fgetcsv($handle))!==false){if($row===[null])continue;abort_unless(count($rows)<100 && count($row)===count($header),422,'Use at most 100 rows and match template columns.');$rows[]=$s->validate(array_combine($header,$row));}
        }finally{fclose($handle);}
        abort_unless(count($rows)>0,422,'No records to import.');$file=$r->file('document');$path=$file->store('esg-evidence','local');$hash=hash_file('sha256',$file->getRealPath());
        try{DB::transaction(function()use($rows,$s,$path,$hash){foreach($rows as $data)$s->insert($data,$path,$hash,$s->actor());});}
        catch(\Throwable $e){Storage::disk('local')->delete($path);if($e instanceof \Illuminate\Database\QueryException)return back()->withErrors(['csv'=>'Duplicate source record or import failed. No rows were imported.']);throw $e;}
        return redirect('/user/score/evidence')->with('msg',count($rows).' records imported as pending review.');
    }
}
