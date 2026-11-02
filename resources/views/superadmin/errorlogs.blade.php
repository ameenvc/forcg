@extends('superadmin.datatable')

@section('content')
  <!-- Main Container -->

                  <!-- END Hero -->
                  <div class="bg-body-light">
                    <div class="content content-full">
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
                           
                            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-alt">
                                <li class="breadcrumb-item">Error Logs</li>
                                    <li class="breadcrumb-item" aria-current="page">
                                    <a id="a1" onclick="" class="link-fx" href="/superadmin/home">Back</a>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <!-- END Hero -->
                    
                            <?php $next=0; ?>
                            @if(count($error)>0) 
                            <?php $i=0; ?>
                            <!-- Page Content -->
                <div class="content"  style="width:92%;margin-left:40px">
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
                                            <th style="font-size:10px;" class="d-none d-sm-table-cell text-center">Error</th>
                                            <th style="font-size:10px;" class="d-none d-sm-table-cell text-center">Logged Date</th>
                                            <th style="font-size:10px;" class="text-center" style="width:80px;">Occurence</th>
                                            <th style="font-size:10px;" class="text-center" >View Full Error</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                
                                    
                                    @foreach($error as $er)
                                        <tr>
                                            <td class="text-center font-size-sm" style="font-size:10px;">
                                                    <span style="font-size:10px;">{{$i+=1}}</span>
                                            </td>
                                            <?php
                                            $err=substr($er->error,0,strpos($er->error,'.php'));
                                            ?>
                                            <td style="font-size:10px;" class="text-center font-size-sm" style="word-break:break-all;max-width:1%;">
                                                    <span  style="font-size:10px;">{{$err}}{{__('.....')}}</span>
                                            </td>
                                            <td style="font-size:10px;" class="text-center font-size-sm">
                                                    <span style="font-size:10px;">{{$er->logged_date}}</span>
                                            </td>
                                            
                                            <td style="font-size:10px;" class="text-center font-size-sm">
                                                    <span style="font-size:10px;">{{$er->occurence}}</span>
                                            </td>
                                            <td style="font-size:10px;" class="text-center font-size-sm">
                                            <button class="btn btn-sm btn-alt-success" data-id="{{ $er->id }}" data-target="#viewModal{{$er->id}}"  data-toggle="modal" title="View">
                                                    <i class="fa fa-fw fa-eye" style="font-size:10px;"></i>
                                                </button>
                                            </td>
            <div class="modal fade" id="viewModal{{$er->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-block-fadein" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="block block-rounded block-themed block-transparent mb-0">
                        <div class="block-header bg-primary-dark">
                            <h3 class="block-title">Error in Full View</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                    <i class="fa fa-fw fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content font-size-sm">
                        <div class="table-responsive">
                        <span style="background-color:yellow;" style="font-size:10px;">{{$er->error}}</span>
                            </div>
                        </div>
                        <div class="block-content block-content-full text-right border-top">
                            <button type="button" class="btn btn-alt-primary mr-1" data-dismiss="modal" style="font-size:10px;">Close</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal" style="font-size:10px;">Ok</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Fade In Block Modal -->
                                            
                                        </tr>
                                    @endforeach
                                  
                                    </tbody>
                            </table>
                            @else
                            No Data
                            @endif
                        </div>
                    </div>
                    <!-- END Dynamic Table Full -->

                   
                </div>
                <!-- END Page Content -->  
       
       
            <!-- END Main Container -->

@endsection



