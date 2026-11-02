@extends('layouts.multiselect')
@section('content')
<!doctype html>
<html lang="en">
<script>
    function validation() {
        if (document.getElementById('vendor_eval').value == 0) {
            alert('Please select Frequency Of Evaluation');
            document.getElementById('vendor_eval').focus();
            return false;
        }

        //validation for appr date calendar required field
        if (document.getElementById('vendorgroup').value == "Existing") {
            if (document.getElementById('vendorappr').value == 0) {
                alert('Please select Approved Date');
                document.getElementById('vendorappr').focus();
                return false;
            }
        }
        if (document.getElementById('vendorgroup').value == "New") {
            if (document.getElementById('vendor_trial').value == 0) {
                alert('Please select Trial Order Made');
                document.getElementById('vendor_trial').focus();
                return false;
            }
        }

        // always return false to prevent form from submission.
    }

</script>

<body>

    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">

                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Vendor Management</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Review Vendor</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="/user/vendormgmt">Back</a>
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
        <div class="block block-rounded">

            <div class="block-content block-content-full">
                <div class="row">

                    <div class="col-lg-10">
                        <!-- Form Horizontal - Default Style -->
                        <form name="form" class="mb-5" action="{{ route('vendor.review') }}" onsubmit="return validation()" method="POST">
                            {{csrf_field()}}
                            @if(count($errors) >0 )
                            <ul class='alert alert-warning'>
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            @endif

                            @if(Session::has('msg'))
                            <div class="alert alert-primary">
                                <a class="close" data-dismiss="alert">×</a>
                                {!!Session::get('msg')!!}
                            </div>
                            @endif
                            <input type="hidden" value="{{ $vendor->id }}" name="vendor_id" />
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="vendorname" style="font-size:13px;">Vendor name<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" style="font-size:13px;" class="form-control" id="vendorname" name="vendorname" value="{{ $vendor->vendor_name }}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="vendorgroup" style="font-size:13px;">Purpose of addition<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="vendorgroup" id="vendorgroup" style="font-size:13px;" value="{{ $vendor->vendor_group }}" disabled />
                                </div>
                            </div>
                            @if($vendor->vendor_group === 'Existing')
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="vendorappr" id="lblappr" style="font-size:13px;">Approved date</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="Date" id="vendorappr" name="vendorappr" style="font-size:13px;" value="{{ $vendor->approved_date }}">
                                </div>
                            </div>
                            @endif
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="vendorscope" id="lblscope" style="font-size:13px;">product/service<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <textarea type="text" class="form-control" id="vendorscope" name="vendorscope" required style="font-size:13px;">{{ $vendor->scope }}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="vendoradd" id="lbladd" style="font-size:13px;">Country</label>
                                <div class="col-sm-8" id="country">
                                    <select class="js-select2 form-control" style="font-size: 13px;" id="vendoradd" name="vendoradd" style="width: 100%;" data-placeholder="Choose a country" multiselect>
                                        <option></option>
                                        @if(count($countries)> 0 )
                                        @foreach($countries as $value)
                                        <option value="{{$value->id}}" {{ $value->id == $vendor->country_id ? 'selected' : '' }}>{{ $value->country_name }}</option></br>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="vendorloc" id="lblloc" style="font-size:13px;">Location<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="vendorloc" name="vendorloc" value="{{ $vendor->location }}" required style="font-size:13px;">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="vendoremail" id="lblemail" style="font-size:13px;">Email<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="vendoremail" name="vendoremail" value="{{ $vendor->email }}" required style="font-size:13px;">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="vendorcontact" id="lblcontact" style="font-size:13px;">Contact<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="vendorcontact" name="vendorcontact" value="{{ $vendor->contact }}" required style="font-size:13px;">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="has_certificate" id="lblhas_certificate"
                                       style="font-size:13px;">Is this vendor certified<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <div class="form-check">
                                        <label class="form-check-label" for="has_certificate_yes" id="has_certificate_yes" style="font-size:13px;">
                                            <input class="form-check-input check-cer" type="radio" name="has_certificate"
                                                   {{ !$isNotCertified ? 'checked' : '' }}
                                                   id="has_certificate_yes" value="yes" required style="font-size:13px;"><span></span>Yes
                                        </label>
                                        <label class="form-check-label mx-4" for="has_certificate_no" id="has_certificate_no" style="font-size:13px;">
                                            <input class="form-check-input check-cer" type="radio" name="has_certificate"
                                                   {{ $isNotCertified ? 'checked' : '' }}
                                                   id="has_certificate_no" value="no" required style="font-size:13px;"><span></span>No
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row {{ !$isNotCertified ? '' : 'd-none'}} cis-wrap">
                                <label class="col-sm-4 col-form-label" for="vendor_iso" id="lbliso" style="font-size:13px;">Certified iso standards<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <div class="form-check">
                                        @foreach($certificateStandard as $s)
                                            <label class="form-check-label vendor_iso" style="font-size:13px;">
                                                <input class="form-check-input has-cer" type="checkbox" name="vendor_iso[]"
                                                       value="{{ $s->id }}" {{ in_array($s->id, $vendor_standards_array) ? 'checked' : '' }}>{{ $s->name }}&nbsp;&nbsp;&nbsp;&nbsp;
                                            </label><br>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row {{ $isNotCertified ? '' : 'd-none'}} no-cis-wrap">
                                <label class="col-sm-4 col-form-label" for="vendor_iso" id="lbliso" style="font-size:13px;">Certified iso standards<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <label class="form-check-label vendor_iso" style="font-size:13px;">
                                        Not certified
                                    </label><br>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="vendor_yoe" id="lblyoe" style="font-size:13px;">Year of establishment</label>
                                <div class="col-sm-8">
                                    <select class="form-control js-select2" name="vendor_yoe" id="vendor_yoe" style="font-size:13px;width:100%" data-placeholder="Choose year">
                                        <option></option>
                                        <option selected="" value="0" style="font-size:13px;">Choose year</option>
                                        <?php $y = date("Y");
                                        for ($year= $y; $year >= config('constants.vendor.start_year'); $year--): ?>
                                        <option style="font-size:13px;" value="<?=$year;?>" {{ $vendor->Yoe == $year ? 'selected' : ''}}><?=$year;?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="vendortype" id="lbltype" style="font-size:13px;">Vendor type</label>
                                <div class="col-sm-8">
                                    <div class="form-check">
                                        <label class="form-check-label" for="vendortype" id="lbltype1" style="font-size:13px;">
                                            <input class="form-check-input" type="radio" name="vendortype" id="vendortype1" value="critical" required style="font-size:13px;" {{ $vendor->vendor_type == 'critical' ? 'checked' : '' }}><span></span>Critical &nbsp;&nbsp;&nbsp;&nbsp;
                                        </label>
                                        &nbsp;&nbsp; <label class="form-check-label" for="vendortype" id="lbltype2" style="font-size:13px;">
                                            <input class="form-check-input" type="radio" name="vendortype" id="vendortype2" value="semi_critical" style="font-size:13px;" {{ $vendor->vendor_type == 'semi_critical' ? 'checked' : '' }}><span></span>Semi-critical&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </label>
                                        &nbsp;&nbsp; <label class="form-check-label" for="vendortype" id="lbltype3" style="font-size:13px;">
                                            <input class="form-check-input" type="radio" name="vendortype" id="vendortype3" value="non_critical" style="font-size:13px;" {{ $vendor->vendor_type == 'non_critical' ? 'checked' : '' }}><span></span>Non critical
                                        </label>

                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="vendor_eval" id="lbleval" style="font-size:13px;">Frequency of evaluation</label>
                                <div class="col-sm-8">
                                    <select class="form-control" name="vendor_eval" id="vendor_eval" style="font-size:13px;" placeholder="Choose Frequency">
                                        <option value="0">Choose</option>
                                        <option value="quarterly" {{ $vendor->eval == 'quarterly' ? 'selected' : ''}}>Quarterly</option>
                                        <option value="half_year" {{ $vendor->eval == 'half_year' ? 'selected' : ''}}>Half year</option>
                                        <option value="one_year" {{ $vendor->eval == 'one_year' ? 'selected' : ''}}>One year</option>
                                        <option value="one_half_year" {{ $vendor->eval == 'one_half_year' ? 'selected' : ''}}>1.5 year</option>
                                        <option value="two_year" {{ $vendor->eval == 'two_year' ? 'selected' : ''}}>Two year</option>
                                        <option value="three_year" {{ $vendor->eval == 'three_year' ? 'selected' : ''}}>Three year</option>
                                        <option value="four_year" {{ $vendor->eval == 'four_year' ? 'selected' : ''}}>Four year</option>
                                        <option value="five_year" {{ $vendor->eval == 'five_year' ? 'selected' : ''}}>Five year</option>
                                    </select>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="vendor_trial" id="lbltrial" style="font-size:13px;">Any trial order made</label>
                                <div class="col-sm-8">
                                    <select class="form-control" name="vendor_trial" id="vendor_trial" style="font-size:13px;">
                                        {{-- <option selected="" disabled value="0">Choose</option> --}}
                                        <option value="yes" {{ $vendor->trial_order == 'yes' ? 'selected' : '' }}>Yes</option>
                                        <option value="no" {{ $vendor->trial_order == 'no' ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="vendoroutcome" id="lbloutcome" style="font-size:13px;">What was the outcome of the trial order</label>
                                <div class="col-sm-8">
                                    <div class="form-check-inline">
                                        <label class="form-check-label" for="vendoroutcome1" id="lblout1" style="font-size:13px;">
                                            <input class="form-check-input" type="radio" name="vendoroutcome" id="vendoroutcome1" value="good" style="font-size:13px;" {{ $vendor->outcome == 'good' ? 'checked' : '' }}><span></span>Good &nbsp;&nbsp;&nbsp;&nbsp;
                                        </label>
                                        <label class="form-check-label" for="vendoroutcome2" id="lblout2" style="font-size:13px;">
                                            <input class="form-check-input" type="radio" name="vendoroutcome" id="vendoroutcome2" value="satisfactory" style="font-size:13px;" {{ $vendor->outcome == 'satisfactory' ? 'checked' : '' }}><span></span>Satisfactory &nbsp;&nbsp;&nbsp;&nbsp;
                                        </label>
                                        <label class="form-check-label" for="vendoroutcome3" id="lblout3" style="font-size:13px;">
                                            <input class="form-check-input" type="radio" name="vendoroutcome" id="vendoroutcome3" value="poor" style="font-size:13px;" {{ $vendor->outcome == 'poor' ? 'checked' : '' }}><span></span>Poor &nbsp;&nbsp;&nbsp;&nbsp;
                                        </label>

                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label d-none" for="vendorbasis_appr" id="lblbaoap" style="font-size:13px;">Basis of approval</label>
                                <div class="col-sm-8 d-none" id="basis_approval_div">
                                    <div class="form-check">
                                        <label class="form-check-label" id="lblappr1" style="font-size:13px;">
                                            <input class="form-check-input" type="checkbox" name="vendorbasis_appr[]" id="vendorbasis_appr1" value="based_on_past_experience" {{ in_array('based_on_past_experience', $basis_approvals) ? 'checked' : '' }}><span></span>Based on past experience&nbsp;&nbsp;&nbsp;&nbsp;
                                        </label><br>
                                        <label class="form-check-label" id="lblappr2" style="font-size:13px;">
                                            <input class="form-check-input" type="checkbox" name="vendorbasis_appr[]" id="vendorbasis_appr2" value="trial_order" {{ in_array('trial_order', $basis_approvals) ? 'checked' : '' }}><span></span>Trial order&nbsp;&nbsp;&nbsp;&nbsp;
                                        </label><br>
                                        <label class="form-check-label" id="lblappr3" style="font-size:13px;">
                                            <input class="form-check-input" type="checkbox" name="vendorbasis_appr[]" id="vendorbasis_appr3" value="suggested_by_customer" {{ in_array('suggested_by_customer', $basis_approvals) ? 'checked' : '' }}><span></span>Suggested by customer&nbsp;&nbsp;&nbsp;&nbsp;
                                        </label><br>
                                        <label class="form-check-label" id="lblappr4" style="font-size:13px;">
                                            <input class="form-check-input" type="checkbox" name="vendorbasis_appr[]" id="vendorbasis_appr4" value="sole_supplier" {{ in_array('sole_supplier', $basis_approvals) ? 'checked' : '' }}><span></span>Sole supplier&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </label><br>
                                        <label class="form-check-label" id="lblappr5" style="font-size:13px;">
                                            <input class="form-check-input" type="checkbox" name="vendorbasis_appr[]" id="vendorbasis_appr5" value="cost_effective" {{ in_array('cost_effective', $basis_approvals) ? 'checked' : '' }}><span></span>Cost effective&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </label>
                                        <br> <label class="form-check-label" id="lblappr6" style="font-size:13px;">
                                            <input class="form-check-input" type="checkbox" name="vendorbasis_appr[]" id="vendorbasis_appr6" value="reputed_company" {{ in_array('reputed_company', $basis_approvals) ? 'checked' : '' }}><span></span>Reputed company
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label d-none" for="vendorbasis_add" id="lbladdb" style="font-size:13px;">Addition of new vendor based on</label>
                                <div class="col-sm-8 d-none" id="basis_addition_div">
                                    <div class="form-check">
                                        <label class="form-check-label" id="lbladdb1" style="font-size:13px;">
                                            <input class="form-check-input" type="checkbox" name="vendorbasis_add[]" id="vendorbasis_add1" value="suggested_by_customer" {{ in_array('suggested_by_customer', $basis_additions) ? 'checked' : '' }}><span></span>Suggested by customer &nbsp;&nbsp;&nbsp;&nbsp;
                                        </label><br>
                                        <label class="form-check-label" id="lbladdb2" style="font-size:13px;">
                                            <input class="form-check-input" type="checkbox" name="vendorbasis_add[]" id="vendorbasis_add2" value="single_source" {{ in_array('single_source', $basis_additions) ? 'checked' : '' }}><span></span>Single source&nbsp;&nbsp;&nbsp;&nbsp;
                                        </label><br>
                                        <label class="form-check-label" id="lbladdb3" style="font-size:13px;">
                                            <input class="form-check-input" type="checkbox" name="vendorbasis_add[]" id="vendorbasis_add3" value="economical_cost_effective" {{ in_array('economical_cost_effective', $basis_additions) ? 'checked' : '' }}><span></span>Economical/cost effective<br>
                                        </label>
                                        <br> <label class="form-check-label" id="lbladdb4" style="font-size:13px;">
                                            <input class="form-check-input" type="checkbox" name="vendorbasis_add[]" id="vendorbasis_add4" value="reputed_company_brand_agency" {{ in_array('reputed_company_brand_agency', $basis_additions) ? 'checked' : '' }}><span></span>Reputed company/brand/agency&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </label>
                                        <br><label class="form-check-label" id="lbladdb5" style="font-size:13px;">
                                            <input class="form-check-input" type="checkbox" name="vendorbasis_add[]" id="vendorbasis_add5" value="suggested_by_management" {{ in_array('suggested_by_management', $basis_additions) ? 'checked' : '' }}><span></span>Suggested by management
                                        </label>
                                    </div>
                                </div>
                            </div>
                            {{-- @if(in_array(Auth::user()->email, $hods)) --}}
                            @if(Gate::allows('review-vendor'))
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="vendorcomments" style="font-size:13px;">Added person Comments</label>
                                <div class="col-sm-8">
                                    <textarea type="text" style="font-size:13px;" class="form-control" readonly>{{ $vendor->comments_of_added_person }}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="vendorcomments" id="lblcomm" style="font-size:13px;">Comments</label>
                                <div class="col-sm-8">
                                    <textarea type="text" style="font-size:13px;" class="form-control" id="vendorcomments" name="vendorcomments">{{ $vendor->comments }}</textarea>
                                </div>
                            </div>
                            @endif
                            <div class="form-group row">
                                <div class="col-sm-8 ml-auto">
                                    <button type="submit" class="btn btn-primary" style="font-size:13px;margin-left:440px;">Submit</button>
                                </div>
                            </div>
                        </form>
                        <!-- END Form Horizontal - Default Style -->

                    </div>
                </div>
            </div>
        </div>
        <!-- END Horizontal -->



        <!-- Apps Modal -->
        <!-- Opens from the modal toggle button in the header -->
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
                                    <a class="block block-rounded block-link-shadow bg-body" href="/user/vendormgmt">
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="far fa-list-alt fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Register
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Products -->
                                </div>
                                <div class="col-6">
                                    <!-- Products -->
                                    <a class="block block-rounded block-link-shadow bg-body" href="/user/vendor/report">
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Vendor report
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

</body>
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script>
    $('#lbloutcome').hide();
    $('#lblout1').hide();
    $('#lblout2').hide();
    $('#lblout3').hide();

    function showOutcome() {
        if ($("#vendor_trial option:selected").val() == "yes") {
            $('#lbloutcome').show();
            $('#lblout1').show();
            $('#lblout2').show();
            $('#lblout3').show();
        } else {
            $('#lbloutcome').hide();
            $('#lblout1').hide();
            $('#lblout2').hide();
            $('#lblout3').hide();
        }
    }

    showOutcome();

    $("#vendor_trial").change(function() {
        showOutcome();
    });

    if ($("#vendorgroup").val() == "Existing") {

        $('#lbladdb').removeClass('d-block');
        $('#lbladdb').addClass('d-none');
        $('#basis_addition_div').removeClass('d-block');
        $('#basis_addition_div').addClass('d-none');

        $('#lblbaoap').removeClass('d-none');
        $('#lblbaoap').addClass('d-block');
        $('#basis_approval_div').removeClass('d-none');
        $('#basis_approval_div').addClass('d-block');
    } else if ($("#vendorgroup").val() == "New") {

        $('#lbladdb').removeClass('d-none');
        $('#lbladdb').addClass('d-block');
        $('#basis_addition_div').removeClass('d-none');
        $('#basis_addition_div').addClass('d-block');

        $('#lblbaoap').removeClass('d-block');
        $('#lblbaoap').addClass('d-none');
        $('#basis_approval_div').removeClass('d-block');
        $('#basis_approval_div').addClass('d-none');

    }
    $(function () {
        $('body').on('click', '.check-cer', function () {
            if ($(this).val() == 'yes') {
                $('.cis-wrap').removeClass('d-none');
                $('.no-cis-wrap').addClass('d-none');
            } else {
                $('.no-cis-wrap').removeClass('d-none');
                $('.cis-wrap').addClass('d-none');
                $('.has-cer').prop('checked', false);
            }
        })
    })

</script>

</html>
@endsection
