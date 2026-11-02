@extends('admin.datatable')

@section('content')
    <!-- Page Content -->
    <div class="content">
        <!-- Horizontal -->
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">Edit iso clauses</h3>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="/admin/viewCategories">Back</a>
                        </li>
                    </ol>
                </nav>


            </div>
            <div class="block-content block-content-full"  style="width:90%;">
                <div class="row">
                    <div class="col-lg-12 col-sm-12">
                        <form class="mb-5" method="POST" action="{{url('/admin/updateisoclauses')}} " onsubmit="return validation()" enctype="multipart/form-data" >
                            {{csrf_field()}}
                            @if(count($datas)>0)
                            @foreach($datas as $dat)
                            @if(Session::has('msg'))
                                <div class="alert alert-primary">
                                    <a class="close" data-dismiss="alert">×</a>
                                    {!!Session::get('msg')!!}
                                </div>
                            @endif
                            <div class="row mb-4">
                                <div class="col-sm-3">
                                    <label class="col-form-label" style="font-size: 13px;" for="type">Department name</label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" id="dept1"  name="dept1" value= "{{ request()->get('dept') }}"  readonly="" style="font-size: 13px;" class="form-control" >
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="form-group col-sm-3">
                                    <label class="col-form-label" style="font-size: 13px;" for="type">Edit iso clauses</label>                                  
                                </div>
                                <div class="col-sm-9">
                                    <textarea id="isoclauses"  name="isoclauses"   style="font-size: 13px;" rows="10" cols="5" class="form-control" >{{ $isoclauses }}</textarea>
                                    <small class="text-danger">please avoid using "~" character in Your text</small>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <input type="submit" name="update" class="btn btn-primary" style="font-size:10px;" value="Update">
                                </div>
                                <input type="hidden"  name="id" id="id"  value= "{{ request()->get('val') }}">
                            </div>
                            @endforeach
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection