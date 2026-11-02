@php
    $duedate= $value->Duedate;
    $ddate = date('Y-m-d',strtotime($duedate));
    $dudat = explode('-',$ddate);
    $dudateval = $duedate == NULL ? NULL : $dudat[2]."-".$dudat[1]."-".$dudat[0];
    $neartoexpiry=date('Y-m-d',strtotime($duedate. ' - 30 days'));
    $today_dt=date("Y-m-d");
    $calibrationFieldRules = json_decode($value->calibration_field_rules, true);
@endphp
<div id="viewModal" class="modal fadebd-example-modal-lg scrollable" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Detailed View</h2>
            </div>
            <table class="table table-striped table-bordered">
                <thead></thead>
                <tbody class="sortable-field">
                    @if ($calibrationFieldRules['Instrument_no']['enable_field'])
                        <tr data-index="{{array_shift($externalFieldIndex)}}">
                            <th style="width:100px">{{ $calibrationFieldRules ? $calibrationFieldRules['Instrument_no']['field_display_label'] : 'Instrument Number' }}</th>
                            <td>{{ $value->Instrument_no }}</td>
                        </tr>
                    @endif
                    @if ($calibrationFieldRules['Instrument_desc']['enable_field'])
                        <tr data-index="{{array_shift($externalFieldIndex)}}">
                            <th style="width:100px">{{ $calibrationFieldRules ? $calibrationFieldRules['Instrument_desc']['field_display_label'] : 'Instrument Description'}}</th>
                            <td><?php echo nl2br($value->Instrument_desc )?></td>
                        </tr>
                    @endif
                    @if ($calibrationFieldRules['Instru_Manufacturer']['enable_field'])
                        <tr data-index="{{array_shift($externalFieldIndex)}}">
                            <th style="width:100px">{{ $calibrationFieldRules ? $calibrationFieldRules['Instru_Manufacturer']['field_display_label'] : 'Instrument Manufacturer'}}</th>
                            <td>{{ $value->Instru_Manufacturer=='no value'?'--': $value->Instru_Manufacturer}}</td>
                        </tr>
                    @endif

                    <tr data-index="{{array_shift($externalFieldIndex)}}">
                        <th style="width:100px">Department</th>
                        <td>
                            @if(is_numeric($value->Department))
                                {{$value->departmentdetails? $value->departmentdetails->departmentname : ''}}
                            @else
                                {{$value->Department}}
                            @endif
                        </td>
                    </tr>
                    @if ($calibrationFieldRules['Holder']['enable_field'])
                        <tr data-index="{{array_shift($externalFieldIndex)}}">
                            <th style="width:100px">{{ $calibrationFieldRules ? $calibrationFieldRules['Holder']['field_display_label'] : 'Instrument Holder/Location'}}</th>
                            <td>{{ $value->Holder}}</td>
                        </tr>
                    @endif
                    @if ($calibrationFieldRules['Capacity']['enable_field'])
                        <tr data-index="{{array_shift($externalFieldIndex)}}">
                            <th style="width:100px">{{ $calibrationFieldRules ? $calibrationFieldRules['Capacity']['field_display_label'] : 'Instrument Capacity/Range'}}</th>
                            <td>{{ $value->Capacity}}</td>
                        </tr>
                    @endif
                    <tr data-index="{{array_shift($externalFieldIndex)}}">
                        <th style="width:100px">Least count</th>
                        <td>{{ $value->least_count?$value->least_count:'--'}}</td>
                    </tr>
                    @if ($calibrationFieldRules['Calib_procedure']['enable_field'])
                        <tr data-index="{{array_shift($externalFieldIndex)}}">
                            <th style="width:100px">{{ $calibrationFieldRules ? $calibrationFieldRules['Calib_procedure']['field_display_label'] : 'Calibration Procedure'}}</th>
                            <td><?php echo nl2br($value->Calib_procedure)?></td>
                        </tr>
                    @endif
                    @if(($value->Type=='Internal') || ($value->Type=='INTERNAL') || ($value->Type=='internal'))
                        <tr data-index="{{array_shift($externalFieldIndex)}}">
                            <th style="width:100px">{{ $calibrationFieldRules ? $calibrationFieldRules['Agency']['field_display_label'] : 'Calibration Agency'}}</th>
                            <td>--</td>
                        </tr>

                    @elseif(($value->Type=='External') || ($value->Type=='EXTERNAL') || ($value->Type=='external'))
                        <tr data-index="{{array_shift($externalFieldIndex)}}">
                            <th style="width:100px">{{ $calibrationFieldRules ? $calibrationFieldRules['Agency']['field_display_label'] : 'Calibration Agency'}}</th>
                            <td>{{$value->Agency}}</td>
                        </tr>
                        <tr data-index="{{array_shift($externalFieldIndex)}}">
                            <th>Attachments</th>
                            <td>
                                @if($value->extfile!=NULL)
                                    <div class="col-sm-8">
                                        <button type="button" class="btn btn-secondary btn-small" style="font-size:13px;width: 150px;" data-toggle="modal" data-id="{{ $value->id }}" data-target="#viewModal4{{$value->id}}"><strong>View Attachments</strong></button>
                                        <div id="viewModal4{{$value->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h3 class="modal-title">Attachments</h3>
                                                    </div>
                                                    <table class="table table-responsive table-hover table-bordered table-striped" width="70%">
                                                        <div class="row justify-content-center">
                                                            <thead>
                                                            <tr class="table-secondary">

                                                                <th>#</th>
                                                                <th>FileName</th>
                                                                <th></th>
                                                            </tr>
                                                            </thead>

                                                            <tbody>
                                                            <?php
                                                            $file_values = DB::table('calibration_externalfiles')->where('instrument_id',$value->id)->get();
                                                            $sl =1;
                                                            ?>
                                                                    <!-- <tr>
                                                            <td style="font-size:15px;width: 100%;">{{ $value->extfile}}</td>
                                                            <td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success" href="{{asset("storage/calibration/".$value->extfile)}}" download="{{$value->extfile}}">Download</a></td>
                                                        </tr> -->

                                                            @foreach($file_values as $file_value)
                                                                <tr>
                                                                    <td style="font-size:15px;width:2%;">
                                                                        {{$sl++}}
                                                                    </td>
                                                                    <td style="font-size:15px;width:70%;">
                                                                        {{$file_value->Filename}}
                                                                    </td>

                                                                    <td>
                                                                        <a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success" href="{{asset("storage/calibration/".$file_value->Filename)}}" download="{{$file_value->Filename}}">Download </a>
                                                                    </td>

                                                                </tr>
                                                            @endforeach
                                                            </tbody>

                                                        </div>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span style="color:red;font-size:13px;">No Attachments</span>
                                @endif
                            </td>
                        </tr>
                    @endif
                    <tr data-index="{{array_shift($externalFieldIndex)}}">
                        <th style="width:100px">Frequency</th>
                        <td>{{$value->Frequency}}</td>
                    </tr>
                    <tr data-index="{{array_shift($externalFieldIndex)}}">
                        <th style="width:100px">Standard Edition</th>
                        <td>{{$value->standard_edition ? $value->standard_edition:'--'}}</td>
                    </tr>
                    <tr data-index="{{array_shift($externalFieldIndex)}}">
                        <th style="width:100px">Acceptance Criteria</th>
                        <td>{{$value->acceptance_criteria ? $value->acceptance_criteria :'--'}}</td>
                    </tr>
                    <tr data-index="{{array_shift($externalFieldIndex)}}">
                        <th style="width:100px">Date of Calibration</th>
                        <td>
                            {{$value->dateofcal==""?'--':date('d-m-Y',strtotime($value->dateofcal))}}
                        </td>
                    </tr>
                    <tr data-index="{{array_shift($externalFieldIndex)}}">
                        <th style="width:100px">Due Date</th>
                        <td>
                            {{$value->Duedate==""?'--':date('d-m-Y',strtotime($value->Duedate))}}
                        </td>
                    </tr>
                    <tr data-index="{{array_shift($externalFieldIndex)}}">
                        <th style="width:100px">Certificate Number</th>
                        <td>
                            @php
                                $reportno = App\CalibrationExternal::where('Instrument_no',$value->Instrument_no)
                                                                    ->where('Status','Calibrated')
                                                                    ->value('Report_no');
                            @endphp
                            {{$reportno?$reportno:'-'}}
                        </td>
                    </tr>
                    <tr data-index="{{array_shift($externalFieldIndex)}}">
                        <th style="width:100px">Status</th>
                        @if($value->Status == \App\Calibration_instrument::UNDER_CALIBRATION_STATUS)
                            <td><span class="badge badge-primary">Under Calibration</span></td>
                        @elseif($value->Duedate==NULL)
                            <td><span class="badge badge-secondary">Not Calibrated</span></td>
                        @elseif(($value->Duedate<$today_dt) || ($value->Status=="Expired"))
                            <td><span class="badge badge-danger">Expired</span></td>
                        @elseif(($neartoexpiry<=$today_dt) || ($value->Status=="Near to expiry"))
                            <td><span class="badge badge-warning">Near to expiry</span></td>
                        @elseif(($value->Duedate>$today_dt || $value->Status=="Calibrated"))
                            <td><span class="badge badge-success">Calibrated</span></td>
                        @endif
                    </tr>
                    @foreach($CustomFields as $index => $customField)
                        @php
                            $fieldname = Str::slug($customField->field_name, '_');
                            $rawValue = $value->calibrationExternalReportNo->{$fieldname} ?? '';
                            $displayValue = null;
                            $isFile = $customField->field_type === 'file';
                            $isFileMultiple = $customField->field_type === 'file_multiple';

                            $fileArray = [];
                            if($isFile || $isFileMultiple){
                                $decoded = json_decode($rawValue, true);
                                if (is_array($decoded)) {
                                    $fileArray = $decoded;
                                }
                            }elseif (is_string($rawValue) && is_array(json_decode($rawValue, true))) {
                                $decoded = json_decode($rawValue, true);
                                if(in_array($customField->field_type,['multiselect','checkbox'])){
                                    $decoded = App\DropdownOption::whereIn('id',$decoded)->pluck('option')->toArray();
                                }
                                $displayValue = implode(', ', $decoded);
                            } else {
                                if(in_array($customField->field_type,['radio','select'])){
                                    $rawValue = App\DropdownOption::where('id',$rawValue)->value('option');
                                }
                                $displayValue = $rawValue;
                            }
                        @endphp
                        <tr data-index="{{array_shift($externalFieldIndex)}}">
                            <th style="width: 200px;">{{ $customField->field_name }}</th>
                            <td>
                                @if (count($fileArray))
                                    <!-- View button opens modal -->
                                    <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#fileModal{{ $index }}">
                                        View Attachments
                                    </button>

                                    <!-- Modal for files -->
                                    <div class="modal fade" id="fileModal{{ $index }}" tabindex="-1" role="dialog" aria-labelledby="fileModalLabel{{ $index }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">{{ $customField->field_name }} - Attachments</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <table class="table table-bordered table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Filename</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($fileArray as $fileIndex => $filename)
                                                                <tr>
                                                                    <td>{{ $fileIndex + 1 }}</td>
                                                                    <td>{{ Str::after($filename,'_') }}</td>
                                                                    <td>
                                                                        <a href="{{ asset('storage/calibration/' . $filename) }}" class="btn btn-success btn-sm" download>
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
                </tbody>
            </table>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        var savedOrder = @json($defaultFieldIndex);
        var $container = $('.sortable-field');
        var $allFields = $container.children('tr');
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
    });
</script>
