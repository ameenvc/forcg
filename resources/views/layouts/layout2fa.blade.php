<!doctype html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <title>{{ config('app.name') }}</title>

    <meta name="description" content="{{ config('app.name') }}">
    <meta name="author" content="{{ config('app.name') }}">
    <meta name="robots" content="noindex, nofollow">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .disabled-color {
            color: #3f4253 !important;
        }

        .cursor-none {
            cursor: inherit !important;
        }

        .hover-text-color {
            color: #25E5D4 !important;
        }

    </style>

    {{-- Script for getting idea on user perspectives --}}
    <script type="text/javascript">
        (function(c, l, a, r, i, t, y) {
            c[a] = c[a] || function() {
                (c[a].q = c[a].q || []).push(arguments)
            };
            t = l.createElement(r);
            t.async = 1;
            t.src = "https://www.clarity.ms/tag/" + i;
            y = l.getElementsByTagName(r)[0];
            y.parentNode.insertBefore(t, y);
        })(window, document, "clarity", "script", "fxie009nn8");

    </script>
    {{-- End script for getting idea on user perspectives --}}


    <!-- Icons -->
    <link rel="shortcut icon" href="{{ asset('media/favicons/Setilas_logo.jfif') }}">
    <link rel="icon" sizes="192x192" type="image/png" href="{{ asset('media/favicons/Setilas_logo.jfif') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('media/favicons/Setilas_logo.jfif') }}">
    <meta property="og:title" content="{{ config('app.name') }}">
    <meta property="og:description" content="{{ config('app.name') }}">
    <meta property="og:image" content="{{ asset('media/favicons/Setilas_logo.jfif') }}">
    <meta property="og:url" content="{{config('app.url')}}">
    <!-- Stylesheets -->

    <!-- Fonts and Styles -->
    @yield('css_before')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" id="css-main" href="{{ mix('/css/oneui.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css')}}">

    <!-- You can include a specific file from public/css/themes/ folder to alter the default color theme of the template. eg: -->
    <!-- <link rel="stylesheet" id="css-theme" href="{{ mix('/css/themes/amethyst.css') }}"> -->
    @yield('css_after')

    <!-- Scripts -->
    <script src="/js/sweetalert/sweetalert2.all.min.js"></script>
</head>

<body>

    <div id="page-container" class="page-header-fixed main-content-narrow">


        <!-- Header -->
        <header id="page-header">
            <!-- Header Content -->
            <div class="content-header">
                <!-- Left Section -->
                <div class="d-flex align-items-center">

                </div>
                <div class="d-flex align-items-center">
                    @yield('sideitem')
                    <div class="dropdown d-inline-block ml-2">
                        @if (\Route::current()->getName() == 'user.auditschedule')
                        <button type="button" class="btn btn-sm btn-dual width-150 video-btn" id="learn_audit_btn" data-toggle="modal" data-src="https://youtube.com/embed/VbQbGmkkm0c" data-target="#modal-learn-audit-module">
                            <i class="fa fa-1x fa-video" style="color:grey;"></i><span id="learn_audit" class="ml-2">Learn Module</span>
                        </button>

                        @endif
                    </div>
                    @php
                      $user_details= App\User::where('email',Auth::user()->email)->first();
                      $profilePicture =  $user_details ? $user_details->profile_picture : '';
                    @endphp
                    <div class="dropdown d-inline-block ml-2">
                        <button type="button" class="btn btn-sm btn-dual d-flex align-items-center" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @if($profilePicture != null)
                            <img class="rounded-circle" src="/storage/profile/{{ $profilePicture }}" alt="" style="width: 21px;">
                            @else
                            <img class="rounded-circle" src="{{ asset('media/avatars/avatar10.jpg') }}" alt="" style="width: 21px;">
                            @endif
                            <span class="d-none d-sm-inline-block ml-2" style="white-space:nowrap">{{ Auth::user()->name }}</span>
                            <i class="fa fa-fw fa-angle-down d-none d-sm-inline-block ml-1 mt-1"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-right p-0 border-0" aria-labelledby="page-header-user-dropdown">
                            <div class="p-3 text-center bg-primary-dark rounded-top">
                                @if($profilePicture != null)
                                <img class="img-avatar img-avatar48 img-avatar-thumb" src="/storage/profile/{{ $profilePicture }}" alt="">
                                @else
                                <img class="img-avatar img-avatar48 img-avatar-thumb" src="{{ asset('media/avatars/avatar10.jpg') }}" alt="">
                                @endif
                                <p class="mt-2 mb-0 text-white font-w500">{{ Auth::user()->name }}</p>
                                @if(Session::get('type')==0)

                                <p class="mb-0 text-white-50 font-size-sm">User</p>
                                @elseif(Session::get('type')==1)
                                <p class="mb-0 text-white-50 font-size-sm">Admin</p>
                                @endif
                            </div>
                            <div class="p-2">
                                <a class="dropdown-item d-flex align-items-center justify-content-between" href="{{ route('user.logout') }}">
                                    <span class="font-size-sm font-w500" style="color: #000;">Logout</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <main id="main-container">
            @yield('content')
        </main>
        <footer id="page-footer" class="bg-white">
            <div class="content py-3">
                <div class="row font-size-sm">
                    <div class="col-sm-6 order-sm-2 py-1 text-center text-sm-right">
                        Powered by <a class="font-w600" href="#" target="_blank">{{ config('app.name') }}</a>
                    </div>
                    <div class="col-sm-6 order-sm-1 py-1 text-center text-sm-left">
                        <a class="font-w600" href="#" target="_blank">{{ config('app.name') }} {{ config('app.ver') }}</a>
                        &copy; <span data-toggle="year-copy"></span>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <!-- END Page Container -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    @if (Auth::user())
        <script>
            let lifetime = "{{ config('session.lifetime') }}";
            $(function() {
                setInterval(function checkSession() {
                    $.get('/check-session', function(data) {
                        // if session was expired
                        if (data.guest) {
                            // redirect to login page
                            // location.assign('/auth/login');

                            // or, may be better, just reload page
                            location.reload();
                        }
                    });
                }, lifetime * 60000); // session_lifetime * every minute
            });

        </script>
    @endif

    <!-- OneUI Core JS -->
    <script src="{{ mix('js/oneui.app.js') }}"></script>

    @yield('js_after')

    @yield('javascript')

</body>

</html>
