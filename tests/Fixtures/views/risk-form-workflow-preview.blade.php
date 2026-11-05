@extends('pages.datatables')
@section('content')
<link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
{!! $form !!}
<script src="{{ asset('js/plugins/select2/js/select2.full.js') }}"></script>
@endsection
