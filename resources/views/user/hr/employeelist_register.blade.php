@extends('pages.datatables')
@section('exportadd')

<div class="input-group input-group-sm">
    <a href="" class="btn btn-sm btn-dual" style="margin-left:0px;" onclick="return fnCovertTable('print')">

        <i class="fa fa-print  fa-1x" style="color:grey"></i>

    </a>
</div>

{{--<div class="input-group input-group-sm">
<button type="button" class=" btn-sm btn-dual btn btn-default dropdown-toggle mr-0 mb-0" style="margin-left:8px; " data-toggle="dropdown">
    <i class="fa fa-file-export fa-1x" style="color:grey;"></i>
</button>
<ul class="dropdown-menu" role="menu" style="min-width:10px;text-align:center;">
<li>
    <!--change route as below-->
        <form method="post" action="{{url('/user/convert/file/'.'tvalue1')}}" style="margin-bottom: 0px">
@csrf
<button onclick="return fnCovertTable('pdf')" style="width:50px;  font-size:10px;border:none;background-color:white;">Pdf</button>
<!--change hidden input type id as below-->
<input type="hidden" id="tvalue1" name="tvalue1" />
</form>
</li>
<li><button onclick="fnCovertTable('excel')" style="width:50px;  font-size:10px;border:none;background-color:white;">Excel</button></li>
<li><button onclick="fnCovertTable('word')" style="width:50px;  font-size:10px;border:none;background-color:white;">Word</button></li>
</ul>
</div>--}}

<div class="input-group input-group-sm">
    <a type="button" href="{{url('/user/add/healthrecord/form')}}" class="btn btn-sm btn-dual" style="margin-left:8px;" style="">
        <i class="fa fa-1x fa-plus" style="color:grey;"></i>
    </a>
</div>
@endsection
@section('content')
<!doctype html>
<html lang="en">
<body>



    <!-- Page Content -->
    <div class="content" style="width:100%;">
        <!-- Dynamic Table Full -->
        <div class="block block-rounded">
            {{-- <div class="block-header">
                            <h3 class="block-title">Dynamic Table <small>Full</small></h3>
                        </div> --}}
            <div class="block-content block-content-full">
                <div style="overflow-x:hidden;overflow-y:auto;" id='content'>
                
                    <table class="move" width="15%" style="margin-left:670px">
                        <tr>

                            <th>
                                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                    <ol class="breadcrumb breadcrumb-alt" style="margin-right:-200px;margin-bottom:-250px;">
                                        <li class="breadcrumb-item" style="margin-left:-595px;margin-top:-8px">HR Management</li>
                                        <li class="breadcrumb-item" aria-current="page" style="margin-top:-8px">
                                            <a class="link-fx" href="">Employee List</a>
                                        </li>
                                    </ol>
                                </nav>
                            </th>
                        </tr>
                        
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
                                    <th style="width:2%;"></th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">S.No</th>
                                    <th class="text-center" style="font-size:10px;width:8%;text-transform:capitalize; position:sticky;top:-1;">Employee ID</th>
                                    <th style="font-size:10px;width:18%;text-transform:capitalize;text-align:center;position:sticky;top:-1;">Name</th>
                                    <th class="d-none d-sm-table-cell" style="font-size:10px;text-align:center;text-transform:capitalize; position:sticky;top:-1;">Department</th>
                                    <th class="d-none d-sm-table-cell" style="font-size:10px;text-align:center;text-transform:capitalize; position:sticky;top:-1;">Position</th>

                                   <!-- <th style="font-size:10px;width:10%;text-transform:capitalize; text-align:center;position:sticky;top:-1;">Joining Date</th>-->
                                   

                                    <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">View</th>
                                    <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no=0;?>
                                @foreach($data as $emp)
                                
                                <tr>
                                    <td><input class="checkbox" type="checkbox" id="{{$emp->id}}" name="id[]"></td>
                                    <td class="text-center" style="font-size:10px;">{{$no=$no+1}}</td>
                                    <td class="text-center" style="font-size:10px;">{{$emp->emp_number}}</td>
                                    <td class="text-center">
                                        <em class="text-mute" style="font-size:10px;">{{ucfirst($emp->emp_name)}}</em>
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
                                   <!-- <td class="text-center" style="font-size:10px;">
                                        <em class="text-muted">{{$emp->joining_date}}</em>
                                    </td>-->
                                  
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
                                                            <th style="width:70px;">Employee ID</th>
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
                                                            <th style="width:70px;">Joining Date</th>
                                                            <td>{{$emp->joining_date}}</td>
                                                        </tr>
                                                        
                                                    </table>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <div class="dropdown">

                                        <div class="dropdown font-size-sm">
                                            <td class="font-w600 font-size-sm">

                                                <button type="button" class="btn btn-primary dropdown-toggle" id="dropdown-default-primary" style="font-size:10px;" data-toggle="dropdown">Actions</button>
                                                <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark">
                                                   
                                            </td>
                                        </div>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
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
                                        // console.log(dataArr);
                                        $.ajax({
                                            type: 'get'
                                            , url: "{{route('deletehealthrecord')}}" + "?val=" + $(this).attr('id')
                                            , success: function(response) {
                                                // alert(response);
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

                        </script>
                        {{-- --------edit  button ------------}}
                        <script>
                            $("#edit").click(function() {
                                var dataArr = new Array();
                                var group = new Array();
                                var d;
                                var all;
                                if ($('input:checkbox:checked').length > 0) {
                                    $('input:checkbox:checked').each(function() {
                                        dataArr.push($(this).attr('id'));
                                        //  console.log(dataArr);

                                        all = "" + dataArr;
                                        group.push(all); // push all checkbox values into an array
                                        //  console.log(group);


                                    });
                                    $.ajax({
                                        type: 'get'
                                        , url: "{{route('employeehealthrecords')}}" + "?val=" + (all)
                                        , success: function(response) {
                                            // alert(response);

                                            window.location.href = "{{route('employeehealthrecords')}}" + "?val=" + (all);
                                            // console.log(response);

                                        }
                                        , error: function(response) {
                                            alert(errResponse);
                                        }
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

                </div>
            </div>
            <!-- END Dynamic Table Full -->


        </div>
        <!-- END Page Content -->
        {{-- </main> --}}
        <!-- END Main Container -->


        <!-- END Footer -->

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
                                    <a class="block block-rounded block-link-shadow bg-body" href="/user/hrjmgmt">
                                        <div class="block-content text-center">
                                            <i class="far fa-list-alt fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                 Job Description
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Products -->
                                </div>
                                <div class="col-6">
                                    <!-- Products -->
                                    <a class="block block-rounded block-link-shadow bg-body" href="/user/hrtmgmt">
                                        <div class="block-content text-center">
                                            <i class="far fa-list-alt fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Training Schedule
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Products -->
                                </div>
                                <div class="col-6">
                                    <!-- Products -->
                                    <a class="block block-rounded block-link-shadow bg-body" href="/user/employeelist">
                                        <div class="block-content text-center">
                                            <i class="far fa-list-alt fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Employee List
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Products -->
                                </div>
                                <div class="col-6">                    
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/employeelist">
                                            <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-clone fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                   Employee List
                                                </p>
                                            </div>
                                        </a>       
                                    </div> 
                              
                                <!--<div class="col-6">

                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/risks/closedrisks">
                                            <div class="block-content text-center">
                                                <i class="si si-plane fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Closed Risks
                                                </p>
                                            </div>
                                        </a>

                                    </div>
                                    <div class="col-6">

                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/risks/analysis">
                                            <div class="block-content text-center">
                                                <i class="si si-wallet fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Analysis
                                                </p>
                                            </div>
                                        </a>

                                    </div>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Apps Modal -->


</body>
</html>
@endsection


@section('sideitem')
{{-- @foreach($mr as $mrs) --}}
{{-- @if(Auth::user()->email==$mrs) --}}
<!--@if(Gate::allows('edit-doctor') || Gate::allows('edit-all'))
<div class="input-group input-group-sm ml-2">
    <button type="button" class="btn btn-sm btn-dual" style="margin-left:398px;" id="edit" value="id[]">
        <i class="fa fa-1x fa-edit" style="color:grey;"></i>
    </button>
</div>
@endif
@if(Gate::allows('delete-doctor') || Gate::allows('delete-all'))
<div class="input-group input-group-sm ml-2">
    <button type="button" id="del" value="id[]" class="btn btn-sm btn-dual">
        <i class="fas fa-1x fa-trash" style="color:red;"></i>
    </button>
</div>
@endif-->
{{-- @endif --}}
{{-- @endforeach --}}
@endsection
