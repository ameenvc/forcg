@extends('superadmin.datatable')
@section('content')


<!-- END Hero -->
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">Custom Field Configuration</li>

                    <li class="breadcrumb-item" aria-current="page">
                        <a id="a1" onclick="" class="link-fx" href="/superadmin/home">Back</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<form action="{{ route('superadmin.custom_fields_enadisable')}}" method="post">
    @csrf
    <div class="container my-4">
        @if(Session::has('successmsg'))
            <div class="alert alert-primary mx-5">
                <a class="close" data-dismiss="alert">×</a>
                {!!Session::get('successmsg')!!}
            </div>
        @endif
        <div class="row justify-content-center">
            <div class="col-11 col-md-11 col-sm-12">
                <ul class="list-group push">
                    <li class="list-group-item">
                        <div class="row align-items-center">
                            <div class="col-12 col-md-10">
                                <span style="font-weight: 600;">Custom Field Configuration</span>
                                <p style="font-size: 13px; margin-bottom: 0;">
                                    This feature allows users to create and customize fields (type, position, and settings) on any page, enabling them to tailor the system to their business needs without backend changes.
                                </p>
                            </div>
                            <div class="col-12 col-md-2 text-md-right mt-2 mt-md-0">
                                <div class="custom-control custom-switch custom-control-primary custom-control-lg">
                                    <input type="checkbox" class="custom-control-input" id="enadisable" name="enadisable" {{$enable?'checked':''}}>
                                    <label class="custom-control-label" for="enadisable" style="font-size: 13px;"></label>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row justify-content-center">
                            <div class="col-auto">
                                <button id="submit" class="btn btn-primary" type="submit" style="font-size: 13px;" disabled>Update</button>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</form>
<!-- jQuery (required for the script above) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#enadisable').on('change', function() {
            $('#submit').prop('disabled',false);
        });
    });
</script>
<!-- END Services -->
@endsection