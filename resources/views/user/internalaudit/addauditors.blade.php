{{--@extends('layouts.backend')--}}
@extends('layouts.multiselect')
@section('content')
<!doctype html>
<html lang="en">

<style>
.paragraph-style
{
    font-weight:bold;
    font-size:20px;
    color:#191e3b;
    margin-top:30px;
    margin-left:-1px;
}
</style>

<body>

<!-- Hero -->
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
            <!--<nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">Auditors List</li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="">Add Auditors</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="/user/internalaudit/auditorslist">Back</a>
                    </li>
                </ol>
            </nav>-->
            <p class="paragraph-style">Add Auditor Form</p>
        </div>
    </div>
</div>
<!-- END Hero -->

                <!-- Page Content -->
                <div class="content">


                   <!-- Horizontal -->
                    <div class="block block-rounded" style="box-shadow: 0 0 15px;background-color: #ffffff;">

                        <div class="block-content block-content-full">
                            <div class="row">

                                <div class="col-lg-10">
            <form class="mb-5" action="{{url('/user/internalaudit/insertauditors')}}" method="post" enctype="multipart/form-data">
            {{csrf_field()}}
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

                    <div class="form-group" style="display:inline-block;width:33%;">
                        <label class="col-sm-12 col-form-label" for="auditorsemail"style="font-size: 13px;">Name of auditor<span class="text-danger">*</span></label>
                        <div class="col-sm-12">
                            <div class="form-group"style="color:#666666">
                                <select class="js-select2 form-control"style="font-size: 13px;"required="true" id="auditorsemail" name="auditorsemail" style="width: 100%;" data-placeholder="Choose one..">
                                    <option selected="" disabled>Choose Auditor</option>
                                    @if(count($users)> 0 )
                                        @foreach($users as $value)
                                            <option value="{{$value->email}}"
                                                {{ old('auditorsemail') == $value->email ? 'selected' : '' }}>
                                                {{ $value->name }}
                                            </option></br>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" style="display:inline-block;width:33%;">
                        <label class="col-sm-12 col-form-label" for="experience" style="font-size: 13px;">
                            Total Audit Experience
                        </label>
                        <div class="col-sm-12">
                            <input type="text" style="font-size: 13px;"
                                   value="{{ old('experience') }}"
                                   class="form-control" id="experience"
                                   name="experience">
                        </div>
                    </div>

                    <div class="form-group" style="display:inline-block;width:33%;">
                        <label class="col-sm-12 col-form-label" for="overall_experience" style="font-size: 13px;">
                            Overall Experience
                        </label>
                        <div class="col-sm-12">
                            <input type="text" style="font-size: 13px;"
                                   value="{{ old('overall_experience') }}"
                                   class="form-control" id="overall_experience"
                                   name="overall_experience">
                        </div>
                    </div>

                    <div class="form-group" style="display:inline-block;width:99%;">
                        <label class="col-sm-12 col-form-label" for="certifications_licensing" style="font-size: 13px;">
                            Certifications and Licensing(List all audit Certificates)<span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-12">
                            <textarea style="font-size: 13px;"
                                      class="form-control" id="certifications_licensing"
                                      name="certifications_licensing">{{ old('certifications_licensing') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group" style="display:inline-block;width:99%;">
                        <label class="col-sm-12 col-form-label" for="specialization_area" style="font-size: 13px;">
                            Area of Specialization
                        </label>
                        <div class="col-sm-12">
                            <textarea style="font-size: 13px;"
                                      class="form-control" id="specialization_area"
                                      name="specialization_area">{{ old('specialization_area') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group" style="display:inline-block;width:99%;margin-top:-20px;">
                            <label class="col-sm-12 col-form-label" for="file"style="font-size: 13px;">Attach certificates(avoid '#' and '&' in file names)</label>
                            <div class="col-sm-12">
                                <input type="file"style="font-size: 13px;" class="form-control" name="file[]" multiple>
                            </div>
                    </div>
                    <div>
                        <button type="submit" for="submit" class="btn btn-primary"style="font-size:13px; margin-left:89%;" data-toggle="click-ripple" onclick="return confirm('Are you sure to submit the request?')">Submit</button>
                    </div>
            </form>

            </div>
        </div>

        </div>
        </div>
        </main>
        </body>
        </html>
        <div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="block block-rounded block-themed block-transparent mb-0">
                            <div class="block-header bg-primary-dark">
                                <h3 class="block-title">Options</h3>
                                <div class="block-options">
                                    <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                        <i class="si si-close"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="block-content block-content-full">
                                <div class="row gutters-tiny">

                                    <div class="col-6">
                                        <!-- Products -->
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/auditschedule">
                                            <div class="block-content text-center">
                                                <i class="far fa-list-alt fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Audit Schedule
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Products -->
                                    </div>
                                    <div class="col-6">
                                        <!-- Sales -->
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/internalaudit/auditorslist">
                                            <div class="block-content text-center">
                                                <i class="fas fa-address-card fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                   <br> Auditors List
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Sales -->
                                    </div>
                                    <div class="col-6">
                                        <!-- Payments -->
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/internalaudit/auditlog">
                                            <div class="block-content text-center">
                                                <i class="fas fa-calendar-check fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    <br>Audit Log
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Payments -->
                                    </div>
                                    <div class="col-6">
                                        <!-- Sales -->
                                        <a class="block block-rounded block-link-shadow bg-body mb-2" href="/user/internalaudit/closedaudits">
                                            <div class="block-content text-center">
                                                <i class="fas fa-check-circle fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Closed Audits
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Sales -->
                                    </div>
                                    <div class="col-6">
                                        <!-- Sales -->
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href='/user/internalaudit/analysis'>
                                            <div class="block-content text-center">
                                                <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Analysis
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Sales -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- END Apps Modal -->


</body>
</html>
@endsection



