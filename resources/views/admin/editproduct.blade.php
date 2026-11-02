@extends('admin.datatable')
@section('content')
<!-- Page Content -->
<div class="content">
    <!-- Horizontal -->
    <div class="block block-rounded">     
        <div class="block-header">
            <h3 class="block-title">Edit product/service</h3>
            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="/admin/viewproduct">Back</a>
                    </li>
                </ol>
            </nav>
        </div>                   
        <div class="block-content block-content-full"  style="width:90%;">
            <div class="row">                                
                <div class="col-lg-12 col-sm-12">
                    @foreach($product as $c)
                        <!-- Form Horizontal - Default Style -->
                        <form class="mb-5" method="POST" action="{{url('/admin/updateproduct')}} " onsubmit="return validation()" enctype="multipart/form-data" >
                            {{csrf_field()}}
                            @if(Session::has('msg'))
                                <div class="alert alert-primary">
                                    <a class="close" data-dismiss="alert">×</a>
                                    {!!Session::get('msg')!!}
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-sm-2 mb-2">
                                    <label class="col-form-label" style="font-size: 13px;" for="type">Name</label>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <input type="text" class="form-control" style="font-size: 13px;" id="input01" name="name" value="{{$c->product}}">
                                </div>
                                <div class="col-sm-4 mb-2">
                                    <input type="submit" name="update" class="btn btn-primary" style="font-size:10px;" value="Update">
                                </div>
                                <input type="hidden" name="id" class="xlarge" value="{{$c->id}}">
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