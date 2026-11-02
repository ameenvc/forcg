@extends('layouts.backend')
@section('content')
<style>
    .bor {
    border: 1px solid black;
    border-collapse: collapse;

    }

    .outlined {
    border: 1px solid white;
    }
    table{

    border-collapse: collapse;
    }
    td{
        border:1px solid #D0D0D0;
    }
</style>
<!-- Hero -->
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center"  style="height:0px;">

            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">Calibration Management</li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="">Calibration log</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="/user/calibrationmgmt">Back</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<!-- END Hero -->
<div class="content">
    <div class="block block-rounded">
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-sm-12">
                    <div class="table-responsive">
                        @if( count($data2) > 0)
                            <?php $no=0;?>
                            <table width="100%" >
                                <tbody class="sortable-field">
                                    <tr id="firstTableRow">
                                        <td colspan="6" style="font-size:20px;height:35px;font-family:Arial, Helvetica, sans-serif;" class="bg-success text-white"><center><b>Internal Calibration Report</b></center></td>
                                    </tr>
                                    @foreach($data2 as $value)
                                        @php
                                            $caldt = $value->Dateofcal;
                                            $cdate = date('Y-m-d',strtotime($caldt));
                                            $caldat = explode('-',$cdate);
                                            $datecalval = $caldt == null ? null : $caldat[2]."-".$caldat[1]."-".$caldat[0];
                                            $calibrationFieldRules = json_decode($value->calibration_field_rules, true);
                                        @endphp
                                        @if ($calibrationFieldRules['Instrument_desc']['enable_field'])
                                            <tr class="field-row" data-index="{{ array_shift($customFieldIndex) }}">
                                                <td style="width:200px;font-size:13px;background:#ededed;" >&nbsp;&nbsp;&nbsp;{{ $calibrationFieldRules ? $calibrationFieldRules['Instrument_desc']['field_display_label'] : 'Instrument description'}}</td>
                                                <td style="width:240px;font-size:13px">&nbsp;&nbsp;&nbsp;{{$value->Instrument_desc}}</td>
                                            </tr>
                                        @endif
                                        @if ($calibrationFieldRules['Instrument_no']['enable_field'])
                                            <tr class="field-row" data-index="{{ array_shift($customFieldIndex) }}">
                                                <td style="width:200px;height:35px;font-size:13px;background:#ededed;" >&nbsp;&nbsp;&nbsp;{{ $calibrationFieldRules ? $calibrationFieldRules['Instrument_no']['field_display_label'] : 'Instrument number'}}</td>
                                                <td style="width:240px;height:35px;font-size:13px">&nbsp;&nbsp;&nbsp;{{$value->Instrument_no}}</td>
                                            </tr>
                                        @endif
                                        @if ($calibrationFieldRules['Calib_procedure']['enable_field'])
                                            <tr class="field-row" data-index="{{ array_shift($customFieldIndex) }}">
                                                <td style="width:230px;height:35px;font-size:13px;background:#ededed;">&nbsp;&nbsp;&nbsp;{{ $calibrationFieldRules ? $calibrationFieldRules['Calib_procedure']['field_display_label'] : 'Calibration procedure'}}</td>
                                                <td style="width:270px;height:35px;font-size:13px">&nbsp;&nbsp;&nbsp;
                                                    <!-- @if($value->Calib_procedure==NULL)
                                                    &nbsp;&nbsp;&nbsp;
                                                    @else
                                                    {{$value->Calib_procedure}}
                                                    @endif -->
                                                    {{$value->Calib_procedure==''?'--':$value->Calib_procedure}}
                                                </td>
                                            </tr>
                                        @endif


                                        <tr class="field-row" data-index="{{ array_shift($customFieldIndex)}}">
                                            <td style="width:160px;height:35px;font-size:13px;background:#ededed;">&nbsp;&nbsp;&nbsp;Acceptable Error</td>
                                            <td   style="width:150px;height:35px;font-size:13px">&nbsp;&nbsp;&nbsp;{{$value->Acc_error}}</td>
                                        </tr>
                                        <tr class="field-row" data-index="{{ array_shift($customFieldIndex) }}">
                                            <td   style="width:160px;height:35px;font-size:13px;background:#ededed;">&nbsp;&nbsp;&nbsp;Master instrument</td>
                                            <td   style="width:150px;height:35px;font-size:13px">&nbsp;&nbsp;&nbsp;{{$value->M_instru}}</td>
                                        </tr>
                                        <tr class="field-row" data-index="{{ array_shift($customFieldIndex) }}">
                                            <td   style="width:160px;height:35px;font-size:13px;background:#ededed;">&nbsp;&nbsp;&nbsp;Master Instrument Certificate Number</td>
                                            <td   style="width:150px;height:35px;font-size:13px">&nbsp;&nbsp;&nbsp;{{$value->Certificate_no}}</td>
                                        </tr>

                                        <tr class="field-row" data-index="{{ array_shift($customFieldIndex) }}">
                                            <td  style="width:160px;height:35px;font-size:13px;background:#ededed;">&nbsp;&nbsp;&nbsp;Report Number</td>
                                            <td  style="width:150px;height:35px;font-size:13px">&nbsp;&nbsp;&nbsp;{{$value-> Report_no}}</td>
                                        </tr>
                                        <tr class="field-row" data-index="{{ array_shift($customFieldIndex) }}">
                                            <td  style="width:160px;height:35px;font-size:13px;background:#ededed;">&nbsp;&nbsp;&nbsp;Calibration Status</td>
                                            <td  style="width:150px;height:35px;font-size:13px">&nbsp;&nbsp;&nbsp;{{$value-> Status}}</td>
                                        </tr>
                                        <tr class="field-row" data-index="{{ array_shift($customFieldIndex) }}">
                                            <td  style="width:160px;height:35px;font-size:13px;background:#ededed;">&nbsp;&nbsp;&nbsp;Adjustment Made</td>
                                            <td  style="width:150px;height:35px;font-size:13px">&nbsp;&nbsp;&nbsp;{{$value-> Adj_made}}</td>
                                        </tr>

                                        <tr class="field-row" data-index="{{ array_shift($customFieldIndex) }}">
                                            <td style="width:160px;height:35px;font-size:13px;background:#ededed;">&nbsp;&nbsp;&nbsp;Date of calibration</td>
                                            <td  style="width:150px;height:35px;font-size:13px">&nbsp;&nbsp;&nbsp;
                                                @if($value->Dateofcal=="1970-01-01")
                                                    &nbsp;&nbsp;&nbsp;
                                                @else
                                                    {{$datecalval}}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr class="field-row" data-index="{{ array_shift($customFieldIndex) }}">
                                            <td  style="width:160px;height:35px;font-size:13px;background:#ededed;">&nbsp;&nbsp;&nbsp;Calibrated By</td>
                                            <td  style="width:150px;height:35px;font-size:13px">&nbsp;&nbsp;&nbsp;{{$value-> Calibrated_by}}</td>
                                        </tr>
                                        <tr class="field-row" data-index="{{ array_shift($customFieldIndex) }}">
                                            <td  style="width:160px;height:35px;font-size:13px;background:#ededed;">&nbsp;&nbsp;&nbsp;Verified By</td>
                                            <td  style="width:150px;height:35px;font-size:13px">&nbsp;&nbsp;&nbsp;{{$value-> Verified_by}}</td>
                                        </tr>
                                        @foreach($CustomFields as $CustomField)
                                            <tr class="field-row" data-index="{{ array_shift($customFieldIndex) }}">
                                                @php
                                                    $fieldname = Str::slug($CustomField->field_name, '_');
                                                    $rawValue = $data4->{$fieldname} ?? '';
                                                    $displayValue = null;
                                                    $isFile = $CustomField->field_type === 'file';
                                                    $isFileMultiple = $CustomField->field_type === 'file_multiple';

                                                    $fileArray = [];
                                                    if($isFile || $isFileMultiple){
                                                        $decoded = json_decode($rawValue, true);
                                                        if (is_array($decoded)) {
                                                            $fileArray = $decoded;
                                                        }
                                                    }elseif (is_string($rawValue) && is_array(json_decode($rawValue, true))) {
                                                        $decoded = json_decode($rawValue, true);
                                                        if(in_array($CustomField->field_type,['multiselect','checkbox'])){
                                                            $decoded = App\DropdownOption::whereIn('id',$decoded)->pluck('option')->toArray();
                                                        }
                                                        $displayValue = implode(', ', $decoded);
                                                    } else {

                                                        if(in_array($CustomField->field_type,['radio','select'])){
                                                            $rawValue = App\DropdownOption::where('id',$rawValue)->value('option');
                                                        }
                                                        $displayValue = $rawValue;
                                                    }
                                                @endphp
                                                <td style="width:160px;height:35px;font-size:13px;background:#ededed;">
                                                    &nbsp;&nbsp;&nbsp;{{ $CustomField->field_name }}
                                                </td>
                                                <td style="width:150px;height:35px;font-size:13px;">
                                                    &nbsp;&nbsp;&nbsp;
                                                    @if(count($fileArray))
                                                        <!-- View button opens modal -->
                                                        <button type="button" class="btn btn-secondary btn-sm" style="font-size:12px;" data-toggle="modal" data-target="#fileModal">
                                                            View Attachments
                                                        </button>

                                                        <!-- Modal for files -->
                                                        <div class="modal fade" id="fileModal" tabindex="-1" role="dialog" aria-labelledby="fileModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">{{ $CustomField->field_name }} - Attachments</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <table class="table table-bordered table-hover">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th style="font-size:12px;">#</th>
                                                                                    <th style="font-size:12px;">Filename</th>
                                                                                    <th style="font-size:12px;">Action</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach($fileArray as $fileIndex => $filename)
                                                                                    <tr>
                                                                                        <td style="font-size:12px;">{{ $fileIndex + 1 }}</td>
                                                                                        <td style="font-size:12px;">{{ $filename }}</td>
                                                                                        <td style="font-size:12px;">
                                                                                            <a href="{{ asset('storage/calibration/' . $filename) }}" style="font-size:12px;" class="btn btn-success btn-sm" download>
                                                                                                Download
                                                                                            </a>
                                                                                        </td>
                                                                                    </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        {{$displayValue??'--'}}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        @break
                                    @endforeach
                                    <tr style="border-top:3px solid #28a745;" id="secondTableRow">
                                        <td  style="width:100px;height:35px;font-size:13px;background:#ededed;"><center>Sl No</center></td>
                                        <td  style="width:130px;height:35px;font-size:13px;background:#ededed;"><center>Master Reading</center></td>
                                        <td  style="width:120px;height:35px;font-size:13px;background:#ededed;"><center>Instrument Reading<center></td>
                                        <td  style="width:135px;height:35px;font-size:13px;background:#ededed;"><center>Error</center></td>
                                        <td colspan="2" style="width:135px;height:35px;font-size:13px;background:#ededed;"><center>Remarks</center></td>
                                    </tr>
                                    <?php $no=1; ?>
                                    @foreach($data2 as $value)
                                        <tr>
                                            <td class="text-center" style="font-size:14px;width: 50px;height:30px">&nbsp;&nbsp;&nbsp;{{$no++}}</td>
                                            <td class="text-center" style="font-size:14px;width: 50px;">&nbsp;&nbsp;&nbsp;{{$value-> M_reading}}</td>
                                            <td class="text-center" style="font-size:14px;width: 50px;">&nbsp;&nbsp;&nbsp;{{$value->Instrument_reading}}</td>
                                            <td class="text-center "style="font-size:14px;width: 50px;">&nbsp;&nbsp;&nbsp;{{$value-> Error}} </td>
                                            <td colspan="2" class="text-center "style="font-size:14px;width: 50px;">&nbsp;&nbsp;&nbsp;
                                                @if($value->Remarks=="No Remarks")
                                                &nbsp;&nbsp;&nbsp;
                                                @else
                                                {{$value->Remarks}}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                        <tr>
                                <td colspan="6" style="font-size:20px;"><center>No Calibration Logs Available</center></Td>
                        </tr>
                        @endif
                    </div>
                    <?php
                        $idd= request()->get('val') ;
                        $repno=request()->get('repno') ;
                    ?>
                    <div class="col-sm-12 text-center mt-4">
                        <a href="{{url('report/print-pdf1?val='.$idd.'&repno='.$repno)}}" style="font-size:13px" class="btn btn-primary">Save report</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="block block-rounded block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">OPTIONS</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content block-content-full">
                    <div class="row gutters-tiny">
                    <div class="col-6">
                            <!-- Products -->
                            <a class="block block-rounded block-link-shadow bg-body" href="/user/calibrationmgmt">
                                <div class="block-content text-center" style="height:130px">
                                    <i class="far fa-list-alt fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        <br> Register
                                    </p>
                                </div>
                            </a>
                            <!-- END Products -->
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {

        var savedOrder = @json($defaultFieldIndex);
        var $container = $('.sortable-field');
        var $allFields = $container.children('.field-row');
        // Map of index to element
        var fieldMap = {};
        $allFields.each(function () {
            var index = $(this).data('index');
            if (index !== undefined) {
                fieldMap[index] = $(this);
            }
        });
        // Remove all fields from container
        $allFields.detach();
        // Append elements in saved order
        if (Array.isArray(savedOrder)) {
            savedOrder.forEach(function (index) {
                if (fieldMap[index]) {
                    $container.append(fieldMap[index]);
                    delete fieldMap[index];
                }
            });
        }
        // Append remaining fields (not in savedOrder)
        $.each(fieldMap, function (index, $el) {
            $container.append($el);
        });

        const table = document.querySelector("table");
        const secondRow = document.getElementById("secondTableRow");
        if (!table || !secondRow) return;

        // Step 1: Get all .field-row elements
        const fieldRows = Array.from(table.querySelectorAll("tr.field-row"));
        if (!fieldRows.length) return;

        fieldRows.forEach(row => row.remove());
        for (let i = 0; i < fieldRows.length; i += 3) {
            const row1 = fieldRows[i];
            const row2 = fieldRows[i + 1];
            const row3 = fieldRows[i + 2];

            const newRow = document.createElement("tr");

            // Add row1 TDs
            if (row1) {
                Array.from(row1.children).forEach(td => newRow.appendChild(td.cloneNode(true)));
            } else {
                newRow.appendChild(document.createElement("td"));
                newRow.appendChild(document.createElement("td"));
            }

            // Add row2 TDs
            if (row2) {
                Array.from(row2.children).forEach(td => newRow.appendChild(td.cloneNode(true)));
            } else {
                newRow.appendChild(document.createElement("td"));
                newRow.appendChild(document.createElement("td"));
            }

            // Add row3 TDs
            if (row3) {
                Array.from(row3.children).forEach(td => newRow.appendChild(td.cloneNode(true)));
            } else {
                newRow.appendChild(document.createElement("td"));
                newRow.appendChild(document.createElement("td"));
            }

            // Insert the merged row before secondTableRow
            secondRow.parentNode.insertBefore(newRow, secondRow);
        }
    });
</script>
<!-- END Apps Modal -->
@endsection
