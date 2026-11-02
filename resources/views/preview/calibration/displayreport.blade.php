{{-- <table>
    <thead>
        <tr style="border-top:3px solid #28a745;">     
            <td  style="width:100px;height:35px;font-size:13px;background:#ededed;"><center>Sl No</center></td>
            <td  style="width:130px;height:35px;font-size:13px;background:#ededed;"><center>Master Reading</center></td>
            <td  style="width:120px;height:35px;font-size:13px;background:#ededed;"><center>Instrument Reading<center></td>
            <td  style="width:135px;height:35px;font-size:13px;background:#ededed;"><center>Error</center></td>  
            <td style="width:135px;height:35px;font-size:13px;background:#ededed;"><center>Remarks</center></td>  
        </tr>
    </thead>
    <tbody class="sortable-field">
        <tr>                                                      
            <td class="text-center" style="font-size:12px;width: 50px;height:30px">&nbsp;&nbsp;&nbsp;1</td>
            <td class="text-center" style="font-size:12px;width: 50px;">&nbsp;&nbsp;&nbsp;xyz</td>     
            <td class="text-center" style="font-size:12px;width: 50px;">&nbsp;&nbsp;&nbsp;xyz</td> 
            <td class="text-center "style="font-size:12px;width: 50px;">&nbsp;&nbsp;&nbsp;xyz</td>
            <td colspan="2" class="text-center "style="font-size:12px;width: 50px;">&nbsp;&nbsp;&nbsp;
                xyz
            </td>   
        </tr>
    </tbody>
</table>  --}}
<table class="table table-striped table-bordered table-hover" style="width:100%;font-size:12px;">
    <thead></thead>
    <tbody class="sortable-field">
        <tr data-index="{{array_shift($field_index)}}">
            <td>
                <i class="drag-handle fa fa-grip-vertical"></i>
            </td>
            <td style="font-size:12px;">Instrument Description</td>
            <td style="font-size:12px;">xyz</td>
        </tr>
        <tr data-index="{{array_shift($field_index)}}">
            <td>
                <i class="drag-handle fa fa-grip-vertical"></i>
            </td>
            <td style="font-size:12px;">Instrument Number</td>
            <td style="font-size:12px;">xyz</td>
        </tr>
        <tr data-index="{{array_shift($field_index)}}">
            <td>
                <i class="drag-handle fa fa-grip-vertical"></i>
            </td>
            <td style="font-size:12px;">Calibration Procedure</td>
            <td style="font-size:12px;">xyz</td>
        </tr>
        <tr data-index="{{array_shift($field_index)}}">
            <td>
                <i class="drag-handle fa fa-grip-vertical"></i>
            </td>
            <td style="font-size:12px;">Acceptable error</td>
            <td style="font-size:12px;">
                    xyz
            </td>
        </tr>
        <tr data-index="{{array_shift($field_index)}}">
            <td>
                <i class="drag-handle fa fa-grip-vertical"></i>
            </td>
            <td style="font-size:12px;">Master instrument</td>
            <td style="font-size:12px;">xyz</td>
        </tr>
        <tr data-index="{{array_shift($field_index)}}">
            <td>
                <i class="drag-handle fa fa-grip-vertical"></i>
            </td>
            <td style="font-size:12px;">Master instrument certificate number</td>
            <td style="font-size:12px;">xyz</td>
        </tr>
        <tr data-index="{{array_shift($field_index)}}">
            <td>
                <i class="drag-handle fa fa-grip-vertical"></i>
            </td>
            <td style="font-size:12px;">Report Number</td>
            <td style="font-size:12px;">xyz</td>
        </tr>
        <tr data-index="{{array_shift($field_index)}}">
            <td>
                <i class="drag-handle fa fa-grip-vertical"></i>
            </td>
            <td style="font-size:12px;">Calibration status</td>
            <td style="font-size:12px;">xyz</td>
        </tr>

        <tr data-index="{{array_shift($field_index)}}">
            <td>
                <i class="drag-handle fa fa-grip-vertical"></i>
            </td>
            <td style="font-size:12px;">Adjustment made</td>
            <td style="font-size:12px;">xyz</td>
        </tr>

        <tr data-index="{{array_shift($field_index)}}">
            <td>
                <i class="drag-handle fa fa-grip-vertical"></i>
            </td>
            <td style="font-size:12px;">Date of calibration</td>
            <td style="font-size:12px;">dd/mm/yyyy</td>
        </tr>
        <tr data-index="{{array_shift($field_index)}}">
            <td>
                <i class="drag-handle fa fa-grip-vertical"></i>
            </td>
            <td style="font-size:12px;">Calibrated by</td>
            <td style="font-size:12px;">xyz</td>
        </tr>
        <tr data-index="{{array_shift($field_index)}}">
            <td>
                <i class="drag-handle fa fa-grip-vertical"></i>
            </td>
            <td style="font-size:12px;">Verified by</td>
            <td style="font-size:12px;">xyz</td>
        </tr>
        {!!$fields!!}
        {!!$addonfield!!}
    </tbody>
</table>