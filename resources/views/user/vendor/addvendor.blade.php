@extends('layouts.multiselect')
@section('content')
<!doctype html>
<html lang="en">
<style>
    .select2-container {
        width: 100% !important;
    }
</style>
<body>

    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">

                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Vendor Management</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Add Vendor</a>
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
                        <form name="form" class="mb-5" action="{{url('/user/vendor/submit')}}" method="POST">
                            {{csrf_field()}}
                            @if(Session::has('msg'))
                            <div class="alert alert-primary">
                                <a class="close" data-dismiss="alert">×</a>
                                {!!Session::get('msg')!!}
                            </div>
                            @endif

                            <!-- Vendor name -->
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="vendorname" style="font-size:13px;">Vendor name<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" style="font-size:13px;" class="form-control @error('vendorname') is-invalid @enderror"
                                           value="{{ old('vendorname') }}"
                                           id="vendorname" name="vendorname" placeholder="Enter vendor name">
                                    @error('vendorname')
                                    <span class="text-danger" style="font-size: 13px">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Purpose of addition -->
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label"
                                       for="vendorgroup" style="font-size:13px;">Purpose of addition<span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-8">
                                    <select class="form-control @error('vendorgroup') is-invalid @enderror"
                                            name="vendorgroup" id="vendorgroup" style="font-size:13px;" placeholder="Choose Purpose of Addition">
                                        <option value="0" {{ !old('vendorgroup') ? 'selected' : '' }} =""
                                            disabled style="font-size:13px;">Choose</option>
                                        <option value="Existing" {{ old('vendorgroup') == 'Existing' ? 'selected' : '' }}
                                            style="font-size:13px;">Existing Vendor</option>
                                        <option value="New" {{ old('vendorgroup') == 'New' ? 'selected' : '' }}
                                                style="font-size:13px;">New vendor</option>
                                    </select>
                                    @error('vendorgroup')
                                    <span class="text-danger" style="font-size: 13px">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Approved date -->
                            <div class="form-group row existing-vendor-option d-none">
                                <label class="col-sm-4 col-form-label" for="vendorappr" id="lblappr" style="font-size:13px;">Approved date</label>
                                <div class="col-sm-8">
                                    <input class="form-control @error('vendorappr') is-invalid @enderror"
                                           value="{{ old('vendorappr') }}"
                                           type="Date" id="vendorappr" name="vendorappr" style="font-size:13px;">
                                    @error('vendorappr')
                                    <span class="text-danger" style="font-size: 13px">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- product/service -->
                            <div class="form-group row both-option d-none">
                                <label class="col-sm-4 col-form-label" for="vendorscope" id="lblscope" style="font-size:13px;">product/service<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <textarea type="text" class="form-control @error('vendorscope') is-invalid @enderror"
                                              id="vendorscope" name="vendorscope"
                                              placeholder="Enter scope" style="font-size:13px;">{{ old('vendorscope') }}</textarea>
                                    @error('vendorscope')
                                    <span class="text-danger" style="font-size: 13px">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Country -->
                            <div class="form-group row both-option d-none">
                                <label class="col-sm-4 col-form-label" for="vendoradd" id="lbladd" style="font-size:13px;">Country<span class="text-danger">*</span></label>
                                <div class="col-sm-8" id="country">
                                    <select class="js-select2 form-control @error('vendoradd') is-invalid @enderror" style="font-size: 13px;" id="vendoradd" name="vendoradd"
                                            style="width: 100%;" data-placeholder="Choose a country">
                                        <option></option>
                                        @if(count($countries)> 0 )
                                            @foreach($countries as $value)
                                                <option value="{{$value->id}}" {{ old('vendoradd') == $value->id ? 'selected' : ''  }}>
                                                    {{ $value->country_name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('vendoradd')
                                    <span class="text-danger" style="font-size: 13px">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Location -->
                            <div class="form-group row both-option d-none">
                                <label class="col-sm-4 col-form-label" for="vendorloc" id="lblloc" style="font-size:13px;">Location<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control @error('vendorloc') is-invalid @enderror" id="vendorloc"
                                           value="{{ old('vendorloc') }}"
                                           name="vendorloc" placeholder="Enter location" style="font-size:13px;">
                                    @error('vendorloc')
                                    <span class="text-danger" style="font-size: 13px">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="form-group row both-option d-none">
                                <label class="col-sm-4 col-form-label" for="vendoremail" id="lblemail" style="font-size:13px;">Email<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="email" class="form-control @error('vendoremail') is-invalid @enderror" id="vendoremail"
                                             value="{{ old('vendoremail') }}"
                                           name="vendoremail" placeholder="Enter email" style="font-size:13px;">
                                    @error('vendoremail')
                                    <span class="text-danger" style="font-size: 13px">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Contact -->
                            <div class="form-group row both-option d-none">
                                <label class="col-sm-4 col-form-label"
                                       for="vendorcontact" id="lblcontact" style="font-size:13px;">Contact<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control @error('vendorcontact') is-invalid @enderror" id="vendorcontact"
                                             value="{{ old('vendorcontact') }}"
                                           name="vendorcontact" placeholder="Enter contact" style="font-size:13px;">
                                    @error('vendorcontact')
                                    <span class="text-danger" style="font-size: 13px">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Is this vendor certified -->
                            <div class="form-group row both-option d-none align-items-center">
                                <label class="col-sm-4 col-form-label" for="has_certificate" id="lblhas_certificate"
                                       style="font-size:13px;">Is this vendor certified<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <div class="form-check d-flex px-0">
                                        <div class="d-flex align-items-center"
                                               id="has_certificate_yes" style="font-size:13px;">
                                            <input class="check-cer mx-1 @error('has_certificate') is-invalid @enderror" type="radio"
                                                   {{ old('has_certificate') == 'yes' ? 'checked' : '' }}
                                                   name="has_certificate"
                                                   id="has_certificate_yes" value="yes" style="font-size:13px;"><span class="font-w600">Yes</span>
                                        </div>
                                        <div class="d-flex align-items-center mx-4"
                                               id="has_certificate_no" style="font-size:13px;">
                                            <input class="check-cer mx-1 @error('has_certificate') is-invalid @enderror"
                                                   {{ old('has_certificate') == 'no' ? 'checked' : '' }}
                                                   type="radio" name="has_certificate"
                                                   id="has_certificate_no" value="no" style="font-size:13px;"><span class="font-w600">No</span>
                                        </div>
                                    </div>
                                    @error('has_certificate')
                                    <div><span class="text-danger" style="font-size: 13px">{{ $message }}</span></div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Certified iso standards -->
                            <div class="form-group row d-none cis-wrap">
                                <label class="col-sm-4 col-form-label" for="vendor_iso" id="lbliso" style="font-size:13px;">Certified iso standards<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <div class="form-check">
                                        @foreach($certificateStandard as $s)
                                            <label class="form-check-label vendor_iso" style="font-size:13px;">
                                                <input class="form-check-input has-cer"
                                                       {{ in_array($s->id, old('vendor_iso', [])) ? 'checked' : '' }}
                                                       type="checkbox" name="vendor_iso[]" value="{{ $s->id }}"><span>{{ $s->name }}</span>
                                            </label><br>
                                        @endforeach
                                    </div>
                                    @error('vendor_iso')
                                    <span class="text-danger" style="font-size: 13px">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>

                            <!-- Certified iso standards: Not certified -->
                            <div class="form-group row d-none no-cis-wrap">
                                <label class="col-sm-4 col-form-label" for="vendor_iso"
                                       id="lbliso" style="font-size:13px;">Certified iso standards<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <label class="form-check-label vendor_iso" style="font-size:13px;">
                                        Not certified
                                    </label>
                                </div>
                            </div>

                            <!-- Year of establishment -->
                            <div class="form-group row both-option d-none" id="year_of_establishment">
                                <label class="col-sm-4 col-form-label" for="vendor_yoe" id="lblyoe" style="font-size:13px;">Year of establishment</label>
                                <div class="col-sm-8">
                                    <select class="js-select2 form-control @error('vendor_yoe') is-invalid @enderror"
                                            name="vendor_yoe" id="vendor_yoe" style="font-size:13px;width:100%;" data-placeholder="Choose year">
                                        <option></option>
                                        <option selected="" value="0" style="font-size:13px;">Choose year</option>
                                        @for($year= date('Y'); $year >= config('constants.vendor.start_year'); $year--)
                                            <option style="font-size:13px;"
                                                {{ old('vendor_yoe') == $year ? 'selected' : '' }}
                                                value="{{ $year }}">{{ $year }}</option>
                                        @endfor
                                    </select>
                                    @error('vendor_yoe')
                                    <span class="text-danger" style="font-size: 13px">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Vendor type -->
                            @php
                                $vendorType = [
                                    'critical' => 'Critical',
                                    'semi_critical' => 'Semi-critical',
                                    'non_critical' => 'Non critical'
                                ]
                            @endphp
                            <div class="form-group row both-option d-none align-items-center">
                                <label class="col-sm-4 col-form-label" for="vendortype"
                                       id="lbltype" style="font-size:13px;">Vendor type<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <div class="form-check d-flex px-0" style="font-size:13px;">
                                        @foreach($vendorType as $key => $value)
                                            <div class="d-flex align-items-center mr-2">
                                                <input class="mx-1" type="radio"
                                                       {{ old('vendortype') == $key ? 'checked' : '' }}
                                                       name="vendortype" id="vendortype{{$key}}" value="{{ $key }}" style="font-size:13px;">
                                                <span class="font-w600">{{ $value }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('vendortype')
                                    <span class="text-danger" style="font-size: 13px">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Frequency of evaluation -->
                            @php
                                $frequency = [
                                    'quarterly' => 'Quarterly',
                                    'half_year' => 'Half year',
                                    'one_year' => 'One year',
                                    'one_half_year' => '1.5 year',
                                    'two_year' => 'Two year',
                                    'three_year' => 'Three year',
                                    'four_year' => 'Four year',
                                    'five_year' => 'Five year'
                                ]
                            @endphp
                            <div class="form-group row both-option d-none">
                                <label class="col-sm-4 col-form-label"
                                       for="vendor_eval" id="lbleval" style="font-size:13px;">Frequency of evaluation<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <select class="form-control @error('vendor_eval') is-invalid @enderror" name="vendor_eval"
                                            id="vendor_eval" style="font-size:13px;" placeholder="Choose Frequency">
                                        <option value="0" selected="" disabled>Choose</option>
                                        @foreach($frequency as $key => $value)
                                            <option value="{{ $key }}" {{ old('vendor_eval') == $key ? 'selected' : ''  }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('vendor_eval')
                                    <span class="text-danger" style="font-size: 13px">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Any trial order made -->
                            <div class="form-group row new-vendor-option d-none">
                                <label class="col-sm-4 col-form-label"
                                       for="vendor_trial" id="lbltrial" style="font-size:13px;">Any trial order made<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <select class="form-control @error('vendor_trial') is-invalid @enderror"
                                            name="vendor_trial" id="vendor_trial" style="font-size:13px;">
                                        <option selected="" disabled value="0">Choose</option>
                                        <option value="yes" {{ old('vendor_trial') == 'yes' ? 'selected' : '' }}>Yes</option>
                                        <option value="no" {{ old('vendor_trial') == 'no' ? 'selected' : '' }}>No</option>
                                    </select>
                                    @error('vendor_trial')
                                    <span class="text-danger" style="font-size: 13px">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- What was the outcome of the trial order -->
                            @php
                                $trialOutcome = [
                                    'good' => 'Good',
                                    'satisfactory' => 'Satisfactory',
                                    'poor' => 'Poor'
                                ]
                            @endphp
                            <div class="form-group row vendor-trial-yes d-none align-items-center">
                                <label class="col-sm-4 col-form-label" for="vendoroutcome" id="lbloutcome" style="font-size:13px;">What was the outcome of the trial order</label>
                                <div class="col-sm-8">
                                    <div class="form-check d-flex px-0" style="font-size:13px;">
                                        @foreach($trialOutcome as $key => $value)
                                            <div class="d-flex align-items-center mr-2">
                                                <input class="mx-1" type="radio"
                                                       {{ old('vendoroutcome') == $key ? 'checked' : '' }}
                                                       name="vendoroutcome" id="vendoroutcome{{$key}}" value="{{ $key }}" style="font-size:13px;">
                                                <span class="font-w600">{{ $value }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('vendoroutcome')
                                    <span class="text-danger" style="font-size: 13px">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Basis of approval -->
                            @php
                                $basicOfApproval = [
                                    'based_on_past_experience' => 'Based on past experience',
                                    'trial_order' => 'Trial order',
                                    'suggested_by_customer' => 'Suggested by customer',
                                    'sole_supplier' => 'Sole supplier',
                                    'cost_effective' => 'Cost effective',
                                    'reputed_company' => 'Reputed company'
                                ]
                            @endphp
                            <div class="form-group row existing-vendor-option d-none">
                                <label class="col-sm-4 col-form-label" for="vendorbasis_appr" id="lblbaoap" style="font-size:13px;">Basis of approval</label>
                                <div class="col-sm-8">
                                    <div class="form-check">
                                        @foreach($basicOfApproval as $key => $value)
                                            <label class="form-check-label" style="font-size:13px;">
                                                <input class="form-check-input" type="checkbox"
                                                       {{ in_array($key, old('vendorbasis_appr', [])) ? 'checked' : '' }}
                                                       name="vendorbasis_appr[]" id="vendorbasis_appr{{$key}}"
                                                       value="{{ $key }}">
                                                <span>{{ $value }}</span>
                                            </label><br>
                                        @endforeach
                                    </div>
                                    @error('vendorbasis_appr')
                                    <span class="text-danger" style="font-size: 13px">{{ $message }}</span>
                                    @enderror
                                    @error('vendorbasis_appr.*')
                                    <span class="text-danger" style="font-size: 13px">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Addition of new vendor based on -->
                            @php
                                $additionOfNewVendor = [
                                    'suggested_by_customer' => 'Suggested by customer',
                                    'single_source' => 'Single source',
                                    'economical_cost_effective' => 'Economical/cost effective',
                                    'reputed_company_brand_agency' => 'Reputed company/brand/agency',
                                    'suggested_by_management' => 'Suggested by management'
                                ]
                            @endphp
                            <div class="form-group row new-vendor-option d-none">
                                <label class="col-sm-4 col-form-label" for="vendorbasis_add" id="lbladdb" style="font-size:13px;">Addition of new vendor based on</label>
                                <div class="col-sm-8">
                                    <div class="form-check">
                                        @foreach($additionOfNewVendor as $key => $value)
                                        <label class="form-check-label" style="font-size:13px;">
                                            <input class="form-check-input" type="checkbox"
                                                   {{ in_array($key, old('vendorbasis_add', [])) ? 'checked' : '' }}
                                                   name="vendorbasis_add[]"
                                                   id="vendorbasis_add{{ $key }}" value="{{ $key }}"><span>{{ $value }}</span>
                                        </label><br>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Comment -->
                            @if(in_array(Auth::user()->email, $hods))
                            <div class="form-group row new-vendor-option d-none">
                                <label class="col-sm-4 col-form-label" for="vendorcomments" id="lblcomm" style="font-size:13px;">Add comments</label>
                                <div class="col-sm-8">
                                    <textarea type="text" style="font-size:13px;" class="form-control @error('vendorcomments') is-invalid @enderror" id="vendorcomments"
                                              name="vendorcomments" placeholder="Enter comments">{{ old('vendorcomments') }}</textarea>
                                    @error('vendorcomments')
                                    <span class="text-danger" style="font-size: 13px">{{ $message }}</span>
                                    @enderror
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
    </div>
</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        const vendorGroupEl = $('#vendorgroup');
        const checkCertificatedEl = $('.check-cer');
        const vendorTrialOptionEl = $('#vendor_trial');
        const toggleOptions = (vendorGroupVal) => {
            if (vendorGroupVal === "Existing") {
                $('.both-option').removeClass('d-none');
                $('.existing-vendor-option').removeClass('d-none');
                $('.new-vendor-option').addClass('d-none');
            } else if (vendorGroupVal === "New") {
                $('.both-option').removeClass('d-none');
                $('.existing-vendor-option').addClass('d-none');
                $('.new-vendor-option').removeClass('d-none');
            } else {
                $('.both-option').addClass('d-none');
                $('.existing-vendor-option').addClass('d-none');
                $('.new-vendor-option').addClass('d-none');
            }
        }
        const toggleCertification = (hasCertificateVal) => {
            if (hasCertificateVal === "yes") {
                $('.cis-wrap').removeClass('d-none');
                $('.no-cis-wrap').addClass('d-none');
            } else {
                $('.no-cis-wrap').removeClass('d-none');
                $('.cis-wrap').addClass('d-none');
                $('.has-cer').prop('checked', false);
            }
        }
        const toggleTrial = (vendorTrialVal) => {
            if (vendorTrialVal === "yes") {
                $('.vendor-trial-yes').removeClass('d-none');
            } else {
                $('.vendor-trial-yes').addClass('d-none');
            }
        }

        /** Vendor group handling */
        if(vendorGroupEl.val() !== null) {
            toggleOptions(vendorGroupEl.val());
        }
        vendorGroupEl.change(function() {
            const selected = $(this).val();
            toggleOptions(selected);
        });

        /** Check certificated handling */
        if(checkCertificatedEl.is(':checked')) {
            toggleCertification($('.check-cer:checked').val());
        }
        $('body').on('click', '.check-cer', function () {
            const hasCertificateVal = $(this).val();
            toggleCertification(hasCertificateVal);
        })

        /** Vendor trial handling */
        if(vendorTrialOptionEl.val() !== null) {
            toggleTrial(vendorTrialOptionEl.val());
        }
        vendorTrialOptionEl.change(function() {
            toggleTrial($(this).val());
        });
    });
</script>

</html>
@endsection
