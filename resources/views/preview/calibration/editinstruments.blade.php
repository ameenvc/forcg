<table class="table table-bordered table-striped">
    <thead></thead>
    <tbody class="sortable-field">
        <tr data-index="{{array_shift($field_index)}}">
            <td>
                <i class="drag-handle fa fa-grip-vertical"></i>
            </td>
            <td>
                <label class="col-form-label" style="font-size:12px;" for="type">Instrument Number</label>
            </td>
            <td>
                <input type="text" class="form-control" style="font-size:12px;" value="xyz" disabled>
            </td>
        </tr>

        <tr data-index="{{array_shift($field_index)}}">
            <td>
                <i class="drag-handle fa fa-grip-vertical"></i>
            </td>
            <td class="col-sm-4">
                <label class="col-form-label" style="font-size:12px;" for="type">Manufacturer name</label>
            </td>
            <td class="col-sm-8">
                <span style="font-size:13px;">
                    <input type="text" class="form-control" style="font-size:12px;" value="xyz" disabled>
                </span>
            </td>
        </tr>
        <tr data-index="{{array_shift($field_index)}}">
            <td>
                <i class="drag-handle fa fa-grip-vertical"></i>
            </td>
            <td class="col-sm-4">
                <label class="col-form-label" style="font-size:12px;" for="type">Type</label>
            </td>
            <td class="col-sm-8">
                <select class="form-control" name="type" id="type" style="font-size:12px;" disabled>
                    @if($pageDetail->sub_module=='internal')
                    <option>Internal</option>
                    @elseif($pageDetail->sub_module=='external')
                    <option>External</option>
                    @endif
                </select>
            </td>
        </tr>
        <tr data-index="{{array_shift($field_index)}}">
            <td>
                <i class="drag-handle fa fa-grip-vertical"></i>
            </td>
            <td class="col-sm-4">
                <label class="col-form-label" style="font-size:12px;" for="type">Description</label>
            </td>

            <td class="col-sm-8">
                <textarea name="desc" class="form-control" style="font-size:12px;" disabled>xyz</textarea>
            </td>
        </tr>

        <tr data-index="{{array_shift($field_index)}}">
            <td>
                <i class="drag-handle fa fa-grip-vertical"></i>
            </td>
            <td class="col-sm-4">
                <label class=" col-form-label" style="font-size:12px;" for="type">Department</label>
            </td>
            <td class="col-sm-8">
                <select class="form-control" style="font-size:12px;" disabled>
                    <option style="font-size:12px;">choose Department</option>
                </select>
            </td>
        </tr>

        <tr data-index="{{array_shift($field_index)}}">
            <td>
                <i class="drag-handle fa fa-grip-vertical"></i>
            </td>
            <td class="col-sm-4">
                <label class=" col-form-label" style="font-size:12px;" for="type">Holder</label>
            </td>
            <td class="col-sm-8">
                <input type="text" class="form-control" style="font-size:12px;" value="xyz" disabled>
            </td>
        </tr>

        <tr data-index="{{array_shift($field_index)}}">
            <td>
                <i class="drag-handle fa fa-grip-vertical"></i>
            </td>
            <td class="col-sm-4">
                <label class=" col-form-label" style="font-size:12px;" for="type">Range</label>
            </td>
            <td class="col-sm-8">
                <input type="text" class="form-control" style="font-size:12px;" value="0-10" disabled>
            </td>
        </tr>
        <tr data-index="{{array_shift($field_index)}}">
            <td>
                <i class="drag-handle fa fa-grip-vertical"></i>
            </td>
            <td class="col-sm-4">
                <label class=" col-form-label" style="font-size:12px;" for="least">Least count</label>
            </td>
            <td class="col-sm-8">
                <input type="text" class="form-control" style="font-size:12px;" value="0" disabled>
            </td>
        </tr>
        <tr data-index="{{array_shift($field_index)}}">
            <td>
                <i class="drag-handle fa fa-grip-vertical"></i>
            </td>
            <td class="col-sm-4">
                <label class=" col-form-label" style="font-size:12px;" for="type">Calibration Procedure</label>
            </td>
            <td class="col-sm-8">
                <textarea type="text" class="form-control" style="font-size:12px;" disabled>xyz</textarea>
            </td>
        </tr>

        <tr data-index="{{array_shift($field_index)}}">
            <td>
                <i class="drag-handle fa fa-grip-vertical"></i>
            </td>
            <td class="col-sm-4">
                <label class=" col-form-label" style="font-size:12px;" for="type">Standard Edition</label>
            </td>
            <td class="col-sm-8">
                <input type="text" class="form-control" style="font-size:12px;" value="xyz" disabled>
            </td>
        </tr>

        <tr data-index="{{array_shift($field_index)}}">
            <td>
                <i class="drag-handle fa fa-grip-vertical"></i>
            </td>
            <td class="col-sm-4">
                <label class=" col-form-label" style="font-size:12px;" for="type">Acceptance Criteria</label>
            </td>
            <td class="col-sm-8">
                <input type="text" class="form-control" style="font-size:12px;" value="xyz" disabled>
            </td>
        </tr>
        @if($pageDetail->sub_module=='external')
        <tr data-index="{{array_shift($field_index)}}">
            <td>
                <i class="drag-handle fa fa-grip-vertical"></i>
            </td>
            <td class="col-sm-4">
                <label class=" col-form-label" style="font-size:12px;" for="number">Certficate Number</label>
            </td>
            <td class="col-sm-8">
                <input type="text" class="form-control" style="font-size:12px;" value="xyz" disabled>
            </td>
        </tr>

        <tr data-index="{{array_shift($field_index)}}">
            <td>
                <i class="drag-handle fa fa-grip-vertical"></i>
            </td>
            <td class="col-sm-4">
                <label class=" col-form-label" style="font-size:12px;" for="type">Agency</label>
            </td>
            <td class="col-sm-8">
                <input type="text" class="form-control" style="font-size:12px;" value="xyz" disabled>
            </td>
        </tr>

        <tr data-index="{{array_shift($field_index)}}">
            <td>
                <i class="drag-handle fa fa-grip-vertical"></i>
            </td>
            <td class="col-sm-4">
                <label class=" col-form-label" style="font-size:12px;" for="type">Attachments</label>
            </td>
            <td class="col-sm-8">
                <button class="btn btn-sm btn-secondary" style="font-size:12px;" disabled>View Attachments</button>
            </td>
        </tr>
        @endif
        <tr data-index="{{array_shift($field_index)}}">
            <td>
                <i class="drag-handle fa fa-grip-vertical"></i>
            </td>
            <td class="col-sm-4">
                <label class=" col-form-label" style="font-size:12px;" for="type">Frequency</label>
            </td>
            <td class="col-sm-8">
                <select class="form-control" style="font-size:12px;" disabled>
                    <option style="font-size:12px;">choose frequency</option>
                </select>
            </td>
        </tr>
        <tr data-index="{{array_shift($field_index)}}">
            <td>
                <i class="drag-handle fa fa-grip-vertical"></i>
            </td>
            <td class="col-sm-4">
                <label class="col-form-label" style="font-size:12px;" for="type">Date of First Use</label>
            </td>
            <td class="col-sm-8">
                <input type="date" class="form-control" style="font-size:12px;" placeholder="dd-mm-yyyy" disabled>
            </td>
        </tr>
        <tr data-index="{{array_shift($field_index)}}">
            <td>
                <i class="drag-handle fa fa-grip-vertical"></i>
            </td>
            <td class="col-sm-4">
                <label class="col-form-label" style="font-size:12px;" for="type">Date of Calibration</label>
            </td>
            <td class="col-sm-8">
                <input type="date" class="form-control" style="font-size:12px;" placeholder="dd-mm-yyyy" disabled>
            </td>
        </tr>
        <tr data-index="{{array_shift($field_index)}}">
            <td>
                <i class="drag-handle fa fa-grip-vertical"></i>
            </td>
            <td class="col-sm-4">
                <label class="col-form-label" style="font-size:12px;" for="type">Due Date</label>
            </td>
            <td class="col-sm-8">
                <input type="date" class="form-control" style="font-size:12px;" placeholder="dd-mm-yyyy" disabled>
            </td>
        </tr>
        {!!$fields!!}
        {!!$addonfield!!}
    </tbody>
</table>