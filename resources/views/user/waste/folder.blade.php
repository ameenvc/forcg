@extends('pages.datatables')
@section('exportadd')
<!--<div class="input-group input-group-sm">
    <a type="button" href="{{url('/user/internalaudit/addaudit')}}" class="btn btn-sm btn-dual" style="margin-left:0px;" style="">
        <i class="fa fa-1x fa-plus" style="color:grey;"></i>
    </a>
</div>-->
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

<body>
    <!-- END Hero -->

    <!-- page content -->
    <div class="content" style="width:100%">

        <!-- Dynamic Table Full -->
        <div class="block block-rounded">
            <div class="block-content block-content-full">
                <div id="content">
                    <div class="row gutters-tiny py-3 px-3">
                        @if(count($years)>0)
                        <?php
                          for($i=0;$i<count($years);$i++) 
                          {
                        ?>
                         @if($years[$i]>0)
                        <div class="col-2">                  
                            <a class="block block-rounded block-link-shadow bg-body" href="/user/waste/transferschedule/{{$years[$i]}}">
                                <div class="block-content text-center" style="height:130px">
                                    <i class="far fa-folder-open fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        <br>{{$years[$i]}}
                                    </p>
                                </div>
                            </a>            
                        </div>
                        @endif
                    <?php } ?>
                    @else
                    <div class="col-md-12" style="font-size:16px;color:red;margin-left:600px;">No details to display</div>
                    @endif

                </div>

            </div>
            <!--</div>-->

        </div>
    </div>
    <!-- end of page content -->
    <!-- Footer -->
    <script>
         function autoResizeDiv(height) {
            document.getElementById('content').style.height = (height) + 'px';
        }
        window.onresize = autoResizeDiv;
        autoResizeDiv(window.innerHeight - 240);
    </script>

    <!-- END Footer -->

    <!-- END Footer -->

    <!-- Apps Modal -->
    <!-- Opens from the modal toggle button in the header -->
    <!-- END Apps Modal -->
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
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/waste/register">
                                    <div class="block-content text-center">
                                        <i class="far fa-list-alt fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Waste Management
                                        </p>
                                    </div>
                                </a>
                                <!-- END Products -->
                            </div>
                            <div class="col-6">
                                <!-- Products -->
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/waste/register">
                                    <div class="block-content text-center">
                                        <i class="far fa-list-alt fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Waste / Scrap List
                                        </p>
                                    </div>
                                </a>
                                <!-- END Products -->
                            </div>
                            <div class="col-6">

                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/waste/transferrequest">
                                    <div class="block-content text-center">
                                        <i class="fas fa-clone fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Transfer Request
                                        </p>
                                    </div>
                                </a>

                            </div>
                            
                                    <div class="col-6">
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/waste/analysis">
                                            <div class="block-content text-center">
                                                <i class="si si-wallet fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Analysis
                                                </p>
                                            </div>
                                        </a>
                                    </div>
                           
                        </div>
                    </div>
                </div>
</body>

</html>
@endsection
