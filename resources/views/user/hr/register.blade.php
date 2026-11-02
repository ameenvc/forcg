@extends('pages.datatables')
@section('exportadd')
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

                <button title="Pdf" onclick="return fnCovertTable('pdf')" style="width:50px;  font-size:10px;border:none;background-color:white;">Pdf</button>
                <!--change hidden input type id as below-->
                <input type="hidden" id="tvalue1" name="tvalue1" />
            </form>
        </li>
        <li><button title="Excel" onclick="fnCovertTable('excel')" style="width:50px;  font-size:10px;border:none;background-color:white;">Excel </button></li>
        <li><button title="Word" onclick="fnCovertTable('word')" style="width:50px;  font-size:10px;border:none;background-color:white;">Word </button></li>
    </ul>
</div>

<div class="input-group input-group-sm">
    <a type="button" href="{{url('/user/hr/addjob')}}" class="btn btn-sm btn-dual" style="margin-left:8px;" style="">
        <i class="fa fa-1x fa-plus" style="color:grey;"></i>
    </a>
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
    {{--<style>
.header{
    margin-left:6%;
    margin-bottom:-2.9%;
}
</style>--}}
</head>
<body>

    <input type="hidden" value="a1" id="tabselected">
    <!-- Page Content -->

    <div class="content" style="width:100%;">
        <!-- Dynamic Table Full -->
        <div class="block block-rounded" style="margin-left:-1%;">
            {{-- <div class="block-header">
                            <h3 class="block-title">Dynamic Table <small>Full</small></h3>
                        </div> --}}
            <div class="block-content block-content-full">
                <div style="oveflow-x:auto;oveflow-y:auto;" id="content">
                    <table class="move" width="15%" style="margin-left:670px">
                        <tr>

                            <th>
                                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                    <ol class="breadcrumb breadcrumb-alt" style="margin-right:-200px;margin-bottom:-100px;">
                                        <li class="breadcrumb-item" style="margin-left:-595px;margin-top:-8px">HR Management</li>
                                        <li class="breadcrumb-item" aria-current="page" style="margin-top:-8px">
                                            <a class="link-fx" href="/user/hr/register">Job</a>


                                        </li>

                                    </ol>
                                </nav>
                            </th>
                        </tr>
                        <div class="table-responsive">
                        <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                        <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                            <thead>
                                <tr>
                                    <th style="width: 5%;position:sticky;top:-1"></th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">S.No</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Department</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Position Title </th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Job Code</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Reports To</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Prepared / Revised On</th>
                                    <!-- <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Training Provider</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Training Duration</th>
                                         <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Date Of Training</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Benefits of Training</th> -->
                                    <!-- <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Action Comment</th>
                                        <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Reevaluate</th> -->
                                    <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">View</th>
                                    <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $no=0;?>
                                @foreach($data as $value)
                                <?php $rr=$value->id; ?>
                                <tr>
                                    <td><input class="text-center font-size-sm" type="checkbox" id="{{ $value->id}}" name="id[]"></td>
                                    <td class="text-center " style="font-size:10px;height:30px;">{{$no=$no+1}}</td>

                                    <td class="text-center " style="font-size:10px;">{{ $value->department ? $value->department->departmentname : '_' }}</td>
                                    <td class="text-center " style="font-size:10px;text-transform:capitalize">{{ $value->position_title }}</td>
                                    <td class="text-center " style="font-size:10px;">{{ $value->job_code}}</td>
                                    <td class="text-center" style="font-size:10px;">{{ $value->reports_to}}</td>
                                    <td class="text-center " style="font-size:10px;">{{ $value->prepared_on}}</td>

                                    <td>
                                        <button type="button" class="btn btn-primary" style="font-size:10px;width: 60px;" data-toggle="modal" data-id="{{  $value->id}}" data-target="#viewModal{{$value->id}}"><strong>View</strong> </button>

                                        <div id="viewModal{{$value->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h2 class="modal-title">Detailed View</h2>
                                                    </div>
                                                    <table class="table table-striped">
                                                        <tr>
                                                            <th style="width:70px">Department</th>
                                                            <td>{{ $value->department ? $value->department->departmentname : '_'  }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Position Title</th>
                                                            <td>{{ $value->position_title }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Job Code</th>
                                                            <td>{{ $value->job_code }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Reports To</th>
                                                            <td>{{ $value->reports_to}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Prepared / Revised On</th>
                                                            <td>{{ $value->prepared_on }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Responsibilities</th>
                                                            <td><?php echo nl2br( $value->responsibilities)?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Job Authorities</th>
                                                            <td><?php echo nl2br( $value->job_authorities)?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Accountabilities</th>
                                                            <td><?php echo nl2br( $value->accountabilities)?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>HSE General Responsibilities</th>
                                                            <td><?php echo nl2br( $value->hse)?></td>
                                                        </tr>
                                                        <tr>
                                                            <th colspan="2" style="font-size:15px;"> Communications & Working Relationships</th>
                                                            <!-- <td colspan="2"></td> -->
                                                        </tr>
                                                        <tr>
                                                            <th>Internal</th>
                                                            <td>{{ $value->internal}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th> External</th>
                                                            <td>{{ $value->external}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Minimum Qualifications</th>
                                                            <td>{{ $value->min_qual}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th> Training / Skills</th>
                                                            <td>{{ $value->training_skills}}</td>
                                                        </tr>

                                                        <tr>
                                                            <th> Minimum Experience</th>
                                                            <td>{{ $value->min_exp}}</td>
                                                        </tr>

                                                        <tr>
                                                            <th>Added by</th>
                                                            <td>{{$value->added_by }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Added Date</th>
                                                            <td>{{$value->created_at }}</td>
                                                        </tr>

                                                    </table>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                    </td>

                                    <div class="dropdown font-size-sm">
                                        <td class="font-w600 font-size-sm">



                                        </td>
                                    </div>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                </div>
            </div>
        </div>
        <!-- END Dynamic Table Full -->
        {{-----------delete button for mr ------------}}
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script>
            $("#del").click(function() {
                var dataArr = new Array();
                if ($('input:checkbox:checked').length > 0) {
                    $('input:checkbox:checked').each(function() {
                        dataArr.push($(this).attr('id'));
                        // console.log(dataArr);
                        $.ajax({
                            type: 'get'
                            , url: "{{route('deletejob')}}" + "?val=" + $(this).attr('id')
                            , success: function(response) {
                                // alert(response);
                                window.location.href = "{{url('/user/hrjmgmt')}}";
                                //  redirect('/user/doc/masterlist')->withData($data);
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
        {{-----------edit button for mr ------------}}

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
                        , url: "{{route('editj')}}" + "?val=" + (all)
                        , success: function(response) {
                            // alert(response);

                            window.location.href = "{{route('editj')}}" + "?val=" + (all);
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

        </script>

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
                        <h3 class="block-title">Options</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content block-content-full">
                        <div class="row gutters-tiny">
                            <!-- <div class="col-6">

                                        <a class="block block-rounded block-link-shadow bg-body" href="{{url('/user/vendor/addvendor')}}" >
                                            <div class="block-content text-center"style="height:130px" >
                                             <i class="fas fa-plus-circle fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-4">
                                                Add Vendor
                                                </p>
                                            </div>
                                        </a>

                                    </div> -->
                            <div class="col-6">
                                <!-- Products -->
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/hrjmgmt">
                                    <div class="block-content text-center" style="height:130px">
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
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="fas fa-chart-bar fa-2x text-primary"></i>
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

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- END Apps Modal -->

    @section('sideitem')
    {{-- @foreach($mr as $mrs) --}}
    {{-- @if(Auth::user()->email==$mrs) --}}
    @if(Gate::allows('edit-hr') || Gate::allows('edit-all'))
    <div class="input-group input-group-sm ml-2">
        <button type="button" style="margin-left:460px;" class="btn btn-sm btn-dual" id="edit" value=id[]>
            <i class="fa fa-1x fa-edit" style="color:grey;"></i>
        </button>
    </div>
    @endif
    @if(Gate::allows('delete-hr') || Gate::allows('delete-all'))
    <div class="input-group input-group-sm ml-2">
        <button type="button" class="btn btn-sm btn-dual" id="del" value=id[]>
            <i class="fas fa-1x fa-trash" style="color:red;"></i>
        </button>
    </div>
    @endif
    {{-- @endif --}}
    {{-- @endforeach --}}
    @endsection
</body>

</html>
@endsection
