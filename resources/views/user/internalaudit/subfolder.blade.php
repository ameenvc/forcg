@extends('pages.datatables')
@section('content')
<!doctype html>
<html lang="en">
<head>
<style>
    body {
  overflow-y: hidden; 
    }
  </style>

    <body>
          
  
                <!-- END Hero -->
             
                <!-- page content -->
                <div class="content" style="width:100%">
         
                    <!-- Dynamic Table Full -->
                    <div class="block block-rounded" >
                    <div class="block-content block-content-full">
                            <div style="height:450px;" >
                       <div class="row gutters-tiny py-3 px-3">
                            @if(count($years)>0)
                             @for($i=0;$i<count($years);$i++)
                                <div class="col-2">
                                    <!-- Products -->
                                    <a class="block block-rounded block-link-shadow bg-body" href="/user/internalaudit/auditschedule/{{Session::get('auditdept')}}/{{$years[$i]}}">
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="far fa-folder-open fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                             <br>{{$years[$i]}}
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Products -->
                                </div>
                                @endfor
                              @endif
                             
                                </div>
                                
                                </div>
                                <!--</div>-->
                   
                    </div>
                    </div>
                    <!-- end of page content -->
<!-- Footer -->
 

<!-- END Footer -->
          
            <!-- END Footer -->

            <!-- Apps Modal -->
            <!-- Opens from the modal toggle button in the header -->
            <!-- END Apps Modal -->
           

    </body>
   
 
</html>
@endsection

      





