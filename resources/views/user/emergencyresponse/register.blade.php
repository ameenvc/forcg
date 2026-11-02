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
    <a type="button" href="{{route('emergency.addemergencyresponse')}}" class="btn btn-sm btn-dual" style="margin-left:8px;">
        <i class="fa fa-1x fa-plus" style="color:grey;"></i>
    </a>
    <!-- <a href="#" class="button btn btn-sm btn-dual" style="margin-left:8px;" data-toggle="modal"  data-target="#addreviewModal">
        <i class="fa fa-1x fa-plus" style="color:grey;"></i>
    </a> -->
</div>
@endsection
@section('content')

    <style>
        body {
            overflow-y: auto;
        }
   
</style>

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
                                        <li class="breadcrumb-item" style="margin-left:-590px;margin-top:-8px">Emergency Response</li>
                                        <li class="breadcrumb-item" aria-current="page" style="margin-top:-8px">
                                            <a class="link-fx" href="">Register</a>
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
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width:5%;position:sticky;top:-1;">Emergency Situation</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 5%;position:sticky;top:-1">Department</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width:15%;position:sticky;top:-1">Location</th> 
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width:10%;position:sticky;top:-1">Frequency</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 10%;position:sticky;top:-1">Date of Last drill</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 10%;position:sticky;top:-1">Next drill date</th>
                                 <!-- <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 10%;position:sticky;top:-1">Due date</th> -->
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 10%;position:sticky;top:-1">Status</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width:5%;position:sticky;top:-1">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                           <?php $no = 1; ?>
                            @foreach($datas as $data)
                              <?php 
                                 $duedate = date('d-m-Y',strtotime($data->duedate));
                                 $neartoexpiry=date('Y-m-d',strtotime($duedate. ' - 30 days'));
                                 $today=date("Y-m-d");
                              ?>
                            <tr>
                                <td><input class="checkbox" type="checkbox" id="{{$data->id}}" name="id[]" ></td>
                                <td class="text-center" style="font-size:10px;">{{$no}}</td>
                                <td class="text-center" style="font-size:10px;">{{$data->emergency_situation_name}} </td>
                                <td>
                                    <em class="text-mute" style="font-size:10px;">{{$data->departmentdetails ? $data->departmentdetails->departmentname:'-'}}</em>
                                </td>
                                <td>
                                  <em class="text-mute" style="font-size:10px;">
                                    {{$data->locationdetails ? $data->locationdetails->name:'-'}}
                                  </em> 
                                </td>
                                <td>
                                    <em class="text-mute" style="font-size:10px;">
                                     {{$data->frequency}}
                                    </em>
                                </td>
                                <td class="text-center" style="font-size:13px;">
                                  <em class="text-mute" style="font-size:10px;">{{date('d-m-Y',strtotime($data->drill_date_last))}}</em>
                                </td>
                                <td class="text-center" style="font-size:13px;">
                                  <em class="text-mute" style="font-size:10px;">{{date('d-m-Y',strtotime($data->drill_date_next))}}</em>
                                </td>
                                <!-- <td class="text-center" style="font-size:13px;">
                                  <em class="text-mute" style="font-size:10px;">{{date('d-m-Y',strtotime($data->duedate))}}</em>
                                </td> -->
                                <td class="text-center" style="font-size:13px;">
                                    @if($data->duedate<$today || $data->status=="Due")
                                    <?php
                                        echo App\Http\Controllers\EmergencyResponseController::statusdue($data->duedate);
                                    ?>
                                    <span class="badge badge-danger">Due</span>
                                    @elseif($neartoexpiry<=$today || $data->status=="Near to Due")
                                    <?php
                                        echo App\Http\Controllers\EmergencyResponseController::statusneardue($data->duedate);?>
                                        <span class="badge badge-warning" style="font-size:10px">Near to Due</span>
                                    @elseif($data->duedate>$today || $data->status=="Scheduled")
                                    <?php
                                    echo App\Http\Controllers\EmergencyResponseController::statusscheduled($data->duedate);?>
                                        <span class="badge badge-info" style="font-size:10px">Scheduled</span>
                                    @endif
                                </td>
                                                              
                                <td style="font-size:10px" class="text-left">         
                                   <div class="btn-group m-auto d-flex justify-content-center">
                                        <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" data-placement="top" title="Actions" id="drop{{$data->id}}">
                                            <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer"></i>
                                        </a>                               
                                            <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownMenu{{ $data->id }}">                               
                                                <a href="{{route('emergency.createdrillreport',['id'=>$data->id,'serialnum'=>$no])}}" class="dropdown-item" style="font-size:10px;color:#343a40;">
                                                    <i class="fa fa-tasks" style="font-size:10px;"></i>&nbsp;&nbsp;Create Drill Report
                                                </a>
                                                <?php
                                                  $reportdatas = DB::table('emergency_drill_report_details')->where('emergency_details_id',$data->id)->get();
                                                ?>
                                                @if(count($reportdatas)>0)
                                                <a href="{{route('emergency.viewdrillreport',['id'=>$data->id])}}" class="dropdown-item" style="font-size:10px;color:#343a40;">
                                                    <i class="far fa-2x fa-file-alt" style="font-size:12px;"></i>&nbsp;&nbsp;View Drill Report
                                                </a>
                                                @endif  
                                                      
                                            </div>
                                        
                                        <a href="#" class="btn btn-sm btn-alt-primary" data-id="{{ $data->id }}" data-toggle="modal" data-placement="top" title="View" data-target="#viewModal{{$data->id}}">
                                            <i class="far fa-eye text-center " style="font-size:10px;cursor:pointer" ></i>
                                        </a>
                                    </div> 

                                    <?php $no++; ?>

                                    <div id="viewModal{{$data->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h2 class="modal-title">Detailed View</h2>
                                                    </div>
                                                   
                                                        <table class="table table-striped" style="font-size:13px;">                        
                                                            <tr>
                                                                <th style="width:70px;">Emergency Situation</th>
                                                                <td style="text-transform:capitalize">{{$data->emergency_situation_name}}</td>
                                                            </tr>

                                                            <tr>
                                                                <th style="width:70px;">Description</th>
                                                                <td style="text-transform:capitalize">{{$data->emergency_situation_desp}}</td>
                                                            </tr>

                                                            <tr>
                                                                <th>Department</th>
                                                                <td style="text-transform:capitalize">{{$data->departmentdetails ? $data->departmentdetails->departmentname:'-'}}</td>
                                                            </tr>

                                                            <tr>
                                                              <th>Location</th>
                                                              <td style="text-transform:capitalize">
                                                                {{$data->locationdetails ? $data->locationdetails->name:'-'}}
                                                             </td>
                                                            </tr>

                                                            <tr>
                                                                <th>Frequency</th>
                                                                <td style="text-transform:capitalize">
                                                                  {{$data->frequency}} 
                                                                </td>
                                                            </tr>                                                           
                                                            
                                                            <tr>
                                                                <th>Drill Team</th>
                                                                <td style="text-transform:capitalize">
                                                                 <?php
                                                                   $drilldatas = DB::table('emergency_response_drill_team_details')
                                                                                                        ->where('emergency_response_details_id',$data->id)
                                                                                                        ->get();
                                                                 ?>
                                                                 
                                                                 @foreach($drilldatas as $drilldata)
                                                                   <?php
                                                                     $name = DB::table('employee_details')->where('id',$drilldata->drill_team_id)->value('emp_name');
                                                                   ?>
                                                                  {{$name}},
                                                                 @endforeach
                                                                  </ul>
                                                                </td>
                                                            </tr>       

                                                            <tr>
                                                                <th style="vertical-align: top;">Date of Last drill</th>
                                                                <td style="text-transform:capitalize">
                                                                   {{date('d-m-Y',strtotime($data->drill_date_last))}}                                      
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <th>Next drill date</th>
                                                                <td style="text-transform:capitalize">
                                                                {{date('d-m-Y',strtotime($data->drill_date_next))}}
                                                               </td>
                                                            </tr>                                                  

                                                            <tr>
                                                                <th>Status</th>
                                                                <td style="text-transform:capitalize">
                                                                   @if($data->duedate<$today || $data->status=="Due")                                      
                                                                     <span class="badge badge-danger">Due</span>
                                                                    @elseif($neartoexpiry<=$today || $data->status=="Near to Due")                         
                                                                     <span class="badge badge-warning" style="font-size:10px">Near to Due</span>
                                                                    @elseif($data->duedate>$today || $data->status=="Scheduled")                                                 
                                                                      <span class="badge badge-info" style="font-size:10px">Scheduled</span>
                                                                    @endif
                                                               </td>
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
  <!-- @section('sideitem')
    
    @endsection -->
    <script>
        function autoResizeDiv(height) {
            document.getElementById('content').style.height = (height) + 'px';
        }
        window.onresize = autoResizeDiv;
        autoResizeDiv(window.innerHeight - 100);

    </script>


@include('user.emergencyresponse.header_options') 
    
@endsection