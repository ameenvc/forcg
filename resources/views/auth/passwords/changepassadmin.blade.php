@extends('admin.nav')
    @section('content')
                <!-- Hero -->
                <div class="bg-body-light">
                    <div class="content content-full">
                   
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0">
                           
                            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-alt">
                                    <li class="breadcrumb-item">Admin</li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="">Change Password</a>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <!-- END Hero -->

                <!-- Page Content -->
                <div class="content">
                    <!-- Basic -->
                    <div class="block block-rounded">
                        <div class="block-header">
                        @if (session('success'))
                                            <div class="alert alert-success" role="alert">
                                            <a class="close" data-dismiss="alert">×</a>
                                                {{ session('success') }}
                                            </div>   
                       @endif
                        @if (session('error'))
                                            <div class="alert alert-danger" role="alert">
                                            <a class="close" data-dismiss="alert">×</a>
                                                {{ session('error') }}
                                            </div>   
                       @endif
                            <h3 class="block-title"></h3>
                        </div>
                        <div class="block-content block-content-full">
                            <form action="{{ route('admin.changepassword') }}" method="POST" enctype="multipart/form-data" onsubmit="return validation()"  id="dropdownForm">
                            {{csrf_field()}}
                            
                            <div class="form-group{{ $errors->has('current-password') ? ' has-error' : '' }}">
                            <label for="new-password" class="col-md-4 control-label" style="font-size:13px">Current Password<span class="text-danger">*</span></label>

                            <div class="col-md-6">
                                <input id="current-password" type="password" style="font-size:13px" class="form-control" name="current-password" required>

                                @if ($errors->has('current-password'))
                                   
                                        <strong style="color:red">{{ $errors->first('current-password') }}</strong>
                                   
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('new-password') ? ' has-error' : '' }}">
                            <label for="new-password"  style="font-size:13px" class="col-md-4 control-label">New Password<span class="text-danger">*</span></label>

                            <div class="col-md-6">
                                <input id="new-password" style="font-size:13px" type="password" class="form-control" name="new-password" required>

                                @if ($errors->has('new-password'))
                                 
                                        <strong style="color:red">{{ $errors->first('new-password') }}</strong>
                                   
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="new-password-confirm" style="font-size:13px" class="col-md-4 control-label">Confirm New Password<span class="text-danger">*</span></label>

                            <div class="col-md-6">
                                <input id="new-password-confirm" type="password" style="font-size:13px" class="form-control" name="new-password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary" style="font-size:13px">
                                    Change Password
                                </button>
                            </div>
                        </div>
                               
                            </form>
                        </div>
                    </div>
                    <!-- END Basic -->

                
                   
                  

                 

                  
                </div>
                <!-- END Page Content -->
          
            <!-- END Main Container -->
        @endsection
 
