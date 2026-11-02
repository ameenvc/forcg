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
                        <a class="link-fx" href="/user/calibrationmgmt?tab=ext&rowId={{request()->get('val')}}">Back</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<!-- END Hero -->
<div class="content">
    <div class="block block-rounded">
        <div class="block-content block-content-full" align="center">
            <div class="row">
                <div class="col-sm-12">
                    @if($status > 0)
                        <div class="table-responsive">
                            @php
                                $calibrationFieldRules = json_decode($data3->calibration_field_rules, true);
                            @endphp
                            <table width="80%">
                                <tbody class="sortable-field">
                                    <tr id="firstTableRow">
                                        <td colspan="4" class="bg-success text-white" style="font-size:20px;height:35px;font-family:Arial, Helvetica, sans-serif;"><center><b>Calibration Log</b></center></td>
                                    </tr>
                                    @if ($calibrationFieldRules['Instrument_desc']['enable_field'])
                                        <tr class="field-row" data-index="{{ array_shift($customFieldIndex) }}">
                                            <td style="width:100px;font-size:13px;background:#ededed;">&nbsp;&nbsp;&nbsp;{{ $calibrationFieldRules ? $calibrationFieldRules['Instrument_desc']['field_display_label'] : 'Instrument description'}}</td>
                                            <td style="width:205px;font-size:13px">&nbsp;&nbsp;&nbsp;{{$data3->Instrument_desc}}</td>
                                        </tr>
                                    @endif

                                    @if ($calibrationFieldRules['Instru_Manufacturer']['enable_field'])
                                        <tr class="field-row" data-index="{{ array_shift($customFieldIndex) }}">
                                            <td style="width:160px;height:35px;font-size:13px;background:#ededed;">&nbsp;&nbsp;&nbsp;{{ $calibrationFieldRules ? $calibrationFieldRules['Instru_Manufacturer']['field_display_label'] : 'Instrument manufacturer'}}</td>
                                            <td style="width:185px;height:35px;font-size:13px">&nbsp;&nbsp;&nbsp;
                                                @if($data3->Instru_Manufacturer=="no value")
                                                    &nbsp;&nbsp;&nbsp;
                                                @else
                                                    {{$data3->Instru_Manufacturer}}
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                    @if ($calibrationFieldRules['Instrument_no']['enable_field'])
                                        <tr class="field-row" data-index="{{ array_shift($customFieldIndex) }}">
                                            <td style="width:160px;height:35px;font-size:13px;background:#ededed;">&nbsp;&nbsp;&nbsp;{{ $calibrationFieldRules ? $calibrationFieldRules['Instrument_no']['field_display_label'] : 'Instrument number'}}</td>
                                            <td   style="width:150px;height:35px;font-size:13px">&nbsp;&nbsp;&nbsp;{{$data3->Instrument_no}}</td>
                                        </tr>
                                    @endif
                                    <tr class="field-row" data-index="{{ array_shift($customFieldIndex) }}">
                                        <td  style="width:160px;height:35px;font-size:13px;background:#ededed;">&nbsp;&nbsp;&nbsp;Calibration frequency</td>
                                        <td  style="width:150px;height:35px;font-size:13px">&nbsp;&nbsp;&nbsp;{{$data3->Frequency}}</td>
                                    </tr>
                                    @if ($calibrationFieldRules['Capacity']['enable_field'])
                                        <tr class="field-row" data-index="{{ array_shift($customFieldIndex) }}">
                                            <td  style="width:160px;height:35px;font-size:13px;background:#ededed;">&nbsp;&nbsp;&nbsp;{{ $calibrationFieldRules ? $calibrationFieldRules['Capacity']['field_display_label'] : 'Instrument capacity/range'}}</td>
                                            <td  style="width:150px;height:35px;font-size:13px">&nbsp;&nbsp;&nbsp;{{$data3->Capacity}}</td>
                                        </tr>
                                    @endif
                                    @if ($calibrationFieldRules['Holder']['enable_field'])
                                        <tr class="field-row" data-index="{{ array_shift($customFieldIndex) }}">
                                            <td style="width:160px;height:35px;font-size:13px;background:#ededed;">&nbsp;&nbsp;&nbsp;{{ $calibrationFieldRules ? $calibrationFieldRules['Holder']['field_display_label'] : 'Instrument holder/location'}}</td>
                                            <td  style="width:150px;height:35px;font-size:13px">&nbsp;&nbsp;&nbsp;{{$data3->Holder}}</td>
                                        </tr>
                                    @endif

                                    <tr class="field-row" data-index="{{ array_shift($customFieldIndex) }}">
                                        <td  style="width:160px;height:35px;font-size:13px;background:#ededed;">&nbsp;&nbsp;&nbsp;Date of First Use</td>
                                        <td  style="width:150px;height:35px;font-size:13px">&nbsp;&nbsp;&nbsp;{{$data3->date_of_first_use?date('d-m-Y',strtotime($data3->date_of_first_use)):''}}</td>
                                    </tr>
                                    @foreach($CustomFields as $CustomField)
                                        <tr class="field-row" data-index="{{ array_shift($customFieldIndex) }}">
                                            @php
                                                $fieldname = Str::slug($CustomField->field_name, '_');
                                                $rawValue = $data4->{$fieldname} ?? '';

                                                $isFile = $CustomField->field_type === 'file';
                                                $isFileMultiple = $CustomField->field_type === 'file_multiple';
                                                $displayValue = null;
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
                                    <tr style="border-top:3px solid #28a745;" id="secondTableRow">
                                        <td  style="width:100px;height:35px;font-size:13px;background:#ededed;"><center><b>Report No</b></center></td>
                                        <td  style="width:130px;height:35px;font-size:13px;background:#ededed;"><center><b>Date of Calibration</b></center></td>
                                        <td  style="width:120px;height:35px;font-size:13px;background:#ededed;"><center><b>Calibration Status</b><center></td>
                                        <td  style="width:135px;height:35px;font-size:13px;background:#ededed;"><center><b>Calibration Due Date</b></center></td>
                                    </tr>
                                    @foreach($data2 as $value)
                                        @php
                                            $caldt = $value->Dateofcal;
                                            $cdate = date('Y-m-d',strtotime($caldt));
                                            $caldat = explode('-',$cdate);
                                            $datecalval = $caldt == null ? null : $caldat[2]."-".$caldat[1]."-".$caldat[0];

                                            $ddt = $value->Duedate;
                                            $dudate = date('Y-m-d',strtotime($ddt));
                                            $dudat = explode('-',$dudate);
                                            $duedateval = $ddt == null ? null : $dudat[2]."-".$dudat[1]."-".$dudat[0];
                                            $neartoexpiry=date('Y-m-d',strtotime($ddt. ' - 30 days'));
                                            $today_dt=date('Y-m-d');
                                        @endphp
                                        @if($value->Status!=NULL)
                                            <tr>
                                                <td class="text-center" style="font-size:14px;width: 50px;height:30px">&nbsp;&nbsp;&nbsp;{{$value-> Report_no}}</td>
                                                <td class="text-center" style="font-size:14px;width: 50px;">&nbsp;&nbsp;&nbsp;
                                                    @if($value->Dateofcal=="1970-01-01")
                                                        &nbsp;&nbsp;&nbsp;
                                                    @else
                                                        {{$datecalval}}
                                                    @endif
                                                </td>
                                                <td class="text-center" style="font-size:14px;width: 50px;">&nbsp;&nbsp;&nbsp;
                                                    @if($value->Duedate<$today_dt || $value->Status=="Expired")
                                                        <span class="badge badge-danger"> Expired </span>
                                                    @elseif($neartoexpiry<=$today_dt || $value->Status=="Near to expiry")
                                                        <span class="badge badge-warning"> Near to expiry </span>
                                                    @elseif($value->Status=="Calibrated")
                                                        <span class="badge badge-success">Calibrated</span>
                                                    @endif
                                                </td>
                                                <td class="text-center "style="font-size:14px;width: 50px;">&nbsp;&nbsp;&nbsp;
                                                    @if($value->Duedate=="1970-01-01")
                                                        &nbsp;&nbsp;&nbsp;
                                                    @else
                                                    {{$duedateval}}
                                                    @endif

                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-sm-12 text-center text-danger" style="font-size:15px;">
                                No Calibration Logs available
                            </div>
                        </div>
                   @endif

                </div>
            </div>
            <?php $idd= request()->get('val') ;?>
            @if($status > 0)
                <div class="col-sm-12 text-center mt-4">
                    <a href="{{url('report/print-pdfext?val='.$idd)}}" style="font-size:13px" class="btn btn-primary">Save report</a>
                </div>
            @endif
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
<!-- END Apps Modal -->

@endsection

<script>
    function click1(x,l1,a2,a3,a4,a5,l2,l3,l4,l5){
        document.getElementById('tabselected').value=x.id;
        x.style.backgroundColor="#2F5597";
        x.style.color="white";
        document.getElementById(a2).style.backgroundColor="white";
        document.getElementById(a2).style.color="black";
        document.getElementById(a3).style.backgroundColor="white";
        document.getElementById(a3).style.color="black";
        document.getElementById(a4).style.backgroundColor="white";
        document.getElementById(a4).style.color="black";
        document.getElementById(a5).style.backgroundColor="white";
        document.getElementById(a5).style.color="black";
        document.getElementById(l1).className="active";
        document.getElementById(l2).className="";
        document.getElementById(l3).className="";
        document.getElementById(l4).className="";
        document.getElementById(l5).className="";
    }
</script>
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
        // Step 1: Get all .field-row elements (we assume all are consecutive)
        const fieldRows = Array.from(table.querySelectorAll("tr.field-row"));
        if (!fieldRows.length) return;
        // Step 3: Remove them from table first
        fieldRows.forEach(row => row.remove());

        // Step 4: Merge 2 field-rows into one <tr> with 4 <td>s
        for (let i = 0; i < fieldRows.length; i += 2) {
            const row1 = fieldRows[i];
            const row2 = fieldRows[i + 1];
            const newRow = document.createElement("tr");

            // Clone and append TDs from row1
            Array.from(row1.children).forEach(td => {
                newRow.appendChild(td.cloneNode(true));
            });

            // Clone and append TDs from row2 if exists
            if (row2) {
                Array.from(row2.children).forEach(td => {
                    newRow.appendChild(td.cloneNode(true));
                });
            } else {
                // if row2 doesn't exist, pad with 2 empty TDs
                newRow.appendChild(document.createElement("td"));
                newRow.appendChild(document.createElement("td"));
            }
            secondRow.parentNode.insertBefore(newRow, secondRow);

        }
    });
</script>
