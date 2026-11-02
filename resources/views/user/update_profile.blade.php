@extends('layouts.backend')
@section('content')
    @include('common.update_profile');
    <script>
        $('i.fa-cubes').closest('button').hide();
    </script>
@endsection
