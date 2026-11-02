@extends('layouts.simple')
    @section('content')       
@if(Session::get('verificationemail')=="")
    <!-- Page Content -->
    <div class="hero-static">
        <div class="content">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-4">
                    <!-- Sign In Block -->
                    <div class="block block-rounded block-themed mb-0">
                        <div class="block-header bg-primary-dark">
                            <h3 class="block-title">Forgot Password</h3>
                            <div class="block-options">
                                
                                <a class="btn-block-option font-size-sm" href="{{ route('login') }}">Sign In</a>
                               
                                @if(Session::get('curcompsize')>=Session::get('compsize'))
                                <a class="btn-block-option" href="{{ route('blockregister') }}"  data-toggle="tooltip" data-placement="left" title="New Account">
                                    <i class="fa fa-user-plus"></i>
                                </a>
                                @else
                                <a class="btn-block-option" href="user/register"  data-toggle="tooltip" data-placement="left" title="New Account">
                                    <i class="fa fa-user-plus"></i>
                                </a>
                                @endif
                            </div>
                        </div>
                        <div class="block-content">
                            <div class="p-sm-3 px-lg-4 py-lg-5">
                            {{-- @if(Session::get('app_name')=='quickms_logo.png')
                            <h1 class="h2 mb-1">QuickMS</h1>
                            @elseif(Session::get('app_name')=='quickcg_logo.png')
                            <h1 class="h2 mb-1">QuickCG</h1>
                            @else --}}
                            <h1 class="h2 mb-1">{{ config('app.name') }}</h1>
                            {{-- @endif --}}
                                <p class="text-muted">
                                Please provide Email-ID.
                                </p>
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                        <a class="close" data-dismiss="alert">×</a>
                            {{ session('error') }}
                        </div>
                    @endif
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                        <a class="close" data-dismiss="alert">×</a>
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('verifyemail') }}">
                        @csrf

                        <div class="py-3">
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-alt form-control-lg @error('email') is-invalid @enderror" value="{{ old('email') }}" id="email" name="email" required autocomplete="email" autofocus placeholder="Enter Email-ID">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                
                            </div>
                        <div class="form-group row">
                            <div class="col-md-6 col-xl-12">
                                <button type="submit" class="btn btn-block btn-alt-primary">
                                     {{ __('Send Verification Code') }}
                                </button>
                            </div>
                         </div>
                    </form>
                    </div>
                                    </div>
                                </div>
                                <!-- END Sign In Block -->
                            </div>
                        </div>
                    </div>
                </div>
@else
<!-- Page Content -->
<div class="hero-static">
        <div class="content">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-4">
                    <!-- Sign In Block -->
                    <div class="block block-rounded block-themed mb-0">
                        <div class="block-header bg-primary-dark">
                            <h3 class="block-title">Forgot Password</h3>
                            <div class="block-options">
                            <a class="btn-block-option font-size-sm" href="{{ route('login') }}">Sign In</a>

                                @if(Session::get('curcompsize')>=Session::get('compsize'))
                                <a class="btn-block-option" href="{{ route('blockregister') }}"  data-toggle="tooltip" data-placement="left" title="New Account">
                                    <i class="fa fa-user-plus"></i>
                                </a>
                                @else
                                <a class="btn-block-option" href="user/register"  data-toggle="tooltip" data-placement="left" title="New Account">
                                    <i class="fa fa-user-plus"></i>
                                </a>
                                @endif
                            </div>
                        </div>
                        <div class="block-content">
                            <div class="p-sm-3 px-lg-4 py-lg-5">
                            {{-- @if(Session::get('app_name')=='quickms_logo.png')
                            <h1 class="h2 mb-1">QuickMS</h1>
                            @elseif(Session::get('app_name')=='quickcg_logo.png')
                            <h1 class="h2 mb-1">QuickCG</h1>
                            @else --}}
                            <h1 class="h2 mb-1">{{ config('app.name') }}</h1>
                            {{-- @endif --}}
                                <p class="text-muted">
                                    Please Enter Verification code.
                                </p>
              
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                        <a class="close" data-dismiss="alert">×</a>
                            {{ session('status') }}
                        </div>
                    @endif
                    @if(Session::has('msg'))
                        <div class="alert alert-danger">
                            <a class="close" data-dismiss="alert">×</a>
                            {!!Session::get('msg')!!}
                        </div>
                     @endif
                    <form method="POST" action="{{ route('verifyemail') }}">
                        @csrf
                        <div class="py-3">
                                <div class="form-group">
                                    <input type="email" readonly class="form-control form-control-alt form-control-lg @error('email') is-invalid @enderror" value="{{session::get('verificationemail')}}" id="email" name="email" required autocomplete="email" autofocus >
                                </div>  
                            </div>
                            <div class="form-group row">
                            <div class="col-md-2 col-xl-12">
                                <button type="submit" class="btn btn-block btn-alt-primary">
                                    {{ __('Resend Verification Code') }}
                                </button>
                            </div>
                         </div>
                    </form><br/>
<form method="post" action="{{route('resetpassword')}}">
                @csrf

                <div class="py-3">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-alt form-control-lg" placeholder="Enter Verification code"  id="vcode" name="vcode" required autocomplete="email" autofocus >
                                </div>  
                </div>
                        <div class="form-group row">
                            <div class="col-md-6 col-xl-12">
                                <button type="submit" class="btn btn-block btn-alt-primary">
                                      {{ __('Confirm') }}
                                </button>
                            </div>
                         </div>
                        </form>
                                            <!-- END Sign In Form -->
                                            </div>
                                    </div>
                                </div>
                                <!-- END Sign In Block -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Page Content -->
        @endif
@endsection

