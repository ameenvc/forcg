@extends('layouts.backend')

@section('content')

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">

            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">Occupational Health</li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="">Update Health Record</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="/user/occupationhealth/register">Back</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<!-- END Hero -->

<!-- Page Content -->
<div class="content" style="">
    <!-- Horizontal -->
    <div class="block block-rounded">
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-10">
                    <!-- Form Horizontal - Default Style -->
                    <?php $f=0; ?>
                    <form class="mb-5" action="{{route('emphealthrecordupdateconf')}}" method="POST" enctype="multipart/form-data">

                        @csrf
                        @if(Session::has('msg'))
                        <div class="alert alert-primary">
                            <a class="close" data-dismiss="alert">×</a>
                            {!!Session::get('msg')!!}
                        </div>
                        @endif
                        <div class="col-lg-10">
                            @if(count($empdata) > 0)
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="number" style="font-size: 13px;">Employee Number</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" value="{{$healthrecrd->e_number}}" name="number" id="number" style="font-size: 13px;" readonly>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="name" style="font-size: 13px;">Employee Name</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="name" id="name" value="{{$healthrecrd->e_name}}" style="font-size: 13px;" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="department" style="font-size: 13px;">Department</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="department" id="department" value="{{$empdept ? $empdept : '-'}}" style="font-size: 13px;" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="position" style="font-size: 13px;">Position</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="position" id="position" value="{{$healthrecrd->position ? $healthrecrd->position : '-'}}" style="font-size: 13px;" readonly>
                                </div>
                            </div>

                            @php

                            $medidate = date('Y-m-d',strtotime($healthrecrd->date));
                            @endphp

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="hospital" style="font-size: 13px;">Date of Medical</label>
                                <div class="col-sm-8">
                                    <input type="date" class="js-datepicker form-control" id="medicaldate" name="medicaldate" data-week-start="1" data-autoclose="true" value="{{$medidate}}" data-date-format="dd/mm/yy" placeholder="dd/mm/yy" style="font-size: 13px;">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="tobacco" style="font-size: 13px;"> Tobacco</label>
                                @if($healthrecrd->tobacco=='yes')
                                <div class="col-md-3">
                                    <input style="margin-right:4px;" type="radio" value="yes" id="yes" name="tobacco" checked><label style="font-size: 13px;">Yes</label>
                                </div>
                                <div class="col-md-3">
                                    <input style="margin-right:4px;" type="radio" value="no" id="no" name="tobacco"><label style="font-size: 13px;">No</label>
                                </div>
                                @else
                                <div class="col-md-3">
                                    <input style="margin-right:4px;" type="radio" value="yes" id="yes" name="tobacco"><label style="font-size: 13px;">Yes</label>
                                </div>
                                <div class="col-md-3">
                                    <input style="margin-right:4px;" type="radio" checked value="no" id="no" name="tobacco"><label style="font-size: 13px;">No</label>
                                </div>
                                @endif
                            </div>



                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="hospital" style="font-size: 13px;">Blood Pressure Diastolic <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" style="font-size: 13px;" required class="form-control" id="bloodpd" name="bloodpd" value="{{$healthrecrd->bp_diastolic ? $healthrecrd->bp_diastolic : ''}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="hospital" style="font-size: 13px;">Blood Pressure Systolic <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" style="font-size: 13px;" required class="form-control" id="bps" name="bps" value="{{$healthrecrd ? $healthrecrd->bp_systolic : ''}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="hospital" style="font-size: 13px;">Blood Sugar Level <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" style="font-size: 13px;" required class="form-control" id="bsl" name="bsl" value="{{$healthrecrd ? $healthrecrd->bs_level : ''}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="hospital" style="font-size: 13px;">Remarks (if any)</label>
                                <div class="col-sm-8">
                                    <input type="text" style="font-size: 13px;" class="form-control" id="remark" name="remark" value="{{$healthrecrd ? $healthrecrd->remarks : ''}}">
                                </div>
                            </div>


                        </div>
                        <input type="hidden" name="employeeid" value="{{$healthrecrd->employee_id}}">
                        <br />
                        @else

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" for="number" style="font-size: 13px;">Employee Number</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" value="{{$emprecord->emp_number}}" name="number" id="number" style="font-size: 13px;" readonly>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" for="name" style="font-size: 13px;">Employee Name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="name" id="name" value="{{$emprecord->emp_name}}" style="font-size: 13px;" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" for="department" style="font-size: 13px;">Department</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="department" id="department" value="{{$empdept ? $empdept : '-'}}" style="font-size: 13px;" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" for="position" style="font-size: 13px;">Position</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="position" id="position" value="{{$emprecord->emp_position ? $emprecord->emp_position : '-'}}" style="font-size: 13px;" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" for="hospital" style="font-size: 13px;">Date of Medical</label>
                            <div class="col-sm-8">
                                <input type="date" class="js-datepicker form-control" id="medicaldate" name="medicaldate" data-week-start="1" data-autoclose="true" value="" data-date-format="dd/mm/yy" placeholder="dd/mm/yy" style="font-size: 13px;">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" for="tobacco" style="font-size: 13px;"> Tobacco</label>

                            <div class="col-md-3">
                                <input style="margin-right:4px;" type="radio" value="yes" id="yes" name="tobacco"><label style="font-size: 13px;">Yes</label>
                            </div>
                            <div class="col-md-3">
                                <input style="margin-right:4px;" type="radio" checked value="no" id="no" name="tobacco"><label style="font-size: 13px;">No</label>
                            </div>

                        </div>



                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" for="hospital" style="font-size: 13px;">Blood Pressure Diastolic <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" style="font-size: 13px;" required class="form-control" id="bloodpd" name="bloodpd" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" for="hospital" style="font-size: 13px;">Blood Pressure Systolic <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" style="font-size: 13px;" required class="form-control" id="bps" name="bps" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" for="hospital" style="font-size: 13px;">Blood Sugar Level <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" style="font-size: 13px;" required class="form-control" id="bsl" name="bsl" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" for="hospital" style="font-size: 13px;">Remarks (if any)</label>
                            <div class="col-sm-8">
                                <input type="text" style="font-size: 13px;" class="form-control" id="remark" name="remark" value="">
                            </div>
                        </div>


                </div>
                <input type="hidden" name="employeeid" value="{{$emprecord->id}}">
                <br />
                @endif

                <div style="text-align:center;">
                    <button type="submit" for="submit" class="btn btn-primary" style="font-size:13px; float:right;margin-right:18%;" data-toggle="click-ripple" onclick="return confirm('Do you want to proceed ? ')">Update</button>
                    <button for="reset" type="reset" class="btn btn-secondary" style="font-size:13px;float:right;margin-right:1%; ">Reset</button>
                </div>

                </form>

            </div>
        </div>
    </div>
</div>


<!-- Apps Modal -->
<!-- Opens from the modal toggle button in the header -->
<div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="block block-rounded block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">More</h3>
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
                            <a class="block block-rounded block-link-shadow bg-body" href="/user/occupationhealth/register">
                                <div class="block-content text-center">
                                    <i class="far fa-list-alt fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Employee Health Record
                                    </p>
                                </div>
                            </a>
                            <!-- END Products -->
                        </div>
                        <div class="col-6">
                            <!-- Products -->
                            <a class="block block-rounded block-link-shadow bg-body" href="/user/occupationhealth/injuryrecords">
                                <div class="block-content text-center">
                                    <i class="far fa-list-alt fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Employee Injury Record
                                    </p>
                                </div>
                            </a>
                            <!-- END Products -->
                        </div>
                        <div class="col-6">
                            <!-- Products -->
                            <a class="block block-rounded block-link-shadow bg-body" href="/user/occupationhealth/illnessrecords">
                                <div class="block-content text-center">
                                    <i class="far fa-list-alt fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Employee Illness Record
                                    </p>
                                </div>
                            </a>
                            <!-- END Products -->
                        </div>

                        <div class="col-6">
                            <!-- Products -->
                            <a class="block block-rounded block-link-shadow bg-body" href="{{ url('/user/occupationhealth/analysis') }}">
                                <div class="block-content text-center">
                                    <i class="far fa-list-alt fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 " style="margin-bottom:60px">
                                        Analysis
                                    </p>
                                </div>
                            </a>
                            <!-- END Products -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END Apps Modal -->


@endsection
