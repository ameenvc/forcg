<div class="row sortable-field">
    <div class="table-responsive form-group col-sm-12" data-index="{{array_shift($field_index)}}">
        <i class="drag-handle fa fa-grip-vertical"></i>
        <table class="table table-bordered table-striped table-vcenter">                               
            <thead style="background-color:#FFFFFF">
                <tr>
                    <th class="text-center" style="font-size: 12px;width: 20px;text-transform:capitalize">Instrument No</th>
                    <th class="text-center" style="font-size: 12px;width: 30px;text-transform:capitalize">Description</th>
                    <th class="text-center" style="font-size: 12px;width: 30px;text-transform:capitalize">Calibration Procedure </th>                                                   
                    <th class="text-center" style="font-size: 12px;width: 100px;text-transform:capitalize">Due Date</th>  
                </tr>
            </thead>
                <tbody>
                <tr> 
                    <input  type="hidden" id="id" name="id" value=""> 
                    <td class="text-center "style="font-size:13px">
                        <input  type="text" style="font-size: 12px;"   class="form-control" id="Instrument_no" 
                        name="Instrument_no" readonly  value="">   
                    </td>   
                    <td class="text-center "style="font-size:13px;">
                        <textarea style="font-size: 12px;" rows="2" cols="2"  class="form-control" id="Instrument_desc" 
                        name="Instrument_desc" readonly></textarea> 
                    </td>  
                    <td class="text-center "style="font-size:13px;">
                        <textarea style="font-size: 12px;"   class="form-control" rows="2" cols="2" id="Calib_procedure" 
                        name="Calib_procedure" readonly></textarea>   
                    </td>  
                    <td class="text-center "style="font-size:13px;">
                        <input  type="text" style="font-size: 12px;"   class="form-control" id="duedate" 
                        name="duedate"  readonly value=""> 
                    </td>                                                             
                </tr>                                                                         
            </tbody>  
        </table>
    </div>
    <div class="form-group col-sm-6" data-index="{{array_shift($field_index)}}">
        <i class="drag-handle fa fa-grip-vertical"></i>
        <label class="col-sm-9 col-form-label pl-1" style="font-size: 12px;">Acceptable error <span style="color:red">*</span></label>
        <div class="col-sm-12">
            <input type="text" class="form-control" style="font-size: 12px;" disabled placeholder="Enter acceptable error">
        </div>
    </div>
    <div class="form-group col-sm-6" data-index="{{array_shift($field_index)}}">
        <i class="drag-handle fa fa-grip-vertical"></i>
        <label class="col-sm-9 col-form-label pl-1" style="font-size: 12px;">Master instrument certificate number <span style="color:red">*</span></label>
        <div class="col-sm-12">
            <input class="form-control" type="text" style="font-size: 12px;" disabled placeholder="Enter master instrument certificate number">
        </div>
    </div>
    <div class="form-group col-sm-12" data-index="{{array_shift($field_index)}}">
        <i class="drag-handle fa fa-grip-vertical"></i>
        <label class="col-sm-9 col-form-label pl-1" style="font-size: 12px;">Master instrument <span style="color:red">*</span></label>
        <div class="col-sm-12">
            <textarea type="text" class="form-control" style="font-size: 12px;" placeholder="Enter master instrument" disabled></textarea>
        </div>
    </div>
    <div class="form-group col-sm-12" data-index="{{array_shift($field_index)}}">
        <i class="drag-handle fa fa-grip-vertical"></i>
        <label class="col-sm-9 col-form-label pl-1" style="font-size: 12px;">Master reading, Instrument reading & Error <span style="color:red">*</span></label>
        <div class="col-sm-12">
            <table style="border:none;width:100%; table-layout:fixed;">
                <colgroup>
                    <col style="width:33.33%;">
                    <col style="width:33.33%;">
                    <col style="width:33.34%;">
                </colgroup>
                <tr>  
                    <td>
                        <select style="font-size: 12px;margin-bottom:15px;font-weight:bold;" disabled>
                            <option><span class="form-label">Select Decimal Points</span></option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>
                        <input type="text"style="font-size: 12px;width:150px;" class="form-control"placeholder="Enter master reading" disabled>
                    </td>
                    <td>
                        <input type="text"style="font-size: 12px;width:150px;" class="form-control" placeholder="Enter instrument reading" disabled>
                    </td>
                    <td>
                        <input type="text"style="font-size: 12px;width:150px;" class="form-control" placeholder="Enter error" disabled>
                    </td>                                               
                </tr>  
            </table>  
        </div>
    </div>
    <div class="form-group col-sm-6" data-index="{{array_shift($field_index)}}">
        <i class="drag-handle fa fa-grip-vertical"></i>
        <label class="col-sm-9 col-form-label pl-1" style="font-size: 12px;">Report Number</label>
        <div class="col-sm-12">                                       
            <input type="text" class="form-control" style="font-size: 12px;" placeholder="Enter report number" disabled>
        </div>
    </div>
    <div class="form-group col-sm-6" data-index="{{array_shift($field_index)}}">
        <i class="drag-handle fa fa-grip-vertical"></i>
        <label class="col-sm-9 col-form-label pl-1" style="font-size: 12px;">Calibrated by</label>
        <div class="col-sm-12"> 
            <input type="text" class="form-control" style="font-size: 12px;" disabled>
        </div>
    </div>
    <div class="form-group col-sm-6" data-index="{{array_shift($field_index)}}">
        <i class="drag-handle fa fa-grip-vertical"></i>
        <label class="col-sm-9 col-form-label pl-1" style="font-size: 12px;" >Verified by <span style="color:red">*</span></label>
        <div class="col-sm-12">   
            <select class="js-select2 form-control" style="font-size: 12px;width:100%;" disabled>
                <option>Select Verifier</option>
            </select>
        </div>
    </div>
    <div class="form-group col-sm-6" data-index="{{array_shift($field_index)}}">
        <i class="drag-handle fa fa-grip-vertical"></i>
        <label class="col-sm-9 col-form-label pl-1" style="font-size: 12px;">Date of calibration <span style="color:red">*</span></label>
        <div class="col-sm-12"> 
            <input type="date" class="form-control" style="font-size: 12px;" disabled>
        </div>
    </div>  
    <div class="form-group col-sm-12" data-index="{{array_shift($field_index)}}">
        <i class="drag-handle fa fa-grip-vertical"></i>
        <label class="col-sm-9 col-form-label pl-1" style="font-size: 12px;">Adjustment made <span style="color:red">*</span></label>
        <div class="col-sm-12"> 
            <textarea type="text" class="form-control" style="font-size: 12px;" placeholder="Enter adjustment" disabled></textarea>
        </div>
    </div>
    <div class="form-group col-sm-12" data-index="{{array_shift($field_index)}}">
        <i class="drag-handle fa fa-grip-vertical"></i>
        <label class="col-sm-9 col-form-label pl-1" style="font-size: 12px;">Remarks</label>
        <div class="col-sm-12"> 
            <textarea type="text" class="form-control" style="font-size: 12px;" placeholder="Enter remarks" disabled></textarea>
        </div>
    </div>
    {!!$fields!!}
    {!!$addonfield!!}
</div>