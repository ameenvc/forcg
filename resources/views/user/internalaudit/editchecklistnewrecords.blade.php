@extends('layouts.multiselect')
@section('content')
<style>
    .innercontent{
        font-size:13px;
        font-family:Arial, Helvetica, sans-serif;
    }

    .table-responsive{
  height:540px;  
  overflow:scroll;
}
 thead tr:nth-child(1) td{
    background: #ededed;
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
                                        <a class="link-fx" href=""> Checklist Library</a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="">Edit Record Details</a>
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
                                    @if(in_array(Auth::user()->email,$auditorlists))
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx text-danger" href="{{route('checklistnewrecordsdisplay',['id'=>$data->id])}}">Back</a>
                                    </li> 
                                   @endif
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

            <form class="mb-5" action="{{route('updatechecklistnewrecords')}}" method="POST" enctype="multipart/form-data">             
             @csrf
            <div class="row" style="margin-left:5px;margin-right:5px;" >
                <div class="col-lg-12 col-md-6">
            <table  width="100%">
              <tr id="record"><td width="100%">
                    <table width="100%" cellpadding="0" cellspacing="0" class="table table-bordered">                                   
                                                      
                            <tr><td colspan="8">
                                <table width="100%">  
                                <tr>
                                       <td  class="text-left col-form-label innercontent" style="background:#ededed;vertical-align: middle;width:15%">Audit Checklist ID</td>
                                        <td  style="font-size:13px;text-transform:capitalize;vertical-align: middle;">
                                          <a href="#">{{$data->audit_checklist_id}}</a>
                                          <input type="hidden" class="form-control innercontent" name="auditcheckid" id="auditcheckid" value="{{$data->audit_checklist_id}}" style="font-size:13px;width:180px;" readonly/>
                                        </td>

                                        <td  class="text-left col-form-label innercontent" style="background:#ededed;vertical-align: middle;width:15%">Checklist Name</td>
                                        <td  style="font-size:13px;text-transform:capitalize;vertical-align: middle;">
                                          <a href="#">{{ucfirst($checklist->checklist_name)}}</a>
                                          <input type="hidden" class="form-control innercontent" name="checklistname" id="checklistname" value="{{ucfirst($checklist->checklist_name)}}" style="font-size:13px;width:180px;" readonly/>
                                        </td>

                                        <td  class="text-left col-form-label innercontent" style="background:#ededed;vertical-align: middle;width:15%">{{$category}}</td>
                                        <td  style="font-size:13px;text-transform:capitalize;vertical-align: middle;">
                                          <a href="#">{{ucfirst($listing_name)}}</a>
                                          <input type="hidden" class="form-control innercontent"  value="{{ucfirst($listing_name)}}" style="font-size:13px;width:180px;" readonly/>
                                        </td>
                                </tr>               
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
                            <input type="hidden"  name="audit_id" value="{{$audits->id}}"  />
                            <input type="hidden" name="mainrecordid"  value="{{$data->id}}" />
                            <input type="hidden" name="updatedby_userid"  value="{{Auth::user()->id}}" />
                            <input type="hidden" name="audtchecklistid"  value="{{$data->audit_checklist_id}}" />
                            <input type="hidden" name="audtchecklistidyear"  value="{{$data->audit_checklist_id_year}}" />
                            <input type="hidden" name="totalpoints"  value="{{$data->total_checklist_qns}}" />
                            <input type="hidden" name="count"  value="{{$data->count}}" />
                            

                        <tr><td colspan="8" class="text-white" style="font-size:15px;height:2px;font-family:Arial, Helvetica, sans-serif;background:#c1c1c1;"></td></tr>
                       
                        <tr><td colspan="8">
                         <table width="100%" class="table-responsive">
                          <thead>
                             <tr style="background:#ededed;">
                                <!-- <td class="text-center" style="font-size:14px;width:0.5%"></td> -->
                                <td class="text-center" style="font-size:14px;width:0.5%">#</td>
                                <td class="text-center" style="font-size:14px;width: 15%">Control Area</td>
                                <td class="text-center" style="font-size:14px;width: 15%">Purpose </td>
                                <td class="text-center" style="font-size:14px;width: 15%">Items to be Verified</td>
                                <td class="text-center" style="font-size:14px;width: 15%">Item Verified</td>
                                <td class="text-center" style="font-size:14px;width: 12%">Conformance</td>
                                <td class="text-center" style="font-size:14px;width: 15%">Remarks</td>
                             </tr>
                          </thead>

                          <tbody id="tablecontents">
                                <?php
                                   $sno = 1;          
                                ?>
                           
                           @foreach($pointstitle as $pttitle)
                           <tr>
                                <td colspan="8" class="font-weight-bold" style="font-size:14px;height:5px;font-family:Arial, Helvetica, sans-serif;">
                                  <span style="margin-left:10px;margin-right:10px;">Section {{$sno++}} : {{ucfirst($pttitle->title_name)}}</span>
                                  
                               </td>
                            </tr>  
                       
                                <?php
                                    $mainnum = $sno - 1;
                                    $no = 1;
                                    $newpoints = DB::table('audit_checklist_new_record_details')
                                                                            ->where('title_name',$pttitle->title_name)
                                                                            //->where('audit_checklist_id',$data->audit_checklist_id)
                                                                            ->where('audit_checklist_id_year',$data->audit_checklist_id_year) 
                                                                            ->where('is_deleted',0)
                                                                            ->get();
                                   
                                ?>
                         
                        @foreach($newpoints as $point)
                                  
                                <tr>
                                    <!-- <td class="text-center" style="font-size:14px;width:0.5%">
                                       @if($point->conformance_id!=NULL)
                                      <input  style="border-radius:50%;color:grey;" type="checkbox" checked  id="{{$point->id}}" name="id[]">
                                      @else
                                        <input  style="border-radius:50%;color:grey;" type="checkbox"  id="{{$point->id}}" name="id[]">
                                      @endif 
                                    </td> -->
                                    <td class="innercontent font-weight-normal">{{$mainnum}}.{{$no++}}</td>
                                    <td class="font-weight-normal innercontent">
                                       <textarea class="form-control"style="font-size:12px;" id="pointsname" name="pointsname[]" rows="5" cols="15"> <?php echo nl2br ($point->pointsname)?></textarea>
                                    </td>
                                    <td class="innercontent font-weight-normal">
                                        <textarea class="form-control"style="font-size:12px;" id="purpose" name="purpose[]" rows="5" cols="15"> <?php echo nl2br ($point->purpose)?></textarea>
                                    </td>
                                    <td class="innercontent font-weight-normal">
                                        <textarea class="form-control"style="font-size:12px;" id="items_verified" name="items_verified[]" rows="5" cols="15"> <?php echo nl2br ($point->items_verified)?></textarea>
                                    </td>
                                    
                                    <td class="innercontent font-weight-normal">                               
                                        <textarea class="form-control"style="font-size:12px;" id="items_verified_new" name="items_verified_new[]" rows="5" cols="15"> <?php echo nl2br ($point->items_verified_records)?></textarea>                               
                                    </td>

                                    <td class="innercontent font-weight-normal">                                                                 
                                       @if(count($conformance)>0)
                                            <select class="form-control" name="conformance[]" id="conformance" style="font-size:13px;width:180px;">
                                            <option value="">Select Conformance</option>
                                                @foreach($conformance as $conf)
                                                <option value="{{$conf->id}}" <?php if($point->conformance_id==$conf->id) {?> selected<?php } ?>>{{$conf->stage}}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </td>

                                    <td class="innercontent font-weight-normal">                   
                                       <textarea class="form-control"style="font-size:12px;" id="remarks" name="remarks[]" rows="5" cols="15">{{$point->remarks}}</textarea>
                                       
                                       <input type="hidden" name="points_id[]" value="{{$point->points_id}}"/>
                                       <input type="hidden" name="checklistmainid"  value="{{$point->checklist_id}}"/> 
                                        <input type="hidden" name="auditchecklistid[]" value="{{$point->audit_checklist_id}}" />
                                        <input type="hidden" name="audtchecklistidyear[]"  value="{{$point->audit_checklist_id_year}}" />
                                        
                                       
                                       <input type="hidden" name="createdate[]" id="createdate[]" value="{{date('Y-m-d')}}"/>
                                       
                                    </td> 
                                </tr>
                               
                                @endforeach     
                        @endforeach
                        
                        </tbody>
                            </table>
                            </td></tr> 
                       
                        <tr>
                            <td colspan="8" align="right">                
                                <input type="submit" class="font-weight-normal btn btn-primary" style="font-size:14px;font-family:Arial, Helvetica, sans-serif;margin-top:20px;" name="save" value="Update"/>                           
                            </td>
                        </tr>
                         
                                                           
                    <!-- </table>
                  </td></tr> -->

                </table> 
                   </td></tr>

                    
                </table>
                 
                </div>
               
                 
             </div>
            </form>

            
            </div>
        </div>
    </div>

  

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>



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






