@extends('layouts.multiselect')
@section('content')
<!doctype html>
<html lang="en">
<script>
    function ddlyear() {
        var d = document.getElementById("vendor_yoe");
        var displaytext = d.options[d.selectedIndex].text;
        document.getElementsByName('yoe')[0].value = displaytext;
    }

    function ddleval() {
        var d = document.getElementById("vendor_eval");
        var displaytext = d.options[d.selectedIndex].text;
        document.getElementsByName('eval_readonly')[0].value = displaytext;
    }

    function ddltrial() {
        var d = document.getElementById("vendor_trial");
        var dt = d.options[d.selectedIndex].text;
        if (dt == "Yes") {
            document.getElementById("lbloutcome").style.visibility = 'visible';
            document.getElementsByName('vendoroutcome')[0].style.visibility = 'visible';
        } else {
            document.getElementById("lbloutcome").style.visibility = 'hidden';
            document.getElementsByName('vendoroutcome')[0].style.visibility = 'hidden';
        }

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
                            <a class="link-fx" href="">Edit Vendor</a>
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

                    <div class="col-lg-8">
                        <!-- Form Horizontal - Default Style -->
                        <form class="mb-5" action="{{url('/user/vendor/edit')}}" method="POST" id="reeval" onsubmit="return fun()">
                            {{csrf_field()}}
                            @if(count($errors) >0 )
                            <ul class='alert alert-warning'>
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            @endif

                            {{-- @if(count($data)>0) --}}
                            {{-- @foreach($data as $dat) --}}
                            <input type="hidden" id="id" name="vendor_id" value=" {{ $data->id }} ">

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="vendorname" style="font-size:13px;">Vendor name</label>
                                <div class="col-sm-8">
                                    <input type="text" style="font-size:13px;" class="form-control" id="vendorname" name="vendorname" value="{{ $data->vendor_name }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="vendorgroup" style="font-size:13px;">Purpose of addition</label>
                                <div class="col-sm-8">
                                    <input type="text" style="font-size:13px;" class="form-control" id="vendorgroup" name="vendorgroup" value="{{ $data->vendor_group}}" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="vendorscope" id="lblscope" style="font-size:13px;">product/service<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <textarea type="text" style="font-size:13px;" class="form-control" id="vendorscope" name="vendorscope" required>{{ $data->scope}}</textarea>
                                </div>
                            </div>
                            {{-- <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="vendoradd" id="lbladd" style="font-size:13px;">Country<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="vendoradd" name="vendoradd" required style="font-size:13px;" value="{{$data->address}}">
                                </div>
                            </div> --}}
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="vendoradd" id="lbladd" style="font-size:13px;">Country</label>
                                <div class="col-sm-8" id="country">
                                    <select class="js-select2 form-control" style="font-size: 13px;" id="vendoradd" name="vendoradd" style="width: 100%;" data-placeholder="Choose a country" multiselect>
                                        <option></option>
                                        @if(count($countries)> 0 )
                                        @foreach($countries as $value)
                                        <option value="{{$value->id}}" {{ $value->id == $data->country_id ? 'selected' : '' }}>{{ $value->country_name }}</option></br>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="vendorloc" id="lblloc" style="font-size:13px;">Location <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="vendorloc" name="vendorloc" value="{{$data->location}}" style="font-size:13px;" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="vendoremail" id="lblemail" style="font-size:13px;">Email<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="vendoremail" name="vendoremail" style="font-size:13px;" value="{{$data->email}}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="vendorcontact" id="lblcontact" style="font-size:13px;">Contact<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="vendorcontact" name="vendorcontact" style="font-size:13px;" value="{{$data->contact}}" required>
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
                                <label class="col-sm-4 col-form-label" for="yoe" id="lblyoe" style="font-size:13px;">Selected year of establishment</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="yoe" name="yoe" value="{{$data->Yoe??'Select year'}}" readonly style="font-size:13px;">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="vendor_yoe" id="lblyoe" style="font-size:13px;">Select year of establishment</label>
                                <div class="col-sm-8">
                                    <select class="form-control js-select2" style="font-size:13px;width:100%" name="vendor_yoe" id="vendor_yoe" onchange="ddlyear()" data-placeholder="Choose year">
                                        <option selected="" value="0" style="font-size:13px;">Select year</option>
                                        <?php $y = date("Y");
                                        for ($year= $y; $year >= config('constants.vendor.start_year'); $year--): ?>
                                        <option value="<?=$year;?>" <?=$year == $data->Yoe ? 'selected' : '' ?>><?=$year;?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="vendortype" id="lbltype" style="font-size:13px;">Vendor type</label>
                                <div class="col-sm-8">
                                    <div class="form-check">
                                        <label class="form-check-label" for="vendortype" id="lbltype1" style="font-size:13px;">
                                            <input class="form-check-input" type="radio" name="vendortype" id="vendortype1" value="critical" style="font-size:13px;" {{ $data->vendor_type == 'critical' ? 'checked' : '' }}><span></span>Critical &nbsp;&nbsp;&nbsp;&nbsp;
                                        </label>
                                        &nbsp;&nbsp;&nbsp; <label class="form-check-label" for="vendortype" id="lbltype2" style="font-size:13px;">
                                            <input class="form-check-input" type="radio" name="vendortype" id="vendortype2" value="semi_critical" style="font-size:13px;" {{ $data->vendor_type == 'semi_critical' ? 'checked' : '' }}><span></span>Semi-Critical&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                        </label>
                                        &nbsp;&nbsp;&nbsp; <label class="form-check-label" for="vendortype" id="lbltype3" style="font-size:13px;">
                                            <input class="form-check-input" type="radio" name="vendortype" id="vendortype3" value="non_critical" style="font-size:13px;" {{ $data->vendor_type == 'non_critical' ? 'checked' : '' }}><span></span>Non critical

                                        </label>

                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="eval" id="lbleval" style="font-size:13px;">Selected frequency of evaluation</label>
                                <div class="col-sm-8">

                                    @php
                                    switch($data->eval){
                                    case 'quarterly':
                                    $eval_value = 'Quarterly';
                                    break;
                                    case 'half_year':
                                    $eval_value = 'Half year';
                                    break;

                                    case 'one_year':
                                    $eval_value = 'One year';
                                    break;

                                    case 'one_half_year':
                                    $eval_value = '1.5 year';
                                    break;

                                    case 'two_year':
                                    $eval_value = 'Two year';
                                    break;
                                    case 'three_year':
                                    $eval_value = 'Three year';
                                    break;
                                    case 'four_year':
                                    $eval_value = 'Four year';
                                    break;
                                    case 'five_year':
                                    $eval_value = 'Five year';
                                    break;

                                    }
                                    @endphp

                                    <input type="text" class="form-control" value="{{$eval_value}}" id="eval_readonly" name="eval_readonly" readonly style="font-size:13px;">
                                    <input type="hidden" name="eval_hidden" value="{{$data->eval}}" style="font-size:13px;">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="vendor_eval" id="lbleval" style="font-size:13px;">Select frequency of evaluation</label>
                                <div class="col-sm-8">
                                    <select class="form-control" name="vendor_eval" id="vendor_eval" onchange="ddleval()" style="font-size:13px;">
                                        <option value="0" selected="" disabled style="font-size:13px;">Choose</option>
                                        <option value="quarterly" {{ $data->eval == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                        <option value="half_year" {{ $data->eval == 'half_year' ? 'selected' : '' }}>Half year</option>
                                        <option value="one_year" {{ $data->eval == 'one_year' ? 'selected' : '' }}>One year</option>
                                        <option value="one_half_year" {{ $data->eval == 'one_half_year' ? 'selected' : '' }}>1.5 year</option>
                                        <option value="two_year" {{ $data->eval == 'two_year' ? 'selected' : '' }}>Two year</option>
                                        <option value="three_year" {{ $data->eval == 'three_year' ? 'selected' : '' }}>Three year</option>
                                        <option value="four_year" {{ $data->eval == 'four_year' ? 'selected' : '' }}>Four year</option>
                                        <option value="five_year" {{ $data->eval == 'five_year' ? 'selected' : '' }}>Five year</option>
                                    </select>
                                </div>
                            </div>

                            @if($data->vendor_group=="New")
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="vendorbasis_add" id="lbladdb" style="font-size:13px;">Addition of new vendor based on</label>
                                <div class="col-sm-8">
                                    <div class="form-check">
                                        <label class="form-check-label" id="lbladdb1" style="font-size:13px;">
                                            <input class="form-check-input" type="checkbox" name="vendorbasis_add[]" id="vendorbasis_add1" value="suggested_by_customer"><span></span>Suggested By customer &nbsp;&nbsp;&nbsp;&nbsp;
                                        </label><br>
                                        <label class="form-check-label" id="lbladdb2" style="font-size:13px;">
                                            <input class="form-check-input" type="checkbox" name="vendorbasis_add[]" id="vendorbasis_add2" value="single_source"><span></span>Single source&nbsp;&nbsp;&nbsp;&nbsp;
                                        </label><br>
                                        <label class="form-check-label" id="lbladdb3" style="font-size:13px;">
                                            <input class="form-check-input" type="checkbox" name="vendorbasis_add[]" id="vendorbasis_add3" value="economical_cost_effective"><span></span>Economical/Cost effective&nbsp;&nbsp;&nbsp;&nbsp;
                                        </label><br>
                                        <label class="form-check-label" id="lbladdb4" style="font-size:13px;">
                                            <input class="form-check-input" type="checkbox" name="vendorbasis_add[]" id="vendorbasis_add4" value="reputed_company_brand_agency"><span></span>Reputed company/brand/agency&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </label><br>
                                        <label class="form-check-label" id="lbladdb5" style="font-size:13px;">
                                            <input class="form-check-input" type="checkbox" name="vendorbasis_add[]" id="vendorbasis_add5" value="suggested_by_management"><span></span>Suggested By management
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="vendor_trial" id="lbltrial" style="font-size:13px;">Trial order Made</label>
                                <div class="col-sm-8">
                                    <select class="form-control" name="vendor_trial" id="vendor_trial" onchange="ddltrial()" style="font-size:13px;">
                                        <option selected="" disabled value="0" style="font-size:13px;">Choose</option>
                                        <option value="yes" {{ $data->trial_order == 'yes' ? 'selected' : '' }}>Yes</option>
                                        <option value="no" {{ $data->trial_order == 'no' ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="vendoroutcome" id="lbloutcome" style="font-size:13px;">Trial outcome<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    {{-- <input type="text" class="form-control" id="out" name="out" value="{{$data->outcome}}" required style="font-size:13px;"> --}}
                                    <select class="form-control" name="vendoroutcome" id="vendoroutcome" style="font-size:13px;" required>
                                        <option selected="" disabled value="0">Choose</option>
                                        <option value="good" {{ $data->outcome == 'good' ? 'selected' : '' }}>Good</option>
                                        <option value="satisfactory" {{ $data->outcome == 'satisfactory' ? 'selected' : '' }}>Satisfactory</option>
                                        <option value="poor" {{ $data->outcome == 'poor' ? 'selected' : '' }}>Poor</option>
                                    </select>
                                </div>
                            </div>
                            @endif

                            @if($data->vendor_group=="Existing")
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="vendorbasis_appr" id="lblbaoap" style="font-size:13px;">Basis of approval</label>
                                <div class="col-sm-8">
                                    <div class="form-check">
                                        <label class="form-check-label" id="lblappr1" style="font-size:13px;">
                                            <input class="form-check-input" type="checkbox" name="vendorbasis_appr[]" id="vendorbasis_appr1" value="based_on_past_experience"><span></span>Based on Past Experience&nbsp;&nbsp;&nbsp;&nbsp;
                                        </label><br>
                                        <label class="form-check-label" id="lblappr2" style="font-size:13px;">
                                            <input class="form-check-input" type="checkbox" name="vendorbasis_appr[]" id="vendorbasis_appr2" value="trial_order"><span></span>Trial Order&nbsp;&nbsp;&nbsp;&nbsp;
                                        </label><br>
                                        <label class="form-check-label" id="lblappr3" style="font-size:13px;">
                                            <input class="form-check-input" type="checkbox" name="vendorbasis_appr[]" id="vendorbasis_appr3" value="suggested_by_customer"><span></span>Suggested By customer&nbsp;&nbsp;&nbsp;&nbsp;
                                        </label><br>
                                        <label class="form-check-label" id="lblappr4" style="font-size:13px;">
                                            <input class="form-check-input" type="checkbox" name="vendorbasis_appr[]" id="vendorbasis_appr4" value="sole_supplier"><span></span>Sole supplier&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </label><br>
                                        <label class="form-check-label" id="lblappr5" style="font-size:13px;">
                                            <input class="form-check-input" type="checkbox" name="vendorbasis_appr[]" id="vendorbasis_appr5" value="cost_effective"><span></span>Cost Effective&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </label><br>
                                        <label class="form-check-label" id="lblappr6" style="font-size:13px;">
                                            <input class="form-check-input" type="checkbox" name="vendorbasis_appr[]" id="vendorbasis_appr6" value="reputed_company"><span></span>Reputed Company
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="vendorappr" id="lblappr" style="font-size:13px;">Approved date</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="Date" id="vendorappr" name="vendorappr" value="{{$data->approved_date}}" style="font-size:13px;">
                                </div>
                            </div>
                            @endif
                            <input type="hidden" class="form-control" id="bas_add" name="bas_add" value="{{$data->Basis_addition}}">
                            <input type="hidden" class="form-control" id="triall" name="triall" value="{{$data->trial_order}}">
                            <input type="hidden" class="form-control" id="basis" name="basis" value="{{$data->basis_approval}}">
                            <input type="hidden" class="form-control" id="type" name="type" value="{{$data->vendor_type}}">
                            <div class="form-group row">
                                <div class="col-sm-8 ml-auto">
                                    <button type="submit" class="btn btn-primary" style="font-size:13px;margin-left:325px;">Submit</button>
                                </div>
                            </div>
                            {{-- @endforeach
                            @endif --}}
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
                                <!-- <div class="col-6">

                                        <a class="block block-rounded block-link-shadow bg-body" href="{{url('/user/vendor/addvendor')}}" >
                                            <div class="block-content text-center"style="height:130px" >
                                             <i class="fas fa-plus-circle fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-4">
                                                Add Vendor
                                                </p>
                                            </div>
                                        </a>

                                    </div> -->
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
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        //for basis of approval checkbox
        var b = document.getElementsByName('basis')[0].value;
        var b1 = b.includes("based_on_past_experience");
        if (b1 == true) {
            document.getElementById("vendorbasis_appr1").checked = true;

        }
        var b2 = b.includes("trial_order");
        if (b2 == true) {
            document.getElementById("vendorbasis_appr2").checked = true;

        }
        var b3 = b.includes("suggested_by_customer");
        if (b3 == true) {
            document.getElementById("vendorbasis_appr3").checked = true;

        }
        var b4 = b.includes("sole_supplier");
        if (b4 == true) {
            document.getElementById("vendorbasis_appr4").checked = true;

        }
        var b5 = b.includes("cost_effective");
        if (b5 == true) {
            document.getElementById("vendorbasis_appr5").checked = true;

        }
        var b6 = b.includes("reputed_company");
        if (b6 == true) {
            document.getElementById("vendorbasis_appr6").checked = true;

        }


        //for vendor type radio button
        var tt = document.getElementById('type').value;
        if (tt == "Critical") {
            document.getElementById("vendortype1").checked = true;

        }
        if (tt == "SemiCritical") {
            document.getElementById("vendortype2").checked = true;

        }
        if (tt == "Non critical") {
            document.getElementById("vendortype3").checked = true;

        }

        //for basis of addition checkbox
        var t = document.getElementsByName('bas_add')[0].value;
        var one = t.includes("suggested_by_customer");
        if (one == true) {
            document.getElementById("vendorbasis_add1").checked = true;

        }
        var two = t.includes("single_source");
        if (two == true) {
            document.getElementById("vendorbasis_add2").checked = true;

        }
        var three = t.includes("economical_cost_effective");
        if (three == true) {
            document.getElementById("vendorbasis_add3").checked = true;

        }
        var four = t.includes("reputed_company_brand_agency");
        if (four == true) {
            document.getElementById("vendorbasis_add4").checked = true;

        }
        var five = t.includes("suggested_by_management");
        if (five == true) {
            document.getElementById("vendorbasis_add5").checked = true;

        }

        //for vendor trial dropdown
        var trial = document.getElementsByName('triall')[0].value;
        if (trial == "yes") {
            document.getElementById("vendor_trial").value = 'yes';
        } else if (trial == "no") {
            document.getElementById("vendor_trial").value = 'no';
            document.getElementById("lbloutcome").style.visibility = 'hidden';
            document.getElementsByName('vendoroutcome')[0].style.visibility = 'hidden';
        }

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

    });

</script>
</html>
@endsection
