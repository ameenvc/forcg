<?php

namespace App\Http\Controllers;

use App;
use App\Charts\DocAnalysis;
use App\ClientDetail;
use App\Dcr;
use App\Departments;
use App\Doccontroller;
use App\Dochistory;
use App\Doctype;
use App\Document;
use App\GovernanceHub;
use App\GovernanceHubChangeRequestDetail;
use App\GovernanceHubComment;
use App\GovernanceHubCommentViewedDetail;
use App\GovernanceHubDocumentContent;
use App\GovernanceHubDocumentContentRichText;
use App\GovernanceHubDocumentDefinitionDetail;
use App\GovernanceHubDocumentReferenceDetail;
use App\GovernanceHubDocumentReview;
use App\GovernanceHubDraft;
use App\GovernanceHubDraftComment;
use App\GovernanceHubDraftContent;
use App\GovernanceHubDraftContentRichText;
use App\GovernanceHubDraftReviewerDetail;
use App\GovernanceHubReadRequestDetail;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddCrDocumentRequest;
use App\Http\Requests\DocumentAddMasterListRequest;
use App\Http\Requests\Document\AddContentRequest;
use App\Http\Requests\Document\AddDocumentReviewRequest;
use App\Http\Requests\Document\AddDraftContentRequest;
use App\Http\Requests\Document\AddDraftReviewApproveRequest;
use App\Http\Requests\Document\AddGovernanceHubChangeRequest;
use App\Http\Requests\Document\AddGovernanceHubReadRequest;
use App\Http\Requests\Document\UpdateContentRichTextRequest;
use App\Http\Requests\Document\UpdateDraftContentRichTextRequest;
use App\Imports\DocumentContentImport;
use App\Imports\DocumentDraftContentImport;
use App\Services\ContentSplitterService;
use App\Mail\DocumentAdditionRequestApproved;
use App\Mail\DocumentAdditionRequestDisapproved;
use App\Mail\DocumentAdditionRequestRaised;
use App\Mail\DocumentAdditionRequestReviewed;
use App\Mail\DocumentChangeRequestApproved;
use App\Mail\DocumentChangeRequestDisapproved;
use App\Mail\DocumentChangeRequestRaised;
use App\Mail\DocumentChangeRequestReviewed;
use App\Mail\DocumentChangeRequestUpdated;
use App\Mail\DocumentDraftApproveAcceptRemarkAdded;
use App\Mail\DocumentDraftReviewAcceptRemarkAdded;
use App\Mail\DocumentDraftReviewCompleted;
use App\Mail\DocumentReadRateRequestNotification;
use App\Mail\DocumentReadRateReviewCompletedNotification;
use App\Mail\DocumentRemovalRequestApproved;
use App\Mail\DocumentRemovalRequestDisapproved;
use App\Mail\DocumentRemovalRequestRaised;
use App\Mail\DocumentRemovalRequestReviewed;
use App\Mail\DocumentRemovalRequestUpdated;
use App\Mail\DraftApprovalRequestApproved;
use App\Mail\DraftApprovalRequestDisapproved;
use App\Mail\DraftReviewRequestApproved;
use App\Mail\DraftReviewRequestDisapproved;
use App\Mail\DraftReviewRequestRaised;
use App\Masterdoc;
use App\ModenaDisable;
use App\Modpageload;
use App\Multidoc;
use App\Representative;
use App\User;
use App\ViewActivity;
use Carbon\Carbon;
use Excel;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use PDF;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:web,admin', 'mfa']);
    }
    public function add()
    {
        $departments = Departments::all();
        $doctypes = Doctype::get();
        $controllers = Doccontroller::get();
        $representatives = Representative::get();
        $list = collect([]);
        foreach ($controllers as $c) {
            $list[] = $c->email;
        }

        foreach ($representatives as $representative) {
            $list[] = $representative->email;
        }
        $mrdoc = $list->unique();
        $models = Modenadisable::First();

        return view('user.document.addrequest')->with([
            'mrdoc' => $mrdoc,
            'departments' => $departments,
            'doctypes' => $doctypes,
            'model' => $models,
        ]);
    }
    public function adddcr(AddCrDocumentRequest $req)
    {
        if ($req->hasFile('documents')) {
            $image_array = $req->file('documents');
            $array_len = count($image_array);
            $name = [];
            for ($i = 0; $i < $array_len; $i++) {
                $image_size = $image_array[$i]->getClientOriginalName();
                $image_ext = $image_array[$i]->getClientOriginalExtension();
                $new_image_name = $image_size;
                $destination_path = public_path('storage/documents');
                $image_array[$i]->move($destination_path, $new_image_name);
                //read the datas into the array
                $name[] = $new_image_name;
            }
            foreach ($name as $f) {
                $a[] = $f;
            }
            $cer = implode(',', $name);
        } else {
            $cer = "NULL";
        }

        $department = $req->input('department');
        $departmentname = DB::table('departments')->where('id', $department)->value('departmentname');
        $exist = Dcr::where('department', $department)->first();
        if ($exist) {
            $c = Dcr::where('department', $department)->pluck('count');
            foreach ($c as $count) {
                $count = $count + 1;
            }
        } else {
            $count = "1";
        }

        //$hods = Departments::where('departmentname', $department)->pluck('hod');
        $hods = Departments::where('id', $department)->pluck('hod');
        foreach ($hods as $hod) {
            $hodemail = $hod;
        }
        $name = Auth::user()->name;
        // $id = Auth::user()->employeeid;
        $employee_id = Auth::user()->employeeid;

        $date = date('Y-m-d H:i:s', time());

        $doctypeId = null;
        if (!empty($req->input('other'))) {
            $doctype = $req->input('other');
        } else {
            $doctypeId = $req->input('doctype');
            $doctype = Doctype::where('id', $doctypeId)->value('typename');
        }
        $type = $req->input('type');
        $affected = $req->input('affected');
        $reason = $req->input('reason');
        $postedby = $req->input('post');
        if ($cer == "NULL") {
            $data1 = array(
                'count' => $count, 'department' => $department, 'name' => $name,
                'rid' => $employee_id, 'doctype' => $doctype, 'doctype_id' => $doctypeId, 'type' => 'New Document', 'affected' => $affected, 'reason' => $reason,
                'date' => $date, 'hod' => $hodemail, 'status' => 'Pending', 'postedby' => $postedby,
            );
        } else {
            $data1 = array(
                'count' => $count, 'department' => $department, 'name' => $name, 'documents' => $cer,
                'rid' => $employee_id, 'doctype' => $doctype, 'doctype_id' => $doctypeId, 'type' => 'New Document', 'affected' => $affected, 'reason' => $reason,
                'date' => $date, 'hod' => $hodemail, 'status' => 'Pending', 'postedby' => $postedby,
            );
        }
        Dcr::create($data1);
        $c = strtoupper(substr($departmentname, 0, 3));
        $co = "-0";
        $code = $c . $co;
        $a_id = $code . $count;
        $a = strtoupper('/');
        $b_id = $a_id . $a;
        $ldate = date('Y-m-d');
        $date = date('Y', strtotime($ldate));
        $document_id = $b_id . $date;
        $data3 = array('doc_id' => $document_id);
        Dcr::where('count', $count)->where('department', $department)->update($data3);
        $data2 = Dcr::latest('created_at')->first();
        $dcrs_id = $data2->id;
        $document_data = [];
        foreach ($req->docu as $key => $value) {
            //$date = date('Y-m-d', strtotime('+1 day', strtotime($value['erevdate'])));
            $data = array(
                'id' => $dcrs_id,
                'department' => $departmentname,
                'name' => $name,
                'rid' => $employee_id,
                'ndocname' => $value['ndocname'],
                'ndocnumber' => $value['ndocnumber'],
                //'erevnumber' => $value['erevnumber'],
                //'nrevnumber' => $value['erevnumber'] + 1,
                //'erevdate' => $value['erevdate'],
                //'nrevdate' => $date,
                'doctype' => $doctype,
                'status' => 'Pending',
            );
            $document_data[] = array(
                'dcrs_id' => $dcrs_id,
                'department' => $department,
                'name' => $name,
                'rid' => $employee_id,
                'ndocname' => $value['ndocname'],
                'ndocnumber' => $value['ndocnumber'],
                //'erevnumber' => $value['erevnumber'],
                //'nrevnumber' => $value['erevnumber'] + 1,
                //'erevdate' => $value['erevdate'],
                //'nrevdate' => $date,
                'doctype' => $doctype,
                'status' => 'Pending',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            );
            //mail for hod
            $hod = Departments::where('departmentname', $departmentname)->pluck('hod');
            Mail::to($hod)->send(new DocumentAdditionRequestRaised($data));
        }
        Document::insert($document_data);
        if ($cer != "NULL") {
            $d = Dcr::latest('created_at')->first();
            $dcrid = $d->id;
            $certificate = $d->documents;
            $expl = explode(',', $certificate);
            foreach ($expl as $certificate) {
                $data1 = array('id' => $dcrid, 'documents' => $certificate, 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
                //inserting values to activity table superadmin
                $activity = "New Document Change Request is added with Document ID " . $dcrs_id . ", with Department " . $department . " and with Name " . $name;
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
                Viewactivity::insert($datas);
                //end of superadmin codes
                Multidoc::insert($data1);
            }
        }

        Session::flash('msg', "Request sent for approval Successfully");
        $models = Modenadisable::first();

        return redirect()->back()->with([
            'model' => $models,
        ]);
    }
    public function view()
    {
        $sw = 0;
        //below is superadmin related codes
        $documentmanagement = Modpageload::where('id', 1)->get();
        if (sizeof($documentmanagement) == 0) {
            Modpageload::insert(['id' => 1, 'correctiveaction' => 0, 'riskmanagement' => 0, 'objectives' => 0, 'opportunity' => 0, 'compliance' => 0, 'enviaspects' => 0, 'auditmanagement' => 0, 'ohsrisk' => 0, 'documentmanagement' => 1, 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')]);
        } else {
            $modpageload = Modpageload::find(1);
            $modpageload->documentmanagement += 1;
            $modpageload->save();
        }
        //end of superadmin codes
        // //--------------------View register----------------
        $departments = Departments::get();
        $mrs = Doccontroller::pluck('email');
        $mrsArr = Doccontroller::pluck('email')->toArray();
        $doctypes = Doctype::get();
        //------------------------ Master list---------------
        $controllers = Doccontroller::get();

        $list = collect([]);
        foreach ($controllers as $c) {
            $list[] = $c->email;
        }
        $mrdoc = $list->unique();
        $data1 = Masterdoc::with('doctype')->where('status', '!=', 'Removal Request Approved')
            ->where('status', '!=', 'Deleted')
            ->orderby('level', 'asc')
            ->orderby('type', 'asc')
            ->orderby('docnumber', 'asc')
            ->paginate(1000);
        $hodEmails = Departments::pluck('hod')->toArray();
        // $hodDepartmentNames = Departments::pluck('departmentname')->toArray();
        $hodDepartmentNames = Departments::pluck('id')->toArray();
        foreach ($data1 as $value) {
            $value->sw = 0;
            switch ($value->status) {
                case "-":
                    $value->status_class = "text-muted";
                    break;
                case "Change Request Approved":
                    $value->status_class = "badge badge-success";
                    break;
                case "Change Request Reviewed OK":
                case "Removal Request Reviewed OK":
                    $value->status_class = "badge badge-info";
                    break;
                case "Change Request Disapproved":
                case "Removal Request Disapproved":
                case "Change Request Reviewed Not OK":
                case "Removal Request Reviewed Not OK":
                    $value->status_class = "badge badge-danger";
                    break;
                case "Change Request Raised":
                case "Removal Request Raised":
                    $value->status_class = "badge badge-warning";
                    break;
            }
            if ((in_array(Auth::user()->email, $hodEmails) && in_array($value->department, $hodDepartmentNames) && ($value->status == 'Change Request Raised' || $value->status == "Removal Request Raised")) || (in_array(Auth::user()->email, $mrsArr) && ($value->status == "Change Request Reviewed OK" || $value->status == "Removal Request Reviewed OK")) || (($value->postedby == Auth::user()->employeeid) && $value->status == "Change Request Reviewed Not OK" || $value->status == "Removal Request Reviewed Not OK" || $value->status == "Change Request Disapproved" || $value->status == "Removal Request Disapproved")) {
                $value->sw = 1;
            }
        }
        $hods = Departments::get();
        $models = Modenadisable::First();

        return view('user.document.viewlogs')->with([
            'data1' => $data1,
            'mrs' => $mrs,
            'mrdoc' => $mrdoc,
            'departments' => $departments,
            'doctypes' => $doctypes,
            'hods' => $hods,
            'model' => $models,
            'hodEmails' => $hodEmails,
            'sw' => $sw,
            'doccontrollers'=>$mrsArr
        ]);
    }
    public function register()
    {

        $controllers = Doccontroller::get();
        $representatives = Representative::get();
        $list = collect([]);
        foreach ($controllers as $c) {
            $list[] = $c->email;
        }
        foreach ($representatives as $representative) {
            $list[] = $representative->email;
        }
        $mrdoc = $list->unique();
        $departments = Departments::get();
        $mrs = Doccontroller::pluck('email');
        $doctypes = Doctype::get();
        $data = Dcr::with('docType')->orderby('id', 'desc')->paginate(1000);
        $models = Modenadisable::first();

        return view('user.document.register')->with([
            'data' => $data,
            'mrs' => $mrs,
            'mrdoc' => $mrdoc,
            'departments' => $departments,
            'doctypes' => $doctypes,
            'model' => $models,
        ]);
    }
    //DAR delete
    public function dardelete(int $id)
    {

        //inserting data to activities table
        $name = Dcr::where('id', $id)->pluck('doc_id');
        $activity = "A document is Removed with ID " . $name;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
        Viewactivity::insert($datas);
        Dcr::where('id', $id)->delete();
        // Document::where('id', $id)->delete();
        Document::where('dcrs_id', $id)->delete();
        DB::table('multidocs')->where('id', $id)->delete();
        $models = Modenadisable::first();

        return redirect(route('doc.register'))->with([
            'model' => $models,
        ]);
    }

    //open
    public function open(int $id)
    {

        $controllers = Doccontroller::get();
        $representatives = Representative::get();

        $list = collect([]);
        foreach ($controllers as $c) {
            $list[] = $c->email;
        }

        foreach ($representatives as $representative) {
            $list[] = $representative->email;
        }

        $mrdoc = $list->unique();
        $mrs = Doccontroller::pluck('email');

        $docs = Multidoc::get();
        $data = Dcr::join('document', 'document.dcrs_id', '=', 'dcrs.id')
            ->select(
                'dcrs.id',
                'dcrs.department',
                'dcrs.rid',
                'dcrs.name',
                'dcrs.doctype',
                'dcrs.hod',
                'dcrs.doc_id',
                'dcrs.type',
                'dcrs.postedby',
                'dcrs.id',
                'dcrs.date',
                'document.id',
                'document.erevnumber',
                'document.edocnumber',
                'document.edocname',
                'document.erevdate',
                'document.ndocnumber',
                'document.ndocname',
                'document.nrevdate',
                'document.nrevnumber',
                'document.status',
                'dcrs.reviewed_at'
            )
            ->where('document.dcrs_id', '=', $id)
            ->paginate(1000);
        $hods = Departments::get();
        $department = Dcr::where('id', '=', $id)->value('department');
        $status = Dcr::where('id', '=', $id)->value('status');
        $models = Modenadisable::First();

        return view('user.document.open')->with([
            'data' => $data,
            'mrs' => $mrs,
            'id' => $id,
            'docs' => $docs,
            'mrdoc' => $mrdoc,
            'hods' => $hods,
            'department' => $department,
            'status' => $status,
            'model' => $models,
        ]);
    }

    //reviewed ok button for hod in open
    public function ok(int $id)
    {
        $reviewdate = date('Y-m-d H:i:s', time());
        $data = array(
            'status' => 'Reviewed OK',
            'reviewed_at' => $reviewdate,
        );
        Dcr::where('id', '=', $id)->update($data);
        // Document::where('id', '=', $id)->update($data);
        Document::where('dcrs_id', '=', $id)->update($data);

        //mail for mr to notify reviewed docs
        // $document = Document::findOrFail($id);
        // $document = Document::where('dcrs_id',$id)->first();
        // dd($document);
        // $department = $document->department;
        // $docu = $document->ndocname;
        // $ndocnumber = $document->ndocnumber;
        // $status = $document->status;
        // $type = $document->doctype;
        // $departmentname = DB::table('departments')->where('id',$department)->value('departmentname');
        // $data = array(
        //     'department' => $departmentname,
        //     'ndocnumber' => $ndocnumber,
        //     'ndocname' => $docu,
        //     'doctype' => $type,
        //     'status' => $status,
        // );

        //----mail to mr to notify reviewed docs------
        $documents = Document::where('dcrs_id', $id)->get();
        foreach ($documents as $document) {
            $department = $document->department;
            $docu = $document->ndocname;
            $ndocnumber = $document->ndocnumber;
            $status = $document->status;
            $type = $document->doctype;
            $departmentname = DB::table('departments')->where('id', $department)->value('departmentname');
            $maildata = array(
                'department' => $departmentname,
                'ndocnumber' => $ndocnumber,
                'ndocname' => $docu,
                'doctype' => $type,
                'status' => $status,
            );
            $mr = Representative::pluck('email');
            Mail::to($mr)->send(new DocumentAdditionRequestReviewed($maildata));
        }

        //inserting values to activity table superadmin code
        $activity = "Document Status Updated to [Reviewed OK] where Document ID " . $id;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
        Viewactivity::insert($datas);
        //end of superadmin code
        $models = Modenadisable::first();

        return redirect(route('doc.register'))->with([
            'model' => $models,
        ]);
    }
    //reviewed not ok button for hod in open
    public function notok(int $id)
    {

        $reviewdate = date('Y-m-d H:i:s', time());
        $data = array(
            'status' => 'Reviewed Not OK',
            'reviewed_at' => $reviewdate,
        );
        Dcr::where('id', '=', $id)->update($data);
        // Document::where('id', '=', $id)->update($data);
        Document::where('dcrs_id', '=', $id)->update($data);
        //insert data to activity table superadmin code
        $activity = "Document Status Updated to [Reviewed Not OK] where Document ID " . $id;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
        Viewactivity::insert($datas);
        //end of superadmin code
        $models = Modenadisable::first();

        return redirect(route('doc.register'))->with([
            'model' => $models,
        ]);
    }

    //resubmit for raised
    public function resubmit(int $id)
    {

        $docs = Multidoc::get();
        $data = Dcr::where('id', $id)->get();
        //$data1 = Document::where('id', $id)->get();
        $data1 = Document::where('dcrs_id', $id)->get();
        $controllers = Doccontroller::get();
        $representatives = Representative::get();
        $list = collect([]);
        foreach ($controllers as $c) {
            $list[] = $c->email;
        }
        foreach ($representatives as $representative) {
            $list[] = $representative->email;
        }
        $mrdoc = $list->unique();
        $models = Modenadisable::first();

        return view('user/document/resubmit')->with([
            'data' => $data,
            'mrdoc' => $mrdoc,
            'docs' => $docs,
            'id' => $id,
            'data1' => $data1,
            'model' => $models,
        ]);
    }
    public function resubmitdoc(Request $req)
    {

        $id = $req->input('posted');
        if ($req->hasFile('documents')) {
            $image_array = $req->file('documents');
            $array_len = count($image_array);
            $name = [];
            for ($i = 0; $i < $array_len; $i++) {
                $image_size = $image_array[$i]->getClientOriginalName();
                $image_ext = $image_array[$i]->getClientOriginalExtension();
                $new_image_name = $image_size;
                $destination_path = public_path('storage/documents');
                $image_array[$i]->move($destination_path, $new_image_name);
                //read the datas into the array
                $name[] = $new_image_name;
            }
            foreach ($name as $f) {
                $a[] = $f;
            }
            $cer = implode(',', $name);
        } else {
            $cer = "NULL";
        }

        $remarks = $req->input('reason');
        $data1 = array(
            'reason' => $remarks,
            'status' => 'Pending',
            'documents' => $cer,
        );

        Dcr::where('id', $id)->update($data1);
        //inserting values to activity table superadmincode
        $activity = "Document Request is  Resubmitted and  status Updated to Pending where  Document Id is" . $id;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
        Viewactivity::insert($datas);
        //end of superadmin code
        if ($cer != "NULL") {
            $d = Dcr::where('id', $id)->first();
            $id = $d->id;
            $certificate = $d->documents;
            $expl = explode(',', $certificate);
            foreach ($expl as $cer) {
                $data = array('id' => $id, 'documents' => $cer, 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
                Multidoc::insert($data);
            }
        }

        // $data2 = Document::where('id', $id)->get();
        // $m = count($req->ndocnumber);
        // $sno = $req->input('sno');
        // $n = 0;
        // foreach ($data2 as $dat) {
        //     foreach ($sno as $s) {
        //         if ($dat->sno == $s && $n < $m) {
        //             $data = array(
        //                 'ndocnumber' => $req->ndocnumber[$n],
        //                 'ndocname' => $req->ndocname[$n],
        //                 'status' => 'Pending',
        //             );
        //             Document::where('sno', $s)->update($data);
        //         }
        //     }
        //     $n = $n + 1;
        // }

        $data2 = Document::where('dcrs_id', $id)->get();
        $m = count($req->ndocnumber);
        $documents_id = $req->input('documents_id');
        $n = 0;
        foreach ($data2 as $dat) {
            foreach ($documents_id as $document_id) {
                if ($dat->id == $document_id && $n < $m) {
                    $data = array(
                        'ndocnumber' => $req->ndocnumber[$n],
                        'ndocname' => $req->ndocname[$n],
                        'status' => 'Pending',
                    );
                    Document::where('id', $document_id)->update($data);
                }
            }
            $n = $n + 1;
        }

        $models = Modenadisable::first();

        return redirect(route('doc.register'))->with([
            'model' => $models,
        ]);
    }

    //delete add request
    public function deleteaddrequest($id)
    {
        //insert data to activity table superadmin code
        $name = Dcr::where('id', $id)->value('doc_id');
        $activity = "Document Add Request is deleted with Document Id" . $name;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
        Viewactivity::insert($datas);

        Dcr::where('id', $id)->delete();
        DB::table('document')->where('dcrs_id', $id)->delete();
        DB::table('multidocs')->where('id', $id)->delete();

        $models = DB::table('modenadisable')->First();
        return redirect('/user/register')->with('model', $models)->with('msg', 'Add Request Details deleted successfully');
    }

    //approve button for doc contrl in open
    public function addss(int $id)
    {
        $docs = Multidoc::get();
        $data = Dcr::where('id', $id)->get();
        //$data1 = Document::where('id', $id)->get();
        $data1 = Document::where('dcrs_id', $id)->get();
        $controllers = Doccontroller::get();
        $representatives = Representative::get();

        $list = collect([]);
        foreach ($controllers as $c) {
            $list[] = $c->email;
        }

        foreach ($representatives as $representative) {
            $list[] = $representative->email;
        }

        $mrdoc = $list->unique();
        $models = Modenadisable::First();

        return view('user/document/hodreview')->with([
            'docs' => $docs,
            'mrdoc' => $mrdoc,
            'data' => $data,
            'id' => $id,
            'data1' => $data1,
            'model' => $models,
        ]);
    }
    public function hodreview(Request $req)
    {
        $id = $req->input('posted');
        $level = $req->input('level');
        $reviewdate = date('Y-m-d H:i:s', time());
        $data1 = array(
            'status' => 'Approved',
            'reviewed_at' => $reviewdate,
        );
        Dcr::where('id', $id)->update($data1);
        //superadmin code
        $activity = "Document Status Updated to Approved where Document ID " . $id;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
        Viewactivity::insert($datas);
        //end of superadmin code

        // $data2 = Document::where('id', $id)->get();
        // $m = count($req->ndocnumber);
        // $sno = $req->input('sno');
        // //$i=$req->input('revno');
        // $revdate = $req->input('revdate');
        // $n = 0;

        // foreach ($data2 as $dat) {
        //     foreach ($sno as $s) {
        //         if ($dat->sno == $s && $n < $m) {
        //             $data = array(
        //                 'ndocnumber' => $req->ndocnumber[$n],
        //                 'ndocname' => $req->ndocname[$n],
        //                 'status' => 'Approved',
        //                 'reviewed_at' => $reviewdate,
        //             );
        //             Document::where('sno', $s)->update($data);
        //             /*-----mail for hod to notify approved documents ---- */
        //             $doctype = $dat->doctype;
        //             $department = $dat->department;
        //             $data = array(
        //                 'ndocnumber' => $req->ndocnumber[$n],
        //                 'ndocname' => $req->ndocname[$n],
        //                 'doctype' => $doctype,
        //             );
        //
        //             $hod = Departments::where('id', $department)->value('hod');
        //             Mail::to($hod)->send(new DocumentAdditionRequestApproved($data));
        //         }
        //     }
        //     $n = $n + 1;
        // }
        // $da = Document::where('id', $id)->get();

        $data2 = Document::where('dcrs_id', $id)->get();
        $m = count($req->ndocnumber);
        $documents_id = $req->input('documents_id');
        $revdate = $req->input('revdate');
        $n = 0;

        foreach ($data2 as $dat) {
            foreach ($documents_id as $document_id) {
                if ($dat->id == $document_id && $n < $m) {
                    $data = [
                        'ndocnumber' => $req->ndocnumber[$n],
                        'ndocname' => $req->ndocname[$n],
                        'erevnumber' => $req->erevnumber[$n],
                        'erevdate' => $req->erevdate[$n],
                        'status' => 'Approved',
                        'reviewed_at' => $reviewdate,
                    ];
                    Document::where('id', $document_id)->update($data);
                    /*-----mail for hod to notify approved documents ---- */
                    $doctype = $dat->doctype;
                    $department = $dat->department;
                    $data = array(
                        'ndocnumber' => $req->ndocnumber[$n],
                        'ndocname' => $req->ndocname[$n],
                        'doctype' => $doctype,
                    );
                    $hod = Departments::where('id', $department)->value('hod');
                    Mail::to($hod)->send(new DocumentAdditionRequestApproved($data));
                }
            }
            $n = $n + 1;
        }

        $da = Document::where('dcrs_id', $id)->get();
        foreach ($da as $dat) {
            $department = $dat->department;
            $document = $dat->ndocname;
            $docnumber = $dat->ndocnumber;
            $erevnumber = $dat->erevnumber;
            $nrevnumber = $dat->nrevnumber;
            $erevdate = $dat->erevdate;
            $nrevdate = $dat->nrevdate;
            $type = $dat->doctype;
            $rid = $dat->rid;
            //$hods = Departments::where('departmentname', $department)->pluck('hod');
            $hods = Departments::where('id', $department)->pluck('hod');
            foreach ($hods as $hod) {
                $hodemail = $hod;
            }
            $date = date('Y-m-d H:i:s', time());
            $data = [
                'department' => $department,
                'document' => $document, 'docnumber' => $docnumber,
                'revnumber' => $erevnumber, 'nrevnumber' => $nrevnumber,
                'erevdate' => $erevdate, 'nrevdate' => $nrevdate,
                'type' => $type, 'level' => $level,
                'created_at' => $date,
                'postedby' => $rid, 'hod' => $hodemail,
            ];
            Masterdoc::insert($data);
            //inserting to dochistory
            $rdate = Dcr::where('id', $id)->pluck('date');
            foreach ($rdate as $d) {
                $reqdate = $d;
                $year = date('Y', strtotime($reqdate));
            }
            $reqdate = Carbon::createFromFormat('d-m-Y h:i:s A', $reqdate)->format('Y-m-d H:i:s');
            $reasons = Dcr::where('id', $id)->pluck('reason');
            foreach ($reasons as $r) {
                $reason = $r;
            }
            $request = "AR";
            $exist = Dochistory::where('request', $request)->first();
            if ($exist) {
                $c = Dochistory::where('request', $request)->pluck('count');
                foreach ($c as $count) {
                    $count = $count + 1;
                }
            } else {
                $count = "1";
            }

            $hdata = array(
                'count' => $count, 'request' => 'AR', 'department' => $department,
                'docname' => $document, 'docno' => $docnumber,
                'erevnumber' => $erevnumber, 'nrevnumber' => $nrevnumber,
                'erevdate' => $erevdate, 'nrevdate' => $nrevdate,
                'reqdate' => $reqdate, 'reason' => $reason,
                'approvedate' => $date, 'year' => $year,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            );

            Dochistory::insert($hdata);
            $c = "AR";
            $document_id = $c . $count;
            // $data3 = array('id' => $document_id);
            $data3 = array('document_id' => $document_id);
            Dochistory::where('request', $c)->where('count', $count)->update($data3);
        }
        $models = Modenadisable::First();

        return redirect(route('doc.register'))->with([
            'model' => $models,
        ]);
    }

    //disapprove button for doc control in open
    public function disapprove(int $id)
    {

        $reviewdate = date('Y-m-d H:i:s', time());
        $data = array(
            'status' => 'Disapproved',
            'reviewed_at' => $reviewdate,
        );
        Dcr::where('id', '=', $id)->update($data);
        // Document::where('id', '=', $id)->update($data);
        Document::where('dcrs_id', '=', $id)->update($data);
        //$da = Document::where('id', $id)->get();

        /**----sending mail to hod----- */
        $da = Document::where('dcrs_id', $id)->get();
        foreach ($da as $dat) {
            $department = $dat->department;
            $data = array(
                'ndocnumber' => $dat->ndocnumber,
                'ndocname' => $dat->ndocname,
                'doctype' => $dat->doctype,
            );
            //$hod = Departments::where('departmentname', $department)->pluck('hod');
            $hod = Departments::where('id', $department)->pluck('hod');
            Mail::to($hod)->send(new DocumentAdditionRequestDisapproved($data));
        }

        //insert data to activity controller superadmin code
        $activity = "Document Rejected and updated status to [Rejected] where Document ID " . $id;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
        Viewactivity::insert($datas);
        //end of superadmin codes
        $models = Modenadisable::first();

        return redirect(route('doc.register'))->with([
            'model' => $models,
        ]);
    }

    public function explorer()
    {

        $email = User::where('id', Auth::user()->id)->pluck('email');
        $dept = Departments::pluck('hod');
        $controllers = Doccontroller::get();
        $representatives = Representative::get();
        $u = User::pluck('email');
        $list = collect([]);
        foreach ($controllers as $c) {
            $list[] = $c->email;
        }
        foreach ($representatives as $representative) {
            $list[] = $representative->email;
        }
        $doc = $list->unique();
        $rep = Representative::pluck('email');
        $models = Modenadisable::first();

        return view('user.document.explorer')->with([
            'email' => $email,
            'dept' => $dept,
            'doc' => $doc,
            'rep' => $rep,
            'model' => $models,
        ]);
    }
    //add master form
    public function addmaster()
    {
        $departments = Departments::all();
        $mrs = Doccontroller::pluck('email');
        $doctypes = Doctype::get();
        $controllers = Doccontroller::get();
        $representatives = Representative::get();

        $list = collect([]);
        foreach ($controllers as $c) {
            $list[] = $c->email;
        }

        foreach ($representatives as $representative) {
            $list[] = $representative->email;
        }

        $mrdoc = $list->unique();
        $models = Modenadisable::First();

        return view('user.document.addmaster')->with([
            'mrs' => $mrs,
            'mrdoc' => $mrdoc,
            'departments' => $departments,
            'doctypes' => $doctypes,
            'model' => $models,
        ]);
    }

    public function addmasterlist(DocumentAddMasterListRequest $req)
    {

        $department = $req->input('department');
        //$hods = Departments::where('departmentname', $department)->pluck('hod');
        $hods = Departments::where('id', $department)->pluck('hod');
        foreach ($hods as $hod) {
            $hodemail = $hod;
        }
        $date = date('Y-m-d H:i:s', time());
        $typeId = $req->input('type');
        $type = Doctype::where('id', $typeId)->value('typename');
        $postedby = $req->input('post');
        foreach ($req->docu as $key => $value) {
            $nrevdate = date('Y-m-d', strtotime('+1 day', strtotime($value['revdate'])));
            $data[] = array(
                'department' => $department,
                'document' => $value['docname'],
                'docnumber' => $value['docnumber'],
                'type' => $type,
                'doctype_id' => $typeId,
                'level' => $value['level'],
                'revnumber' => $value['revnumber'],
                'nrevnumber' => $value['revnumber'] + 1,
                'erevdate' => $value['revdate'],
                'nrevdate' => $nrevdate,
                'created_at' => $date,
                'hod' => $hodemail,
                'postedby' => $postedby,
            );

            //inserted data into activity table superadmin code
            $activity = "Document is added with Document Number " . $value['docnumber'] . " ,document " . $value['docname'] . " and with department " . $department;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas[] = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
        }
        Masterdoc::insert($data);
        ViewActivity::insert($datas);
        //end of superadmi code
        Session::flash('msg', "Documents entered Master List Successfully");
        $models = Modenadisable::First();

        return redirect()->back()->with([
            'model' => $models,
        ]);
    }

    public function changereq(string $id)
    {

        $id = explode(',', $id);
        $m = count($id);
        $n = 0;
        foreach ($id as $d) {
            if ($n < $m) {
                $data[] = Masterdoc::where('id', $d)->first();
            }
            $n = $n + 1;
        }
        $controllers = Doccontroller::get();
        $representatives = Representative::get();
        $list = collect([]);
        foreach ($controllers as $c) {
            $list[] = $c->email;
        }
        foreach ($representatives as $representative) {
            $list[] = $representative->email;
        }
        $mrdoc = $list->unique();
        $models = Modenadisable::First();

        return view('user/document/changerequest')->with([
            'mrdoc' => $mrdoc,
            'data' => $data,
            'id' => $id,
            'model' => $models,
        ]);
    }

    public function changerequest(Request $req)
    {

        if ($req->hasFile('documents')) {
            $image_array = $req->file('documents');
            $array_len = count($image_array);
            $name = [];
            for ($i = 0; $i < $array_len; $i++) {
                $image_size = $image_array[$i]->getClientOriginalName();
                $image_ext = $image_array[$i]->getClientOriginalExtension();
                $new_image_name = $image_size;
                $destination_path = public_path('storage/documents');
                $image_array[$i]->move($destination_path, $new_image_name);
                //read the datas into the array
                $name[] = $new_image_name;
            }
            foreach ($name as $f) {
                $a[] = $f;
            }
            $cer = implode(',', $name);
        } else {
            $cer = "NULL";
        }
        $m = count($req->docnumber);
        $changedate = date('Y-m-d H:i:s', time());
        $affected = $req->input('affected');
        $reason = $req->input('reason');
        $sno = $req->input('sno');
        $m = count($sno);
        $n = 0;
        foreach ($sno as $d) {
            if ($n < $m) {
                $data2[] = Masterdoc::where('id', $d)->first();
            }
            $n = $n + 1;
        }
        $n = 0;
        foreach ($data2 as $dat) {
            foreach ($sno as $s) {
                if ($dat->id == $s && $n < $m) {
                    $data = array(
                        'affected' => $affected,
                        'reason' => $reason,
                        'status' => 'Change Request Raised',
                        'changedate' => $changedate,
                        'documents' => $cer,
                    );
                    Masterdoc::where('id', $s)->update($data);
                    //insert data to activity table superadmin code
                    $activity = "Document Status Updated as [Change Request Raised] where Document ID " . $s;
                    $date = Carbon::now()->toDateTimeString();
                    $date = Carbon::parse($date);
                    $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
                    Viewactivity::insert($datas);
                    //end of superadmin code
                    //mail for hod

                    //foreach ($data2 as $d) {
                    $number = $dat->docnumber;
                    $document = $dat->document;
                    $type = $dat->type;
                    $department = $dat->department;
                    $hod = $dat->hod;

                    $data = array(
                        'docnumber' => $number,
                        'document' => $document,
                        'type' => $type,
                    );
                    Mail::to($hod)->send(new DocumentChangeRequestRaised($data));
                    //}
                }
            }
            $n = $n + 1;
        }
        $models = Modenadisable::First();

        return redirect(route('doc.home'))->with([
            'model' => $models,
        ]);
    }

    //change request hod review
    public function creview(int $id)
    {

        $data = Masterdoc::where('id', $id)->get();
        $controllers = Doccontroller::get();
        $representatives = Representative::get();
        $list = collect([]);
        foreach ($controllers as $c) {
            $list[] = $c->email;
        }
        foreach ($representatives as $representative) {
            $list[] = $representative->email;
        }
        $mrdoc = $list->unique();
        $models = Modenadisable::First();

        return view('user/document/creview')->with([
            'mrdoc' => $mrdoc,
            'data' => $data,
            'id' => $id,
            'model' => $models,
        ]);
    }
    public function changeok(int $id)
    {

        $reviewdate = date('Y-m-d H:i:s', time());
        $data = array(
            'status' => 'Change Request Reviewed OK',
            'reviewedat' => $reviewdate,
        );
        Masterdoc::where('id', '=', $id)->update($data);
        $doc = Masterdoc::find($id);
        $data = array(
            'docnumber' => $doc->docnumber,
            'document' => $doc->document,
            'type' => $doc->type,
            'status' => $doc->status,
        );
        $representative_emails = Representative::pluck('email');
        Mail::to($representative_emails)->send(new DocumentChangeRequestReviewed($data));
        //insert data into activity table superadmin
        $activity = "Document Change Request is Reviewed and Status Updated as [Change Request Reviewed OK] where Document ID " . $id;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
        Viewactivity::insert($datas);
        //end of superadmin code
        $models = Modenadisable::first();

        return redirect(route('doc.home'))->with([
            'model' => $models,
        ]);
    }
    public function changenotok(int $id)
    {

        $reviewdate = date('Y-m-d H:i:s', time());
        $data = array(
            'status' => 'Change Request Reviewed Not OK',
            'reviewedat' => $reviewdate,
        );
        Masterdoc::where('id', '=', $id)->update($data);
        //insert data into activity table superadmin code
        $activity = "Document Change Request is Rejected and Status Updated as [Change Request Reviewed Not OK] where Document ID " . $id;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
        Viewactivity::insert($datas);
        //end of superadmin code
        $models = Modenadisable::First();

        return redirect(route('doc.home'))->with([
            'model' => $models,
        ]);
    }

    //edit change request
    public function editchangerequest($id)
    {
        $masterdata = Masterdoc::find($id);
        $models = Modenadisable::First();

        return view('user.document.editchangerequest')
            ->with('masterdata', $masterdata)
            ->with('model', $models);
    }

    public function updatechangerequest(Request $req)
    {
        $id = $req->id;
        $affected = $req->input('affected');
        $reason = $req->input('reason');

        if ($req->hasFile('document_attachments')) {
            $image_array = $req->file('document_attachments');
            $array_len = count($image_array);
            $name = [];
            for ($i = 0; $i < $array_len; $i++) {
                $image_size = $image_array[$i]->getClientOriginalName();
                $image_ext = $image_array[$i]->getClientOriginalExtension();
                $new_image_name = $image_size;
                $destination_path = public_path('storage/documents');
                $image_array[$i]->move($destination_path, $new_image_name);
                //read the datas into the array
                $name[] = $new_image_name;
            }
            foreach ($name as $f) {
                $a[] = $f;
            }
            $cer = implode(',', $name);
        } else {
            $cer = $req->existing_document;
        }

        Masterdoc::where('id', $id)->update([
            'affected' => $affected,
            'reason' => $reason,
            'status' => 'Change Request Raised',
            'documents' => $cer,
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        //sending mail to HOD
        $maildata = array('docnumber' => $req->docnumber, 'document' => $req->document, 'type' => $req->type);
        Mail::to($req->hod)->send(new DocumentChangeRequestUpdated($maildata));

        //insert data to activity table superadmin code
        $activity = "Change Request Updated for Document Id" . $id;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
        Viewactivity::insert($datas);

        $models = DB::table('modenadisable')->First();
        return redirect()->back()->with('model', $models)->with('msg', 'Change Request Details updated successfully');
    }

    //delete change request
    public function deletechangerequest($id)
    {
        Masterdoc::where('id', $id)->update([
            'status' => '',
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        //insert data to activity table superadmin code
        $document_number = Masterdoc::where('id', $id)->value('docnumber');
        $activity = "Document Status Updated as NULL for Document Number" . $document_number;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
        Viewactivity::insert($datas);

        $models = DB::table('modenadisable')->First();
        return redirect('/user/docmgmt')->with('model', $models)->with('msg', 'Change Request data Details deleted successfully');
    }

    //master review
    public function masterreview(int $id)
    {
        $data = Masterdoc::where('id', $id)->get();
        $controllers = Doccontroller::get();
        $representatives = Representative::get();
        $list = collect([]);
        foreach ($controllers as $c) {
            $list[] = $c->email;
        }
        foreach ($representatives as $representative) {
            $list[] = $representative->email;
        }
        $mrdoc = $list->unique();
        $models = Modenadisable::first();

        return view('user/document/masterreview')->with([
            'mrdoc' => $mrdoc,
            'data' => $data,
            'id' => $id,
            'model' => $models,
        ]);
    }
    public function approvemaster(Request $req)
    {

        $id = $req->input('posted');
        $reviewdate = date('Y-m-d H:i:s', time());
        $nrevdate = $req->input('date');
        if ($nrevdate == null) {
            $data = array(
                'status' => 'Change Request Approved',
                'ch_approved' => 'YES',
                'reviewedat' => $reviewdate,
            );
            Masterdoc::where('id', '=', $id)->update($data);
        }
        if ($nrevdate != null) {
            $data = array(
                'status' => 'Change Request Approved',
                'ch_approved' => 'YES', 'nrevdate' => $nrevdate,
                'reviewedat' => $reviewdate,
            );
            Masterdoc::where('id', '=', $id)->update($data);
        }
        //inserting to dochistory
        $doc = Masterdoc::find($id);
        $department = $doc->department;
        $document = $doc->document;
        $docnumber = $doc->docnumber;
        $erevnumber = $doc->revnumber;
        $nrevnumber = $doc->nrevnumber;
        $erevdate = $doc->erevdate;
        $nrevdate = $doc->nrevdate;
        $reqdate = $doc->changedate;
        $year = date('Y', strtotime($reqdate));
        $chapproved = $doc->ch_approved;
        $reason = $doc->reason;
        $date = date('Y-m-d H:i:s', time());
        //mail for hod to notify approved change requested doc
        $type = $doc->type;
        $hod = $doc->hod;
        $data = array(
            'docnumber' => $docnumber,
            'document' => $document,
            'type' => $type,
        );
        Mail::to($hod)->send(new DocumentChangeRequestApproved($data));
        $request = "CR";
        $exist = Dochistory::where('request', $request)->first();
        if ($exist) {
            $c = Dochistory::where('request', $request)->pluck('count');
            foreach ($c as $count) {
                $count = $count + 1;
            }
        } else {
            $count = "1";
        }

        $hdata = array(
            'count' => $count, 'request' => 'CR', 'department' => $department,
            'docname' => $document, 'docno' => $docnumber,
            'erevnumber' => $erevnumber, 'nrevnumber' => $nrevnumber,
            'erevdate' => $erevdate, 'nrevdate' => $nrevdate,
            'reqdate' => $reqdate,
            'approvedate' => $date, 'reason' => $reason, 'ch_approved' => $chapproved, 'year' => $year,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        );

        Dochistory::insert($hdata);
        $c = "CR";
        $document_id = $c . $count;
        //$data3 = array('id' => $document_id);
        $data3 = array('document_id' => $document_id);
        Dochistory::where('count', $count)->where('request', $c)->update($data3);
        //insert data into activity table superadmin
        $activity = "Document Change Request is Approved and Status Updated as [Approved] where Document ID " . $id;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
        Viewactivity::insert($datas);
        //end of superadmin code
        $models = Modenadisable::First();

        return redirect(route('doc.home'))->with([
            'model' => $models,
        ]);
    }
    public function disapprovemaster(int $id)
    {

        $reviewdate = date('Y-m-d H:i:s', time());
        $data = array(
            'status' => 'Change Request Disapproved',
            'reviewedat' => $reviewdate,
        );
        Masterdoc::where('id', '=', $id)->update($data);
        $doc = Masterdoc::where('id', $id)->first();
        $department = $doc->department;
        $document = $doc->document;
        $docnumber = $doc->docnumber;
        $type = $doc->type;
        $hod = $doc->hod;
        $data = array(
            'docnumber' => $docnumber,
            'document' => $document,
            'type' => $type,
        );
        Mail::to($hod)->send(new DocumentChangeRequestDisapproved($data));
        //insert data into activity table superadmin code
        $activity = "Document Change Request is Rejected and Status Updated as [Disapproved] where Document ID " . $id;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
        Viewactivity::insert($datas);
        //end of superadmin code
        $models = Modenadisable::first();

        return redirect(route('doc.home'))->with([
            'model' => $models,
        ]);
    }
    //delete request
    public function deletereq(int $id)
    {

        //inserting data to activities table
        $name = Masterdoc::where('id', $id)->pluck('document');
        $activity = "A document is Removed with Name " . $name;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
        Viewactivity::insert($datas);
        Masterdoc::where('id', $id)->delete();
        $models = ModenaDisable::First();

        return redirect(route('doc.home'))->with([
            'model' => $models,
        ]);
    }

    //removal request
    public function removereq(string $id)
    {

        $id = explode(',', $id);
        $m = count($id);
        $n = 0;
        foreach ($id as $d) {
            if ($n < $m) {
                $data[] = Masterdoc::where('id', $d)->first();
            }
            $n = $n + 1;
        }
        $controllers = Doccontroller::get();
        $representatives = Representative::get();
        $list = collect([]);
        foreach ($controllers as $c) {
            $list[] = $c->email;
        }

        foreach ($representatives as $representative) {
            $list[] = $representative->email;
        }
        $mrdoc = $list->unique();
        $models = Modenadisable::First();

        return view('user/document/removalrequest')->with([
            'mrdoc' => $mrdoc,
            'data' => $data,
            'id' => $id,
            'model' => $models,
        ]);
    }
   /*
    public function removalrequest(Request $req)
    {

        $m = count($req->docnumber);
        $removedate = date('Y-m-d H:i:s', time());
        $reason = $req->input('reason');
        $sno = $req->input('sno');
        $m = count($sno);
        $n = 0;
        foreach ($sno as $d) {
            if ($n < $m) {
                $data2[] = Masterdoc::where('id', $d)->first();
            }
            $n = $n + 1;
        }
        $n = 0;
        foreach ($data2 as $dat) {
            foreach ($sno as $s) {
                if ($dat->id == $s && $n < $m) {
                    $data = array(
                        'reason' => $reason,
                        'updated_at' => date('Y-m-d H:i:s', time()),
                        'status' => 'Removal Request Raised',
                        'removedate' => $removedate,
                    );
                    Masterdoc::where('id', $s)->update($data);
                    //insert data to activity table superadmin code
                    $activity = "Document Status Updated as [Removal Request Raised] where Document ID " . $s;
                    $date = Carbon::now()->toDateTimeString();
                    $date = Carbon::parse($date);
                    $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
                    Viewactivity::insert($datas);
                    //end of superadmin code
                    //mail for hod
                    foreach ($data2 as $d) {
                        $number = $d->docnumber;
                        $document = $d->document;
                        $type = $d->type;
                        $department = $d->department;
                        $hod = $d->hod;
                        $data = array(
                            'docnumber' => $number,
                            'document' => $document,
                            'type' => $type,
                        );
                        Mail::to($hod)->send(new DocumentRemovalRequestRaised($data));
                    }
                }
            }
            $n = $n + 1;
        }
        $models = Modenadisable::First();

        return redirect(route('doc.home'))->with([
            'model' => $models,
        ]);
    }
    */
    public function removalrequest(Request $req)
    {
        $removedate = date('Y-m-d H:i:s', time());
        $reason = $req->input('reason');
        $ids = $req->input('sno');
        $update_data = array(
                        'reason' => $reason,
                        'updated_at' => date('Y-m-d H:i:s', time()),
                        'status' => 'Removal Request Raised',
                        'removedate' => $removedate,
                    );
        foreach($ids as $id){
            Masterdoc::where('id', $id)->update($update_data);
            //email sending to hods
             $current_data = Masterdoc::where('id',$id)->first();
             $emaildata = array(
                                 'docnumber' => $current_data->docnumber,
                                 'document' => $current_data->document,
                                 'type' => $current_data->type,
                              );
            Mail::to($current_data->hod)->send(new DocumentRemovalRequestRaised($emaildata));

            //--------insert data to activity table superadmin code---------//
            $activity = "Document Status Updated as [Removal Request Raised] where Document ID " . $id;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email,
                        'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'),
                        'activity' => $activity, 'type' => 'user', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
            Viewactivity::insert($datas);
            //--------end of superadmin code-----------//
        }
        $models = Modenadisable::First();

        return redirect(route('doc.home'))->with([
            'model' => $models,
        ]);
    }
    //removal request hod review
    public function rreview(int $id)
    {

        $data = Masterdoc::where('id', $id)->get();
        $controllers = Doccontroller::get();
        $representatives = Representative::get();
        $list = collect([]);
        foreach ($controllers as $c) {
            $list[] = $c->email;
        }
        foreach ($representatives as $representative) {
            $list[] = $representative->email;
        }
        $mrdoc = $list->unique();
        $models = Modenadisable::first();

        return view('user/document/rreview')->with([
            'mrdoc' => $mrdoc,
            'data' => $data,
            'id' => $id,
            'model' => $models,
        ]);
    }
    public function removeok(int $id)
    {

        $reviewdate = date('Y-m-d H:i:s', time());
        $data = array(
            'status' => 'Removal Request Reviewed OK',
            'reviewedat' => $reviewdate,
        );
        Masterdoc::where('id', '=', $id)->update($data);
        //mail for mr to notify approved removal request
        $doc = Masterdoc::find($id);
        $document = $doc->document;
        $docnumber = $doc->docnumber;
        $status = $doc->status;
        $type = $doc->type;
        $data = array('docnumber' => $docnumber, 'document' => $document, 'type' => $type, 'status' => $status);
        $representative_emails = Representative::pluck('email');
        Mail::to($representative_emails)->send(new DocumentRemovalRequestReviewed($data));
        //insert data into activity table superadmin
        $activity = "Document Removal Request is Reviewed and Status Updated as [Removal Request Reviewed OK] where Document ID " . $id;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
        Viewactivity::insert($datas);
        //end of superadmin code
        $models = Modenadisable::first();

        return redirect(route('doc.home'))->with([
            'model' => $models,
        ]);
    }
    public function removenotok(int $id)
    {

        $reviewdate = date('Y-m-d H:i:s', time());
        $data = array(
            'status' => 'Removal Request Reviewed Not OK',
            'reviewedat' => $reviewdate,
        );
        Masterdoc::where('id', '=', $id)->update($data);
        //insert data into activity table superadmin code
        $activity = "Document Removal Request is Rejected and Status Updated as [Removal Request Reviewed Not OK] where Document ID " . $id;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
        Viewactivity::insert($datas);
        //end of superadmin code
        $models = Modenadisable::First();

        return redirect(route('doc.home'))->with([
            'model' => $models,
        ]);
    }

    //edit removal request
    public function editremoverequest($id)
    {
        $masterdata = Masterdoc::find($id);
        $models = Modenadisable::First();

        return view('user.document.editremovalrequest')
            ->with('masterdata', $masterdata)
            ->with('model', $models);
    }

    public function updateremoverequest(Request $req)
    {
        $id = $req->id;
        Masterdoc::where('id', $id)->update([
            'reason' => $req->reason,
            'status' => 'Removal Request Raised',
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        //sending mail to HOD
        $maildata = array('docnumber' => $req->docnumber, 'document' => $req->document, 'type' => $req->type);
        Mail::to($req->hod)->send(new DocumentRemovalRequestUpdated($maildata));

        //insert data to activity table superadmin code
        $activity = "Removal Request Updated for Document Id" . $id;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
        Viewactivity::insert($datas);

        $models = DB::table('modenadisable')->First();

        return redirect()->back()->with('model', $models)->with('msg', 'Removal Request Details updated successfully');
    }

    //delete removal request
    public function deleteremoverequest($id)
    {
        Masterdoc::where('id', $id)->update([
            'status' => '',
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        //insert data to activity table superadmin code
        $document_number = Masterdoc::where('id', $id)->value('docnumber');
        $activity = "Document Status Updated as NULL for Document Number" . $document_number;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
        Viewactivity::insert($datas);

        $models = DB::table('modenadisable')->First();
        return redirect('/user/docmgmt')->with('model', $models)->with('msg', 'Change Request data Details deleted successfully');
    }

    //master review
    public function removereview(int $id)
    {

        $data = Masterdoc::where('id', $id)->get();
        $controllers = Doccontroller::get();
        $representatives = Representative::get();
        $list = collect([]);
        foreach ($controllers as $c) {
            $list[] = $c->email;
        }
        foreach ($representatives as $representative) {
            $list[] = $representative->email;
        }
        $mrdoc = $list->unique();
        $models = Modenadisable::First();

        return view('user/document/removereview')->with([
            'mrdoc' => $mrdoc,
            'data' => $data,
            'id' => $id,
            'model' => $models,
        ]);
    }
    public function approveremove(int $id)
    {

        $reviewdate = date('Y-m-d H:i:s', time());
        $data = array(
            'status' => 'Removal Request Approved',
            'reviewedat' => $reviewdate,
        );
        Masterdoc::where('id', '=', $id)->update($data);
        //inserting to dochistory
        //inserting to dochistory
        $doc = Masterdoc::find($id);
        // $da=DB::table('document')->where('ndocnumber',$number)->get();
        $department = $doc->department;
        $document = $doc->document;
        $docnumber = $doc->docnumber;
        $erevnumber = $doc->revnumber;
        $nrevnumber = $doc->nrevnumber;
        $erevdate = $doc->erevdate;
        $nrevdate = $doc->nrevdate;
        $reqdate = $doc->removedate;
        $year = date('Y', strtotime($reqdate));
        $chapproved = $doc->ch_approved;
        //   $id=$dat->id;
        //$reason = $doc->updated_at;
        $reason = $doc->reason;
        $date = date('Y-m-d H:i:s', time());

        //mail for hod to notify approved change requested doc
        $type = $doc->type;
        $hod = $doc->hod;
        $data = array(
            'docnumber' => $docnumber,
            'document' => $document,
            'type' => $type,
        );

        Mail::to($hod)->send(new DocumentRemovalRequestApproved($data));

        $request = "RR";
        $exist = Dochistory::where('request', $request)->first();
        if ($exist) {
            $c = Dochistory::where('request', $request)->pluck('count');
            foreach ($c as $count) {
                $count = $count + 1;
            }
        } else {
            $count = "1";
        }

        $hdata = array(
            'count' => $count, 'request' => 'RR', 'department' => $department,
            'docname' => $document, 'docno' => $docnumber,
            'erevnumber' => $erevnumber, 'nrevnumber' => $nrevnumber,
            'erevdate' => $erevdate, 'nrevdate' => $nrevdate,
            'reason' => $reason,
            'reqdate' => $reqdate,
            'approvedate' => $date,
            'ch_approved' => $chapproved, 'year' => $year,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        );
        Dochistory::insert($hdata);
        $c = "RR";
        $document_id = $c . $count;
        //$data3 = array('id' => $document_id);
        $data3 = array('document_id' => $document_id);
        Dochistory::where('count', $count)->where('request', $c)->update($data3);

        $models = Modenadisable::First();

        return redirect(route('doc.home'))->with([
            'model' => $models,
        ]);
    }
    public function disapproveremove(int $id)
    {

        $reviewdate = date('Y-m-d H:i:s', time());
        $data = array(
            'status' => 'Removal Request Disapproved',
            'reviewedat' => $reviewdate,
        );
        Masterdoc::where('id', '=', $id)->update($data);
        $doc = Masterdoc::find($id);
        $document = $doc->document;
        $docnumber = $doc->docnumber;
        $type = $doc->type;
        $hod = $doc->hod;
        $data = array(
            'docnumber' => $docnumber,
            'document' => $document,
            'type' => $type,
        );

        Mail::to($hod)->send(new DocumentRemovalRequestDisapproved($data));
        $models = Modenadisable::first();

        return redirect(route('doc.home'))->with([
            'model' => $models,
        ]);
    }

    public function editreq(string $id)
    {

        $id = explode(',', $id);
        $m = count($id);
        $n = 0;
        foreach ($id as $d) {
            if ($n < $m) {
                $data[] = Masterdoc::where('id', $d)->first();
            }
            $n = $n + 1;
        }
        $controllers = Doccontroller::get();
        $representatives = Representative::get();
        $list = collect([]);
        foreach ($controllers as $c) {
            $list[] = $c->email;
        }
        foreach ($representatives as $representative) {
            $list[] = $representative->email;
        }
        $mrdoc = $list->unique();
        $doctypes = Doctype::get();
        $models = Modenadisable::First();

        return view('user/document/editrequest')->with([
            'mrdoc' => $mrdoc,
            'doctypes' => $doctypes,
            'data' => $data,
            'id' => $id,
            'model' => $models,
        ]);
    }

    public function editrequest(Request $req)
    {

        $m = count($req->docnumber);
        $sno = $req->input('sno');
        $m = count($sno);
        $n = 0;
        foreach ($sno as $d) {
            if ($n < $m) {
                $data2[] = DB::table('masterdocs')->where('id', $d)->first();
            }
            $n = $n + 1;
        }
        $n = 0;
        foreach ($data2 as $dat) {
            foreach ($sno as $s) {
                if ($dat->id == $s && $n < $m) {
                    $data = array(
                        'docnumber' => $req->docnumber[$n],
                        'document' => $req->document[$n],
                        'level' => $req->level[$n],
                        'type' => $req->type[$n],
                    );
                    Masterdoc::where('id', $s)->update($data);
                    //insert data to activity table superadmin code
                    $activity = "Document Status Updated as [Change Request Raised] where Document ID " . $s;
                    $date = Carbon::now()->toDateTimeString();
                    $date = Carbon::parse($date);
                    $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
                    Viewactivity::insert($datas);
                    //end of superadmin code
                }
            }
            $n = $n + 1;
        }

        $models = Modenadisable::First();

        return redirect(route('doc.home'))->with([
            'model' => $models,
        ]);
    }

    public function history()
    {
        //--------------------View register----------------
        $departments = Departments::get();
        $mrs = Departments::pluck('hod');
        $doctypes = Doctype::get();

        $controllers = Doccontroller::get();
        $representatives = Representative::get();

        $list = collect([]);
        foreach ($controllers as $c) {
            $list[] = $c->email;
        }
        foreach ($representatives as $representative) {
            $list[] = $representative->email;
        }
        $mrdoc = $list->unique();
        //-------------------dochistory------------------------
        $data2 = Dochistory::paginate(1000);
        $models = Modenadisable::First();

        return view('user.document.history')->with([
            'data2' => $data2,
            'mrs' => $mrs,
            'mrdoc' => $mrdoc,
            'departments' => $departments,
            'doctypes' => $doctypes,
            'mrdoc' => $mrdoc,
            'model' => $models,
        ]);
    }

    public function analysis()
    {
        //----------------Analysis-------------
        $chart = new DocAnalysis;
        $chart->title("Documents-Type Wise");
        $processes = Doctype::select('typename')->orderBy('typename', 'asc')->pluck('typename')->toArray();
        $process = [];
        foreach ($processes as $key) {
            $process[] = $key;
        }
        $chart->labels($process);
        $phigh = Masterdoc::select(DB::raw('COUNT(masterdocs.type) as number'), 'doctypes.typename')
            ->rightjoin('doctypes', function ($join) {
                $join->on('masterdocs.type', '=', 'doctypes.typename')
                    ->where('masterdocs.status', '!=', 'Removal Request Approved');
            })->groupby('doctypes.typename')->orderBy('doctypes.typename', 'asc')->get();
        $highp = [];
        foreach ($phigh as $key) {
            $highp[] = $key->number;
        }
        $chart->dataset('No of Documents', 'bar', $highp)->color('#FFD700')->backgroundcolor('#FFD700');
        $chart1 = new DocAnalysis;
        $chart1->title("Total AR,CR and RR");
        $processes = ['AR', 'CR', 'RR'];
        $process = [];
        foreach ($processes as $key) {
            $process[] = $key;
        }
        // $chart1->labels($process);
        $dar = Dochistory::select(DB::raw('COUNT(request) as number'))->where('request', 'AR')->get();
        $dcr = Dochistory::select(DB::raw('COUNT(request) as number'))->where('request', 'CR')->get();
        $drr = Dochistory::select(DB::raw('COUNT(request) as number'))->where('request', 'RR')->get();
        $ar = [];
        foreach ($dar as $key) {
            $ar[] = $key->number;
        }
        $cr = [];
        foreach ($dcr as $key) {
            $cr[] = $key->number;
        }
        $rr = [];
        foreach ($drr as $key) {
            $rr[] = $key->number;
        }
        $chart1->dataset('AR', 'bar', $ar)->backgroundcolor('#f7533e')->color('#f7533e');
        $chart1->dataset('CR', 'bar', $cr)->backgroundcolor('#FFD700')->color('#FFD700');
        $chart1->dataset('RR', 'bar', $rr)->backgroundcolor('#a6ff00')->color('#a6ff00');
        $chart1->height(400);
        $models = Modenadisable::first();

        return view('user.document.analysis')->with([
            'chart' => $chart,
            'chart1' => $chart1,
            'model' => $models,
        ]);
    }
    public function governancehub(Request $request)
    {
        $datas = GovernanceHub::with('doctype')->where('is_deleted', false)
            ->join('doctypes', 'governance_hubs.doctype_id', '=', 'doctypes.id')
            ->select('governance_hubs.*', 'doctypes.level as document_level')
            ->orderBy('document_level', 'asc')
            ->get();

        // Apply custom alphanumeric sorting to the collection
        $datas = $datas->sortBy(function ($item) {
            return [
                $item->document_level,
                $this->getAlphanumericSortKey($item->document_number)
            ];
        })->values();

        $controllers = Doccontroller::get();
        $representatives = Representative::get();
        $departments = Departments::get();
        $users = User::where('is_email_verified', true)->get();
        $list = collect([]);
        foreach ($controllers as $c) {
            $list[] = $c->email;
        }

        foreach ($representatives as $representative) {
            $list[] = $representative->email;
        }
        $mrdoc = $list->unique();
        $priorities = ['High', 'Medium', 'Low'];
        $notifications = ['Monthly', 'Weekly', 'Daily', 'Every 3 Days', 'Every 2 Days'];
        $models = Modenadisable::first();
        $types = Doctype::orderBy('level', 'asc')->get();
        $docTypeIds = $datas->pluck('doctype_id')->toArray();
        $typenames = Doctype::whereIn('id', $docTypeIds)->orderBy('level')->pluck('typename')->toArray();
        $alltypenames = implode(',', $typenames);
        $allowners = GovernanceHub::pluck('owner')->toArray();
        $owners = array_unique($allowners);
        $departmentnames = array();
        foreach ($owners as $owner) {
            $dept_names = DB::table('departments')->where('id', $owner)->value('departmentname');
            if ($dept_names) {
                array_push($departmentnames, $dept_names);
            }
        }
        $alldeptnames = implode(',', $departmentnames);
        $document_comments = GovernanceHubComment::take(10)->where('is_deleted', false)
            ->latest('id')
            ->get();

        //checking comment notification
        $get_user_id = User::where('email', Auth::user()->email)->value('id');
        $comment_count = GovernanceHubComment::where('is_deleted', false)
            ->where('read_by_user', 'not LIKE', '%' . $get_user_id . '%')
            ->count();
        return view('user.document.governancehub')
            ->with([
                'mrdoc' => $mrdoc,
                'model' => $models,
                'datas' => $datas,
                'controllers' => $controllers,
                'departments' => $departments,
                'users' => $users,
                'priorities' => $priorities,
                'notifications' => $notifications,
                'alldeptnames' => $alldeptnames,
                'alltypenames' => $alltypenames,
                'document_comments' => $document_comments,
                'comment_count' => $comment_count,
                'types' => $types
            ]);
    }
    public function updateusercommentnotification(Request $request)
    {
        $email = $request->input('email_value');
        $user_id = User::where('email', $request->input('email_value'))->value('id');
        $allusers = GovernanceHubComment::where('is_deleted', false)
            ->where('read_by_user', '!=', 0)
            ->get();

        if (count($allusers) > 0) {
            $allids = GovernanceHubComment::where('is_deleted', false)
                ->where('read_by_user', '!=', null)
                ->pluck('read_by_user')->toArray();

            $updated_read_user = array_unique($allids);
            $string_users = implode(',', $updated_read_user);
            $filter_users = array_filter(explode(',', $string_users));
            array_push($filter_users, $user_id);
            $final_ursd = implode(',', $filter_users);
            GovernanceHubComment::where('is_deleted', false)
                ->update(['read_by_user' => $final_ursd]);

        } else {
            GovernanceHubComment::where('is_deleted', false)
                ->update(['read_by_user' => $user_id]);
        }
        return response()->json([
            'status' => 200,
        ]);
    }
    public static function getreviewsaverage($dataid)
    {
        $total_reviews = GovernanceHubDocumentReview::where('document_id', $dataid)->get()->count();
        if ($total_reviews > 0) {
            $review_stars = GovernanceHubDocumentReview::where('document_id', $dataid)->sum('score');
            $average_get = $review_stars / $total_reviews;
            $average = number_format($average_get, 1);
        } else {
            $average = 0;
        }

        return $average;
    }
    public static function getreviewspercent($avg)
    {
        $percent = (round($avg) / 5) * 100;
        return $percent;
    }

    /**
     * Retrieve a new document number based on the document type.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function retrieveDocumentNumber(Request $request): JsonResponse
    {
        $typeId = $request->input('type');
        $newDocumentNumber = $this->getDocNoBasedOnTypeId($typeId);

        return response()->json(['document_number' => $newDocumentNumber]);
    }

    /**
     * Check if a document number already exists in the governance hub.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function checkDocumentNumber(Request $request): JsonResponse
    {
        $documentNumber = $request->input('document_number');
        $documentId = $request->input('document_id');
        if (!$documentNumber) {
            return response()->json(['error' => 'Document number is required'], 400);
        }

        $exists = GovernanceHub::where('document_number', $documentNumber)
            ->where('is_deleted', false)
            ->where(function ($query) use ($documentId) {
                if ($documentId) {
                    $query->where('id', '!=', $documentId);
                }
            })
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    public function saveDocument(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'document_name' => 'required|string|max:75|unique:governance_hubs,document_name',
            'document_number' => 'required|string|max:75|unique:governance_hubs,document_number',
            'type' => 'required||exists:doctypes,id',
            'revision' => 'nullable|integer',
            'date' => 'required|date',
            'owner' => 'required|integer',
            'prepared_by' => 'required|string|min:5|max:255',
            'reviewed_by' => 'required|string|min:5|max:255',
            'approved_by' => 'required|string|min:5|max:255',
        ],
            [
                'owner.integer' => 'The owner is required.',
                'prepared_by.min' => 'The prepared by field is required.',
                'reviewed_by.min' => 'The reviewed by field is required.',
                'approved_by.min' => 'The approved by field is required.',
            ]);
        $error = null;
        if ($validator->fails()) {
            return [
                'error' => $validator->errors()->all(),
            ];
        }
        if ($error == null) {
            $typeId = $request->input('type');
            $docType = Doctype::find($typeId);
            $data = new GovernanceHub();
            $data->document_name = $request->input('document_name');
            $data->doctype_id = $typeId;
            $data->type = $docType ? $docType->typename : null;
            $data->document_number = $request->input('document_number');
            $data->revision = $request->input('revision');
            $data->date = $request->input('date');
            $data->owner = $request->input('owner');
            $data->views = 0;
            $data->prepared_by = $request->input('prepared_by');
            $data->reviewed_by = $request->input('reviewed_by');
            $data->approved_by = $request->input('approved_by');
            $data->is_deleted = false;
            $data->save();
            Session::flash('msg', "Document added successfully");
            return [
                'error' => $error,
            ];
        }
        return [
            'error' => 'An error occurred during approval',
        ];
    }

    public function saveDocumentDraft(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'drafted_by' => 'required|string|max:255',
            'document_name' => 'required|string|max:75',
            'type' => 'required|exists:doctypes,id',
            'owner' => 'required|integer',
        ], ['owner.integer' => 'The owner is required.']);
        if ($validator->fails()) {
            return [
                'error' => $validator->errors()->all(),
            ];
        }
        $error = null;
        if ($error == null) {
            $typeId = $request->input('type');
            $docType = Doctype::find($typeId);
            $data = new GovernanceHubDraft();
            $data->drafted_by = $request->input('drafted_by');
            $data->document_name = $request->input('document_name');
            $data->doctype_id = $typeId;
            $data->type = $docType ? $docType->typename : null;
            $data->date = now()->format('Y-m-d H:i:s');
            $data->owner = $request->input('owner');
            $data->procedure_type = 'new_request';
            $data->status = 'Under Draft';
            $data->save();
            Session::flash('msg', "Document draft added successfully");
            return [
                'error' => $error,
            ];
        }
        return [
            'error' => $error,
        ];
    }

    /**
     * Generate a new document number based on the provided document type ID.
     *
     * @param int $typeId
     * @return string
     */
    public function getDocNoBasedOnTypeId(int $typeId): string
    {
        $type = Doctype::find($typeId);
        $typeFormats = config('constants.document_management.default_document_number');
        $countNumberOfRecord = GovernanceHub::where('doctype_id', $typeId)->count();
        $typeName = strtolower(str_replace(' ', '_', $type->typename));
        $newDocumentNumber = '';

        if (array_key_exists($typeName, $typeFormats)) {
            $newDocumentNumber = sprintf($type->type_code . $typeFormats[$typeName], $countNumberOfRecord + 1);
        } else {
            $newDocumentNumber = sprintf($type->type_code . '%d', $countNumberOfRecord + 1);
        }

        $currentlyDocumentNumberList = GovernanceHub::pluck('document_number')->toArray();
        while (in_array($newDocumentNumber, $currentlyDocumentNumberList)) {
            $countNumberOfRecord++;
            if (array_key_exists($typeName, $typeFormats)) {
                $newDocumentNumber = sprintf($type->type_code . $typeFormats[$typeName], $countNumberOfRecord + 1);
            } else {
                $newDocumentNumber = sprintf($type->type_code . '%d', $countNumberOfRecord + 1);
            }
        }

        return $newDocumentNumber;
    }

    public function updateViews(Request $request, int $id)
    {
        $document = GovernanceHub::where('id', $id)->where('is_deleted', false)->firstOrFail();
        // Update views count
        $document->views += 1;
        $document->save();
        return response()->json(['message' => 'Views count updated successfully']);
    }
    public function addContent(int $id)
    {
        $model = ModenaDisable::first();
        $document = GovernanceHub::with('doctype')->where('id', $id)->where('is_deleted', false)->firstOrFail();
        return view('user.document.add_content')->with([
            'id' => $id,
            'document' => $document,
            'model' => $model,
        ]);
    }
    public function insertContent(AddContentRequest $request)
    {
        $data = $request->validated();
        $id = $data['document_id'];
        $models = ModenaDisable::first();
        $document_title = $data['title'];
        $document_type_id = $data['type'];
        $document_type = Doctype::where('id', $document_type_id)->value('typename');
        $document_res_array = $request->input('responsibility');
        $rich_text_array = $request->input('rich_text');
        try {
            $res = new GovernanceHubDocumentContent();
            $res->document_id = $id;
            $res->title = $document_title;
            $res->doctype_id = $document_type_id;
            $res->type = $document_type;
            $res->save();
            $count = count($rich_text_array);
            for ($i = 0; $i < $count; $i++) {
                $richTextModel = new GovernanceHubDocumentContentRichText();
                $richTextModel->content_id = $res->id;
                $richTextModel->responsibility = $document_res_array[$i];
                $richTextModel->rich_text = $rich_text_array[$i];
                $richTextModel->document_id = $id;
                $richTextModel->save();
            }
            Session::flash('msg', "Content added for the document successfully");
        } catch (\Exception $e) {
            Session::flash('msg', 'Content has not been added for the document.');
        }
        return redirect()->back()->with('models', $models);
    }
    /**----document content excel upload----- */
    public function documentuploadcontent(int $id)
    {
        $model = ModenaDisable::first();
        $data = GovernanceHub::where('id', $id)->firstOrFail();
        return view('user.document.documentuploadcontent')->with([
            'id' => $id,
            'data' => $data,
            'model' => $model,
        ]);
    }
    public function insertdocumentexcelcontent(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'content_data' => 'required|mimes:xls,xlsx',

            ]);

            if ($validator->fails()) {
                $models = DB::table('modenadisable')->First();
                return back()->withErrors($validator)->withInput()->with('model', $models);
            } else {
                $rows = Excel::toArray(new DocumentContentImport, $request->file('content_data'))[0];
                if (count($rows) > 0) {
                    $data = $this->validateContentFileUpload($rows);
                    if (isset($data['error'])) {
                        Session::flash('errmsg', $data['error']);
                        return redirect()->back();
                    }
                    foreach ($data['content'] as $row) {
                        $document_id = $request->document_id;
                        $titledata = array(
                            'title' => $row['title'],
                            'responsibility' => $row['responsibility'],
                            'document_id' => $document_id,
                        );

                        //checking if a title for a particular content already exists in database
                        $titlecheck = GovernanceHubDocumentContent::where('title', $row['title'])
                            ->where('document_id', $document_id)
                            ->where('is_deleted', false)
                            ->get();

                        if (count($titlecheck) > 0) {
                            $latest_id = GovernanceHubDocumentContent::where('title', $row['title'])
                                ->where('document_id', $document_id)
                                ->where('is_deleted', false)
                                ->value('id');
                        } else {
                            GovernanceHubDocumentContent::create($titledata);
                            $latest_data = GovernanceHubDocumentContent::latest('id')->first();
                            $latest_id = $latest_data->id;
                        }
                        $contentdata = array(
                            'content_id' => $latest_id,
                            'rich_text' => $row['rich_text'],
                            'responsibility' => $row['responsibility'],
                            'document_id' => $document_id,
                        );

                        //checking if a content for a particular document and title already exists in database
                        $contentscheck = GovernanceHubDocumentContentRichText::where('rich_text', $row['rich_text'])
                            ->where('content_id', $latest_id)
                            ->where('document_id', $document_id)
                            ->where('is_deleted', false)
                            ->get();

                        if (count($contentscheck) == 0) {
                            GovernanceHubDocumentContentRichText::create($contentdata);
                        } else {
                            foreach ($contentscheck as $check) {
                                GovernanceHubDocumentContentRichText::where('id', $check->id)->update($contentdata);
                            }
                        }
                    }
                }

                if (!File::exists(public_path('/storage/DocumentGovernancehubContent/'))) {
                    File::makeDirectory(public_path() . '/storage/DocumentGovernancehubContent/', 0755, true);
                }

                $name = 'document_content_data' . '.' . $request->file('content_data')->extension();
                $request->file('content_data')->move(public_path() . '/storage/DocumentGovernancehubContent/', $name);
                $models = DB::table('modenadisable')->First();
                return redirect('user/doc/governancehub')
                    ->with('msg', 'Document Content Uploaded successfully')
                    ->with('model', $models);
            }
        } catch (\Exception $exception) {
            Log::error($exception);
            Session::flash('msg', "Content Upload failed");

            return redirect()->back();
        }
    }
    public function procedures(Request $request)
    {
        // Set reasonable memory limit (1GB) for large documents with images
        ini_set('memory_limit', '1024M');

        $id = $request->input('id');
        $comment_id = $request->input('comment');
        $controllers = Doccontroller::get();
        $representatives = Representative::get();
        $list = collect([]);
        foreach ($controllers as $c) {
            $list[] = $c->email;
        }
        foreach ($representatives as $representative) {
            $list[] = $representative->email;
        }
        $mrdoc = $list->unique();
        $models = Modenadisable::first();
        $docname = GovernanceHub::findOrFail($id);
        $doc = GovernanceHub::with('governance_hub_comments.replies')->findOrFail($id);
        $documentContents = GovernanceHubDocumentContent::join('governance_hubs', 'governance_hub_document_contents.document_id', '=', 'governance_hubs.id')
            ->where('governance_hubs.id', '=', $id)
            ->where('governance_hub_document_contents.is_deleted', false)
            ->orderBy('governance_hub_document_contents.position', 'asc')
            ->get();

        $relatedDocumentIds = GovernanceHubDocumentContent::where('document_id', '=', $id)
            ->where('is_deleted', false)
            ->orderBy('position', 'asc')
            ->pluck('id')
            ->toArray();
        $document_reviews = GovernanceHubDocumentReview::where('document_id', $id)->get();
        $document_comments = GovernanceHubComment::where('commentable_id', $id)
            ->where('is_deleted', false)
            ->get();
        $doc_review_score_array = GovernanceHubDocumentReview::where('document_id', $id)->pluck('score')->toArray();
        $doc_review_score_array = array_filter($doc_review_score_array);
        $average_score = count($doc_review_score_array) ? round(array_sum($doc_review_score_array) / count($doc_review_score_array), 2) : 0;
        $serchval = null;
        if ($comment_id != null) {
            $user_id = User::where('email', Auth::user()->email)->value('id');
            $exist = GovernanceHubCommentViewedDetail::where('comment_id', $comment_id)
                ->where('user_id', $user_id)
                ->get();
            if (count($exist) == 0) {
                GovernanceHubCommentViewedDetail::create([
                    'comment_id' => $comment_id,
                    'user_id' => $user_id,
                ]);
            } else {
                GovernanceHubCommentViewedDetail::where('comment_id', $comment_id)
                    ->update([
                        'user_id' => $user_id,
                    ]);
            }
        }
        return view('user.document.procedures')
            ->with([
                'mrdoc' => $mrdoc,
                'model' => $models,
                'id' => $id,
                'document_reviews' => $document_reviews,
                'average_score' => $average_score,
                'docname' => $docname,
                'doc' => $doc,
                'documentContents' => $documentContents,
                'relatedDocumentIds' => $relatedDocumentIds,
                'document_comments' => $document_comments,
                'serchval' => $serchval,
            ]);
    }

    /**
     * Update content position of the document
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function updateContentPosition(Request $request, $id): JsonResponse
    {
        // Set reasonable memory limit (512MB) for large documents
        // Don't use -1 (unlimited) as it can crash the system
        ini_set('memory_limit', '512M');

        $oldContentId = $request->get('old_content_id');
        $oldSubContentId = $request->get('old_sub_content_id');
        $newContentId = $request->get('new_content_id');
        $newSubContentId = $request->get('new_sub_content_id');

        if (!$oldContentId || !$newContentId) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }
        if ($oldContentId != $newContentId) {
            if ($oldSubContentId && $newSubContentId) {
                $oldDocumentContentRichText = GovernanceHubDraftContentRichText::where('content_id', $oldContentId)
                    ->where('id', $oldSubContentId)
                    ->first();
                $newDocumentContentRichText = GovernanceHubDraftContentRichText::where('content_id', $newContentId)
                    ->where('id', $newSubContentId)
                    ->first();

                // Null checks to prevent errors
                if (!$oldDocumentContentRichText || !$newDocumentContentRichText) {
                    \Log::error("❌ Content not found in database!", [
                        'old_content_id' => $oldContentId,
                        'old_sub_content_id' => $oldSubContentId,
                        'old_found' => $oldDocumentContentRichText ? 'YES' : 'NO',
                        'new_content_id' => $newContentId,
                        'new_sub_content_id' => $newSubContentId,
                        'new_found' => $newDocumentContentRichText ? 'YES' : 'NO',
                    ]);

                    return response()->json([
                        'success' => false,
                        'message' => 'Content not found'
                    ]);
                }

                $nextPosition = $newDocumentContentRichText->position + 1;
                $oldDocumentContentRichText->update([
                    'content_id' => $newContentId,
                    'position' => $nextPosition,
                ]);

                $nextDocumentContentOfNewContents = GovernanceHubDraftContent::where('document_id', $id)
                    ->where('position', '>', $newDocumentContentRichText->position)
                    ->orderBy('position', 'asc')
                    ->get();

                foreach ($nextDocumentContentOfNewContents as $item) {
                    $nextPosition += 1;
                    $item->update([
                        'position' => $nextPosition,
                    ]);
                }
            } else {
                $oldDocumentContent = GovernanceHubDraftContent::where('document_id', $id)
                    ->where('id', $oldContentId)
                    ->first();
                $newDocumentContent = GovernanceHubDraftContent::where('document_id', $id)
                    ->where('id', $newContentId)
                    ->first();

                // Null checks to prevent errors
                if (!$oldDocumentContent || !$newDocumentContent) {
                    \Log::warning("updateContentPosition: Document content not found", [
                        'old_id' => $oldContentId,
                        'new_id' => $newContentId
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Document content not found'
                    ]);
                }

                if ($oldDocumentContent->position < $newDocumentContent->position) {
                    $nextDocumentContentOfOldContents = GovernanceHubDraftContent::where('document_id', $id)
                        ->where('position', '>', $newDocumentContent->position)
                        ->orderBy('position', 'asc')
                        ->get();
                    $nextPosition                     = $newDocumentContent->position + 1;
                    $oldDocumentContent->update([
                        'position' => $nextPosition,
                    ]);
                    if (count($nextDocumentContentOfOldContents)) {
                        foreach ($nextDocumentContentOfOldContents as $item) {
                            $nextPosition += 1;
                            $item->update([
                                'position' => $nextPosition,
                            ]);
                        }
                    }
                } else {
                    $previousDocumentContentOfOldContents = GovernanceHubDraftContent::where('document_id', $id)
                        ->where('position', '<', $newDocumentContent->position)
                        ->orderBy('position', 'asc')
                        ->get();
                    $previousPosition                     = $newDocumentContent->position - 1;
                    $oldDocumentContent->update([
                        'position' => $previousPosition,
                    ]);
                    if (count($previousDocumentContentOfOldContents)) {
                        foreach ($previousDocumentContentOfOldContents as $item) {
                            $previousPosition -= 1;
                            $item->update([
                                'position' => $previousPosition,
                            ]);
                        }
                    }
                }
            }
        } else {
            $draggingDocumentRichText = GovernanceHubDraftContentRichText::where('content_id', $oldContentId)
                ->where('id', $oldSubContentId)
                ->where('is_deleted', false)  // ✅ Filter deleted records
                ->first();
            $droppedDocumentRichText = GovernanceHubDraftContentRichText::where('content_id', $newContentId)
                ->where('id', $newSubContentId)
                ->where('is_deleted', false)  // ✅ Filter deleted records
                ->first();

            // ✅ Null check for dragging content
            if (!$draggingDocumentRichText) {
                \Log::warning("updateContentPosition: Dragging content not found", [
                    'old_content_id' => $oldContentId,
                    'old_sub_content_id' => $oldSubContentId
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Dragging content not found or has been deleted'
                ]);
            }

            if (!$droppedDocumentRichText) {
                // Move to top
                $documentList = GovernanceHubDraftContentRichText::where('content_id', $oldContentId)
                    ->where('is_deleted', false)  // ✅ Filter deleted records
                    ->orderBy('position', 'asc')
                    ->get();
                $firstPosition = $documentList->first()->position;
                $draggingDocumentRichText->update([
                    'position' => $firstPosition - 1,
                ]);
            } else {
                // Move to middle & bottom
                $documentRichTextNextItems = GovernanceHubDraftContentRichText::where('content_id', $oldContentId)
                    ->where('id', '!=', $oldSubContentId)
                    ->where('is_deleted', false)  // ✅ Filter deleted records
                    ->where('position', '>', $droppedDocumentRichText->position)
                    ->orderBy('position', 'asc')
                    ->get();

                $droppedDocumentRichTextPosition = $droppedDocumentRichText->position;
                $nextPosition = $droppedDocumentRichTextPosition + 1;
                $draggingDocumentRichText->update([
                    'position' => $nextPosition,
                ]);

                if (count($documentRichTextNextItems)) {
                    foreach ($documentRichTextNextItems as $item) {
                        $nextPosition += 1;
                        $item->update([
                            'position' => $nextPosition,
                        ]);
                    }
                }
            }
        }

        $docname = GovernanceHubDraft::findOrFail($id);

        // Select only needed columns (exclude large rich_text content for drag-drop view)
        $documentContents = GovernanceHubDraftContent::join('governance_hub_drafts', 'governance_hub_draft_contents.document_id', '=', 'governance_hub_drafts.id')
            ->select('governance_hub_draft_contents.id', 'governance_hub_draft_contents.document_id',
                     'governance_hub_draft_contents.title', 'governance_hub_draft_contents.position',
                     'governance_hub_draft_contents.is_deleted')
            ->where('governance_hub_drafts.id', '=', $id)
            ->where('governance_hub_draft_contents.is_deleted', false)
            ->orderBy('governance_hub_draft_contents.position', 'asc')
            ->get();

        $relatedDocumentIds = GovernanceHubDraftContent::where('document_id', '=', $id)
            ->where('is_deleted', false)
            ->orderBy('position', 'asc')
            ->pluck('id')
            ->toArray();

        // OPTIMIZATION: Don't render view - we use DOM swap on frontend!
        // This saves 400-430ms per request (and 100MB of HTML generation)

        return response()->json([
            'success' => true,
            'message' => 'Content position updated successfully',
            // 'view' => $renderedView, ← REMOVED! Not needed with DOM swap
        ]);
    }

    /**
     * Get content for edit modal via AJAX (lazy loading)
     * Note: Returns raw rich_text for editing (not processed HTML)
     * Increases memory limit as rich_text may contain large base64 images
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getContentForEdit(Request $request): JsonResponse
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(300);

        $contentId = $request->input('content_id');

        if (empty($contentId)) {
            return response()->json([
                'success' => false,
                'message' => 'Content ID is required'
            ], 400);
        }

        try {
            $contentIds = GovernanceHubDraftContentRichText::where('content_id', $contentId)
                ->where('is_deleted', false)
                ->orderBy('position', 'asc')
                ->pluck('id');

            $contents = [];

            foreach ($contentIds as $id) {
                $content = GovernanceHubDraftContentRichText::find($id, ['id', 'content_id', 'responsibility', 'rich_text', 'position']);

                if ($content) {
                    $contents[] = [
                        'id' => $content->id,
                        'content_id' => $content->content_id,
                        'responsibility' => $content->responsibility ?? '',
                        'rich_text' => $content->rich_text ?? '',
                        'position' => $content->position
                    ];
                }

                unset($content);
                if (function_exists('gc_collect_cycles')) {
                    gc_collect_cycles();
                }
            }

            return response()->json([
                'success' => true,
                'contents' => $contents
            ]);
        } catch (\Exception $e) {
            \Log::error('getContentForEdit error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading content: ' . $e->getMessage()
            ], 500);
        }
    }

    public function loadEditModal(Request $request)
    {
        $i = $request->input('index');
        $contentId = $request->input('content_id');
        $documentId = $request->input('document_id');

        $documentContent = GovernanceHubDraftContent::where('id', $contentId)
            ->where('document_id', $documentId)
            ->first();

        if (!$documentContent) {
            return response()->json([
                'success' => false,
                'message' => 'Content not found'
            ], 404);
        }

        $relatedDocumentIds = [$i => $contentId];

        $html = view('user.document._prcdedures_draft_item_edit', compact('i', 'relatedDocumentIds', 'documentContent'))->render();

        return response()->json([
            'success' => true,
            'html' => $html,
            'modalId' => 'edit_content_' . $i
        ]);
    }

    public function searchprocedures(Request $request, $id)
    {
        $controllers = Doccontroller::get();
        $representatives = Representative::get();
        $list = collect([]);
        foreach ($controllers as $c) {
            $list[] = $c->email;
        }
        foreach ($representatives as $representative) {
            $list[] = $representative->email;
        }
        $mrdoc = $list->unique();
        $models = Modenadisable::first();
        $docname = GovernanceHub::findOrFail($id);
        $doc = GovernanceHub::with('governance_hub_comments.replies')->findOrFail($id);
        $documentContents = GovernanceHubDocumentContent::join('governance_hubs', 'governance_hub_document_contents.document_id', '=', 'governance_hubs.id')
            ->where('governance_hubs.id', '=', $id)
            ->where('governance_hub_document_contents.is_deleted', false)
            ->get();
        $relatedDocumentIds = GovernanceHubDocumentContent::where('document_id', '=', $id)
            ->where('is_deleted', false)
            ->pluck('id')
            ->toArray();

        if ($request->search == null) {
            $serchval = null;
        } else {
            $serchval = $request->search;
        }
        $document_reviews = GovernanceHubDocumentReview::where('document_id', $id)->get();
        $document_comments = GovernanceHubComment::where('commentable_id', $id)
            ->where('is_deleted', false)
            ->get();
        $doc_review_score_array = GovernanceHubDocumentReview::where('document_id', $id)->pluck('score')->toArray();
        $doc_review_score_array = array_filter($doc_review_score_array);
        $average_score = count($doc_review_score_array) ? round(array_sum($doc_review_score_array) / count($doc_review_score_array), 2) : 0;
        return view('user.document.procedures')
            ->with([
                'mrdoc' => $mrdoc,
                'model' => $models,
                'id' => $id,
                'document_reviews' => $document_reviews,
                'average_score' => $average_score,
                'docname' => $docname,
                'doc' => $doc,
                'documentContents' => $documentContents,
                'relatedDocumentIds' => $relatedDocumentIds,
                'document_comments' => $document_comments,
                'serchval' => $serchval,
            ]);

    }
    //-----------document reference-----------------//
    public function storedocumentreference(Request $request)
    {
        $document_id = $request->input('main_document_id');
        $check = GovernanceHubDocumentReferenceDetail::where('reference_doc_number', $request->input('ref_doc_number'))
            ->where('document_id', $document_id)
            ->get();
        if (count($check) > 0) {
            return response()->json([
                'status' => 403,
                'data' => null,
            ]);
        } else {
            if ($request->input('ref_doc_number') == null) {
                $ref_doc_number = 0;
            } else {
                $ref_doc_number = $request->input('ref_doc_number');
            }
            if ($request->input('ref_doc_name') == null) {
                $ref_doc_name = 0;
            } else {
                $ref_doc_name = $request->input('ref_doc_name');
            }
            $data = array(
                'document_id' => $document_id,
                'reference_doc_number' => $ref_doc_number,
                'reference_doc_name' => $ref_doc_name,
            );

            GovernanceHubDocumentReferenceDetail::create($data);
            return response()->json([
                'status' => 200,
                'data' => null,
            ]);
        }
    }
    //-----------document definition-----------------//
    public function storedocumentdefinition(Request $request)
    {
        $document_id = $request->input('main_document_def_id');
        $check = GovernanceHubDocumentDefinitionDetail::where('term', $request->input('def_term'))
            ->where('document_id', $document_id)
            ->get();
        if (count($check) > 0) {
            return response()->json([
                'status' => 403,
                'data' => null,
            ]);
        } else {
            if ($request->input('def_term') == null) {
                $def_term = 0;
            } else {
                $def_term = $request->input('def_term');
            }
            if ($request->input('definition') == null) {
                $definition = 0;
            } else {
                $definition = $request->input('definition');
            }
            $data = array(
                'document_id' => $document_id,
                'term' => $def_term,
                'definition' => $definition,
            );

            GovernanceHubDocumentDefinitionDetail::create($data);
            return response()->json([
                'status' => 200,
                'data' => null,
            ]);
        }
    }
    /**
     * API endpoint to check if PDF download is complete
     * Returns JSON: { "in_progress": true/false }
     */
    public function checkPdfDownloadStatus()
    {
        $inProgress = session()->has('pdf_download_in_progress');

        return response()->json([
            'in_progress' => $inProgress
        ]);
    }

    public function exportdocumentreportpdf($id)
    {
        session()->put('pdf_download_in_progress', true);

        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 300); // 5 minutes
        set_time_limit(300);

        \App\Helpers\VideoThumbnailHandler::cleanupOldTempFiles();

        $document = GovernanceHub::findOrFail($id);
        $document_name = $document->document_name;
        $document_titles = GovernanceHubDocumentContent::where('document_id', $id)
            ->orderBy('position', 'asc')
            ->where('is_deleted', false)
            ->with(['contentId' => function($query) {
                $query->where('is_deleted', false)
                      ->orderBy('position', 'asc');
            }])
            ->get();
        $client_logos = ClientDetail::first();
        $isAllowExportLogo = env('EXPORT_DOCUMENT_PDF_WITH_LOGO', true);

        // Convert image URLs to base64 for PDF
        foreach ($document_titles as $title) {
            if ($title->contentId) {
                foreach ($title->contentId as $content) {
                    if (!empty($content->rich_text)) {
                        $content->rich_text = $this->convertImagesToBase64ForPDF($content->rich_text);
                    }
                }
            }
        }

        $contentAnalysis = [
            'paper_size' => 'A4',
            'orientation' => 'portrait',
            'dpi' => 120,
            'font_scale' => 'standard'
        ];

        $splitter = new ContentSplitterService();
        $document_titles = $splitter->splitLongContentForPDF($document_titles, true);

        $pdf = PDF::loadView('user/document/procedure_export_pdf', compact('document_titles',
            'client_logos', 'document_name', 'document', 'isAllowExportLogo', 'contentAnalysis'
        ))
            ->setPaper($contentAnalysis['paper_size'], $contentAnalysis['orientation'])
            ->setOptions([
                'dpi' => $contentAnalysis['dpi'],
                'defaultFont' => 'sans-serif',
                'isRemoteEnabled' => true,
                'isPhpEnabled' => true,
                'isFontSubsettingEnabled' => true,
                'isHtml5ParserEnabled' => true,
                'debugKeepTemp' => false,
                'debugCss' => false,
                'debugLayout' => false,
                'debugLayoutLines' => false,
                'debugLayoutBlocks' => false,
                'debugLayoutInline' => false,
                'debugLayoutPaddingBox' => false,
            ]);

        // Remove the download in-progress flag from session (download is complete)
        session()->forget('pdf_download_in_progress');

        return $pdf->download($document_name . '_Procedure_save.pdf');
    }

    public function updateContentText(UpdateContentRichTextRequest $request)
    {
        $error = null;
        $content_id = $request->input('content_id');
        $document_id = $request->input('document_id');
        $title = $request->input('title');
        if (GovernanceHubDocumentContent::where('id', $content_id)->where('document_id', $document_id)->first()) {
            if ($error == null) {
                $affectedRows = GovernanceHubDocumentContent::where('is_deleted', false)
                    ->where('id', $content_id)
                    ->where('document_id', $document_id)
                    ->update([
                        'title' => $title,
                        // 'rich_text' => $rich_text,
                    ]);
                if (GovernanceHubDocumentContent::where('is_deleted', false)->where('id', $content_id)->where('document_id', $document_id)->first()) {
                    $count = count($request->input('rich_text'));
                    // Delete the rich text content which is not in the request
                    $richTextIds = $request->input('text_id');
                    // Remove null value on the array
                    $richTextIds = array_filter($richTextIds, function ($value) {
                        return $value !== 'null';
                    });
                    GovernanceHubDocumentContentRichText::where('content_id', $content_id)
                        ->whereNotIn('id', $richTextIds)
                        ->delete();

                    for ($y = 0; $y < $count; $y++) {
                        $richTextEntry = GovernanceHubDocumentContentRichText::where('is_deleted', false)->where('id', $request->input('text_id')[$y])->first();
                        if ($richTextEntry) {
                            $richTextEntry->update([
                                'responsibility' => $request->input('responsibility')[$y],
                                'rich_text' => $request->input('rich_text')[$y],
                            ]);
                        } else {
                            GovernanceHubDocumentContentRichText::create([
                                'content_id' => $content_id,
                                'responsibility' => $request->input('responsibility')[$y],
                                'rich_text' => $request->input('rich_text')[$y],
                            ]);
                        }
                    }

                    Session::flash('msg', "Document updated successfully");
                } else {
                    Session::flash('msg', "Document is not updated.");
                }
            }
            return [
                'data' => null,
                'error' => $error,
            ];
        } else {
            Session::flash('msg', "Document is not updated.");
            return [
                'data' => null,
                'error' => $error,
            ];
        }
    }
    public function deleteContent(Request $request)
    {
        $id = $request->input('id');
        $data = GovernanceHubDocumentContent::find($id);
        if ($data) {
            GovernanceHubDocumentContent::where('id', $id)->update(['is_deleted' => true]);
            GovernanceHubDocumentContentRichText::where('content_id', $id)->update(['is_deleted' => true]);
            Session::flash('msg', "Document content deleted successfully");
            return response()->json([
                'status' => 200,
            ]);
        } else {
            return response()->json([
                'status' => 404,
            ]);
        }
    }
    public function governancehubchangerequest(AddGovernanceHubChangeRequest $request)
    {
        $user_exists = User::where('email', Auth::user()->email)->first();
        if ($user_exists) {
            $user_id = $user_exists->id;
        } else {
            $user_id = Auth::user()->email;
        }
        $id = $request->main_data_id;
        $document = GovernanceHub::where('id', $id)->first();
        $unique_id = uniqid() . $document->id . $user_id . date('s');
        $data = new GovernanceHubDraft;
        $data->drafted_by = Auth::user()->email;
        $data->governance_hub_id = $document->id;
        $data->document_name = $document->document_name;
        $data->document_number = $document->document_number;
        $data->type = $document->type;
        $data->doctype_id = $document->doctype_id;
        $data->change_request_submitted_date = now()->format('Y-m-d H:i:s');
        $data->change_request_submitted_by = $user_id;
        $data->date = $document->date;
        $data->revision = $document->revision;
        $data->owner = $document->owner;
        $data->procedure_type = 'change_request';
        $data->change_reason = $request->change_reason;
        $data->proposed_changes = $request->proposed_changes;
        $data->document_effects = $request->effect_documents;
        $data->priority_level = $request->priority;
        $data->status = 'Under Draft';
        $data->change_request_unique_id = $unique_id;
        $data->save();

        //storing data into governancehubdraftscontents table

        $documentcontents = GovernanceHubDocumentContent::where('document_id', $id)->get();
        foreach ($documentcontents as $documentcontent) {
            $changedraft = new GovernanceHubDraftContent;
            $changedraft->document_id = $data->id;
            $changedraft->title = $documentcontent->title;
            $changedraft->type = $documentcontent->type;
            $changedraft->doctype_id = $documentcontent->doctype_id;
            $changedraft->position = $documentcontent->position;
            $changedraft->save();

            $documentcontentrichtexts = GovernanceHubDocumentContentRichText::where('content_id', $documentcontent->id)->get();
            foreach ($documentcontentrichtexts as $documentcontentrichtext) {
                $changedraftcontentrich = new GovernanceHubDraftContentRichText;
                $changedraftcontentrich->content_id = $changedraft->id;
                $changedraftcontentrich->responsibility = $documentcontentrichtext->responsibility;
                $changedraftcontentrich->rich_text = $documentcontentrichtext->rich_text;
                $changedraftcontentrich->draft_id = $data->id;
                $changedraftcontentrich->position = $documentcontentrichtext->position;
                $changedraftcontentrich->save();
            }
        }

        GovernanceHub::where('id', $id)->update(['change_request_submit' => 1,
            'change_request_submitted_date' => date('Y-m-d'),
            'change_request_submitted_by' => $user_id,
        ]);

        //storing datas into change request details table
        $insert_data = array(
            'parent_governance_id' => $id,
            'change_requested_date' => date('Y-m-d'),
            'change_requested_submitted_by_user_id' => $user_id,
            'changes_made' => $request->change_reason,
            'unique_id' => $unique_id,
        );
        GovernanceHubChangeRequestDetail::create($insert_data);
        Session::flash('msg', "Change request submitted successfully");
        return response()->json([
            'status' => 200,
            'data' => null,
        ]);
    }
    public function governancehubreaderequest(AddGovernanceHubReadRequest $request)
    {
        try {
            $user_exists = User::where('email', Auth::user()->email)->first();
            $submitted_date = date('Y-m-d');
            foreach ($request->read_users as $read_user) {
                $user_email = User::where('id', $read_user)->value('email');
                $review_exists = GovernanceHubDocumentReview::where('document_id', $request->main_document_data_id)
                    ->where('email', $user_email)
                    ->first();
                $readdatas = new GovernanceHubReadRequestDetail;
                $readdatas->governance_hub_id = $request->main_document_data_id;
                $readdatas->user_id = $read_user;
                $readdatas->read_request_submitted_by = $user_exists ? $user_exists->id : 0;
                $readdatas->read_request_submitted_date = date('Y-m-d');
                $readdatas->notification_interval = $request->notification_schedule;
                $readdatas->notification_duedate = $this->getreadrequestschedule($submitted_date, $request->notification_schedule);
                //$readdatas->review_completed = $review_exists ? 1 : 0;
                $readdatas->save();
            }
            //Email notification sending to all selected users
            $document_name = GovernanceHub::where('id', $request->main_document_data_id)->value('document_name');
            $email_users = User::whereIn('id', $request->read_users)->pluck('email')->toArray();
            $app_url = BaseController::addSlashToEndOfURL();
            $maildata = [
                'sender_name' => $user_exists ? ucfirst($user_exists->name) : '',
                'document_name' => $document_name ? ucfirst($document_name) : '',
                'app_url' => $app_url,
                'id' => $request->main_document_data_id,
            ];
            foreach ($email_users as $email_user) {
                $user_name_details = User::where('email', $email_user)->first();
                $rated_details = array('rate_user' => $user_name_details ? ucfirst($user_name_details->name) : '');
                $email_data = array_merge($maildata, $rated_details);
                Mail::to($email_user)->send(new DocumentReadRateRequestNotification($email_data));
            }
            return response()->json([
                'status' => 200,
            ]);

        } catch (\Exception $exception) {
            Log::error($exception);
            return response()->json([
                'status' => 500,
            ]);
        }
    }
    protected function getreadrequestschedule($start_date, $interval)
    {
        if ($interval == 'Monthly') {
            $due_date = date("Y-m-d", strtotime($start_date . ' + 30 days'));
        } else if ($interval == 'Weekly') {
            $due_date = date("Y-m-d", strtotime($start_date . ' + 7 days'));
        } else if ($interval == 'Daily') {
            $due_date = date("Y-m-d", strtotime($start_date . ' + 1 days'));
        } else if ($interval == 'Every 3 Days') {
            $due_date = date("Y-m-d", strtotime($start_date . ' + 3 days'));
        } else if ($interval == 'Every 2 Days') {
            $due_date = date("Y-m-d", strtotime($start_date . ' + 2 days'));
        }
        return $due_date;
    }
    protected function getuserdetails($value)
    {
        $user_details = User::where('id', $value)->first();
        $user_name = $user_details ? $user_details->name : '';
        return $user_name;
    }
    /**
     * Replace base64 images in HTML with img tags using stored file URLs
     * Saves images to storage and replaces with asset URLs for better performance
     *
     * @param string $html HTML content with base64 images
     * @param int $contentId Content ID for identifying frames
     * @return string Modified HTML with img tags using asset URLs
     */
    private function replaceBase64ImagesWithFrames($html, $contentId)
    {
        if (empty($html)) {
            return $html;
        }

        $imageCounter = 1;

        $pattern = '/<img([^>]*?)src=["\']data:image\/(png|jpe?g|gif|webp);base64,([^"\']+)["\']([^>]*?)>/i';

        $modifiedHtml = preg_replace_callback($pattern, function($matches) use (&$imageCounter, $contentId) {
            try {
                $extension = $matches[2];
                $base64Data = $matches[3];

                // Normalize extension
                if ($extension === 'jpeg' || $extension === 'jpg') {
                    $extension = 'jpg';
                }

                // Decode the base64 image
                $imageData = base64_decode($base64Data);
                if ($imageData === false) {
                    return $matches[0]; // Return original if decoding fails
                }

                // Generate unique hash and filename
                $imageHash = md5($imageData);
                $cacheKey = "image_cache_{$imageHash}";

                // Check if image already exists in cache
                $cachedUrl = \Cache::get($cacheKey);
                if ($cachedUrl) {
                    $cachedPath = str_replace(asset('storage/'), '', $cachedUrl);
                    $cachedPath = str_replace(url('storage/'), '', $cachedPath);

                    if (\Storage::disk('public')->exists("document_images/" . basename($cachedPath))) {
                        $imageCounter++;
                        return '<img src="' . $cachedUrl . '" alt="Document Image" style="max-width: 100%;">';
                    } else {
                        \Cache::forget($cacheKey);
                    }
                }

                // Create new filename and path
                $filename = "{$imageHash}_{$contentId}_{$imageCounter}.{$extension}";
                $storagePath = "document_images/{$filename}";

                // Check if file already exists
                if (!\Storage::disk('public')->exists($storagePath)) {
                    // Save the image to storage
                    \Storage::disk('public')->put($storagePath, $imageData);
                }

                // Generate public URL
                $publicUrl = asset('storage/' . $storagePath);

                // Cache the URL for future use
                \Cache::put($cacheKey, $publicUrl, now()->addDays(30));

                $imageCounter++;

                // Return img tag with asset URL
                return '<img src="' . $publicUrl . '" alt="Document Image" style="max-width: 100%;">';

            } catch (\Exception $e) {
                // On error, return original image tag
                \Log::error('Error processing base64 image in replaceBase64ImagesWithFrames: ' . $e->getMessage());
                return $matches[0];
            }
        }, $html);

        return $modifiedHtml;
    }

    /**
     * Retrieve base64 image data from cache
     * Fast endpoint for lazy loading
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCachedBase64Image(Request $request)
    {
        $cacheKey = $request->input('cache_key');

        if (empty($cacheKey)) {
            return response()->json(['success' => false, 'message' => 'No cache key provided'], 400);
        }

        $base64Data = \Cache::get($cacheKey);

        if (!$base64Data) {
            return response()->json(['success' => false, 'message' => 'Image data not found in cache'], 404);
        }

        return response()->json([
            'success' => true,
            'base64' => $base64Data
        ]);
    }

    /**
     * Process and save a single base64 image via AJAX with caching
     * Uses MD5 hash to detect and reuse identical images
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function processBase64Image(Request $request)
    {
        try {
            if ($request->hasFile('image')) {
                $uploadedFile = $request->file('image');

                if (!$uploadedFile->isValid()) {
                    return response()->json(['success' => false, 'message' => 'Invalid file upload'], 400);
                }

                $imageData = file_get_contents($uploadedFile->getRealPath());
                $extension = $uploadedFile->getClientOriginalExtension() ?: 'jpg';

                if ($extension === 'jpeg') {
                    $extension = 'jpg';
                }

            } else {
                $base64Data = $request->input('base64') ?: $request->input('base64_image');

                if (empty($base64Data)) {
                    return response()->json(['success' => false, 'message' => 'No image data provided'], 400);
                }

                if (preg_match('/^data:image\/(png|jpe?g|gif|webp);base64,(.+)$/i', $base64Data, $matches)) {
                    $extension = $matches[1];
                    $imageData = base64_decode($matches[2]);
                } else {
                    return response()->json(['success' => false, 'message' => 'Invalid base64 format'], 400);
                }

                if ($extension === 'jpeg' || $extension === 'jpg') {
                    $extension = 'jpg';
                }

                if ($imageData === false) {
                    return response()->json(['success' => false, 'message' => 'Failed to decode base64'], 400);
                }
            }

            $imageHash = md5($imageData);
            $cacheKey = "image_cache_{$imageHash}";

            $cachedUrl = \Cache::get($cacheKey);
            if ($cachedUrl) {
                $cachedPath = str_replace(asset('storage/'), '', $cachedUrl);
                $cachedPath = str_replace(url('storage/'), '', $cachedPath);

                if (\Storage::disk('public')->exists("document_images/" . basename($cachedPath))) {
                    return response()->json([
                        'success' => true,
                        'url' => $cachedUrl,
                        'filename' => basename($cachedPath),
                        'cached' => true
                    ]);
                } else {
                    \Cache::forget($cacheKey);
                }
            }

            $contentId = $request->input('content_id', time());
            $imageIndex = $request->input('image_index', 1);
            $filename = "{$imageHash}_{$contentId}_{$imageIndex}.{$extension}";
            $storagePath = "document_images/{$filename}";

            if (\Storage::disk('public')->exists($storagePath)) {
                $publicUrl = asset('storage/' . $storagePath);
                \Cache::put($cacheKey, $publicUrl, now()->addDays(30));

                return response()->json([
                    'success' => true,
                    'url' => $publicUrl,
                    'filename' => $filename,
                    'cached' => true,
                    'source' => 'filesystem'
                ]);
            }

            \Storage::disk('public')->put($storagePath, $imageData);

            $publicUrl = asset('storage/' . $storagePath);
            \Cache::put($cacheKey, $publicUrl, now()->addDays(30));

            return response()->json([
                'success' => true,
                'url' => $publicUrl,
                'filename' => $filename,
                'cached' => false
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function proceduresDraft(int $id)
    {
        ini_set('memory_limit', '1024M');

        $controllers = Doccontroller::get();
        $representatives = Representative::get();
        $list = collect([]);
        foreach ($controllers as $c) {
            $list[] = $c->email;
        }
        foreach ($representatives as $representative) {
            $list[] = $representative->email;
        }
        $mrdoc = $list->unique();
        $models = Modenadisable::first();
        $docname = GovernanceHubDraft::findOrFail($id);

        $documentContents = GovernanceHubDraftContent::join('governance_hub_drafts', 'governance_hub_draft_contents.document_id', '=', 'governance_hub_drafts.id')
            ->where('governance_hub_drafts.id', '=', $id)
            ->where('governance_hub_draft_contents.is_deleted', false)
            ->orderBy('governance_hub_draft_contents.position', 'asc')
            ->get();

        $relatedDocumentIds = GovernanceHubDraftContent::where('document_id', '=', $id)
            ->where('is_deleted', false)
            ->orderBy('position', 'asc')
            ->pluck('id')
            ->toArray();

        // Get all content items and replace base64 images with div frames
        $processedContents = [];

        foreach ($relatedDocumentIds as $index => $contentId) {
            $contents = \App\GovernanceHubDraftContentRichText::where('content_id', $contentId)
                ->where('is_deleted', false)
                ->orderBy('position', 'asc')
                ->get();

            // Replace base64 images with div frames (so browser doesn't try to load them)
            $contentRichText = [];
            foreach ($contents as $content) {
                $contentRichText[] = [
                    'id' => $content->id,
                    'content_id' => $content->content_id,
                    'responsibility' => $content->responsibility,
                    'draft_id' => $content->draft_id,
                    'is_deleted' => $content->is_deleted,
                    'position' => $content->position,
                    'created_at' => $content->created_at,
                    'updated_at' => $content->updated_at,
                    'processed_html' => empty($content->rich_text) ? '' : $this->replaceBase64ImagesWithFrames($content->rich_text, $content->id),
                ];
            }

            $processedContents[$contentId] = $contentRichText;
        }
        $doc = GovernanceHubDraft::with('governance_hub_draft_comments.replies')->findOrFail($id);
        $draft_comments = GovernanceHubDraftComment::where('commentable_id', $id)
            ->where('is_deleted', false)
            ->get();

        return view('user.document.procedures_draft')
            ->with([
                'mrdoc' => $mrdoc,
                'model' => $models,
                'id' => $id,
                'docname' => $docname,
                'documentContents' => $documentContents,
                'relatedDocumentIds' => $relatedDocumentIds,
                'processedContents' => $processedContents,
                'doc' => $doc,
                'draft_comments' => $draft_comments,
            ]);
    }
    public function documentDraft(Request $request)
    {
        $keywordForNewTab = $request->input('n_keyword');
        $newdatas = GovernanceHubDraft::with('documentHubDraftReviewerDetails', 'doctype')
            ->join('doctypes', 'doctypes.id', 'governance_hub_drafts.doctype_id')
            ->select('governance_hub_drafts.*', 'doctypes.level as document_level')
            ->where('governance_hub_drafts.is_deleted', false)
            ->Where(function ($query) {
                $query->where('procedure_type', 'new_request')
                    ->orWhere('procedure_type', '=', null);
            })
            ->where(function ($query) use ($keywordForNewTab) {
                $query->where('document_name', 'like', '%' . $keywordForNewTab . '%')
                    ->orWhere('status', 'like', '%' . $keywordForNewTab . '%')
                    ->orWhere('type', 'like', '%' . $keywordForNewTab . '%')
                    ->orWhere('date', 'like', '%' . $keywordForNewTab . '%')
                    ->orWhereHas('department', function ($query) use ($keywordForNewTab) {
                        $query->where('departmentname', 'like', '%' . $keywordForNewTab . '%');
                    })
                    ->orWhereHas('draftedbydetails', function ($query) use ($keywordForNewTab) {
                        $query->where('name', 'like', '%' . $keywordForNewTab . '%');
                    });
            })
            ->orderBy('governance_hub_drafts.updated_at', 'desc')
            ->get();
        $changedatas = GovernanceHubDraft::with('documentHubDraftReviewerDetails', 'doctype')
            ->join('doctypes', 'doctypes.id', 'governance_hub_drafts.doctype_id')
            ->select('governance_hub_drafts.*', 'doctypes.level as document_level')
            ->where('governance_hub_drafts.is_deleted', false)
            ->where('procedure_type', 'change_request')
            ->orderBy('governance_hub_drafts.updated_at', 'desc')
            ->get();
        $controllers = Doccontroller::get();
        $models = Modenadisable::first();
        $rev = User::where('is_email_verified', true)->where('approved', true)->get();

        $drafts_id = GovernanceHubDraft::pluck('id')->toArray();

        $document_comments = GovernanceHubDraftComment::take(10)
            ->whereIn('commentable_id', $drafts_id)
            ->where('is_deleted', false)
            ->latest('id')
            ->get();
        // Status
        $statusNames = [
            'Under Draft', 'Review Requested', 'Request Rejected',
            'Rejected', 'Draft Approved', 'Draft Reviewed'
        ];
        $allstatusnames = implode(',', $statusNames);
        $typeData = Doctype::orderBy('id', 'desc')->pluck('typename')->toArray();
        /* New data */
        // Type
        $newDocTypeIds = $newdatas->pluck('doctype_id')->toArray();
        $newDocTypeNames = Doctype::whereIn('id', $newDocTypeIds)->orderBy('level')->pluck('typename')->toArray();
        $newtypenames = implode(',', $newDocTypeNames);
        // Department
        $newOwnerList = $newdatas->pluck('owner')->toArray();
        $newOwners = array_unique($newOwnerList);
        $newDeptNames = DB::table('departments')->whereIn('id', $newOwners)->pluck('departmentname', 'id');
        $newdepartmentnames = array_unique($newDeptNames->count() ? $newDeptNames->toArray() : []);
        $allnewdeptnames = implode(',', $newdepartmentnames);
        // Drafted By
        $allnewDraftedBy = $newdatas->pluck('drafted_by')->toArray();
        $newDraftedByUsers = \App\User::whereIn('email', $allnewDraftedBy)->pluck('name', 'email')->toArray();
        $allnewdraftedbynames = implode(',', $newDraftedByUsers);
        // Date
        $allNewDateName = $this->getYearAndMonthToFilter($newdatas->sortByDesc('date'), 'date');

        /* Changed data */
        // Type
        $changeDocTypeIds = $changedatas->pluck('doctype_id')->toArray();
        $changeDocTypeNames = Doctype::whereIn('id', $changeDocTypeIds)->orderBy('level')->pluck('typename')->toArray();
        $changetypenames = implode(',', $changeDocTypeNames);
        // Department
        $changeOwnerList = $changedatas->pluck('owner')->toArray();
        $changeOwners = array_unique($changeOwnerList);
        $changeDeptNames = DB::table('departments')->whereIn('id', $changeOwners)->pluck('departmentname', 'id');
        $changedepartmentnames = array_unique($changeDeptNames->count() ? $changeDeptNames->toArray() : []);
        $allchangedeptnames = implode(',', $changedepartmentnames);
        // Drafted By
        $allchangeDraftedBy = $changedatas->pluck('drafted_by')->toArray();
        $changeDraftedByUsers = \App\User::whereIn('email', $allchangeDraftedBy)->pluck('name', 'email')->toArray();
        $allchangedraftedbynames = implode(',', $changeDraftedByUsers);
        // Date
        $allChangeDateName = $this->getYearAndMonthToFilter($changedatas->sortByDesc('date'), 'date');
        return view('user.document.document_draft')
            ->with([
                'newdatas' => $newdatas,
                'changedatas' => $changedatas,
                'model' => $models,
                'controllers' => $controllers,
                'rev' => $rev,
                'document_comments' => $document_comments,
                'allnewtypenames' => $newtypenames,
                'allnewdeptnames' => $allnewdeptnames,
                'allnewstatusnames' => $allstatusnames,
                'allnewdraftedbynames' => $allnewdraftedbynames,
                'allnewdatenames' => $allNewDateName,
                'allchangetypenames' => $changetypenames,
                'allchangedeptnames' => $allchangedeptnames,
                'allchangestatusnames' => $allstatusnames,
                'allchangedraftedbynames' => $allchangedraftedbynames,
                'allchangedatenames' => $allChangeDateName,
            ]);
    }

    /**
     * @param $dataList
     * @param $column
     * @return string
     */
    private function getYearAndMonthToFilter($dataList, $column)
    {
        $yearvalues = $dataList->map(function ($item) use($column) {
            return $item->{$column} ? date('Y-m', strtotime($item->{$column})) : '';
        });
        $year_arr = $yearvalues->toArray();
        $yearUnique = array_unique($year_arr);
        usort($yearUnique,  function( $x, $y) {
            if (strtotime($x)== strtotime($y))
                return 0;
            if (strtotime($x) < strtotime($y))
                return 1;
            else
                return -1;
        });

        return implode(',', $yearUnique);
    }

    public function addContentDraft(int $id)
    {
        $model = ModenaDisable::first();
        $documentDraft = GovernanceHubDraft::with('doctype')->findOrFail($id);
        return view('user.document.add_content_draft')->with([
            'id' => $id,
            'documentDraft' => $documentDraft,
            'model' => $model,
        ]);
    }
    public function insertContentDraft(AddDraftContentRequest $request)
    {
        $data = $request->validated();
        $id = $data['document_id'];
        $models = ModenaDisable::first();
        $document_title = $data['title'];
        $document_type_id = $data['type'];
        $document_type = Doctype::where('id', $document_type_id)->value('typename');
        $document_res_array = $request->input('responsibility');
        $rich_text_array = $request->input('rich_text');
        try {
            $res = new GovernanceHubDraftContent();
            $res->document_id = $id;
            $res->title = $document_title;
            $res->doctype_id = $document_type_id;
            $res->type = $document_type;
            $res->save();
            $count = count($rich_text_array);
            for ($i = 0; $i < $count; $i++) {
                $richTextModel = new GovernanceHubDraftContentRichText();
                $richTextModel->content_id = $res->id;
                $richTextModel->responsibility = $document_res_array[$i];
                $richTextModel->rich_text = $rich_text_array[$i];
                $richTextModel->draft_id = $id;
                $richTextModel->save();
            }
            Session::flash('msg', "Content added for the draft successfully");
        } catch (\Exception $e) {
            Session::flash('msg', 'Content has not been added for the draft.');
        }
        return redirect()->back()->with('models', $models);
    }
    /**-------draft excel upload content--------- */
    public function draftuploadcontent(int $id)
    {
        $model = ModenaDisable::first();
        $data = GovernanceHubDraft::where('id', $id)->firstOrFail();
        return view('user.document.draftuploadcontent')->with([
            'id' => $id,
            'data' => $data,
            'model' => $model,
        ]);
    }
    public function validateContentFileUpload($rows)
    {
        $data = [];
        foreach ($rows as $row) {
            if (empty($row['title']) && empty($row['rich_text']) && empty($row['responsibility'])) {
                continue;
            }
            if (empty($row['title']) && !empty($row['rich_text'])) {
                $data['error'] = 'Title data is empty. Please refer Note for excel upload format';
                break;
            }
            if (!empty($row['title']) && empty($row['rich_text'])) {
                $data['error'] = 'Content data is empty. Please refer Note for excel upload format';
                break;
            }
            if (!empty($row['title']) && !empty($row['rich_text'])) {
                $data['content'][] = [
                    'title' => $row['title'],
                    'rich_text' => $row['rich_text'],
                    'responsibility' => $row['responsibility']
                ];
            }
        }

        if (count($data) == 0) {
            $data['error'] = 'No valid content found. Please refer Note for excel upload format';
        }

        return $data;
    }

    public function insertdraftexcelcontent(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'content_data' => 'required|mimes:xls,xlsx',

            ]);

            if ($validator->fails()) {
                $models = DB::table('modenadisable')->First();
                return back()->withErrors($validator)->withInput()->with('model', $models);
            } else {
                $rows = Excel::toArray(new DocumentDraftContentImport, $request->file('content_data'))[0];
                if (count($rows) > 0) {
                    $data = $this->validateContentFileUpload($rows);
                    if (isset($data['error'])) {
                        Session::flash('errmsg', $data['error']);
                        return redirect()->back();
                    }
                    foreach ($data['content'] as $row) {
                        $draft_id = $request->draft_id;
                        $titledata = array(
                            'title' => $row['title'],
                            'responsibility' => $row['responsibility'],
                            'document_id' => $draft_id,
                        );

                        //checking if a title for a particular content already exists in database
                        $titlecheck = GovernanceHubDraftContent::where('title', $row['title'])
                            ->where('document_id', $draft_id)
                            ->where('is_deleted', false)
                            ->get();

                        if (count($titlecheck) > 0) {
                            $latest_id = GovernanceHubDraftContent::where('title', $row['title'])
                                ->where('document_id', $draft_id)
                                ->where('is_deleted', false)
                                ->value('id');
                        } else {
                            GovernanceHubDraftContent::create($titledata);
                            $latest_data = GovernanceHubDraftContent::latest('id')->first();
                            $latest_id = $latest_data->id;
                        }
                        $contentdata = array(
                            'content_id' => $latest_id,
                            'rich_text' => $row['rich_text'],
                            'responsibility' => $row['responsibility'],
                            'draft_id' => $draft_id,
                        );

                        //checking if a content for a particular document and title already exists in database
                        $contentscheck = GovernanceHubDraftContentRichText::where('rich_text', $row['rich_text'])
                            ->where('content_id', $latest_id)
                            ->where('draft_id', $draft_id)
                            ->where('is_deleted', false)
                            ->get();

                        if (count($contentscheck) == 0) {
                            GovernanceHubDraftContentRichText::create($contentdata);
                        } else {
                            foreach ($contentscheck as $check) {
                                GovernanceHubDraftContentRichText::where('id', $check->id)->update($contentdata);
                            }
                        }
                    }
                }

                if (!File::exists(public_path('/storage/DocumentDraftGovernancehubContent/'))) {
                    File::makeDirectory(public_path() . '/storage/DocumentDraftGovernancehubContent/', 0755, true);
                }

                $name = 'document_draft_content_data' . '.' . $request->file('content_data')->extension();
                $request->file('content_data')->move(public_path() . '/storage/DocumentDraftGovernancehubContent/', $name);
                $models = DB::table('modenadisable')->First();
                return redirect()->route('doc.document_draft')
                    ->with('msg', 'Draft Content Uploaded successfully')
                    ->with('model', $models);
            }
        } catch (\Exception $exception) {
            Log::error($exception);
            Session::flash('msg', "Content Upload failed");

            return redirect()->back();
        }
    }
    /**
     * Convert image URLs back to base64 for PDF generation
     * DomPDF can't load external URLs, so we need to embed images as base64
     */
    private function convertImagesToBase64ForPDF($html)
    {
        if (empty($html)) {
            return $html;
        }

        $pattern = '/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i';

        return preg_replace_callback($pattern, function($matches) {
            $fullImgTag = $matches[0];
            $src = $matches[1];

            // Skip if already base64
            if (strpos($src, 'data:image') === 0) {
                return $fullImgTag;
            }

            // Skip external URLs (not our storage)
            if (strpos($src, 'storage/document_images/') === false) {
                return $fullImgTag;
            }

            try {
                // Extract the storage path
                $storagePath = null;
                if (preg_match('#storage/document_images/(.+)$#', $src, $pathMatches)) {
                    $storagePath = 'document_images/' . $pathMatches[1];
                }

                if ($storagePath && \Storage::disk('public')->exists($storagePath)) {
                    // Read the image file
                    $imageContent = \Storage::disk('public')->get($storagePath);

                    // Detect MIME type
                    $extension = strtolower(pathinfo($storagePath, PATHINFO_EXTENSION));
                    $mimeTypes = [
                        'jpg' => 'image/jpeg',
                        'jpeg' => 'image/jpeg',
                        'png' => 'image/png',
                        'gif' => 'image/gif',
                        'webp' => 'image/webp'
                    ];
                    $mimeType = $mimeTypes[$extension] ?? 'image/jpeg';

                    // Convert to base64

                    // $image = Image::make($imageContent)->encode($extension);
                    // $base64 = base64_encode($image);

                    // // $base64 = base64_encode($imageContent);
                    // $base64Src = "data:{$mimeType};base64,{$base64}";

                    // // Replace the src attribute
                    // return str_replace($src, $base64Src, $fullImgTag);
                    $image = Image::make($imageContent)->encode('jpg', 90);
                    $base64 = base64_encode($image);
                    $base64Src = "data:image/jpeg;base64,{$base64}";
                    return str_replace($src, $base64Src, $fullImgTag);
                }
            } catch (\Exception $e) {
                // If conversion fails, return original tag
                return $fullImgTag;
            }

            return $fullImgTag;
        }, $html);
    }

    public function exportdocumentdraftreportpdf($id)
    {
        session()->put('pdf_download_in_progress', true);

        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 300); // 5 minutes
        set_time_limit(300);

        \App\Helpers\VideoThumbnailHandler::cleanupOldTempFiles();

        $document = GovernanceHubDraft::findOrFail($id);
        $document_name = $document->document_name;
        $document_titles = GovernanceHubDraftContent::where('document_id', $id)
            ->where('is_deleted', false)
            ->orderBy('position', 'asc')
            ->with(['contentId' => function($query) {
                $query->where('is_deleted', false)
                      ->orderBy('position', 'asc');
            }])
            ->get();

        // Convert image URLs to base64 for PDF
        foreach ($document_titles as $title) {
            if ($title->contentId) {
                foreach ($title->contentId as $content) {
                    if (!empty($content->rich_text)) {
                        $content->rich_text = $this->convertImagesToBase64ForPDF($content->rich_text);
                    }
                }
            }
        }

        $contentAnalysis = [
            'paper_size' => 'A4',
            'orientation' => 'portrait',
            'dpi' => 120,
            'font_scale' => 'standard'
        ];

        $splitter = new ContentSplitterService();
        $document_titles = $splitter->splitLongContentForPDF($document_titles, false);

        $pdf = PDF::loadView('user/document/procedure_draft_export_pdf',
                compact('document_titles', 'document_name', 'document', 'contentAnalysis'))
            ->setPaper($contentAnalysis['paper_size'], $contentAnalysis['orientation'])
            ->setOptions([
                'dpi' => $contentAnalysis['dpi'],
                'defaultFont' => 'sans-serif',
                'isRemoteEnabled' => true,
                'isPhpEnabled' => true,
                'isFontSubsettingEnabled' => true,
                'isHtml5ParserEnabled' => true,
                'debugKeepTemp' => false,
                'debugCss' => false,
                'debugLayout' => false,
                'debugLayoutLines' => false,
                'debugLayoutBlocks' => false,
                'debugLayoutInline' => false,
                'debugLayoutPaddingBox' => false,
            ]);

        session()->forget('pdf_download_in_progress');

        return $pdf->download($document_name . '_Procedure_draft_save.pdf');
    }

    public function updateDraftContentText(UpdateDraftContentRichTextRequest $request)
    {
        $error = null;
        $content_id = $request->input('content_id');
        $document_id = $request->input('document_id');
        $title = $request->input('title');

        if (GovernanceHubDraftContent::where('id', $content_id)->where('document_id', $document_id)->first()) {
            if ($error == null) {
                $affectedRows = GovernanceHubDraftContent::where('is_deleted', false)
                    ->where('id', $content_id)
                    ->where('document_id', $document_id)
                    ->update([
                        'title' => $title,
                    ]);

                if (GovernanceHubDraftContent::where('is_deleted', false)->where('id', $content_id)->where('document_id', $document_id)->first()) {
                    if ($request->has('changed_records')) {
                        $changedRecords = json_decode($request->input('changed_records'), true);

                        if (!empty($changedRecords)) {
                            foreach ($changedRecords as $record) {
                                $textId = $record['text_id'];
                                $responsibility = $record['responsibility'];
                                $richText = $record['rich_text'];

                                if ($textId && $textId !== 'null') {
                                    $richTextEntry = GovernanceHubDraftContentRichText::where('is_deleted', false)
                                        ->where('id', $textId)
                                        ->first();

                                    if ($richTextEntry) {
                                        $richTextEntry->update([
                                            'responsibility' => $responsibility,
                                            'rich_text' => $richText,
                                        ]);
                                    }
                                } else {
                                    GovernanceHubDraftContentRichText::create([
                                        'content_id' => $content_id,
                                        'responsibility' => $responsibility,
                                        'rich_text' => $richText,
                                    ]);
                                }
                            }
                        }
                    } else {
                        $count = count($request->input('rich_text'));
                        $richTextIds = $request->input('text_id');
                        $richTextIds = array_filter($richTextIds, function ($value) {
                            return $value !== 'null';
                        });
                        GovernanceHubDraftContentRichText::where('content_id', $content_id)
                            ->whereNotIn('id', $richTextIds)
                            ->delete();

                        for ($y = 0; $y < $count; $y++) {
                            $richTextEntry = GovernanceHubDraftContentRichText::where('is_deleted', false)
                                ->where('id', $request->input('text_id')[$y])
                                ->first();

                            if ($richTextEntry) {
                                $richTextEntry->update([
                                    'responsibility' => $request->input('responsibility')[$y],
                                    'rich_text' => $request->input('rich_text')[$y],
                                ]);
                            } else {
                                GovernanceHubDraftContentRichText::create([
                                    'content_id' => $content_id,
                                    'responsibility' => $request->input('responsibility')[$y],
                                    'rich_text' => $request->input('rich_text')[$y],
                                ]);
                            }
                        }
                    }

                    Session::flash('msg', "Document updated successfully");
                } else {
                    Session::flash('msg', "Document is not updated.");
                }
            }
            return [
                'data' => null,
                'error' => $error,
            ];
        } else {
            Session::flash('msg', "Document is not updated.");
            return [
                'data' => null,
                'error' => $error,
            ];
        }
    }
    public function deleteDraftContent(Request $request)
    {
        $id = $request->input('id');
        $data = GovernanceHubDraftContent::find($id);
        if ($data) {
            GovernanceHubDraftContent::where('id', $id)->update(['is_deleted' => true]);
            GovernanceHubDraftContentRichText::where('content_id', $id)->update(['is_deleted' => true]);
            Session::flash('msg', "Draft content deleted successfully");
            return response()->json([
                'status' => 200,
            ]);
        } else {
            return response()->json([
                'status' => 404,
            ]);
        }
    }
    public function reviewDraft(int $id)
    {
        $controllers = Doccontroller::get();
        $representatives = Representative::get();
        $list = collect([]);
        foreach ($controllers as $c) {
            $list[] = $c->email;
        }
        foreach ($representatives as $representative) {
            $list[] = $representative->email;
        }
        $mrdoc = $list->unique();
        $models = Modenadisable::first();
        $docname = GovernanceHubDraft::findOrFail($id);
        $documentContents = GovernanceHubDraftContent::join('governance_hub_drafts', 'governance_hub_draft_contents.document_id', '=', 'governance_hub_drafts.id')
            ->where('governance_hub_drafts.id', '=', $id)
            ->where('governance_hub_draft_contents.is_deleted', false)
            ->orderBy('governance_hub_draft_contents.position', 'asc')
            ->get();

        $relatedDocumentIds = GovernanceHubDraftContent::where('document_id', '=', $id)
            ->where('is_deleted', false)
            ->orderBy('position', 'asc')
            ->pluck('id')
            ->toArray();
        return view('user.document.reviewdraft')
            ->with([
                'mrdoc' => $mrdoc,
                'model' => $models,
                'id' => $id,
                'docname' => $docname,
                'documentContents' => $documentContents,
                'relatedDocumentIds' => $relatedDocumentIds,
            ]);
    }
    public function reviewRejectDraft(int $id)
    {
        $controllers = Doccontroller::get();
        $models = Modenadisable::first();
        return view('user.document.reviewrejectdraft')->with([
            'model' => $models,
            'controllers' => $controllers,
            'id' => $id,
        ]);
    }
    public function selectdraftreviewers(Request $request)
    {
        try {
            $id = $request->id_value;
            $draft = GovernanceHubDraft::findOrFail($id);
            $reviewers = $draft->reviewer;
            $users = User::where('is_email_verified', true)->where('approved', true)->get();
            return response()->json([
                'status' => 200,
                'reviewers' => $reviewers,
                'users' => $users,
                'drafts' => $draft,
            ]);
        } catch (\Exception $exception) {
            Log::error($exception);
            Session::flash('errmsg', "Request failed");
            return response()->json([
                'status' => 404,
            ]);
        }
    }
    public function storereviewerapprover(AddDraftReviewApproveRequest $request)
    {
        try {
            $id = $request->draft_id;
            $draft = GovernanceHubDraft::findOrFail($id);
            $reviwers = implode(',', $request->reviewer_value);
            $approvers = $request->approver_value;
            $draft->reviewer = $reviwers;
            $draft->approvers = $approvers;
            $draft->status = 'Review Requested';
            $draft->update();

            //storing reviewer details in governance_hub_drafts_reviewer_details table
            $storeData = [];
            $emails = $request->reviewer_value;
            $users = User::whereIn('email', $emails)->get();
            $currentTime = now();
            foreach ($users as $user) {
                $storeData[] = [
                    'draft_id' => $id,
                    'reviewer_id' => $user->id,
                    'created_at' => $currentTime,
                    'updated_at' => $currentTime,
                ];
            }
            if (count($storeData) > 0) {
                GovernanceHubDraftReviewerDetail::insert($storeData);
            }

            //Sending email to all reviewers
//            foreach ($request->reviewer_value as $reviewerEmail) {
//                Mail::to(trim($reviewerEmail))->send(new DraftReviewRequestRaised($data));
//            }
            $data = [
                'docname' => $draft->document_name,
                'type' => $draft->type,
            ];
            Mail::to($users)->send(new DraftReviewRequestRaised($data));
            return response()->json([
                'status' => 200,
            ]);
        } catch (\Exception $exception) {
            Log::error($exception);
            Session::flash('errmsg', "Request for Review and Approval failed");
            return response()->json([
                'status' => 404,
            ]);
        }
    }

    public function modifydraftreviewers(Request $request)
    {
        try {
            $id = $request->id_value;
            $draft = GovernanceHubDraft::findOrFail($id);
            $reviewers = $draft->reviewer;
            $users = User::where('is_email_verified', true)->where('approved', true)->get();
            return response()->json([
                'status' => 200,
                'reviewers' => $reviewers,
                'users' => $users,
                'drafts' => $draft,
            ]);
        } catch (\Exception $exception) {
            Log::error($exception);
            Session::flash('errmsg', "Request failed");
            return response()->json([
                'status' => 404,
            ]);
        }
    }

    public function updatemodifieddraftreviewer(Request $request)
    {
        try {
            $id = $request->draft_rev_id;
            $draft = GovernanceHubDraft::findOrFail($id);
            $reviwers = implode(',', $request->reviewer_value_modify);
            $draft->reviewer = $reviwers;
            $draft->update();

            //storing reviewer details in governance_hub_drafts_reviewer_details table
            $draft_exists = GovernanceHubDraftReviewerDetail::where('draft_id', $id)->get();
            if (count($draft_exists) == 0) {
                foreach ($request->reviewer_value_modify as $revdetails) {
                    $user_id = User::where('email', $revdetails)->value('id');
                    $reviwdetails = new GovernanceHubDraftReviewerDetail;
                    $reviwdetails->draft_id = $id;
                    $reviwdetails->reviewer_id = $user_id ? $user_id : 0;
                    $reviwdetails->save();
                }
            } else {
                GovernanceHubDraftReviewerDetail::where('draft_id', $id)->delete();
                foreach ($request->reviewer_value_modify as $revdetails) {
                    $user_id = User::where('email', $revdetails)->value('id');
                    $reviwdetails = new GovernanceHubDraftReviewerDetail;
                    $reviwdetails->draft_id = $id;
                    $reviwdetails->reviewer_id = $user_id ? $user_id : 0;
                    $reviwdetails->save();
                }
            }

            //Sending email to all reviewers
            $data = [
                'docname' => $draft->document_name,
                 'type' => $draft->type,
            ];
            Mail::to($request->reviewer_value_modify)->send(new DraftReviewRequestRaised($data));
            return response()->json([
                'status' => 200,
            ]);
        } catch (\Exception $exception) {
            Log::error($exception);
            Session::flash('errmsg', "Request for changing Reviewer failed");
            return response()->json([
                'status' => 404,
            ]);
        }
    }

    public function modifydraftapprovers(Request $request)
    {
        try {
            $id = $request->id_value;
            $draft = GovernanceHubDraft::findOrFail($id);
            $approvers = $draft->approvers;
            $users = User::where('is_email_verified', true)->where('approved', true)->get();
            return response()->json([
                'status' => 200,
                'approvers' => $approvers,
                'users' => $users,
                'drafts' => $draft,
            ]);
        } catch (\Exception $exception) {
            Log::error($exception);
            Session::flash('errmsg', "Request failed");
            return response()->json([
                'status' => 404,
            ]);
        }
    }

    public function updatemodifieddraftapprover(Request $request)
    {
        $id = $request->draft_app_id;
        $draft = GovernanceHubDraft::find($id);
        if ($draft) {
            GovernanceHubDraft::where('id', $id)->update(['approvers' => $request->approver_value_modify]);
            $updated_appr_email = GovernanceHubDraft::where('id', $id)->value('approvers');
            //Sending email to approvers
            $approver_details = User::where('email', $updated_appr_email)->first();
            if ($approver_details) {
                $approver_name = $approver_details->name;
            } else {
                $approver_name = 'Approver';
            }
            $data = ['docname' => $draft->document_name,
                'type' => $draft->type,
                'approver' => $approver_name,
            ];
            Mail::to($updated_appr_email)->send(new DocumentDraftReviewCompleted($data));
            return response()->json([
                'status' => 200,
            ]);
        } else {
            return response()->json([
                'status' => 404,
            ]);
        }

    }

    public function notok_addremarks(Request $request)
    {
        $models = ModenaDisable::first();
        $validator = Validator::make($request->all(), [
            'reject_reason' => 'required|string|max:2000',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $id = $request->input('id');
        $reject_reason = $request->input('reject_reason');
        $draft = GovernanceHubDraft::find($id);
        $curr_user_id = User::where('email', Auth::user()->email)->value('id');
        if ($draft) {
            GovernanceHubDraft::where('id', $id)
                ->update([
                    'reviewed_rejection_reason' => $reject_reason,
                    'status' => 'Request Rejected',
                    'reviewed_by' => 'yes',
                    'review_rejected_by_user' => $curr_user_id ? $curr_user_id : 0,
                ]);

            $total_reviewers = explode(",", $draft->reviewer);
            $draft_exists = GovernanceHubDraftReviewerDetail::where('draft_id', $id)->get();
            if (count($draft_exists) == 0) {
                foreach ($total_reviewers as $total_reviewer) {
                    $user_id = User::where('email', $total_reviewer)->value('id');
                    $reviwdetails = new GovernanceHubDraftReviewerDetail;
                    $reviwdetails->draft_id = $id;
                    $reviwdetails->reviewer_id = $user_id ? $user_id : 0;
                    $reviwdetails->save();
                }
            }

            $update_data = array('reviewed_status' => 1,
                'reviewed_date' => date('Y-m-d'),
                'draft_rejected' => 1,
                'draft_reject_reason' => $reject_reason,
            );
            $reviewed_by_details = User::where('email', Auth::user()->email)->first();
            GovernanceHubDraftReviewerDetail::where('draft_id', $id)
                ->where('reviewer_id', $reviewed_by_details->id)
                ->update($update_data);
            $data = array(
                'docname' => $draft->document_name,
                'type' => $draft->type,
            );
            Mail::to($draft->drafted_by)->send(new DraftReviewRequestDisapproved($data));
            return redirect('/user/doc/document-draft')
                ->with('msg', 'The draft with Document name ' . ucfirst($draft->document_name) . ' is rejected')
                ->with('models', $models);
        } else {
            Session::flash('msg', "Review process failed!");
            return redirect()->back();
        }

    }
    public function reviewAcceptDraft($id)
    {
        $models = ModenaDisable::first();
        $draft = GovernanceHubDraft::find($id);
        if ($draft) {
            $reviewed_by = Auth::user()->email;
            $total_reviewers = explode(",", $draft->reviewer);
            $draft->reviewed_by = 'yes';
            $draft->update();

            $reviewed_by_details = User::where('email', $reviewed_by)->first();
            if ($reviewed_by_details) {
                $reviewed_by_name = $reviewed_by_details->name;
            } else {
                $reviewed_by_name = $reviewed_by;
            }

            $approver_details = User::where('email', $draft->approvers)->first();
            if ($approver_details) {
                $approver_name = $approver_details->name;
            } else {
                $approver_name = 'Approver';
            }

            $draft_exists = GovernanceHubDraftReviewerDetail::where('draft_id', $id)->get();
            if (count($draft_exists) == 0) {
                foreach ($total_reviewers as $total_reviewer) {
                    $user_id = User::where('email', $total_reviewer)->value('id');
                    $reviwdetails = new GovernanceHubDraftReviewerDetail;
                    $reviwdetails->draft_id = $id;
                    $reviwdetails->reviewer_id = $user_id ? $user_id : 0;
                    $reviwdetails->save();
                }
            }
            $update_data = array('reviewed_status' => 1, 'reviewed_date' => date('Y-m-d'));
            GovernanceHubDraftReviewerDetail::where('draft_id', $id)
                ->where('reviewer_id', $reviewed_by_details->id)
                ->update($update_data);

            $data = array(
                'docname' => $draft->document_name,
                'type' => $draft->type,
                'reviewedby' => $reviewed_by_name,
                'approver' => $approver_name,
            );
            Mail::to($draft->drafted_by)->send(new DraftReviewRequestApproved($data));
            $total_reviwed_by_count = GovernanceHubDraftReviewerDetail::where('draft_id', $id)
                ->where('reviewed_status', true)
                ->get()
                ->count();

            $existing_reviewers = count($total_reviewers) - $total_reviwed_by_count;
            if ($existing_reviewers == 0) {
                GovernanceHubDraft::where('id', $id)->update(['status' => 'Draft Reviewed']);
                Mail::to($draft->approvers)->send(new DocumentDraftReviewCompleted($data));
            }
            return redirect('/user/doc/document-draft')
                ->with('msg', 'The draft has been reviewed successfully')
                ->with('models', $models);
        } else {
            Session::flash('msg', "Review process failed!");
            return redirect()->back();
        }
    }
    public function reviewacceptremarksdraft(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'review_accept_remarks' => 'required|string',
        ], ['review_accept_remarks.required' => 'Please Enter the Remarks']);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'error' => $validator->errors()->all(),
            ]);
        } else {
            $id = $request->main_draft_id;
            $draft = GovernanceHubDraft::find($id);
            if ($draft) {
                $reviewed_by = Auth::user()->email;
                $total_reviewers = explode(",", $draft->reviewer);
                $draft->reviewed_by = 'yes';
                $draft->update();

                $reviewed_by_details = User::where('email', $reviewed_by)->first();
                if ($reviewed_by_details) {
                    $reviewed_by_name = $reviewed_by_details->name;
                } else {
                    $reviewed_by_name = $reviewed_by;
                }

                $approver_details = User::where('email', $draft->approvers)->first();
                if ($approver_details) {
                    $approver_name = $approver_details->name;
                } else {
                    $approver_name = 'Approver';
                }

                $draft_exists = GovernanceHubDraftReviewerDetail::where('draft_id', $id)->get();
                if (count($draft_exists) == 0) {
                    foreach ($total_reviewers as $total_reviewer) {
                        $user_id = User::where('email', $total_reviewer)->value('id');
                        $reviwdetails = new GovernanceHubDraftReviewerDetail;
                        $reviwdetails->draft_id = $id;
                        $reviwdetails->reviewer_id = $user_id ? $user_id : 0;
                        $reviwdetails->save();
                    }
                }
                $update_data = array(
                    'reviewed_status' => 1,
                    'reviewed_date' => date('Y-m-d'),
                    'draft_accept_remark' => $request->review_accept_remarks,
                    'draft_accept_remark_date' => date('Y-m-d'),
                );
                GovernanceHubDraftReviewerDetail::where('draft_id', $id)
                    ->where('reviewer_id', $reviewed_by_details->id)
                    ->update($update_data);

                //storing remarks in drafts comments table
                $fetch_rev_datas = GovernanceHubDraftReviewerDetail::where('draft_id', $id)
                    ->whereNotNull('draft_accept_remark')
                    ->where('comment_added', false)
                    ->get();
                if (count($fetch_rev_datas) > 0) {
                    foreach ($fetch_rev_datas as $fetch_rev_data) {
                        $comment = new GovernanceHubDraftComment;
                        $comment->user_id = $fetch_rev_data->reviewer_id;
                        $comment->comment = $fetch_rev_data->draft_accept_remark;
                        $comment->parent_id = 0;
                        $comment->commentable_type = 'App\GovernanceHubDraft';
                        $comment->commentable_id = $fetch_rev_data->draft_id;
                        $comment->record_id = $fetch_rev_data->reviewer_id . $fetch_rev_data->draft_id . date('s') . uniqid();
                        $comment->created_at = now();
                        $comment->updated_at = now();
                        $comment->save();
                    }
                }

                GovernanceHubDraftReviewerDetail::where('draft_id', $id)
                    ->where('reviewer_id', $reviewed_by_details->id)
                    ->update(['comment_added' => 1]);
                $total_reviwed_by_count = GovernanceHubDraftReviewerDetail::where('draft_id', $id)
                    ->where('reviewed_status', true)
                    ->get()
                    ->count();

                $existing_reviewers = count($total_reviewers) - $total_reviwed_by_count;
                if ($existing_reviewers == 0) {
                    GovernanceHubDraft::where('id', $id)->update(['status' => 'Draft Reviewed']);
                }

                try {
                    $data = array(
                        'docname' => $draft->document_name,
                        'type' => $draft->type,
                        'reviewedby' => $reviewed_by_name,
                        'approver' => $approver_name,
                    );
                    Mail::to($draft->drafted_by)->send(new DraftReviewRequestApproved($data));
                    //sending mail to concerned persons
                    if ($request->review_accept_remarks != null) {
                        $remark_data = array('remarks' => $request->review_accept_remarks);
                        $mailData = array_merge($data, $remark_data);
                        $receivers = array();
                        $docontrollers = Doccontroller::all();
                        foreach ($docontrollers as $docontroller) {
                            $receivers[] = $docontroller->email;
                        }
                        if ($draft->drafted_by) {
                            $receivers[] = $draft->drafted_by;
                        }
                        foreach ($total_reviewers as $total_reviewer) {
                            if ($total_reviewer != Auth::user()->email) {
                                $receivers[] = $total_reviewer;
                            }
                        }

                        if (count($receivers)) {
                            Mail::to($receivers)->send(new DocumentDraftReviewAcceptRemarkAdded($mailData));
                        }
                    }
                    if ($existing_reviewers == 0) {
                        Mail::to($draft->approvers)->send(new DocumentDraftReviewCompleted($data));
                    }
                } catch (\Exception $exception) {
                    Log::error('Error sending email: ' . $exception->getMessage());
                    return response()->json([
                        'status' => 200,
                        'data' => null,
                        'msg' => "The email notification has not been sent to a user.",
                    ]);
                }

                return response()->json([
                    'status' => 200,
                    'data' => null,
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'msg' => "Review process failed!",
                ]);
            }
        }
    }
    protected function getusername($email)
    {
        $user = User::where('email', $email)->value('name');
        $user_name = $user ? $user : $email;
        return $user_name;
    }
    public function approveDraftdetails(int $id)
    {
        $controllers = Doccontroller::get();
        $representatives = Representative::get();
        $list = collect([]);
        foreach ($controllers as $c) {
            $list[] = $c->email;
        }
        foreach ($representatives as $representative) {
            $list[] = $representative->email;
        }
        $mrdoc = $list->unique();
        $models = Modenadisable::first();
        $docname = GovernanceHubDraft::findOrFail($id);
        $documentContents = GovernanceHubDraftContent::join('governance_hub_drafts', 'governance_hub_draft_contents.document_id', '=', 'governance_hub_drafts.id')
            ->where('governance_hub_drafts.id', '=', $id)
            ->where('governance_hub_draft_contents.is_deleted', false)
            ->orderBy('governance_hub_draft_contents.position', 'asc')
            ->get();

        $relatedDocumentIds = GovernanceHubDraftContent::where('document_id', '=', $id)
            ->where('is_deleted', false)
            ->orderBy('position', 'asc')
            ->pluck('id')
            ->toArray();
        return view('user.document.approvedraft')
            ->with([
                'mrdoc' => $mrdoc,
                'model' => $models,
                'id' => $id,
                'docname' => $docname,
                'documentContents' => $documentContents,
                'relatedDocumentIds' => $relatedDocumentIds,
            ]);
    }
    public function approveDraft($id)
    {
        $models = ModenaDisable::first();
        $draft = GovernanceHubDraft::find($id);
        $user_exists = User::where('email', Auth::user()->email)->first();
        if ($user_exists) {
            $user_id = $user_exists->id;
        } else {
            $user_id = Auth::user()->email;
        }
        if ($draft) {
            if ($draft->procedure_type == 'change_request') {
                $new_revision = $draft->revision + 1;
                $new_revision_date = date('Y-m-d');
                GovernanceHubDraft::where('id', $id)->update([
                    'revision' => $new_revision,
                    'date' => $new_revision_date,
                    'approved_by' => 'yes',
                    'approved_by_user' => $user_id,
                    'status' => 'Draft Approved',
                    'approved_date' => date('Y-m-d'),
                ]);
            } else {
                GovernanceHubDraft::where('id', $id)->update([
                    'approved_by' => 'yes',
                    'approved_by_user' => $user_id,
                    'status' => 'Draft Approved',
                    'approved_date' => date('Y-m-d'),
                ]);
            }

            return redirect('/user/doc/document-draft')
                ->with('msg', 'Draft has been approved successfully')
                ->with('models', $models);
        } else {
            Session::flash('msg', "Draft Approval process failed");
            return redirect()->back();
        }
    }
    public function approveacceptremarksdraft(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'approve_remarks' => 'required|string',
        ],
            ['approve_remarks.required' => 'Please Enter the Remarks']);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'error' => $validator->errors()->all(),
            ]);
        } else {
            $id = $request->main_draft_id;
            $draft = GovernanceHubDraft::find($id);
            $user_exists = User::where('email', Auth::user()->email)->first();
            if ($user_exists) {
                $user_id = $user_exists->id;
            } else {
                $user_id = Auth::user()->email;
            }
            if ($draft) {
                if ($draft->procedure_type == 'change_request') {
                    $new_revision = $draft->revision + 1;
                    $new_revision_date = date('Y-m-d');
                    GovernanceHubDraft::where('id', $id)->update([
                        'revision' => $new_revision,
                        'date' => $new_revision_date,
                        'approved_by' => 'yes',
                        'approved_by_user' => $user_id,
                        'status' => 'Draft Approved',
                        'approved_date' => date('Y-m-d'),
                        'draft_approve_accept_remark' => $request->approve_remarks,
                    ]);
                } else {
                    GovernanceHubDraft::where('id', $id)->update([
                        'approved_by' => 'yes',
                        'approved_by_user' => $user_id,
                        'status' => 'Draft Approved',
                        'approved_date' => date('Y-m-d'),
                        'draft_approve_accept_remark' => $request->approve_remarks,
                    ]);
                }

                //storing remarks in draft comments table
                $latest_hub_record = GovernanceHubDraft::find($id);
                if ($latest_hub_record->draft_approve_accept_remark != null) {
                    $approver_id = User::where('email', $latest_hub_record->approvers)->value('id');
                    $user_id = $approver_id ? $approver_id : 0;
                    $comment = new GovernanceHubDraftComment;
                    $comment->user_id = $user_id;
                    $comment->comment = $latest_hub_record->draft_approve_accept_remark;
                    $comment->parent_id = 0;
                    $comment->commentable_type = 'App\GovernanceHubDraft';
                    $comment->commentable_id = $latest_hub_record->id;
                    $comment->record_id = $user_id . $latest_hub_record->id . date('s') . uniqid();
                    $comment->created_at = now();
                    $comment->updated_at = now();
                    $comment->save();
                }

                //sending mail to connected users
                if ($request->approve_remarks != null) {
                    try {
                        $data = array(
                            'docname' => $draft->document_name,
                            'type' => $draft->type,
                            'approvedby' => $this->getusername(Auth::user()->email),
                        );
                        $remark_data = array('remarks' => $request->approve_remarks);
                        $emaildata = array_merge($data, $remark_data);
                        $receivers = [];
                        $docontrollers = Doccontroller::all();
                        foreach ($docontrollers as $docontroller) {
                            $receivers[] = $docontroller->email;
                        }
                        $receivers[] = $draft->drafted_by;

                        if(count($receivers)) {
                            Mail::to($receivers)->send(new DocumentDraftApproveAcceptRemarkAdded($emaildata));
                        }
                    } catch (Exception $e) {
                        Log::error('Error sending email: ' . $e->getMessage());
                        return response()->json([
                            'status' => 200,
                            'data' => null,
                            'msg' => "The email notification has not been sent to a user.",
                        ]);
                    }
                }

                return response()->json([
                    'status' => 200,
                    'data' => null,
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'msg' => "Approval process failed!",
                ]);
            }
        }
    }
    public function approveRejectDraft(int $id)
    {
        $controllers = Doccontroller::get();
        $models = Modenadisable::first();
        return view('user.document.approverejectdraft')->with([
            'model' => $models,
            'controllers' => $controllers,
            'id' => $id,
        ]);
    }
    public function notok_approve(Request $request)
    {
        $models = ModenaDisable::first();
        $validator = Validator::make($request->all(), [
            'reject_reason' => 'required|string|max:2000',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $id = $request->input('id');
        $reject_reason = $request->input('reject_reason');
        $curr_user_id = User::where('email', Auth::user()->email)->value('id');
        $draft = GovernanceHubDraft::find($id);
        if ($draft) {
            GovernanceHubDraft::where('id', $id)
                ->update([
                    'approve_rejection_reason' => $reject_reason,
                    'status' => 'Rejected',
                    'approve_rejected_by_user' => $curr_user_id ? $curr_user_id : 0,
                ]);

            $data = array('docname' => $draft->document_name, 'type' => $draft->type);
            Mail::to($draft->drafted_by)->send(new DraftApprovalRequestDisapproved($data));
            return redirect('/user/doc/document-draft')
                ->with('msg', 'The draft with Document name ' . ucfirst($draft->document_name) . ' is rejected')
                ->with('models', $models);

        } else {
            Session::flash('msg', "Draft Approval Reject process failed");
            return redirect()->back()->with('models', $models);
        }
    }

    public function releasechangerequestdraft($id)
    {
        $models = ModenaDisable::first();
        $draft = GovernanceHubDraft::find($id);
        if ($draft) {
            //inserting to change history table
            $request = "CR";
            $exist = Dochistory::where('request', $request)->first();
            if ($exist) {
                $c = Dochistory::where('request', $request)->pluck('count');
                foreach ($c as $count) {
                    $count = $count + 1;
                }
            } else {
                $count = "1";
            }

            $department_exists = DB::table('departments')->where('id', $draft->owner)->first();
            $department = $department_exists ? $department_exists->departmentname : '-';
            $document = $draft->document_name;
            $docnumber = $draft->document_number;
            $reqdate = $draft->change_request_submitted_date;
            $apprdate = $draft->approved_date;
            $reason = $draft->change_reason;
            $document_id = 'CR' . $count;
            $nrevnumber = $draft->revision;

            $historydata = array(
                'count' => $count, 'request' => 'CR', 'department' => $department, 'document_id' => $document_id,
                'docname' => $document, 'docno' => $docnumber, 'nrevnumber' => $nrevnumber,
                'reqdate' => $reqdate,
                'approvedate' => $apprdate, 'reason' => $reason, 'ch_approved' => 'YES', 'year' => Date('Y'),
            );

            Dochistory::create($historydata);

            //delete existing record from governance hub contents table
            $existingcontentsid = GovernanceHubDocumentContent::where('document_id', $draft->governance_hub_id)
                ->pluck('id')->toArray();

            GovernanceHubDocumentContentRichText::whereIn('content_id', $existingcontentsid)->delete();
            GovernanceHubDocumentContent::where('document_id', $draft->governance_hub_id)->delete();

            //updating the existing governance hub document
            $doc_update = array(
                'revision' => $draft->revision,
                'date' => $draft->date,
                'owner' => $draft->owner,
                'prepared_by' => $draft->drafted_by,
                'reviewed_by' => $draft->reviewer,
                'approved_by' => $draft->approvers,
                'approve_remarks' => $draft->draft_approve_accept_remark,
                'approve_remarks_added_date' => $draft->approved_date,
                'change_request_submit' => 0,
            );

            GovernanceHub::where('id', $draft->governance_hub_id)->update($doc_update);
            $draftRecordContents = GovernanceHubDraftContent::where('document_id', $id)
                ->where('is_deleted', false)
                ->get();
            foreach ($draftRecordContents as $record) {
                $newRecord = new GovernanceHubDocumentContent();
                $newRecord->document_id = $draft->governance_hub_id;
                $newRecord->title = $record->title;
                $newRecord->type = $record->type;
                $newRecord->position = $record->position;
                $newRecord->save();
                $draftRecordRichText = GovernanceHubDraftContentRichText::where('content_id', $record->id)->get();
                foreach ($draftRecordRichText as $drt) {
                    $nr = new GovernanceHubDocumentContentRichText();
                    $nr->content_id = $newRecord->id;
                    $nr->responsibility = $drt->responsibility;
                    $nr->rich_text = $drt->rich_text;
                    $nr->document_id = $draft->governance_hub_id;
                    $nr->position = $drt->position;
                    $nr->save();
                }

            }
            $prepared_by = $draft->drafted_by;
            foreach ($draftRecordContents as $record) {
                $draftRecordRichText = GovernanceHubDraftContentRichText::where('content_id', $record->id)->get();
                foreach ($draftRecordRichText as $drt) {
                    $drt->delete();
                }
                $record->delete();
            }

            //updating governance_hub_change_request_details table
            $update_data = array(
                'change_request_completed_date' => date('Y-m-d'),
                'change_requested_completed_status' => 1,
                'new_revision' => $draft->revision,
                'reviewers' => $draft->reviewer,
                'approvers' => $draft->approvers,
            );
            GovernanceHubChangeRequestDetail::where('parent_governance_id', $draft->governance_hub_id)
                ->where('unique_id', $draft->change_request_unique_id)
                ->update($update_data);

            GovernanceHubDraftReviewerDetail::where('draft_id', $draft->id)
                ->update(['new_governance_hub_id' => $draft->governance_hub_id]);

            //deleting existing reviewer details from draft reviewers table
            $alldraftids = GovernanceHubDraftReviewerDetail::where('new_governance_hub_id', $draft->governance_hub_id)
                ->pluck('draft_id')
                ->toArray();
            foreach ($alldraftids as $alldraftid) {
                if ($alldraftid != $draft->id) {
                    GovernanceHubDraftReviewerDetail::where('draft_id', $alldraftid)->delete();
                }
            }

            GovernanceHubDraftComment::where('commentable_id', $draft->id)
                ->update(['new_governance_hub_id' => $draft->governance_hub_id]);

            //storing drafts hub commment into  governance_hub_comments table
            $fetch_comments = GovernanceHubDraftComment::where('commentable_id', $draft->id)
                ->where('is_deleted', false)
                ->get();

            foreach ($fetch_comments as $fetch_comment) {
                $comment = new GovernanceHubComment();
                $comment->user_id = $fetch_comment->user_id;
                $comment->comment = $fetch_comment->comment;
                $comment->parent_id = $fetch_comment->parent_id ? $fetch_comment->parent_id : 0;
                $comment->commentable_type = 'App\GovernanceHub';
                $comment->commentable_id = $fetch_comment->new_governance_hub_id;
                $comment->record_id = $fetch_comment->record_id;
                $comment->created_at = $fetch_comment->created_at;
                $comment->updated_at = $fetch_comment->updated_at;
                $comment->save();

                $get_latest = GovernanceHubComment::latest('id')->first();
                if ($get_latest->parent_id != null) {
                    $record_chk_id = $get_latest->record_id;
                    $reply_parent_id = GovernanceHubDraftComment::where('record_id', $record_chk_id)->value('parent_id');
                    $get_main_comment_record_id = GovernanceHubDraftComment::where('id', $reply_parent_id)->value('record_id');
                    $parent_id_frm_draft = GovernanceHubComment::where('record_id', $get_main_comment_record_id)->value('id');
                    GovernanceHubComment::where('id', $get_latest->id)->update(['parent_id' => $parent_id_frm_draft]);
                }
            }

            $draft->delete();
            return redirect('/user/doc/governancehub')
                ->with('msg', 'Document Released Successfully')
                ->with('models', $models);

        } else {
            Session::flash('msg', "Release Document process failed");
            return redirect()->back()->with('models', $models);
        }
    }

    public function releasenewdraftdocument(Request $request)
    {
        $models = ModenaDisable::first();
        $validator = Validator::make($request->all(), [
            'document_number' => 'required|string|max:255|unique:governance_hubs,document_number',
            'document_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all(),

            ]);
        } else {
            $id = $request->input('draft_release_id');
            $docnumber = $request->input('document_number');
            $docname = $request->input('document_name');
            $draftRecord = GovernanceHubDraft::find($id);
            if ($draftRecord) {
                $document = new GovernanceHub();
                $document->document_number = $docnumber;
                $document->document_name = $docname;
                $document->type = $draftRecord->type;
                $document->doctype_id = $draftRecord->doctype_id;
                $document->views = 0;
                $document->revision = 0;
                $document->date = $draftRecord->date;
                $document->owner = $draftRecord->owner;
                $document->prepared_by = $draftRecord->drafted_by;
                $document->reviewed_by = $draftRecord->reviewer;
                $document->approved_by = $draftRecord->approvers;
                $document->approve_remarks = $draftRecord->draft_approve_accept_remark;
                $document->approve_remarks_added_date = $draftRecord->approved_date;
                $document->save();
                $draftRecordContents = GovernanceHubDraftContent::where('document_id', $id)
                    ->where('is_deleted', false)
                    ->get();
                foreach ($draftRecordContents as $record) {
                    $newRecord = new GovernanceHubDocumentContent();
                    $newRecord->document_id = $document->id;
                    $newRecord->title = $record->title;
                    $newRecord->doctype_id = $record->doctype_id;
                    $newRecord->type = $record->type;
                    $newRecord->position = $record->position;
                    $newRecord->save();
                    $draftRecordRichText = GovernanceHubDraftContentRichText::where('content_id', $record->id)->get();
                    foreach ($draftRecordRichText as $drt) {
                        $nr = new GovernanceHubDocumentContentRichText();
                        $nr->content_id = $newRecord->id;
                        $nr->responsibility = $drt->responsibility;
                        $nr->rich_text = $drt->rich_text;
                        $nr->document_id = $document->id;
                        $nr->position = $drt->position;
                        $nr->save();
                    }

                }
                $prepared_by = $draftRecord->drafted_by;
                foreach ($draftRecordContents as $record) {
                    $draftRecordRichText = GovernanceHubDraftContentRichText::where('content_id', $record->id)->get();
                    foreach ($draftRecordRichText as $drt) {
                        $drt->delete();
                    }
                    $record->delete();
                }

                GovernanceHubDraftReviewerDetail::where('draft_id', $draftRecord->id)
                    ->update(['new_governance_hub_id' => $document->id]);

                GovernanceHubDraftComment::where('commentable_id', $draftRecord->id)
                    ->update(['new_governance_hub_id' => $document->id]);

                //storing draft commments in governance_hub_comments table
                $fetch_comments = GovernanceHubDraftComment::where('commentable_id', $draftRecord->id)
                    ->where('is_deleted', false)
                    ->get();

                foreach ($fetch_comments as $fetch_comment) {
                    $comment = new GovernanceHubComment();
                    $comment->user_id = $fetch_comment->user_id;
                    $comment->comment = $fetch_comment->comment;
                    $comment->parent_id = $fetch_comment->parent_id ? $fetch_comment->parent_id : 0;
                    $comment->commentable_type = 'App\GovernanceHub';
                    $comment->commentable_id = $fetch_comment->new_governance_hub_id;
                    $comment->record_id = $fetch_comment->record_id;
                    $comment->created_at = $fetch_comment->created_at;
                    $comment->updated_at = $fetch_comment->updated_at;
                    $comment->save();

                    $get_latest = GovernanceHubComment::latest('id')->first();
                    if ($get_latest->parent_id != null) {
                        $record_chk_id = $get_latest->record_id;
                        $reply_parent_id = GovernanceHubDraftComment::where('record_id', $record_chk_id)->value('parent_id');
                        $get_main_comment_record_id = GovernanceHubDraftComment::where('id', $reply_parent_id)->value('record_id');
                        $parent_id_frm_draft = GovernanceHubComment::where('record_id', $get_main_comment_record_id)->value('id');
                        GovernanceHubComment::where('id', $get_latest->id)->update(['parent_id' => $parent_id_frm_draft]);
                    }
                }

                $draftRecord->delete();
                return response()->json([
                    'status' => 200,
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                ]);
            }
        }
    }

    public function cancelchangerequesthub($id)
    {
        $models = ModenaDisable::first();
        $draft = GovernanceHubDraft::where('governance_hub_id', $id)->first();
        if ($draft) {
            $main_draft_id = $draft->id;
            GovernanceHubDraftContentRichText::where('draft_id', $draft->id)->delete();
            GovernanceHubDocumentContent::where('document_id', $draft->id)->delete();
            GovernanceHubDraft::where('id', $draft->id)->delete();

            $user_exists = User::where('email', Auth::user()->email)->first();
            if ($user_exists) {
                $user_id = $user_exists->id;
            } else {
                $user_id = Auth::user()->email;
            }
            GovernanceHub::where('id', $id)->update(['change_request_submit' => 0,
                'change_request_cancelled_date' => date('Y-m-d'),
                'change_request_cancelled_by' => $user_id,
            ]);
            return redirect('/user/doc/governancehub')
                ->with('msg', 'Change Request Cancelled')
                ->with('models', $models);

        } else {
            Session::flash('msg', "Change Request Cancelled process failed");
            return redirect()->back()->with('models', $models);
        }
    }
    public function approveDocument(Request $request)
    {
        $models = ModenaDisable::first();
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'approved_by' => 'required|string|max:255',
            'docnumber' => 'required|string|max:255|unique:governance_hubs,document_number',
            'docname' => 'required|string|max:255',
        ]);
        $error = null;
        if ($validator->fails()) {
            return [
                'error' => $validator->errors()->all(),
            ];
        }
        if ($error == null) {
            DB::beginTransaction();
            $id = $request->input('id');
            $docnumber = $request->input('docnumber');
            $docname = $request->input('docname');
            $approvedBy = $request->input('approved_by');
            $draftRecord = GovernanceHubDraft::find($id);
            if (!$draftRecord) {
                DB::rollBack();
                return [
                    'error' => 'The draft was not found.',
                ];
            }
            $document = new GovernanceHub();
            $document->document_number = $docnumber;
            $document->document_name = $docname;
            $document->type = $draftRecord->type;
            $document->doctype_id = $draftRecord->doctype_id;
            $document->views = 0;
            $document->revision = 0;
            $document->date = $draftRecord->date;
            $document->owner = $draftRecord->owner;
            $document->prepared_by = $draftRecord->drafted_by;
            $document->reviewed_by = $draftRecord->reviewer;
            $document->approved_by = $approvedBy;
            $document->save();
            $draftRecordContents = GovernanceHubDraftContent::where('document_id', $id)->get();
            foreach ($draftRecordContents as $record) {
                $newRecord = new GovernanceHubDocumentContent();
                $newRecord->document_id = $document->id;
                $newRecord->title = $record->title;
                $newRecord->type = $record->type;
                $newRecord->doctype_id = $record->doctype_id;
                // $newRecord->responsibility = $record->responsibility;
                // $newRecord->rich_text = $record->rich_text;
                $newRecord->save();
                $draftRecordRichText = GovernanceHubDraftContentRichText::where('content_id', $record->id)->get();
                foreach ($draftRecordRichText as $drt) {
                    $nr = new GovernanceHubDocumentContentRichText();
                    $nr->content_id = $newRecord->id;
                    $nr->responsibility = $drt->responsibility;
                    $nr->rich_text = $drt->rich_text;
                    $nr->save();
                }

            }
            $prepared_by = $draftRecord->drafted_by;
            $data = [
                'docnumber' => $docnumber,
                'docname' => $docname,
                'type' => $draftRecord->type,
            ];
            Mail::to($prepared_by)->send(new DraftApprovalRequestApproved($data));
            foreach ($draftRecordContents as $record) {
                $draftRecordRichText = GovernanceHubDraftContentRichText::where('content_id', $record->id)->get();
                foreach ($draftRecordRichText as $drt) {
                    $drt->delete();
                }
                $record->delete();
            }
            $draftRecord->delete();
            DB::commit();

            return [
                'error' => $error,
            ];
        }
        return [
            'error' => 'An error occurred during approval',
        ];
    }
    public function editDraft(int $id)
    {
        $controllers = Doccontroller::get();
        $representatives = Representative::get();
        $list = collect([]);
        foreach ($controllers as $c) {
            $list[] = $c->email;
        }
        foreach ($representatives as $representative) {
            $list[] = $representative->email;
        }
        $mrdoc = $list->unique();
        $models = Modenadisable::first();
        $docname = GovernanceHubDraft::findOrFail($id);
        $documentContents = GovernanceHubDraftContent::join('governance_hub_drafts', 'governance_hub_draft_contents.document_id', '=', 'governance_hub_drafts.id')
            ->where('governance_hub_drafts.id', '=', $id)
            ->where('governance_hub_draft_contents.is_deleted', false)
            ->get();

        $relatedDocumentIds = GovernanceHubDraftContent::where('document_id', '=', $id)
            ->where('is_deleted', false)
            ->pluck('id')
            ->toArray();
        return view('user.document.editdraft')
            ->with([
                'mrdoc' => $mrdoc,
                'model' => $models,
                'id' => $id,
                'docname' => $docname,
                'documentContents' => $documentContents,
                'relatedDocumentIds' => $relatedDocumentIds,
            ]);
    }
    public function editDraftContentText(UpdateDraftContentRichTextRequest $request)
    {
        $error = null;
        $content_id = $request->input('content_id');
        $document_id = $request->input('document_id');
        $title = $request->input('title');
        if (GovernanceHubDraftContent::where('id', $content_id)->where('document_id', $document_id)->first()) {
            if ($error == null) {
                $affectedRows = GovernanceHubDraftContent::where('is_deleted', false)
                    ->where('id', $content_id)
                    ->where('document_id', $document_id)
                    ->update([
                        'title' => $title,
                    ]);
                if (GovernanceHubDraftContent::where('is_deleted', false)->where('id', $content_id)->where('document_id', $document_id)->first()) {
                    $count = count($request->input('rich_text'));
                    for ($y = 0; $y < $count; $y++) {
                        $richTextEntry = GovernanceHubDraftContentRichText::where('is_deleted', false)->where('id', $request->input('text_id')[$y])->first();
                        if ($richTextEntry) {
                            $richTextEntry->update([
                                'responsibility' => $request->input('responsibility')[$y],
                                'rich_text' => $request->input('rich_text')[$y],
                            ]);
                        } else {
                            GovernanceHubDraftContentRichText::create([
                                'content_id' => $content_id,
                                'responsibility' => $request->input('responsibility')[$y],
                                'rich_text' => $request->input('rich_text')[$y],
                            ]);
                        }
                    }
                    Session::flash('msg', "Draft updated successfully");
                } else {
                    Session::flash('msg', "Draft is not updated.");
                }
                //GovernanceHubDraft::where('is_deleted', false)->where('id', $document_id)->update(['status' => 'Under Draft']);
                $update_data = array(
                    'status' => 'Under Draft',
                    'reviewer' => null,
                    'approvers' => null,
                    'reviewed_by' => null,
                );
                GovernanceHubDraft::where('id', $document_id)->update($update_data);
                GovernanceHubDraftReviewerDetail::where('draft_id', $document_id)->delete();
            }
            return [
                'data' => null,
                'error' => $error,
            ];
        } else {
            Session::flash('msg', "Draft is not updated.");
            return [
                'data' => null,
                'error' => $error,
            ];
        }
    }
    public function trashdocument(Request $request)
    {
        $id = $request->input('id');
        $data = GovernanceHub::find($id);
        if ($data) {
            GovernanceHub::where('id', $id)->update(['is_deleted' => true]);
            GovernanceHubComment::where('commentable_id', $id)->update(['is_deleted' => true]);
            Session::flash('msg', 'Document has been moved to the trash.');
            return response()->json([
                'status' => 200,
            ]);
        } else {
            return response()->json([
                'status' => 404,
            ]);
        }

    }
    public function trashdocumentlist(Request $request)
    {
        $datas = GovernanceHub::where('is_deleted', true)->get();
        $controllers = Doccontroller::get();
        $models = Modenadisable::first();
        $rev = User::all();
        return view('user.document.trashdocumentlist')->with([
            'datas' => $datas,
            'model' => $models,
            'controllers' => $controllers,
            'rev' => $rev,
        ]);
    }
    public function restoredocument(Request $request)
    {
        $id = $request->input('id');
        $result = GovernanceHub::where('id', $id)->update(['is_deleted' => false]);
        GovernanceHubComment::where('commentable_id', $id)->update(['is_deleted' => false]);
        if ($result) {
            $datas = GovernanceHub::where('is_deleted', true)->get();
            $controllers = Doccontroller::get();
            $models = Modenadisable::first();
            $rev = User::all();
            Session::flash('msg', 'Document has been successfully restored');
            return view('user.document.trashdocumentlist')->with([
                'datas' => $datas,
                'model' => $models,
                'controllers' => $controllers,
                'rev' => $rev,
            ]);
        }
        Session::flash('msg', 'Document was not restored successfully.');
        return redirect()->back()->with('error', 'Document was not restored successfully.');
    }
    public function deletedocument(Request $request)
    {
        $id = $request->input('id');
        // $result = GovernanceHub::where('is_deleted', true)->where('id', $id)->delete();
        $result = GovernanceHub::where('id', $id)->delete();
        GovernanceHubComment::where('commentable_id', $id)->delete();
        if ($result) {
            $datas = GovernanceHub::where('is_deleted', true)->get();
            $controllers = Doccontroller::get();
            $models = Modenadisable::first();
            $rev = User::all();
            Session::flash('msg', 'Document deleted successfully');
            return view('user.document.trashdocumentlist')->with([
                'datas' => $datas,
                'model' => $models,
                'controllers' => $controllers,
                'rev' => $rev,
            ]);
        }
        Session::flash('msg', 'Document was not deleted successfully.');
        return redirect()->back()->with('error', 'Document was not deleted successfully.');
    }
    public function editdocument(int $id)
    {
        $data = GovernanceHub::where('id', $id)->where('is_deleted', false)->first();
        $controllers = Doccontroller::get();
        $models = Modenadisable::first();
        $departments = Departments::get();
        $rev = User::all();
        $types = Doctype::orderBy('level', 'asc')->get();
        return view('user.document.editdocument')->with([
            'data' => $data,
            'model' => $models,
            'departments' => $departments,
            'controllers' => $controllers,
            'rev' => $rev,
            'types' => $types,
        ]);
    }
    public function editdocumentrequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'docnumber' => ['required', 'string', 'max:75', Rule::unique('governance_hubs', 'document_number')->where(function ($query) {
                return $query->where('is_deleted', 0);
            })->ignore($request->input('id'))],
            'docname' => 'required|string|max:75',
            'revision' => 'required|integer',
            'date' => 'required|date',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $id = $request->input('id');
        $docnumber = $request->input('docnumber');
        $docname = $request->input('docname');
        $doctypeId = $request->input('doctype');
        $doctype = Doctype::find($doctypeId);
        $revision = $request->input('revision');
        $date = $request->input('date');
        $docowner = $request->input('docowner');
        GovernanceHub::where('is_deleted', false)->where('id', $id)->update([
            'document_number' => $docnumber,
            'document_name' => $docname,
            'doctype_id' => $doctypeId,
            'type' => $doctype ? $doctype->typename : null,
            'revision' => $revision,
            'date' => $date,
            'owner' => $docowner,
        ]);
        Session::flash('msg', 'Document has not been updated successfully.');
        return redirect()->back()->withInput();
    }
    public function addReview(AddDocumentReviewRequest $request)
    {
        $user_email = Auth::user()->email;
        $data = $request->validated();
        $score = $data['score'];
        $document_id = $data['document_id'];

        $found = GovernanceHubDocumentReview::where('document_id', $document_id)->where('email', $user_email)->first();

        if (!$found) {
            GovernanceHubDocumentReview::create([
                'document_id' => $document_id,
                'email' => $user_email,
                'score' => $score,
            ]);
        } else {
            GovernanceHubDocumentReview::where('document_id', $document_id)->where('email', $user_email)->update([
                'score' => $score,
            ]);
        }

        $user_id = User::where('email', $user_email)->value('id');
        if ($user_id) {
            $review_schedule = GovernanceHubReadRequestDetail::where('governance_hub_id', $document_id)
                ->where('user_id', $user_id)
                ->where('review_completed', false)
                ->get();
            if (count($review_schedule) > 0) {
                GovernanceHubReadRequestDetail::where('governance_hub_id', $document_id)
                    ->where('user_id', $user_id)
                    ->update(['review_completed' => 1]);

                //sending email to readrequested user
                $document_name = GovernanceHub::where('id', $review_schedule[0]->governance_hub_id)->value('document_name');
                $reviewed_by_name = $this->getuserdetails($review_schedule[0]->user_id);
                $requested_user_name = $this->getuserdetails($review_schedule[0]->read_request_submitted_by);
                if ($score == 1) {
                    $final_score = '★';
                    $final_score_blank = '★ ★ ★ ★';
                } else if ($score == 2) {
                    $final_score = '★ ★';
                    $final_score_blank = '★ ★ ★';
                } else if ($score == 3) {
                    $final_score = '★ ★ ★';
                    $final_score_blank = '★ ★';
                } else if ($score == 4) {
                    $final_score = '★ ★ ★ ★';
                    $final_score_blank = '★';
                } else if ($score == 5) {
                    $final_score = '★ ★ ★ ★ ★';
                    $final_score_blank = '';
                }

                $maildata = [
                    'rate_requested_user' => $requested_user_name ? $requested_user_name : '',
                    'reviewed_by_name' => $reviewed_by_name ? $reviewed_by_name : '',
                    'document_name' => $document_name ? ucfirst($document_name) : '',
                    'rating' => $final_score,
                    'rating_blank' => $final_score_blank,
                ];
                $requested_user_email = User::where('id', $review_schedule[0]->read_request_submitted_by)->value('email');
                if ($requested_user_email) {
                    Mail::to($requested_user_email)->send(new DocumentReadRateReviewCompletedNotification($maildata));
                }

            }
        }

        return [
            'data' => null,
            'error' => null,
        ];
    }
    public function trashdraft(Request $request)
    {
        $id = $request->input('id');
        $data = GovernanceHubDraft::find($id);
        if ($data) {
            GovernanceHubDraft::where('id', $id)->update(['is_deleted' => true]);
            Session::flash('msg', 'Draft moved to trash successfully');
            return response()->json([
                'status' => 200,
            ]);
        } else {
            return response()->json([
                'status' => 404,
            ]);
        }
    }
    public function trashdraftlist(Request $request)
    {
        $datas = GovernanceHubDraft::where('is_deleted', true)->get();
        $controllers = Doccontroller::get();
        $models = Modenadisable::first();
        return view('user.document.trashdraftlist')->with([
            'datas' => $datas,
            'model' => $models,
            'controllers' => $controllers,
        ]);
    }
    public function restoredraft(Request $request)
    {
        $id = $request->input('id');
        $result = GovernanceHubDraft::where('id', $id)->update(['is_deleted' => false]);
        if ($result) {
            $datas = GovernanceHubDraft::where('is_deleted', true)->get();
            $controllers = Doccontroller::get();
            $models = Modenadisable::first();
            Session::flash('msg', 'Draft has been successfully restored');
            return view('user.document.trashdraftlist')->with([
                'datas' => $datas,
                'model' => $models,
                'controllers' => $controllers,
            ]);
        }
        Session::flash('msg', 'Draft was not restored successfully.');
        return redirect()->back()->with('error', 'Draft was not restored successfully.');
    }
    public function deletedraft(Request $request)
    {
        $id = $request->input('id');
        // $result = GovernanceHubDraft::where('is_deleted', true)->where('id', $id)->delete();
        $result = GovernanceHubDraft::where('id', $id)->delete();
        GovernanceHubDraftComment::where('commentable_id', $id)->delete();
        if ($result) {
            $datas = GovernanceHubDraft::where('is_deleted', true)->get();
            $controllers = Doccontroller::get();
            $models = Modenadisable::first();
            Session::flash('msg', 'Draft deleted successfully');
            return view('user.document.trashdraftlist')->with([
                'datas' => $datas,
                'model' => $models,
                'controllers' => $controllers,
            ]);
        }
        Session::flash('msg', 'Draft was not deleted successfully.');
        return redirect()->back()->with('error', 'Draft was not deleted successfully.');
    }

    /**
     * Generate a sort key for alphanumeric sorting of document names
     *
     * @param string $documentName
     * @return string
     */
    private function getAlphanumericSortKey($documentNumber)
    {
        $documentNumber = trim($documentNumber);

        // Check if it's numeric only
        if (is_numeric($documentNumber)) {
            return '1' . str_pad($documentNumber, 20, '0', STR_PAD_LEFT);
        }

        // Check if it's alphabetic only
        if (ctype_alpha($documentNumber)) {
            return '2' . strtolower($documentNumber);
        }

        // Check if it's alphanumeric (letters before numbers) - e.g., A001, B010
        if (preg_match('/^([A-Za-z]+)(\d+)$/', $documentNumber, $matches)) {
            $letterPart = strtolower($matches[1]);
            $numberPart = str_pad($matches[2], 20, '0', STR_PAD_LEFT);
            return '3' . $letterPart . $numberPart;
        }

        // Check if it's numeric-alphabet combination - e.g., 1A, 10B, 100C
        if (preg_match('/^(\d+)([A-Za-z]+)$/', $documentNumber, $matches)) {
            $numberPart = str_pad($matches[1], 20, '0', STR_PAD_LEFT);
            $letterPart = strtolower($matches[2]);
            return '4' . $numberPart . $letterPart;
        }

        // For other cases (special characters, mixed patterns), use ASCII-based sorting
        return '5' . $documentNumber;
    }
}
