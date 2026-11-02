@extends('pages.datatables')
@section('content')
<!doctype html>
<html lang="en">
<head>
    <style>
        body {
            overflow-y: hidden;
        }

        .block-content-padding {
            padding: 1rem 1.25rem 15px !important;
        }

        .icon-style {
            flex-grow: 0 !important;
            background: #147CC7;
            border-radius: 10%;
            padding-bottom: 5px;
            width: 49px;
            max-width: 49px;
        }


        .mt-10 {
            margin-top: 10px !important;
        }

        .main-text-style {
            margin-bottom: 0 !important;
        }

        .gray-text-style {
            margin-bottom: 0 !important;
            color: gray
        }

        .font-medium {
            font-size: 1.5em;
        }

        .small-font {
            font-size: 12px;
        }

        .tooltip-inner {
            background-color: lightgray !important;
            color: #000 !important;
            font-size: 12px !important;
        }

    </style>

<body>


    <!-- END Hero -->

    <!-- page content -->
    <div class="content" style="width:100%">

        <!-- Dynamic Table Full -->
        <div class="block block-rounded">
            <div class="block-content block-content-full">
                <div class="scrollable" style="height:450px;">
                    <div class="row gutters-tiny py-3 px-3">
                        @if(count($locations)>0)
                        @foreach($locationsNew as $key => $location) <div class="col-md-4 col-sm-6 col-lg-3 ">
                            <!-- Products -->
                            @php
                            $ohs_dept = Session::get('ohsdept');
                            if($type) {
                            $url = "/user/ohsrisk/list/". $ohs_dept. "/". $key. "/". $type;
                            } else {
                            $url = "/user/ohsrisk/list/". $ohs_dept. "/". $key;
                            }

                            @endphp
                            <a class="block block-rounded block-link-shadow bg-body" href="{{$url}}">

                                <div class="block-content block-content-padding">
                                    <div class="row">
                                        <div class="col icon-style">
                                            <i class="far fa-folder-open font-medium text-white mt-10 d-flex justify-content-center"></i>
                                        </div>
                                        <div class="col">

                                            {{-- <p class="font-w600 font-size-sm main-text-style" data-toggle="tooltip" data-placement="top" title=" {{$location[0] !== '-' ? $location[0] : $key }}">
                                            {{$location[0] !== '-' ? substr($location[0], 0, 13). ( strlen($location[0]) >= 14 ? '...' : '') : substr($key, 0, 13). ( strlen($key) >= 14 ? '...' : '') }}
                                            </p> --}}
                                            <p class="font-w600 font-size-sm main-text-style" data-toggle="tooltip" data-placement="top" title=" {{$location[0]}}">
                                                {{ substr($location[0], 0, 10). ( strlen($location[0]) >= 11 ? '...' : '') }}
                                            </p>

                                            <p class="small-font gray-text-style">
                                                Total {{ $location[1] }}
                                            </p>
                                        </div>
                                    </div>

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
@section('js_after')
<script>
    $(document).ready(function() {
        $("p").tooltip();
    });

</script>
@endsection
