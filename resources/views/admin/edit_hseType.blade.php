@extends('admin.datatable')
@section('content')
    <!-- Page Content -->
    <div class="content">
        @if(Session::has('msg'))
            <div class="alert alert-primary">
                <a class="close" data-dismiss="alert">×</a>
                {!!Session::get('msg')!!}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <a class="close" data-dismiss="alert">×</a>
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif 
        <div class="block block-rounded">  
                   
            <div class="block-header">
                <h3 class="block-title">Edit type</h3>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="/admin/viewhseCategories">Back</a>
                        </li>
                    </ol>
                </nav>
                    
                
            </div>  
                                 
            <div class="block-content block-content-full" style="width:90%;">
                <div class="row">                                
                    <div class="col-lg-12">
                        @foreach($hsetypes as $c)
                            <form class="mb-5" method="POST" action="{{url('/admin/updatehseType')}} " onsubmit="return validation()" enctype="multipart/form-data" >
                               {{csrf_field()}}
                                   
                                <div class="row">
                                    <div class="col-sm-2 mb-2">
                                        <label class="col-form-label" style="font-size: 13px;" for="type">HSE Type</label>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <input type="text" class="form-control" style="font-size: 13px;" id="input01" name="name" value="{{$c->hsetype}}">
                                    </div>
                                    <div class="col-sm-4 mb-2">
                                        <input type="submit" name="update" class="btn btn-primary" style="font-size:10px;" value="Update">
                                    </div>
                                    <input type="hidden" name="id" class="xlarge" value="{{$c->id}}">
                                    <input type="hidden" name="oldname" class="xlarge" value="{{$c->hsetype}}">
                                </div>
                            </form> 
                        @endforeach
                                                
                   </div>
               </div>
           </div>
       </div>
        <!-- END Page Content -->
    </div>
@endsection