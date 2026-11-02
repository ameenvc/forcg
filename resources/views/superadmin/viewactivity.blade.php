@extends('superadmin.datatable')

@section('content')
  <!-- Main Container -->

<div class="bg-body-light">
                    <div class="content content-full">
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
                           
                            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-alt">
                                <li class="breadcrumb-item">Admin/Users Activities</li>
                                    <li class="breadcrumb-item" aria-current="page">
                                    <a id="a1" onclick="" class="link-fx" href="/superadmin/home">Back</a>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <!-- END Hero -->

              
                @if(count($activities)>0)                                                                                 <!-- Page Content -->
                <div class="content"  style="width:92%;margin-left:50px;">
                    <!-- Dynamic Table Full -->
                    <div class="block block-rounded" >
                        {{-- <div class="block-header">
                            <h3 class="block-title">Dynamic Table <small>Full</small></h3>
                        </div> --}}
                        <div class="block-content block-content-full" style=" overflow-y:scroll;height:450px;display:block;">
                            <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->                            <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                                <thead>
                                        <tr>
                                            <th style="font-size:10px;" class="text-center" style="width: 100px;">SL No.</th>
                                            <th style="font-size:10px;" class="d-none d-sm-table-cell text-center">Name</th>
                                            <th style="font-size:10px;" class="d-none d-sm-table-cell text-center">Email</th>
                                            <th style="font-size:10px;" class="text-center" >Type</th>
                                            <th style="font-size:10px;" class="text-center" >Date</th>
                                            <th style="font-size:10px;" class="text-center" >Time</th>
                                            <th style="font-size:10px;" class="text-center" >Activity</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i=0; ?>
                                    @foreach($activities as $activity)
                                        <tr>
                                            <td class="text-center font-size-sm">
                                                    <span style="font-size:10px;">{{$i+=1}}</span>
                                            </td>
                                            <td class="text-center font-size-sm">
                                                    <span style="font-size:10px;">{{$activity->name}}</span>
                                            </td>
                                            <td class="text-center font-size-sm">
                                                    <span style="font-size:10px;">{{$activity->email}}</span>
                                            </td>
                                            <td class="text-center font-size-sm">
                                                    <span style="font-size:10px;">{{$activity->type}}</span>
                                            </td>
                                            <td class="text-center font-size-sm">
                                                    <span style="font-size:10px;">{{$activity->date}}</span>
                                            </td>
                                            <td class="text-center font-size-sm">
                                                    <span style="font-size:10px;">{{$activity->time}}</span>
                                            </td>
                                            <td class="text-center font-size-sm">
                                                    <span style="font-size:10px;">{{$activity->activity}}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    
                                    </tbody>
                            </table>
                            @endif
                        </div>
                    </div>
                    <!-- END Dynamic Table Full -->

                   
                </div>
                <!-- END Page Content -->
           
            <!-- END Main Container -->

@endsection