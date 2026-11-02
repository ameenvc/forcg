<style>
    .warranty_coverage_btn:focus {
        box-shadow: 0 0 0 0.25rem rgba(81, 121, 214, 0.25);
    }
</style>

@if ($complaintTemplateSectionFields['customer']['enable_field'] || $complaintTemplateSectionFields['customerphone']['enable_field'] || $complaintTemplateSectionFields['customeremail']['enable_field'] || $complaintTemplateSectionFields['customeraddress']['enable_field'] || $complaintTemplateSectionFields['product']['enable_field'] )
    <div class="row p-0">
        <fieldset class="col-sm-12 row p-0 ml-4" style="border:2px solid #dfe4e8;max-width:95%;">
            <legend style="font-size:15px;width:90px;text-weight:bold;" class="bg-primary text-white p-1">Customer</legend>
            @if ($complaintTemplateSectionFields['customer']['enable_field'])
                <div class="form-group col-sm-12 col-md-4 px-0" id="customerDiv">
                    <label class="col-sm-12 col-form-label" for="Customer Name" style="font-size: 13px;">{{$complaintTemplateSectionFields['customer']['field_display_label']}} @if($complaintTemplateSectionFields['customer']['field_is_mandatory'])<span class="text-danger">*</span> @endif</label>
                    <div class="col-sm-12">
                        <select data-placeholder="select {{$complaintTemplateSectionFields['customer']['field_display_label']}}" class="form-control" name="customer" id="customer" style="font-size: 13px;width:100%;" {{ $complaintTemplateSectionFields['customer']['field_is_mandatory'] ? 'required' : ''}}>
                            <option value="" selected disabled>Choose Customer</option>
                            @if(count($customer)>0)
                            @foreach($customer as $dep)
                            <option value="{{$dep->id}}" {{$data->customer==$dep->id ? 'selected' : ''}}>{{$dep->customer}}</option></br>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            @endif

            @if ($complaintTemplateSectionFields['customerphone']['enable_field'])
                <div class="form-group col-sm-12 col-md-4 px-0">
                    <label class="col-sm-12 col-form-label" for="Customer Phone" style="font-size: 13px;">{{$complaintTemplateSectionFields['customerphone']['field_display_label']}} @if($complaintTemplateSectionFields['customerphone']['field_is_mandatory'])<span class="text-danger">*</span> @endif</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" style="font-size: 13px;" id="customerphone" name="customerphone" value="{{$data->customer_phone}}" placeholder="Enter {{$complaintTemplateSectionFields['customerphone']['field_display_label']}}" {{$complaintTemplateSectionFields['customerphone']['field_is_mandatory'] ? 'required' : ''}}>
                    </div>
                </div>
            @endif
            @if ($complaintTemplateSectionFields['customeremail']['enable_field'])
                <div class="form-group col-sm-12 col-md-4 px-0">
                    <label class="col-sm-12 col-form-label" for="Customer Email" style="font-size: 13px;">{{$complaintTemplateSectionFields['customeremail']['field_display_label']}}@if($complaintTemplateSectionFields['customeremail']['field_is_mandatory'])<span class="text-danger">*</span> @endif</label>
                    <div class="col-sm-12">
                        <input type="email" class="form-control" style="font-size: 13px;" id="customeremail" name="customeremail" value="{{$data->customer_email}}" placeholder="Enter {{$complaintTemplateSectionFields['customeremail']['field_display_label']}}" {{$complaintTemplateSectionFields['customeremail']['field_is_mandatory'] ? 'required' : ''}}>
                    </div>
                </div>
            @endif
            @if ($complaintTemplateSectionFields['customeraddress']['enable_field'])
                <div class="form-group col-sm-12 col-md-4 px-0">
                    <label class="col-sm-12 col-form-label" for="Customer Address" style="font-size: 13px;">{{$complaintTemplateSectionFields['customeraddress']['field_display_label']}}@if($complaintTemplateSectionFields['customeraddress']['field_is_mandatory'])<span class="text-danger">*</span> @endif</label>
                    <div class="col-sm-12">
                        <textarea class="form-control" style="font-size: 13px;" id="customeraddress" name="customeraddress" placeholder="Enter {{$complaintTemplateSectionFields['customeraddress']['field_display_label']}}" {{$complaintTemplateSectionFields['customeraddress']['field_is_mandatory'] ? 'required' : ''}}>{{$data->customer_address}}</textarea>
                    </div>
                </div>
            @endif
            @if ($complaintTemplateSectionFields['product']['enable_field'])
                <div class="form-group col-sm-12 col-md-4 px-0" id="productServiceDiv">
                    <label class="col-sm-12 col-form-label" for="product" style="font-size: 13px;">{{$complaintTemplateSectionFields['product']['field_display_label']}} @if($complaintTemplateSectionFields['product']['field_is_mandatory'])<span class="text-danger">*</span> @endif</label>
                    <div class="col-sm-12">
                        <select class="form-control js-select2" id="product" name="product[]" style="font-size: 13px; width: 100%;" {{$complaintTemplateSectionFields['product']['field_is_mandatory'] ? 'required' : ''}} multiple data-placeholder="Select {{$complaintTemplateSectionFields['product']['field_display_label']}}">
                            @if(count($product) > 0)
                                @foreach($product as $value)
                                    <option value="{{ $value->id }}" {{ in_array($value->id,explode(',',$data->product)) ? 'selected' : '' }}>
                                        {{ $value->product }}
                                    </option>
                                @endforeach
                            @endif
                        </select>

                    </div>
                </div>
            @endif
        </fieldset>
    </div>
@endif

<div class="row">
    @if ($complaintTemplateSectionFields['job_order_no']['enable_field'])
        <div class="form-group col-sm-12 col-md-6 col-lg-3">
            <label class="col-sm-12 col-form-label" for="job_order_no" style="font-size: 13px;">{{$complaintTemplateSectionFields['job_order_no']['field_display_label']}} @if($complaintTemplateSectionFields['job_order_no']['field_is_mandatory'])<span class="text-danger">*</span> @endif</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" style="font-size: 13px;" name="job_order_no" id="job_order_no" placeholder="Enter {{$complaintTemplateSectionFields['job_order_no']['field_display_label']}}" {{$complaintTemplateSectionFields['job_order_no']['field_is_mandatory'] ? 'required' : ''}} value="{{ $data->job_order_no}}">
            </div>
        </div>
    @endif
    @if ($complaintTemplateSectionFields['product_serial_no']['enable_field'])
        <div class="form-group col-sm-12 col-md-6 col-lg-3">
            <label class="col-sm-12 col-form-label" for="title" style="font-size: 13px;">{{$complaintTemplateSectionFields['product_serial_no']['field_display_label']}} @if($complaintTemplateSectionFields['product_serial_no']['field_is_mandatory'])<span class="text-danger">*</span> @endif</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" style="font-size: 13px;" id="product_serial_no" name="product_serial_no" placeholder="Enter {{$complaintTemplateSectionFields['product_serial_no']['field_display_label']}}" {{$complaintTemplateSectionFields['product_serial_no']['field_is_mandatory'] ? 'required' : ''}} value="{{ $data->product_serial_no}}">
            </div>
        </div>
    @endif
    @if ($complaintTemplateSectionFields['project_name']['enable_field'])
        <div class="form-group col-sm-12 col-md-6 col-lg-3">
            <label class="col-sm-12 col-form-label" for="title" style="font-size: 13px;">{{$complaintTemplateSectionFields['project_name']['field_display_label']}} @if($complaintTemplateSectionFields['project_name']['field_is_mandatory'])<span class="text-danger">*</span> @endif</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" style="font-size: 13px;" name="project_name" id="project_name"  {{$complaintTemplateSectionFields['project_name']['field_is_mandatory'] ? 'required' : ''}} placeholder="Enter {{$complaintTemplateSectionFields['project_name']['field_display_label']}}" value="{{ $data->project_name}}">
            </div>
        </div>
    @endif
    <div class="form-group col-sm-12 col-md-6 col-lg-3">
        <label class="col-sm-12 col-form-label" for="warranty_coverage" style="font-size: 13px;">Warranty Coverage <span class="text-danger">*</span></label>
        <div class="col-sm-12">
            <input type="hidden" name="meta[warranty_coverage]" id="warranty_coverage" value="">
            @foreach(App\Constants\Constants::WARRANTY_COVERAGES as $key => $value)
                <input
                    type="button"
                    class="btn btn-sm {{ $value['btn_class']}} {{ (isset($data->warranty_coverage) && $data->warranty_coverage && ($data->warranty_coverage == $value['title'])) ? 'selected_wc_class' : '' }}  warranty_coverage_btn"
                    id="{{ $key }}"
                    style="margin-right:2px;margin-bottom:10px;font-size:12px;"
                    value="{{$value['title']}}">
            @endforeach
            <div id="warranty_coverage-error" class="text-danger" style="display:none;font-size:12px;">Please select a Warranty Coverage.</div>
        </div>
    </div>
</div>
@if ($complaintTemplateSectionFields['job_history']['enable_field'])
    <div class="row">
        <div class="form-group col-sm-12">
            <label class="col-sm-12 col-form-label" for="title" style="font-size: 13px;">{{$complaintTemplateSectionFields['job_history']['field_display_label']}} @if($complaintTemplateSectionFields['job_history']['field_is_mandatory'])<span class="text-danger">*</span> @endif</label>
            <div class="col-sm-12">
                <textarea type="text" rows="4" class="form-control" style="font-size: 13px;" name="job_history" id="job_history" placeholder="Enter {{$complaintTemplateSectionFields['job_history']['field_display_label']}}">{{ $data->job_history }}</textarea>
                <div id="job_history-error" class="text-danger" style="display:none;font-size:12px;">{{$complaintTemplateSectionFields['job_history']['field_display_label']}} is required.</div>
            </div>
        </div>
    </div>
@endif
<div class="row">
    <div class="form-group col-sm-12 col-md-6 col-lg-3">
        <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="type">Type</label>
        <div class="col-sm-12">
            <select class="form-control js-select2" style="font-size: 13px;width:100%;" id="type" name="type" placeholder="Choose Type">
                <option value="" selected>Choose Type</option>
                <option value="High" <?= $data->type == "High" ? 'selected' : '' ?>>High</option>
                <option value="Moderate" <?= $data->type == "Moderate" ? 'selected' : '' ?>>Moderate</option>
                <option value="Low" <?= $data->type == "Low" ? 'selected' : '' ?>>Low</option>
            </select>
        </div>
    </div>
    <div class="form-group col-sm-12 col-md-6 col-lg-3" id="plantDiv">
        <label class="col-sm-12 col-form-label" for="Plant" style="font-size: 13px;">Plant <span class="text-danger">*</span></label>
        <div class="col-sm-12" >
            <select class="form-control js-select2" name="plant" id="plant" style="font-size: 13px;width:100%;" required>
                <option value="" selected>Choose Plant</option>
                @if(count($plants)>0)
                @foreach($plants as $p)
                <option value="{{$p->id}}" <?= $data->plant == $p->id ? 'selected' : '' ?>> {{$p->plant_name}}</option></br>
                @endforeach
                @endif

            </select>
        </div>
    </div>
    <div class="form-group col-sm-12 col-md-6 col-lg-3" id="complaintCategoryDiv">
        <label class="col-sm-12 col-form-label" for="complaint_category_id" style="font-size: 13px;">Complaint Category <span class="text-danger">*</span></label>
        <div class="col-sm-12" >
            <select class="form-control js-select2" name="meta[complaint_category_id]" id="complaint_category_id" style="font-size: 13px;width:100%;" required>
                <option value="" selected>Select Complaint Category</option>
                @foreach($complaint_cat as $cc)
                <option value=" {{$cc->id}}" <?= $data->complaint_category_id == $cc->id ? 'selected' : '' ?>> {{$cc->category_name}}</option></br>
                @endforeach
                {{-- prints complaint category, if complaint category does not exist( this will happen if admin switches plant category dependency )--}}
                @if (!$complaint_cat->contains('id', $data->complaint_category_id))
                    @php
                        $category = $all_complaint_cat->firstWhere('id', $data->complaint_category_id);
                    @endphp
                    @if($category)
                        <option value="{{ $data->complaint_category_id }}" selected>
                            {{ $category->category_name }}
                        </option>
                    @endif
                @endif
            </select>
        </div>
    </div>
    <div class="form-group col-sm-12 col-md-6 col-lg-3" id="vendorDiv">
        <label class="col-sm-12 col-form-label" for="vendor_id" style="font-size: 13px;">Vendor</label>
        <div class="col-sm-12" >
            <select class="form-control js-select2" name="meta[vendor_id]" id="vendor_id" style="font-size: 13px;width:100%;">
                <option value="" selected>Select Vendor</option>
                @foreach($vendors as $vendor)
                    <option value="{{ $vendor->id }}"
                        {{ isset($data->vendor_id) && $data->vendor_id == $vendor->id ? 'selected' : '' }}>
                        {{ $vendor->vendor_name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-sm-12 col-md-6">
        <label class="col-sm-12 col-form-label" for="title" style="font-size: 13px;">Complaint Title <span class="text-danger">*</span></label>
        <div class="col-sm-12">
            <input type="text" class="form-control" style="font-size: 13px;" id="title" name="title" placeholder="Enter Title" value="{{$data->title}}" required>
        </div>
    </div>
    <div class="form-group col-sm-12 col-md-12">
        <label class="col-sm-12 col-form-label" for="complaint" style="font-size: 13px;">Complaint Description <span class="text-danger">*</span></label>
        <div class="col-sm-12">
            <textarea type="text" class="form-control" style="font-size: 13px;" id="complaint" name="complaint" placeholder="Enter description">{{$data->complaint}}</textarea>
            <div id="complaint-error" class="text-danger" style="display:none;font-size:12px;">Complaint Description field is required.</div>
        </div>
    </div>
</div>
@if (isset($data->previously_occured_complaint))
    <div class="row">
        <div class="form-group col-sm-12">
            <label class="col-sm-12 col-form-label" for="name" style="font-size:13px;">Is this a previously occurred Compliant?</label>
            <div class="col-sm-12">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="previously_occured_complaint_yes" name="meta[previously_occured_complaint]" value="yes" {{ isset($data->previously_occured_complaint) && $data->previously_occured_complaint == 'yes' ? 'checked' : '' }}>
                    <label class="form-check-label" for="previously_occured_complaint_yes" style="font-size:13px;">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="previously_occured_complaint_no" name="meta[previously_occured_complaint]" value="no" {{ isset($data->previously_occured_complaint) && $data->previously_occured_complaint == 'no' ? 'checked' : ''}}>
                    <label class="form-check-label" for="previously_occured_complaint_no" style="font-size:13px;">No</label>
                </div>
            </div>
        </div>
        <div class="form-group {{ isset($data->previously_occured_complaint) && $data->previously_occured_complaint == 'no' ? 'd-none' : '' }} col-sm-4" id="no_of_occurrence_div">
            <label class="col-sm-12 col-form-label" for="no_of_occurrence" style="font-size:13px;">No of Occurrence</label>
            <div class="col-sm-12">
                <input type="number" class="form-control" style="font-size: 13px;" id="no_of_occurrence" name="meta[no_of_occurrence]" placeholder="Enter Occurence" value="{{old('meta[no_of_occurrence]') ?? $data->no_of_occurrence ?? ''}}" min="1">
            </div>
        </div>
        <div class="form-group {{ isset($data->previously_occured_complaint) && $data->previously_occured_complaint == 'no' ? 'd-none' : '' }} col-sm-4" id="previous_complaint_reference_div">
            <label class="col-sm-12 col-form-label" for="previous_complaint_reference" style="font-size:13px;">Previous Complaint Reference</label>
            <div class="col-sm-12">
                <select class="form-control" name="meta[previous_complaint_reference]" id="previous_complaint_reference" style="font-size:13px;width:100%;">
                    <option></option>
                </select>
            </div>
        </div>
        <div class="form-group {{ isset($data->previous_complaint_reference) && $data->previous_complaint_reference == '0' ? '' : 'd-none' }} col-sm-4" id="external_reference_div">
            <label class="col-sm-12 col-form-label" for="external_reference" style="font-size:13px;">External Complaint Reference</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" style="font-size: 13px;" id="external_reference" name="meta[external_reference]" value="{{old('meta[external_reference]') ?? $data->external_reference ?? ''}}">
            </div>
        </div>
    </div>
@endif

<div class="row">
    <div class="form-group col-sm-12">
        <label class="col-sm-12 col-form-label" for="action" style="font-size: 13px;">Recommended Action <span class="text-danger">*</span></label>
        <div class="col-sm-12">
            <textarea type="text" class="form-control" id="action" style="font-size: 13px;" name="action" placeholder="Enter Here">{{$data->action}}</textarea>
            <div id="action-error" class="text-danger" style="display:none;font-size:12px;">Recommended Action field is required.</div>
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-sm-12 col-md-4">
        <label class="col-sm-12 col-form-label" for="department" style="font-size: 13px;">Responsible Department <span class="text-danger">*</span></label>
        <div class="col-sm-12">
            <select class="form-control js-select2" id="department" name="department" style="font-size: 13px;width:100%;" required="true" onchange="chooseDivision($(this).val())">
                <option selected disabled>Choose Department</option>
                @if(count($departments)>0)
                @foreach($departments as $dep)
                <option value="{{$dep->id}}" <?= $data->dept == $dep->id ? 'selected' : '' ?>> {{$dep->departmentname}}</option>
                @endforeach
                @endif
            </select>
        </div>
    </div>
    @php
        $divisions = App\Division::where('department_id',$data->dept)->get();
        $areas = App\Area::where('division_id',$data->division_id)->get();
    @endphp
    <div class="form-group col-sm-12 col-md-4 {{count($divisions)>0 ?'':'d-none'}}" id="division">
        <label class="col-sm-12 col-form-label" style="font-size:13px;" for="division">Division</label>
        <div class="col-sm-12">
            <select class="form-control js-select2" id="division_select" name="division" style="font-size: 13px;width:100%;" placeholder="Division" onchange="chooseArea($(this).val())">
                <option selected value=''>Choose Division</option>
                @foreach($divisions as $division)
                <option value="{{$division->id}}" {{$division->id==$data->division_id ?'selected':''}}>{{$division->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group col-sm-12 col-md-4 {{count($areas)>0 ?'':'d-none'}}" id="area">
        <label class="col-sm-12 col-form-label" style="font-size:13px;" for="area">Sub Division</label>
        <div class="col-sm-12" id="action_number" name="action">
            <select class="form-control js-select2" id="area_select" name="area" style="font-size: 13px;width:100%;" placeholder="Sub Division">
                <option selected value=''>Choose Sub Division</option>
                @foreach($areas as $area)
                <option value="{{$area->id}}" {{$area->id==$data->area_id ?'selected':''}}>{{$area->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group col-sm-12 col-md-4">
        <label class="col-sm-12 col-form-label" for="responsibility" style="font-size: 13px;">Responsibility <span class="text-danger">*</span></label>
        <div class="col-sm-12">
            <select class="js-select2 form-select w-100" id="responsibility" name="responsibility" style="font-size: 13px;width:100%;" required="">
                <option value="">Select Responsible Person</option>
                @if(count($user)> 0 )
                @foreach($user as $use)
                <option value="{{$use->email}}" <?= $data->responsibility == $use->email ? 'selected' : '' ?>> {{ $use->name }}</option><br>
                @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="form-group col-sm-12 col-md-4">
        <label class="col-sm-12 col-form-label" for="date" style="font-size: 13px;">Target date <span class="text-danger">{{in_array(Auth::user()->email, $issue_reviewers) ? "*" : ''}}<span></label>
        <div class="col-sm-12">
            <input type="Date" class="form-control" id="date" style="font-size: 13px;" name="date" {{in_array(Auth::user()->email, $issue_reviewers) ? "required" : ''}} value="{{ $data->tardate}}">
        </div>
    </div>
</div>

<!-- Below include file contains logic for Repetitive Complaint Identification -->
@include('user.complaint.common.repetitiveComplaintIdentification')

<script>
    $('.warranty_coverage_btn').click(function(){
        var warranty = $(this).val();
        $('#warranty_coverage').val(warranty);
        $('#warranty_coverage-error').hide();
        if(warranty == "Under Warranty"){
            $('#under_warranty').addClass('btn-alt-primary').css('background-color','#5179d6').css('color', 'white').css('border','none');
            $('#out_of_warranty').removeClass('btn-alt-primary').css('background-color','').css('color', '').css('border','');
        }else if(warranty == "Out of Warranty"){
            $('#out_of_warranty').addClass('btn-alt-primary').css('background-color','#5179d6').css('color', 'white').css('border','none');
            $('#under_warranty').removeClass('btn-alt-primary').css('background-color','').css('color', '').css('border','');
        }
    });

    $('.selected_wc_class').first().trigger('click');

    // $('#complaintForm').on('submit', function (e) {
    //     let value = $('#warranty_coverage').val();
    //     if (!value) {
    //         e.preventDefault(); // stop form submission
    //         $('#warranty_coverage-error').show();
    //         // scroll to field
    //         $('html, body').animate({
    //             scrollTop: $('#warranty_coverage').closest('.form-group').offset().top - 100
    //         }, 500);
    //     }
    // });

</script>
