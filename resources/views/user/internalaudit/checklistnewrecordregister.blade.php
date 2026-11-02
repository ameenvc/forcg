@extends('pages.datatables')

@section('content')

    <style>
        body {
            overflow-y: auto;

        }

    </style>
    <!-- Page Content -->
    
    <div class="content" style="width:100%;">
        <!-- Dynamic Table Full -->
        <div class="block block-rounded" style="margin-left:-1%;">
           
            <div class="block-content block-content-full">
                <div class="scrollable" style="height:600px;overflow-y:scroll;" id="content">
                @if(count($audits)>0)
                    <table class="move" width="15%" style="margin-left:670px">
                        <tr>
                            <th>
                                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                    <ol class="breadcrumb breadcrumb-alt" style="margin-right:-200px;margin-bottom:-250px;">
                                        <li class="breadcrumb-item" style="margin-left:-595px;margin-top:-8px">Audit Management</li>
                                        <li class="breadcrumb-item" aria-current="page" style="margin-top:-8px">
                                           <a class="link-fx text-dark" href=""> Checklist Library</a>
                                        </li>
                                        <li class="breadcrumb-item" aria-current="page" style="margin-top:-8px">
                                            <a class="link-fx text-dark" href="">Create New Record</a>
                                        </li> 
                                        <li class="breadcrumb-item" aria-current="page" style="margin-top:-8px">
                                            <a class="link-fx" href="">
                                                @if($checklist->division_id!=null)
                                                    <span style="color:#808080;">
                                                        {{ucfirst($listing_name)}}
                                                    </span>
                                                @elseif($checklist->area_id!=null)
                                                <span style="color:#b5651d;">
                                                    {{ucfirst($listing_name)}}
                                                </span>    
                                                @else
                                                <span class="{{$listing_font}}">
                                                        {{ucfirst($listing_name)}}
                                                    </span>
                                                @endif
                                            </a>
                                        </li>
                                        <li class="breadcrumb-item" aria-current="page" style="margin-top:-8px">
                                            <a class="link-fx" href="">
                                             <span style="color:#669933;">
                                                {{ucfirst($checklist->checklist_name)}}
                                             </span>
                                            </a>
                                        </li>                                  
                                        <!-- <li class="breadcrumb-item" aria-current="page" style="margin-top:-8px">
                                            <a class="link-fx" href="{{url('/user/internalaudit/checklistdetails/'.$checklist->id)}}">Back</a>
                                        </li> -->
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
                        <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                        <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                            <thead>
                                <tr>
                                    <!-- <th style="width: 5%;position:sticky;top:-1"></th> -->
                                    <th class="text-center" style="font-size:12px;text-transform:capitalize;position:sticky;top:-1;width:0.5%">#</th>
                                    <th class="text-center" style="font-size:12px;width:10%;text-transform:capitalize;position:sticky;top:-1">Audit ID</th>
                                    <th class="text-center" style="font-size:12px;width:20%;text-transform:capitalize;position:sticky;top:-1">Auditors</th>        
                                    <th class="text-center" style="font-size:12px;text-transform:capitalize;position:sticky;top:-1;width:10%;">Auditee</th>
                                    <th class="text-center" style="font-size:12px;width:15%;text-transform:capitalize;position:sticky;top:-1">Total Records Created</th>                                                            
                                    <th class="text-center" style="font-size:12px;text-transform:capitalize;position:sticky;top:-1">Pending Records</th>                                  
                                    <th class="text-center" style="font-size:12px;text-transform:capitalize;position:sticky;top:-1">Created Records</th>            
                                    <th class="text-center" style="font-size:12px;text-transform:capitalize;position:sticky;top:-1">Percentage of Completion</th>
                                    <th class="text-center" style="font-size:12px;text-transform:capitalize;position:sticky;top:-1;width:15%;"></th>
                                   
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no=0;?>

                                @foreach($audits as $audit)
                                <?php
                                   $records = DB::table('audit_checklist_new_records')
                                                                                  ->where('audit_schedule_id',$audit->id)
                                                                                  ->where('checklist_id',$checklist->id)
                                                                                  ->get();
                                   $completed_records = DB::table('audit_checklist_new_records')
                                                                                ->where('audit_schedule_id',$audit->id)
                                                                                ->where('checklist_id',$checklist->id)
                                                                                ->where('record_status','Completed')
                                                                                ->get();                               
                                   $pendingids = DB::table('audit_checklist_new_records')
                                                                   ->where('audit_schedule_id',$audit->id)
                                                                   ->where('checklist_id',$checklist->id)
                                                                   ->where('record_status','Pending')
                                                                   ->get();
                                      
                                 ?>
                                <tr>
                                    <!-- <td><input class="text-center font-size-sm" type="checkbox" id="{{ $audit->id}}" name="id[]"></td> -->
                                    <td class="text-center" style="font-size:12px;height:30px;">{{$no=$no+1}}</td>
                                    <td class="text-center text-primary font-weight-bold" style="font-size:12px;">{{ $audit->aud_id}} </td>                                 
                                   
                                    <td class="text-center" style="font-size:12px;">{{ $audit->auditors }}</td>
                                    
                                    <td class="text-center" style="font-size:12px;">{{ $audit->contact }}</td>
                                  
                                    <td class="text-center" style="font-size:12px;">
                                       {{$records ? count($records) : '0'}} 
                                    </td>
                                    <td class="text-center" style="font-size:14px;">             
                                      <?php
                                        $pending_records_count = $pendingids ? count($pendingids) : '0';
                                      ?>
                                      @if($pending_records_count==0)
                                      <span class="text-secondary">{{$pending_records_count}}</span>
                                      @else
                                      <span class="text-danger">{{$pending_records_count}}</span>
                                      @endif
                                    </td>                             
                                    <td class="text-center">
                                       @foreach($records as $record)
                                       @if($record->record_status=='Pending')
                                       <span class="badge badge-danger">
                                       <a href="{{route('checklistnewrecordsdisplay',['id'=>$record->id])}}" class="badge badge-danger  font-weight-bold" style="font-size:10px;">
                                         {{$record->audit_checklist_id}}
                                       </a>
                                       </span>
                                       @else
                                       <span class="badge badge-success">
                                       <a href="{{route('checklistnewrecordsdisplay',['id'=>$record->id])}}" class="badge badge-success  font-weight-bold" style="font-size:10px;">
                                         {{$record->audit_checklist_id}}
                                       </a>
                                       </span>
                                       @endif
                                       @endforeach
                                    </td>

                                    <td class="text-center" style="font-size:14px;">
                                       <?php
                                         $total_records = $records ? count($records) : '0';
                                         $completed_records_count=$completed_records ? count($completed_records) : '0';
                                         $completed_status = DB::table('audit_checklist_new_records')
                                                                                                ->where('audit_schedule_id',$audit->id)
                                                                                                ->where('checklist_id',$checklist->id)
                                                                                                ->where('record_status','Pending')
                                                                                                ->value('completed_status');
                                       ?>
                                       @if($total_records==0)
                                       <div class="progress mb-1" style="height: 7px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <p class="fs-xs fw-semibold mb-0">0%</p>  
                                       @elseif($total_records>0)
                                          @if($total_records==$completed_records_count)
                                               <div class="progress mb-1" style="height: 7px;">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <p class="fs-xs fw-semibold mb-0">100%</p>
                                          @elseif($total_records!=$completed_records_count)
                                            <div class="progress mb-1" style="height: 7px;">
                                                <div class="progress-bar bg-success" role="progressbar" style="width:{{$completed_status}};" aria-valuenow="{{$completed_status}}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <p class="fs-xs fw-semibold mb-0">{{$completed_status}}</p>
                                          @endif   
                                        @endif                                     
                                    </td>

                                    <td class="text-center " style="font-size:10px;">                               
                                       @if($total_records==0)
                                          <a href="{{route('createchecknewrecord',['id'=>$checklist->id,'auditid'=>$audit->id])}}" class="button btn btn-secondary bt-sm  font-weight-normal" style="font-size:12px;font-family:Arial, Helvetica, sans-serif;">Create Record</a> 
                                       @elseif($total_records>0)
                                          @if($total_records==$completed_records_count)
                                          <a href="{{route('checklistnewrecordsdisplay',['id'=>$record->id])}}" class="button btn btn-primary bt-sm  font-weight-normal" style="font-size:12px;font-family:Arial, Helvetica, sans-serif;">View</a> 
                                          @elseif($total_records!=$completed_records_count)
                                             @foreach($pendingids as $pendingid)
                                             <a href="{{route('checklistnewrecordsdisplay',['id'=>$pendingid->id])}}" class="button btn btn-warning bt-sm  font-weight-normal" style="font-size:12px;font-family:Arial, Helvetica, sans-serif;">Resume</a> 
                                             @endforeach
                                          @endif
                                       @endif 
                                    </td>                                
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                            <div class="text-danger text-center">
                                No audit scheduled for this department
                            </div>                   
                     @endif
               
                </div>
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
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/checklistlibrary">
                                    <div class="block-content text-center">
                                        <i class="far fa-list-alt fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Checklist Library
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
    </div>
    <!-- END Apps Modal -->
@endsection
