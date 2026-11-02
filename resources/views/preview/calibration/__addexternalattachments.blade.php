@if($pageDetail->group_name=='Register Page')
    <div class="row sortable-field">
        <div class="form-group col-sm-6" data-index="{{array_shift($field_index)}}">
            <i class="drag-handle fa fa-grip-vertical"></i>
            <label class="col-sm-9 col-form-label pl-1" style="font-size: 11px;">Attach files(avoid '#' and '&' in file names) <span style="color:red">*</span></label>
            <div class="col-sm-12">
                <input type="file" style="font-size: 11px;" class="form-control" disabled>
            </div>
        </div> 
        {!!$fields!!} 
        {!!$addonfield!!}
    </div>                              
@elseif($pageDetail->group_name=='Under Calibration')
    <div class="row sortable-field">
        <div class="form-group col-sm-6" data-index="{{array_shift($field_index)}}">
            <i class="drag-handle fa fa-grip-vertical"></i>
            <label class="col-sm-9 col-form-label pl-1" style="font-size: 11px;">Attach files(avoid '#' and '&' in file names) <span style="color:red">*</span></label>
            <div class="col-sm-12">
                <input type="file" style="font-size: 11px;" class="form-control" disabled>
            </div>
        </div> 
        {!!$fields!!}
        {!!$addonfield!!}
    </div>                                 
@endif