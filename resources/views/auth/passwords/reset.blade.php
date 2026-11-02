@extends('layouts.simple')
    @section('content')
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
                                Please provide new password.
                                </p>
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('confirmreset') }}">
                        @csrf
                        <div class="py-3">
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-alt form-control-lg @error('email') is-invalid @enderror" value="{{$email}}" id="email" name="email" readonly autocomplete="email" autofocus placeholder="Enter Email-ID">
                                </div> 
                            </div>
                            <div class="py-3">
                                <div class="form-group">
                                    <input id="password" type="password" class="form-control form-control-alt form-control-lg @error('password') is-invalid @enderror" name="password" required  placeholder="Enter New Password">
                                </div>
                                <span class="help-block" id="span1">
                                        <strong></strong>
                                 </span> 
                            </div>
                            <div class="py-3">
                                <div class="form-group">
                                    <input id="password-confirm" type="password" class="form-control form-control-alt form-control-lg @error('password') is-invalid @enderror" name="password_confirmation" required  placeholder="Confirm Password">
                                </div>
                            </div>
                        <div class="form-group row">
                            <div class="col-md-6 col-xl-12">
                                <button type="submit" class="btn btn-block btn-alt-primary" onclick="return matchpassword()">
                                {{ __('Confirm Password') }}
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
                </div>
<script src="https://use.fontawesome.com/releases/v5.10.1/js/all.js" data-auto-replace-svg="nest"></script>
<script type="text/javascript">
function matchpassword(){
    password1=document.getElementById('password').value;
    password2=document.getElementById('password-confirm').value;
    if(password1!=password2){
        document.getElementById('span1').innerHTML="<b>Confirm Password and Password should match</b>";
        document.getElementById('span1').style.color="red";
        return false;
    }
return true;
}
</script>
@endsection
