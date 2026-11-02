@extends('pages.datatables')
@section('exportadd')

<div class="input-group input-group-sm">
    <a href="" class="btn btn-sm btn-dual" style="margin-left:0px;" onclick="return fnCovertTable('print')">
        <i class="fa fa-print  fa-1x" style="color:grey"></i>
    </a>
</div>
@endsection
@section('content')
    <!-- Page Content -->
    <div class="content" style="width:100%;">
        <!-- Dynamic Table Full -->
        <div class="block block-rounded">
            <div class="block-content block-content-full">
                <div style="overflow-x:hidden;overflow-y:auto;" id='content'>
                    <table class="move" width="15%" style="margin-left:670px">
                        <tr>
                            <th>
                                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                    <ol class="breadcrumb breadcrumb-alt" style="margin-right:-200px;margin-bottom:-250px;">
                                        <li class="breadcrumb-item" style="margin-left:-595px;margin-top:-8px">Occupational Health</li>
                                        <li class="breadcrumb-item" aria-current="page" style="margin-top:-8px">
                                            <a class="link-fx" href="">Health Record</a>
                                        </li>
                                    </ol>
                                </nav>
                            </th>
                        </tr>
                        @if(Session::has('msg'))
                            <div class="alert alert-success">
                                <a class="close" data-dismiss="alert">×</a>
                                {!!Session::get('msg')!!}
                            </div>
                            @endif
                        @if(Session::has('accessmsg'))
                            <div class="alert alert-danger">
                                <a class="close" data-dismiss="alert">×</a>
                                {!!Session::get('accessmsg')!!}
                            </div>
                            @endif
                      @if(Session::has('delmsg'))
                            <div class="alert alert-success">
                                <a class="close" data-dismiss="alert">×</a>
                                {!!Session::get('delmsg')!!}
                            </div>
                            @endif
                        <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                        <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">S.No</th>
                                    <th class="text-center" style="font-size:10px;width:8%;text-transform:capitalize; position:sticky;top:-1;">Employee Number</th>
                                    <th style="font-size:10px;width:18%;text-transform:capitalize;text-align:center;position:sticky;top:-1;">Name</th>
                                    <th class="d-none d-sm-table-cell" style="font-size:10px;text-align:center;text-transform:capitalize; position:sticky;top:-1;">Department</th>
                                    <th class="d-none d-sm-table-cell" style="font-size:10px;text-align:center;text-transform:capitalize; position:sticky;top:-1;">Position</th>
                                    <th style="font-size:10px;width:10%;text-transform:capitalize; text-align:center;position:sticky;top:-1;">Date of Medical</th>
                                    <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">Action</th>
                                    <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">View</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no=0;?>
                                @foreach($empdetails as $emp)
                                <?php
                                   //$rec = DB::table('employee_health_record')->where('employee_id',$emp->id)->first();
                                   $rec = DB::table('employee_health_record')->where('e_number',$emp->emp_number)->first();                  
                               ?> 
                                              
                                <tr>
                                    <td><input class="checkbox" type="checkbox" id="{{$emp->id}}" name="id[]"></td>
                                    <td class="text-center" style="font-size:10px;">{{$no=$no+1}}</td>
                                    <td class="text-center" style="font-size:10px;">{{$emp->emp_number}}</td>
                                    <td class="text-center">
                                        <em class="text-mute" style="font-size:10px;">{{$emp->emp_name}}</em>
                                    </td>
                                    <td class="text-center" style="font-size:10px;">
                                        <em class="text-muted">
                                          @if(is_numeric($emp->emp_department))
                                            @foreach($departments as $dep)
                                                @if($emp->emp_department==$dep->id)
                                                    {{$dep->departmentname}}
                                                    @break
                                                @endif
                                            @endforeach
                                           @else
                                             {{$emp->emp_department}}
                                           @endif
                                        </em>
                                    </td>
                                    <td class="text-center" style="font-size:10px;">
                                        <em class="text-muted">{{$emp->emp_position}}</em>
                                    </td>
                                    <td class="text-center" style="font-size:10px;">
                                        <em class="text-muted">
                                            {{$rec ? date('d-m-Y',strtotime($rec->date)) : '' }}
                                        </em>
                                    </td>
                    
                                    <div class="dropdown">
                                        <div class="dropdown font-size-sm">
                                            <td class="font-w600 font-size-sm">

                                                <button type="button" class="btn btn-primary dropdown-toggle" id="dropdown-default-primary" style="font-size:10px;" data-toggle="dropdown">Actions</button>
                                                <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark">
                                                    @if(count($doct)>0)
                                                    <a class='dropdown-item' href="/user/employee/health/record/update/{{$emp->emp_number}}" style="font-size:10px;">Update Health Record</a>
                                                    <a class='dropdown-item' href="/user/employee/health/addinjury/{{$emp->emp_number}}" style="font-size:10px;">Add Injury Details</a>
                                                    <a class='dropdown-item' href="/user/employee/health/addillness/{{$emp->emp_number}}" style="font-size:10px;">Add Illness Details</a>
                                                    @endif
                                                    <a class='dropdown-item' href="/user/employee/health/history/{{$emp->emp_number}}" style="font-size:10px;">View Health History</a>
                                            </td>
                                        </div>

                                    <td>
                                        <button type="button" class="btn btn-primary" style="font-size:10px;width: 60px;" data-toggle="modal" data-id="{{ $emp->id }}" data-target="#viewModal{{$emp->id}}"><strong>View</strong> </button>
                                        <div id="viewModal{{$emp->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h2 class="modal-title">Detailed View</h2>
                                                    </div>
                                                    <table class="table table-striped">
                                                        <tr>
                                                            <th style="width:70px;">Employee Number</th>
                                                            <td>{{$emp->emp_number}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th style="width:70px;">Employee Name</th>
                                                            <td>{{$emp->emp_name}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th style="width:70px;">Department</th>
                                                            <td>
                                                                @if(is_numeric($emp->emp_department))
                                                                    @foreach($departments as $dep)
                                                                        @if($emp->emp_department==$dep->id)
                                                                            {{$dep->departmentname}}
                                                                            @break
                                                                        @endif
                                                                    @endforeach
                                                                @else
                                                                    {{$emp->emp_department}}
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th style="width:70px;">Position</th>
                                                            <td>{{$emp->emp_position}}</td>
                                                        </tr>
                                                   
                                                        <tr>
                                                            <th style="width:70px;">Date</th>
                                                            <td>{{$rec ? date('d-m-Y',strtotime($rec->date)) : '' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th style="width:70px;">Tobacco</th>
                                                            <td>{{$rec ? $rec->tobacco : ''}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th style="width:70px;">Blood Pressure Diastolic</th>
                                                            <td>{{$rec ? $rec->bp_diastolic : ''}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th style="width:70px;">Blood Pressure Systolic</th>
                                                            <td>{{$rec ? $rec->bp_systolic : ''}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th style="width:70px;">Blood Sugar Level</th>
                                                            <td>{{$rec ? $rec->bs_level : ''}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th style="width:70px;">Remarks</th>
                                                            <td>{{$rec ? $rec->remarks : ''}}</td>
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
            <!-- END Dynamic Table Full -->
        </div>
        <!-- END Page Content -->
       

        <!-- Apps Modal -->
        <!-- Opens from the modal toggle button in the header -->
        <div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="block block-rounded block-themed block-transparent mb-0">
                        <div class="block-header bg-primary-dark">
                            <h3 class="block-title">More</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                    <i class="si si-close"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content block-content-full">
                            <div class="row gutters-tiny">

                                <div class="col-6">
                                    <!-- Products -->
                                    <a class="block block-rounded block-link-shadow bg-body" href="/user/occupationhealth/register">
                                        <div class="block-content text-center">
                                            <i class="far fa-list-alt fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Employee Health Record
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Products -->
                                </div>
                                <div class="col-6">
                                    <!-- Products -->
                                    <a class="block block-rounded block-link-shadow bg-body" href="/user/occupationhealth/injuryrecords">
                                        <div class="block-content text-center">
                                            <i class="far fa-list-alt fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Employee Injury Record
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Products -->
                                </div>
                                <div class="col-6">
                                    <!-- Products -->
                                    <a class="block block-rounded block-link-shadow bg-body" href="/user/occupationhealth/illnessrecords">
                                        <div class="block-content text-center">
                                            <i class="far fa-list-alt fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Employee Illness Record
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Products -->
                                </div>


                                <div class="col-6">
                                    <!-- Products -->
                                    <a class="block block-rounded block-link-shadow bg-body" href="{{ url('/user/occupationhealth/analysis') }}">
                                        <div class="block-content text-center">
                                            <i class="far fa-list-alt fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2" style="margin-bottom:60px">
                                                Analysis
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Products -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Apps Modal -->

 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
   {{----------delete request button ----------}}
    <script>
            $("#del").click(function() {
                mess1 = "Are you sure?";
                x = confirm(mess1);
                if (x == true) {
                    mess2 = 'Health Record will be deleted permanently,Press OK to continue.';
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
                        $.ajax({
                            type: 'get'
                            , url: "{{route('deletehealthrecord')}}" + "?val=" + $(this).attr('id')
                            , success: function(response) {
                                window.location.href = "{{url('/user/occupationhealth/register')}}";                             
                            }
                            , error: function(response) {
                                alert(errResponse);
                            }
                        });
                    });

                } else {
                    alert('No Record Selected');
                }
            });

        

            function autoResizeDiv(height) {
                document.getElementById('content').style.height = (height) + 'px';
            }
            window.onresize = autoResizeDiv;
            autoResizeDiv(window.innerHeight - 100);

    </script>

@endsection


@section('sideitem')
@if(Gate::allows('delete-doctor') || Gate::allows('delete-all'))
<div class="input-group input-group-sm ml-2">
    <button type="button" id="del" value="id[]" class="btn btn-sm btn-dual">
        <i class="fas fa-1x fa-trash" style="color:red;"></i>
    </button>
</div>
@endif
@endsection
