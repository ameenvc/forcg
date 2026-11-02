@extends('layouts.multiselect')
@section('content')
<style>
    .innercontent{
        font-size:13px;
        font-family:Arial, Helvetica, sans-serif;
    }

    .table-responsive{
  height:550px;  
  overflow:scroll;
}
 thead tr:nth-child(1) td{
    background: #ededed;
    position: sticky;
    top: 0;
    z-index: 10;
  }
   
  .drillimg{
        padding:2px;
        background-color: #edf0f2;
        border: 1px solid #dee2e6;
        border-radius:2px;      
    }
</style>

<div class="bg-body-light">
                    <div class="content content-full">
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
                           
                            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-alt">
                                    <li class="breadcrumb-item">Audit Management</li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href=""> Checklist Library</a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="">Record Details</a>
                                    </li> 
                                    <li class="breadcrumb-item" aria-current="page">                                   
                                        <a class="link-fx" href="">
                                            @if($checklist->division_id!=null)
                                                <span style="color:#808080;">
                                                    {{ucfirst($listing_name)}}
                                                </span>
                                            @elseif($checklist->area_id!=null)
                                            <span style="color:#b5651d;">
                                                {{ucfirst($listing_name)}}
                                            </span>    
                                            @else
                                            <span class="text-primary">
                                                    {{ucfirst($listing_name)}}
                                                </span>
                                            @endif
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="">
                                          <span style="color:#669933;">
                                            {{ucfirst($checklist->checklist_name)}}
                                         </span>
                                        </a>
                                    </li>                                 
                                    <!-- @if(in_array(Auth::user()->email,$auditorlists))
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx text-danger" href="{{route('checklistnewrecord',['id'=>$checklist->id])}}">Back</a>
                                    </li> 
                                   @endif -->
                                </ol>
                            </nav>
                        </div>
                    </div>
 </div>


<div class="content" style="width:100%;">
    <div class="block block-rounded" >
        <div class="block-content block-content-full">
            @if(Session::has('msg'))
                <div class="alert alert-success">
                    <a class="close" data-dismiss="alert">×</a>
                    {!!Session::get('msg')!!}
                </div>
            @endif
            <div id="successmessage"></div>
            @if($data->completed_status!='0%')
            @if($data->record_status=='Pending' || $data->record_status=='Completed')
            @if(Gate::allows('audit-checklist-records-edit'))
            <div class="d-flex justify-content-end flex-wrap mb-2" style="gap:8px;">
                @if($data->completed_status=='100%')
                    <a href="{{url('/user/internalaudit/savechecklistreportpdf?val='.$data->id)}}" 
                    class="btn btn-sm btn-dual" data-toggle="tooltip" title="Export">
                    <i class="fa fa-file-export fa-1x" style="color:#ffc107;"></i>
                    </a>
                @endif
                <a href="{{route('checklistnewrecordsedit',['id'=>$data->id])}}" 
                    class="btn btn-sm btn-dual" data-toggle="tooltip" title="Edit">
                    <i class="fa fa-1x fa-edit" style="color:#007bff;"></i>
                </a>
                @if(in_array(Auth::user()->email,$auditorlists))
                    <button type="button" class="btn btn-sm btn-dual" id="del" value="id[]" 
                            data-toggle="tooltip" title="Delete">
                        <i class="fas fa-1x fa-trash" style="color:red;"></i>
                    </button>
                    @if(count($deletedpoints)>0)
                        <a href="{{route('viewdeletedchecklistrecords',['id'=>$data->id,'checklistid'=>$data->checklist_id])}}" 
                            class="btn btn-alt-secondary font-weight-normal"
                            style="font-size:13px; min-width:130px; font-family:'Times New Roman', Times, serif;">
                            View Deleted Points
                        </a>
                    @endif
                @endif
            </div>
            @endif
            @endif
            @endif
            <form class="mb-5" action="{{route('insertchecklistnewrecord')}}" method="POST" enctype="multipart/form-data">             
             @csrf                      
            <div class="container-fluid px-2 mb-4">
               <div class="row border align-items-center text-center no-gutters mx-auto">
                    <!-- Audit Checklist # -->
                    <div class="col-lg-4 col-md-6 d-flex align-items-center border-right p-2">
                        <span class="font-weight-bold small text-left mr-2" style="min-width:120px;">
                            Audit Checklist #
                        </span>
                        <input type="text" class="form-control form-control-sm"
                                id="auditcheckid" name="auditcheckid"
                                value="{{ $data->audit_checklist_id }}" readonly>
                              
                    </div>

                    <!-- Checklist Name -->
                    <div class="col-lg-4 col-md-6 d-flex align-items-center border-right p-2">
                        <span class="font-weight-bold small text-left mr-2" style="min-width:120px;">
                            Checklist Name
                        </span>
                        <input type="text" class="form-control form-control-sm"
                                name="checklistname" id="checklistname"
                                value="{{ ucfirst($checklist->checklist_name) }}" readonly>
                    </div>

                    <!-- Category -->
                    <div class="col-lg-4 col-md-12 d-flex align-items-center p-2">
                        <span class="font-weight-bold small text-left mr-2" style="min-width:120px;">
                            {{ $category }}
                        </span>
                        <input type="text" class="form-control form-control-sm"
                                value="{{ ucfirst($listing_name) }}" readonly>
                    </div>               
                    <?php
                        $today=Carbon\Carbon::now()->toDateTimeString();
                        $today=Carbon\Carbon::parse($today);                                   
                    ?> 
                    <input type="hidden"   id="addeddate" name="addeddate" value="{{$today->format('d/m/Y')}}">             
                    <input type="hidden" name="auditoremail" id="auditoremail">
                    <input type="hidden" name="audit_id" value="{{ $audits->id }}">
                </div>
            </div>


<div class="container-fluid px-2">
    <div class="row  align-items-center text-center no-gutters mx-auto" style="overflow-x:scroll" >
        <div class="col-lg-12 col-md-6 d-flex">
            <table  width="100%">
              <tr id="record"><td width="100%">
                    <table width="100%" cellpadding="0" cellspacing="0" class="table table-bordered">                                               
                        <tr>
                            <td colspan="8">
                                <table width="100%">                 
                                    <tr>
                                        <td  class="innercontent" style="background:#ededed;">Audit Progress</td>
                                        <td  style="font-size:15px;font-family:Arial, Helvetica, sans-serif;width:40%">
                                            <div class="progress mb-1" style="height: 7px;">
                                                <div class="progress-bar bg-success" role="progressbar" style="width:{{$data->completed_status}};" aria-valuenow="{{$data->completed_status}}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <p class="fs-xs fw-semibold mb-0">{{$data->completed_status}}</p>
                                        </td>

                                        <td  class="text-left col-form-label innercontent" style="background:#ededed;">Total Check points</td>
                                        <td  style="font-size:13px;text-transform:capitalize;width:7%"><a href="#">{{$data->total_checklist_qns}}</a></td>

                                        <td  class="text-left col-form-label innercontent" style="background:#ededed;">Completed </td>
                                        <td  style="font-size:13px;text-transform:capitalize;width:7%"><a href="#">{{$data->completed_qns}}</a></td>
                                        
                                        <td  class="text-left col-form-label innercontent" style="background:#ededed;">Remaining  </td>
                                        <td  style="font-size:13px;text-transform:capitalize;width:7%"><a href="#">{{$data->remaining_qns}}</a></td> 
                                    </tr>
                                </table>
                            </td>
                        </tr>    
                            <tr><td colspan="8">
                                <table width="100%">                 
                                    <tr>
                                        <td  class="text-left col-form-label innercontent" style="background:#ededed;vertical-align: middle;width:15%">Audit ID</td>
                                        <td  style="font-size:13px;text-transform:capitalize;vertical-align: middle;width:20%" class="text-left">
                                          <a href="#">{{$audits->aud_id}}</a>
                                          <input type="hidden" class="form-control innercontent" name="audit_name" id="audit_name" value="{{$audits->aud_id}}" style="font-size:13px;width:180px;" readonly/>
                                        </td>
                                        <td  class="text-left col-form-label innercontent" style="background:#ededed;vertical-align: middle;width:5%">Auditor</td>
                                        <td  style="font-size:13px;text-transform:capitalize;vertical-align: middle;width:25%" class="text-left">
                                          <a href="#">{{$audits->auditors}}</a>
                                          <input type="hidden" class="form-control innercontent" name="auditor" id="auditor" value="{{$audits->auditors}}" readonly/>
                                        </td>
                                        <td  class="text-left col-form-label innercontent" style="background:#ededed;vertical-align: middle;width:9%">Auditee</td>
                                        <td  style="font-size:13px;text-transform:capitalize;vertical-align: middle;" class="text-left">
                                         <a href="#">{{$audits->contact}}</a>
                                         <input type="hidden" class="form-control innercontent" name="contact" id="contact" value="{{$audits->contact}}" readonly/>
                                        </td>     
                                    </tr>
                                    
                                    <tr>
                                        <td  class="text-left col-form-label innercontent" style="background:#ededed;">Audit Date From</td>
                                        <td  style="font-size:13px;text-transform:capitalize"><input type="text" style="font-size: 13px;width:120px;" class="form-control"  id="auditfrmdate" name="auditfrmdate" value="{{date('d-m-Y',strtotime($audits->date))}}"   readonly></td>
                                        <td  class="text-left col-form-label innercontent" style="background:#ededed;width:12%">Audit Date To</td>
                                        <td colspan="3"  style="font-size:13px;text-transform:capitalize"><input type="text" style="font-size: 13px;width:120px;" class="form-control"  id="audittodate" name="audittodate" value="{{date('d-m-Y',strtotime($audits->enddate))}}"   readonly></td>
                                                                
                                    </tr>
                                </table>
                            </td></tr>                        

                            <input type="hidden" name="mainrecordid"  value="{{$data->id}}" />
                            <input type="hidden" name="audtchecklistid"  value="{{$data->audit_checklist_id}}" />
                            <input type="hidden" name="audit_checklist_id_year"  value="{{$data->audit_checklist_id_year}}" />
                            <input type="hidden" name="totalpoints"  value="{{$data->total_checklist_qns}}" />
                            <input type="hidden" name="count"  value="{{$data->count}}" />
                            

                        <tr><td colspan="8" class="text-white" style="font-size:15px;height:2px;font-family:Arial, Helvetica, sans-serif;background:#c1c1c1;"></td></tr>

                        <tr><td colspan="8">
                        <div class="table-responsive">
                         <table class="table table-sm w-100">
                            <thead>
                             <tr style="background:#ededed;">
                                <td class="text-center" style="font-size:14px;width:0.5%"></td>
                                <td class="text-center" style="font-size:14px;width:0.5%">#</td>
                                <td class="text-center" style="font-size:14px;width: 15%">Control Area</td>
                                <td class="text-center" style="font-size:14px;width: 15%">Purpose </td>
                                <td class="text-center" style="font-size:14px;width: 15%">Items to be Verified</td>
                                <td class="text-center" style="font-size:14px;width: 15%">Item Verified</td>
                                <td class="text-center" style="font-size:14px;width: 12%">Conformance</td>
                                <td class="text-center" style="font-size:14px;width: 15%">Remarks</td>
                                <td class="text-center" style="font-size:14px;width: 7%"></td>
                             </tr>
                            </thead>

                            <tbody id="tablecontents">
                                <?php
                                   $sno = 1;          
                                ?>                         
                            @foreach($pointstitle as $pttitle)
                                <tr>
                                    <td colspan="9" class="font-weight-bold" align="left" style="font-size:14px;height:5px;font-family:Arial, Helvetica, sans-serif;">
                                       <span style="margin-left:10px;margin-right:10px;">Section {{$sno++}} : {{ucfirst($pttitle->title_name)}}</span>          
                                    </td>
                                </tr>                        
                                    <?php
                                        $mainnum = $sno - 1;
                                        $no = 1;
                                        $newpoints = DB::table('audit_checklist_new_record_details')
                                                                                ->where('title_name',$pttitle->title_name)
                                                                                ->where('audit_checklist_id_year',$data->audit_checklist_id_year)
                                                                                ->where('is_deleted',0)
                                                                                ->get();                                   
                                    ?>                        
                                @foreach($newpoints as $point)                                 
                                    <tr>
                                        <td class="innercontent font-weight-normal"><input class="checkbox" type="checkbox" id="{{$point->id}}" name="id[]"></td>
                                        <td class="innercontent font-weight-normal">{{$mainnum}}.{{$no++}}</td>
                                        <td class="font-weight-normal innercontent">
                                        <span style="vertical-align: top;padding-left:0px;"><?php echo nl2br ($point->pointsname)?></span>
                                        </td>
                                        <td class="innercontent font-weight-normal"><?php echo nl2br ($point->purpose)?></td>
                                        <td class="innercontent font-weight-normal"><?php echo nl2br ($point->items_verified)?></td>
                                        
                                        <td class="innercontent font-weight-normal">
                                            @if($point->items_verified_records!=NULL)
                                            <?php echo nl2br ($point->items_verified_records)?>
                                            <textarea hidden class="form-control"style="font-size:12px;" id="items_verified_new" name="items_verified_new[]" readonly> <?php echo nl2br ($point->items_verified_records)?></textarea>
                                            @else
                                            <textarea class="form-control"style="font-size:12px;" id="items_verified_new" name="items_verified_new[]"></textarea>
                                            @endif
                                        </td>
                                        <td class="innercontent font-weight-normal">
                                        @if($point->conformance_id!=NULL)
                                            <?php
                                            $conformancevalues = DB::table('audit_checklist_conformances')->where('id',$point->conformance_id)->first();                               
                                            ?>
                                            <input type="hidden" name="conformance[]" id="conformance" value="{{$conformancevalues->id}}"/>
                                            @if($conformancevalues->stage=='OK')
                                            <span class="badge badge-success">OK</span>
                                            @elseif($conformancevalues->stage=='NC A')
                                            <span class="badge badge-danger">NC A</span>
                                            @elseif($conformancevalues->stage=='NC B')
                                            <span class="badge badge-warning">NC B</span>
                                            @elseif($conformancevalues->stage=='PI')
                                            <span class="badge badge-primary">PI</span>
                                            @elseif($conformancevalues->stage=='NA')
                                            <span class="badge badge-secondary">NA</span>
                                            @endif                                       
                                        @else
                                            @if(count($conformance)>0)
                                                <select class="form-control" name="conformance[]" id="conformance" style="font-size:13px;width:180px;">
                                                <option value="">Select Conformance</option>
                                                    @foreach($conformance as $conf)
                                                    <option value="{{$conf->id}}">{{$conf->stage}}</option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        @endif                                      
                                        </td>
                                        <td class="innercontent font-weight-normal">
                                        @if($point->remarks!=NULL)
                                        {{$point->remarks}} 
                                        <textarea hidden class="form-control"style="font-size:12px;" id="remarks" name="remarks[]" readonly>{{$point->remarks}}</textarea>
                                        @else
                                        <textarea class="form-control"style="font-size:12px;" id="remarks" name="remarks[]"></textarea>
                                        @endif
                                        <input type="hidden" name="points_id[]" value="{{$point->points_id}}"/>
                                        <input type="hidden" name="checklistmainid"  value="{{$point->checklist_id}}"/> 
                                            <input type="hidden" name="auditchecklistid[]" value="{{$point->audit_checklist_id}}" />
                                            <input type="hidden" name="addedby_userid[]"  value="{{Auth::user()->id}}"/>
                                            @if($checklist->department_id!=null)
                                            <input type="hidden" name="departmentid"  value="{{$checklist->department_id}}"/>
                                            @elseif($checklist->division_id!=null)
                                            <input type="hidden" name="division_id"  value="{{$checklist->division_id}}"/>
                                            @elseif($checklist->area_id!=null)
                                            <input type="hidden" name="area_id"  value="{{$checklist->area_id}}"/>
                                            @endif
                                            <input type="hidden" name="auditchecklistidyear[]" value="{{$point->audit_checklist_id_year}}" />            
                                        <input type="hidden" name="createdate[]" id="createdate[]" value="{{date('Y-m-d')}}"/>               
                                        </td> 
                                        <td>
                                            <div class="btn-group m-auto d-flex justify-content-center">
                                                <a href="#" class="btn btn-sm btn-alt-secondary" data-id="{{ $point->id }}"  
                                                    data-toggle="modal" data-placement="top"  
                                                    data-target="#addattachmentsModal" onclick="getcheckpointdetails('{{$point->id}}')">
                                                    <span data-toggle="tooltip" title="Click here to add attachments">
                                                    <i class="fa fa-2x fa-cloud-upload-alt text-center " style="font-size:12px;cursor:pointer" ></i>
                                                    </span>
                                                    
                                                </a>                                                        
                                                <a href="#" class="btn btn-sm btn-alt-dark" data-id="{{ $point->id }}"  
                                                data-toggle="modal" data-placement="top"  
                                                data-target="#viewattachments{{$point->id}}">
                                                <span data-toggle="tooltip" title="Click here to view attachments">
                                                    <i class="far fa-eye text-center " style="font-size:12px;cursor:pointer" ></i>
                                                </span>
                                                </a>
                                                <!-- view attachments starts -->
                                                <div id="viewattachments{{$point->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                                    <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                <h6 class="modal-title">View Attachments</h6>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <table class="table table-bordered"> 
                                                                    <tr>
                                                                        <td align="center">
                                                                            <table class="table table-striped" style="width:650px;">
                                                                            </tr>               
                                                                        <tr class="table-secondary">
                                                                            <td class="innercontent">#</td>
                                                                            <td class="innercontent font-weight-bold">Attachments</td>
                                                                            <td style="width:2%"></td>
                                                                        </tr>
                                                                <tbody id="getfiledetailsdb_{{$point->id}}">
                                                                    <?php
                                                                            $file_values = DB::table('audit_checklist_new_record_file_details')->where('record_details_id',$point->id)->get();
                                                                            $sl =1;
                                                                        ?>
                                                                        @if(count($file_values)>0)
                                                                            @foreach($file_values as $file_value)
                                                                                <tr>
                                                                                    <td style="width:2%;" class="innercontent">
                                                                                        {{$sl++}}
                                                                                    </td>        
                                                                                    <td class="text-left">                                                                        
                                                                                        {{ $file_value->file_name }}
                                                                                    </td>                                                                          
                                                                                    <td>
                                                                                        <div class="btn-group m-auto d-flex justify-content-center">
                                                                                        <a href="{{asset("storage/Auditchecklist/".$file_value->file_name)}}" 
                                                                                            class="btn btn-sm btn-alt-dark" 
                                                                                            download="{{$file_value->file_name}}" data-toggle="tooltip" style="font-size:11px;" 
                                                                                            title="Click here to download"> <i class="fa fa-download"></i>
                                                                                        </a>
                                                                                        <a href="{{route('deletechecklistnewrecordattachments',['id'=>$file_value->id])}}" 
                                                                                            class="btn btn-sm btn-alt-dark" 
                                                                                        onclick="return confirm('Are you sure?')" style="font-size:11px;" 
                                                                                        data-toggle="tooltip" title="Remove"><i class="fa fa-fw fa-times text-danger"></i>
                                                                                        </a>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        @else
                                                                        <tr><td colspan="4" class="text-danger text-center" style="font-size:12px;">No Attachments added</td></tr>
                                                                        @endif
                                                                </tbody>
                                                                <tbody id="getfiledetails_{{$point->id}}"></tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>                                                  
                                                                </table>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" 
                                                                        data-dismiss="modal" style="font-size:12px">Close 
                                                                    </button>
                                                                </div>
                                                            </div>
                                                    </div>
                                                </div>                                        
                                                <!-- view attachments ends -->
                                            </div> 
                                        </td> 
                                    </tr>
                                @endforeach                                       
                            @endforeach                                             
                           </tbody>
                        </table>
                      </div>
                    </td></tr>  
                       @if(in_array(Auth::user()->email,$auditorlists))
                            @if($data->record_status=='Pending')
                            <tr>
                                <td colspan="8" align="right">                
                                    <input type="submit" class="font-weight-normal btn btn-primary" style="font-size:14px;font-family:Arial, Helvetica, sans-serif;margin-top:20px;" name="save" value="Submit"/>                           
                                </td>
                            </tr>                      
                            @endif  
                       @endif              
                </table> 
                   </td></tr>                   
                </table>                 
                </div>                 
             </div>
            </form>           
            </div>
        </div>
    </div>

  <!-- add attachments starts-->
 <div class="modal fade" id="addattachmentsModal" tabindex="-1" role="dialog" aria-labelledby="addattachmentsModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addattachmentsModalLabel">Add Attachments</h5>
                        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="return closemodel()">
                            <span aria-hidden="true">&times;</span>
                        </button> -->
                    </div>
                <form id="add_attachments_ajax" method="POST" enctype="multipart/form-data">
                        @csrf
                    <div class="modal-body">
                        <div class="alert alert-success" id="addattachmentsuccess" style="display:none">
                           Attachments added successfully
                        </div>

                        <ul class="alert alert-danger" id="dangeraddattachment" style="display:none">
                           Internal server error, we are trying to fix them soon
                        </ul>

                        <ul class="alert alert-danger" id="attachmentnotupdated" style="display:none"></ul>

                        <div class="form-group">
                            <label class="col-form-label" style="font-size:12px;"> Attach files(avoid '#' and '&' in file names)</label>

                            <input type="file" style="font-size: 13px;" class="form-control" name="externalfiles[]" id="externalfiles" multiple required>
                        </div>                                  
                    </div>
                    <input type="hidden" name="point_main_id" id="point_main_id" value=""/>             
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px" onclick="return closemodel()">
                             Close
                        </button>
                        <button type="submit" class="btn btn-primary" style="font-size:12px">Upload</button>
                    </div>
                </form>
                </div>
            </div>
        </div>

<!-- add attachments ends-->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
     let CSRF_TOKEN = "";
            window.onload = function() {
                $('#getfiledetails').hide();
                $('#getfiledetailsdb').show();
                CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            }

    let currentRecordId = null;
    function getcheckpointdetails(id){
        document.getElementById('point_main_id').value=id;
        currentRecordId = id;
    }

    $(document).on('submit', '#add_attachments_ajax', function(e) {
                e.preventDefault();
                //let uploadformdata = new FormData($('#test')[0]);// used for uploading single file
                let uploadformdata = new FormData(this);
                let TotalImages = $('#externalfiles')[0].files.length; 
                let images = $('#externalfiles')[0];
                for (let i = 0; i < TotalImages; i++) {
                    uploadformdata.append('images' + i, images.files[i]);
                }

                uploadformdata.append('TotalImages', TotalImages);
              
                $.ajax({
                    data: uploadformdata
                    , url: "{{ route('addchecklistnewrecordattachments')}}"
                    , type: "POST"
                    , contentType: false
                    , processData: false
                    , _token: CSRF_TOKEN
                    , success: function(response) {
                        if (response.status == 200){
                            $('#addattachmentsuccess').fadeIn();
                            // Hide modal after brief delay
                                setTimeout(function () {
                                    $('#addattachmentsModal').modal('hide');
                                    $('#addattachmentsuccess').fadeOut();
                                    $('#add_attachments_ajax')[0].reset();
                                }, 1000);
                            //$('#addattachmentsuccess').css('display', 'block');
                            $('#getfiledetailsdb_' + currentRecordId).hide();
                            $('#getfiledetails_' + currentRecordId).show();

                            let fileValues = response.file_values;
                            console.log(fileValues);
                            let filehtmldatas='';
                            if(fileValues.length>0){
                                for(let i=0;i<fileValues.length;i++){
                                    var image = `<img class="drillimg" src="/storage/Auditchecklist/${fileValues[i]['file_name']}" alt="" width="100"`;
                                    let links = `<a href="/storage/Auditchecklist/${fileValues[i]['file_name']}" 
                                                                    class="btn btn-sm btn-alt-dark" 
                                                                    download="${fileValues[i]['file_name']}" data-toggle="tooltip" style="font-size:11px;" 
                                                                    title="Click here to download"> <i class="fa fa-download"></i>
                                                                </a>`;
                                    let removeitem = `<a href="#" 
                                                        class="btn btn-sm btn-alt-dark" 
                                                        onclick="return attachmentsdelete(${fileValues[i]['id']})" 
                                                        style="font-size:11px;" 
                                                        data-toggle="tooltip" title="Remove">
                                                        <i class="fa fa-fw fa-times text-danger"></i>
                                                      </a>`;
                                    filehtmldatas+=`<tr>
                                                        <td style="width:2%;" class="innercontent">
                                                          ${i+1}
                                                        </td>        
                                                        <td class="text-left">                                                                        
                                                         ${fileValues[i]['file_name']}      
                                                        </td>                                                                          
                                                        <td>
                                                            <div class="btn-group m-auto d-flex justify-content-center">
                                                               ${links}
                                                               ${removeitem}
                                                            </div>
                                                        </td>
                                                    </tr>`;
                                } 
                            }
                            else
                                {
                                    filehtmldatas+='<tr><td colspan="4" class="text-danger text-center" style="font-size:12px;">No Attachments added</td></tr>';
                                } 
                                $('#getfiledetails_' + currentRecordId).html(filehtmldatas);
                        }                 

                    },
                    error: function(err) {
                             if (err.status === 500) {
                                var error_text='Internal server error, we are trying to fix them soon';                               
                                $.each(err.responseJSON.errors, function(i) {
                                $.each(err.responseJSON.errors[i], function(key, val) {
                                    error_text += '<li style="font-size:12px;">' + val + '</li>';
                              });
                            });                               
                                $('#attachmentnotupdated').html(error_text);
                                $('#dangeraddattachment').hide();
                                $('#attachmentnotupdated').css('display', 'block');
                            }            
                        }
                });

            });

            function closemodel(){
                $('#externalfiles').val('');
                $('#addattachmentsuccess').hide();
                $('#attachmentnotupdated').hide();
                $('#dangeraddattachment').hide();
            }

            $(document).ready(function () {
            $('#addattachmentsModal').on('hidden.bs.modal', function () {
                $('#externalfiles').val('');
                $('#addattachmentsuccess').hide();
                $('#attachmentnotupdated').hide();
                $('#dangeraddattachment').hide();
            });
        });

</script>
<script>
    function attachmentsdelete(delid) {
        message1 = "Are you sure?";
        x = confirm(message1);
        $.ajax({
                type: 'get'
                , url: "{{route('ajaxdeletechecklistnewrecordattachments', ':id')}}".replace(':id', delid)
                , success: function(response) {                     
                    if(response.status == 200){
                    setTimeout(function() {
                        window.location.href = "{{ url('/user/internalaudit/checklistnewrecordsdisplay') }}" + "/" + response.data;
                    }, 100);                           
                    }
                }
                , error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    }
            });                  
    }
</script>

{{-----------delete button  ------------}}   
        <script>
            $("#del").click(function() {
                mess1 = "Are you sure?";
                x = confirm(mess1);
                if (x == true) {
                    mess2 = 'Details will be deleted,Press OK to continue.';
                    y = confirm(mess2);
                    if (y == true) {} else {
                        return false;
                    }
                } else {
                    return false;
                }
                var dataArr = new Array();
                if ($('input:checkbox:checked').length > 0) {
                    $('input:checkbox:checked').each(function() {
                        dataArr.push($(this).attr('id'));
                        // console.log(dataArr);
                        $.ajax({
                            type: 'get'
                            , url: "{{route('deletechecklistnewrecords')}}" + "?val=" + $(this).attr('id')
                            , success: function(response) {
                                // console.log(response);
                              if(response.status == 200)
                              {
                                window.location.href = "{{url('/user/internalaudit/checklistnewrecordsdisplay')}}" + "/"  + response.data;
                                document.getElementById("successmessage").innerHTML +=
                                "<div class='alert alert-success'><a class='close' data-dismiss='alert'>×</a>Records deleted successfully</div>";   
                              }
                            }
                            , error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                              }
                        });
                    });

                } else {
                    alert('No Record Selected');
                }
            });
</script>

    <div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">Options</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content block-content-full">
                        <div class="row gutters-tiny">
                            <div class="col-6">
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/auditschedule">
                                    <div class="block-content text-center">
                                        <i class="far fa-list-alt fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Audit Schedule
                                        </p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6">
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/internalaudit/auditorslist">
                                    <div class="block-content text-center">
                                        <i class="fas fa-address-card fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            <br> Auditors List
                                        </p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6">
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/internalaudit/auditlog">
                                    <div class="block-content text-center">
                                        <i class="fas fa-calendar-check fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            <br>Audit Log
                                        </p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6">
                                <a class="block block-rounded block-link-shadow bg-body mb-2" href="/user/internalaudit/closedaudits">
                                    <div class="block-content text-center">
                                        <i class="fas fa-check-circle fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Closed Audits
                                        </p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6">
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href='/user/internalaudit/analysis'>
                                    <div class="block-content text-center">
                                        <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Analysis
                                        </p>
                                    </div>
                                </a>
                            </div>
                </div>
            </div>
        </div>
    </div>
</div>



                              


@endsection
@section('js_after')
<script>
     function autoResizeDiv(height) {
            document.getElementById('content').style.height = (height) + 'px';
        }
        window.onresize = autoResizeDiv;
        autoResizeDiv(window.innerHeight - 240);
</script>
@endsection






