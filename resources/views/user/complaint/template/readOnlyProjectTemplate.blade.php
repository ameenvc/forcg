@if ($complaintTemplateSectionFields['customer']['enable_field'] || $complaintTemplateSectionFields['customerphone']['enable_field'] || $complaintTemplateSectionFields['customeremail']['enable_field'] || $complaintTemplateSectionFields['customeraddress']['enable_field'] || $complaintTemplateSectionFields['product']['enable_field'] )
    <div class="row p-0">
        <fieldset class="col-sm-12 row p-0 ml-4" style="border:2px solid #dfe4e8;max-width:95%;">
            <legend style="font-size:15px;width:90px;text-weight:bold;" class="bg-primary text-white p-1">Customer</legend>
            @if ($complaintTemplateSectionFields['customer']['enable_field'])
                <div class="form-group col-sm-12 col-md-4 px-0" id="customerDiv">
                    <label class="col-sm-12 col-form-label" for="Customer Name" style="font-size: 13px;">{{$complaintTemplateSectionFields['customer']['field_display_label']}} @if($complaintTemplateSectionFields['customer']['field_is_mandatory'])<span class="text-danger">*</span> @endif</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" style="font-size: 13px;" value="{{ $customer}}" readonly>
                    </div>
                </div>
            @endif

            @if ($complaintTemplateSectionFields['customerphone']['enable_field'])
                <div class="form-group col-sm-12 col-md-4 px-0">
                    <label class="col-sm-12 col-form-label" for="Customer Phone" style="font-size: 13px;">{{$complaintTemplateSectionFields['customerphone']['field_display_label']}} @if($complaintTemplateSectionFields['customerphone']['field_is_mandatory'])<span class="text-danger">*</span> @endif</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" style="font-size: 13px;" id="customerphone" name="customerphone" value="{{$data->customer_phone}}" placeholder="Enter Phone Number" <?= $data->customer_phone ? 'readonly' : ''?>>
                    </div>
                </div>
            @endif
            @if ($complaintTemplateSectionFields['customeremail']['enable_field'])
                <div class="form-group col-sm-12 col-md-4 px-0">
                    <label class="col-sm-12 col-form-label" for="Customer Email" style="font-size: 13px;">{{$complaintTemplateSectionFields['customeremail']['field_display_label']}}@if($complaintTemplateSectionFields['customeremail']['field_is_mandatory'])<span class="text-danger">*</span> @endif</label>
                    <div class="col-sm-12">
                        <input type="email" class="form-control" style="font-size: 13px;" id="customeremail" value="{{$data->customer_email}}" readonly>
                    </div>
                </div>
            @endif
            @if ($complaintTemplateSectionFields['customeraddress']['enable_field'])
                <div class="form-group col-sm-12 col-md-4 px-0">
                    <label class="col-sm-12 col-form-label" for="Customer Address" style="font-size: 13px;">{{$complaintTemplateSectionFields['customeraddress']['field_display_label']}}@if($complaintTemplateSectionFields['customeraddress']['field_is_mandatory'])<span class="text-danger">*</span> @endif</label>
                    <div class="col-sm-12">
                        <textarea class="form-control" style="font-size: 13px;" id="customeraddress" name="customeraddress" placeholder="Enter Address" <?= $data->customer_address ? 'readonly' : ''?>>{{$data->customer_address}}</textarea>
                    </div>
                </div>
            @endif
            @if ($complaintTemplateSectionFields['product']['enable_field'])
                <div class="form-group col-sm-12 col-md-4 px-0" id="productServiceDiv">
                    <label class="col-sm-12 col-form-label" for="product" style="font-size: 13px;">{{$complaintTemplateSectionFields['product']['field_display_label']}} @if($complaintTemplateSectionFields['product']['field_is_mandatory'])<span class="text-danger">*</span> @endif</label>
                    <div class="col-sm-12">
                        <textarea type="text" class="form-control" style="font-size: 13px;" readonly>{{implode(',',$product)}}</textarea>
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
                <input type="text" class="form-control" style="font-size: 13px;" placeholder="Enter Job Order No." value="{{ $data->job_order_no }}" {{$complaintTemplateSectionFields['job_order_no']['field_is_mandatory'] ? 'required' : ''}} readonly>
            </div>
        </div>
    @endif
    @if ($complaintTemplateSectionFields['product_serial_no']['enable_field'])
        <div class="form-group col-sm-12 col-md-6 col-lg-3">
            <label class="col-sm-12 col-form-label" for="title" style="font-size: 13px;">{{$complaintTemplateSectionFields['product_serial_no']['field_display_label']}} @if($complaintTemplateSectionFields['product_serial_no']['field_is_mandatory'])<span class="text-danger">*</span> @endif</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" style="font-size: 13px;" placeholder="Enter Product Serial No." value="{{ $data->product_serial_no }}" {{$complaintTemplateSectionFields['product_serial_no']['field_is_mandatory'] ? 'required' : ''}} readonly>
            </div>
        </div>
    @endif
    @if ($complaintTemplateSectionFields['project_name']['enable_field'])
        <div class="form-group col-sm-12 col-md-6 col-lg-3">
            <label class="col-sm-12 col-form-label" for="title" style="font-size: 13px;">{{$complaintTemplateSectionFields['project_name']['field_display_label']}} @if($complaintTemplateSectionFields['project_name']['field_is_mandatory'])<span class="text-danger">*</span> @endif</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" style="font-size: 13px;" placeholder="Enter Project Name." value="{{ $data->project_name }}" {{$complaintTemplateSectionFields['project_name']['field_is_mandatory'] ? 'required' : ''}} readonly>
            </div>
        </div>
    @endif
    @if (isset($data->warranty_coverage))
        <div class="form-group col-sm-12 col-md-6 col-lg-3">
            <label class="col-sm-12 col-form-label" for="title" style="font-size: 13px;">Warranty Coverage <span class="text-danger">*</span></label>
            <div class="col-sm-12">
                <input type="text" class="form-control" style="font-size: 13px;" value="{{ $data->warranty_coverage }}" readonly>
            </div>
        </div>
    @endif
</div>
@if ($complaintTemplateSectionFields['job_history']['enable_field'])
    <div class="row">
        <div class="form-group col-sm-12">
            <label class="col-sm-12 col-form-label" for="title" style="font-size: 13px;">{{$complaintTemplateSectionFields['job_history']['field_display_label']}} @if($complaintTemplateSectionFields['job_history']['field_is_mandatory'])<span class="text-danger">*</span> @endif</label>
            <div class="col-sm-12">
                <textarea type="text" rows="4" class="form-control" style="font-size: 13px;" name="job_history" id="job_history" {{$complaintTemplateSectionFields['job_history']['field_is_mandatory'] ? 'required' : ''}} placeholder="Enter Job History." readonly>{{ $data->job_history }}</textarea>
            </div>
        </div>
    </div>
@endif

<div class="row">
    <div class="form-group col-sm-12 col-md-6 col-lg-3">
        <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="type">Type</label>

        <div class="col-sm-12">
            @if($data->type)
                <input type="text" class="form-control" name="type" style="font-size: 13px;" value="{{$data->type}}" readonly>
            @else
            <select class="form-control js-select2" style="font-size: 13px;width:100%;" id="type" name="type" placeholder="Choose Type">
                <option value="" selected>Choose Type</option>
                <option value="High" <?= $data->type == "High" ? 'selected' : '' ?>>High</option>
                <option value="Moderate" <?= $data->type == "Moderate" ? 'selected' : '' ?>>Moderate</option>
                <option value="Low" <?= $data->type == "Low" ? 'selected' : '' ?>>Low</option>
            </select>
            @endif
        </div>
    </div>
    @if ($data->plant)
        <div class="form-group col-sm-12 col-md-6 col-lg-3" id="plantDiv">
            <label class="col-sm-12 col-form-label" for="Plant" style="font-size: 13px;">Plant <span class="text-danger">*</span></label>
            <div class="col-sm-12" >
                @if($data->plant)
                    <input type="text" class="form-control" name="oldplant" style="font-size: 13px;" value="{{DB::table('complaint_plants')->where('id',$data->plant)->value('plant_name')}}" readonly>
                @else
                <select class="form-control js-select2" name="plant" id="plant" style="font-size: 13px;width:100%;" required>
                    <option value="" selected disabled>Choose Plant</option>
                    @if(count($plants)>0)
                    @foreach($plants as $p)
                    <option value="{{$p->id}}" <?= $data->plant == $p->id ? 'selected' : '' ?>> {{$p->plant_name}}</option></br>
                    @endforeach
                    @endif

                </select>
                @endif
            </div>
        </div>
    @endif
    @if (isset($data->complaint_category_id))
        <div class="form-group col-sm-12 col-md-6 col-lg-3" id="complaintCategoryDiv">
            <label class="col-sm-12 col-form-label" for="complaint_category_id" style="font-size: 13px;">Complaint Category <span class="text-danger">*</span></label>
            <div class="col-sm-12" >
                <input type="text" class="form-control" name="oldplant" style="font-size: 13px;" value="{{DB::table('complaint_categories')->where('id',$data->complaint_category_id)->value('category_name')}}" readonly>
            </div>
        </div>
    @endif
    @if (isset($data->vendor_id))
        <div class="form-group col-sm-12 col-md-6 col-lg-3" id="vendorDiv">
            <label class="col-sm-12 col-form-label" for="vendor" style="font-size: 13px;">Vendor</label>
            <div class="col-sm-12" >
                <input type="text" class="form-control" name="oldplant" style="font-size: 13px;" value="{{DB::table('vendor')->where('id',$data->vendor_id)->value('vendor_name')}}" readonly>
            </div>
        </div>
    @endif
</div>
<div class="row">
    <div class="form-group col-sm-12 col-md-6">
        <label class="col-sm-12 col-form-label" for="title" style="font-size: 13px;">Complaint Title <span class="text-danger">*</span></label>
        <div class="col-sm-12">
            <input type="text" class="form-control" style="font-size: 13px;" placeholder="Enter Title" value="{{$data->title}}" required readonly>
        </div>
    </div>
    <div class="form-group col-sm-12 col-md-12">
        <label class="col-sm-12 col-form-label" for="complaint" style="font-size: 13px;">Complaint Description <span class="text-danger">*</span></label>
        <div class="col-sm-12">
            <textarea type="text" id="complaint" class="form-control" style="font-size: 13px;" placeholder="Enter description" required="" readonly>{{$data->complaint}}</textarea>
        </div>
    </div>
</div>
@if (isset($data->previously_occured_complaint))
    <div class="row">
        <div class="form-group col-sm-12">
            <label class="col-sm-12 col-form-label" for="name" style="font-size:13px;">Is this a previously occurred Compliant?</label>
            <div class="col-sm-12">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" disabled type="radio" id="previously_occured_complaint_yes" name="meta[previously_occured_complaint]" value="yes" {{ isset($data->previously_occured_complaint) && $data->previously_occured_complaint == 'yes' ? 'checked' : '' }}>
                    <label class="form-check-label" for="previously_occured_complaint_yes" style="font-size:13px;">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" disabled type="radio" id="previously_occured_complaint_no" name="meta[previously_occured_complaint]" value="no" {{ isset($data->previously_occured_complaint) && $data->previously_occured_complaint == 'no' ? 'checked' : ''}}>
                    <label class="form-check-label" for="previously_occured_complaint_no" style="font-size:13px;">No</label>
                </div>
            </div>
        </div>
        <div class="form-group {{ isset($data->previously_occured_complaint) && $data->previously_occured_complaint == 'no' ? 'd-none' : '' }} col-sm-4" id="no_of_occurrence_div">
            <label class="col-sm-12 col-form-label" for="no_of_occurrence" style="font-size:13px;">No of Occurrence</label>
            <div class="col-sm-12">
                <input type="readonly" disabled class="form-control" style="font-size: 13px;" id="no_of_occurrence" name="meta[no_of_occurrence]" placeholder="Enter Occurence" value="{{ $data->no_of_occurrence ?? ''}}">
            </div>
        </div>
        <div class="form-group {{ isset($data->previously_occured_complaint) && $data->previously_occured_complaint == 'no' ? 'd-none' : '' }} col-sm-4" id="previous_complaint_reference_div">
            <label class="col-sm-12 col-form-label" for="previous_complaint_reference" style="font-size:13px;">Previous Complaint Reference</label>
            <div class="col-sm-12">
                <input type="readonly" disabled class="form-control" style="font-size: 13px;" id="previous_complaint_reference" name="meta[previous_complaint_reference]" placeholder="Enter Occurence"
                    value="{{ isset($data->previous_complaint_reference) ?
                        $data->previous_complaint_reference != '0' ?
                            ($data->previous_complaint_ref ? $data->previous_complaint_ref['trackno'].' - '.$data->previous_complaint_ref['title'] : 'This complaint was deleted' )
                            : 'other'
                        : ''}}">
            </div>
        </div>
        <div class="form-group {{ isset($data->previous_complaint_reference) && $data->previous_complaint_reference == '0' ? '' : 'd-none' }} col-sm-4" id="external_reference_div">
            <label class="col-sm-12 col-form-label" for="external_reference" style="font-size:13px;">External Complaint Reference</label>
            <div class="col-sm-12">
                <input type="readonly" disabled class="form-control" style="font-size: 13px;" id="external_reference" name="meta[external_reference]" value="{{ $data->external_reference ?? ''}}">
            </div>
        </div>
    </div>
@endif

<div class="row">
    <div class="form-group col-sm-12">
        <label class="col-sm-12 col-form-label" for="action" style="font-size: 13px;">Recommended Action <span class="text-danger">*</span></label>
        <div class="col-sm-12">
            <textarea type="text" id="action" class="form-control" style="font-size: 13px;" placeholder="Enter Here" required readonly>{{$data->action}}</textarea>
        </div>
    </div>
</div>

