@extends('pages.datatables')

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
                                            <a class="link-fx" href="">View Drill Reports</a>
                                        </li>
                                        <!-- <li class="breadcrumb-item" aria-current="page">
                                            <a class="link-fx" href="{{ route('emergency.register') }}">Back</a>
                                        </li> -->
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
                                <!-- <th style="font-size:12px;text-transform:capitalize;width:2%;position:sticky;top:-1;"></th> -->
                                <th class="text-center" style="font-size:12px;text-transform:capitalize;width:2%;position:sticky;top:-1;">S.No</th>
                                <th class="text-center" style="font-size:12px;text-transform:capitalize;width:5%;position:sticky;top:-1">Type of Drill</th> 
                                <th class="text-center" style="font-size:12px;text-transform:capitalize;width:5%;position:sticky;top:-1;">Report Number</th>
                                <th class="text-center" style="font-size:12px;text-transform:capitalize;width: 5%;position:sticky;top:-1">Date of Drill</th>
                                
                                <th class="text-center" style="font-size:12px;text-transform:capitalize;width:10%;position:sticky;top:-1">Time Drill Conducted</th>
                                <th class="text-center" style="font-size:12px;text-transform:capitalize;width:15%;position:sticky;top:-1">Drill Team</th>
                                <th class="text-center" style="font-size:12px;text-transform:capitalize;width:5%;position:sticky;top:-1">Actions</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                           <?php $no = 1; ?>
                            @foreach($datas as $data)
                              <?php 
                                 $drilldate = date('d-m-Y',strtotime($data->drill_date));
                              ?>
                            <tr>
                                <!-- <td><input class="checkbox" type="checkbox" id="{{$data->id}}" name="id[]" ></td> -->
                                <td class="text-center" style="font-size:10px;">{{$no}}</td>
                                <td class="text-center"  style="font-size:12px;">
                                    {{$data->drill_type}}
                                </td>
                                <td class="text-center" style="font-size:12px;"><a href="{{route('emergency.viewdetailedreport',['id'=>$data->id])}}">{{$data->report_number}}</a></td>
                                <td class="text-center"  style="font-size:12px;"> {{$drilldate}} </td>                           
                                
                                <td class="text-center"  style="font-size:12px;">
                                  
                                    {{$data->time_drill_conducted}}
                                 
                                </td>
                                <td class="text-center"  style="font-size:12px;">
                                  @if($data->drill_team_other!=NULL)
                                  {{$data->drill_team_other}}
                                  @else
                                   <?php
                                     $teams = explode(',',$data->drill_team);
                                     foreach($teams as $team)
                                     {
                                        $name = DB::table('employee_details')->where('id',$team)->value('emp_name');
                                    ?> 
                                    {{ucfirst($name)}},
                                    <?php
                                     }
                                   ?>
                                  @endif                  
                                </td>                                                  
                                <?php $no++; ?>
                                <td style="font-size:10px" class="text-left">         
                                   <div class="btn-group m-auto d-flex justify-content-center">
                                        <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" data-placement="top" title="Actions" id="drop{{$data->id}}">
                                            <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer"></i>
                                        </a>                               
                                            <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownMenu{{ $data->id }}">                               
                                                <a href="{{route('emergency.addcorrectivedrillreport',['id'=>$data->id])}}" class="dropdown-item" style="font-size:10px;color:#343a40;">
                                                    <i class="fa fa-tasks" style="font-size:10px;"></i>&nbsp;&nbsp;Add Corrective Actions
                                                </a>
                                                
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
    </div>
    <!-- END Dynamic Table Full -->
    </div>

   <script>

        function autoResizeDiv(height) {
            document.getElementById('content').style.height = (height) + 'px';
        }
        window.onresize = autoResizeDiv;
        autoResizeDiv(window.innerHeight - 100);

    </script>


@include('user.emergencyresponse.header_options') 
    
@endsection