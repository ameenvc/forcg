<div class="row sortable-field">
    <div class="form-group col-sm-6" data-index="{{array_shift($field_index)}}">
        <i class="drag-handle fa fa-grip-vertical"></i>
        <label class="col-sm-9 col-form-label pl-1" style="font-size: 12px;">Instrument number</label>
        <div class="col-sm-12">
            <input type="text" class="form-control" style="font-size: 12px;" placeholder="Instrument number" disabled>
        </div>
    </div>
    <div class="form-group col-sm-6" data-index="{{array_shift($field_index)}}">
        <i class="drag-handle fa fa-grip-vertical"></i>
        <label class="col-sm-9 col-form-label pl-1" style="font-size: 12px;">Date of calibration <span class="text-danger">*</span></label>
        <div class="col-sm-12">
            <input type="date" class="form-control" style="font-size: 12px;" disabled>
        </div>
    </div>
    <div class="form-group col-sm-6" data-index="{{array_shift($field_index)}}">
        <i class="drag-handle fa fa-grip-vertical"></i>
        <label class="col-sm-9 col-form-label pl-1" style="font-size: 12px;">Certificate number</label>
        <div class="col-sm-12">
            <input type="text" class="form-control" style="font-size: 12px;" placeholder="Certificate number" disabled>                                            
        </div>
    </div>
    <div class="form-group col-sm-6" data-index="{{array_shift($field_index)}}">
        <i class="drag-handle fa fa-grip-vertical"></i>
        <label class="col-sm-9 col-form-label pl-1" style="font-size: 12px;">Next due date</label>
        <div class="col-sm-12">
            <input type="date" style="font-size: 12px;"class="form-control" disabled>    
        </div>
    </div>
    {!!$fields!!}
    {!!$addonfield!!}
</div>