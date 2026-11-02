@extends('pages.datatables')
@section('mainpage')
<div class="input-group input-group-sm">
    <a href="{{ url('/user/objectives/folders') }}" class="btn btn-secondary" style="font-size:11px;width: 150px;margin-left:9px;">Main Page</a>
</div>
@endsection
@section('content')
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

</style>
<!-- END Hero -->
<!-- page content -->
<div class="content" style="width:100%">
    <!-- Dynamic Table Full -->
    <div class="block block-rounded">
        <div class="block-header">
            <h3 class="block-title">
                Division objectives
            </h3>
        </div>

        <div class="block-content block-content-full">
            <div style="overflow-x:hidden;overflow-y:auto;" id="content">
                <div class="row gutters-tiny py-3 px-3">
                    <div class="col-md-4 col-sm-6 col-lg-3">
                        <a class="block block-rounded block-link-shadow bg-body" href="/user/objectives/listofobjectives/{{Session::get('objdept')}}/divisional/{{$division}}">
                            <div class="block-content block-content-padding">
                                <div class="row">
                                    <div class="col icon-style">
                                        <i class="far fa-folder-open font-medium text-white mt-10 d-flex justify-content-center"></i>
                                    </div>
                                    <div class="col">
                                        <p class="font-w600 font-size-sm main-text-style">
                                            {{ $division_name }}
                                        </p>
                                        <p class="font-w600 font-size-sm gray-text-style">
                                            sample text
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <div class="block-header">
            <h3 class="block-title">
                Sub-Divisions objectives
            </h3>
        </div>

        <div class="block-content block-content-full">
            <div style="overflow-x:hidden;overflow-y:auto;" id="content">
                <div class="row gutters-tiny py-3 px-3">
                    @if(count($subdivisions)>0)
                    @foreach($subdivisions as $subdivision) 
                    <div class="col-md-4 col-sm-6 col-lg-3">
                        <a class="block block-rounded block-link-shadow bg-body" href="/user/objectives/listofobjectives/{{Session::get('objdept')}}/subdivisional/{{$division}}/{{ $subdivision->id}}">
                            <div class="block-content block-content-padding">
                                <div class="row">
                                    <div class="col icon-style">
                                        <i class="far fa-folder-open font-medium text-white mt-10 d-flex justify-content-center"></i>
                                    </div>
                                    <div class="col">
                                        <p class="font-w600 font-size-sm main-text-style">
                                            {{$subdivision->name}}
                                        </p>
                                        <p class="font-w600 font-size-sm gray-text-style">
                                            sample text
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </a>
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
@endsection
