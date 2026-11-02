
@if($pageDetail->sub_module=='external')
    <div class="row sortable-field">
        <div class="form-group col-sm-6" data-index="{{array_shift($field_index)}}">
            <i class="drag-handle fa fa-grip-vertical"></i>
            <label class="col-sm-9 col-form-label pl-1" style="font-size: 12px;">Instrument number <span style="color:red">*</span></label>
            <div class="col-sm-12">
                <input type="text" class="form-control" style="font-size: 12px;" placeholder="Enter Instrument no" disabled>
            </div>
        </div>
        <div class="form-group col-sm-6" data-index="{{array_shift($field_index)}}">
            <i class="drag-handle fa fa-grip-vertical"></i>
            <label class="col-sm-9 col-form-label pl-1" style="font-size: 12px;">Instrument manufacturer <span style="color:red">*</span></label>
            <div class="col-sm-12">
                <input class="form-control" type="text" style="font-size: 12px;" placeholder="Enter instrument manufacturer" value="" disabled>
            </div>
        </div>
        <div class="form-group col-sm-12" data-index="{{array_shift($field_index)}}">
            <i class="drag-handle fa fa-grip-vertical"></i>
            <label class="col-sm-9 col-form-label pl-1" style="font-size: 12px;">Instrument description <span style="color:red">*</span></label>
            <div class="col-sm-12">
                <textarea type="text" class="form-control" style="font-size: 12px;" placeholder="Enter instrument description" disabled></textarea>
            </div>
        </div>
        <div class="form-group col-sm-6" data-index="{{array_shift($field_index)}}">
            <i class="drag-handle fa fa-grip-vertical"></i>
            <label class="col-sm-9 col-form-label pl-1" style="font-size: 12px;">Department <span style="color:red">*</span></label>
            <div class="col-sm-12">
                <select class="form-control" style="font-size: 12px;width:100%;" disabled>
                    <option value="" style="font-size: 12px;">Choose a Department</option>
                </select>
            </div>
        </div>
        <div class="form-group col-sm-6" data-index="{{array_shift($field_index)}}">
            <i class="drag-handle fa fa-grip-vertical"></i>
            <label class="col-sm-9 col-form-label pl-1" style="font-size: 12px;">Instrument holder/location <span style="color:red">*</span></label>
            <div class="col-sm-12">
                <input type="text" class="form-control" style="font-size: 12px;" placeholder="Enter instrument holder" value="" disabled>
            </div>
        </div>
        <div class="form-group col-sm-6" data-index="{{array_shift($field_index)}}">
            <i class="drag-handle fa fa-grip-vertical"></i>
            <label class="col-sm-9 col-form-label pl-1" style="font-size: 12px;">Instrument capacity/range <span style="color:red">*</span></label>
            <div class="col-sm-12">
                <input class="form-control" type="text" placeholder="Enter Instrument capacity" style="font-size: 12px;" disabled>
            </div>
        </div>
        <div class="form-group col-sm-6" data-index="{{array_shift($field_index)}}">
            <i class="drag-handle fa fa-grip-vertical"></i>
            <label class="col-sm-9 col-form-label pl-1" style="font-size: 12px;">Calibration agency</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" style="font-size: 12px;" placeholder="Enter agency" disabled>
            </div>
        </div>
        <div class="form-group col-sm-6" data-index="{{array_shift($field_index)}}">
            <i class="drag-handle fa fa-grip-vertical"></i>
            <label class="col-sm-9 col-form-label pl-1" style="font-size: 12px;">Frequency <span style="color:red">*</span></label>
            <div class="col-sm-12">
                <select class="form-control" style="width:100%;font-size: 12px;" disabled>
                    <option style="font-size: 12px;">Choose frequency</option>
                </select>
            </div>
        </div>
        <div class="form-group col-sm-6" data-index="{{array_shift($field_index)}}">
            <i class="drag-handle fa fa-grip-vertical"></i>
            <label class="col-sm-9 col-form-label pl-1" for="externalfiles" style="font-size: 12px;">Attach files(avoid '#' and '&' in file names)</label>
            <div class="col-sm-12">
                <input type="file" style="font-size: 12px;" class="form-control" disabled>
            </div>
        </div>
        <div class="form-group col-sm-12" data-index="{{array_shift($field_index)}}">
            <i class="drag-handle fa fa-grip-vertical"></i>
            <label class="col-sm-9 col-form-label pl-1" for="procedure_ext" style="font-size: 12px;">Calibration procedure</label>
            <div class="col-sm-12">
                <textarea type="text" class="form-control" style="font-size: 12px;" placeholder="Enter procedure" disabled></textarea>
            </div>
        </div>
        <div class="form-group col-sm-6" data-index="{{array_shift($field_index)}}">
            <i class="drag-handle fa fa-grip-vertical"></i>
            <label class="col-sm-9 col-form-label pl-1" style="font-size: 12px;">Standard Edition</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" style="font-size: 12px;" placeholder="Enter Standard Edition" disabled>
            </div>
        </div>

        <div class="form-group col-sm-6" data-index="{{array_shift($field_index)}}">
            <i class="drag-handle fa fa-grip-vertical"></i>
            <label class="col-sm-9 col-form-label pl-1" for="acceptance_criteria_ext" style="font-size: 12px;">Acceptance Criteria</label>
            <div class="col-sm-12">
                <input class="form-control" type="text" style="font-size: 12px;" placeholder="Enter Acceptance Criteria" disabled>
            </div>
        </div>
        <div class="form-group col-sm-6" data-index="{{array_shift($field_index)}}">
            <i class="drag-handle fa fa-grip-vertical"></i>
            <label class="col-sm-9 col-form-label pl-1" style="font-size: 12px;">Least Count</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" style="font-size: 12px;" placeholder="Enter Least count" disabled>
            </div>
        </div>
        <div class="form-group col-sm-6" data-index="{{array_shift($field_index)}}">
            <i class="drag-handle fa fa-grip-vertical"></i>
            <label class="col-sm-9 col-form-label pl-1" style="font-size: 12px;">Date of First Use</label>
            <div class="col-sm-12">
                <input type="date" class="form-control" style="font-size: 12px;" placeholder="dd/mm/yyyy" disabled>
            </div>
        </div>
        <div class="form-group col-sm-6" data-index="{{array_shift($field_index)}}">
            <i class="drag-handle fa fa-grip-vertical"></i>
            <label class="col-sm-9 col-form-label pl-1" style="font-size: 12px;" for="instrument_calibrated_external">
                Is this instrument Calibrated <span style="color:red">*</span>
                <input type="radio" class="ml-2 mr-2" disabled><label style="font-size: 12px;">Yes</label>
                <input type="radio" class="ml-2 mr-2" disabled><label style="font-size: 12px;">No</label>
            </label>                               
        </div>
        {!!$fields!!}
        {!!$addonfield!!}
    </div> 
@elseif($pageDetail->sub_module=='internal')
    <div class="row sortable-field">
        <div class="form-group col-sm-6" data-index="{{array_shift($field_index)}}">
            <i class="drag-handle fa fa-grip-vertical"></i>
            <label class="col-sm-9 col-form-label pl-1" style="font-size: 12px;">Instrument number <span style="color:red">*</span></label>
            <div class="col-sm-12">
                <input type="text" class="form-control" style="font-size: 12px;" placeholder="Enter Instrument no" disabled>
            </div>
        </div>
        
        <div class="form-group col-sm-6" data-index="{{array_shift($field_index)}}">
            <i class="drag-handle fa fa-grip-vertical"></i>
            <label class="col-sm-9 col-form-label pl-1" style="font-size: 12px;">Instrument manufacturer <span style="color:red">*</span></label>
            <div class="col-sm-12">
                <input class="form-control " type="text" style="font-size: 12px;" placeholder="Enter instrument manufacturer" disabled>
            </div>
        </div>
        <div class="form-group col-sm-12" data-index="{{array_shift($field_index)}}">
            <i class="drag-handle fa fa-grip-vertical"></i>
            <label class="col-sm-9 col-form-label pl-1" style="font-size: 12px;">Instrument description <span style="color:red">*</span></label>
            <div class="col-sm-12">
                <textarea type="text" class="form-control" style="font-size: 12px;" placeholder="Enter instrument description" disabled></textarea>
            </div>
        </div>
        <div class="form-group col-sm-6" data-index="{{array_shift($field_index)}}">
            <i class="drag-handle fa fa-grip-vertical"></i>
            <label class="col-sm-9 col-form-label pl-1" style="font-size: 12px;">Department <span style="color:red">*</span></label>
            <div class="col-sm-12">
                <select class="form-control" disabled style="font-size: 12px;width:100%;" >
                    <option value="" style="font-size: 12px;">Choose a Department</option>  
                </select>
            </div>
        </div>
        <div class="form-group col-sm-6" data-index="{{array_shift($field_index)}}">
            <i class="drag-handle fa fa-grip-vertical"></i>
            <label class="col-sm-9 col-form-label pl-1" style="font-size: 12px;">Instrument holder/location <span style="color:red">*</span></label>
            <div class="col-sm-12">
                <input type="text" class="form-control" style="font-size: 12px;"  placeholder="Enter instrument holder" disabled>
            </div>
        </div>
        <div class="form-group col-sm-6" data-index="{{array_shift($field_index)}}">
            <i class="drag-handle fa fa-grip-vertical"></i>
            <label class="col-sm-9 col-form-label pl-1" style="font-size: 12px;">Instrument capacity/range <span style="color:red">*</span></label>
            <div class="col-sm-12">
                <input class="form-control " type="text" placeholder="Enter Instrument capacity" style="font-size: 12px;" disabled>
            </div>
        </div>
        <div class="form-group col-sm-6" data-index="{{array_shift($field_index)}}">
            <i class="drag-handle fa fa-grip-vertical"></i>
            <label class="col-sm-9 col-form-label pl-1" style="font-size: 12px;">Frequency <span style="color:red">*</span></label>
            <div class="col-sm-12">
                <select class="form-control" style="width:100%;font-size: 12px;" disabled>
                    <option value="" style="font-size: 12px;">Choose frequency</option>
                </select>
            </div>
        </div>
        <div class="form-group col-sm-12" data-index="{{array_shift($field_index)}}">
            <i class="drag-handle fa fa-grip-vertical"></i>
            <label class="col-sm-9 col-form-label pl-1" for="riskdec" style="font-size: 12px;">Calibration procedure</label>
            <div class="col-sm-12">
                <textarea type="text" class="form-control" style="font-size: 12px;" placeholder="Enter procedure" disabled></textarea>
            </div>
        </div>
        <div class="form-group col-sm-6" data-index="{{array_shift($field_index)}}">
            <i class="drag-handle fa fa-grip-vertical"></i>
            <label class="col-sm-9 col-form-label pl-1" style="font-size: 12px;">Standard Edition</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" style="font-size: 12px;" placeholder="Enter Standard Edition" disabled>
            </div>
        </div>

        <div class="form-group col-sm-6" data-index="{{array_shift($field_index)}}">
            <i class="drag-handle fa fa-grip-vertical"></i>
            <label class="col-sm-9 col-form-label pl-1" style="font-size: 12px;">Acceptance Criteria</label>
            <div class="col-sm-12">
                <input class="form-control" type="text" style="font-size: 12px;" placeholder="Enter Acceptance Criteria" disabled>
            </div>
        </div>
        <div class="form-group col-sm-6" data-index="{{array_shift($field_index)}}">
            <i class="drag-handle fa fa-grip-vertical"></i>
            <label class="col-sm-9 col-form-label pl-1" style="font-size: 12px;">Least Count</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" style="font-size: 12px;" placeholder="Enter Least count" disabled>
            </div>
        </div>
        <div class="form-group col-sm-6" data-index="{{array_shift($field_index)}}">
            <i class="drag-handle fa fa-grip-vertical"></i>
            <label class="col-sm-9 col-form-label pl-1" style="font-size: 12px;">Date of First Use</label>
            <div class="col-sm-12">
                <input type="date" class="form-control" style="font-size: 12px;" placeholder="dd/mm/yyyy" disabled>
            </div>
        </div>
        <div class="form-group col-sm-6" data-index="{{array_shift($field_index)}}">
            <i class="drag-handle fa fa-grip-vertical"></i>
            <label class="col-sm-9 col-form-label pl-1" style="font-size: 12px;">
                Is this instrument Calibrated <span style="color:red">*</span>
                <input type="radio" class="ml-2 mr-2" disabled><label style="font-size: 12px;" readonly>Yes</label>
                <input type="radio" class="ml-2 mr-2" disabled><label style="font-size: 12px;" readonly>No</label>
            </label>                               
        </div>
        {!!$fields!!}
        {!!$addonfield!!}
    </div>
@endif                           