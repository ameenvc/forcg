@extends('admin.datatable')
@section('content')
    <!-- Page Content -->
    <div class="content">
        <!-- Horizontal -->
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">Edit</h3>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">

                        
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="/admin/wastedesc">Back</a>
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="block-content block-content-full" style="width:90%;">
                <div class="row">
                    <div class="col-lg-10">
                        <!-- Form Horizontal - Default Style -->
                        @foreach($waste as $w)
                        <form class="mb-5" method="POST" action="{{url('/admin/updatewaste')}}" enctype="multipart/form-data">
                            {{csrf_field()}}
                            @if(Session::has('msg'))
                            <div class="alert alert-primary">
                                <a class="close" data-dismiss="alert">×</a>
                                {!!Session::get('msg')!!}
                            </div>
                            @endif
                            <div class="row mb-4">
                                <div class="col-sm-3 ">
                                    <label class="col-form-label" style="font-size: 13px;" for="stage"> Waste Type</label>
                                    
                                </div>
                                <div class="col-sm-9">
                                    <select class="form-control" id="type" name="type" style="font-size:13px;width:100%;">
                                        <option>select type</option>
                                        @foreach($wastetypes as $type)
                                        <option value="{{ $type->name}}" {{ $type->name == $w->waste_type ? 'selected' : ''}}> {{ $type->name }}</option><br>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-sm-3">
                                    <label class="col-form-label" for="classification" style="font-size: 13px;">Waste description</label>
                                    
                                </div>
                                <div class="col-sm-9">
                                    <textarea type="text" class="form-control" rows="5" style="font-size: 13px;" id="desc" name="desc">{{$w->wastedesc}}</textarea>

                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <input type="hidden" name="id" class="xlarge" value="{{$w->id}}">

                                    <input type="submit" name="update" class="btn btn-primary" style="font-size:10px;" value="Update">
                                </div>
                            </div>
                        </form>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection