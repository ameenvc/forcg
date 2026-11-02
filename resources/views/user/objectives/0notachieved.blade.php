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
        <form method="post" action="{{url('/user/convert/file/'.'tvalue3')}}" style="margin-bottom: 0px">
            @csrf
            <button onclick="return fnCovertTable('pdf')" style="width:60px;  font-size:10px;border:none;background-color:white;">Pdf</button>
            <!--change hidden input type id as below-->
            <input type="hidden"  id="tvalue3" name="tvalue3"/>
        </form>
    </li>
    <li><button  onclick="fnCovertTable('excel')" style="width:60px;  font-size:10px;border:none;background-color:white;">Excel</li>
    <li><button  onclick="fnCovertTable('word')" style="width:60px;  font-size:10px;border:none;background-color:white;">Word</button></li>
</ul>
</div>


@endsection
@section('content')
<!doctype html>
<html lang="en">
<head>
<style>
    body {
  overflow-y: hidden;
    }
  </style>
  </head>
    <body>
    <?php
    $data1=$data=$data3=$data4=[];
    ?>
      <input type="hidden" value="a3" id="tabselected">
     {{--  <div class="bg-body-light" >
                    <div class="content content-full">
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                            <h1 class="flex-sm-fill h3 my-2">

                               Not-Achieved Objectives<small class="d-block d-sm-inline-block mt-2 mt-sm-0 font-size-base font-w400 text-muted"></small>
                            </h1>
                            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-alt">
                                    <li class="breadcrumb-item">Objectives</li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="">Not-Achieved</a>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>--}}
                <!-- END Hero -->

                <!-- Page Content -->
                <div class="content" style="width:100%" >
                    <!-- Dynamic Table Full -->
                    <div class="block block-rounded" style="margin-left:-1%">
                        {{-- <div class="block-header">
                            <h3 class="block-title">Dynamic Table <small>Full</small></h3>
                        </div> --}}
                        <div class="block-content block-content-full">
                            <div class="scrollable" style="height:450px;" >
                                <table class="move" width="10%" style="margin-left:670px">
                                    <tr>

                                    <th>
                                    <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                            <ol class="breadcrumb breadcrumb-alt"style="margin-right:-200px;margin-bottom:-250px">
                                                <li class="breadcrumb-item" style="margin-left:-595px;;margin-top:-8px">Objectives</li>
                                                <li class="breadcrumb-item" aria-current="page" style="margin-top:-8px">
                                                    <a class="link-fx" href="/user/objectives/notachieved" >Not Achieved</a>
                                                </li>
                                            </ol>
                                        </nav>
                                    </th>
                                    </tr>
                            <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->                            <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                                <thead>
                                    <tr>
                                    <th class="text-center" style="width: 5%;font-size:10px;text-transform:capitalize;position:sticky;top:-1">S.No</th>
                                        <th class="text-center" style="width:5%;font-size:10px;text-transform:capitalize;position:sticky;top:-1">Objective ID</th>
                                        <th  class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Department</th>
                                        <th class="text-center" style="width: 15%;font-size:10px;text-transform:capitalize;position:sticky;top:-1">Objective&nbspName</th>


                                        <th style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Target</th>
                                        <th style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Span</th>


                                        <th  class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Target&nbspDate</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Frequency</th>
                                        <th class="text-center" style="font-size:10px;width: 10%;text-transform:capitalize;position:sticky;top:-1">Reviewed&nbspdate</th>

                                        <th class="text-center"  style="font-size:10px;text-transform:capitalize;width: 5%;;position:sticky;top:-1">View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $no=0;?>
                                    @foreach($data2 as $value)
                                    <?php
                                                    $rr=$value->Oid;
                                                    $day = $value->tardate;
                                                    $d = strtotime($day);
                                                    $date = date('Y-m-d',$d);
                                                    $dt = $value->tardate;
                                                    $dat=strtotime($dt);
                                                    $dates = date('Y-m-d',$dat);
                                                    $cats=$value->department;
                                                    $cat=strtoupper(substr($cats,0,3));
                                                    ?>
                                                    <?php $status=$value->status;
                                                    $color=null;
                                                    ?>

                                                  @if ($status == "Review Pending")
                                                        <?php $color="orange";?>
                                                    @elseif($status == "Reviewed")
                                                        <?php $color="yellow";?>
                                                    @elseif($status == "Rejected")
                                                        <?php $color="red";?>
                                                    @else
                                                        <?php $color="#a6ff00";?>
                                                    @endif

                                    <tr>
                                        <td class="text-center" style="font-size:10px;">{{$no=$no+1}}</td>
                                       <td class="text-center" style="font-size:10px;">
                                        @if($value->status=="Rejected")
                                         <span  class="badge badge-danger" class="text-center" style="font-size:10px;"><?=$cat?>{{ $value->count }}</span>
                                        @elseif($value->status=="Review Pending")
                                        <span class="badge badge-warning" class="text-center" style="font-size:10px;"><?=$cat?>{{ $value->count }}</span>

                                        @elseif($value->status=="Reviewed")
                                        <span  class="badge badge-primary" class="text-center" style="font-size:10px;"><?=$cat?>{{ $value->count }}</span>
                                         @else

                                         <span  class="badge badge-success" class="text-center" style="font-size:10px;"><?=$cat?>{{ $value->count }}</span>


                                        @endif
                                         </td>
                                        <td class="text-center" style="font-size:10px;">
                                            <em class="text-center" style="font-size:10px;">{{ $value->department }}</em>
                                        </td>
                                        <td class="text-center" style="font-size:10px;">
                                           <em class="text-muted">{{$value->objectives}}</em>
                                        </td>
                                        @if($value->unit=='No sign')

                                        <td class="text-center" style="font-size:10px;">{{ $value->otarget}}</td>

                                        @else

                                        <td class="text-center" style="font-size:10px;">{{ $value->otarget}}{{ $value->unit }}</td>
                                        @endif
                                        <td class="text-center" style="font-size:10px;">{{ $value->span}}</td>

                                        <td class="text-center" style="font-size:10px;">{{date('M/d/Y',strtotime($value->tardate))}}</td>

                                        <td class="text-center"   style="font-size:10px">{{ $value->frequency}}</td>
                                        <td class="text-center"   style="font-size:10px">{{ $value->reviewed_at}}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary" style="font-size:10px;" data-toggle="modal" data-id="{{ $value->Oid }}" data-target="#viewModal{{$value->Oid}}"><strong>View</strong> </button>
                                        <div id="viewModal{{$value->Oid}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h2 class="modal-title">Detailed View</h2>
                                                </div>
                                                <table class="table table-striped">
                                                    <tr>
                                                        <th style="width:70px">Objective ID</th>
                                                        <td>  @if($value->status=="Rejected")
                                         <span  class="badge badge-danger" ><?=$cat?>{{ $value->count }}</span>
                                        @elseif($value->status=="Review Pending")
                                        <span class="badge badge-warning" ><?=$cat?>{{ $value->count }}</span>

                                        @elseif($value->status=="Reviewed")
                                        <span  class="badge badge-primary"><?=$cat?>{{ $value->count }}</span>
                                         @else

                                         <span  class="badge badge-success" ><?=$cat?>{{ $value->count }}</span>


                                        @endif</td>
                                                    </tr>
                                                    <tr>
                                                                                    <th>Objectives name</th>
                                                                                    <td>{{ $value->objectives }}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>Objectives desription</th>

                                                                                    <td><?php echo nl2br( $value->description )?></td>
                                                                                </tr>

                                                                                <tr>
                                                                                    <th>Department</th>
                                                                                    <td>{{ $value->department }}</td>
                                                                                </tr>
                                                                                @if($value->unit=='No sign')
                                                                                <tr>
                                                                                    <th>Target</th>
                                                                                    <td>{{ $value->otarget }}</td>
                                                                                </tr>
                                                                                @else
                                                                                <tr>
                                                                                    <th>Target</th>
                                                                                    <td>{{ $value->otarget }}{{$value->unit}}</td>
                                                                                </tr>
                                                                               @endif
                                                                                <tr>
                                                                                    <th>Span</th>
                                                                                    <td>{{ $value->span }}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>Target date</th>
                                                                                    <td>{{ $value->tardate }}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>Responsibility</th>
                                                                                    <td>{{ $value->responsibility }}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>Period : From</th>
                                                                                    <td>{{ $value->fromdate }}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>Period : To</th>
                                                                                    <td>{{ $value->todate }}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>Methodology</th>
                                                                                    <td><?php echo nl2br( $value->methodology )?></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>Resources</th>
                                                                                    <td><?php echo nl2br( $value->resources )?></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>Frequency</th>
                                                                                    <td>{{ $value->frequency }}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>Reviewer remarks</th>
                                                                                    <td>{{ $value->remarks }}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>Reviewer proposal</th>
                                                                                    <td>{{ $value->proposal }}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>Approver remarks</th>
                                                                                    <td>{{ $value->higherremarks }}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>Approver proposal</th>
                                                                                    <td>{{ $value->higherproposal }}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>Process stage</th>
                                                                                    <td>  @if($value->status=="Rejected")
                                         <span  class="badge badge-danger" >{{ $value->status }}</span>
                                        @elseif($value->status=="Review Pending")
                                        <span class="badge badge-warning" >{{ $value->status }}</span>

                                        @elseif($value->status=="Reviewed")
                                        <span  class="badge badge-primary" >{{ $value->status }}</span>
                                         @else

                                         <span  class="badge badge-success" >{{ $value->status }}</span>


                                        @endif
                                                           </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>Posted by</th>
                                                                                    <td>{{$value->postedby}}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>Added date</th>
                                                                                    <td>{{$value->created_at }}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>Last reviewed</th>
                                                                                    <td>{{$value->reviewed_at }}</td>
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
                                <h3 class="block-title">OPTIONS</h3>
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
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/objectives/listofobjectives/{{Session::get('objdept')}}/{{Session::get('year')}}">
                                            <div class="block-content text-center" style="height:130px">
                                                <i class="far fa-list-alt fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                 <br> Register
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Products -->
                                    </div>
                                    <div class="col-6">
                                        <!-- Sales -->
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/objectives/achieved">
                                            <div class="block-content text-center"  style="height:130px">

                                            <i class="fas fa-check-circle fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                 <br>  Achieved<br>
                                                </p>
                                            </div>
                                        </a>

                                      </div>
                                      <div class="col-6">
                                        <!-- Sales -->
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/objectives/notachieved">
                                            <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-times-circle fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                   Not Achieved
                                                </p>
                                            </div>
                                        </a>

                                      </div>

                                      <div class="col-6">
                                        <!-- Sales -->
                                        <br>
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/objectives/objreport"  style="margin-top:-18px">
                                            <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Objective report
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
            @include('exporting.forobjectives')

    </body>
</html>
@endsection


