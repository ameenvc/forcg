@extends('admin.datatable')
@section('content')
<!-- Page Content -->
<div class="content">
    <div class="block block-rounded">
        <div class="block-header">
            <h3 class="block-title">Edit evaluation result</h3>
            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="/admin/vendorEvaluationResult">Back</a>
                    </li>
                </ol>
            </nav>
        </div>
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    @foreach($eval as $c)
                    <form class="mb-5" method="POST"action="{{url('/admin/updateres')}}" onsubmit="return validation()" enctype="multipart/form-data" >
                        {{csrf_field()}}
                        @if(Session::has('msg'))
                            <div class="alert alert-primary">
                                <a class="close" data-dismiss="alert">×</a>
                                {!!Session::get('msg')!!}
                            </div>
                        @endif
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="type">Percentage</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control"  style="font-size: 13px;"    id="percent" name="percent" value= "{{$c->percent}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="type">Result</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control"  style="font-size: 13px;"    id="score"  name="result" readonly value="{{ $c->result}}">
                                </div>
                            </div>
                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="type">Action Comment</label>
                                <div class="col-sm-12">
                                    <textarea type="text" rows="4" class="form-control" id="comment" style="font-size: 13px;"  name="comment" required >{{ $c->comment }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12 text-center">
                                <input type="submit" name="update" class="btn btn-primary" style="font-size:10px;" value="Update">
                            </div>
                            <input type="hidden"   name="id" class = "xlarge" value= "{{$c->id}}">
                        </div>
                    </form>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END Page Content -->
@endsection