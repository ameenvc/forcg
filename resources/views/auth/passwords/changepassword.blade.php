@extends('layouts.backend')    
@section('content')
  <!-- Hero -->
  <div class="bg-body-light">
                    <div class="content content-full">
                   
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0">
                           
                            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-alt">
                                    <li class="breadcrumb-item">User</li>
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
                    <form class="form-horizontal" method="POST" action="{{ route('user.changepassword') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('current-password') ? ' has-error' : '' }}">
                            <label for="new-password" class="col-md-4 control-label" style="font-size:13px">Current Password  <span class="text-danger">*</span> </label>

                            <div class="col-md-6">
                                <input id="current-password" type="password" class="form-control" style="font-size:13px" name="current-password" required>

                                @if ($errors->has('current-password'))
                                    <span class="help-block">
                                        <strong style="color:red">{{ $errors->first('current-password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('new-password') ? ' has-error' : '' }}">
                            <label for="new-password" class="col-md-4 control-label" style="font-size:13px">New Password  <span class="text-danger">*</span> </label>

                            <div class="col-md-6">
                                <input id="new-password" type="password" class="form-control" style="font-size:13px" name="new-password"  pattern="(?=.*\d)(?=.*[A-Za-z]).{6,}" required>
                                <!-- <p style="font-size:11px;">Password must contain minimum of 6 letters and at least 1 digit.</p>  -->
                                @if ($errors->has('new-password'))
                                    <span class="help-block">
                                        <strong style="color:red">{{ $errors->first('new-password') }}</strong>
                                    </span>
                                 

                                @endif
                            </div>
                            
                        </div>

                        <div class="form-group">
                            <label for="new-password-confirm" class="col-md-4 control-label"  style="font-size:13px">Confirm New Password  <span class="text-danger">*</span> </label>

                            <div class="col-md-6">
                                <input id="new-password-confirm" type="password" class="form-control" style="font-size:13px" name="new-password_confirmation" required>
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
@endsection
 <!-- END Basic -->