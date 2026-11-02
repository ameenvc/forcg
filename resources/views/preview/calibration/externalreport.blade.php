{{-- <tr style="border-top:3px solid #28a745;">     
    <td  style="width:100px;height:35px;font-size:12px;background:#ededed;"><center><b>Report No</b></center></td>
    <td  style="width:130px;height:35px;font-size:12px;background:#ededed;"><center><b>Date of Calibration</b></center></td>
    <td  style="width:120px;height:35px;font-size:12px;background:#ededed;"><center><b>Calibration Status</b><center></td>
    <td  style="width:135px;height:35px;font-size:12px;background:#ededed;"><center><b>Calibration Due Date</b></center></td>  
</tr>                              
<tr>                                                     
    <td class="text-center" style="font-size:12px;width: 50px;height:30px">xyz</td>
    <td class="text-center" style="font-size:12px;width: 50px;">
        dd-mm-yyyy
    </td>
    <td class="text-center" style="font-size:12px;width: 50px;">
        <span class="badge badge-success">Calibrated</span>
    </td>
    <td class="text-center "style="font-size:12px;width: 50px;">
        dd-mm-yyyy    
    </td>
</tr>   --}}
<table class="table table-striped table-bordered table-hover" style="width:100%;font-size:12px;">
    <thead></thead>
    <tbody class="sortable-field">
        <tr data-index="{{array_shift($field_index)}}">
            <td>
                <i class="drag-handle fa fa-grip-vertical"></i>
            </td>
            <td style="font-size:12px;">Instrument Name</td>
            <td style="font-size:12px;">xyz</td>
        </tr>
        <tr data-index="{{array_shift($field_index)}}">
            <td>
                <i class="drag-handle fa fa-grip-vertical"></i>
            </td>
            <td style="font-size:12px;">Instrument Manufacturer</td>
            <td style="font-size:12px;">xyz</td>
        </tr>
        <tr data-index="{{array_shift($field_index)}}">
            <td>
                <i class="drag-handle fa fa-grip-vertical"></i>
            </td>
            <td style="font-size:12px;">Instrument serial No</td>
            <td style="font-size:12px;">xyz</td>
        </tr>
        <tr data-index="{{array_shift($field_index)}}">
            <td>
                <i class="drag-handle fa fa-grip-vertical"></i>
            </td>
            <td style="font-size:12px;">Instrument Number</td>
            <td style="font-size:12px;">
                    xyz
            </td>
        </tr>
        <tr data-index="{{array_shift($field_index)}}">
            <td>
                <i class="drag-handle fa fa-grip-vertical"></i>
            </td>
            <td style="font-size:12px;">Calibration frequency</td>
            <td style="font-size:12px;">xyz</td>
        </tr>
        <tr data-index="{{array_shift($field_index)}}">
            <td>
                <i class="drag-handle fa fa-grip-vertical"></i>
            </td>
            <td style="font-size:12px;">Capacity</td>
            <td style="font-size:12px;">xyz</td>
        </tr>
        <tr data-index="{{array_shift($field_index)}}">
            <td>
                <i class="drag-handle fa fa-grip-vertical"></i>
            </td>
            <td style="font-size:12px;">Instrument Location</td>
            <td style="font-size:12px;">xyz</td>
        </tr>
        <tr data-index="{{array_shift($field_index)}}">
            <td>
                <i class="drag-handle fa fa-grip-vertical"></i>
            </td>
            <td style="font-size:12px;">Date of First Use</td>
            <td style="font-size:12px;">dd-mm-yyyy</td>
        </tr>
        {!!$fields!!}
        {!!$addonfield!!}
    </tbody>    
</table>                                     