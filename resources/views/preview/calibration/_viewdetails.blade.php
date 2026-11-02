@if($pageDetail->sub_module=='internal')
    <table class="table table-striped table-bordered">
        <thead></thead>
        <tbody class="sortable-field">
            <tr data-index="{{array_shift($field_index)}}">
                <td>
                    <i class="drag-handle fa fa-grip-vertical"></i>
                </td>
                <td style="font-size:12px;">Instrument No</td>
                <td style="font-size:12px;">xyz</td>
            </tr>
            <tr data-index="{{array_shift($field_index)}}">
                <td>
                    <i class="drag-handle fa fa-grip-vertical"></i>
                </td>
                <td style="font-size:12px;">Description</td>
                <td style="font-size:12px;">xyz</td>
            </tr>
            <tr data-index="{{array_shift($field_index)}}">
                <td>
                    <i class="drag-handle fa fa-grip-vertical"></i>
                </td>
                <td style="font-size:12px;">Manufacturer</td>
                <td style="font-size:12px;">xyz</td>
            </tr>
            <tr data-index="{{array_shift($field_index)}}">
                <td>
                    <i class="drag-handle fa fa-grip-vertical"></i>
                </td>
                <td style="font-size:12px;">Department</td>
                <td style="font-size:12px;">
                        xyz 
                </td>
            </tr>
            <tr data-index="{{array_shift($field_index)}}">
                <td>
                    <i class="drag-handle fa fa-grip-vertical"></i>
                </td>
                <td style="font-size:12px;">Holder/Location</td>
                <td style="font-size:12px;">xyz</td>
            </tr>
            <tr data-index="{{array_shift($field_index)}}">
                <td>
                    <i class="drag-handle fa fa-grip-vertical"></i>
                </td>
                <td style="font-size:12px;">Type</td>
                <td style="font-size:12px;">xyz</td>
            </tr>
            <tr data-index="{{array_shift($field_index)}}">
                <td>
                    <i class="drag-handle fa fa-grip-vertical"></i>
                </td>
                <td style="font-size:12px;">Range</td>
                <td style="font-size:12px;">0-10</td>
            </tr>
            <tr data-index="{{array_shift($field_index)}}">
                <td>
                    <i class="drag-handle fa fa-grip-vertical"></i>
                </td>
                <td style="font-size:12px;">Least count</td>
                <td style="font-size:12px;">00</td>
            </tr>
            <tr data-index="{{array_shift($field_index)}}">
                <td>
                    <i class="drag-handle fa fa-grip-vertical"></i>
                </td>
                <td style="font-size:12px;">Standard Edition</td>
                <td style="font-size:12px;">xyz</td>
            </tr>
            <tr data-index="{{array_shift($field_index)}}">
                <td>
                    <i class="drag-handle fa fa-grip-vertical"></i>
                </td>
                <td style="font-size:12px;">Procedure</td>
                <td style="font-size:12px;">xyz</td>
            </tr>
            
            <tr data-index="{{array_shift($field_index)}}">
                <td>
                    <i class="drag-handle fa fa-grip-vertical"></i>
                </td>
                <td style="font-size:12px;">Frequency</td>
                <td style="font-size:12px;">xyz</td>
            </tr>
            <tr data-index="{{array_shift($field_index)}}">
                <td>
                    <i class="drag-handle fa fa-grip-vertical"></i>
                </td>
                <td style="font-size:12px;">Standard Edition</td>
                <td style="font-size:12px;">xyz</td>
            </tr>
            <tr data-index="{{array_shift($field_index)}}">
                <td>
                    <i class="drag-handle fa fa-grip-vertical"></i>
                </td>
                <td style="font-size:12px;">Acceptance Criteria</td>
                <td style="font-size:12px;">xyz</td>
            </tr>
            <tr data-index="{{array_shift($field_index)}}">
                <td>
                    <i class="drag-handle fa fa-grip-vertical"></i>
                </td>
                <td style="font-size:12px;">Date of First Use</td>
                <td style="font-size:12px;">
                    dd-mm-yyyy
                </td>
            </tr>
            <tr data-index="{{array_shift($field_index)}}">
                <td>
                    <i class="drag-handle fa fa-grip-vertical"></i>
                </td>
                <td style="font-size:12px;">Date of Calibration</td>
                <td style="font-size:12px;">
                    dd-mm-yyyy
                </td>
            </tr>
            <tr data-index="{{array_shift($field_index)}}">
                <td>
                    <i class="drag-handle fa fa-grip-vertical"></i>
                </td>
                <td style="font-size:12px;">Due Date</td>
                <td style="font-size:12px;">xyz</td>
            </tr>
            <tr data-index="{{array_shift($field_index)}}">
                <td>
                    <i class="drag-handle fa fa-grip-vertical"></i>
                </td>
                <td style="font-size:12px;">Status</td>
                <td style="font-size:12px;"><span class="badge badge-success">Calibrated</span></td>
            </tr>
            {!!$fields!!}
            {!!$addonfield!!}
        </tbody>
    </table>    
@else
    <table class="table table-striped table-bordered">
        <thead></thead>
        <tbody class="sortable-field">
            <tr data-index="{{array_shift($field_index)}}">
                <td>
                    <i class="drag-handle fa fa-grip-vertical"></i>
                </td>
                <td style="font-size:12px;">Instrument No</td>
                <td style="font-size:12px;">xyz</td>
            </tr>
            <tr data-index="{{array_shift($field_index)}}">
                <td>
                    <i class="drag-handle fa fa-grip-vertical"></i>
                </td>
                <td style="font-size:12px;">Description</td>
                <td style="font-size:12px;">xyz</td>
            </tr>
            <tr data-index="{{array_shift($field_index)}}">
                <td>
                    <i class="drag-handle fa fa-grip-vertical"></i>
                </td>
                <td style="font-size:12px;">Manufacturer</td>
                <td style="font-size:12px;">xyz</td>
            </tr>
            <tr data-index="{{array_shift($field_index)}}">
                <td>
                    <i class="drag-handle fa fa-grip-vertical"></i>
                </td>
                <td style="font-size:12px;">Department</td>
                <td style="font-size:12px;">
                        xyz
                </td>
            </tr>
            <tr data-index="{{array_shift($field_index)}}">
                <td>
                    <i class="drag-handle fa fa-grip-vertical"></i>
                </td>
                <td style="font-size:12px;">Holder/Location</td>
                <td style="font-size:12px;">xyz</td>
            </tr>
            <tr data-index="{{array_shift($field_index)}}">
                <td>
                    <i class="drag-handle fa fa-grip-vertical"></i>
                </td>
                <td style="font-size:12px;">Range</td>
                <td style="font-size:12px;">0-10</td>
            </tr>
            <tr data-index="{{array_shift($field_index)}}">
                <td>
                    <i class="drag-handle fa fa-grip-vertical"></i>
                </td>
                <td style="font-size:12px;">Least count</td>
                <td style="font-size:12px;">00</td>
            </tr>
            <tr data-index="{{array_shift($field_index)}}">
                <td>
                    <i class="drag-handle fa fa-grip-vertical"></i>
                </td>
                <td style="font-size:12px;">Procedure</td>
                <td style="font-size:12px;">xyz</td>
            </tr>

            <tr data-index="{{array_shift($field_index)}}">
                <td>
                    <i class="drag-handle fa fa-grip-vertical"></i>
                </td>
                <td style="font-size:12px;">Agency</td>
                <td style="font-size:12px;">xyz</td>
            </tr>

            <tr data-index="{{array_shift($field_index)}}">
                <td>
                    <i class="drag-handle fa fa-grip-vertical"></i>
                </td>
                <td style="font-size:12px;">Attachments</td>
                <td style="font-size:12px;">
                    <button class="btn btn-sm btn-secondary" disabled style="font-size: 11px;">View Attachments</button>
                </td>
            </tr>
            <tr data-index="{{array_shift($field_index)}}">
                <td>
                    <i class="drag-handle fa fa-grip-vertical"></i>
                </td>
                <td style="font-size:12px;">Frequency</td>
                <td style="font-size:12px;">xyz</td>
            </tr>
            <tr data-index="{{array_shift($field_index)}}">
                <td>
                    <i class="drag-handle fa fa-grip-vertical"></i>
                </td>
                <td style="font-size:12px;">Standard Edition</td>
                <td style="font-size:12px;">xyz</td>
            </tr>
            <tr data-index="{{array_shift($field_index)}}">
                <td>
                    <i class="drag-handle fa fa-grip-vertical"></i>
                </td>
                <td style="font-size:12px;">Acceptance Criteria</td>
                <td style="font-size:12px;">xyz</td>
            </tr>
            <tr data-index="{{array_shift($field_index)}}">
                <td>
                    <i class="drag-handle fa fa-grip-vertical"></i>
                </td>
                <td style="font-size:12px;">Date of First Use</td>
                <td style="font-size:12px;">
                    dd-mm-yyyy
                </td>
            </tr>
            <tr data-index="{{array_shift($field_index)}}">
                <td>
                    <i class="drag-handle fa fa-grip-vertical"></i>
                </td>
                <td style="font-size:12px;">Date of Calibration</td>
                <td style="font-size:12px;">
                    dd-mm-yyyy
                </td>
            </tr>
            <tr data-index="{{array_shift($field_index)}}">
                <td>
                    <i class="drag-handle fa fa-grip-vertical"></i>
                </td>
                <td style="font-size:12px;">Due Date</td>
                <td style="font-size:12px;">
                    dd-mm-yyyy
                </td>
            </tr>
            <tr data-index="{{array_shift($field_index)}}">
                <td>
                    <i class="drag-handle fa fa-grip-vertical"></i>
                </td>
                <td style="font-size:12px;">Certificate Number</td>
                <td style="font-size:12px;">
                    xyz
                </td>
            </tr>
            <tr data-index="{{array_shift($field_index)}}">
                <td>
                    <i class="drag-handle fa fa-grip-vertical"></i>
                </td>
                <td style="font-size:12px;">Status</td>
                <td style="font-size:12px;"><span class="badge badge-success">Calibrated</span></td>
            </tr>
            {!!$fields!!}
            {!!$addonfield!!}
        </tbody>
    </table>
@endif