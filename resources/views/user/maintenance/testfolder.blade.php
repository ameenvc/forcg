@extends('pages.datatables')

@section('content')

<!doctype html>
<html lang="en">
<head>
<style>
    body {
  overflow-y: auto; 
    }
  </style>

    <body>
          
  
                <!-- END Hero -->
             
                <!-- page content -->
                <div class="content" style="width:100%">
         
                    <!-- Dynamic Table Full -->
                    <div class="block block-rounded" >
                    <div class="block-content block-content-full">
                            <div style="overflow-x:hidden;overflow-y:auto;" >
                       <div class="row gutters-tiny py-3 px-3">
                        @if(count($m)>0)
                        @foreach($m as $department)
                                <div class="col-2">
                                    <!-- Products -->
                                    <a class="block block-rounded block-link-shadow bg-body" href="/user/maintenance/testplan/{{$department}}">
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="far fa-folder-open fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                             <br>{{$department}}
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Products -->
                                </div>
                                @endforeach
                              @endif
                               
                               </div>
                                
                                </div>
                                </div>
                   
                    </div>
                    </div>       
                  
                  



                  
                <!--</div>-->
                <!-- END Page Container -->
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
                                            <a class="block block-rounded block-link-shadow bg-body" href="/user/maintenancemgmt">
                                                <div class="block-content text-center" style="height:130px">
                                                    <i class="far fa-list-alt fa-2x text-primary"></i>
                                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Total Equipment List
                                                    </p>
                                                </div>
                                            </a>
                                            <!-- END Products -->
                                        </div>
                                        <div class="col-6">
                                            <!-- Sales -->
                                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/maintenance/pmfolder">
                                                <div class="block-content text-center"style="height:130px">
                                                    <i class="fas fa-address-card fa-2x text-primary"></i>
                                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                                      PM Plan and Report
                                                    </p>
                                                </div>
                                            </a>
                                            <!-- END Sales -->
                                        </div>
                                        <div class="col-6">
                                            <!-- Payments -->
                                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/maintenance/testfolder">
                                                <div class="block-content text-center" style="height:130px">
                                                    <i class="fas fa-calendar-check fa-2x text-primary"></i>
                                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                                       Test Plan
                                                    </p>
                                                </div>
                                            </a>
                                            <!-- END Payments -->
                                        </div>
                                        <div class="col-6">
                                            <!-- Sales -->
                                            <a class="block block-rounded block-link-shadow bg-body mb-2" href="/user/maintenance/breakfolder">
                                                <div class="block-content text-center" style="height:130px">
                                                    <i class="fas fa-check-circle fa-2x text-primary"></i>
                                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                                       Breakdown Maintenance
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
               
        @endsection 
        
