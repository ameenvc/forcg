<!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Risk register preview</title><link rel="stylesheet" href="{{ asset('css/oneui.min.css') }}">
<link rel="stylesheet" href="{{ asset('js/plugins/datatables/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
<script src="{{ asset('js/core/jquery.min.js') }}"></script>@include('shared.theme.assets')</head>
<body style="background:#f4f6fb"><header id="page-header" style="padding:10px 20px;display:flex;justify-content:flex-end">@include('shared.forms.theme-control')</header><main id="main-container">@yield('content')</main>
<script src="{{ asset('js/core/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('js/plugins/select2/js/select2.full.js') }}"></script>
</body></html>
