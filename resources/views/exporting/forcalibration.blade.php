<style>
    .tag-style {
        display:block;
        font-size:9px;
        color:#555;
        background:#f2f2f2;
        padding:2px 6px;
        border-radius:4px;
        margin-top:2px;"
    }
</style>
@if(count($internal_datas)>0)
    <table class="table table-responsive table-bordered table-striped table-vcenter " style="display:none;"
           id="headerTable1">
        <thead>
        <tr>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span
                        style="font-size:10px;text-transform:capitalize">S.No</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span
                        style="font-size:10px;text-transform:capitalize">{{$calibrationFieldRules['Instrument_no']['field_display_label']}}</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span
                        style="font-size:10px;text-transform:capitalize">{{$calibrationFieldRules['Instrument_desc']['field_display_label']}}</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span
                        style="font-size:10px;text-transform:capitalize">{{$calibrationFieldRules['Instru_Manufacturer']['field_display_label']}}</span></th>
            <th class="text-center" style="font-size:12px;width: 200px;text-transform:capitalize"><span
                        style="font-size:10px;text-transform:capitalize">Department</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span
                        style="font-size:10px;text-transform:capitalize">{{$calibrationFieldRules['Holder']['field_display_label']}}</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span
                        style="font-size:10px;text-transform:capitalize">{{$calibrationFieldRules['Capacity']['field_display_label']}}</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span
                        style="font-size:10px;text-transform:capitalize">Least count</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span
                        style="font-size:10px;text-transform:capitalize">Standard Edition</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span
                        style="font-size:10px;text-transform:capitalize">Acceptance Criteria</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span
                        style="font-size:10px;text-transform:capitalize">Frequency</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span
                        style="font-size:10px;text-transform:capitalize">Date of Calibration</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span
                        style="font-size:10px;text-transform:capitalize">Due Date</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span
                        style="font-size:10px;text-transform:capitalize">Status</span></th>
        </tr>
        </thead>
        <?php $no = 0;?>

        @foreach($internal_datas as $key => $value)
            @php
                $duedate= $value->Duedate;
                $ddate = date('Y-m-d',strtotime($duedate));
                $dudat = explode('-',$ddate);
                $dudateval = $duedate == NULL ? NULL : $dudat[2]."-".$dudat[1]."-".$dudat[0];

                $neartoexpiry=date('Y-m-d',strtotime($duedate. ' - 30 days'));
                $today_dt=date("Y-m-d");
                $rowCalibrationFieldRules = json_decode($value->calibration_field_rules, true);
            @endphp

            <tbody>
            <tr>
                <td class="text-center" style="font-size:12px;width: 50px;"><span
                            style="font-size:10px;width: 50px;">{{++$no}}</span></td>
                <td class="text-center" style="font-size:12px;width: 50px;"><span
                    style="font-size:10px;width: 50px;">{{ $value->Instrument_no }}</span>
                    @php
                        $tag = App\Helpers\Calibration\CalibrationFormConfigurationHelper::getTag($rowCalibrationFieldRules, $calibrationFieldRules,  'Instrument_no', 'Instrument number');
                    @endphp
                    @if ($tag)
                        <br>
                        <span style="font-size:9px; color:#555; background:#f2f2f2; padding:2px 6px; border-radius:4px; margin-top:2px;">
                            {{$tag}}</span>
                    @endif
                </td>

                <td class="text-center" style="font-size:12px;width: 50px;">
                    <span style="font-size:10px;width: 50px;">{{ $value->Instrument_desc  }}</span>
                    @php
                        $tag = App\Helpers\Calibration\CalibrationFormConfigurationHelper::getTag($rowCalibrationFieldRules, $calibrationFieldRules,  'Instrument_desc', 'Instrument description');
                    @endphp
                    @if ($tag)
                        <br>
                        <span style="font-size:9px; color:#555; background:#f2f2f2; padding:2px 6px; border-radius:4px; margin-top:2px;">
                            {{$tag}}</span>
                    @endif
                </td>
                <td class="text-center" style="font-size:12px;width: 50px;">
                    <span style="font-size:10px;width: 50px;">{{ $value->Instru_Manufacturer=='no value'?'--': $value->Instru_Manufacturer }}</span>
                    @php
                        $tag = App\Helpers\Calibration\CalibrationFormConfigurationHelper::getTag($rowCalibrationFieldRules, $calibrationFieldRules,  'Instru_Manufacturer', 'Instrument manufacturer');
                    @endphp
                    @if ($tag)
                        <br>
                        <span style="font-size:9px; color:#555; background:#f2f2f2; padding:2px 6px; border-radius:4px; margin-top:2px;">
                            {{$tag}}</span>
                    @endif
                </td>
                <td class="text-center" style="font-size:12px;width: 50px;">
                <span style="font-size:10px;width: 50px;">
                    @if(is_numeric($value->Department))
                        {{$value->departmentdetails? $value->departmentdetails->departmentname : ''}}
                    @else
                        {{$value->Department}}
                    @endif
                </span></td>
                <td class="text-center" style="font-size:12px;width: 50px;">
                    <span style="font-size:10px;width: 50px;">{{ $value->Holder }}</span>
                    @php
                        $tag = App\Helpers\Calibration\CalibrationFormConfigurationHelper::getTag($rowCalibrationFieldRules, $calibrationFieldRules,  'Holder', 'Instrument holder/location');
                    @endphp
                    @if ($tag)
                        <br>
                        <span style="font-size:9px; color:#555; background:#f2f2f2; padding:2px 6px; border-radius:4px; margin-top:2px;">
                            {{$tag}}</span>
                    @endif
                </td>
                <td class="text-center" style="font-size:12px;width: 50px;">
                    <span style="font-size:10px;width: 50px;">{{ $value->Capacity  }}</span>
                    @php
                        $tag = App\Helpers\Calibration\CalibrationFormConfigurationHelper::getTag($rowCalibrationFieldRules, $calibrationFieldRules,  'Capacity', 'Instrument capacity/range');
                    @endphp
                    @if ($tag)
                        <br>
                        <span style="font-size:9px; color:#555; background:#f2f2f2; padding:2px 6px; border-radius:4px; margin-top:2px;">
                            {{$tag}}</span>
                    @endif
                </td>
                <td class="text-center" style="font-size:12px;width: 50px;"><span
                            style="font-size:10px;width: 50px;">{{ $value->least_count?$value->least_count:'--'  }}</span>
                </td>
                <td class="text-center" style="font-size:12px;width: 50px;"><span
                            style="font-size:10px;width: 50px;">{{ $value->standard_edition?$value->standard_edition:'--'  }}</span>
                </td>
                <td class="text-center" style="font-size:12px;width: 50px;"><span
                            style="font-size:10px;width: 50px;">{{ $value->acceptance_criteria?$value->acceptance_criteria:'--'  }}</span>
                </td>
                <td class="text-center" style="font-size:12px;width: 50px;"><span
                            style="font-size:10px;width: 50px;">{{ $value->Frequency  }}</span></td>
                <td class="text-center" style="font-size:12px;width: 50px;"><span
                            style="font-size:10px;width: 50px;">{{$value->dateofcal==""?'--':date('d-m-Y',strtotime($value->dateofcal))}}</span>
                </td>
                <td class="text-center" style="font-size:12px;width: 50px;"><span
                            style="font-size:10px;width: 50px;">{{$value->Duedate==""?'--':date('d-m-Y',strtotime($value->Duedate))}}</span>
                </td>

                <td class="text-center" style="font-size:12px;width: 50px;">
                    @if($value->Status == \App\Calibration_instrument::UNDER_CALIBRATION_STATUS)
                        <span style="font-size:10px;color:#5179d6;">Under Calibration</span>
                    @else
                        @if($value->Duedate==NULL)
                            <span style="font-size:10px;color:#6c757d;">Not Calibrated</span>
                        @elseif($value->Duedate<$today_dt || $value->Status=="Expired")
                            <span style="font-size:10px;color:#dc3545;">Expired</span>
                        @elseif($neartoexpiry<=$today_dt || $value->Status=="Near to expiry")
                            <span style="font-size:10px;color:#ffc107;">Near to expiry</span>
                        @elseif($value->Duedate>$today_dt || $value->Status=="Calibrated")
                            <span style="font-size:10px;color:#28a745;">Calibrated</span>
                        @endif
                    @endif
                </td>
            </tr>

            </tbody>
        @endforeach

    </table>
@endif
@if(count($external_datas)>0)
    <table class="table table-responsive table-bordered table-striped table-vcenter " style="display:none;"
           id="headerTable2">
        <thead>
        <tr>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span
                        style="font-size:10px;text-transform:capitalize">S.No</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span
                        style="font-size:10px;text-transform:capitalize">{{$calibrationFieldRules['Instrument_no']['field_display_label']}}</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span
                        style="font-size:10px;text-transform:capitalize">{{$calibrationFieldRules['Instrument_desc']['field_display_label']}}</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span
                        style="font-size:10px;text-transform:capitalize">{{$calibrationFieldRules['Instru_Manufacturer']['field_display_label']}}</span></th>
            <th class="text-center" style="font-size:12px;width: 200px;text-transform:capitalize"><span
                        style="font-size:10px;text-transform:capitalize">Department</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span
                        style="font-size:10px;text-transform:capitalize">{{$calibrationFieldRules['Holder']['field_display_label']}}</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span
                        style="font-size:10px;text-transform:capitalize">{{$calibrationFieldRules['Capacity']['field_display_label']}}</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span
                        style="font-size:10px;text-transform:capitalize">Least count</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span
                        style="font-size:10px;text-transform:capitalize">{{$calibrationFieldRules['Agency']['field_display_label']}}</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span
                        style="font-size:10px;text-transform:capitalize">Frequency</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span
                        style="font-size:10px;text-transform:capitalize">Date of Calibration</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span
                        style="font-size:10px;text-transform:capitalize">Due Date</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span
                        style="font-size:10px;text-transform:capitalize">Certificate Number</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span
                        style="font-size:10px;text-transform:capitalize">Status</span></th>
        </tr>
        </thead>
        <?php $no = 0;?>

        @foreach($external_datas as $key => $value)
            @php
                $duedate= $value->Duedate;
                $ddate = date('Y-m-d',strtotime($duedate));
                $dudat = explode('-',$ddate);
                $dudateval = $duedate == NULL ? NULL : $dudat[2]."-".$dudat[1]."-".$dudat[0];

                $neartoexpiry=date('Y-m-d',strtotime($duedate. ' - 30 days'));
                $today_dt=date("Y-m-d");
                $rowCalibrationFieldRules = json_decode($value->calibration_field_rules, true);
            @endphp

            <tbody>
            <tr>
                <td class="text-center" style="font-size:12px;width: 50px;"><span
                            style="font-size:10px;width: 50px;">{{++$no}}</span></td>
                <td class="text-center" style="font-size:12px;width: 50px;">
                    <span style="font-size:10px;width: 50px;">{{ $value->Instrument_no }}</span>
                    @php
                        $tag = App\Helpers\Calibration\CalibrationFormConfigurationHelper::getTag($rowCalibrationFieldRules, $calibrationFieldRules,  'Instrument_no', 'Instrument number');
                    @endphp
                    @if ($tag)
                        <br>
                        <span style="font-size:9px; color:#555; background:#f2f2f2; padding:2px 6px; border-radius:4px; margin-top:2px;">
                            {{$tag}}</span>
                    @endif
                </td>
                <td class="text-center" style="font-size:12px;width: 50px;">
                    <span style="font-size:10px;width: 50px;">{{ $value->Instrument_desc  }}</span>
                    @php
                        $tag = App\Helpers\Calibration\CalibrationFormConfigurationHelper::getTag($rowCalibrationFieldRules, $calibrationFieldRules,  'Instrument_desc', 'Instrument description');
                    @endphp
                    @if ($tag)
                        <br>
                        <span style="font-size:9px; color:#555; background:#f2f2f2; padding:2px 6px; border-radius:4px; margin-top:2px;">
                            {{$tag}}</span>
                    @endif
                </td>
                <td class="text-center" style="font-size:12px;width: 50px;">
                    <span style="font-size:10px;width: 50px;">{{ $value->Instru_Manufacturer=='no value'?'--': $value->Instru_Manufacturer }}</span>
                    @php
                        $tag = App\Helpers\Calibration\CalibrationFormConfigurationHelper::getTag($rowCalibrationFieldRules, $calibrationFieldRules,  'Instru_Manufacturer', 'Instrument manufacturer');
                    @endphp
                    @if ($tag)
                        <br>
                        <span style="font-size:9px; color:#555; background:#f2f2f2; padding:2px 6px; border-radius:4px; margin-top:2px;">
                            {{$tag}}</span>
                    @endif
                </td>
                <td class="text-center" style="font-size:12px;width: 50px;">
                <span style="font-size:10px;width: 50px;">
                    @if(is_numeric($value->Department))
                        {{$value->departmentdetails? $value->departmentdetails->departmentname : ''}}
                    @else
                        {{$value->Department}}
                    @endif
                </span></td>
                <td class="text-center" style="font-size:12px;width: 50px;">
                    <span style="font-size:10px;width: 50px;">{{ $value->Holder }}</span>
                    @php
                        $tag = App\Helpers\Calibration\CalibrationFormConfigurationHelper::getTag($rowCalibrationFieldRules, $calibrationFieldRules,  'Holder', 'Instrument holder/location');
                    @endphp
                    @if ($tag)
                        <br>
                        <span style="font-size:9px; color:#555; background:#f2f2f2; padding:2px 6px; border-radius:4px; margin-top:2px;">
                            {{$tag}}</span>
                    @endif
                </td>
                <td class="text-center" style="font-size:12px;width: 50px;">
                    <span style="font-size:10px;width: 50px;">{{ $value->Capacity  }}</span>
                    @php
                        $tag = App\Helpers\Calibration\CalibrationFormConfigurationHelper::getTag($rowCalibrationFieldRules, $calibrationFieldRules,  'Capacity', 'Instrument capacity/range');
                    @endphp
                    @if ($tag)
                        <br>
                        <span style="font-size:9px; color:#555; background:#f2f2f2; padding:2px 6px; border-radius:4px; margin-top:2px;">
                            {{$tag}}</span>
                    @endif
                </td>
                <td class="text-center" style="font-size:12px;width: 50px;"><span
                            style="font-size:10px;width: 50px;">{{ $value->least_count?$value->least_count:'--'  }}</span>
                </td>
                <td class="text-center" style="font-size:12px;width: 50px;">
                    <span style="font-size:10px;width: 50px;">{{ $value->Agency  }}</span>
                    @php
                        $tag = App\Helpers\Calibration\CalibrationFormConfigurationHelper::getTag($rowCalibrationFieldRules, $calibrationFieldRules,  'Agency', 'Calibration agency');
                    @endphp
                    @if ($tag)
                        <br>
                        <span style="font-size:9px; color:#555; background:#f2f2f2; padding:2px 6px; border-radius:4px; margin-top:2px;">
                            {{$tag}}</span>
                    @endif
                </td>
                <td class="text-center" style="font-size:12px;width: 50px;"><span
                            style="font-size:10px;width: 50px;">{{ $value->Frequency  }}</span></td>
                <td class="text-center" style="font-size:12px;width: 50px;"><span
                            style="font-size:10px;width: 50px;">{{$value->dateofcal==""?'--':date('d-m-Y',strtotime($value->dateofcal))}}</span>
                </td>
                <td class="text-center" style="font-size:12px;width: 50px;"><span
                            style="font-size:10px;width: 50px;">{{$value->Duedate==""?'--':date('d-m-Y',strtotime($value->Duedate))}}</span>
                </td>
                <td class="text-center" style="font-size:12px;width: 50px;"><span
                            style="font-size:10px;width: 50px;">
                        @php
                            $reportno = $value->calibrationExternalReportNo ? $value->calibrationExternalReportNo->Report_no : '';
                        @endphp
                        {{$reportno?$reportno:'-'}}
                    </span>
                </td>
                <td class="text-center" style="font-size:12px;width: 50px;">
                    @if($value->Status == \App\Calibration_instrument::UNDER_CALIBRATION_STATUS)
                        <span style="font-size:10px;color:#5179d6;">Under Calibration</span>
                    @elseif($value->Status == 'Obsolete')
                        <span style="color:darkgray;font-size:10px;">Obsolete</span></td>
                    @else
                        @if($value->Duedate==NULL)
                            <span style="font-size:10px;color:#6c757d;">Not Calibrated</span>
                        @elseif($value->Duedate<$today_dt || $value->Status=="Expired")
                            <span style="font-size:10px;color:#dc3545;">Expired</span>
                        @elseif($neartoexpiry<=$today_dt || $value->Status=="Near to expiry")
                            <span style="font-size:10px;color:#ffc107;">Near to expiry</span>
                        @elseif($value->Duedate>$today_dt || $value->Status=="Calibrated")
                            <span style="font-size:10px;color:#28a745;">Calibrated</span>
                        @endif
                    @endif
                </td>
            </tr>

            </tbody>
        @endforeach

    </table>
@endif


<script type="text/javascript">
    var tab_text

    function fnCovertTable(type, underCalibration = 0) {
        if (document.getElementById('tabselected').value == 'a1') {
            pdfvalue = "tvalue1";
            let isInternal = underCalibration ? false : true;
            $('.nav-link').each(function () {
                if ($(this).hasClass('active') && $(this).attr('id') == 'external-tab') {
                    isInternal = false;
                }
            });
            if (isInternal) {
                exptable = "headerTable1";
            } else {
                exptable = "headerTable2";
            }
            x = 0;
        }

        tab_text = "<style>.text-center{ text-align: center }</style><table border='1' style='border-collapse:collapse;' bordercolor='black'  align='center' >";
        tab = document.getElementById(exptable);// id of table
        for (j = 0; j < tab.rows.length; j++) {
            tab_text = tab_text + "<tr>"
            for (i = 0; i < tab.rows[j].cells.length - x; i++) {
                tab_text = tab_text + "<td class='text-center'>"
                tab_text = tab_text + tab.rows[j].cells[i].innerHTML + "</td>";
            }
            tab_text = tab_text + "</tr>";
        }

        tab_text = tab_text + "</table>";
        tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
        tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
        tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params
        if (type == 'excel') {
            tab_text = "<style>.text-center{ text-align: center }</style><table border='1' style='border-collapse:collapse;' bordercolor='black'  align='center' >";
            tab = document.getElementById(exptable);// id of table
            for (j = 0; j < tab.rows.length; j++) {
                tab_text = tab_text + "<tr>"
                tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
            }

            tab_text = tab_text + "</table>";
            tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
            tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
            tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params
            var ua = window.navigator.userAgent;
            var msie = ua.indexOf("MSIE ");
            if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
            {
                txtArea1.document.open("txt/html", "replace");
                txtArea1.document.write(tab_text);
                txtArea1.document.close();
                txtArea1.focus();
                sa = txtArea1.document.execCommand("SaveAs", true, "Say Thanks to Sumit.xls");
                return (sa);
            } else {
                // Create a Blob with the Excel MIME type
                const blob = new Blob([tab_text], { type: "application/vnd.ms-excel" });
                const url = URL.createObjectURL(blob);
                const currentUsername = document.getElementById('current_user_name').value

                // Open in a new tab or prompt download
                const link = document.createElement("a");
                link.href = url;
                link.download = "excelsavedby_" + currentUsername + ".xls"; // Specify the file name
                link.click();
                URL.revokeObjectURL(url); // Clean up
            }
        } else if (type == 'word') {
            const orientation = document.getElementById('orientation').value;
            const currentUsername = document.getElementById('current_user_name').value
            const html = "<style>@page portrait_A4_page{size:595.3pt 841.9pt;margin:72.0pt 72.0pt 72.0pt 72.0pt;mso-header-margin:35.4pt;mso-footer-margin:35.4pt;mso-paper-source:0;} " +
                " div.portrait_A4_page { page:portrait_A4_page;} " +
                " @page landscape_A4_page {size:841.9pt 595.3pt;mso-page-orientation:landscape;margin:72.0pt 72.0pt 72.0pt 72.0pt;mso-header-margin:35.45pt;mso-footer-margin:35.45pt;mso-paper-source:0;} " +
                " div.landscape_A4_page { page:landscape_A4_page;} " +
                " .text-center{ align-content: center } " +
                "</style><div class=" + (orientation === "portrait" ? "portrait_A4_page" : "landscape_A4_page") + ">" + tab_text + "</div>"
            var ua = window.navigator.userAgent;
            var msie = ua.indexOf("MSIE ");
            if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
            {
                txtArea1.document.open("txt/html", "replace");
                txtArea1.document.write(tab_text);
                txtArea1.document.close();
                txtArea1.focus();
                sa = div.innerHTML.document.execCommand("SaveAs", true, "Setilas.doc");
                return (sa);
            } else {
                const source = 'data:application/msword,' + encodeURIComponent(html)
                var fileDownload = document.createElement("a");
                document.body.appendChild(fileDownload);
                fileDownload.href = source;
                fileDownload.download = "wordsavedby_" + currentUsername + ".doc"
                fileDownload.click();
                document.body.removeChild(fileDownload);
            }
        } else if (type == 'print') {
            var oldPage = document.body.innerHTML;
            document.body.innerHTML = tab_text;
            window.print();
            document.body.innerHTML = oldPage;
        } else if (type == "screenshot") {
            oldpage = document.body.innerHTML;
            document.body.innerHTML = tab_text;
            document.body.innerHTML = document.body.innerHTML + "<br/><button value='10' style='float:right'  class='btn btn-success' onclick='saveme()' id='save' download='file-name.png'>Save</button><span></span><button style='float:right' class='btn btn-success' onclick='back()'>Back</button><a id='blank'></a>";
        } else if (type == "pdf") {
            // tab_text="<table border='1' style='border-collapse:collapse;' bordercolor='black'  align='center' >";
            // var textRange; var j=0;var i=0;
            // tab = document.getElementById(exptable);// id of table
            // tab_text=tab_text+"<tr>"
            // for(i=0;i<tab.rows[0].cells.length-x;i++)
            // {
            //     tab_text=tab_text+"<th>"
            //          tab_text=tab_text+tab.rows[0].cells[i].innerHTML+"</th>";
            // }
            // tab_text=tab_text+"</tr>"
            // for(j = 1 ; j < tab.rows.length ; j++)
            //     {   tab_text=tab_text+"<tr>"
            //     for(i=0;i<tab.rows[j].cells.length-x;i++)
            //         {
            //          tab_text=tab_text+"<td>"
            //          tab_text=tab_text+tab.rows[j].cells[i].innerHTML+"</td>";
            //         }
            //          tab_text=tab_text+"</tr>";
            //     }
            //
            // tab_text=tab_text+"</table>";
            //
            // tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
            // tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
            // tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params


            document.getElementById(pdfvalue).value = tab_text;
            return true;

        } else if (type == 'magnify') {
            tab_text = "<table border='1' style='border-collapse:collapse;' bordercolor='black'  align='center' >";
            var textRange;
            var j = 0;
            var i = 0;
            tab = document.getElementById('mainTable');// id of table
            for (j = 0; j < tab.rows.length; j++) {
                tab_text = tab_text + "<tr>"
                tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
            }

            tab_text = tab_text + "</table>";
            tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
            tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
            tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params
            oldpage = document.body.innerHTML;
            var div1 = document.createElement('div');
            div1.setAttribute("id", "div1");
            var div2 = document.createElement('div');
            div2.setAttribute("id", "div2");
            div1.innerHTML = tab_text;
            div2.innerHTML = "<button type='button' class='btn btn-success'  onclick='back()'>Reset</button>"
            document.body.innerHTML = ''
            document.body.appendChild(div1);
            document.body.appendChild(div2);

        }
    }
</script>
