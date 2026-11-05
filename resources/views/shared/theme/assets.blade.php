@once
<link rel="stylesheet" href="{{ asset('css/risk-theme.css') }}?v={{ filemtime(public_path('css/risk-theme.css')) }}">
<link rel="stylesheet" href="{{ asset('css/app-theme.css') }}?v={{ filemtime(public_path('css/app-theme.css')) }}">
@include('shared.typography.assets')
<script src="{{ asset('js/risk-theme.js') }}?v={{ filemtime(public_path('js/risk-theme.js')) }}"></script>
<script src="{{ asset('js/app-theme.js') }}?v={{ filemtime(public_path('js/app-theme.js')) }}" defer></script>
@endonce
