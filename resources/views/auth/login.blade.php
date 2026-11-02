@extends('layouts.simple')
@section('content')
<style>
.logo_position {
	  position: absolute;
  top: 10px;
  left:12px;

}
/* .bgcontent_position{
  position: absolute;
  top: 65px;
  left:0px;
  background-color: #fff;
  width:104%;
  height: 40px;
  font-size:14px;
  padding-top:10px;
  padding-bottom:8px;
} */

@media (min-width: 576px) and (max-width: 767.98px) {
    .bgcontent_position{
        position: absolute;
        top: 65px;
        left:0px;
        background-color: #fff;
        width:104%;
        height: 40px;
        font-size:10px;
        padding-top:10px;
        padding-bottom:8px;     
    }
 }

 @media (min-width: 768px) and (max-width: 991.98px) {
    .bgcontent_position{
        position: absolute;
        top: 65px;
        left:0px;
        background-color: #fff;
        width:104%;
        height: 40px;
        font-size: 10px;
        padding-top:10px;
        padding-bottom:8px;
    }
 }

 @media (min-width: 992px) and (max-width: 1020px) { 
    .bgcontent_position{
        position: absolute;
        top: 65px;
        left:0px;
        background-color: #fff;
        width:120%;
        height: 40px;
        font-size:11px;
        padding-top:10px;
        padding-bottom:8px;
    }
  }

  @media (min-width: 1020px) and (max-width: 1200px) { 
    .bgcontent_position{
        position: absolute;
        top: 65px;
        left:0px;
        background-color: #fff;
        width:120%;
        height: 40px;
        font-size:11px;
        padding-top:10px;
        padding-bottom:8px;
    }
  }
  @media (min-width: 1200px) and (max-width: 1400px) { 
    .bgcontent_position{
        position: absolute;
        top: 65px;
        left:0px;
        background-color: #fff;
        width:100%;
        height: 40px;
        font-size:13px;
        padding-top:10px;
        padding-bottom:8px;
    }
   }
   @media (min-width: 1400px) { 
    .bgcontent_position{
        position: absolute;
        top: 65px;
        left:0px;
        background-color: #fff;
        width:104%;
        height: 40px;
        font-size:14px;
        padding-top:10px;
        padding-bottom:8px;
    }
   }

.list > li {
  position: relative;
  left:20px;
  top: 38px;
}
.text-white-50 {
  color: hsla(0, 0%, 100%, 0.99) !important;
}
.text-white-75 {
  color: #d9c8f5 !important;
}
.top_main {
  position:relative ;
  top: 40px;
  left:-35px;
}
.center-content{
    font-size:15px;
    color:#fbe5d6;
    background-color: #0674ec;
    padding-top:4px;
    padding-bottom:4px;
    width:270px;
}
.btn-signin {
  color: #fff;
  background-color: #4C7DD9;
  border-color: #4C7DD9;
}

.btn-signin:hover {
  color: #fff;
  background-color: #3261cf;
  border-color: #2f5cc5;
}

.btn-signin:focus, .btn-signin.focus {
  color: #fff;
  background-color: #3261cf;
  border-color: #2f5cc5;
  box-shadow: 0 0 0 0.2rem rgba(107, 141, 220, 0.5);
}
  .avatar-thumb {          
        width: 50%;
        height: 8rem;      
    }
    .avatar-thumb img {
        padding: 1rem;
        object-fit: contain;
        width: 100%;
        height: 100%;
    }

    .logo-thumb {          
        width: 40%;
    }
    .logo-thumb img {
        object-fit: contain;
        width: 100%;
        height: 100%;
    }
    @media (min-width: 768px) and (max-width: 991.98px) {
   .footercgbod{
            font-size:11px;
            color:#076CC2;
            margin-top:5px;
        }
    }
  @media (min-width: 992px) and (max-width: 1020px) { 
        .footercgbod{
            font-size:13px;
            color:#076CC2;
            margin-top:10px;
        }
    }
  @media (min-width: 1020px) and (max-width: 1050px) { 
    .footercgbod{
        font-size:14px;
        color:#076CC2;
        margin-top:10px;
    }
  }
  @media (min-width: 1050px) and (max-width: 1200px) { 
    .footercgbod{
        font-size:14px;
        color:#076CC2;
        margin-top:15px;
    }
  }
  @media (min-width: 1200px) and (max-width: 1400px) { 
    .footercgbod{
        font-size:15px;
        color:#076CC2;
        margin-top:20px;
    }
   }
   @media (min-width: 1400px) { 
    .footercgbod{
        font-size:15px;
        color:#076CC2;
        margin-top:28px;
    }
   }
</style>

<main id="main-container">
    <div class="bg-image" >
        <div class="row no-gutters bg-primary-dark-op">
            <div class="bg-image hero-static col-lg-4 d-none d-lg-flex flex-column justify-content-center" style="background-image:url('media/photos/login_new_backgrd.jpg')">
                <div class="p-4 p-xl-5 flex-grow-1 d-flex align-items-center">
                    <div class="w-100">
                        <a class="logo_position  font-w600 font-size-h2 " href="{{ ('login') }}">
                           	<img src="media/photos/logo_cgbod.png">
                        </a>
                        <!-- <div class="bgcontent_position" style="font-size:14px;padding-top:10px;padding-bottom:8px;"> -->
                        <div class="bgcontent_position">
                           <span style="padding-left:15px;padding-right:10px;">
                           Corporate Governance | Business Excellence | Sustainability</span>
                        </div>
                                                               
                        <table class="table table-borderless mt-9" style="width:100%">
                            <tr>
                                <td width="100%" align="center">
                                    <p class="h5 font-weight-normal center-content">
                                       "In the realm of corporate <br/> governance, CGBOD stands as <br/> 
                                       a sentinel, guiding businesses <br/> towards excellence while <br/> 
                                       championing sustainability as the <br/> cornerstone of enduring success"
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <!-- <div class=" p-5 p-xl-5 d-xl-flex justify-content-between align-items-center">
                     <p class="top_main font-w900 h5 mb-0" style="font-size:15px;color:#076CC2;">
                        <strong>{{ config('app.name') }}</strong>  OÜ&copy;<span data-toggle="year-copy"></span>
                    </p>
                    <ul class="top_list list list-inline mb-0 py-2 mt-0 d-flex justify-content-end" style="left: 66px;">
                         <li class="list-inline-item d-flex justify-content-end">
                            <img src="media/photos/setilas_logo_40.png">                         
                        </li>
                    </ul>
                </div> -->
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-borderless" style="width:100%">
                            <tr>
                                <td align="left">
                                    <p class="font-w900 h5 mb-0 footercgbod">
                                        <strong>{{ config('app.name') }}</strong>  OÜ&copy;<span data-toggle="year-copy"></span>
                                    </p>
                                </td>
                                <td align="right" class="logo-thumb">
                                    <img src="media/photos/setilas_logo_2.png" >
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>
            <div class="hero-static col-lg-8 d-flex flex-column align-items-center bg-white">
                <div class="p-3 w-100 d-lg-none text-center">
                    <a class="link-fx font-w600 font-size-h3 text-dark" href="{{ ('login') }}">
                        {{ config('app.name') }}
                    </a>
                </div>
                <div class="p-4 w-100 flex-grow-1 d-flex align-items-center">
                    <div class="w-100">
                        <div class="mb-5" align="center">                      
                           <div class="avatar-thumb">
                               @if($client_logos)
                                   @if($client_logos->client_logo && file_exists(public_path('/storage/clientlogos/'. $client_logos->client_logo)))
                                      <img 
                                        src="{{ asset("storage/clientlogos/". $client_logos->client_logo) }}"
                                        alt="CompanyLogo">
                                    @endif
                                @endif
                           </div>                         
                            @if($client_logos)
                              <span style="font-size:15px;font-weight:500;">
                                    {{ucwords($client_logos->company_name)}}
                              </span>
                            @endif                 
                        </div>
                        @if (session('resetsuccess'))
                        <div class="alert alert-success" role="alert">
                            <a class="close" data-dismiss="alert">×</a>
                            {{ session('resetsuccess') }}
                        </div>
                        @endif
                        @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            <a class="close" data-dismiss="alert">×</a>
                            {{ session('error') }}
                        </div>
                        @endif
                        @if (session('msg'))
                        <div class="alert alert-success" role="alert">
                            <a class="close" data-dismiss="alert">×</a>
                            {{ session('msg') }}
                        </div>
                        @endif
                        <div class="row no-gutters justify-content-center">
                            <div class="col-sm-8 col-xl-4">
                                <form class="js-validation-signin" action="{{url('/user/login')}}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <input type="email" class="form-control form-control-lg form-control-alt py-4" id="login-username" name="email" placeholder="Email" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-lg form-control-alt py-4" id="login-password" name="password" placeholder="Password" required>
                                    </div>
                                    <div class="form-group d-flex justify-content-between align-items-center">
                                        <div>
                                            @if(Route::has('password.request'))
                                            <a class="text-muted font-size-sm font-w500 d-block d-lg-inline-block mb-1" href="{{ route('password.request') }}">
                                                Forgot Password?
                                            </a>
                                            @endif
                                        </div>
                                        <div>
                                            <button type="submit" class="btn btn-lg btn-signin">
                                                <i class="fa fa-fw fa-sign-in-alt mr-1 opacity-50"></i>Sign In
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="text-center mb-5 mt-6">
                             <p class="mb-3">
                               <span class="font-w500">Not a user? </span><a href="user/register" class="text-primary font-w500">Register now</a>
                            </p>                     
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 w-100 d-lg-none d-flex flex-column flex-sm-row justify-content-between font-size-sm text-center text-sm-left">
                    <p class="font-w500 text-black-50 py-2 mb-0">
                        <strong>{{ config('app.name') }}</strong> &copy; <span data-toggle="year-copy"></span>
                    </p>
                    <ul class="list list-inline py-2 mb-0">
                       <li class="list-inline-item">
                            <a class="text-muted font-w500" href="https://cgbod.com/">Contact</a>
                        </li>
                        <li class="list-inline-item">
                            <a class="text-muted font-w500" href="javascript:void(0)">Terms</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main>
</div>
<script src="{{url('/assets/js/oneui.core.min.js')}}"></script>
<script src="{{url('/assets/js/oneui.app.min.js')}}"></script>
<script src="{{url('assets/js/pages/op_auth_signin.min.js')}}"></script>
@if (session('resetsuccess'))
<script>
    document.addEventListener("DOMContentLoaded", function() {
        fetch("{{ route('send.registration.email') }}?id={{ session('user_id') }}")
            .then(response => console.log("Email sending triggered"));
    });
</script>
@endif
@endsection
