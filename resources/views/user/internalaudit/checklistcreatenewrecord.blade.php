@extends('layouts.multiselect')
@section('content')
<style>
    .innercontent{
        font-size:13px;
        font-family:Arial, Helvetica, sans-serif;
    }

    .maintable{
    height:550px;  
    overflow:scroll;
  /* overflow-y: scroll; */
}
    .freezestand{
        background:#ededed;
        position: sticky;
        top: 0;
        z-index: 10;
    }

   
    
</style>

<div class="bg-body-light">
                    <div class="content content-full">
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
                           
                            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-alt">
                                    <li class="breadcrumb-item">Audit Management</li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx text-dark" href=""> Checklist Library</a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx text-dark" href="">Create New Record</a>
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
                                    <!-- <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx text-danger" href="{{route('checklistnewrecord',['id'=>$checklist->id])}}">Back</a>
                                    </li>  -->
                                </ol>
                            </nav>
                        </div>
                    </div>
 </div>


<div class="content" style="width:100%;">
    <div class="block block-rounded" >
        <div class="block-content block-content-full">
            <!-- <div class="scrollable" style="overflow-x:hidden;" id="content">  -->
            @if(Session::has('msg'))
                            <div class="alert alert-success">
                                <a class="close" data-dismiss="alert">×</a>
                                {!!Session::get('msg')!!}
                            </div>
                        @endif
                        <!-- <div class="maintable"> -->
            <form class="mb-5" action="{{route('insertchecklistnewrecord')}}" method="POST" enctype="multipart/form-data">
              
             @csrf
              
              <div class="row" style="margin-left:5px;margin-right:5px;">
                <div class="col-lg-12 col-md-6">
            
                <table width="100%" cellpadding="0" cellspacing="0" class="table table-borderless" style="border:1px solid #e1e6e9;">                 
                                   
                                    <tr>
                                        <td  class="text-left col-form-label innercontent font-weight-bold text-center" style="width:10%;vertical-align: middle;">Audit Checklist #</td>
                                        <td  style="font-size:13px;width:10%;border-right:1px solid #e1e6e9;">
                                        <input type="text" style="font-size: 13px;" class="form-control"  id="auditcheckid" name="auditcheckid" value="{{$auditchecklistid}}" readonly/>
                                       </td>
      
                                       <td  class="text-left col-form-label innercontent font-weight-bold text-center" style="width:10%;vertical-align: middle;">Checklist Name</td>
                                        <td  style="font-size:13px;width:10%;vertical-align: middle;border-right:1px solid #e1e6e9;">
                                           <input type="text" class="form-control innercontent" name="checklistname" id="checklistname" value="{{ucfirst($checklist->checklist_name)}}"  readonly/>
                                        </td> 

                                       <td  class="text-left col-form-label innercontent font-weight-bold text-center" style="width:10%;vertical-align: middle;">{{$category}}</td>
                                        <td  style="font-size:13px;width:10%;vertical-align: middle;">
                                          <input type="text" class="form-control innercontent" value="{{ucfirst($listing_name)}}" readonly style="font-size:13px;width:180px;"/>
                                       </td>  
                                       <?php
                                          $today=Carbon\Carbon::now()->toDateTimeString();
                                          $today=Carbon\Carbon::parse($today);                                   
                                       ?> 
                                       <input type="hidden"   id="addeddate" name="addeddate" value="{{$today->format('d/m/Y')}}">
                                       <input type="hidden" class="" name="auditoremail" id="auditoremail"  />
                                       <input type="hidden"  name="audit_id" value="{{$audits->id}}"  />
                                    </tr>
                                    
                                </table>
                               
                </div>
              </div>
           

            <div class="row" style="margin-left:5px;margin-right:5px;" >
                <div class="col-lg-12 col-md-6">
                        
            <!-- <div class="maintable"> -->

            <table  width="100%">
              <tr id="record"><td width="100%">
                    <table width="100%" cellpadding="0" cellspacing="0" class="table table-bordered">                                   
                                 <!-- <tr class="bg-secondary">                     -->                          
                      <tr><td colspan="8">
                            <table width="100%">                 
                                <tr>
                                    <td  class="innercontent" style="background:#ededed;">Audit Progress</td>
                                    <td  style="font-size:15px;font-family:Arial, Helvetica, sans-serif;width:40%">
                                        <div class="progress mb-1" style="height: 7px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <p class="fs-xs fw-semibold mb-0">0%</p>                
                                    </td>

                                    <td  class="text-left col-form-label innercontent" style="background:#ededed;">Total Check points</td>
                                    <td  style="font-size:13px;text-transform:capitalize;width:7%"><a href="#">{{$totalpoints}}</a></td>

                                    <td  class="text-left col-form-label innercontent" style="background:#ededed;">Completed</td>
                                    <td  style="font-size:13px;text-transform:capitalize;width:7%"><a href="#">0</a></td>

                                    <td  class="text-left col-form-label innercontent" style="background:#ededed;">Remaining</td>
                                    <td  style="font-size:13px;text-transform:capitalize;width:7%"><a href="#">0</a></td> 

                                </tr>
                            </table>
                            </td>
                        </tr>    

                            <tr><td colspan="8">
                                <table width="100%">                 
                                    <tr>
                                        <td  class="text-left col-form-label innercontent" style="background:#ededed;vertical-align: middle;width:15%">Audit ID</td>
                                        <td  style="font-size:13px;text-transform:capitalize;vertical-align: middle;width:20%">
                                          <a href="#">{{$audits->aud_id}}</a>
                                          <input type="hidden" class="form-control innercontent" name="audit_name" id="audit_name" value="{{$audits->aud_id}}" style="font-size:13px;width:180px;" readonly/>
                                        </td>
                                        <td  class="text-left col-form-label innercontent" style="background:#ededed;vertical-align: middle;width:5%">Auditor</td>
                                        <td  style="font-size:13px;text-transform:capitalize;vertical-align: middle;width:25%">
                                          <a href="#">{{$audits->auditors}}</a>
                                          <input type="hidden" class="form-control innercontent" name="auditor" id="auditor" value="{{$audits->auditors}}" readonly/>
                                        </td>
                                        <td  class="text-left col-form-label innercontent" style="background:#ededed;vertical-align: middle;width:9%">Auditee</td>
                                        <td  style="font-size:13px;text-transform:capitalize;vertical-align: middle;">
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
                     
                            <input type="hidden" name="audtchecklistid"  value="{{$auditchecklistid}}" />
                            <input type="hidden" name="audit_checklist_id_year"  value="{{$auditchecklistidyear}}" />

                            <input type="hidden" name="totalpoints"  value="{{$totalpoints}}" />
                            <input type="hidden" name="count"  value="{{$count}}" />
                    </table>
                    </td></tr>
            </table></div></div>
            
            <div class="row" style="margin-left:5px;margin-right:5px;">
                <div class="col-lg-12 col-md-6">

                <div class="maintable">
                <table width="100%" cellpadding="0" cellspacing="0" class="table table-bordered"> 
                        <tr><td colspan="8" style="background:#c1c1c1;"></td></tr>
                       
                             <tr class="freezestand">
                                <!-- <td class="text-center" style="font-size:14px;width:0.5%"></td> -->
                                <td class="text-center" style="font-size:14px;width:0.5%">#</td>
                                <td class="text-center" style="font-size:14px;width: 15%">Control Area</td>
                                <td class="text-center" style="font-size:14px;width: 15%">Purpose </td>
                                <td class="text-center" style="font-size:14px;width: 15%">Items to be Verified</td>
                                <td class="text-center" style="font-size:14px;width: 15%">Item Verified</td>
                                <td class="text-center" style="font-size:14px;width: 12%">Conformance</td>
                                <td class="text-center" style="font-size:14px;width: 15%">Remarks</td>
                                <td class="text-center" style="font-size:14px;width: 7%"></td>
                             </tr>
                                <?php
                                   $sno = 1;          
                                ?>
                           @foreach($titles as $title)
                            <tr>
                                <td colspan="8" class="font-weight-bold" style="font-size:14px;height:5px;font-family:Arial, Helvetica, sans-serif;">
                                  <span style="margin-left:10px;margin-right:10px;">Section {{$sno++}} : {{ucfirst($title->title)}}</span>
                                  <input type="hidden" name="title_id" value="{{$title->id}}"/>
                               </td>
                            </tr>
                       
                                <?php
                                    $mainnum = $sno - 1;
                                    $no = 1;
                                    $allpoints = DB::table('audit_checklist_points')->where('checklist_title_id',$title->id)->get();
                                    $arrid = 0;
                                ?>
                        @foreach($allpoints as $point)
                           
                                <tr>
                                    <!-- <td class="text-center" style="font-size:14px;width:0.5%">
                                           <input  style="border-radius:50%;color:grey;" type="checkbox"  id="{{$point->id}}" name="id[]"> 
                                    </td> -->
                                    <td class="innercontent font-weight-normal">{{$mainnum}}.{{$no++}}</td>
                                    <td class="font-weight-normal innercontent">
                                       <span style="vertical-align: top;padding-left:0px;"><?php echo nl2br ($point->points)?></span>
                                    </td>
                                    <td class="innercontent font-weight-normal"><?php echo nl2br ($point->purpose)?></td>
                                    <td class="innercontent font-weight-normal"><?php echo nl2br ($point->items_verified)?></td>
                                    
                                    <td class="innercontent font-weight-normal">                                      
                                        <textarea class="form-control"style="font-size:12px;" id="items_verified_new" name="items_verified_new[]"></textarea>                                      
                                    </td>
                                    <td class="innercontent font-weight-normal">                   
                                        @if(count($conformance)>0)
                                            <select class="form-control" name="conformance[]" id="conformance" style="font-size:13px;width:180px;">
                                            <option value="">Select Conformance</option>
                                                @foreach($conformance as $conf)
                                                <option value="{{$conf->id}}">{{$conf->stage}}</option>
                                                @endforeach
                                            </select>
                                        @endif                                      
                                    </td>
                                    <td class="innercontent font-weight-normal">
                                       <textarea class="form-control"style="font-size:12px;" id="remarks" name="remarks[]"></textarea>
                                       <input type="hidden" name="points_id[]" value="{{$point->id}}"/>
                                       <input type="hidden" name="checklistmainid"  value="{{$checklist->id}}"/> 
                                        <input type="hidden" name="auditchecklistid[]" value="{{$auditchecklistid}}" />
                                        <input type="hidden" name="addedby_userid[]"  value="{{Auth::user()->id}}"/>
                                        @if($checklist->department_id!=null)
                                        <input type="hidden" name="departmentid"  value="{{$checklist->department_id}}"/>
                                        @elseif($checklist->division_id!=null)
                                        <input type="hidden" name="division_id"  value="{{$checklist->division_id}}"/>
                                        @elseif($checklist->area_id!=null)
                                        <input type="hidden" name="area_id"  value="{{$checklist->area_id}}"/>
                                        @endif
                                        <input type="hidden" name="auditchecklistidyear[]" value="{{$auditchecklistidyear}}" />
                                        
                                       <input type="hidden" name="createdate[]" id="createdate[{{$arrid}}]" value="{{date('Y-m-d')}}"/>
                                       
                                    </td> 
                                    <td>
                                        <div class="btn-group m-auto d-flex justify-content-center">
                                          <button type="submit" class="btn btn-sm btn-alt-secondary mr-1 mb-3" data-toggle="tooltip" title="Proceed to add attachments">
                                            <i class="fa fa-2x fa-angle-double-right" style="font-size:12px;cursor:pointer" ></i>
                                           </button>                                      
                                    </td> 
                                </tr>
                               
                           <?php $arrid = $arrid+1;?>    
                        @endforeach
                        @endforeach

                        <!-- <tr>
                            <td colspan="8" align="right" >
                                <input type="submit" class="font-weight-normal btn btn-primary" style="font-size:14px;font-family:Arial, Helvetica, sans-serif;margin-top:20px;" name="save" value="Submit"/>
                            </td>
                        </tr>                                          -->
                </table> 
                </div></div>
                </div>  
                
                <div class="row" style="margin-left:5px;margin-right:5px;">
                <div class="col-lg-12 col-md-6">         
                    <table width="100%" cellpadding="0" cellspacing="0" class="table table-borderless">                                                   
                    <tr>
                            <td  align="right" >
                                <input type="submit" class="font-weight-normal btn btn-primary" style="font-size:14px;font-family:Arial, Helvetica, sans-serif;" name="save" value="Submit"/>
                            </td>
                        </tr>                                                 
                    </table>                             
                </div>
              </div>
             </div>
            </div>
            </form>                    
            </div>
        </div>
    </div>
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






