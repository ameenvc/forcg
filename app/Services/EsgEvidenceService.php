<?php
namespace App\Services;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
class EsgEvidenceService
{
    public function ready(){return Schema::hasTable('esg_evidence') && Schema::hasTable('esg_evidence_events');}
    public function actor(){ $u=auth()->user(); return get_class($u).':'.$u->getAuthIdentifier(); }
    public function canReview(){ $u=auth()->user(); return $u instanceof \App\Admin || $u instanceof \App\SuperAdmin || ($u instanceof \App\User && Schema::hasTable('user_gates') && DB::table('user_gates')->where('users_id',$u->id)->where('key','manage-esg-evidence')->exists()); }
    public function validate(array $data): array
    {
        $v=Validator::make($data,[
            'kind'=>'required|in:evidence,emission,rating','title'=>'required|string|max:190','site'=>'required|string|max:190',
            'period_start'=>'required|date_format:Y-m-d','period_end'=>'required|date_format:Y-m-d|after_or_equal:period_start|before_or_equal:today',
            'source_reference'=>'required|string|max:500','pillar'=>'required|in:E,S,G',
            'scope'=>'required_if:kind,emission|nullable|in:1,2,3','basis'=>'required_if:kind,emission|nullable|in:direct,location,market',
            'quantity'=>'required_if:kind,emission|nullable|numeric|min:0|max:1000000000000',
            'unit'=>'required_if:kind,emission|nullable|string|max:40','factor'=>'required_if:kind,emission|nullable|numeric|min:0|max:1000000',
            'factor_reference'=>'required_if:kind,emission|nullable|string|max:500',
            'provider'=>'required_if:kind,rating|nullable|string|max:190','rating'=>'required_if:kind,rating|nullable|string|max:100',
            'scale'=>'required_if:kind,rating|nullable|string|max:100',
        ]);
        $v->after(function($v)use($data){
            if (($data['kind']??'')==='emission') {
                $scope=(string)($data['scope']??'');$basis=$data['basis']??'';
                if (($scope==='2' && !in_array($basis,['location','market'],true)) || ($scope!=='2' && $basis!=='direct')) $v->errors()->add('basis','Scope 2 needs location or market basis; Scope 1/3 use direct.');
                if (($data['pillar']??'')!=='E') $v->errors()->add('pillar','Emissions belong to Environmental.');
            }
        });
        return $v->validate();
    }
    public function insert(array $data,string $path,string $hash,string $actor): int
    {
        $payload=array_intersect_key($data,array_flip(['pillar','scope','basis','quantity','unit','factor','factor_reference','provider','rating','scale']));
        if ($data['kind']==='emission') $payload['tco2e']=round((float)$data['quantity']*(float)$data['factor']/1000,6);
        // One source/activity title per site, period, kind, scope and accounting basis.
        $identity=array_map(function($s){return mb_strtolower(trim((string)$s));},[$data['kind'],$data['title'],$data['site'],$data['period_start'],$data['period_end'],$data['source_reference'],$data['scope']??'',$data['basis']??'']);
        $id=DB::table('esg_evidence')->insertGetId(['fingerprint'=>hash('sha256',json_encode($identity)),'kind'=>$data['kind'],'title'=>$data['title'],'site'=>$data['site'],
            'period_start'=>$data['period_start'],'period_end'=>$data['period_end'],'source_reference'=>$data['source_reference'],'payload'=>json_encode($payload),
            'file_path'=>$path,'file_hash'=>$hash,'submitted_by'=>$actor,'status'=>'pending','created_at'=>now(),'updated_at'=>now()]);
        DB::table('esg_evidence_events')->insert(['evidence_id'=>$id,'actor'=>$actor,'event'=>'submitted','created_at'=>now()]);
        return $id;
    }
    public function inventory(): array
    {
        if (!$this->ready()) return [];
        $groups=[];
        foreach(DB::table('esg_evidence')->where('kind','emission')->where('status','accepted')->orderBy('id')->cursor() as $record){
            $p=json_decode($record->payload,true);$key=json_encode([$record->site,$record->period_start,$record->period_end,$p['scope'],$p['basis']]);
            if (!isset($groups[$key])) $groups[$key]=['site'=>$record->site,'start'=>$record->period_start,'end'=>$record->period_end,'scope'=>$p['scope'],'basis'=>$p['basis'],'total'=>0,'count'=>0];
            $groups[$key]['total']+=(float)$p['tco2e'];$groups[$key]['count']++;
        }
        return array_values($groups);
    }
}
