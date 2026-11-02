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
        <form method="post" action="{{url('/user/convert/file/'.'tvalue4')}}" style="margin-bottom: 0px">
            @csrf
            <button onclick="return fnCovertTable('pdf')" style="width:60px;  font-size:10px;border:none;background-color:white;">Pdf</i></button>
            <!--change hidden input type id as below-->
            <input type="hidden"  id="tvalue4" name="tvalue4"/>
        </form>
    </li>
    <li><button  onclick="fnCovertTable('excel')" style="width:60px;  font-size:10px;border:none;background-color:white;">Excel</button></li>
    <li><button  onclick="fnCovertTable('word')" style="width:60px;  font-size:10px;border:none;background-color:white;">Word</button></li>
</ul>
</div>

<div class="input-group input-group-sm">
<a type="button"  href="{{url('/user/internalaudit/addaudit')}}" class="btn btn-sm btn-dual" style="margin-left:8px;"  style="">
<i class="fa fa-1x fa-plus" style="color:grey;"></i>
</a>
</div>
@endsection
@section('content')
<!doctype html>
<html lang="en">
    <head>
        <style>
        .move{
            margin-left:5%;
            position:absolute;
            top:17%
        }
        </style>
        </head>
    <body>
    <?php $data=$data1=$data3=[]; ?>
    <input type="hidden" value="a4" id="tabselected">
          <div class="content"style="width:100%">
                   <div class="block block-rounded"style="margin-left:-1%;">
                       <div class="block-content block-content-full">
                            <div id="content">
                                <table class="move" width="65%">
                                    <tr>
                                    <th>
                                    <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                            <ol class="breadcrumb breadcrumb-alt"style="margin-left:1%;">
                                                <li class="breadcrumb-item">Audit Management</li>
                                                <li class="breadcrumb-item" aria-current="page">
                                                    <a class="link-fx" href="">Closed Audits</a>
                                                </li>
                                            </ol>
                                        </nav>
                                    </th>
                                    </tr>
                                    </table>
                                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full">

                                <thead>
                                    <tr>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 5%;position:sticky;top:-1" >S.No</th>
                                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 10%;position:sticky;top:-1" >Audit ID</th>
                                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 15%;position:sticky;top:-1">Department</th>
                                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 15%;position:sticky;top:-1">Auditors</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 15%;position:sticky;top:-1">Auditee</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 10%;position:sticky;top:-1">From Date</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 10%;position:sticky;top:-1">To Date</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 15%;position:sticky;top:-1">Process Stage</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 5%;position:sticky;top:-1">View</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no=0;?>
                                    @foreach($data4 as $value)
                                    <?php
                                  $rr=$value->id;
                                  $dept = DB::table('departments')->where('id',$value->department)->value('departmentname');
                                 ?>

                                    <tr>
                                       <td class="text-center "style="font-size:10px;">{{$no=$no+1}}</td>

                                        <td class="text-center "style="font-size:10px;">{{$value->aud_id}}</td>


                                        <td class="font-w600 "style="font-size:10px;">
                                            <a href="#">{{$dept}}</a>
                                        </td>

                                        <td class="font-w600 "style="font-size:10px;">
                                            <a href="#">{{$value->auditors}}</a>
                                        </td>
                                        <td class="d-none d-sm-table-cell "style="font-size:10px;">
                                            <em class="text-muted">{{$value->contact}}</em>
                                         </td>
                                         <td class="text-center "style="font-size:10px;">{{$value->date}}</td>
                                         <td class="text-center "style="font-size:10px;" >{{ $value->enddate}}</td>
                                         <td class="d-none d-sm-table-cell"style="font-size:12px;">

                                         <span class="badge badge-success">{{$value->status}}</span>

                                         </td>








                                    <td>
                                        <button type="button" class="btn btn-primary" style="font-size:10px;width: 60px;height:30px" data-toggle="modal" data-id="{{ $value->id }}" data-target="#viewModal{{$value->id}}"><strong>View</strong> </button>
                                        <div id="viewModal{{$value->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h2 class="modal-title">Detailed View</h2>
                                                </div>
                                                <table class="table table-striped">
                                                    <tr>
                                                        <th style="width:70px">Audit id</th>
                                                        <td>{{ $value->aud_id }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Department</th>
                                                        <td>{{ $dept }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Scope of audit</th>
                                                        <td><?php echo nl2br($value->scope)?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Auditors</th>
                                                        <td>{{ $value->auditors }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Auditee</th>
                                                        <td>{{ $value->contact }}</td>
                                                     </tr>
                                                    <tr>
                                                        <th>From date</th>
                                                        <td>{{$value->date}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Time</th>
                                                        <td>{{$value->time}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>To date</th>
                                                        <td>{{$value->enddate}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Focus</th>
                                                         {{-- <td>{{nl2br($value->focus) }}</td>   --}}
                                                         <td><?php echo nl2br($value->focus)?></td>
                                                </tr>
                                                <tr>
                                                    <th>Budgeted hours</th>
                                                    <td>{{$value->budgeted}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Quarter</th>
                                                    <td>{{$value->quarter}}</td>
                                                </tr>
                                                    <tr>
                                                        <th>Process stage</th>
                                                        <td>

                                            <span class="badge badge-success">{{$value->status}}</span>

                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Added at</th>
                                                        <td>{{$value->created_at}}</td>
                                                    </tr>
                                                </table>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-primary"style="font-size:11px;" data-dismiss="modal">Close</button>


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
            <!--</div>
        </div>-->


<script>
      function autoResizeDiv(height) {
            document.getElementById('content').style.height = (height) + 'px';
        }
        window.onresize = autoResizeDiv;
        autoResizeDiv(window.innerHeight - 100);
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
                                        <!-- Products -->
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/auditschedule">
                                            <div class="block-content text-center" style="height:130px">
                                                <i class="far fa-list-alt fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Audit Schedule
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Products -->
                                    </div>
                                    <div class="col-6">
                                        <!-- Sales -->
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/internalaudit/auditorslist">
                                            <div class="block-content text-center" style="height:130px">
                                                <i class="fas fa-address-card fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                   <br> Auditors List
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Sales -->
                                    </div>
                                    <div class="col-6">
                                        <!-- Payments -->
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/internalaudit/auditlog">
                                            <div class="block-content text-center" style="height:130px">
                                                <i class="fas fa-calendar-check fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    <br>Audit Log
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Payments -->
                                    </div>
                                    <div class="col-6">
                                        <!-- Sales -->
                                        <a class="block block-rounded block-link-shadow bg-body mb-2" href="/user/internalaudit/closedaudits">
                                            <div class="block-content text-center" style="height:130px">
                                                <i class="fas fa-check-circle fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Closed Audits
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Sales -->
                                    </div>
                                    <div class="col-6">
                                        <!-- Sales -->
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href='/user/internalaudit/analysis'>
                                            <div class="block-content text-center" style="height:130px">
                                                <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Analysis
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Sales -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Apps Modal -->

            @include('exporting.forauditmanagement')
    </body>
</html>
@endsection



