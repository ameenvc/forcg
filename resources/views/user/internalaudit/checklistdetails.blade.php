@extends('pages.datatables')
@section('content')
<style>
    .innercontent{
        font-size:13px;
        font-family:Arial, Helvetica, sans-serif;
    }

    .checkbox-round {
    width: 1.3em;
    height: 1.3em;
    background-color: white;
    border-radius: 50%;
    vertical-align: middle;
    border: 1px solid #ddd;
    appearance: none;
    -webkit-appearance: none;
    outline: none;
    cursor: pointer;
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
</style>
<div class="content" style="width:100%;">
@include('user.internalaudit.checklistheader') 
    <div class="block block-rounded" >
        <div class="block-content block-content-full">
            <div class="row" style="margin-bottom:10px;">
                <div class="col-lg-12 col-md-6">
                      <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-alt">
                                   <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx text-dark" href="">{{$main_heading}}</a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="">
                                          @if($divisionname!=null)
                                            <span style="color:#808080;">
                                                {{ucfirst($heading_name)}}
                                            </span>
                                          @elseif($areaname!=null)
                                            <span style="color:#b5651d">
                                               {{ucfirst($heading_name)}}
                                            </span>
                                          @else
                                           <span class="{{$heading_font}}">
                                                {{ucfirst($heading_name)}}
                                            </span>
                                          @endif
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx text-success" href="">
                                            <span style="color:#669933;">
                                            {{ucfirst($checklist->checklist_name)}}
                                            </span>
                                        </a>
                                    </li>
                                </ol>
                            </nav>
                </div>
            </div>

            <div class="row" style="margin-left:50px;margin-right:15px;margin-bottom:10px;">
                <div class="col-lg-12 col-md-6" align="right">
                        <form class="d-none d-md-inline-block" action="{{url('/user/internalaudit/checklistdetails/'.$checklist->id)}}" method="GET">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control form-control-alt" placeholder="Search.." style="height:34px;"  id="search" name="search" value="{{$serchval}}">
                                <input type="hidden" name="verify" value="1"/>
                                <div class="input-group-append">
                                    <span class="input-group-text bg-body border-0">
                                      <button type="submit" class="btn btn-sm" style="height:25px;" valign="-10px;">
                                        <i class="fa fa-fw fa-search"></i>
                                      </button>
                                    </span>
                                </div>
                            </div>
                        </form>
                </div>
              </div>

             <div class="row" style="margin-left:15px;margin-right:15px;">
                <div class="col-lg-12 col-md-6">
                        @if(Session::has('msg'))
                            <div class="alert alert-success">
                                <a class="close" data-dismiss="alert">×</a>
                                {!!Session::get('msg')!!}
                            </div>
                        @endif
                    
                    <table width="100%" cellpadding="0" cellspacing="0" class="table table-bordered">  
                            <tr>
                                <td align="left" colspan="6" class="innercontent font-weight-bold text-dark">
                                <span style="margin-left:10px;margin-right:10px;">Checklist Name : <span style="color:#669933;">{{ucfirst($checklist->checklist_name)}}</span></span>
                                @if($checklist->delete_status!='yes')
                                    @if(Gate::allows('audit-checklist-edit'))
                                    <a href="#"  class="btn btn-sm btn-alt-primary" style="font-size:10px;"  title="Edit" data-toggle="modal"  data-target="#editchecklistnameModal"><i class="fa fa-fw fa-pencil-alt"></i></a>
                                    @endif 
                                    @if(Gate::allows('audit-checklist-delete'))
                                    <a href="{{route('deletechecklistname',['id'=>$checklist->id])}}"  class="btn btn-sm btn-alt-danger" style="font-size:10px;" data-toggle="tooltip"  title="Delete"><i class="fa fa-fw fa-times"></i></a>
                                    @endif 
                                @endif
                                </td>
                            </tr>                         
                            <tr>          
                                @if($checklist->status!=null)
                                <td align="left" width="45%">  
                                    @if($checklist->delete_status=='yes')
                                    <button type="button" class="btn btn-sm bg-city-lighter font-weight-bold"  
                                            style="font-size:13px;width: 200px;font-family:system-ui;margin-left:10px;">
                                            Removel Request Pending
                                    </button>
                                    @else
                                        @if($checklist->status=='Awaiting Approval')                      
                                            <button type="button" class="btn btn-sm btn-alt-primary font-weight-bold text-dark"  
                                                    style="font-size:13px;width: 150px;font-family:system-ui;margin-left:10px;">
                                                    {{ucfirst($checklist->status)}}
                                            </button> 
                                        @elseif($checklist->status=='Approved')
                                            <?php
                                                $approvedate = $checklist->approved_date;
                                                $time=date("g:i A", strtotime($approvedate));                                       
                                            ?>
                                            <button type="button" class="btn btn-sm btn-success font-weight-bold text-white"  
                                                    style="font-size:13px;width: 100px;font-family:system-ui;margin-left:10px;">
                                                    {{ucfirst($checklist->status)}} 
                                            </button> 
                                            <span class="innercontent font-weight-bold text-smooth-dark" style="font-family:system-ui;"> 
                                                on {{date('d/M/Y',strtotime($checklist->approved_date))}},{{$time}} by
                                                    {{$checklist->userdetails?$checklist->userdetails->name:'--'}} 
                                                    ({{ $checklist->userdetails?$checklist->userdetails->employeeid:'--' }})
                                            </span>
                                        @elseif($checklist->status=='Need Improvement')
                                        <button type="button" class="btn btn-sm btn-alt-success font-weight-bold text-dark"  
                                                style="font-size:13px;width: 200px;font-family:system-ui;margin-left:10px;">
                                                {{ucfirst($checklist->status)}}
                                        </button> 
                                        @endif   
                                    @endif
                                                              
                                </td>
                                @endif
                                <td align="right">
                                @if(in_array(Auth::user()->email,$auditors) && $checklist->delete_status!='yes')
                                   <a href="#" class="button btn-sm btn btn-primary font-weight-normal" style="font-size:13px;font-family:system-ui;" data-toggle="modal"  data-target="#titleModal">Add Sub title</a>
                                   <a href="{{route('addchecklistpoint',['id'=>$checklist->id])}}" class="button btn btn-sm btn-primary  font-weight-normal" style="font-size:13px;font-family:system-ui;">Add Checkpoints</a>
                                   <a href="{{route('checklistexcelupload',['id'=>$checklist->id])}}" class="button btn btn-sm btn-primary  font-weight-normal" style="font-size:13px;font-family:system-ui;">Upload Checklist</a>
                                @else
                                   <button type="button" class="btn btn-sm btn-alt-primary font-weight-normal text-white"  style="font-size:13px;font-family:system-ui;">Add Sub title</button> 
                                   <button type="button" class="btn btn-sm btn-alt-primary font-weight-normal text-white"  style="font-size:13px;font-family:system-ui;">Add Checkpoints</button> 
                                   <button type="button" class="btn btn-sm btn-alt-primary font-weight-normal text-white"  style="font-size:13px;font-family:system-ui;">Upload Checklist</button> 
                                @endif      
                                </td>
                            </tr>                          

                    @if(count($titles) > 0)
                            <tr><td colspan="6"><table width="100%">                 
                            <tr>
                                <td class="text-left col-form-label" style="font-size:13px;background:#ededed;font-family:system-ui;width:15%">Total Checklist Questions</td>
                                <td style="font-size:13px;text-transform:capitalize;width:70%"><a href="#">{{$totalpoints}}</a></td>
                            @if($checklist->status!=null)                               
                             <td style="width:5%">
                                   <div class="btn-group m-auto d-flex justify-content-center">
                                   @if(in_array(Auth::user()->email,$auditors))
                                        <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" data-placement="top" title="Actions" id="drop">
                                          <span data-toggle="tooltip" title="Click here to approve or remove checklist and suggest improvements">
                                            <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer"></i>
                                          </span>
                                        </a>
                                    
                                        <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownMenu">                                         
                                            @if($checklist->delete_status=='yes')
                                               @if(Gate::allows('audit-checklist-records-approve')) 
                                                <a href="{{route('approvechecklistremoval',$checklist->id)}}" class="dropdown-item" style="font-size:10px;color:#343a40;">
                                                    <i class="far fa-2x fa-check-circle text-info" style="font-size:10px;color:red;"></i>&nbsp;&nbsp;
                                                      Approve Delete Checklist
                                                </a>
                                                <a href="{{route('cancelchecklistremoval',$checklist->id)}}" class="dropdown-item" style="font-size:10px;color:#343a40;">
                                                    <i class="far fa-window-close" style="font-size:10px;color:red;"></i>&nbsp;&nbsp;
                                                      Cancel Delete Checklist
                                                </a>
                                                @endif
                                            @else
                                                @if($checklist->status=='Awaiting Approval' && $checklist->status!='Need Improvement')
                                                @if(Gate::allows('audit-checklist-records-approve'))  
                                                    <a href="{{route('mainchecklistapprove',$checklist->id)}}" class="dropdown-item" style="font-size:10px;color:#343a40;">
                                                        <i class="far fa-2x fa-check-circle" style="font-size:10px;"></i>&nbsp;&nbsp;
                                                        Approve
                                                    </a>
                                                    @endif
                                                    <a href="{{route('addchecklistsuggestions',$checklist->id)}}" class="dropdown-item" style="font-size:10px;color:#343a40;">
                                                        <i class="far fa-2x fa-edit" style="font-size:10px;"></i>&nbsp;&nbsp;
                                                        Need Improvement
                                                    </a>
                                                @endif
                                                @if($checklist->status=='Need Improvement')
                                                @if(Gate::allows('audit-checklist-records-approve')) 
                                                <a href="{{route('mainchecklistapprove',$checklist->id)}}" class="dropdown-item" style="font-size:10px;color:#343a40;">
                                                        <i class="far fa-2x fa-check-circle" style="font-size:10px;"></i>&nbsp;&nbsp;
                                                        Approve
                                                    </a>
                                                @endif
                                                @endif
                                            @endif                                                                                                     
                                        </div>                                                                                                
                                    @else
                                    <button type="button" class="btn btn-alt-primary"  style="font-size:10px;">
                                                <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer"></i>
                                              </button>  
                                    @endif
                                   </div>                          
                                </td>                               
                            @endif            
                            </tr>
                            <tr>
                                <td  class="text-left col-form-label" style="font-size:13px;background:#ededed;font-family:system-ui;">Audit Checklist </td>
                                <td style="font-size:13px;text-transform:capitalize;" colspan="2">
                                  @if(in_array(Auth::user()->email,$auditors) && $checklist->delete_status!='yes')
                                    <a href="{{route('checklistnewrecord',['id'=>$checklist->id])}}" class="button btn btn-sm btn-secondary  font-weight-normal" style="font-size:12px;system-ui;">Create New Record</a> 
                                  @else 
                                    <button type="button" class="button btn btn-sm btn-alt-secondary  font-weight-normal text-white" style="font-size:12px;font-family:system-ui;">Create New Record</button> 
                                  @endif    
                                </td>                        
                            </tr>
                            </table></td></tr> 

                            <tr><td colspan="6"  style="font-size:15px;height:5px;background:#c1c1c1;"></td></tr>

                        <tr><td colspan="6">
                          <table width="100%" class="table-responsive">
                           <thead>
                             <tr style="background:#ededed;">
                                <!-- <td class="text-center" style="font-size:14px;width:0.5%"></td> -->
                                <td class="text-center" style="font-size:14px;width:0.5%">#</td>
                                <td class="text-center" style="font-size:14px;width: 15%">Control Area</td>
                                <td class="text-center" style="font-size:14px;width: 10%">Purpose </td>
                                <td class="text-center" style="font-size:14px;width: 10%">Items to be Verified</td>
                                <td class="text-center" style="font-size:14px;width: 2%">Action</td>
                            </tr>
                           </thead>

                          <tbody id="tablecontents">
                            <?php
                             $sno = 1;          
                            ?>
                         @foreach($titles as $title)
                            <tr>
                                <td colspan="6" class="font-weight-bold" style="font-size:14px;height:5px;font-family:Arial, Helvetica, sans-serif;">
                                  <span style="margin-left:10px;margin-right:10px;">Section {{$sno++}} : {{ucfirst($title->title)}}</span>
                                  @if(in_array(Auth::user()->email,$auditors) && $checklist->delete_status!='yes')
                                  <a href="{{route('editchecklisttitle',['id'=>$title->id ])}}"  class="btn btn-sm btn-alt-primary" style="font-size:10px;"  title="Edit" data-toggle="tooltip"><i class="fa fa-fw fa-pencil-alt"></i></a>
                                  <a href="{{route('deletechecklisttitle',['id'=>$title->id ])}}"  onclick="return confirmdeletetitle()" class="btn btn-sm btn-alt-danger" style="font-size:10px;" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>
                                  @endif
                               </td>
                            </tr>
                       
                                <?php
                                    $mainnum = $sno - 1;
                                    $no = 1;
                                    $allpoints = DB::table('audit_checklist_points')->where('checklist_title_id',$title->id)->get();
                                ?>
                        @foreach($allpoints as $point)
                           <?php
                              $pointsvalue  = str_ireplace($serchval,"<span class='badge badge-warning'>$serchval</span>",$point->points);
                              $purposevalue = str_ireplace($serchval,"<span class='badge badge-warning'>$serchval</span>",$point->purpose);
                              $itemsvalue = str_ireplace($serchval,"<span class='badge badge-warning'>$serchval</span>",$point->items_verified);
                           ?>
                                <tr key={key}>
                                    <!-- <td class="text-center" style="font-size:14px;width:0.5%"><input  style="border-radius:50%;color:grey;" type="checkbox"  id="{{$point->id}}" name="id[]"></td> -->
                                    <td class="innercontent font-weight-normal">{{$mainnum}}.{{$no++}}</td>
                                    <td class="font-weight-normal innercontent">
                                       <span style="vertical-align: top;padding-left:0px;"><?php echo nl2br ($pointsvalue)?></span>
                                    </td>
                                    <td class="innercontent font-weight-normal"><?php echo nl2br ($purposevalue)?></td>
                                    <td class="innercontent font-weight-normal"><?php echo nl2br ($itemsvalue)?></td>
                                    <td class="font-w600 " class="text-center" style="font-size:10px;" align="center"> 
                                        <div class="btn-group m-auto d-flex justify-content-center">
                                        @if(in_array(Auth::user()->email,$auditors) && $checklist->delete_status!='yes')
                                            <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" data-placement="top" title="Actions" id="drop">
                                             <span data-toggle="tooltip" title="Click here to add purpose,items to be verified,edit or delete">
                                                <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer"></i>
                                             </span>
                                            </a>
                                         @else                      
                                              <button type="button" class="btn btn-alt-primary text-white"  style="font-size:10px;">
                                                <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer"></i>
                                              </button>  
                                        @endif             
                                            
                                            
                                            <div class="dropdown-menu " aria-labelledby="dropdown-dropleft-outline-dark">
                                                 @if($point->purpose==NULL)
                                                  <a href="{{ route('checklistpointpurpose',['id'=>$point->id ]) }}" class="dropdown-item font-weight-normal" style="font-size:10px;font-family:Arial, Helvetica, sans-serif;">
                                                     <i class="fa fa-tasks" style="font-size:10px;"></i>&nbsp;&nbsp;Add Purpose
                                                   </a> 
                                                 @endif
                                                 @if($point->items_verified==NULL)
                                                   <a href="{{ route('checklistpointitemsverified',['id'=>$point->id ]) }}" class="dropdown-item font-weight-normal" style="font-size:10px;font-family:Arial, Helvetica, sans-serif;">
                                                   <i class="fa fa-2x fa-money-check" style="font-size:10px;"></i>&nbsp;&nbsp;Add Items to be Verified
                                                    </a>
                                                 @endif
                                                 <a href="{{ route('checklistpointsedit',['id'=>$point->id ]) }}" class="dropdown-item font-weight-normal" style="font-size:10px;font-family:Arial, Helvetica, sans-serif;">
                                                    <i class="far fa-2x fa-edit" style="font-size:10px;"></i>&nbsp;&nbsp;Edit
                                                  </a> 
                                                 <a href="{{ route('checklistpointsdelete',['id'=>$point->id ]) }}" class="dropdown-item font-weight-normal" style="font-size:10px;font-family:Arial, Helvetica, sans-serif;" onclick="return confirmdelete('{{$point->id}}')">
                                                   <i class="far fa-window-close" style="font-size:10px;"></i>&nbsp;&nbsp;Delete
                                                </a>                                       
                                            </div>
                                        </div>
                                        </td>
                                </tr>                             
                        @endforeach
                        @endforeach
                    @else
                        <tr>
                          <td colspan="6" class="font-weight-normal text-danger text-center" style="font-size:14px;font-family:Arial, Helvetica, sans-serif;">
                             No data 
                          </td>
                        </tr>                              
                    @endif
                    </tbody>
                            </table>
                            </td></tr>  
                    </table>
                </div>
             </div>
            </div>
        </div>
    </div>

<!-- add title starts-->
    <div class="modal fade" id="titleModal" tabindex="-1" role="dialog" aria-labelledby="titleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="titleModalLabel">Add Title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success" id="successBtntitle" style="display:none">
                        Title Successfully saved
                    </div>

                    <ul class="alert alert-danger" id="dangerchecklisttitle" style="display:none">
                      Internal error ! Tile not added
                    </ul>
                    <div class="form-group">
                        <label for="title" class="col-form-label" style="font-size:12px;"> Title <span style="color:red">*</span></label>
                        <input type="text" class="form-control" style="font-size:12px;" name="title_value" id="title_value" value="" required>
                    </div>                   
                </div>               
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                    <button type="button" class="btn btn-primary" style="font-size:12px" onclick="saveTitle('{{$checklist->id}}')">Submit</button>
                </div>
            </div>
        </div>
    </div>
<!-- add title  ends-->
<!-- edit checklist name starts-->
<div class="modal fade" id="editchecklistnameModal" tabindex="-1" role="dialog" aria-labelledby="editchecklistnameModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editchecklistnameModalLabel">Edit Checklist Name</h5>
                    <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button> -->
                </div>
                <div class="modal-body">

                    <div class="alert alert-success" id="successBtnchecklistnameedit" style="display:none">
                        Checklist Name Updated Successfully
                    </div>

                    <ul class="alert alert-danger" id="dangerchecklistnotupdated" style="display:none">
                      Internal error ! Checklist not updated
                    </ul>

                    <ul class="alert alert-warning" id="checklistalreadyexists" style="display:none">
                    </ul>

                    @if($departmentname!=null)
                    <div class="form-group">
                        <label for="department" class="col-form-label" style="font-size:12px;"> Department </label>
                        <input type="text" class="form-control" style="font-size:12px;" name="department_value" id="department_value" value="{{ucfirst($departmentname)}}" readonly>
                        <input type="hidden" name="main_controller" id="main_controller" value="{{$checklist->department_id}}"/>
                        <input type="hidden" name="main_controller_name" id="main_controller_name" value="department"/>
                    </div>    
                    @elseif($divisionname!=null)
                    <div class="form-group">
                        <label for="department" class="col-form-label" style="font-size:12px;"> Division </label>
                        <input type="text" class="form-control" style="font-size:12px;" name="division_value" id="division_value" value="{{ucfirst($divisionname)}}" readonly>
                        <input type="hidden" name="main_controller" id="main_controller" value="{{$checklist->division_id}}"/>
                        <input type="hidden" name="main_controller_name" id="main_controller_name" value="division"/>
                    </div>
                    @elseif($areaname!=null)
                    <div class="form-group">
                        <label for="department" class="col-form-label" style="font-size:12px;"> Subdivision </label>
                        <input type="text" class="form-control" style="font-size:12px;" name="area_value" id="area_value" value="{{ucfirst($areaname)}}" readonly>
                        <input type="hidden" name="main_controller" id="main_controller" value="{{$checklist->area_id}}"/>
                        <input type="hidden" name="main_controller_name" id="main_controller_name" value="area"/>
                    </div>
                    @endif

                    <div class="form-group">
                        <label for="checklistname" class="col-form-label" style="font-size:12px;"> Checklist Name <span style="color:red">*</span></label>
                        <input type="text" class="form-control" style="font-size:12px;" name="checklist_name_value" id="checklist_name_value" value="{{$checklist->checklist_name}}" required>
                    </div>                   
                </div>              
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px" onclick="clearValues('{{$checklist->checklist_name}}')">Close</button>
                    <button type="button" class="btn btn-primary" style="font-size:12px" onclick="updatechecklistname('{{$checklist->id}}')">Update</button>
                </div>

            </div>
        </div>
    </div>

<!-- edit checklist name  ends-->

{{----------- button for add title ,edit checklist name------------}}

<script>
  /**------edit checklist name--------- */
    function updatechecklistname(id) {
         let mainControllerid = $('#main_controller').val();
         let mainControllerName = $('#main_controller_name').val();
        $.ajax({
            data: {
                "checklist_name_value": $('#checklist_name_value').val()
                ,"checklisteditid":id
                ,"checklist_controller_id_value": mainControllerid
                ,"checklist_controller_value": mainControllerName
                , _token: CSRF_TOKEN
            }          
            , url: "{{ route('editchecklistname')}}"
            , type: "POST"
            , success: function(response) {
                console.log(response.status);
                if(response.status == 200){
                $('#checklistalreadyexists').hide();
                $('#dangerchecklistnotupdated').hide();
                $('#successBtnchecklistnameedit').css('display', 'block');
                setTimeout(function() {
                    window.location.reload();
                }, 1000);
            }
            else if(response.status === 403){
                  var checklistalreadyexists = response.chkerrmsg;  
                  $('#successBtnchecklistnameedit').hide();
                  $('#dangerchecklistnotupdated').hide(); 
                  $('#checklistalreadyexists').html(checklistalreadyexists); 
                  $('#checklistalreadyexists').css('display', 'block');             
                }              
            } ,         
            error: function(err) { 
                if(err.status === 500) {
                    $('#successBtnchecklistnameedit').hide();
                    $('#checklistalreadyexists').hide();
                    $('#dangerchecklistnotupdated').css('display', 'block');
                } 
            }       
        });
    }

    function clearValues(checklistnamevalue){
        $('#successBtnchecklistnameedit').hide();
        $('#checklistalreadyexists').hide();
        $('#dangerchecklistnotupdated').hide();
        $('#checklist_name_value').val('');
        $('#checklist_name_value').val(checklistnamevalue);
    }

   /**------add title--------- */
    function saveTitle(id) {
         console.log($('#title_value').val());
        // console.log(data.responseJSON.message);
        $.ajax({
            data: {
                "title_value": $('#title_value').val()
                ,"checkid":id
                , _token: CSRF_TOKEN
            }          
            , url: "{{ route('addchecklisttitle')}}"
            , type: "POST"
            , success: function(response) {
                if(response.status == 200){
                    $('#successBtntitle').css('display', 'block');
                    setTimeout(function() {
                    window.location.reload();
                   }, 1000);
                }
                else if(response.status == 500){
                    $('#dangerchecklisttitle').css('display', 'block');                
                }
            },  
        });
    }

      /**------confirm delete points--------- */
        function confirmdelete(){      
            mess1= "Are you sure?";
            x=confirm(mess1);
            if(x==true){
                mess2='Points will be deleted permanently,Press OK to continue.';
                y=confirm(mess2);
                if(y==true){}
                else{
                    return false;
                }}
            else{
                return false;
            }              
        }
    
      /**------confirm delete title--------- */  
        function confirmdeletetitle(){      
            mess1= "Are you sure?";
            x=confirm(mess1);
            if(x==true){
                mess2='Title and all its points  will be deleted permanently,Press OK to continue.';
                y=confirm(mess2);
                if(y==true){}
                else{
                    return false;
                }}
            else{
                return false;
            }             
        }

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






