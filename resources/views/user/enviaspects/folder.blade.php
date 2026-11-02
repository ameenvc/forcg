@extends('layouts.multiselect')
@section('exportadd')
<div class="input-group input-group-sm">
    <a type="button" href="{{url('/user/enviaspects/add')}}" class="btn btn-sm btn-dual" style="margin-left:0px;">
        <i class="fa fa-1x fa-plus" style="color:grey;"></i>
    </a>
</div>
@endsection
@section('content')
<style>
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
        color: gray;
    }

    .font-medium {
        font-size: 1.5em;
    }
</style>
<!-- page content -->
<div class="content" style="width:100%">
    <!-- Dynamic Table Full -->
    <div class="block block-rounded">
        <div class="block-content block-content-full">
            <div class="row gutters-tiny py-3 px-3">
                @if(count($departments)>0)
                @foreach($departments as $department)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                    <!-- Products -->
                    <a class="block block-rounded block-link-shadow bg-body h-100" href="/user/enviaspects/listofaspects/{{$department->departmentname}}">
                        <div class="block-content block-content-padding">
                            <div class="row">
                                <div class="col icon-style">
                                    <i class="{{$department->icon}} font-medium text-white mt-10 d-flex justify-content-center"></i>
                                </div>
                                <div class="col">
                                    <p class="font-w600 font-size-sm main-text-style">
                                        {{$department->departmentname}}
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
<!-- end of page content -->
@endsection
