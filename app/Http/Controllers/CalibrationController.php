<?php

namespace App\Http\Controllers;

use App\CalibrationFrequency;
use App\Calibrationreviewer;
use App\EmailNotification;
use App\EmailNotificationsUserDetails;
use App\Http\Requests\Calibration\ChangeStatusByDueDateRequest;
use App\Http\Requests\Calibration\UpdateCalibrationRequest;
use App\Mail\CalibrationStatusUpdate;
use App\Mail\ChangeStatusOfCalibration;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Auth;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Departments;
use App\Type;
use App\Calibration_instrument;
use App\CalibrationExternal;
use App\CalibrationExternalFile;
use App\CalibrationExternalfiles;
use App\CalibrationInternal;
// use App\Notifications;
use App\CustomField;
use App\ModenaDisable;
use Illuminate\View\View;
use Session;
use PDF;
use App\ModuleBladePageDetail;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Helpers\Calibration\CalibrationFormConfigurationHelper;

class CalibrationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware(['auth:web,admin', 'mfa']);
    }

    /**
     * Show the application dashboard.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        //below is superadmin related codes
        $this->updateStatusWhenViewListData(Calibration_instrument::EXPIRED_STATUS);
        $this->updateStatusWhenViewListData(Calibration_instrument::NEAR_TO_EXPIRED_STATUS);
        $this->updateStatusWhenViewListData(Calibration_instrument::CALIBRATED_STATUS);
        $calibration = DB::table('modpageload')->where('id', 1)->get();
        if (sizeof($calibration) == 0) {
            DB::table('modpageload')->insert(['id' => 1, 'calibration' => 1]);
        } else {
            $calibration              = DB::table('modpageload')->where('id', 1)->First();
            $calibration->calibration = $calibration->calibration + 1;
            DB::table('modpageload')->update(['calibration' => $calibration->calibration]);
        }
        //end of superadmin codes
        $departments    = Departments::all();
        $data           = DB::table('calibration_instruments')->orderByDesc('created_at')->get();
        $model          = DB::table('modenadisable')->First();
        $mr             = DB::table('calibrationreviewers')->where('email', Auth::user()->email)->get();
        $internal_datas = Calibration_instrument::with([
            'departmentdetails',
            'calibrationExternalReportNo',
            'calibrationInternalReportNo',
        ])->where('Type', 'Internal')
            ->orWhere('Type', 'INTERNAL')
            ->orWhere('Type', 'internal')
            ->orderByDesc('created_at')
            ->get();
        $external_datas = Calibration_instrument::with([
            'departmentdetails',
            'calibrationExternalReportNo',
            'calibrationInternalReportNo',
            'calibrationExternalFiles'
        ])->where('Type', 'External')
            ->orWhere('Type', 'EXTERNAL')
            ->orWhere('Type', 'external')
            ->orderByDesc('created_at')
            ->get();
        $frequencies = CalibrationFrequency::orderBy('id', 'DESC')->pluck('frequency');
        $allFrequency = implode(',', array_unique($frequencies->toArray()));
        $dateOfCalibrations = $data->sortByDesc('dateofcal')->map(function ($item) {
            if ($item->dateofcal) {
                return date('Y', strtotime($item->dateofcal));
            }
        });
        $allDateOfCalibrations = implode(',', array_unique($dateOfCalibrations->toArray()));
        $dueDates = $data->sortByDesc('Duedate')->map(function ($item) {
            if ($item->Duedate) {
                return date('Y', strtotime($item->Duedate));
            }
        });
        $allDueDates = implode(',', array_unique($dueDates->toArray()));
        $statuses = $data->map(function ($item) {
            if ($item->Status) {
                return $item->Status;
            } else {
                return Calibration_instrument::NOT_CALIBRATED_STATUS;
            }
        });
        $allStatuses = implode(',', array_unique($statuses->toArray()));
        $calibrationFieldRules = CalibrationFormConfigurationHelper::getCalibrationFields();
        return view('user.calibration.register')
            ->with('calibrationFieldRules', $calibrationFieldRules)
            ->with('mr', $mr)
            ->with('model', $model)
            ->with('data', $data)
            ->with('internal_datas', $internal_datas)
            ->with('external_datas', $external_datas)
            ->with('departments', $departments)
            ->with('allFrequency', $allFrequency)
            ->with('allDateOfCalibrations', $allDateOfCalibrations)
            ->with('allDueDates', $allDueDates)
            ->with('allStatuses', $allStatuses);
    }

    /**
     * Update status when view list data
     *  - Calibrated
     *  - Expired
     *  - Near to expired
     * @param $status
     * @return void
     */
    private function updateStatusWhenViewListData($status)
    {
        $today = date("Y-m-d");
        $nearToExpiryDate = date('Y-m-d',strtotime($today . ' + 30 days'));
        $records = Calibration_instrument::where('Status', '!=', Calibration_instrument::UNDER_CALIBRATION_STATUS)
            ->where('Status','!=','Obsolete')->whereNotNull('dateofcal')
            ->whereNull('Duedate')
            ->get();
        foreach($records as $record){
            $dateofcal = $record->dateofcal;
            $inst_frequency = $record->Frequency;
            $inst_status = $record->Status;
            $inst_id = $record->id;
            if($inst_status == 'Calibrated'){
                $duedt = date('Y-m-d', strtotime($dateofcal . ' + ' . $inst_frequency));
                Calibration_instrument::where('id', $inst_id)->update(['Duedate' => $duedt]);
                CalibrationInternal::where('instrument_id', $inst_id)->update(['Duedate' => $duedt]);
                CalibrationExternal::where('instrument_id', $inst_id)->update(['Duedate' => $duedt]);
            }
        }
        Calibration_instrument::where('Status', '!=', Calibration_instrument::UNDER_CALIBRATION_STATUS)
            ->where('Status','!=','Obsolete')->whereNotNull('Duedate')
            ->where(function ($query) use($status, $today, $nearToExpiryDate) {
                if ($status == Calibration_instrument::EXPIRED_STATUS) {
                    $query->where('Duedate', '<', $today);
                }
                if ($status == Calibration_instrument::CALIBRATED_STATUS) {
                    $query->where('Duedate', '>', $nearToExpiryDate);
                }
                if ($status == Calibration_instrument::NEAR_TO_EXPIRED_STATUS) {
                    $query->where('Duedate', '<=', $nearToExpiryDate)
                        ->where('Duedate', '>=', $today);
                }
            })
            ->update([
                'Status' => $status
            ]);
        CalibrationExternal::where('Status', '!=', Calibration_instrument::UNDER_CALIBRATION_STATUS)
            ->where('Status','!=','Obsolete')->whereNotNull('Duedate')
            ->where(function ($query) use($status, $today, $nearToExpiryDate) {
                if ($status == Calibration_instrument::EXPIRED_STATUS) {
                    $query->where('Duedate', '<', $today);
                }
                if ($status == Calibration_instrument::CALIBRATED_STATUS) {
                    $query->where('Duedate', '>', $nearToExpiryDate);
                }
                if ($status == Calibration_instrument::NEAR_TO_EXPIRED_STATUS) {
                    $query->where('Duedate', '<=', $nearToExpiryDate)
                        ->where('Duedate', '>=', $today);
                }
            })
            ->update([
                'Status' => $status
            ]);
        CalibrationInternal::where('Status', '!=', Calibration_instrument::UNDER_CALIBRATION_STATUS)
            ->where('Status','!=','Obsolete')->whereNotNull('Duedate')
            ->where(function ($query) use($status, $today, $nearToExpiryDate) {
                if ($status == Calibration_instrument::EXPIRED_STATUS) {
                    $query->where('Duedate', '<', $today);
                }
                if ($status == Calibration_instrument::CALIBRATED_STATUS) {
                    $query->where('Duedate', '>', $nearToExpiryDate);
                }
                if ($status == Calibration_instrument::NEAR_TO_EXPIRED_STATUS) {
                    $query->where('Duedate', '<=', $nearToExpiryDate)
                        ->where('Duedate', '>=', $today);
                }
            })
            ->update([
                'Status' => $status
            ]);
    }

    public function statusviewlist($status=null)
    {
        $this->updateStatusWhenViewListData(Calibration_instrument::EXPIRED_STATUS);
        $this->updateStatusWhenViewListData(Calibration_instrument::NEAR_TO_EXPIRED_STATUS);
        $this->updateStatusWhenViewListData(Calibration_instrument::CALIBRATED_STATUS);
        $calibration = DB::table('modpageload')->where('id', 1)->get();
        if (sizeof($calibration) == 0) {
            DB::table('modpageload')->insert(['id' => 1, 'calibration' => 1]);
        } else {
            $calibration              = DB::table('modpageload')->where('id', 1)->First();
            $calibration->calibration = $calibration->calibration + 1;
            DB::table('modpageload')->update(['calibration' => $calibration->calibration]);
        }
        //end of superadmin codes
        $internal_types = ['Internal', 'INTERNAL', 'internal'];
        $external_types = ['External', 'EXTERNAL', 'external'];
        if($status == "calibrated"){
            $internal_datas = Calibration_instrument::where('status','Calibrated')->with([
                'departmentdetails',
                'calibrationExternalReportNo',
                'calibrationInternalReportNo',
            ])->whereIn('Type', $internal_types)
                ->orderByDesc('created_at')
                ->get();
            $external_datas = Calibration_instrument::where('status','Calibrated')->with([
                'departmentdetails',
                'calibrationExternalReportNo',
                'calibrationInternalReportNo',
                'calibrationExternalFiles'
            ])->whereIn('Type', $external_types)
                ->orderByDesc('created_at')
                ->get();
        }elseif($status == "neartoexpiry"){
            $internal_datas = Calibration_instrument::where('status','Near to expiry')->with([
                'departmentdetails',
                'calibrationExternalReportNo',
                'calibrationInternalReportNo',
            ])->whereIn('Type', $internal_types)
                ->orderByDesc('created_at')
                ->get();
            $external_datas = Calibration_instrument::where('status','Near to expiry')->with([
                'departmentdetails',
                'calibrationExternalReportNo',
                'calibrationInternalReportNo',
                'calibrationExternalFiles'
            ])->whereIn('Type', $external_types)
                ->orderByDesc('created_at')
                ->get();
        }elseif($status == "expired"){
            $internal_datas = Calibration_instrument::where('status','Expired')->with([
                'departmentdetails',
                'calibrationExternalReportNo',
                'calibrationInternalReportNo',
            ])->whereIn('Type', $internal_types)
                ->orderByDesc('created_at')
                ->get();
            $external_datas = Calibration_instrument::where('status','Expired')->with([
                'departmentdetails',
                'calibrationExternalReportNo',
                'calibrationInternalReportNo',
                'calibrationExternalFiles'
            ])->whereIn('Type', $external_types)
                ->orderByDesc('created_at')
                ->get();
        }else{
            $internal_datas = Calibration_instrument::with([
                'departmentdetails',
                'calibrationExternalReportNo',
                'calibrationInternalReportNo',
            ])->whereIn('Type',$internal_types)
                ->orderByDesc('created_at')
                ->get();
            $external_datas = Calibration_instrument::with([
                'departmentdetails',
                'calibrationExternalReportNo',
                'calibrationInternalReportNo',
                'calibrationExternalFiles'
            ])->whereIn('Type',$external_types)
                ->orderByDesc('created_at')
                ->get();
        }
        $departments    = Departments::all();
        $data           = DB::table('calibration_instruments')->orderByDesc('created_at')->get();
        $model          = DB::table('modenadisable')->First();
        $mr             = DB::table('calibrationreviewers')->where('email', Auth::user()->email)->get();
        $frequencies = CalibrationFrequency::orderBy('id', 'DESC')->pluck('frequency');
        $allFrequency = implode(',', array_unique($frequencies->toArray()));
        $dateOfCalibrations = $data->sortByDesc('dateofcal')->map(function ($item) {
            if ($item->dateofcal) {
                return date('Y', strtotime($item->dateofcal));
            }
        });
        $allDateOfCalibrations = implode(',', array_unique($dateOfCalibrations->toArray()));
        $dueDates = $data->sortByDesc('Duedate')->map(function ($item) {
            if ($item->Duedate) {
                return date('Y', strtotime($item->Duedate));
            }
        });
        $allDueDates = implode(',', array_unique($dueDates->toArray()));
        $statuses = $data->map(function ($item) {
            if ($item->Status) {
                return $item->Status;
            } else {
                return Calibration_instrument::NOT_CALIBRATED_STATUS;
            }
        });
        $allStatuses = implode(',', array_unique($statuses->toArray()));

        return view('user.calibration.register')
            ->with('mr', $mr)
            ->with('model', $model)
            ->with('data', $data)
            ->with('internal_datas', $internal_datas)
            ->with('external_datas', $external_datas)
            ->with('departments', $departments)
            ->with('allFrequency', $allFrequency)
            ->with('allDateOfCalibrations', $allDateOfCalibrations)
            ->with('allDueDates', $allDueDates)
            ->with('allStatuses', $allStatuses);

    }

    //add instruments
    public function addinstruments()
    {
        $calibrationFieldRules = CalibrationFormConfigurationHelper::getCalibrationFields();
        $departments = Departments::all();
        $freq = DB::select('select * from calibration_frequency');
        $models=DB::table('modenadisable')->First();
        $target_pages = ModuleBladePageDetail::where('module_name','calibration')
            ->where('route_path','user/calibration/addinstruments')
            ->where('is_target_page',true)
            ->pluck('id')
            ->map(function($id) { return (string)$id; })
            ->toArray();
        $exCustomFields = CustomField::where('module_name', 'calibration')->where('sub_module','external')
            ->where(function ($query) use ($target_pages) {
                foreach ($target_pages as $pageId) {
                    $query->orWhereJsonContains('target_pages', $pageId);
                }
            })->where('deleted',false)
            ->whereJsonLength('target_pages', '>', 0)
            ->get();
        $inCustomFields = CustomField::where('module_name', 'calibration')->where('sub_module','internal')
            ->where(function ($query) use ($target_pages) {
                foreach ($target_pages as $pageId) {
                    $query->orWhereJsonContains('target_pages', $pageId);
                }
            })->where('deleted',false)
            ->whereJsonLength('target_pages', '>', 0)
            ->get();
         //Field Index
        $externalFieldIndex = ModuleBladePageDetail::where('module_name','calibration')->where('sub_module','external')->where('route_path','user/calibration/addinstruments')
            ->where('is_target_page',true)->value('field_index');

        $exdefaultFieldIndex = $externalFieldIndex = $externalFieldIndex ? array_values(json_decode($externalFieldIndex, true)) : [];
        $totalCount = count($externalFieldIndex);
        $fieldIndex = $externalFieldIndex;
        sort($fieldIndex,SORT_NUMERIC);
        $exCount = $exCustomFields->count();
        if($exCount>0){
            $exCountIndex = array_slice($fieldIndex,-$exCount);
            $externalFieldIndex = array_slice($fieldIndex, 0, $totalCount - $exCount);
        }else{
            $exCountIndex = [];
        }
        //End Field Index
         //Field Index
        $internalFieldIndex = ModuleBladePageDetail::where('module_name','calibration')->where('sub_module','internal')->where('route_path','user/calibration/addinstruments')
            ->where('is_target_page',true)->value('field_index');
        $indefaultFieldIndex = $internalFieldIndex = $internalFieldIndex ? array_values(json_decode($internalFieldIndex, true)) : [];
        $totalCount = count($internalFieldIndex);
        $fieldIndex = $internalFieldIndex;
        sort($fieldIndex,SORT_NUMERIC);
        $inCount = $inCustomFields->count();
        if($inCount>0){
            $inCountIndex = array_slice($fieldIndex,-$inCount);
            $internalFieldIndex = array_slice($fieldIndex, 0, $totalCount - $inCount);
        }else{
            $inCountIndex = [];
        }
        //End Field Index
        $externalCustomFields = getCustomFieldsView($exCustomFields,$exCountIndex);
        $internalCustomFields = getCustomFieldsView($inCustomFields,$inCountIndex);
        $exCustomFields = $exCustomFields->mapWithKeys(function ($field) {
            return [Str::slug($field->field_name, '_') => (int) $field->mandatory];
        })->toJson();

        $inCustomFields = $inCustomFields->mapWithKeys(function ($field) {
            return [Str::slug($field->field_name, '_') => (int) $field->mandatory];
        })->toJson();
        return view('user.calibration.addinstruments',
                compact('calibrationFieldRules', 'externalCustomFields','internalCustomFields','inCustomFields','exCustomFields',
                    'externalFieldIndex', 'internalFieldIndex','exdefaultFieldIndex','indefaultFieldIndex'
                ))->with('departments',$departments)->with('model',$models)->with('freq',$freq);
    }
    public function insertInstruments(Request $req)
    {
        $freq=  DB::select('select * from calibration_frequency');
        $validator = Validator::make($req->all(), [
                'instno' => 'required',
                'instdesc' => 'required',
                'department' =>'required',
                'instmanu' =>'required',
                'insttholder' =>'required',
                'instcapacity' =>'required',
                'insttype' =>'required',
                'instfrequency' =>'required',
        ]);
        if ($validator->fails()){
            $models=DB::table('modenadisable')->First();
            return back()
                    ->withErrors($validator)
                    ->withInput()->with('model',$models);
        }
        else
        {
           $inst_no  =  $req -> input('instno');
           $inst_desc = $req  -> input('instdesc');
           $department    = $req->input('department');
           $inst_manu    = $req->input('instmanu');
           $inst_capacity =   $req->input('instcapacity');
           $inst_type    = $req->input('insttype');
           $inst_procedure =  $req ->input('procedure');
           $inst_agency    =  $req->input('instagency');
           $inst_frequency=$req->input('instfrequency');
           $inst_standard_edition = $req->input('standard_edition');
           $inst_acceptance_criteria = $req->input('acceptance_criteria');
          // $inst_status    =$req->input('inststatus');
           $inst_file=$req->input('file');
           $freq  = $inst_frequency[0];
           $caldate = $req->input('duedate');
           $instt_holder   =   $req->input('insttholder');
           $inst_holder_nm = explode("-",$instt_holder);
           $inst_holder = $inst_holder_nm[0];

            if($req->input('calibrated')=='yes')
            {
               $inst_status = 'Calibrated';
               //$dateofcal = Carbon::createFromFormat('Y-m-d', $caldate);
               $duedt = Carbon::parse($caldate)->format('Y-m-d');

                    if($inst_frequency=="1 year" || $inst_frequency=="1 Year"|| $inst_frequency=="one year" || $inst_frequency=="One year"  || $inst_frequency=="One Year" )
                    {
                        $caldat=Carbon::createFromFormat('Y-m-d', $duedt)->subYear(1);
                        $dateofcal= Carbon::parse($caldat)->format('Y-m-d');

                    }
                    else if($inst_frequency=="Two Year" || $inst_frequency=="Two Years"||$inst_frequency=="2 Year" || $inst_frequency=="2 Years" || $inst_frequency=="2 year" || $inst_frequency=="2 years" ||$inst_frequency=="Two year" || $inst_frequency=="Two years" || $inst_frequency=="two year"|| $inst_frequency=="two years")
                    {
                        $caldat=Carbon::createFromFormat('Y-m-d', $duedt)->subYear(2);
                        $dateofcal= Carbon::parse($caldat)->format('Y-m-d');
                    }
                    else if($inst_frequency=="Three Year" || $inst_frequency=="Three Years"||$inst_frequency=="3 Year" || $inst_frequency=="3 Years" || $inst_frequency=="3 year" || $inst_frequency=="3 years" ||$inst_frequency=="Three year" || $inst_frequency=="Three years" || $inst_frequency=="three year"|| $inst_frequency=="three years")
                    {
                        $caldat=Carbon::createFromFormat('Y-m-d', $duedt)->subYear(3);
                        $dateofcal= Carbon::parse($caldat)->format('Y-m-d');
                    }
                    else if($inst_frequency=="Four Year"||$inst_frequency=="Four Years"||$inst_frequency=="4 Year" || $inst_frequency=="4 Years" || $inst_frequency=="4 year" || $inst_frequency=="4 years" ||$inst_frequency=="Four year" || $inst_frequency=="Four years" || $inst_frequency=="four year"|| $inst_frequency=="four years")
                        {
                        $caldat=Carbon::createFromFormat('Y-m-d', $duedt)->subYear(4);
                        $dateofcal= Carbon::parse($caldat)->format('Y-m-d');
                    }

                    else if($inst_frequency=="Quarterly" || $inst_frequency=="quarterly" || $inst_frequency=="Quarter" || $inst_frequency=="quarter"||$inst_frequency=="3 Months" ||$inst_frequency=="3 months" ||$inst_frequency=="3 month" ||$inst_frequency=="3 Month")
                    {

                        $d = strtotime("-3 months",strtotime($duedt));
                        $dateofcal= date("Y-m-d",$d);
                    }
                    else if($inst_frequency=="Half Year" || $inst_frequency=="Half year" || $inst_frequency=="half year" ||$inst_frequency=="6 Month"||$inst_frequency=="6 months" || $inst_frequency=="6 month" ||$inst_frequency=="6 Months")
                    {

                        $d = strtotime("-6 months",strtotime($duedt));
                        $dateofcal= date("Y-m-d",$d);
                    }
                    else if($inst_frequency=="1.5 Year"||$inst_frequency=="1.5 Years" ||$inst_frequency=="1.5 years"||$inst_frequency=="1.5 year")
                    {
                        $y=Carbon::createFromFormat('Y-m-d', $duedt)->subYear(1);
                        $d = strtotime("-6 months",strtotime($y));
                        $dateofcal= date("Y-m-d",$d);
                    }
                    else
                    {
                        $caldat=Carbon::createFromFormat('Y-m-d', $duedt)->subYear($freq);
                        $dateofcal= Carbon::parse($caldat)->format('Y-m-d');
                    }

            }
            else
            {
                $inst_status = NULL;
                $dateofcal = NULL;
                $duedt = NULL;
            }
            //$c=date('Y-m-d');
            $instrumentno = preg_replace('/[^A-Za-z0-9\-]/', '', $inst_no);

            $data  = array(
                         'Instrument_no'=>$instrumentno,
                         'Instrument_desc'=>$inst_desc,
                         'Instru_Manufacturer'=>$inst_manu,
                         'Department' =>  $department,
                         'Holder'=>$inst_holder,
                         'Capacity'=>$inst_capacity,
                         'Type'=>trim(strtolower($inst_type)),
                         'Calib_procedure'=>$inst_procedure,
                         'Agency'=>$inst_agency,
                         'Frequency'=>trim($inst_frequency),
                         'standard_edition'=>$inst_standard_edition,
                         'acceptance_criteria'=>$inst_acceptance_criteria,
                         'Status'=>$inst_status,
                         'Duedate'=>$duedt,
                         'dateofcal'=>$dateofcal,
                         'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                         'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            );

            DB::table('calibration_instruments')->insert($data);
            $insid=DB::table('calibration_instruments') ->select('id')->where('Instrument_no',$instrumentno)->first()->id;

            //$data1  = array('Instrument_no'=>$inst_no,'Status'=>$inst_status,'Duedate'=>$due);
            $data1  = array(
                       'Instrument_no'=>$instrumentno,
                       'Duedate'=>$duedt,
                       'Dateofcal'=>$dateofcal,
                       'Status'=>$inst_status,
                       'instrument_id'=>$insid,
                     );

            $data2 = array(
                       'Instrument_no'=>$instrumentno,
                       'Acc_error' => 'novalue',
                       'M_instru' => 'novalue',
                       'Certificate_no' => 'novalue',
                       'M_reading' => 'novalue',
                       'Instrument_reading' => 'novalue',
                       'Error' => 'novalue',
                       'Remarks' => 'novalue',
                       'Report_no' => 'novalue',
                       'Adj_made' => 'novalue',
                       'Calibrated_by' => 'novalue',
                       'Verified_by' => 'novalue',
                       'Calibrated_by' => 'novalue',
                       'Duedate'=>$duedt,
                       'Dateofcal'=>$dateofcal,
                       'Status'=>$inst_status,
                       'instrument_id'=>$insid,
                    );

            if(($inst_type =="Internal") || ($inst_type =="INTERNAL") || ($inst_type =="internal"))
            {
                DB::table('calibration_internal')->insert($data2);
            }
            else
            {
                DB::table('calibration_external')->insert($data1);

                $name = $req->input('externalfiles');

                if($req->hasFile('externalfiles'))
                {

                    $image_array=$req->file('externalfiles');
                    $array_len = count($image_array);
                    $name=[];
                    for($i=0; $i<$array_len; $i++)
                    {
                            $image_size = $image_array[$i]->getClientOriginalName();
                            $image_ext = $image_array[$i]->getClientOriginalExtension();
                            $new_image_name = $image_size;
                            $destination_path = public_path('storage/calibration');
                            $image_array[$i]->move($destination_path,$new_image_name);
                            //read the datas into the array
                            $name[]= $new_image_name;
                    }
                    foreach($name as $f)
                    {
                        $a[]=$f;
                    }
                    $cer=implode(',',$name);

                }
                else
                {
                    $cer="NULL";
                }

                if($cer!="NULL")
                {

                    $expl = explode(',',$cer);

                    foreach($expl as $cer)
                    {
                        $data1  = array('Filename' =>  $cer,'Instrument_no'=>$instrumentno,'Type'=>trim(strtolower($inst_type)),'instrument_id'=>$insid,);
                        DB::table('calibration_externalfiles')->insert($data1);
                        DB::table('calibration_instruments')->where('Instrument_no',$instrumentno)
                                                            ->update(['extfile'=>$cer]);

                    }
                }
            }
           //  DB::table('calibration_externalfiles')->insert(['instrument_no'=>$inst_no,'Type'=>$inst_type,'Filename'=>$inst_file1]);
           Session::flash('msg',"Details added successfully");
           return redirect('/user/calibration/addinstruments');

        }
    }

    //store  instruments
    public function storeinstruments(Request $request)
    {
        $target_pages = ModuleBladePageDetail::where('module_name','calibration')
            ->where('route_path','user.calibration.addinstruments')
            ->pluck('id')
            ->map(function($id) { return (string)$id; })
            ->toArray();

        $dynamicFieldValidation = CalibrationFormConfigurationHelper::getCalibrationConfigurationPostMethodRules($request->type, $request->calibration_field_rules);

        if ($request->type === 'external') {
            $staticFieldValidationRules = [
                'department_ext'    => 'required',
                'instfrequency_ext' => 'required',
                'calibrated_ext'    => ['required', 'in:yes,no'],
                'dateofcalibration_ext'       => 'required_if:instrument_calibrated_external,yes',
            ];
            $rules = array_merge($staticFieldValidationRules, $dynamicFieldValidation['rules']);

            $staticFieldValidationMessages = [
                // 'instno_ext.max'             => 'The Instrument Number may not be greater than 250 characters.',
                'department_ext.required'    => 'The Department field is required.',
                'instfrequency_ext.required' => 'The Frequency field is required.',
                'calibrated_ext.required'    => 'Please choose if instrument is calibrated or not',
                'dateofcalibration_ext.required_if'    => 'The date of calibration field is mandatory, if instrument is calibrated',
            ];
            $message = array_merge($staticFieldValidationMessages, $dynamicFieldValidation['messages']);

            $validator = Validator::make($request->all(), $rules, $message);

            if ($validator->fails()) {
                $models = DB::table('modenadisable')->First();
                return redirect()->back()->withErrors($validator)->withInput()->with('model', $models);
            } else {
                $inst_frequency = $request->input('instfrequency_ext');
                $dateOfCalibrationExt        = $request->input('dateofcalibration_ext');

                if ($request->input('calibrated_ext') == 'yes') {
                    $inst_status = 'Calibrated';
                    $dateofcal       = Carbon::parse($dateOfCalibrationExt)->format('Y-m-d');
                    $duedt = date('Y-m-d', strtotime($dateofcal . ' + ' . $inst_frequency));;
                } else {
                    $inst_status = NULL;
                    $dateofcal   = NULL;
                    $duedt       = NULL;
                }
                $exCustomFields = CustomField::where('module_name', 'calibration')->where('sub_module','external')
                    ->where(function ($query) use ($target_pages) {
                        foreach ($target_pages as $pageId) {
                            $query->orWhereJsonContains('target_pages', $pageId);
                        }
                    })->where('deleted',false)
                    ->whereJsonLength('target_pages', '>', 0)
                    ->get();
                $fieldForm = $this->getCustomFieldFormData($exCustomFields,$request);
                if ($request->hasFile('externalfiles')) {
                    $image_array    = $request->file('externalfiles');
                    $array_len      = count($image_array);
                    $ext_file_names = [];
                    for ($i = 0; $i < $array_len; $i++) {
                        $image_size       = $image_array[$i]->getClientOriginalName();
                        $image_ext        = $image_array[$i]->getClientOriginalExtension();
                        $new_image_name   = $image_size;
                        $destination_path = public_path('storage/calibration');
                        $image_array[$i]->move($destination_path, $new_image_name);
                        //read the datas into the array
                        $ext_file_names[] = $new_image_name;
                    }
                    $external_file_details = implode(',', $ext_file_names);
                } else {
                    $external_file_details = NULL;
                }
                // $instrumentno = preg_replace('/[^A-Za-z0-9\-]/', '', $request->input('instno_ext'));
                $instrumentno = $request->input('instno_ext');
                $data         = array(
                    'Instrument_no'       => $instrumentno,
                    'Instrument_desc'     => $request->input('instdesc_ext'),
                    'Instru_Manufacturer' => $request->input('instmanu_ext'),
                    'Department'          => $request->input('department_ext'),
                    'Holder'              => $request->input('insttholder_ext'),
                    'Capacity'            => $request->input('instcapacity_ext'),
                    'Type'                => 'external',
                    'Frequency'           => trim($inst_frequency),
                    'Agency'              => $request->instagency_ext,
                    'Status'              => $inst_status,
                    'Duedate'             => $duedt,
                    'dateofcal'           => $dateofcal,
                    'extfile'             => $external_file_details,
                    'least_count'         => $request->input('least_count_ext'),
                    'date_of_first_use'   => $request->input('date_of_first_use_ext'),
                    'Calib_procedure'     => $request->procedure_ext,
                    'standard_edition'    => $request->standard_edition_ext,
                    'acceptance_criteria' => $request->acceptance_criteria_ext,
                    'calibration_field_rules' => $request->input('calibration_field_rules') ?? null,
                );
                Calibration_instrument::create($data);
                $latest_record = Calibration_instrument::latest('id')->first();
                $recent_id     = $latest_record->id;
                $ext_datas     = array(
                    'Instrument_no' => $instrumentno,
                    'Duedate'       => $duedt,
                    'Dateofcal'     => $dateofcal,
                    'Status'        => $inst_status,
                    'instrument_id' => $recent_id,
                );
                $ext_datas = array_merge($ext_datas, $fieldForm);
                $model = new CalibrationExternal();
                $model->loadCustomFields();
                $model->fill($ext_datas);
                $model->save();
                if ($external_file_details != null) {
                    $file_attachments = explode(',', $external_file_details);
                    foreach ($file_attachments as $file_attachment) {
                        $extfiles                = new CalibrationExternalfiles();
                        $extfiles->Instrument_no = $instrumentno;
                        $extfiles->Type          = 'external';
                        $extfiles->Filename      = $file_attachment;
                        $extfiles->instrument_id = $recent_id;
                        $extfiles->save();
                    }
                }
                $models = DB::table('modenadisable')->First();
                return redirect()->route('calibration.add.instruments')->with('message', 'Details added successfully');
            }
        } else if ($request->type === 'internal') {
            $staticFieldValidationRules = [
                'department_int'    => 'required',
                'instfrequency_int' => 'required',
                'calibrated_int'    => ['required', 'in:yes,no'],
                'dateofcalibration_int'       => 'required_if:instrument_calibrated_internal,yes',
            ];
            $rules = array_merge($staticFieldValidationRules, $dynamicFieldValidation['rules']);

            $staticFieldValidationMessages = [
                // 'instno_int.max'             => 'The Instrument Number may not be greater than 250 characters.',
                'department_int.required'    => 'The Department field is required.',
                'instfrequency_int.required' => 'The Frequency field is required.',
                'calibrated_int.required'    => 'Please choose if instrument is calibrated or not',
                'dateofcalibration_int.required_if'    => 'The date of calibration field is mandatory, if instrument is calibrated',
            ];
            $message = array_merge($staticFieldValidationMessages, $dynamicFieldValidation['messages']);

            $validator = Validator::make($request->all(), $rules, $message);
            if ($validator->fails()) {
                $models = DB::table('modenadisable')->First();
                return redirect()->back()->withErrors($validator)->withInput()->with('model', $models);
            } else {
                $inCustomFields = CustomField::where('module_name', 'calibration')->where('sub_module','internal')
                    ->where(function ($query) use ($target_pages) {
                        foreach ($target_pages as $pageId) {
                            $query->orWhereJsonContains('target_pages', $pageId);
                        }
                    })->where('deleted',false)
                    ->whereJsonLength('target_pages', '>', 0)
                    ->get();
                $fieldForm = $this->getCustomFieldFormData($inCustomFields,$request);
                $inst_frequency = $request->input('instfrequency_int');
                $dateOfCalibrationInt        = $request->input('dateofcalibration_int');

                if ($request->input('calibrated_int') == 'yes') {
                    $inst_status = 'Calibrated';
                    $dateofcal       = Carbon::parse($dateOfCalibrationInt)->format('Y-m-d');
                    $duedt = date('Y-m-d', strtotime($dateofcal . ' + ' . $inst_frequency));
                } else {
                    $inst_status = NULL;
                    $dateofcal   = NULL;
                    $duedt       = NULL;
                }
                // $instrumentno = preg_replace('/[^A-Za-z0-9\-]/', '', $request->input('instno_int'));
                $instrumentno = $request->input('instno_int');
                $data         = array(
                    'Instrument_no'       => $instrumentno,
                    'Instrument_desc'     => $request->input('instdesc_int'),
                    'Instru_Manufacturer' => $request->input('instmanu_int'),
                    'Department'          => $request->input('department_int'),
                    'Holder'              => $request->input('insttholder_int'),
                    'Capacity'            => $request->input('instcapacity_int'),
                    'Type'                => 'internal',
                    'Frequency'           => trim($inst_frequency),
                    'Calib_procedure'     => $request->procedure_int,
                    'standard_edition'    => $request->standard_edition_int,
                    'acceptance_criteria' => $request->acceptance_criteria_int,
                    'Status'              => $inst_status,
                    'Duedate'             => $duedt,
                    'dateofcal'           => $dateofcal,
                    'least_count'         => $request->input('least_count_int'),
                    'date_of_first_use'   => $request->input('date_of_first_use_int'),
                    'calibration_field_rules' => $request->calibration_field_rules ?? null,
                );
                Calibration_instrument::create($data);
                $latest_record = Calibration_instrument::latest('id')->first();
                $recent_id     = $latest_record->id;
                $int_datas     = array(
                    'Instrument_no'      => $instrumentno,
                    'Acc_error'          => '--',
                    'M_instru'           => '--',
                    'Certificate_no'     => '--',
                    'M_reading'          => '--',
                    'Instrument_reading' => '--',
                    'Error'              => '--',
                    'Remarks'            => '--',
                    'Report_no'          => '--',
                    'Adj_made'           => '--',
                    'Calibrated_by'      => '--',
                    'Verified_by'        => '--',
                    'Duedate'            => $duedt,
                    'Dateofcal'          => $dateofcal,
                    'Status'             => $inst_status,
                    'instrument_id'      => $recent_id,
                );
                $int_datas = array_merge($int_datas, $fieldForm);
                // CalibrationInternal::create($int_datas);
                $model = new CalibrationInternal();
                $model->loadCustomFields();
                $model->fill($int_datas);
                $model->save();
                $models = DB::table('modenadisable')->First();
                return redirect()->route('calibration.add.instruments')->with('message', 'Details added successfully');
            }
        }
    }
    private function getCustomFieldFormData($CustomFields,$request,$data=null,$index=null){
        $fieldForm = [];
        foreach ($CustomFields as $customField) {
            $baseFieldName = Str::slug($customField->field_name, '_');
            $fieldName = $index ? $baseFieldName . $index : $baseFieldName;
            if ($request->hasFile($fieldName)) {
                $files = $request->file($fieldName);
                $filenames = [];
                $existingFiles = [];
                if (!empty($data) && isset($data->{$baseFieldName})) {
                    $existingValue = $data->{$baseFieldName};
                    if (is_string($existingValue)) {
                        $decoded = json_decode($existingValue, true);
                        $existingFiles = is_array($decoded) ? $decoded : [];
                    } elseif (is_array($existingValue)) {
                        $existingFiles = $existingValue;
                    }
                }
                if (is_array($files)) {
                    foreach ($files as $file) {
                        if ($file->isValid()) {
                            $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                            $file->move(public_path('storage/calibration'), $filename);
                            $filenames[] = $filename;
                        }
                    }
                    $allFiles = array_merge($existingFiles, $filenames);
                    $fieldForm[$baseFieldName] = json_encode($allFiles);
                } elseif ($files->isValid()) {
                    $filename = time() . '_' . uniqid() . '_' . $files->getClientOriginalName();
                    $files->move(public_path('storage/calibration'), $filename);
                    $fieldForm[$baseFieldName] = json_encode([$filename]);
                } else {
                    $fieldForm[$baseFieldName] = null;
                }

            } elseif ($request->has($fieldName)) {
                if(is_array($request->input($fieldName))) {
                    $fieldForm[$baseFieldName] = json_encode($request->input($fieldName));
                } else {
                    $fieldForm[$baseFieldName] = $request->input($fieldName);
                }
            } else {
                $fieldForm[$baseFieldName] = null;
            }
        }
        return $fieldForm;
    }
    // internal report
    public  function internal(Request $req)
    {
        $departments=DB::table('departments')->get();
        if (isset($_GET['val']))
        {
            $id=$_GET['val'];
            $id = explode(',',$_GET['val']);
            $m=count($id);
            $n=0;
            foreach($id as $d)
            {
               if($n<$m)
               {

                  $data[]=DB::table('calibration_instruments')->where('id',$d)->first();

               }

                $n=$n+1;
            }
        }
        $record = CalibrationInternal::where('instrument_id', $id)->first();
        $target_pages = ModuleBladePageDetail::where('module_name','calibration')
            ->where('sub_module','internal')
            ->where('route_path','user/calibration/internal')
            ->where('is_target_page',true)
            ->pluck('id')
            ->map(function($id) { return (string)$id; })
            ->toArray();
        $inCustomFields = CustomField::where('module_name', 'calibration')->where('sub_module','internal')
            ->where(function ($query) use ($target_pages) {
                foreach ($target_pages as $pageId) {
                    $query->orWhereJsonContains('target_pages', $pageId);
                }
            })->where('deleted',false)
            ->whereJsonLength('target_pages', '>', 0)
            ->get();
        //Field Index
        $internalFieldIndex = ModuleBladePageDetail::where('module_name','calibration')->where('sub_module','internal')
            ->where('route_path','user/calibration/internal')->where('is_target_page',true)->value('field_index');
        $defaultFieldIndex = $internalFieldIndex = $internalFieldIndex ? array_values(json_decode($internalFieldIndex, true)) : [];
        $totalCount = count($internalFieldIndex);
        $fieldIndex = $internalFieldIndex;
        sort($fieldIndex,SORT_NUMERIC);
        $inCount = $inCustomFields->count();
        if($inCount>0){
            $inCountIndex = array_slice($fieldIndex,-$inCount);
            $internalFieldIndex = array_slice($fieldIndex, 0, $totalCount - $inCount);
        }else{
            $inCountIndex = [];
        }
        //End Field Index
        $internalCustomFields = getCustomFieldsView($inCustomFields,$inCountIndex,$record);

        $id=DB::select('select  id from calibration_internal order by id desc limit 1');
        $verifiers= DB::table('calibrationreviewers')->get();
        $models=DB::table('modenadisable')->First();
        return view('user/calibration/internal',compact('internalCustomFields','internalFieldIndex','defaultFieldIndex')) ->withData($data)->withVerifiers($verifiers)->with('id',$id)->with('model',$models)->with('departments',$departments);

    }

    //insert internal report data
    public function addInternalReport(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'Instrument_no'     => 'required',
            'ins_accerror'      => 'required',
            'ins_master'        => 'required',
            'ins_certificateno' => 'required',
            'ins_adj'           => 'required',
            'ins_verby'         => 'required',
            'ins_date'          => 'required',
        ]);
        if ($validator->fails()) {
            $models = DB::table('modenadisable')->First();
            return back()->withErrors($validator)->withInput()->with('model', $models);
        } else {
            $instrid    = $req->instrid;
            $inst_no    = $req->input('Instrument_no');
            $inst_acc   = $req->input('ins_accerror');
            $inst_m     = $req->input('ins_master');
            $inst_c     = $req->input('ins_certificateno');
            $inst_repno = $req->input('ins_report');
            $inst_adj   = $req->input('ins_adj');
            $inst_calby = $req->input('ins_calby');
            $inst_verby = $req->input('ins_verby');
            foreach ($inst_verby as $a) {
                $b[] = $a;
            }
            $verifiedby = implode(',', $b);


            if ($req->input('ins_remarks') == NULL) {
                $inst_remarks = "No Remarks";
            } else {
                $inst_remarks = $req->input('ins_remarks');
            }

            $inst_date      = $req->input('ins_date');
            $caldate        = Carbon::parse($inst_date)->format('Y-m-d');
            $inst_frequency = $req->freq;

            $status = 'Calibrated';
            $duedt   = date('Y-m-d', strtotime($caldate . ' + ' . $inst_frequency));
            $target_pages = ModuleBladePageDetail::where('module_name','calibration')
                ->where('sub_module','internal')
                ->where('route_path','user/calibration/internal')
                ->where('is_target_page',true)
                ->pluck('id')
                ->map(function($id) { return (string)$id; })
                ->toArray();
            $inCustomFields = CustomField::where('module_name', 'calibration')->where('sub_module','internal')
                ->where(function ($query) use ($target_pages) {
                    foreach ($target_pages as $pageId) {
                        $query->orWhereJsonContains('target_pages', $pageId);
                    }
                })->where('deleted',false)
                ->whereJsonLength('target_pages', '>', 0)
                ->get();
            $tempData = CalibrationInternal::where('instrument_id', $instrid)->first();
            $fieldForm = $this->getCustomFieldFormData($inCustomFields,$req,$tempData);
            foreach ($req->doc as $key => $value) {

                $data = array('Instrument_no'      => $inst_no, 'Acc_error' => $inst_acc, 'M_instru' => $inst_m, 'Certificate_no' => $inst_c,
                              'Remarks'            => $inst_remarks, 'Report_no' => $inst_repno, 'Adj_made' => $inst_adj, 'instrument_id' => $instrid,
                              'Calibrated_by'      => $inst_calby, 'Verified_by' => $verifiedby, 'Dateofcal' => $inst_date, 'Duedate' => $duedt, 'Status' => $status,
                              'Instrument_reading' => $value['ins_reading'], 'M_reading' => $value['ins_mreading'], 'Error' => $value['ins_error']);
                DB::table('calibration_internal')->insert(array_merge($data, $fieldForm));

            }

            $originalCalibration = Calibration_instrument::find($instrid);
            $originalStatus      = $originalCalibration->Status ? $originalCalibration->Status : Calibration_instrument::NOT_CALIBRATED_STATUS;
            DB::table('calibration_instruments')->where('id', $instrid)->update(['Status' => $status, 'dateofcal' => $inst_date, 'Duedate' => $duedt]);
            $calibration = Calibration_instrument::find($instrid);
            if ($originalStatus != $status) {
                $this->sendMailWhenCalibrationStatusHasBeenChange($calibration, $originalStatus, $status);
            }

            return redirect()->back()->with('msg', 'Details added successfully');
        }
    }
    //update external calibration status
    public function viewstatus(Request $req)
    {
        $models      = DB::table('modenadisable')->First();
        $id          = $req->get('val');
        $calibration = Calibration_instrument::findOrFail($id);
        $record = CalibrationExternal::where('instrument_id', $id)->first();
        $target_pages = ModuleBladePageDetail::where('module_name','calibration')
            ->where('sub_module','external')
            ->where('route_path','user/calibration/viewstatus')
            ->where('is_target_page',true)
            ->pluck('id')
            ->map(function($id) { return (string)$id; })
            ->toArray();
        $exCustomFields = CustomField::where('module_name', 'calibration')->where('sub_module','external')
            ->where(function ($query) use ($target_pages) {
                foreach ($target_pages as $pageId) {
                    $query->orWhereJsonContains('target_pages', $pageId);
                }
            })->where('deleted',false)
            ->whereJsonLength('target_pages', '>', 0)
            ->get();
        //Field Index
        $externalFieldIndex = ModuleBladePageDetail::where('module_name','calibration')->where('sub_module','external')->where('route_path','user/calibration/viewstatus')
            ->where('is_target_page',true)->value('field_index');
        $defaultFieldIndex = $externalFieldIndex = $externalFieldIndex ? array_values(json_decode($externalFieldIndex, true)) : [];
        $totalCount = count($externalFieldIndex);
        $fieldIndex = $externalFieldIndex;
        sort($fieldIndex,SORT_NUMERIC);
        $exCount = $exCustomFields->count();
        if($exCount>0){
            $exCountIndex = array_slice($fieldIndex,-$exCount);
            $externalFieldIndex = array_slice($fieldIndex, 0, $totalCount - $exCount);
        }else{
            $exCountIndex = [];
        }
        //End Field Index
        $externalCustomFields = getCustomFieldsView($exCustomFields,$exCountIndex,$record);
        return view('user.calibration.updatestatus',compact('externalCustomFields','externalFieldIndex','defaultFieldIndex'))->with('model', $models)->with('calibration', $calibration);
    }

    /**
     * @param Request $req
     * @return Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function updatestatus(Request $req)
    {
        try {
            $tab         = $req->get('tab');
            $insId      = $req->input('instid');
            $calibration = Calibration_instrument::with('departmentdetails')->findOrFail($insId);
            $dateofcal   = $req->input('dateofcal');
            $duedate     = $req->input('duedate');
            $certino     = $req->input('certino');
            if (!$duedate) {
                $dateOfCalibration       = Carbon::parse($dateofcal)->format('Y-m-d');
                $frequency               = strtolower(trim($calibration->Frequency));
                $replaceData = [
                    'one' => '1',
                    'two' => '2',
                    'three' => '3',
                    'four' => '4',
                    'five' => '5',
                    'six' => '6',
                    'seven' => '7',
                    'eight' => '8',
                    'nine' => '9',
                    'ten' => '10'
                ];
                foreach ($replaceData as $key => $value) {
                    $frequency = str_replace($key, $value, $frequency);
                }
                $duedate   = date('Y-m-d', strtotime($dateOfCalibration . ' + ' . $frequency));
            }

            $originStatus = $calibration->Status;
            $status = get_calibration_status_base_on_due_date($duedate);
            $target_pages = ModuleBladePageDetail::where('module_name','calibration')
                ->where('sub_module','external')
                ->where('route_path','user/calibration/viewstatus')
                ->where('is_target_page',true)
                ->pluck('id')
                ->map(function($id) { return (string)$id; })
                ->toArray();
            $exCustomFields = CustomField::where('module_name', 'calibration')->where('sub_module','external')
                ->where(function ($query) use ($target_pages) {
                    foreach ($target_pages as $pageId) {
                        $query->orWhereJsonContains('target_pages', $pageId);
                    }
                })->where('deleted',false)
                ->whereJsonLength('target_pages', '>', 0)
                ->get();
            $tempData = CalibrationExternal::where('instrument_id', $calibration->id)->first();
            $fieldForm = $this->getCustomFieldFormData($exCustomFields,$req,$tempData);
            \Illuminate\Support\Facades\DB::table('calibration_external')->insert(array_merge([
                'Instrument_no' => $calibration->Instrument_no,
                'Duedate'       => $duedate,
                'Dateofcal'     => $dateofcal,
                'Status'        => $status,
                'Report_no'     => $certino,
                'instrument_id' => $calibration->id,
            ],$fieldForm));

            $calibration->update(['Status' => $status, 'Duedate' => $duedate, 'dateofcal' => $dateofcal]);

            \Illuminate\Support\Facades\Session::flash('msg', "Status updated successfully");
            $originalStatus = $originStatus ? $originStatus : Calibration_instrument::NOT_CALIBRATED_STATUS;
            $this->sendMailWhenCalibrationStatusHasBeenChange($calibration, $originalStatus, $status);

            return redirect()->route('calibration.index', ['tab' => $tab, 'rowId' => $insId]);
        } catch (\Exception $exception) {
            Log::error($exception);
            \Illuminate\Support\Facades\Session::flash('errmsg', 'Update the status is failure');
            return redirect()->back();
        }
    }

    //display calibration log data(internal)
    public function cal_log(Request $req)
    {
        if(isset($_GET['val'])){
            $id = $_GET['val'];
            $model=DB::table('modenadisable')->First();
            //$data=DB::table('calibration_instruments') ->select('Instrument_no')->where ('id', $id)->first()->Instrument_no;
            $data=DB::table('calibration_instruments')->where ('id', $id)->First();
            $instr_no=$data->Instrument_no;
            $status = $data->Status;
            $duedate = $data->Duedate;
            $caldate = $data->dateofcal;
            $data1 = DB::table('calibration_internal')
                                 ->select('Report_no','Dateofcal','Status','Duedate')
                                 ->where('instrument_id',$id)
                                 ->distinct()
                                 ->get();
            $data2=DB::table('calibration_instruments')->where('id',$id)->First();
            $status = DB::table('calibration_instruments')
                                        ->where('Status','!=',NULL)
                                        ->where('id',$id)
                                        ->count();
            $data3 = DB::table('calibration_internal')
                                 ->where('instrument_id',$id)
                                 ->distinct()
                                 ->first();
            $display_pages = ModuleBladePageDetail::where('module_name','calibration')
                ->where('route_path','user/calibration/cal_log')
                ->where('is_display_page',true)
                ->pluck('id')
                ->map(function($id) { return (string)$id; })
                ->toArray();
            $CustomFields = CustomField::where('module_name', 'calibration')->where('sub_module','internal')
                ->where(function ($query) use ($display_pages) {
                    foreach ($display_pages as $pageId) {
                        $query->orWhereJsonContains('display_pages', $pageId);
                    }
                })->where('deleted',false)
                ->whereJsonLength('display_pages', '>', 0)
                ->get();
            //Field Index
            $customFieldIndex = ModuleBladePageDetail::where('module_name','calibration')
                ->where('route_path','user/calibration/cal_log')
                ->where('is_display_page',true)->value('field_index');
            $defaultFieldIndex = $customFieldIndex = $customFieldIndex ? array_values(json_decode($customFieldIndex, true)) : [];
            sort($customFieldIndex,SORT_NUMERIC);
            //End Field Index
           return view('user.calibration.report',compact('CustomFields','data3','customFieldIndex','defaultFieldIndex'))->with('data1',$data1)->with('data2',$data2)->with('model',$model)->with('status',$status);
        }
    }

    //display calibration log data(external)
    public function cal_logext(Request $req)
    {
        if(isset($_GET['val'])){
            $id = $_GET['val'];
            $model=DB::table('modenadisable')->First();
            $data3 =DB::table('calibration_instruments')->where('id',$id)->First();
            $data2=DB::table('calibration_external')->where('instrument_id',$id)->get();
            $status = DB::table('calibration_external')
                            ->where('Status','!=',NULL)
                            ->where('instrument_id',$id)
                            ->count();
            $display_pages = ModuleBladePageDetail::where('module_name','calibration')
                ->where('route_path','user/calibration/cal_logext')
                ->where('is_display_page',true)
                ->pluck('id')
                ->map(function($id) { return (string)$id; })
                ->toArray();
            $CustomFields = CustomField::where('module_name', 'calibration')->where('sub_module','external')
                ->where(function ($query) use ($display_pages) {
                    foreach ($display_pages as $pageId) {
                        $query->orWhereJsonContains('display_pages', $pageId);
                    }
                })->where('deleted',false)
                ->whereJsonLength('display_pages', '>', 0)
                ->get();
            $pagename = $CustomFields->map(function ($field) {
                    return Str::slug($field->field_name,'_');
                })->toArray();
            if (!empty($pagename)) {
                $data4 = DB::table('calibration_external')
                    ->select($pagename)
                    ->where('instrument_id', $id)
                    ->distinct()
                    ->first();
            } else {
                $data4 = null;
            }

            //Field Index
            $customFieldIndex = ModuleBladePageDetail::where('module_name','calibration')
                ->where('route_path','user/calibration/cal_log')
                ->where('is_display_page',true)->value('field_index');
            $defaultFieldIndex = $customFieldIndex = $customFieldIndex ? array_values(json_decode($customFieldIndex, true)) : [];
            sort($customFieldIndex,SORT_NUMERIC);
            //End Field Index
           return view('user.calibration.externalreport',compact('CustomFields','data4','customFieldIndex','defaultFieldIndex'))->with('data3',$data3)->with('data2',$data2)->with('status',$status)->with('model',$model);
       }
    }

    //display internal report data
    public function intrep(Request $req)
    {
        if(isset($_GET['val'])){
            $id = $_GET['val'];
            $model=DB::table('modenadisable')->First();
            $instr_no=DB::table('calibration_instruments')->select('Instrument_no')->where ('id', $id)->first()->Instrument_no;
            $data1=DB::table('calibration_internal')
                                 ->select('Report_no','Instrument_no')
                                 ->where('instrument_id',$id)
                                 ->distinct()
                                 ->get();
            $data2 = DB::table('calibration_internal')->where('instrument_id',$id)->get();
            foreach($data2 as $val)
            {
                $report = $val->Report_no;
            }
           return view('user.calibration.internalreport')->with('data1',$data1)->with('model',$model)->with('report',$report);

        }
        else
        {
            return redirect('/admin/dashboard');
        }

    }

    //display internal report data
    public function disprep(Request $req)
    {
        if(isset($_GET['val'])){
            $instt_no = $_GET['val'];
            $repno=$_GET['repno'];
            $model=DB::table('modenadisable')->First();
            $data1=DB::table('calibration_internal')->where('Instrument_no',$instt_no)->get();
            $data2=DB::table('calibration_internal')
            ->join('calibration_instruments','calibration_instruments.Instrument_no','=','calibration_internal.instrument_no')
            ->select('calibration_internal.id','calibration_internal.Report_no','calibration_internal.Remarks','calibration_internal.Dateofcal','calibration_internal.Adj_made','calibration_internal.Calibrated_by','calibration_internal.M_reading','calibration_internal.Instrument_reading','calibration_internal.Error','calibration_internal.Verified_by','calibration_internal.Certificate_no','calibration_internal.Acc_error','calibration_internal.M_instru','calibration_instruments.Instrument_no','calibration_instruments.Holder','calibration_instruments.Instru_Manufacturer','calibration_instruments.Calib_procedure','calibration_instruments.Instrument_desc','calibration_instruments.Duedate','calibration_instruments.Frequency','calibration_instruments.Status','calibration_instruments.Capacity','calibration_instruments.calibration_field_rules')
            ->where('calibration_instruments.Instrument_no',$instt_no)->where('calibration_internal.Report_no',$repno)->get();
            $display_pages = ModuleBladePageDetail::where('module_name','calibration')
                ->where('route_path','user/calibration/disprep')
                ->where('is_display_page',true)
                ->pluck('id')
                ->map(function($id) { return (string)$id; })
                ->toArray();
            $CustomFields = CustomField::where('module_name', 'calibration')->where('sub_module','internal')
                ->where(function ($query) use ($display_pages) {
                    foreach ($display_pages as $pageId) {
                        $query->orWhereJsonContains('display_pages', $pageId);
                    }
                })->where('deleted',false)
                ->whereJsonLength('display_pages', '>', 0)
                ->get();
            $pagename = $CustomFields->map(function ($field) {
                    return Str::slug($field->field_name,'_');
                })->toArray();

            if (!empty($pagename)) {
                $data4 = DB::table('calibration_internal')->select($pagename)
                    ->where('Instrument_no',$instt_no)->distinct()->first();
            } else {
                $data4 = null;
            }
            //Field Index
            $customFieldIndex = ModuleBladePageDetail::where('module_name','calibration')->where('sub_module','internal')
                ->where('route_path','user/calibration/disprep')
                ->where('is_display_page',true)->value('field_index');
            $defaultFieldIndex = $customFieldIndex = $customFieldIndex ? array_values(json_decode($customFieldIndex, true)) : [];
            sort($customFieldIndex,SORT_NUMERIC);
            //End Field Index
           return view('user.calibration.displayreport',compact('data4','CustomFields','customFieldIndex','defaultFieldIndex'))->with('data1',$data1)->with('data2',$data2)->with('model',$model);

        }
    }


    // calibration log pdf for internal
    public function printPDF(Request $req)
    {
        if (isset($_GET['val'])) {
            $id=$_GET['val'];
            $data=DB::table('calibration_instruments') ->select('Instrument_no')->where ('id', $id)->first()->Instrument_no;
            $data1 = DB::table('calibration_internal')
                                ->select('Report_no','Dateofcal','Status','Duedate')
                                ->where('instrument_id',$id)
                                ->distinct()
                                ->get();
            $data2=DB::table('calibration_instruments')->where('id',$id)->First();
            $status = DB::table('calibration_instruments')
                                        ->where('Status','!=',NULL)
                                        ->where('id',$id)
                                        ->count();
            $display_pages = ModuleBladePageDetail::where('module_name','calibration')
                ->where('route_path','user/calibration/cal_log')
                ->where('is_display_page',true)
                ->pluck('id')
                ->map(function($id) { return (string)$id; })
                ->toArray();
            $CustomFields = CustomField::where('module_name', 'calibration')->where('sub_module','internal')
                ->where(function ($query) use ($display_pages) {
                    foreach ($display_pages as $pageId) {
                        $query->orWhereJsonContains('display_pages', $pageId);
                    }
                })->where('deleted',false)
                ->whereJsonLength('display_pages', '>', 0)
                ->get();
            $pagename = $CustomFields->map(function ($field) {
                    return Str::slug($field->field_name,'_');
                })->toArray();
            if (!empty($pagename)) {
                $data3 = DB::table('calibration_internal')
                    ->select($pagename)
                    ->where('instrument_id', $id)
                    ->distinct()
                    ->first();
            } else {
                $data3 = null;
            }
            //Field Index
            $customFieldIndex = ModuleBladePageDetail::where('module_name','calibration')->where('sub_module','internal')
                ->where('route_path','user/calibration/cal_log')
                ->where('is_display_page',true)->value('field_index');
            $customFieldIndex = $customFieldIndex ? array_values(json_decode($customFieldIndex, true)) : [];
            //End Field Index
            //Pending
            $pdf = PDF::loadView('user/calibration/pdf_view_internal',compact('data1','data2','status','CustomFields','data3','customFieldIndex'));
            return $pdf->download('Calibration_log.pdf');
        }
    }


    // calibration log pdf  for external
    public function printPDF_calext(Request $req)
    {
        if (isset($_GET['val'])){
            $id=$_GET['val'];
            $data2=DB::table('calibration_external')->where('instrument_id',$id)->get();
            $data3 =DB::table('calibration_instruments')->where('id',$id)->first();
            $status = DB::table('calibration_external')
                                    ->where('Status','!=',NULL)
                                    ->where('instrument_id',$id)
                                    ->count();
            $display_pages = ModuleBladePageDetail::where('module_name','calibration')
                ->where('route_path','user/calibration/cal_logext')
                ->where('is_display_page',true)
                ->pluck('id')
                ->map(function($id) { return (string)$id; })
                ->toArray();
            $CustomFields = CustomField::where('module_name', 'calibration')->where('sub_module','external')
                ->where(function ($query) use ($display_pages) {
                    foreach ($display_pages as $pageId) {
                        $query->orWhereJsonContains('display_pages', $pageId);
                    }
                })->where('deleted',false)
                ->whereJsonLength('display_pages', '>', 0)
                ->get();
            $pagename = $CustomFields->map(function ($field) {
                    return Str::slug($field->field_name,'_');
                })->toArray();
            if (!empty($pagename)) {
                $data4 = DB::table('calibration_external')
                    ->select($pagename)
                    ->where('instrument_id', $id)
                    ->distinct()
                    ->first();
            } else {
                $data4 = null;
            }
             //Field Index
            $customFieldIndex = ModuleBladePageDetail::where('module_name','calibration')->where('sub_module','external')
                ->where('route_path','user/calibration/cal_logext')
                ->where('is_display_page',true)->value('field_index');
            $customFieldIndex = $customFieldIndex ? array_values(json_decode($customFieldIndex, true)) : [];
            //End Field Index
            //Pending
            $pdf = PDF::loadView('user/calibration/pdf_view',compact('data2','data3','status','data4','CustomFields','customFieldIndex'));
            return $pdf->download('Calibration_log.pdf');
        }

    }

    public function printInternalrep(Request $req)
    {
        // This  $data array will be passed to our PDF blade
        //    $data = DB::table("calibration_instruments")->limit(10)->get();
        $model=DB::table('modenadisable')->First();
        if (isset($_GET['val'])){
            $d=$_GET['val'];
            $repno=$_GET['repno'];
            // dd($id);

            // $data=DB::table('calibration_instruments') ->select('Instrument_no')->where ('id', $id)->first()->Instrument_no;
            // $d=$data;
            $data1=DB::table('calibration_internal')
                ->join('calibration_instruments','calibration_instruments.Instrument_no','=','calibration_internal.instrument_no')
                ->select('calibration_internal.id','calibration_internal.Report_no','calibration_internal.Remarks','calibration_internal.Acc_error','calibration_internal.M_instru',
                'calibration_internal.Adj_made','calibration_internal.Dateofcal','calibration_internal.Certificate_no','calibration_internal.Verified_by','calibration_internal.Calibrated_by',
                'calibration_internal.M_reading','calibration_internal.Instrument_reading','calibration_internal.Error',
                'calibration_instruments.Instrument_no','calibration_instruments.Holder','calibration_instruments.Instrument_desc',
                'calibration_instruments.Calib_procedure','calibration_instruments.Duedate','calibration_instruments.Frequency',
                'calibration_instruments.Status','calibration_instruments.Capacity')
                ->where('calibration_instruments.Instrument_no',$d)->where('calibration_internal.Report_no',$repno)->get();
            $display_pages = ModuleBladePageDetail::where('module_name','calibration')
                ->where('route_path','user/calibration/disprep')
                ->where('is_display_page',true)
                ->pluck('id')
                ->map(function($id) { return (string)$id; })
                ->toArray();
            $CustomFields = CustomField::where('module_name', 'calibration')->where('sub_module','internal')
                ->where(function ($query) use ($display_pages) {
                    foreach ($display_pages as $pageId) {
                        $query->orWhereJsonContains('display_pages', $pageId);
                    }
                })->where('deleted',false)
                ->whereJsonLength('display_pages', '>', 0)
                ->get();
            $pagename = $CustomFields->map(function ($field) {
                    return Str::slug($field->field_name,'_');
                })->toArray();
            if (!empty($pagename)) {
                $data4 = DB::table('calibration_internal')
                    ->select($pagename)
                    ->where('Instrument_no',$d)
                    ->distinct()
                    ->first();
            }else {
                $data4 = null;
            }
             //Field Index
            $customFieldIndex = ModuleBladePageDetail::where('module_name','calibration')->where('sub_module','internal')
                ->where('route_path','user/calibration/disprep')
                ->where('is_display_page',true)->value('field_index');
            $customFieldIndex = $customFieldIndex ? array_values(json_decode($customFieldIndex, true)) : [];
            //End Field Index
            $pdf = PDF::loadView('user/calibration/pdf_view1',compact('data1','CustomFields','data4','customFieldIndex'));
        }
       return $pdf->download('InternalCalibration_report.pdf');

    }

    //multiple update

    public function update1()
        {


            $department=DB::table('departments')->get();

            $freq=DB::table('calibration_frequency')->get();

            if (isset($_GET['val']))
            {
                $id=$_GET['val'];

                $id = explode(',',$_GET['val']);

                $m=count($id);

                $n=0;
                foreach($id as $d)
                {
                   if($n<$m)
                   {

                         $data[]=DB::table('calibration_instruments')->where('Instrument_no',$d)->first();

                   }

                    $n=$n+1;
                }


            }


            $models=DB::table('modenadisable')->First();
     return view('user/calibration/updatecalibrations') ->withData($data)->with('id',$id)->with('model',$models)->with('department',$department)->with('freq',$freq);



        }

    public function update2(Request $req)
    {

        $department=DB::table('departments')->get();


            // $id=$req->input('posted');
            // dd($id);
            $m=count($req->id);
                //  dd($m);

            // $i=$req->input('revno');
            // $revdate=$req->input('erevdate');
           // date_default_timezone_set('Asia/Kuwait');
             $ins_no = $req->input('id');

            // $ins_desc = $req->input('ins_desc')  ;
            $ins_dept=$req->input('Department');

            $ins_holder=$req->input('Holder');
            $ins_capacity=$req->input('Capacity');
            $ins_type=$req->input('Type');

            $ins_procedure=$req->input('Procedure');
            $ins_frequency=$req->input('Frequency');
            $ins_agency=$req->input('Agency');

            $sno=$req->input('sno');
           //  dd($sno);
            $m=count($sno);
            //  dd($m);
            $n=0;
            foreach($sno as $d)
            {
                //   dd($sno);
               if($n<$m)
               {


                    $data2[]=DB::table('calibration_instruments')->where('Instrument_no',$d)->first();


               }
                    //    dd($sno);
               //nn $n=$n+1;
            }

            $n=0;
         //  dd($data2);
            foreach($data2 as $dat){


                foreach($sno as $s){
                    if($dat->Instrument_no==$s && $n<$m){


                            DB::table('calibration_instruments') ->where('Instrument_no',$s)
                                       ->update(array(
                                           'Instrument_no'=>$req->id[$n],
                                           'Department'=>$req->Department[$n],
                                            'Holder'=>$req->Holder[$n],
                                            'Capacity'=>$req->Capacity[$n],
                                           'Type'=>$req->Type[$n],
                                           'Calib_procedure'=>$req->Calib_procedure[$n],
                                           'Frequency'=>$req->Frequency[$n],

                                           'Agency'=>$req->Agency[$n],


                                                       ));



                        }
                    }

                    $n=$n+1;
                }


      return redirect('/user/calibrationmgmt');


    }

    //filtr data- masterlist of instruments

    public function filtr(Request $req)

    {

        $departments = DB::table('departments')->get();
        // $agency=DB::table('calibration_instruments')->select('Agency')->get();
        $agency=DB::select('select distinct Agency from calibration_instruments');

        $data=DB::table('calibration_instruments')->where(function($query) use ($req)
        {
            $query

                  ->where('department', '=',$req->input('department'));
        })
        ->orwhere(function($query1) use ($req)
        {
            $query1->where('Status', '!=','null')
                   ->where('Status', '=',$req->input('Status'))
                   ->whereIn('Status',['Calibrated','Near to expiry','Expired']);
        })
        ->orwhere(function($query2) use ($req)
        {
            $query2->where('Agency', '!=','null')
                   ->where('Agency', '=',$req->input('agency'));
        })
        ->orwhere(function($query3) use ($req)
        {
            $query3->where('Type', '!=','null')
                   ->where('Type', '=',$req->input('type'))
                   ->whereIn('Type',['External','Internal']);
        })
                                ->paginate(100)
                                ->setpath('');


        $data->appends(array(
            'department'=>$req->input('department'),
            'Status'=>$req->input('Status'),
            'Type'=>$req->input('type'),
            'Agency'=>$req->input('agency'),
        ));
        $models=DB::table('modenadisable')->First();
        return view('user.calibration.register')->with('model',$models)->with('data',$data)->withDepartments($departments)->withAgency($agency);
    }

    //edit instrument details
    public function editins(){
        if(isset($_GET['val'])){
            $id=$_GET['val'];
            $departments = DB::table('departments')->get();
            $freq = DB::table('calibration_frequency')->get();
            $reviewers = DB::table('calibrationreviewers')->pluck('email');
            $caldetails = DB::table('calibration_instruments')->where('id',$id)->First();
            $dateofcal = $caldetails->dateofcal;
            // $report_no_ext = DB::table('calibration_external')->where('instrument_id',$caldetails->id)->value('Report_no');
            $report_no_ext = DB::table('calibration_external')
                                               ->where('instrument_id',$caldetails->id)
                                               ->latest('id')
                                               ->value('Report_no');
            $status = $caldetails->Status;
            $inst_frequency = trim($caldetails->Frequency);
            $models = DB::table('modenadisable')->First();
            $target_pages = ModuleBladePageDetail::where('module_name','calibration')
                ->where('route_path','user/calibration/editins')
                ->where('is_target_page',true)
                ->pluck('id')
                ->map(function($id) { return (string)$id; })
                ->toArray();
            $exCustomFields = CustomField::where('module_name', 'calibration')->where('sub_module','external')
                ->where(function ($query) use ($target_pages) {
                    foreach ($target_pages as $pageId) {
                        $query->orWhereJsonContains('target_pages', $pageId);
                    }
                })->where('deleted',false)
                ->whereJsonLength('target_pages', '>', 0)
                ->get();
            $inCustomFields = CustomField::where('module_name', 'calibration')->where('sub_module','internal')
                ->where(function ($query) use ($target_pages) {
                    foreach ($target_pages as $pageId) {
                        $query->orWhereJsonContains('target_pages', $pageId);
                    }
                })->where('deleted',false)
                ->whereJsonLength('target_pages', '>', 0)
                ->get();
            //Field Index
            $externalFieldIndex = ModuleBladePageDetail::where('module_name','calibration')->where('sub_module','external')->where('route_path','user/calibration/editins')
                ->where('is_target_page',true)->value('field_index');
            $exdefaultFieldIndex = $externalFieldIndex = $externalFieldIndex ? array_values(json_decode($externalFieldIndex, true)) : [];
            $totalCount = count($externalFieldIndex);
            $fieldIndex = $externalFieldIndex;
            sort($fieldIndex,SORT_NUMERIC);
            $exCount = $exCustomFields->count();
            if($exCount>0){
                $exCountIndex = array_slice($fieldIndex,-$exCount);
                $externalFieldIndex = array_slice($fieldIndex, 0, $totalCount - $exCount);
            }else{
                $exCountIndex = [];
            }

            $internalFieldIndex = ModuleBladePageDetail::where('module_name','calibration')->where('sub_module','internal')->where('route_path','user/calibration/editins')
                ->where('is_target_page',true)->value('field_index');
            $indefaultFieldIndex = $internalFieldIndex = $internalFieldIndex ? array_values(json_decode($internalFieldIndex, true)) : [];
            $totalCount = count($internalFieldIndex);
            $fieldIndex = $internalFieldIndex;
            sort($fieldIndex,SORT_NUMERIC);
            $inCount = $inCustomFields->count();
            if($inCount>0){
                $inCountIndex = array_slice($fieldIndex,-$inCount);
                $internalFieldIndex = array_slice($fieldIndex, 0, $totalCount - $inCount);
            }else{
                $inCountIndex = [];
            }
            //End Field Index
            if($caldetails->Type == 'external') {
                $record = CalibrationExternal::where('instrument_id', $id)->latest('id')->first();
                $defaultCustomFields = getCustomFieldsView($exCustomFields,$exCountIndex,$record,'table');
                $CustomFields = getCustomFieldsView($inCustomFields,$inCountIndex,null,'table');
                $defaultCountIndex = $externalFieldIndex;
                $savedOrder = $exdefaultFieldIndex;
            } else {
                $record = CalibrationInternal::where('instrument_id', $id)->latest('id')->first();
                $defaultCustomFields = getCustomFieldsView($inCustomFields,$inCountIndex,$record,'table');
                $CustomFields = getCustomFieldsView($exCustomFields,$exCountIndex,null,'table');
                $defaultCountIndex = $internalFieldIndex;
                $savedOrder = $indefaultFieldIndex;
            }
            $exCustomFields = $exCustomFields->mapWithKeys(function ($field) {
                return [Str::slug($field->field_name, '_') => (int) $field->mandatory];
            })->toJson();

            $inCustomFields = $inCustomFields->mapWithKeys(function ($field) {
                return [Str::slug($field->field_name, '_') => (int) $field->mandatory];
            })->toJson();
            return view('user.calibration.editinstruments',compact('CustomFields','defaultCustomFields','exCustomFields',
                'inCustomFields','internalFieldIndex','externalFieldIndex','defaultCountIndex','savedOrder'))
                ->with('model',$models)->with('cal',$caldetails)->with('freq',$freq)
                ->with('dept',$departments)->with('status',$status)->with('freqdb',$inst_frequency)
                ->with('report_no_ext',$report_no_ext);
        }

    }

    /**
     * Check if the instrument number is already exists
     *
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function checkInstrumentNo(Request $request): JsonResponse
    {
        $instrumentno = $request->input('instrumentno');
        $id = $request->input('id');
        $instrument = Calibration_instrument::where('Instrument_no', $instrumentno)
            ->where(function ($query) use ($id) {
                if ($id) {
                    $query->where('id', '!=', $id);
                }
            })
            ->count();
        if ($instrument) {
            return response()->json(['success' => false, 'message' => 'The instrument number has already been taken.']);
        } else {
            return response()->json(['success' => true, 'message' => 'Instrument number is available']);
        }
    }

    public function addcalibrationattachments(Request $request)
    {
        $calid = $request->input('calid');
        $caldetails = DB::table('calibration_instruments')->where('id',$calid)->first();

        if($request->TotalImages > 0)
        {
            for ($x = 0; $x < $request->TotalImages; $x++)
            {
                if ($request->hasFile('images'.$x))
                {
                    $file      = $request->file('images'.$x);
                    $filename  = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    // $picture   = date('His').'-'.$filename;
                    $picture   = date('His').$filename;
                    $destination_path = public_path('storage/calibration');
                    $file->move($destination_path,$picture);
                    $name[]= $picture;
                }
            }
            foreach($name as $f)
            {
                $a[]=$f;
            }
            $cer=implode(',',$name);

            DB::table('calibration_instruments')->where('id',$calid)
                                                                ->update(['extfile'=>$cer]);
        }
        else
        {
            $cer="NULL";
        }


        if($cer!="NULL")
        {
            $expl = explode(',',$cer);

            foreach($expl as $cer)
            {
                $data1  = array('Filename' =>  $cer,'Instrument_no'=>$caldetails->Instrument_no,'Type'=>trim(strtolower($caldetails->Type)),'instrument_id'=>$calid,);
                DB::table('calibration_externalfiles')->insert($data1);
                //  DB::table('calibration_instruments')->where('id',$calid)
                //                                      ->update(['extfile'=>$cer]);

            }
        }

        return response()->json([
            'status'=>200,
            'data'=>NULL
        ]);

    }

    public function deleteexternalfiles($id)
    {
        $models = DB::table('modenadisable')->First();
        $data = DB::table('calibration_externalfiles')->where('id',$id)->first();
        $instrumentid = $data->id;
        if($data)
        {
            DB::table('calibration_externalfiles')->where('id',$id)->delete();
            $check = DB::table('calibration_externalfiles')->where('instrument_id',$data->instrument_id)->get();
            if($check->isEmpty())
            {
                DB::table('calibration_instruments')->where('id',$data->instrument_id)
                                                                ->update(['extfile'=>NULL]);
            }
            return redirect('/user/calibration/editins?val='.$instrumentid)
                    ->with('model', $models)
                    ->with('msg', 'Attachment Deleted successfully');

        }
        else
        {
            return redirect('/user/calibration/editins?val='.$instrumentid)
                    ->with('model', $models)
                    ->with('msg', 'Attachment Do not exists');

        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCalibrationRequest $request
     * @return Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function updateins(UpdateCalibrationRequest $request)
    {
        $id = $request->id;
        $calibration = Calibration_instrument::find($id);
        if ($calibration->calibration_field_rules) {
            $dynamicFieldValidation = CalibrationFormConfigurationHelper::getCalibrationConfigurationPostMethodRules($request->type, $calibration->calibration_field_rules, 1, $id);

            $validator = Validator::make($request->all(), $dynamicFieldValidation['rules'], $dynamicFieldValidation['messages']);

            if ($validator->fails()) {
                $models = DB::table('modenadisable')->First();
                return redirect()->back()->withErrors($validator)->withInput()->with('model', $models);
            }
        }

        if ($request->inst_manf == NULL) {
            $inst_manf = 'no value';
        } else {
            $inst_manf = $request->inst_manf;
        }

        //checking status
        $due          = $request->duedat;
        $neartoexpiry = date('Y-m-d', strtotime($due . ' - 30 days'));
        $today_dt     = date('Y-m-d');

        if ($due == NULL) {
            $status = NULL;
        } elseif ($due < $today_dt) {
            $status = "Expired";
        } elseif ($neartoexpiry <= $today_dt) {
            $status = "Near to Expiry";
        } else {
            $status = "Calibrated";
        }

        $calibration->update([
            'Instrument_desc'     => $request->desc,
            'Instru_Manufacturer' => $inst_manf,
            'Department'          => $request->dept,
            'Holder'              => $request->holder,
            'Calib_procedure'     => $request->calib_procedure,
            'Agency'              => $request->agency,
            'Capacity'            => $request->capacity,
            'Type'                => $request->type,
            'Frequency'           => $request->freq,
            'standard_edition'    => $request->standard_edition,
            'acceptance_criteria' => $request->acceptance_criteria,
            'Duedate'             => $due,
            'Status'              => $status,
            'dateofcal'           => $request->caldt,
            'least_count'         => $request->least_count,
            'Instrument_no'       => $request->Instrument_no,
            'date_of_first_use'   => $request->date_of_first_use
        ]);
        $target_pages = ModuleBladePageDetail::where('module_name','calibration')
                ->where('route_path','user/calibration/editins')
                ->where('is_target_page',true)
                ->pluck('id')
                ->map(function($id) { return (string)$id; })
                ->toArray();
        //inserting data into external and internal tables
        if (strtolower($request->type) == 'external') {
            $exCustomFields = CustomField::where('module_name', 'calibration')->where('sub_module','external')
                ->where(function ($query) use ($target_pages) {
                    foreach ($target_pages as $pageId) {
                        $query->orWhereJsonContains('target_pages', $pageId);
                    }
                })->where('deleted',false)
                ->whereJsonLength('target_pages', '>', 0)
                ->get();
            $tempData = CalibrationExternal::where('instrument_id', $calibration->id)->first();
            $fieldForm = $this->getCustomFieldFormData($exCustomFields, $request,$tempData);

            $chk = DB::table('calibration_external')->where('instrument_id', $calibration->id)->get();
            if (count($chk) == 0) {
                DB::table('calibration_external')
                    ->insert(array_merge([
                        'Instrument_no' => $request->Instrument_no,
                        'Duedate'       => $due,
                        'Dateofcal'     => $request->caldt,
                        'Report_no'     => $request->report_num_ext,
                        'Status'        => $status,
                        'instrument_id' => $calibration->id,
                    ], $fieldForm));
            } else {
                $latest_data_id_external =  DB::table('calibration_external')
                                         ->where('instrument_id', $calibration->id)
                                         ->latest('id')->value('id');
                DB::table('calibration_external')
                                ->where('id', $latest_data_id_external)
                                ->update(array_merge([
                                    'Instrument_no' => $request->Instrument_no,
                                    'Duedate'       => $due,
                                    'Dateofcal'     => $request->caldt,
                                    'Report_no'     => $request->report_num_ext,
                                    'Status'        => $status,
                                ],$fieldForm));
            }
        } elseif (strtolower($request->type) == 'internal') {
            $inCustomFields = CustomField::where('module_name', 'calibration')->where('sub_module','internal')
                ->where(function ($query) use ($target_pages) {
                    foreach ($target_pages as $pageId) {
                        $query->orWhereJsonContains('target_pages', $pageId);
                    }
                })->where('deleted',false)
                ->whereJsonLength('target_pages', '>', 0)
                ->get();
            $tempData = CalibrationInternal::where('instrument_id', $calibration->id)->first();
            $fieldForm = $this->getCustomFieldFormData($inCustomFields, $request);
            $chkint = DB::table('calibration_internal')->where('instrument_id', $calibration->id)->get();
            if (count($chkint) == 0) {
                DB::table('calibration_internal')
                    ->insert(array_merge([
                        'Instrument_no'      => $request->Instrument_no,
                        'Acc_error'          => 'novalue',
                        'M_instru'           => 'novalue',
                        'Certificate_no'     => 'novalue',
                        'M_reading'          => 'novalue',
                        'Instrument_reading' => 'novalue',
                        'Error'              => 'novalue',
                        'Remarks'            => 'novalue',
                        'Report_no'          => 'novalue',
                        'Adj_made'           => 'novalue',
                        'Verified_by'        => 'novalue',
                        'Calibrated_by'      => 'novalue',
                        'Duedate'            => $due,
                        'Dateofcal'          => $request->caldt,
                        'Status'             => $status,
                        'instrument_id'      => $calibration->id,
                    ]),$fieldForm);
            } else {
                $latest_data_id_internal =  DB::table('calibration_internal')
                                         ->where('instrument_id', $calibration->id)
                                         ->latest('id')->value('id');
                DB::table('calibration_internal')
                        ->where('id', $latest_data_id_internal)
                        ->update(array_merge([
                            'Duedate'       => $due,
                            'Dateofcal'     => $request->caldt,
                            'Status'        => $status,
                            'Instrument_no' => $request->Instrument_no,
                    ], $fieldForm));
            }
        }

        $model = DB::table('modenadisable')->first();

        return redirect('/user/calibrationmgmt?tab=' . (strtolower($request->type) == 'external' ? 'ext' : 'int').'&rowId='.$id)
            ->with('model', $model)
            ->with('msg', 'Instrument Details Edited Successfully');
    }
    public function delete(Request $request)
    {
        $ids = $request->input('ids');
        if (!is_array($ids) || empty($ids)) {
            return response()->json(['message' => 'No valid IDs provided.'], 400);
        }
        Calibration_instrument::whereIn('id', $ids)->delete();
        CalibrationExternal::whereIn('instrument_id', $ids)->delete();
        CalibrationInternal::whereIn('instrument_id', $ids)->delete();
        CalibrationExternalFile::whereIn('instrument_id', $ids)->delete();
        session()->flash('delete_success', 'Records deleted successfully.');
        return response()->json([
            'status' => 200,
            'redirect' => url('/user/calibrationmgmt')
        ]);
    }

    //analysis
    public function analysis()
    {
        $today_dt = date('Y-m-d');
        $nr = date('Y-m-d',strtotime('+30 days'));
        $total = Calibration_instrument::all()->count();
        $expired = Calibration_instrument::where('DueDate','<',$today_dt)->get()->count();
        $neartoexpiry = Calibration_instrument::where('DueDate','<=',$nr )
                                               ->where('DueDate','>',$today_dt)
                                               ->get()->count();
        $calibrated = Calibration_instrument::where('DueDate','>',$today_dt)->where('DueDate','>',$nr )->get()->count();
        $calib_nr = Calibration_instrument::where('DueDate','>',$today_dt)->get()->count();
        $notcalibrated = Calibration_instrument::where('Status','=',NULL)->get()->count();

        $totalcalibrated = $calibrated + $neartoexpiry;

        $depcodes = DB::select("select code from departments order by departmentname ASC");
        $depcode = [];
        foreach ($depcodes as $key) {
            array_push($depcode, $key->code);
        }

       $calibins = DB::select("select s.id,COUNT(r.Status)AS number
                                  FROM  calibration_instruments AS r
                                  right JOIN departments AS s
                                  ON r.Department = s.id
                                  and r.DueDate > '$today_dt' and r.DueDate > '$nr'
                                  group by s.id
                                  order by s.departmentname ASC
                                ");


        $nearins = DB::select("select s.id,COUNT(r.Status)AS number
                            FROM  calibration_instruments AS r
                            right JOIN departments AS s
                            ON r.Department = s.id
                            and r.DueDate <= '$nr' and r.DueDate > '$today_dt'
                            group by s.id
                            order by s.departmentname ASC
                          ");

       $expins = DB::select("select s.id,COUNT(r.Status)AS number
                            FROM  calibration_instruments AS r
                            right JOIN departments AS s
                            ON r.Department = s.id
                            and r.DueDate < '$today_dt'
                            group by s.id
                            order by s.departmentname ASC
                          ");

        $calib = [];
        foreach ($calibins as $key) {
            array_push($calib, $key->number);
        }

        $near = [];
        foreach ($nearins as $key) {
            array_push($near, $key->number);
        }

        $exp = [];
        foreach ($expins as $key) {
            array_push($exp, $key->number);
        }

       $intins =  DB::select("select s.id,COUNT(r.Type)AS number
                            FROM  calibration_instruments AS r
                            right JOIN departments AS s
                            ON r.Department = s.id
                            and r.Type='internal'
                            group by s.id
                            order by s.departmentname ASC
                          ");

      $extins =  DB::select("select s.id,COUNT(r.Type)AS number
                            FROM  calibration_instruments AS r
                            right JOIN departments AS s
                            ON r.Department = s.id
                            and r.Type='external'
                            group by s.id
                            order by s.departmentname ASC
                         ");


        $internal = [];
        foreach ($intins as $key) {
            array_push($internal, $key->number);
        }

        $external = [];
        foreach ($extins as $key) {
            array_push($external, $key->number);
        }

       if(($totalcalibrated!=0) && ($total!=0))
       {
         $percent=round(($totalcalibrated/$total)*100);
       }
       else
       {
         $percent=0;
       }

        $models=DB::table('modenadisable')->First();
        return view('user.calibration.analysis')
                                       ->with('model',$models)
                                       ->with('total',$total)
                                       ->with('calibrated',$calibrated)
                                       ->with('expired',$expired)
                                       ->with('neartoexpiry',$neartoexpiry)
                                       ->with('totalcalibrated',$totalcalibrated)
                                       ->with('percent',$percent)
                                       ->with('depcode', json_encode($depcode, JSON_NUMERIC_CHECK))
                                       ->with('calib', json_encode($calib, JSON_NUMERIC_CHECK))
                                       ->with('exp', json_encode($exp, JSON_NUMERIC_CHECK))
                                       ->with('near', json_encode($near, JSON_NUMERIC_CHECK))
                                       ->with('internal', json_encode($internal, JSON_NUMERIC_CHECK))
                                       ->with('external', json_encode($external, JSON_NUMERIC_CHECK));


    }

    public function addexternalattachments(Request $request)
    {
            $record_details_id = $request->input('instrument_main_id');
            $pagename = $request->input('page_name');
            $fieldForm = [];
            $tempData = CalibrationExternal::where('instrument_id',$record_details_id)->first();
            if($pagename==='undercalibration'){
                $target_pages = ModuleBladePageDetail::where('module_name','calibration')
                    ->where('route_path','user/calibration/under-list')
                    ->where('is_target_page',true)
                    ->where('page_name','Add Attachments')
                    ->pluck('id')
                    ->map(function($id) { return (string)$id; })
                    ->toArray();
                $CustomFields = CustomField::where('module_name','calibration')->where('sub_module','external')
                    ->where(function ($query) use ($target_pages) {
                        foreach ($target_pages as $pageId) {
                            $query->orWhereJsonContains('target_pages', $pageId);
                        }
                    })->where('deleted',false)
                    ->whereJsonLength('target_pages', '>', 0)
                    ->get();
                $fieldForm  = $this->getCustomFieldFormData($CustomFields,$request,$tempData);
            }elseif($pagename=='register'){
                $target_pages = ModuleBladePageDetail::where('module_name','calibration')
                    ->where('route_path','user/calibrationmgmt')
                    ->where('page_name','Add Attachments')
                    ->where('is_display_page',true)
                    ->pluck('id')
                    ->map(function($id) { return (string)$id; })
                    ->toArray();
                $CustomFields = CustomField::where('module_name', 'calibration')->where('sub_module','external')
                    ->where(function ($query) use ($target_pages) {
                        foreach ($target_pages as $pageId) {
                            $query->orWhereJsonContains('target_pages', $pageId);
                        }
                    })->where('deleted',false)
                    ->whereJsonLength('target_pages', '>', 0)
                    ->get();
                $fieldForm  = $this->getCustomFieldFormData($CustomFields,$request,$tempData);
            }
            $caldetails = DB::table('calibration_instruments')->where('id',$record_details_id)->first();
            $ext_file_names = [];
            $externalfiles = $request->file('externalfiles');

            if (!empty($externalfiles) && is_array($externalfiles)) {
                foreach ($externalfiles as $file) {
                    $filename = $file->getClientOriginalName();
                    $picture = date('His') . $filename;
                    $destination_path = public_path('storage/calibration');
                    $file->move($destination_path, $picture);
                    $ext_file_names[] = $picture;
                }
            }
            $external_file_details = count($ext_file_names) > 0 ? implode(',', $ext_file_names) : null;

            DB::table('calibration_instruments')->where('id',$record_details_id)
                                                ->update(['extfile' => $external_file_details]);
            if(count($fieldForm)>0){
                CalibrationExternal::where('instrument_id',$record_details_id)->update($fieldForm);
            }
            foreach ($ext_file_names as $ext_file_name) {
                $data1  = array(
                    'Filename' =>  $ext_file_name,
                    'Instrument_no'=>$caldetails->Instrument_no,
                    'Type'=>trim(strtolower($caldetails->Type)),
                    'instrument_id'=>$record_details_id,
                );
                DB::table('calibration_externalfiles')->insert($data1);
            }
            return response()->json([
                'status' => 200,
                'data' => null,
            ]);
    }

    /**
     * Update the status of the external calibration to the new status in bulk.
     * Input:
     *    ID list: [1,2,3]
     *    Status: Under Calibration, Calibrated, Near to expiry, Expired
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function changeStatus(Request $request): JsonResponse
    {
        $responseData = [];

        try {
            $inputIds = $request->get('ids');
            $targetStatus = $request->get('status');
            $isValidData = $inputIds && count($inputIds) && $targetStatus;

            if (!$isValidData) {
                $responseData['success'] = false;
            }

            Calibration_instrument::where(function ($query) {
                    $query->where('Type','External')
                        ->orWhere('Type','EXTERNAL')
                        ->orWhere('Type','external');
                })
                ->whereIn('id', $inputIds)
                ->update(['Status' => $targetStatus]);

            $responseData['success'] = true;
        } catch (\Exception $exception) {
            Log::error($exception);
            $responseData['success'] = false;
        }

        return response()->json($responseData);
    }

    /**
     * Get the calibration that has the status is "Under Calibration"
     *
     * @return Application|Factory|View
     */
    public function getUnderCalibrationConList(Request $request)
    {
        $departments = Departments::all();
        $data= DB::table('calibration_instruments')->orderByDesc('created_at')->get();
        $model=DB::table('modenadisable')->First();
        $mr = DB::table('calibrationreviewers')->where('email',Auth::user()->email)->get();
        $internal_types = ['Internal', 'INTERNAL', 'internal'];
        $external_types = ['External', 'EXTERNAL', 'external'];
        $external_datas = Calibration_instrument::whereIn('Type', $external_types)
        ->where('status', Calibration_instrument::UNDER_CALIBRATION_STATUS)
        ->orderByDesc('created_at')
        ->get();
        $internal_datas = Calibration_instrument::where('Type',$internal_types)
        ->where('status', Calibration_instrument::UNDER_CALIBRATION_STATUS)
        ->orderByDesc('created_at')
        ->get();
        $targetIds    = $request->session()->get('target_id');
        $targetIdList = [];
        $calibrationFieldRules = CalibrationFormConfigurationHelper::getCalibrationFields();

        return view('user.calibration.under')
            ->with('calibrationFieldRules',$calibrationFieldRules)
            ->with('mr',$mr)
            ->with('model',$model)
            ->with('data',$data)
            ->with('external_datas',$external_datas)
            ->with('internal_datas',$internal_datas)
            ->with('departments',$departments)
            ->with('targetIdList', $targetIdList);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function storeTargetId(Request $request): JsonResponse
    {
        $responseData = [];

        try {
            $inputIds    = $request->get('ids');
            $isValidData = $inputIds && count($inputIds);

            if (!$isValidData) {
                $responseData['success'] = false;
            }
            $request->session()->put('target_id', implode(',', $inputIds));

            $responseData['success'] = true;
        } catch (\Exception $exception) {
            Log::error($exception);
            $responseData['success'] = false;
        }

        return response()->json($responseData);
    }

    /**
     * @param Request $request
     * @return Application|Factory|\Illuminate\Http\RedirectResponse|View
     */
    public function displayUpdateStatusForm(Request $request)
    {
        $targetIds    = $request->session()->get('target_id');
        $inputIds = explode(',', $targetIds);
        $isValidData = $inputIds && count($inputIds);

        if (!$isValidData) {
            \Illuminate\Support\Facades\Session::flash('errmsg', "The target calibration is invalid");
            return redirect()->back();
        }

        $currentMonth      = date('my');
        $latestCalibration = CalibrationExternal::select('id')->orderby('id', 'DESC')->first();
        if (!$latestCalibration) {
            $counter = 1;
        } else {
            $counter = $latestCalibration->id + 1;
        }

        $calibrations = Calibration_instrument::whereIn('id', $inputIds)->get();
        $certificateNumbers = [];
        $nextDueDates = [];
        $target_pages = ModuleBladePageDetail::where('module_name','calibration')
                ->where('route_path','user/calibration/viewstatus')
                ->where('is_target_page',true)
                ->pluck('id')
                ->map(function($id) { return (string)$id; })
                ->toArray();
        $customFields = CustomField::where('module_name', 'calibration')->where('sub_module','external')
            ->where(function ($query) use ($target_pages) {
                foreach ($target_pages as $pageId) {
                    $query->orWhereJsonContains('target_pages', $pageId);
                }
            })->where('deleted',false)
            ->whereJsonLength('target_pages', '>', 0)
            ->get();
        //Field Index
        $CustomFieldIndex = ModuleBladePageDetail::where('module_name','calibration')
            ->where('route_path','user/calibration/viewstatus')->where('is_target_page',true)->value('field_index');
        $defaultFieldIndex = $FieldIndex = $CustomFieldIndex ? array_values(json_decode($CustomFieldIndex, true)) : [];
        sort($FieldIndex,SORT_NUMERIC);
        //End Field Index
        foreach ($calibrations as $index => $calibration) {
            $certificateNumbers[$calibration->id] = ($counter + $index) . $currentMonth;
            $dueDate = $calibration->dateofcal ? $calibration->dateofcal : date('Y-m-d');
            if ($calibration->Frequency == 'One year') {
                $nextDueDate = date('Y-m-d', strtotime('+1 year', strtotime($dueDate)));
            } elseif ($calibration->Frequency == 'Two year') {
                $nextDueDate = date('Y-m-d', strtotime('+2 year', strtotime($dueDate)));
            } elseif ($calibration->Frequency == 'Three year') {
                $nextDueDate = date('Y-m-d', strtotime('+3 year', strtotime($dueDate)));
            } elseif ($calibration->Frequency == 'Four year') {
                $nextDueDate = date('Y-m-d', strtotime('+4 year', strtotime($dueDate)));
            } elseif ($calibration->Frequency == '3 Month') {
                $nextDueDate = date('Y-m-d', strtotime('+3 months', strtotime($dueDate)));
            } elseif ($calibration->Frequency == '6 Months') {
                $nextDueDate = date('Y-m-d', strtotime('+6 months', strtotime($dueDate)));
            } elseif ($calibration->Frequency == '1.5 years') {
                $nextDueDate = date('Y-m-d', strtotime('+1 year +6 months', strtotime($dueDate)));
            } else {
                $nextDueDate = date('Y-m-d');
            }
            $nextDueDates[$calibration->id] = $nextDueDate;
        }
        $model = DB::table('modenadisable')->First();
        return view('user.calibration.update_status_form', compact(
            'calibrations', 'certificateNumbers', 'nextDueDates', 'model','customFields','FieldIndex','defaultFieldIndex'
        ));
    }

    /**
     * @param ChangeStatusByDueDateRequest $request
     * @return JsonResponse
     */
    public function handleStatusUpdates(ChangeStatusByDueDateRequest $request): JsonResponse
    {
        $responseData = [];
        try {
            $targetIdsInput = $request->get('target_ids');
            $dateOfCalibrationsInput = $request->get('date_of_calibrations');
            $dueDatesInput = $request->get('next_due_dates');
            $certificateNumbersInput = $request->get('certificate_numbers');
            // Make unique and assign key/value pairs
            $targetIds = [];
            $dateOfCalibrations = [];
            $dueDates = [];
            $certificateNumbers = [];

            foreach ($targetIdsInput as $index => $targetId) {
                $targetIds[$targetId] = $targetId;
                $dateOfCalibrations[$targetId] = $dateOfCalibrationsInput[$index] ?? null;
                $dueDates[$targetId] = $dueDatesInput[$index] ?? null;
                $certificateNumbers[$targetId] = $certificateNumbersInput[$index] ?? null;
            }
            $calibrationExternalData = [];
            $target_pages = ModuleBladePageDetail::where('module_name','calibration')
                ->where('route_path','user/calibration/viewstatus')
                ->where('is_target_page',true)
                ->pluck('id')
                ->map(function($id) { return (string)$id; })
                ->toArray();
            $customFields = CustomField::where('module_name', 'calibration')->where('sub_module','external')
                ->where(function ($query) use ($target_pages) {
                    foreach ($target_pages as $pageId) {
                        $query->orWhereJsonContains('target_pages', $pageId);
                    }
                })->where('deleted',false)
                ->whereJsonLength('target_pages', '>', 0)
                ->get();
            $finalCustomData = [];
            $targetIdsInput = array_unique($targetIdsInput);
            for($index=1;$index<=count($targetIdsInput);$index++){
                $tempData = CalibrationExternal::where('instrument_id',$targetIdsInput[$index-1])->first();
                $finalCustomData[] = $this->getCustomFieldFormData($customFields, $request,$tempData,$index);
            }
            $count = 0;
            foreach ($targetIds as $targetId) {
                $calibration = Calibration_instrument::find($targetId);
                $status = get_calibration_status_base_on_due_date($dueDates[$calibration->id]);
                $calibrationExternalData[] = array_merge([
                    'Instrument_no' => $calibration->Instrument_no,
                    'Duedate'       => $dueDates[$calibration->id],
                    'Dateofcal'     => $dateOfCalibrations[$calibration->id],
                    'Status'        => $status,
                    'Report_no'     => $certificateNumbers[$calibration->id],
                    'instrument_id' => $calibration->id,
                ],$finalCustomData[$count++]);

                $calibration->update([
                    'Status'    => $status,
                    'Duedate'   => $dueDates[$calibration->id],
                    'dateofcal' => $dateOfCalibrations[$calibration->id],
                ]);
            }
            if (count($calibrationExternalData)) {
                CalibrationExternal::insert($calibrationExternalData);
            }
            \Illuminate\Support\Facades\DB::commit();
            $responseData['success'] = true;

            $currentUserIsReviewer = Calibrationreviewer::where('email', \Illuminate\Support\Facades\Auth::user()->email)->exists();
            $calibrationReviewerEmails = [];
            $additionalEmails = [];
            $emailNotification = EmailNotification::where('module', 'calibration')
                ->where('item', 'Change status from Under Calibration to Calibrated')
                ->where('is_blocked', false)
                ->first();

            if ($emailNotification) {
                $emailNotificationUsers = EmailNotificationsUserDetails::where('notification_id', $emailNotification->id)->get();
                $additionalEmails = count($emailNotificationUsers)
                    ? User::whereIn('id', $emailNotificationUsers->pluck('user_id')->toArray())->pluck('email')->toArray()
                    : [];
            }

            if ($currentUserIsReviewer) {
                $calibrationReviewerEmails[] = \Illuminate\Support\Facades\Auth::user()->email;
                $otherReviewers = Calibrationreviewer::where('email', '!=', \Illuminate\Support\Facades\Auth::user()->email)
                    ->orderBy('id', 'DESC')
                    ->pluck('email');
                $calibrationReviewerEmails = array_merge($calibrationReviewerEmails, $otherReviewers->toArray(), $additionalEmails);
            } else {
                $calibrationReviewerEmails = Calibrationreviewer::orderBy('id', 'DESC')->pluck('email')->toArray();
                $calibrationReviewerEmails = array_merge($calibrationReviewerEmails, $additionalEmails);
            }
            $responseData['target_ids'] = implode(',', $targetIds);
            $responseData['calibration_reviewer_emails'] = implode(',', $calibrationReviewerEmails);
        } catch (\Exception $exception) {
            \Illuminate\Support\Facades\DB::rollBack();
            Log::error($exception);
            $responseData['success'] = false;
        }

        return response()->json($responseData);
    }

    /**
     * Calculate the due date base on date of calibration and frequently
     *
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function calculateDueDate($id, Request $request): JsonResponse
    {
        $responseData = [];
        try {
            $dateOfCalibration = $request->get('date_of_calibration');
            $responseData['success'] = true;
            if ($dateOfCalibration) {
                $replaceData = [
                    'one' => '1',
                    'two' => '2',
                    'three' => '3',
                    'four' => '4',
                    'five' => '5',
                    'six' => '6',
                    'seven' => '7',
                    'eight' => '8',
                    'nine' => '9',
                    'ten' => '10'
                ];
                $calibration = Calibration_instrument::findOrFail($id);
                $dateOfCalibration       = Carbon::parse($dateOfCalibration)->format('Y-m-d');
                $frequency               = strtolower(trim($calibration->Frequency));
                foreach ($replaceData as $key => $value) {
                    $frequency = str_replace($key, $value, $frequency);
                }

                $dueDate   = date('Y-m-d', strtotime($dateOfCalibration . ' + ' . $frequency));
                $responseData['due_date'] = $dueDate;
            }
        } catch (\Exception $exception) {
            Log::error($exception);
            $responseData['success'] = false;
        }

        return response()->json($responseData);
    }

    /**
     * Automatic Email Notification for Status Change from "Under Calibration" to "Calibrated"- External
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function sendMailToReviewer(Request $request): JsonResponse
    {
        $responseData = [];
        $calibrationTargetIdInput = $request->get('target_ids');
        $email = $request->get('email');
        $calibrationTargetIds = explode(',', $calibrationTargetIdInput);
        try {
            $calibrationReviewer = Calibrationreviewer::where('email', $email)->first();
            $emailNotification = EmailNotification::where('module', 'calibration')
                ->where('item', 'Change status from Under Calibration to Calibrated')
                ->where('is_blocked', false)
                ->first();

            if ($emailNotification && $calibrationReviewer) {
                $emailData['app_url'] = BaseController::addSlashToEndOfURL() . 'user/calibrationmgmt?tab=ext';
                $emailData['email_content'] = htmlspecialchars($emailNotification->email_content);
                $calibrations = Calibration_instrument::with('departmentdetails')
                    ->whereIn('id', $calibrationTargetIds)
                    ->get();
                $emailData['calibrations'] = $calibrations;
                $emailData['name'] = $calibrationReviewer->name;
                Mail::to($calibrationReviewer->email)->send(new ChangeStatusOfCalibration($emailData));

                Log::info('Send mail to calibration reviewer successfully');
                $responseData['success'] = true;
            } else {
                $emailNotificationUsers = EmailNotificationsUserDetails::where('notification_id', $emailNotification->id)->get();
                $additionalUser = count($emailNotificationUsers)
                    ? User::whereIn('id', $emailNotificationUsers->pluck('user_id'))
                        ->where('email', $email)->first()
                    : null;

                if ($emailNotification && $additionalUser) {
                    $emailData['app_url'] = BaseController::addSlashToEndOfURL() . 'user/calibrationmgmt?tab=ext';
                    $emailData['email_content'] = htmlspecialchars($emailNotification->email_content);
                    $calibrations = Calibration_instrument::with('departmentdetails')
                        ->whereIn('id', $calibrationTargetIds)
                        ->get();
                    $emailData['calibrations'] = $calibrations;
                    $emailData['name'] = $additionalUser->name;
                    Mail::to($additionalUser->email)->send(new ChangeStatusOfCalibration($emailData));
                    Log::info('Send mail to additional users successfully');
                    $responseData['success'] = true;
                } else {
                    Log::info('Something was wrong when sending mail to calibration reviewer');
                    if (!$emailNotification) {
                        Log::info('Email notification is not found');
                    }
                    if (!$calibrationReviewer) {
                        Log::info('Calibration reviewer is not found');
                    }
                    $responseData['success'] = false;
                }
            }
        } catch (\Exception $exception) {
            Log::info('Send mail to calibration reviewer is failure');
            Log::error($exception);
            $responseData['success'] = false;
        }

        return response()->json($responseData);
    }

    /**
     * Send mail when calibration status has been changed to 'Calibrated', 'Expired', 'Near to expiry
     *
     * @param $calibration
     * @param $orgStatus
     * @param $newStatus
     * @return void
     */
    private function sendMailWhenCalibrationStatusHasBeenChange($calibration, $orgStatus, $newStatus)
    {
        try {
            $calibrationReviewers = Calibrationreviewer::get();
            $emailNotification = EmailNotification::where('module', 'calibration')
                ->where('item', 'Email Content while instrument status changes')
                ->where('is_blocked', false)
                ->first();

            if ($emailNotification && count($calibrationReviewers)) {
                $emailNotificationUsers = EmailNotificationsUserDetails::where('notification_id', $emailNotification->id)->get();
                $additionalEmails = count($emailNotificationUsers)
                    ? User::whereIn('id', $emailNotificationUsers->pluck('user_id')->toArray())->pluck('email')->toArray()
                    : [];
                $emailData['email_content'] = htmlspecialchars($emailNotification->email_content);
                if ($newStatus == Calibration_instrument::CALIBRATED_STATUS) {
                    $tab = strtolower($calibration->Type) == Calibration_instrument::INTERNAL_TYPE ? 'int' : 'ext';
                    $emailData['url'] = route('calibration.index', ['tab' => $tab]);
                } elseif ($newStatus == Calibration_instrument::NEAR_TO_EXPIRED_STATUS
                    || $newStatus == Calibration_instrument::EXPIRED_STATUS) {
                    if (strtolower($calibration->Type) == Calibration_instrument::INTERNAL_TYPE) {
                        $emailData['url'] = route('int', ['val' => $calibration->id]);
                    } elseif(strtolower($calibration->Type) == Calibration_instrument::EXTERNAL_TYPE) {
                        $emailData['url'] = route('calibration.view.status', ['val' => $calibration->id]);
                    } else {
                        $emailData['url'] = BaseController::addSlashToEndOfURL();
                    }
                } else {
                    $emailData['url'] = BaseController::addSlashToEndOfURL();
                }
                $emailData['instrument_no'] = $calibration->Instrument_no;
                $emailData['previous_status'] = $orgStatus;
                $emailData['new_status'] = $newStatus;
                $emailData['department'] = $calibration->departmentdetails
                    ? $calibration->departmentdetails->departmentname
                    : '--';
                $emailData['type'] = $calibration->Type;
                $emailData['due_date'] = $calibration->Duedate
                    ? date('d-m-Y', strtotime($calibration->Duedate))
                    : '--';
                $emailData['name'] = 'reviewer';
                $firstCalibrationReviewers = $calibrationReviewers->first();
                $otherCalibrationReviewers = $calibrationReviewers->slice(1);
                $otherCalibrationReviewerEmails = $otherCalibrationReviewers
                    ? $otherCalibrationReviewers->pluck('email')->toArray()
                    : [];
                Mail::to($firstCalibrationReviewers->email)
                    ->bcc(array_merge($additionalEmails, $otherCalibrationReviewerEmails))
                    ->send(new CalibrationStatusUpdate($emailData));
                Log::info('Send mail [calibration status has been changed] to calibration reviewer successfully');
            } else {
                Log::info('Something was wrong when sending mail [calibration status has been changed]');
                if (!$emailNotification) {
                    Log::info('Email notification is not found');
                }
                if (!count($calibrationReviewers)) {
                    Log::info('Calibration reviewer is not found');
                }
            }
        } catch (\Exception $exception) {
            Log::info('Send mail [calibration status has been changed] is failure');
            Log::error($exception);
        }
    }
    /**
     * Display the detail popup when click "View" button
     *
     * @param $id
     * @return JsonResponse
     * @throws Throwable
     */
    public function show($id,$typename): JsonResponse
    {
        $responseData = [];
        try {
            $type = strtolower($typename);
            if($type==='undercalibration'){
                $external_types = ['External', 'EXTERNAL', 'external'];
                $value = Calibration_instrument::with([
                    'departmentdetails',
                    'calibrationExternalReportNo',
                    'calibrationInternalReportNo',
                    'calibrationExternalFiles'
                ])->whereIn('Type', $external_types)
                    ->where('status', Calibration_instrument::UNDER_CALIBRATION_STATUS)
                    ->where('id', $id)
                    ->orderByDesc('created_at')
                    ->first();
                $display_pages = ModuleBladePageDetail::where('module_name','calibration')
                    ->where('route_path','user/calibration/under-list')
                    ->where('is_display_page',true)
                    ->pluck('id')
                    ->map(function($id) { return (string)$id; })
                    ->toArray();
                $CustomFields = CustomField::where('module_name', 'calibration')->where('sub_module','external')
                    ->where(function ($query) use ($display_pages) {
                        foreach ($display_pages as $pageId) {
                            $query->orWhereJsonContains('display_pages', $pageId);
                        }
                    })->where('deleted',false)
                    ->whereJsonLength('display_pages', '>', 0)
                    ->get();

                //Field Index
                $CustomFieldIndex = ModuleBladePageDetail::where('module_name','calibration')->where('sub_module','external')->where('route_path','user/calibration/under-list')
                    ->where('is_display_page',true)->value('field_index');
                $defaultFieldIndex = $externalFieldIndex = $CustomFieldIndex ? array_values(json_decode($CustomFieldIndex, true)) : [];
                sort($externalFieldIndex,SORT_NUMERIC);
                //End Field Index
                $responseData['view'] = \view('user.calibration._underviewdetails', compact(
                    'value','CustomFields','externalFieldIndex','defaultFieldIndex'
                ))->render();
            }else{
                $query = Calibration_instrument::with([
                    'departmentdetails',
                    'calibrationExternalReportNo',
                    'calibrationInternalReportNo',
                ]);
                if ($type === 'external') {
                    $query->with('calibrationExternalFiles');
                }
                $value = $query->where('id', $id)
                    ->whereIn('Type', [
                        ucfirst($type),
                        strtoupper($type),
                        strtolower($type)
                    ])->orderByDesc('created_at')->first();
                $display_pages = ModuleBladePageDetail::where('module_name','calibration')
                    ->where('route_path','user/calibrationmgmt')
                    ->where('is_display_page',true)
                    ->pluck('id')
                    ->map(function($id) { return (string)$id; })
                    ->toArray();
                $CustomFields = CustomField::where('module_name', 'calibration')->where('sub_module',$typename)
                    ->where(function ($query) use ($display_pages) {
                        foreach ($display_pages as $pageId) {
                            $query->orWhereJsonContains('display_pages', $pageId);
                        }
                    })->where('deleted',false)
                    ->whereJsonLength('display_pages', '>', 0)
                    ->get();
                //Field Index
                $CustomFieldIndex = ModuleBladePageDetail::where('module_name','calibration')->where('sub_module',$typename)->where('route_path','user/calibrationmgmt')
                    ->where('is_display_page',true)->value('field_index');
                $defaultFieldIndex = $CustomFieldIndex = $CustomFieldIndex ? array_values(json_decode($CustomFieldIndex, true)) : [];
                sort($CustomFieldIndex,SORT_NUMERIC);
                //End Field Index
                $responseData['view'] = \view('user.calibration._viewdetails', compact(
                    'value', 'typename','CustomFields','CustomFieldIndex','defaultFieldIndex'
                ))->render();
            }
            $responseData['id'] = $id;
            $responseData['success'] = true;
        } catch (\Exception $exception) {
            Log::error($exception);
            $responseData['success'] = false;
        }

        return response()->json($responseData);
    }

    /**
     * Display the detail popup when click "View" button
     *
     * @param $id
     * @return JsonResponse
     * @throws Throwable
     */
    public function addAttachment($id,$pagename): JsonResponse
    {
        $responseData = [];
        $value = CalibrationExternal::where('instrument_id',$id)->first();
        try {
            if($pagename==='undercalibration'){
                $target_pages = ModuleBladePageDetail::where('module_name','calibration')
                    ->where('route_path','user/calibration/under-list')
                    ->where('is_target_page',true)
                    ->where('page_name','Add Attachments')
                    ->pluck('id')
                    ->map(function($id) { return (string)$id; })
                    ->toArray();
                $CustomFields = CustomField::where('module_name','calibration')->where('sub_module','external')
                    ->where(function ($query) use ($target_pages) {
                        foreach ($target_pages as $pageId) {
                            $query->orWhereJsonContains('target_pages', $pageId);
                        }
                    })->where('deleted',false)
                    ->whereJsonLength('target_pages', '>', 0)
                    ->get();
                //Field Index
                $externalFieldIndex = ModuleBladePageDetail::where('module_name','calibration')->where('sub_module','external')->where('route_path','user/calibration/under-list')
                    ->where('page_name','Add Attachments')->where('is_target_page',true)->value('field_index');
                $defaultFieldIndex = $externalFieldIndex = $externalFieldIndex ? array_values(json_decode($externalFieldIndex, true)) : [];
                $totalCount = count($externalFieldIndex);
                $fieldIndex = $externalFieldIndex;
                sort($fieldIndex,SORT_NUMERIC);
                $exCount = $CustomFields->count();
                if($exCount>0){
                    $exCountIndex = array_slice($fieldIndex,-$exCount);
                    $externalFieldIndex = array_slice($fieldIndex, 0, $totalCount - $exCount);
                }else{
                    $exCountIndex = [];
                }
                //End Field Index
                $CustomFields = getCustomFieldsView($CustomFields,$exCountIndex,$value);
                $responseData['view'] = \view('user.calibration.__addexternalattachments', compact(
                    'value','CustomFields','pagename', 'id','externalFieldIndex','defaultFieldIndex'
                ))->render();
            }elseif($pagename=='register'){
                $target_pages = ModuleBladePageDetail::where('module_name','calibration')
                    ->where('route_path','user/calibrationmgmt')
                    ->where('page_name','Add Attachments')
                    ->where('is_target_page',true)
                    ->pluck('id')
                    ->map(function($id) { return (string)$id; })
                    ->toArray();
                $CustomFields = CustomField::where('module_name', 'calibration')->where('sub_module','external')
                    ->where(function ($query) use ($target_pages) {
                        foreach ($target_pages as $pageId) {
                            $query->orWhereJsonContains('target_pages', $pageId);
                        }
                    })->where('deleted',false)
                    ->whereJsonLength('target_pages', '>', 0)
                    ->get();
                //Field Index
                $externalFieldIndex = ModuleBladePageDetail::where('module_name','calibration')->where('sub_module','external')->where('route_path','user/calibrationmgmt')
                    ->where('page_name','Add Attachments')->where('is_target_page',true)->value('field_index');
                $defaultFieldIndex = $externalFieldIndex = $externalFieldIndex ? array_values(json_decode($externalFieldIndex, true)) : [];
                $totalCount = count($externalFieldIndex);
                $fieldIndex = $externalFieldIndex;
                sort($fieldIndex,SORT_NUMERIC);
                $exCount = $CustomFields->count();
                if($exCount>0){
                    $exCountIndex = array_slice($fieldIndex,-$exCount);
                    $externalFieldIndex = array_slice($fieldIndex, 0, $totalCount - $exCount);
                }else{
                    $exCountIndex = [];
                }
                //End Field Index
                $CustomFields = getCustomFieldsView($CustomFields,$exCountIndex,$value);
                $responseData['view'] = \view('user.calibration.__addexternalattachments', compact(
                    'value', 'pagename','CustomFields', 'id','externalFieldIndex','defaultFieldIndex'
                ))->render();
            }
            $responseData['id'] = $id;
            $responseData['success'] = true;
        } catch (\Exception $exception) {
            Log::error($exception);
            $responseData['success'] = false;
        }

        return response()->json($responseData);
    }

    /**
     * Update the status of the external calibration to the new status in bulk.
     * Input:
     *    ID list: [1,2,3]
     *    Status: Under Calibration, Calibrated, Near to expiry, Expired
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function changeStatusObsolete(Request $request): JsonResponse
    {
        $responseData = [];
        try {
            $inputIds = $request->get('ids');
            $isValidData = $inputIds && count($inputIds);

            if (!$isValidData) {
                $responseData['success'] = false;
            }

            Calibration_instrument::whereIn('id', $inputIds)
                ->update([
                    'Status' => 'Obsolete',
                    'updated_at' => now(),
                ]);
            CalibrationExternal::whereIn('instrument_id', $inputIds)
                ->update([
                    'Status' => 'Obsolete',
                    'updated_at' => now(),
                ]);
            $responseData['success'] = true;
        } catch (\Exception $exception) {
            Log::error($exception);
            $responseData['success'] = false;
        }

        return response()->json($responseData);
    }
}
