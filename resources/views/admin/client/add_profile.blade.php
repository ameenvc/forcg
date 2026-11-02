@extends('admin.datatable')
@section('content')
<style>
    .avatar-thumb {
        border: 1px solid #80808059;
        background: #eeeeee36;
        width: 50%;
        height: 8rem;
    }
    .avatar-thumb img {
        padding: 1rem;
        object-fit: contain;
        width: 100%;
        height: 100%;
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <div class="content">
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">Profile</h3>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb" style="margin-left:70px">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="/admin/home">Back</a>
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="block-content block-content-full" style="width:90%;">
                @if(Session::has('msg'))
                    <div class="alert alert-success">
                        <a href="javascript:void(0)" class="close" data-dismiss="alert">×</a>
                        {!!Session::get('msg')!!}
                    </div>
                @endif
                @include('flashMessage.flash_message')
                @if(count($errors) >0 )
                    @foreach($errors->all() as $error)
                        <div class='alert alert-warning'>
                           <a href="javascript:void(0)" class="close" data-dismiss="alert">×</a>
                                {{ $error }}
                        </div>
                    @endforeach
                @endif
                <div class="row">
                    <div class="col-lg-8">
                       <form class="mb-5" method="POST" action="{{url('/admin/uploadclientprofile')}}"  enctype="multipart/form-data" >
                           {{csrf_field()}}
                            <div class="form-group row">
                                <label style="font-size: 13px;" class="col-lg-4 col-form-label" for="company_name">
                                     Company Name <span style="color:red;">*</span></label>
                                     <input type="hidden" name="client_id" value="{{$logos->id}}" id="client_id">
                                <div class="col-lg-8">
                                   <input type="text" class="form-control" style="font-size: 13px;"
                                     name="company_name" id ="company_name" value= "{{ old('company_name') ? old('company_name') : $logos->company_name }}"
                                     placeholder="Please enter company name" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label style="font-size: 13px;" class="col-lg-4 col-form-label" for="logo">
                                     Upload Company Logo <span style="color:red;">*</span></label>
                                <div class="col-lg-8">
                                    <div class="input-group">
                                        <input type="file" class="form-control"
                                                accept="image/png, image/jpeg, image/jpg, image/gif,image/bmp,image/webp"
                                                style="font-size: 13px;"
                                                id="profile_picture"
                                                name="client_logo" required>
                                        <div class="input-group-append">
                                            <input type="submit" value="Upload" class="btn btn-primary" style="font-size:12px;"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                       </form>
                    </div>
                    <div class="col-lg-4">
                       <div class="avatar-thumb">
                            @if($logos)
                                @if($logos->client_logo && file_exists(public_path('/storage/clientlogos/'. $logos->client_logo)))
                                    <img id="imgPreview"
                                        src="{{ asset("storage/clientlogos/". $logos->client_logo) }}"
                                        alt="CompanyLogo">
                                @endif
                            @else
                                <img id="imgPreview"
                                    src="{{ asset('/images/avatar10.jpg') }}"
                                    alt="CompanyLogo">
                            @endif
                        </div>
                    </div>
                </div>
           </div>
       </div>
   </div>
    <!-- END Page Content -->
@endsection
