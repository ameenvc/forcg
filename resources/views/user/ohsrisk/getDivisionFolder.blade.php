@extends('pages.datatables')
@section('mainpage')
<div class="input-group input-group-sm">
    <a href="/user/ohsrisk/folder/{{$type}}" class="btn btn-secondary" style="font-size:11px;width: 150px;margin-left:9px;">Main Page</a>

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

    </style>

<body>
    <!-- END Hero -->
    <!-- page content -->
    <div class="content" style="width:100%">

        <!-- Dynamic Table Full -->
        <div class="block block-rounded">
            <div class="block-content block-content-full">
                <div class="scrollable" id="content">
                    <div class="row gutters-tiny py-3 px-3">
                        @if(count($divisions)>0)
                        @foreach($divisions as $division) <div class="col-2">
                            <!-- Products -->
                            @php
                            $areas_count = App\Area::where('division_id', $division->id)->count();
                            @endphp
                            @if($type == 'divisional')
                            <a class="block block-rounded block-link-shadow bg-body" href="/user/ohsrisk/subfolder/{{Session::get('objdept')}}/{{$type}}">
                                @elseif($type == 'subdivisional' && $areas_count)
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/ohsrisk/divsionsubdivisionfolder/{{ $division->id }}/{{Session::get('objdept')}}/{{$type}}">
                                    @elseif($type == 'subdivisional' && !$areas_count)
                                    <a class="block block-rounded block-link-shadow bg-body" href="/user/ohsrisk/subfolder/{{Session::get('objdept')}}/{{$type}}">
                                        @endif
                                        <div class="block-content block-content-padding">
                                            <div class="row">
                                                <div class="col icon-style">
                                                    <i class="far fa-folder-open font-medium text-white mt-10 d-flex justify-content-center"></i>
                                                </div>
                                                <div class="col">
                                                    <p class="font-w600 font-size-sm main-text-style">
                                                        {{$division->name}}
                                                    </p>
                                                    <p class="font-w600 font-size-sm gray-text-style">
                                                        sample text
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


</body>


</html>
@endsection
