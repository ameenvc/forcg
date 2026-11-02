<style>
    .warranty_coverage_btn:focus {
        box-shadow: 0 0 0 0.25rem rgba(81, 121, 214, 0.25);
    }
</style>
<div class="row">
    @if ($complaintTemplateSectionFields['job_order_no']['enable_field'])
        <div class="form-group col-sm-12 col-md-6 col-lg-3">
            <label class="col-sm-12 col-form-label" for="job_order_no" style="font-size: 13px;">{{$complaintTemplateSectionFields['job_order_no']['field_display_label']}} @if($complaintTemplateSectionFields['job_order_no']['field_is_mandatory'])<span class="text-danger">*</span> @endif</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" style="font-size: 13px;" name="job_order_no" id="job_order_no" placeholder="Enter {{$complaintTemplateSectionFields['job_order_no']['field_display_label']}}" {{$complaintTemplateSectionFields['job_order_no']['field_is_mandatory'] ? 'required' : ''}} value="{{ old('job_order_no')}}">
            </div>
        </div>
    @endif
    @if ($complaintTemplateSectionFields['product_serial_no']['enable_field'])
        <div class="form-group col-sm-12 col-md-6 col-lg-3">
            <label class="col-sm-12 col-form-label" for="title" style="font-size: 13px;">{{$complaintTemplateSectionFields['product_serial_no']['field_display_label']}} @if($complaintTemplateSectionFields['product_serial_no']['field_is_mandatory'])<span class="text-danger">*</span> @endif</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" style="font-size: 13px;" id="product_serial_no" name="product_serial_no" placeholder="Enter {{$complaintTemplateSectionFields['product_serial_no']['field_display_label']}}" {{$complaintTemplateSectionFields['product_serial_no']['field_is_mandatory'] ? 'required' : ''}} value="{{ old('product_serial_no')}}">
            </div>
        </div>
    @endif
    @if ($complaintTemplateSectionFields['project_name']['enable_field'])
        <div class="form-group col-sm-12 col-md-6 col-lg-3">
            <label class="col-sm-12 col-form-label" for="title" style="font-size: 13px;">{{$complaintTemplateSectionFields['project_name']['field_display_label']}} @if($complaintTemplateSectionFields['project_name']['field_is_mandatory'])<span class="text-danger">*</span> @endif</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" style="font-size: 13px;" name="project_name" id="project_name"  {{$complaintTemplateSectionFields['project_name']['field_is_mandatory'] ? 'required' : ''}} placeholder="Enter {{$complaintTemplateSectionFields['project_name']['field_display_label']}}" value="{{ old('project_name')}}">
            </div>
        </div>
    @endif
    <div class="form-group col-sm-12 col-md-6 col-lg-3">
        <label class="col-sm-12 col-form-label" for="warranty_coverage" style="font-size: 13px;">Warranty Coverage <span class="text-danger">*</span></label>
        <div class="col-sm-12">
            <input type="hidden" name="meta[warranty_coverage]" id="warranty_coverage" value="">
            @foreach(App\Constants\Constants::WARRANTY_COVERAGES as $key => $value)
                <input type="button" class="btn btn-sm {{ $value['btn_class']}} warranty_coverage_btn" id="{{ $key }}" style="margin-right:2px;margin-bottom:10px;font-size:12px;" value="{{$value['title']}}">
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
                <textarea type="text" rows="4" class="form-control" style="font-size: 13px;" name="job_history" id="job_history" placeholder="Enter {{$complaintTemplateSectionFields['job_history']['field_display_label']}}">{{ old('job_history')}}</textarea>
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
                <option value="High" <?= old('type') == "High" ? 'selected' : '' ?>>High</option>
                <option value="Moderate" <?= old('type') == "Moderate" ? 'selected' : '' ?>>Moderate</option>
                <option value="Low" <?= old('type') == "Low" ? 'selected' : '' ?>>Low</option>
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
                <option value=" {{$p->id}}" <?= old('plant') == $p->id ? 'selected' : '' ?>> {{$p->plant_name}}</option></br>
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
                <option value=" {{$cc->id}}" <?= old('meta[complaint_category_id]') == $cc->id ? 'selected' : '' ?>> {{$cc->category_name}}</option></br>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group col-sm-12 col-md-6 col-lg-3" id="vendorDiv">
        <label class="col-sm-12 col-form-label" for="vendor_id" style="font-size: 13px;">Vendor</label>
        <div class="col-sm-12" >
            <select class="form-control js-select2" name="meta[vendor_id]" id="vendor_id" style="font-size: 13px;width:100%;">
                <option value="" selected>Select Vendor</option>
                @foreach($vendors as $vendor)
                    <option value=" {{$vendor->id}}" <?= old('meta[vendor_id]') == $vendor->id ? 'selected' : '' ?>> {{$vendor->vendor_name}}</option></br>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-sm-12 col-md-6">
        <label class="col-sm-12 col-form-label" for="title" style="font-size: 13px;">Complaint Title <span class="text-danger">*</span></label>
        <div class="col-sm-12">
            <input type="text" class="form-control" style="font-size: 13px;" id="title" name="title" placeholder="Enter Title" value="{{old('title')}}" required>
        </div>
    </div>
    <div class="form-group col-sm-12 col-md-12">
        <label class="col-sm-12 col-form-label" for="complaint" style="font-size: 13px;">Complaint Description <span class="text-danger">*</span></label>
        <div class="col-sm-12 editor-container">
            <textarea type="text" class="form-control form-control-alt" style="font-size: 13px;" id="complaint" name="complaint" placeholder="Enter description">{{old('complaint')}}</textarea>
            <div id="complaint-error" class="text-danger" style="display:none;font-size:12px;">Complaint Description field is required.</div>
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-sm-12">
        <label class="col-sm-12 col-form-label" for="name" style="font-size:13px;">Is this a previously occurred Compliant?</label>
        <div class="col-sm-12">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio"
                    id="previously_occured_complaint_yes"
                    name="meta[previously_occured_complaint]"
                    value="yes"
                    {{ old('meta.previously_occured_complaint') == 'yes' ? 'checked' : '' }}>
                <label class="form-check-label" for="previously_occured_complaint_yes" style="font-size:13px;">Yes</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio"
                    id="previously_occured_complaint_no"
                    name="meta[previously_occured_complaint]"
                    value="no"
                    {{ old('meta.previously_occured_complaint', 'no') == 'no' ? 'checked' : '' }}>
                <label class="form-check-label" for="previously_occured_complaint_no" style="font-size:13px;">No</label>
            </div>
        </div>
    </div>
    <div class="form-group d-none col-sm-4" id="no_of_occurrence_div">
        <label class="col-sm-12 col-form-label" for="no_of_occurrence" style="font-size:13px;">No of Occurrence</label>
        <div class="col-sm-12">
            <input type="number" class="form-control" style="font-size: 13px;" id="no_of_occurrence" name="meta[no_of_occurrence]" placeholder="Enter Occurence" value="{{old('meta.no_of_occurrence')}}" min="1">
        </div>
    </div>
    <div class="form-group d-none col-sm-4" id="previous_complaint_reference_div">
        <label class="col-sm-12 col-form-label" for="previous_complaint_reference" style="font-size:13px;">Previous Complaint Reference</label>
        <div class="col-sm-12">
            <select class="form-control" name="meta[previous_complaint_reference]" id="previous_complaint_reference" style="font-size:13px;width:100%;">
                <option></option>
            </select>
        </div>
    </div>
    <div class="form-group d-none col-sm-4" id="external_reference_div">
        <label class="col-sm-12 col-form-label" for="external_reference" style="font-size:13px;">External Complaint Reference</label>
        <div class="col-sm-12">
            <input type="text" class="form-control" style="font-size: 13px;" id="external_reference" name="meta[external_reference]" value="{{old('meta.external_reference')}}">
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-sm-12">
        <label class="col-sm-12 col-form-label" for="action" style="font-size: 13px;">Recommended Action <span class="text-danger">*</span></label>
        <div class="col-sm-12">
            <textarea type="text" class="form-control" id="action" style="font-size: 13px;" name="action" placeholder="Enter Here" >{{old('action')}}</textarea>
            <div id="action-error" class="text-danger" style="display:none;font-size:12px;">Recommended Action field is required.</div>
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group  col-sm-12 col-md-4">
        <label class="col-sm-12 col-form-label" for="department" style="font-size: 13px;">Responsible Department <span class="text-danger">*</span></label>
        <div class="col-sm-12">
            <select class="form-control js-select2" id="department" name="department" style="font-size: 13px;width:100%;" required="true" onchange="chooseDivision($(this).val())">
                <option selected disabled value="">Choose Department</option>
                @if(count($departments)>0)
                @foreach($departments as $dep)
                <option value="{{$dep->id}}" <?= old('department') == $dep->id ? 'selected' : '' ?>> {{$dep->departmentname}}</option>
                @endforeach
                @endif
            </select>
        </div>
    </div>
    @php
        $divisions = App\Division::where('department_id',old('department'))->get();
        $areas = App\Area::where('division_id',old('division'))->get();
    @endphp
    <div class="form-group {{count($divisions)>0?'': 'd-none'}}  col-sm-12 col-md-4" id="division">
        <label class="col-sm-12 col-form-label" style="font-size:13px;" for="division">Division</label>
        <div class="col-sm-12">
            <select class="form-control js-select2" id="division_select" name="division" style="font-size: 13px;width:100%;" placeholder="Division" onchange="chooseArea($(this).val())">
                <option value="" selected>Choose Division</option></br>
                @if(count($divisions)>0)
                @foreach($divisions as $division)
                <option value="{{$division->id}}" {{$division->id==old('division')?'selected':''}}>{{$division->name}}</option>
                @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="form-group {{count($areas)>0?'': 'd-none'}}  col-sm-12 col-md-4" id="area">
        <label class="col-sm-12 col-form-label" style="font-size:13px;" for="area">Sub Division</label>
        <div class="col-sm-12" id="action_number" name="action">
            <select class="form-control js-select2" id="area_select" name="area" style="font-size: 13px;width:100%;" placeholder="Sub Division">
                <option value="" selected>Choose Sub Division</option></br>
                @if(count($areas)>0)
                @foreach($areas as $area)
                <option value="{{$area->id}}" {{$area->id==old('area')?'selected':''}}>{{$area->name}}</option>
                @endforeach
                @endif
            </select>
        </div>
    </div>

    <div class="form-group  col-sm-12 col-md-4">
        <label class="col-sm-12 col-form-label" for="responsibility" style="font-size: 13px;">Responsibility <span class="text-danger">*</span></label>
        <div class="col-sm-12">
            <select class="js-select2 form-select w-100" id="responsibility" name="responsibility" style="font-size: 13px;width:100%;" required="">
                <option value="">Select Responsible Person</option>
                @if(count($user)> 0 )
                @foreach($user as $use)
                <option value="{{$use->email}}" <?= old('responsibility') == $use->email ? 'selected' : '' ?>> {{ $use->name }}</option><br>
                @endforeach
                @endif
            </select>
        </div>
    </div>

    <div class="form-group  col-sm-12 col-md-4">
        <label class="col-sm-12 col-form-label" for="date" style="font-size: 13px;">Target date <span class="text-danger">{{in_array(Auth::user()->email, $issue_reviewers) ? "*" : ''}}<span></label>
        <div class="col-sm-12">
            <input type="Date" class="form-control" id="date" style="font-size: 13px;" name="date" {{in_array(Auth::user()->email, $issue_reviewers) ? "required" : ''}} value="{{ old('date')}}">
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

    // document.getElementById('complaintForm').addEventListener('submit', function (e) {

    // });

</script>

