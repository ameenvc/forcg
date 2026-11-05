<!doctype html>
<html lang="{{ config('app.locale') }}">
<head>
    <style>
        .block-footer {
            display: flex;
            align-items: flex-end;
        }

        .my-is-invalid {
            border-color: #e56767 !important;
        }

    </style>


    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <title>{{ config('app.name') }}</title>

    <meta name="description" content="{{ config('app.name') }}">
    <meta name="author" content="{{ config('app.name') }}">
    <meta name="robots" content="noindex, nofollow">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

    <link rel="stylesheet" href="{{ asset('css/style.css')}}">

    <!-- Fonts and Styles -->
    @yield('css_before')
    <link rel="stylesheet" id="css-main" href="{{ mix('/css/oneui.css') }}">
    @yield('css_after')
        <script>window.Laravel = {!! json_encode(['csrfToken' => csrf_token(),]) !!};</script>
        @include('shared.theme.assets')
</head>
    <body>
        <div id="page-container">
           <div class="app-simple-theme">@include('shared.forms.theme-control')</div>
@yield('content')
        </div>
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


    <script src="{{ mix('js/oneui.app.js') }}"></script>
    @yield('js_after')
</body>
</html>
