@extends('pages.datatables')

@section('exportadd')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />

<div class="input-group input-group-sm">
    <a href="" class="btn btn-sm btn-dual" style="margin-left:0px;" onclick="return fnCovertTable('print')">

        <i class="fa fa-print  fa-1x" style="color:grey"></i>

    </a>
</div>
<div class="input-group input-group-sm">
    <button type="button" class=" btn-sm btn-dual btn btn-default dropdown-toggle mr-0 mb-0" style="margin-left:8px; " data-toggle="dropdown">
        <i class="fa fa-file-export fa-1x" style="color:grey;"></i>
    </button>
    <ul class="dropdown-menu" role="menu" style="min-width:10px;text-align:center;">
        <li>
            <!--change route as below-->
            <form method="post" action="{{url('/user/convert/file/'.'tvalue1')}}" style="margin-bottom: 0px">
                @csrf
                <button onclick="return fnCovertTable('pdf')" style="width:60px;  font-size:10px;border:none;background-color:white;">Pdf</i></button>
                <!--change hidden input type id as below-->
                <input type="hidden" id="tvalue1" name="tvalue1" />
            </form>
        </li>
        <li><button onclick="fnCovertTable('excel')" style="width:60px;  font-size:10px;border:none;background-color:white;">Excel</button></li>
        <li><button onclick="fnCovertTable('word')" style="width:60px;  font-size:10px;border:none;background-color:white;">Word</button></li>
    </ul>
</div>

<div class="input-group input-group-sm">
    <a type="button" href="{{route('management.addmanagementreview')}}" class="btn btn-sm btn-dual" style="margin-left:8px;">
        <i class="fa fa-1x fa-plus" style="color:grey;"></i>
    </a>
    <!-- <a href="#" class="button btn btn-sm btn-dual" style="margin-left:8px;" data-toggle="modal"  data-target="#addreviewModal">
        <i class="fa fa-1x fa-plus" style="color:grey;"></i>
    </a> -->
</div>
@endsection
@section('content')
<!doctype html>
<html lang="en">
<head>  
    <style>
        body {
            overflow-y: auto;
        }
   
</style>
</head>
<body>

    <input type="hidden" value="a1" id="tabselected">
    <div class="content" style="width:100%">
        <div class="block block-rounded" style="margin-left:-1%;">
            <div class="block-content block-content-full">
                <div class="scrollable" style="height:450px;overflow-y:scroll;" id="content">
                    <table class="move" width="15%" style="margin-left:670px">
                        <tr>
                            <th>
                                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                    <ol class="breadcrumb breadcrumb-alt" style="margin-right:-200px;margin-bottom:-250px">
                                        <li class="breadcrumb-item" style="margin-left:-590px;margin-top:-8px">Management Review</li>
                                        <li class="breadcrumb-item" aria-current="page" style="margin-top:-8px">
                                            <a class="link-fx" href="">Meetings</a>
                                        </li>
                                    </ol>
                                </nav>
                            </th>
                        </tr>
                    </table>
                    @if(Session::has('message'))
                            <div class="alert alert-success">
                                <a class="close" data-dismiss="alert">×</a>
                                {!!Session::get('message')!!}
                            </div>
                        @endif

                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full" id="tabledata">
                        <thead>
                            <tr>
                                <th style="font-size:10px;text-transform:capitalize;width:2%;position:sticky;top:-1;"></th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 5%;position:sticky;top:-1;">S.No</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width:5%;position:sticky;top:-1;">Date</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 5%;position:sticky;top:-1">Time</th>
                                <!-- <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 20%;position:sticky;top:-1">Agenda Required</th>-->
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width:15%;position:sticky;top:-1">Location</th> 
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width:20%;position:sticky;top:-1">Participation from management</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 10%;position:sticky;top:-1">Status</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width:5%;position:sticky;top:-1">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                           <?php $no = 0; ?>
                            @foreach($datas as $data)
                              <?php 
                                 $meetingdate = date('d-m-Y',strtotime($data->date));
                                 $currentmeetingdate = date('Y-m-d',strtotime($data->date));
                              ?>
                            <tr>
                                <td><input class="checkbox" type="checkbox" id="{{$data->id}}" name="id[]" ></td>
                                <td class="text-center" style="font-size:10px;">{{$no=$no+1}}</td>
                                <td class="text-center" style="font-size:10px;">{{date('d-m-Y',strtotime($data->date))}} </td>
                                <td>
                                    <em class="text-mute" style="font-size:10px;">{{$data->time}}</em>
                                </td>
                                <td>
                                  <em class="text-mute" style="font-size:10px;">
                                    @if(is_numeric($data->location))
                                        {{ $data->managementLocation ? $data->managementLocation->name: '' }}
                                    @else
                                        {{ $data->location }}
                                    @endif
                                  </em> 
                                </td>
                                <td>
                                    <em class="text-mute" style="font-size:10px;">
                                     @if($data->management_participants=='other')
                                     {{$data->management_participants_other}}
                                     @else
                                        <?php
                                            $manageuserdatas = DB::table('management_review_schedules_management_participants')
                                                                                                    ->where('schedule_id',$data->id)
                                                                                                    ->get(); 
                                            $manageparticipant_arr = array();                                                                                                                                               
                                            ?>
                                        @foreach($manageuserdatas as $manageuserdata) 
                                            <?php
                                                $user_name = DB::table('users')->where('id',$manageuserdata->manage_participant_id)->value('name');
                                                array_push($manageparticipant_arr,$user_name);
                                                $manageparticipants = implode(',',$manageparticipant_arr);
                                            ?>
                                        @endforeach
                                        {{ucfirst($manageparticipants)}}
                                    @endif
                                    </em>
                                </td>
                                <td class="text-center" style="font-size:13px;">
                                   <span class="{{$data->status_class}}">{{$data->status}}</span>
                                </td>

                                <td style="font-size:10px" class="text-left">         
                                   <div class="btn-group m-auto d-flex justify-content-center">
                                        <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" data-placement="top" title="Actions" id="drop{{$data->id}}">
                                            <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer"></i>
                                        </a>
                                        @if(Gate::allows('management-review-meeting-controls'))    
                                            <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownMenu{{ $data->id }}"> 
                                             @if($data->status=='Scheduled' || $data->status=='Completed')
                                                <a href="{{route('management.recordminutes',$data->id)}}" class="dropdown-item" style="font-size:10px;color:#343a40;">
                                                    <i class="fa fa-record-vinyl" style="font-size:10px;"></i>&nbsp;&nbsp;Record Minutes
                                                </a>

                                                <a href="{{route('management.addcorrective',$data->id)}}" class="dropdown-item" style="font-size:10px;">
                                                   <i class="fa fa-tasks" style="font-size:10px;"></i>&nbsp;&nbsp;Add Action Plans
                                                </a>
                                                @if($data->status!='Completed')
                                                <a href="#" class="dropdown-item" style="font-size:10px;" data-toggle="modal"  data-target="#changescheduleModal" onclick="passdetails('{{$data->id}},{{$currentmeetingdate}},{{$data->time}},{{$meetingdate}}')">
                                                   <i class="fa fa-calendar-alt" style="font-size:10px;"></i>&nbsp;&nbsp;Change Schedule
                                                </a>
                                                @endif
                                                @if($data->status!='Completed')
                                                @if($data->meeting_cancel_reason==NULL)
                                                    <a href="#" class="dropdown-item" style="font-size:10px;" data-toggle="modal"  data-target="#cancelmeetingModal" onclick="passidvalue('{{$data->id}},{{$meetingdate}}')">
                                                    <i class="far fa-window-close" style="font-size:10px;"></i>&nbsp;&nbsp;Cancel the Meeting
                                                    </a>
                                                @endif
                                                @endif
                                                @if($data->status!='Completed')
                                                <a href="{{route('management.meetingcompleted',$data->id)}}" class="dropdown-item" style="font-size:10px;" onclick="return confirmclose()">
                                                  <i class="far fa-2x fa-check-square" style="font-size:12px;"></i>&nbsp;&nbsp;Update as Completed                                
                                                </a>
                                                @endif
                                             @endif

                                            </div>
                                        @endif
                                        <a href="#" class="btn btn-sm btn-alt-primary" data-id="{{ $data->id }}" data-toggle="modal" data-placement="top" title="View" data-target="#viewModal{{$data->id}}">
                                            <i class="far fa-eye text-center " style="font-size:10px;cursor:pointer" ></i>
                                        </a>
                                    </div>                      

                                    <div id="viewModal{{$data->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h2 class="modal-title">Detailed View</h2>
                                                    </div>
                                                   
                                                        <table class="table table-striped" style="font-size:13px;">                        
                                                            <tr>
                                                                <th style="width:70px;">Date</th>
                                                                <td style="text-transform:capitalize">{{ date('d-m-Y',strtotime($data->date))}}</td>
                                                            </tr>
                                                            
                                                            <tr>
                                                                <th>Time</th>
                                                                <td style="text-transform:capitalize">{{ $data->time }}</td>
                                                            </tr>

                                                            <tr>
                                                              <th>Location</th>
                                                              <td style="text-transform:capitalize">
                                                                @if(is_numeric($data->location))
                                                                    {{ $data->managementLocation ? $data->managementLocation->name:'' }}
                                                                @else
                                                                {{ $data->location }}
                                                                @endif
                                                             </td>
                                                            </tr>

                                                            <tr>
                                                                <th>Agenda Type</th>
                                                                <td style="text-transform:capitalize">
                                                                     <?php
                                                                       $types = explode(',',$data->agenda_type);
                                                                       $types_arr= array();
                                                                     ?>
                                                                     @foreach($types as $type)
                                                                      <?php
                                                                         $typename = DB::table('management_agenda_types')
                                                                                                                      ->where('id',$type)->value('name');
                                                                         array_push($types_arr,$typename);
                                                                         $typedisplay = implode(', ',$types_arr);
                                                                       ?>
                                                                     @endforeach
                                                                    {{$typedisplay}}
                                                                </td>
                                                            </tr>
                                                            
                                                            <tr>
                                                                <th style="vertical-align: top;">Agenda</th>
                                                                <td style="text-transform:capitalize">
                                                                    <?php
                                                                         $points = DB::table('management_agenda_points')
                                                                                                                      ->where('id',$data->agenda_points)->value('points');
                                                                       
                                                                       
                                                                         $point_arrays = explode("\n",$points);
                                                                    ?>
                                                                    <ol>
                                                                        @foreach($point_arrays as $point_array)
                                                                         <li style="margin-bottom:5px;position: relative;left: -12px;">{{$point_array}}</li>
                                                                        @endforeach
                                                                    </ol>
                                                                </td>
                                                            </tr>


                                                            <tr>
                                                                <th>Participants</th>
                                                                <td style="text-transform:capitalize">
                                                                    <?php
                                                                        $userdatas = DB::table('management_review_schedules_participants')
                                                                                                                                ->where('schedule_id',$data->id)
                                                                                                                                ->get(); 
                                                                        $participant_arr = array();                                                                                                                                               
                                                                    ?>
                                                                       @foreach($userdatas as $userdata) 
                                                                        <?php
                                                                            $user_name = DB::table('users')->where('id',$userdata->participants_id)->value('name');
                                                                            array_push($participant_arr,$user_name);
                                                                            $participants = implode(',',$participant_arr);
                                                                        ?>
                                                                       @endforeach
                                                                    {{ucfirst($participants)}}
                                                               </td>
                                                            </tr>

                                                            <tr>
                                                                <th>Participation from Management</th>
                                                                <td style="text-transform:capitalize">
                                                                @if($data->management_participants=='other')
                                                                   {{$data->management_participants_other}}
                                                                @else
                                                                    <?php
                                                                        $manageuserdatas = DB::table('management_review_schedules_management_participants')
                                                                                                                                ->where('schedule_id',$data->id)
                                                                                                                                ->get(); 
                                                                        $manageparticipant_arr = array();                                                                                                                                               
                                                                        ?>
                                                                        @foreach($manageuserdatas as $manageuserdata) 
                                                                            <?php
                                                                                $user_name = DB::table('users')->where('id',$manageuserdata->manage_participant_id)->value('name');
                                                                                array_push($manageparticipant_arr,$user_name);
                                                                                $manageparticipants = implode(',',$manageparticipant_arr);
                                                                            ?>
                                                                        @endforeach
                                                                    {{ucfirst($manageparticipants)}}
                                                                @endif
                                                               </td>
                                                            </tr>
                                                            
                                                            <tr>
                                                                <th>Corrective Action Number</th>
                                                                <td style="font-size:14px;">
                                                                  <?php
                                                                     $correctives = DB::table('corrective')->where('management_review_schedule_id',$data->id)->get();
                                                                  ?>
                                                                  @if(count($correctives)>0)
                                                                   @foreach($correctives as $corrective)
                                                                   <span class="badge bg-success-light">{{$corrective->issueid}}</span>
                                                                   @endforeach
                                                                 @else
                                                                  --
                                                                 @endif
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <th>Status</th>
                                                                <td style="text-transform:capitalize;font-size:13px;"><span class="{{$data->status_class}}">{{$data->status}}</span></td>
                                                            </tr>

                                                            <tr>
                                                                <th>Added by</th>
                                                                <td style="text-transform:capitalize">{{ $data->addedby ? $data->addedby->name:'' }}</td>
                                                            </tr>

                                                        </table>
                                                    
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                   </table>

                  
    </div>
    </div>
    </div>
    <!-- END Dynamic Table Full -->
    </div>

    <!-- cancel meeting starts-->
    <div class="modal fade" id="cancelmeetingModal" tabindex="-1" role="dialog" aria-labelledby="cancelmeetingLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="cancelmeetingLabel"></h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <form id="add_meet_cancel" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-success" id="successBtntitle" style="display:none">
                            Meeting Cancelled
                        </div>

                        <div class="alert alert-danger" id="dangerValidationtitle" style="display:none">
                          Email Sending blocked for Cancel Meeting
                        </div>

                        <div class="form-group">
                            <label for="title" class="col-form-label" style="font-size:12px;">Please Specify<span style="color:red">*</span></label>
                            <textarea class="form-control"  style="font-size: 13px;" id="meeting_cancel_reason"  name="meeting_cancel_reason" required></textarea>                                                       
                            <input type="hidden" name="review_id" id="review_id" value=""/>
                        </div>                  
                    </div>
                    
                
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                        <button type="submit" class="btn btn-primary" style="font-size:12px">Cancel Meeting</button>
                    </div>
                </form>
                </div>
            </div>
        </div>

<!-- cancel meeting ends-->

<!-- change schedule starts-->
<div class="modal fade" id="changescheduleModal" tabindex="-1" role="dialog" aria-labelledby="changescheduleLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id ="updatescheduleLabel"></h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <form id="update_schedule" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-success" id="successtitle" style="display:none">
                            Schedule Changed Successfully
                        </div>

                        <div class="alert alert-danger" id="dangerValidationtitle" style="display:none">
                          Some issues with sever. We will try to fix it soon
                        </div>

                        <div class="form-group">
                            <label for="title" class="col-form-label" style="font-size:12px;">Date of Management Review<span style="color:red">*</span></label>
                            <input type="date" style="font-size:13px;width:200px;" class="js-datepicker form-control" id="current_date" name="current_date" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy" value="" required>
                            
                        </div>     
                        
                        <div class="form-group">
                            <label for="title" class="col-form-label" style="font-size:12px;">Time<span style="color:red">*</span></label>
                            <input type="time" class="form-control" name="current_time"  id ="current_time" required style="font-size: 13px;width:200px;" placeholder="Time">
                            
                        </div>     

                    </div>
                    
                    <input type="hidden" name="schedule_id" id="schedule_id" value=""/>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                        <button type="submit" class="btn btn-primary" style="font-size:12px">Update</button>
                    </div>
                </form>
                </div>
            </div>
        </div>

<!-- change schedule ends-->
   
  @section('sideitem')
    @if(Gate::allows('management-review-meeting-delete') || Gate::allows('delete-all'))
    <div class="input-group input-group-sm ml-2">
        <button type="button" class="btn btn-sm btn-dual" id="del" value=id[]>
            <i class="fas fa-1x fa-trash" style="color:red;"></i>
        </button>
    </div>
    @endif
    @endsection
   
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            let years = "{{ $years }}".split(',');
            let year_text = "";
            for (var i = 0; i < years.length; i++) {
                year_text += `<option value="${years[i]}">${years[i]}</option>`;
            }

            $("#tabledata").one("preInit.dt", function() {
                let table = $('#tabledata').DataTable();
                $button = $(`<select class="form-control" style="font-size: 13px;display: inline-block;width:100px;" id="year_dropdown">
                <option value="0" selected>Year</option>` +
                    year_text +
                    `</select>`);

           $("#tabledata_filter label").prepend($button);
           $button.button();

           $("#year_dropdown").on('change', function() {
                //console.log($(this).val());
                    if ($(this).val()) {
                        table.columns(2).search($(this).val()).draw();
                    } else {
                        table.columns(2).search("").draw();
                    }
                });

            });


        });
    </script>

   <script>
      let CSRF_TOKEN = "";
        window.onload = function() {
            CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        }


    // function passidvalue(reviewid)
    // {
    //     document.getElementById('review_id').value=reviewid;
    //     return false;
    // } 
    
    function passidvalue(details)
    {
        var modules = details;
        var modules_array = modules.split(',');
        var heading = 'Reason for Cancelling the Meeting on';
        var heading_value = heading.concat(" ",modules_array[1]);
       
        document.getElementById('review_id').value=modules_array[0];
        document.getElementById('cancelmeetingLabel').innerHTML=heading_value;
        return false;
    }    
    
    $(document).on('submit','#add_meet_cancel',function(e){
      e.preventDefault();

        $.ajax({
                data: {
                    "cancel_reason_value" : $('#meeting_cancel_reason').val(),
                    "review_id_value" : $('#review_id').val(),
                     _token: CSRF_TOKEN
                }
                , url: "{{ route('cancelmeetingreason')}}"
                , type: "POST"
                , success: function(response) {
                    if(response.status == 200)
                    {
                        $('#successBtntitle').css('display', 'block');
                        setTimeout(function() 
                        {
                           window.location.reload();
                        }, 1000);
                       
                    } 
                    else if(response.status == 503)
                    {
                        $('#dangerValidationtitle').css('display', 'block');
                        setTimeout(function() 
                        {
                           window.location.reload();
                        }, 1000);
                    }                    
                }          
            });
    
    });
    
    </script>

    <script>
      function passdetails(details)
        {
            var modules = details;
            var modules_array = modules.split(',');      
            document.getElementById('schedule_id').value=modules_array[0];
            document.getElementById('current_date').value=modules_array[1];
            document.getElementById('current_time').value=modules_array[2];
            var heading = 'Change Schedule for';
            var heading_value = heading.concat(" ",modules_array[3]);
            document.getElementById('updatescheduleLabel').innerHTML=heading_value;
            return false;
        }   
        
        $(document).on('submit','#update_schedule',function(e){
        e.preventDefault();
        console.log($('#current_date').val());
        console.log($('#current_time').val());
        console.log($('#schedule_id').val());

            $.ajax({
                    data: {
                        "date_value" : $('#current_date').val(),
                        "time_value" : $('#current_time').val(),
                        "schedule_id_value" : $('#schedule_id').val(),
                        _token: CSRF_TOKEN
                    }
                    , url: "{{ route('management.updateschedule')}}"
                    , type: "POST"
                    , success: function(response) {
                        console.log(response.status);
                        console.log(response.data);
                        if(response.status == 200)
                        {
                            $('#successtitle').css('display', 'block');
                            location.reload(); 
                        
                        } 
                        else if(response.status == 503)
                        {
                            $('#dangerValidationtitle').css('display', 'block');
                            location.reload(); 
                        }                 
                    }          
                });
        
        });

    </script>

    <script>
        $("#del").click(function(e) {
            e.preventDefault();
            mess1 = "Are you sure?";
                x = confirm(mess1);
                if (x == true) {
                    mess2 = 'Records will be deleted permanently,Press OK to continue.';
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
                     console.log(dataArr);
                    $.ajax({
                        type: 'get'
                        , url: "{{route('management.deletereviewmeeting')}}" + "?val=" + $(this).attr('id')
                        , success: function(response) {
                            // window.location.href = "{{url('user/management/meetings')}}";
                            location.reload();              
                        }
                        
                    });
                });

            } else {
                alert('No Record Selected');
            }
        });



        function confirmclose()
        {                   
            mess1= "Are you sure?";
            x=confirm(mess1);
            if(x==true)
            {
                mess2='This review meeting will be closed,Press OK to continue.';
                y=confirm(mess2);
                if(y==true)
                {

                }
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
        }


        function autoResizeDiv(height) {
            document.getElementById('content').style.height = (height) + 'px';
        }
        window.onresize = autoResizeDiv;
        autoResizeDiv(window.innerHeight - 100);

    </script>


@include('user.managementreview.header_options') 
    


</body>

</html>
@endsection