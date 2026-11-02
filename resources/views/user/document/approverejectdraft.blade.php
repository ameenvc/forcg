@extends('layouts.multiselect')
@section('content')

<!-- Hero -->
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">Governance Hub</li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="#">Request Approval</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="{{ route('doc.approve_draft_details', ['id' => $id]) }}">Back</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <!-- Horizontal -->
    @if(count($errors) >0 )
    @foreach($errors->all() as $error)
    <div class='alert alert-warning'>
        {{ $error }}
    </div>
    @endforeach
    @endif
    @if(Session::has('msg'))
    <div class="alert alert-primary">
        <a class="close" data-dismiss="alert">×</a>
        {!!Session::get('msg')!!}
    </div>
    @endif
    <div class="block block-rounded">
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-10">
                    <!-- Form Horizontal - Default Style -->
                    <form name="form" class="mb-5" action="{{route('doc.insert_approval_reject_reason')}}" method="POST">
                        {{csrf_field()}}
                        <input type="hidden" name="id" value="{{$id}}" class="form-control" id="id">
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label class="col-form-label" for="reject_reason" style="font-size:20px;">Reason for Rejecting Request <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <textarea type="text" rows="10" cols="10" class="form-control" id="reject_reason" name="reject_reason" required style="font-size:13px;width:100%;"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 form-group text-right">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                    <!-- END Form Horizontal - Default Style -->
                </div>
            </div>
        </div>
    </div>
    <!-- END Horizontal -->
</div>
@endsection