@extends('pages.datatables')
@section('exportadd')
<div class="input-group input-group-sm">
    <a type="button" href="{{url('/user/objectives/addobjectives/'. $type)}}" class="btn btn-sm btn-dual" style="margin-left:0px;" style="">
        <i class="fa fa-1x fa-plus" style="color:grey;"></i>
    </a>
</div>
@endsection
@section('content')
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
<div class="content" style="width:100%">
    @if(Session::has('errmsg'))
        <div class="alert alert-danger">
            <a class="close" data-dismiss="alert">×</a>
            {!!Session::get('errmsg')!!}
        </div>
    @endif
    @if(Session::has('msg'))
        <div class="alert alert-primary">
            <a class="close" data-dismiss="alert">×</a>
            {!!Session::get('msg')!!}
        </div>
    @endif
    <!-- Dynamic Table Full -->
    <div class="block block-rounded">
        <div class="block-header">
            <h3 class="block-title">
                @if($type == null)
                Departmental Objectives
                @elseif($type == 'divisional')
                Divisional objectives
                @elseif($type == 'subdivisional')
                SubDivisional Objectives
                @endif
            </h3>
        </div>
        <div class="block-content block-content-full">
            <div style="overflow-x:hidden;overflow-y:auto;" id="content">
                <div class="row gutters-tiny py-3 px-3">
                    @if(count($departments)>0)
                        @foreach($departments as $department)
                            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                @php
                                    $divisions_count = App\Division::where('department_id', $department->id)->count();
                                @endphp
                                @if($divisions_count > 0)
                                <a class="block block-rounded block-link-shadow bg-body" href="{{ $department->numberOfObjectives ? '/user/objects/getsubdivsionfolder/'.$department->departmentname.'/'.$type : '#' }}">
                                @else
                                <a class="block block-rounded block-link-shadow bg-body" href="{{ $department->numberOfObjectives ? '/user/objectives/listofobjectives/'.$department->departmentname : '#'}}">
                                @endif
                                    <div class="block-content block-content-padding">
                                        <div class="row">
                                            <div class="col-auto icon-style">
                                                <i class="{{$department->icon}} font-medium text-white mt-10 d-flex justify-content-center"></i>
                                            </div>
                                            <div class="col">
                                                <p class="font-w600 font-size-sm main-text-style">
                                                    {{$department->departmentname}}
                                                </p>
                                                <p class="small-font gray-text-style">
                                                    Total {{ $department->numberOfObjectives}}, {{ $department->update_date != 'no report' ? 'Updated on' : ''}} {{ $department->update_date }}
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
<script>
    function autoResizeDiv(height) {
        document.getElementById('content').style.height = (height) + 'px';
    }
    window.onresize = autoResizeDiv;
    autoResizeDiv(window.innerHeight - 240);

</script>
@endsection
