@extends('pages.datatables')
@section('exportadd')
<div class="input-group input-group-sm">
    <a type="button" href="{{route('addohsrisk', ['type' => $type])}}" class="btn btn-sm btn-dual" style="margin-left:0px;" style="">
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
            overflow-y: auto;
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

    </style>

<body>


    <!-- END Hero -->

    <!-- page content -->
    <div class="content" style="width:100%">

        <!-- Dynamic Table Full -->
        <div class="block block-rounded">
            <div class="block-content block-content-full">
                <div style="overflow-x:hidden;oveflow-y:auto;">
                    <div class="row gutters-tiny py-3 px-3">
                        @if(count($departments)>0)
                        @foreach($departments as $department)
                        <div class="col-md-4 col-sm-6 col-lg-3">
                            @php
                            $divisions_count = App\Division::where('department_id', $department->id)->count();
                            @endphp
                            <!-- Products -->

                            @if(($type == 'divisional' || $type == 'subdivisional') && $divisions_count > 0)
                            <a class="block block-rounded block-link-shadow bg-body" href="/user/ohsrisk/divisionsubfolder/{{$department->departmentname}}/{{$type}}">
                                @else
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/ohsrisk/subfolder/{{$department->departmentname}}/{{$type}}">
                                    @endif

                                    <div class="block-content block-content-padding">
                                        <div class="row">
                                            <div class="col icon-style">
                                                <i class="{{$department->icon}} font-medium text-white mt-10 d-flex justify-content-center"></i>
                                            </div>
                                            <div class="col">
                                                <p class="font-w600 font-size-sm main-text-style">
                                                    {{$department->departmentname}}
                                                </p>
                                                <p class="small-font gray-text-style">
                                                    Total {{ $department->numberOfLocations}}, {{ $department->update_date != 'no review' ? 'Reviewed on' : ''}} {{ $department->update_date }}
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
