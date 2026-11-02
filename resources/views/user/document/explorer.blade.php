<!DOCTYPE html>
{{-- 7 --}}
<html lang="en">
<head>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <!-- Icons -->
    <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
    <!-- Icons -->
    <link rel="shortcut icon" href="{{ asset('media/favicons/Setilas_logo.jfif') }}">
    <link rel="icon" sizes="192x192" type="image/png" href="{{ asset('media/favicons/Setilas_logo.jfif') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('media/favicons/Setilas_logo.jfif') }}">
    <!-- END Icons -->
    <link rel="stylesheet" href=" {{ asset( 'OneUI by pixelcave/OneUI 4.3 (Remastered)/01 - OneUI Source (HTML)/src/assets/js/plugins/datatables/dataTables.bootstrap4.css' ) }}">
    <link rel="stylesheet" href=" {{ asset( 'OneUI by pixelcave/OneUI 4.3 (Remastered)/01 - OneUI Source (HTML)/src/assets/js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css' ) }}">

    <!-- Fonts and OneUI framework -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400italic,600,700%7COpen+Sans:300,400,400italic,600,700">
    <link rel="stylesheet" id="css-main" href=" {{ asset( 'OneUI by pixelcave/OneUI 4.3 (Remastered)/01 - OneUI Source (HTML)/src/assets/css/oneui.min.css' ) }}">
    <!-- jQuery and jQuery UI (REQUIRED) -->
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>

    <!-- elFinder CSS (REQUIRED) -->
    {{-- 1 n 2 --}}
    <link rel="stylesheet" type="text/css" href="<?= asset('packages/barryvdh/elfinder/css/elfinder.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset('packages/barryvdh/elfinder/css/theme.css') ?>">
    <!-- elFinder JS (REQUIRED) -->
    {{-- 3 --}}
    <script src="<?= asset('packages/barryvdh/elfinder/js/elfinder.min.js') ?>"></script>

    {{-- 6 --}}
    <!-- elFinder translation (OPTIONAL) -->
    {{-- 4 --}}
    <script src="<?= asset('packages/barryvdh/elfinder/js/i18n/elfinder.$locale.js') ?>"></script>
    <?php //} ?>
    <!-- elFinder initialization (REQUIRED) -->

    <?php  $f=0; ?>
    @foreach($doc as $d)
    @if(Auth::user()->email==$d )
    {{-- for documnt controller and MR --}}
    <script type="text/javascript" charset="utf-8">
        // Documentation for client options:
        // https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
        $().ready(function() {
            $('#elfinder').elfinder({
                // set your elFinder options here
                //   9

                customData: {
                    _token: '<?= csrf_token() ?>'
                }
                , url: '<?= route("elfinder.connector") ?>', // connector URL

                // 5
            });
        });

    </script>
    <?php  $f=1; ?>
    @break;
    @endif
    @endforeach
    @foreach($doc as $d)
    @if(Auth::user()->email != $d && $f == 0)
    {{-- for all users --}}
    <script type="text/javascript" charset="utf-8">
        $().ready(function() {
            var elf = $('#elfinder').elfinder({
                url: '<?= route("elfinder.connector") ?>', // connector URL
                cssClass: 'explorer',
                // height: 100%,
                uiOptions: {
                    toolbar: [
                        // toolbar configuration
                        ['back', 'forward', 'up']
                        , ['mkdir', 'mkfile']
                        , ['open', 'download', 'getfile']
                        , ['undo', 'redo']
                        , ['copy', 'paste', 'hide']
                        , ['duplicate', 'edit']
                        , ['selectall', 'selectnone', 'selectinvert']
                        , ['quicklook', 'info']
                        , ['extract', 'archive']
                        , ['search']
                        , ['view', 'sort']
                        , ['help']
                        , ['fullscreen']
                    ]
                }
                , contextmenu: {
                    // navbarfolder menu
                    navbar: [
                        'open', 'opennew', 'quicklook', '|', 'download', 'mkdir', '|', 'copy', 'paste', 'duplicate', '|', 'archive', 'info'
                    ],

                    files: [
                        'getfile', '|', 'open', 'opennew', 'download', 'opendir', '|', 'copy', 'paste', 'duplicate', '|'
                        , 'edit', '|', 'archive', 'extract', '|', 'selectall', 'selectinvert', 'info'
                    ],
                    // current directory menu
                    cwd: [
                        'undo', 'reload', '|', 'mkdir', 'mkfile', '|', 'view', 'sort', 'selectall', '|', 'fullscreen', '|', 'info'
                    ]
                , }
            }).elfinder("instance");
        });

    </script>
    @break;
    @endif
    @endforeach
</head>
<body>
    <a href="{{url('/user/home')}}" style="margin-left:5px;">
        <button type="button" class="btn btn">
            <i class="fa fa-backward  fa-1x"></i>
        </button>
    </a>
    <div id="elfinder" style="height:100%;width:100px"></div>

</body>
</html>
