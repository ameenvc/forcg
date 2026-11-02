@extends('layouts.backend')
@section('back')
<button type="button" style="width:34px;" class="btn btn-sm btn-dual mr-2">
    <a href="{{route('management.meetings')}}"><i class="fa fa-arrow-left" style="color:black;"></i></a>
</button>
@endsection

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">Management Review</li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="">Analysis</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<!-- Page Content -->
<div class="content" style="padding-top:30px;width:96%;margin-left:25px;">
    <div class="row">
        <div class="col-lg-12 text-danger text-center" style="font-size:14px;">
            No analysis created for management review
        </div>
    </div>
</div>
<script>
    function autoResizeDiv(height) {
        document.getElementById('content').style.height = (height) + 'px';
    }
    window.onresize = autoResizeDiv;
    autoResizeDiv(window.innerHeight - 100);
</script>
@endsection