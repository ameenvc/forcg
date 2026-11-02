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
            <form method="post" action="{{url('/user/convert/file/'.'tvalue2')}}" style="margin-bottom: 0px">
                @csrf

                <button onclick="return fnCovertTable('pdf')" style="width:60px;  font-size:10px;border:none;background-color:white;">Pdf</i></button>
                <!--change hidden input type id as below-->
                <input type="hidden"  id="tvalue2" name="tvalue2"/>
            </form>
        </li>
        <li><button  onclick="fnCovertTable('excel')" style="width:60px;  font-size:10px;border:none;background-color:white;">Excel</button></li>
    <li><button  onclick="fnCovertTable('word')" style="width:60px;  font-size:10px;border:none;background-color:white;">Word</button></li>
</ul>
</div>

<div class="input-group input-group-sm">
<a type="button"  href="{{url('/user/opportunity/addopportunity')}}" class="btn btn-sm btn-dual" style="margin-left:8px;"  style="">
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
                overflow-y: hidden;
                  }
                </style>
        </head>
    <body>
    <?php
    $data=$data2=$data3=[];
    ?>
      <input type="hidden" value="a2" id="tabselected">
          <div class="content"style="width:100%">
                   <div class="block block-rounded"style="margin-left:-1%;">
                       <div class="block-content block-content-full">
                        <div class="scrollable" style="overflow-x:hidden;" id="content" >
                            <table class="move" width="15%" style="margin-left:670px">
                                    <tr>

                                    <th>
                                    <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                            <ol class="breadcrumb breadcrumb-alt"style="margin-right:-200px;margin-bottom:-250px;">
                                                <li class="breadcrumb-item" style="margin-left:-600px;margin-top:-4px">Opportunity</li>
                                                <li class="breadcrumb-item" aria-current="page" style="margin-top:-4px">
                                                    <a class="link-fx" href="" >Accomplished</a>
                                                </li>
                                            </ol>
                                        </nav>
                                    </th>
                                    </tr>
                                    </table>
                                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">S.No</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">ID</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Department</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Opportunity Name</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Target&nbspDate</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Type</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Added Date</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Positive Impact</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Process Stage</th>
                                       <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Action Status</th>

                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">View</th>


                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no=0;?>
                                    @foreach($data1 as $value)
                                    <?php
                                    $oppdocs=explode(',',$value->file);
                                    $dept = DB::table('departments')->where('id',$value->department)->value('departmentname');?>
                                    <tr>
                                        <td class="text-center "style="font-size:10px;">{{$no=$no+1}}</td>
                                        <td class="text-center "style="font-size:10px;">
                                            <a href="{{ route('opportunity.detail', $value->id) }}">{{$value->dep_id }}</a>
                                        </td>

                                        <td class="d-none d-sm-table-cell" style="font-size:10px;">
                                            <em class="text-muted"style="font-size:10px;">{{$dept}}</em>
                                         </td>
                                         <td class="font-w600" style="font-size:10px;">
                                            <a href="#"style="font-size:10px;">{{$value->oppname}}</a>
                                        </td>
                                        <td>
                                            <em class="text-center "style="font-size:10px;">{{$value->tardate}}</em>
                                        </td>
                                        <td class="d-none d-sm-table-cell"style="font-size:12px;">
                                            <em class="text-center "style="font-size:10px;">{{$value->type}}</em>

                                            {{-- @if($value->likelihood=="Low")
                                        <span class="badge badge-danger">{{$value->likelihood }}</span>
                                        @elseif($value->likelihood=="Moderate")
                                        <span class="badge badge-warning">{{$value->likelihood }}</span>
                                        @else
                                        <span class="badge badge-success">{{$value->likelihood }}</span>
                                        @endif --}}
                                    </td>
                                        <td class="d-none d-sm-table-cell"style="font-size:12px;">
                                            <em class="text-center "style="font-size:10px;">{{$value->createdat}}</em>

                                                            {{-- @if($value->benefit=="Low")
                                                            <span class="badge badge-danger">{{$value->benefit }}</span>
                                                            @elseif($value->benefit=="Moderate")
                                                            <span class="badge badge-warning">{{$value->benefit }}</span>
                                                            @else
                                                            <span class="badge badge-success">{{$value->benefit }}</span>
                                                            @endif --}}
                                                        </td>
                                                            <td class="d-none d-sm-table-cell"style="font-size:12px;">
                                                            @if($value->impact=="Low")
                                                            <span class="badge badge-danger">{{$value->impact }}</span>
                                                            @elseif($value->impact=="Moderate")
                                                            <span class="badge badge-warning">{{$value->impact  }}</span>
                                                             @else
                                                             <span class="badge badge-success">{{$value->impact  }}</span>
                                                             @endif
                                                            </td>
                                        <td class="d-none d-sm-table-cell"style="font-size:12px;">
                                            <span class="badge badge-info">{{$value->status}}</span>
                                        </td>
                                        <td class="text-center "style="font-size:10px;width: 100px;">{{ $value->actionstatus}}</td>
                                        <td>
                                            <button type="button" class="btn btn-primary" style="font-size:10px;width: 60px;"  data-toggle="modal" data-id="{{ $value->id }}" data-target="#viewModal{{$value->id}}"><strong>View</strong> </button>
                                            <div id="viewModal{{$value->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h2 class="modal-title">Detailed View</h2>
                                                    </div>
                                                    <table class="table table-striped">
                                                        <tr>
                                                            <th style="width:70px">Opportunity id</th>
                                                            <td>{{ $value->dep_id }}</td>
                                                        </tr>
                                                        <tr>
                                                          <th>Department</th>
                                                          <td>{{ $dept }}</td>
                                                      </tr>
                                                        <tr>
                                                            <th>Opportunity name</th>
                                                            <td>{{ $value->oppname }}</td>
                                                        </tr>


                                                        <tr>
                                                            <th>Opportunity description</th>
                                                          {{-- <td>{{ $value->oppdes }}</td> --}}
                                                          <td><?php echo nl2br( $value->oppdes)?></td>
                                                        </tr>
                                                        <tr>
                                                          <th>Target date</th>
                                                        <td>{{ $value->tardate }}</td>
                                                      </tr>
                                                      {{-- <tr>
                                                        <th>Added at</th>
                                                      <td>{{ $value->createdat }}</td>
                                                    </tr> --}}
                                                      <tr>
                                                        <th>Reviewed at</th>
                                                      <td>{{ $value->reviewdate }}</td>
                                                    </tr>
                                                      {{-- <tr>
                                                        <th>Is this OHS related</th>
                                                      <td>{{ $value->ohs }}</td>
                                                    </tr>
                                                    @if($value->ohs=='Yes')
                                                    <tr>
                                                        <th>Type</th>
                                                      <td>{{ $value->type}}</td>
                                                    </tr>
                                                    @endif --}}
                                                      <tr>
                                                          <th>Action</th>
                                                          <td><?php echo nl2br($value->action)?></td>
                                                      </tr>
                                                      <tr>
                                                        <th>Attachments</th>
                                                        <td >
                                                            <div class="col-sm-8">
                                                                <button type="button" class="btn btn-secondary btn-small" style="font-size:13px;width: 150px;" data-toggle="modal" data-id="{{ $value->id }}" data-target="#viewModal4{{$value->id}}"><strong>View Attachments</strong> </button>
                                                                <div id="viewModal4{{$value->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                                                    <div class="modal-dialog modal-lg">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h3 class="modal-title">Attachments</h3>
                                                                            </div>
                                                                            <table class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                                                                                <div class="row justify-content-center">
                                                                                    <thead>
                                                                                        <tr class="table-secondary">
                                                                                            <th>S.No</th>
                                                                                            <th>Attachments</th>
                                                                                            <th></th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <?php $sno=0; ?>

                                                                                    @if( count($oppdocs) > 0 )
                                                                                        @foreach($oppdocs as $value1)

                                                                                            @if( $value1!="NULL" && $value1!="")
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td style="font-size:10px; ;">{{ $sno=$sno+1}}</td>
                                                                                                        <td style="font-size:15px;width: 100%;">{{ $value1}}</td>
                                                                                                        <td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success"  href="{{asset("storage/opportunity/".$value1)}}" download="{{$value1}}" >Download</a></td>
                                                                                                    </tr>
                                                                                                </tbody>
                                                                                                @else
                                                                                        <div class="row justify-content-center">
                                                                                            <h2>No Files</h2>
                                                                                        </div>
                                                                                            @endif
                                                                                            {{-- @endif  --}}
                                                                                        @endforeach
                                                                                    @else
                                                                                        <div class="row justify-content-center">
                                                                                            <h2>No Files</h2>
                                                                                        </div>
                                                                                    @endif
                                                                                </div>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </td>
                                                    </tr>
                                                      <tr>
                                                        <th>Likelihood</th>
                                                        <td>
                                                        @if($value->likelihood=="Low")
                                                        <span class="badge badge-danger">{{$value->likelihood }}</span>
                                                        @elseif($value->likelihood=="Moderate")
                                                        <span class="badge badge-warning">{{$value->likelihood }}</span>
                                                        @else
                                                        <span class="badge badge-success">{{$value->likelihood }}</span>
                                                        @endif
                                                    </td>
                                                    </tr>
                                                      <tr>
                                                        <th>Benefit</th>
                                                        <td>
                                                        @if($value->benefit=="Low")
                                                        <span class="badge badge-danger">{{$value->benefit }}</span>
                                                        @elseif($value->benefit=="Moderate")
                                                        <span class="badge badge-warning">{{$value->benefit }}</span>
                                                        @else
                                                        <span class="badge badge-success">{{$value->benefit }}</span>
                                                        @endif
                                                    </td>
                                                    </tr>

                                                    <tr>
                                                        <th>Positive impact</th>
                                                        <td>
                                                        @if($value->impact=="Low")
                                                        <span class="badge badge-danger">{{$value->impact }}</span>
                                                        @elseif($value->impact=="Moderate")
                                                        <span class="badge badge-warning">{{$value->impact  }}</span>
                                                         @else
                                                         <span class="badge badge-success">{{$value->impact  }}</span>
                                                         @endif
                                                        </td>
                                                    </tr>
                                                        <tr>
                                                            <th>Responsibility</th>
                                                         <td>{{ $value->responsibility }}</td>
                                                        </tr>

                                                        <tr>
                                                            <th>Process stage</th>
                                                          <td>{{ $value->status }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Action status</th>
                                                          <td>{{ $value->actionstatus }}</td>
                                                        </tr>

                                                        @if($value->ndareason)
                                                        <tr>
                                                            <th>Closure remarks</th>
                                                          <td><?php echo nl2br( $value->ndareason )?></td>
                                                        </tr>
                                                        @endif

                                                        <tr>
                                                            <th>Added by</th>
                                                            @if($value->postedby=='')
                                                            <td>admin</td>
                                                            @else
                                                            <td>{{$value->postedby}} &nbsp; {{ $value->postedByOpportunity ? $value->postedByOpportunity->name : ''}}</td>
                                                            @endif
                                                        </tr>
                                                        @if ($value->opportunityapprover)
                                                            <tr>
                                                                <th>Approver</th>
                                                                <td>{{$value->opportunityapprover->employeeid}} &nbsp; {{ $value->opportunityapprover->name}}</td>
                                                            </tr>
                                                        @endif
                                                        @if ($value->closurereviewer)
                                                            <tr>
                                                                <th>Closure Reviewer</th>
                                                                <td>{{$value->closurereviewer->employeeid}} &nbsp; {{ $value->closurereviewer->name}}</td>
                                                            </tr>
                                                        @endif
                                                        @if ($value->approved_at && $value->opportunityapprover)
                                                            <tr>
                                                                <th>Approved by and Date & time</th>
                                                                <td>
                                                                    {{$value->opportunityapprover->employeeid}} &nbsp; {{ $value->opportunityapprover->name}} <br>
                                                                    {{ $value->approved_at }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                        @if ($value->closed_at && $value->closurereviewer)
                                                            <tr>
                                                                <th>Closure Reviewed by and Date & time</th>
                                                                <td>
                                                                    {{$value->closurereviewer->employeeid}} &nbsp; {{ $value->opportunityapprover->name}} <br>
                                                                    {{ $value->closed_at }}
                                                                </td>
                                                            </tr>
                                                        @endif

                                                    </table>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-dark"style="font-size:10px;" data-dismiss="modal">Close</button>
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
                <!-- END Page Content -->
<script>
     function autoResizeDiv(height) {
                document.getElementById('content').style.height = (height) + 'px';
            }
            window.onresize = autoResizeDiv;
            autoResizeDiv(window.innerHeight - 240);
</script>
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

                                    <div class="col-6">
                                        <!-- Products -->
                                        <a class="block block-rounded block-link-shadow bg-body" href="{{ route('opportunity.oppindex') }}">
                                            <div class="block-content text-center">
                                                <i class="far fa-list-alt fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Register
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Products -->
                                    </div>
                                    <div class="col-6">
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/{{ route('opportunity.oppaccomplished') }}">
                                            <div class="block-content text-center">
                                                <i class="fas fa-check-circle fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Accomplished
                                                </p>
                                            </div>
                                        </a>
                                         </div>

                                    <div class="col-6">
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href='{{ route('opportunity.oppanalysis') }}'>
                                            <div class="block-content text-center">
                                                <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Analysis
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Payments -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
             @include('exporting.foropportunity')
       </body>
</html>


@endsection


