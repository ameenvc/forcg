@php
    $thInlineStyles = 'font-size:11px;text-transform:capitalize';
    $thSpanInlineStyles = 'font-size:10px;text-transform:capitalize';
@endphp
<style>
    .text-center{ text-align: center }
    .custom-badge{
        display:inline-block; font-size:9px !important;
        color:#555 !important;
        background:#f2f2f2 !important;
        padding:2px 6px !important;
        border-radius:4px !important;
        margin-top:2px !important;
    }
    .badge-class {
        display: inline-block;
        padding: 7px 5px 5px 7px;
        font-weight: 600;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.25rem;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
</style>
@if(isset($commanData[$tab]) && count($commanData[$tab])>0)
    <table class="table2" border='1' style='border-collapse:collapse;' bordercolor='black'  align='center' 
           id="headerTableOpen">
        <thead>
        <tr>
            <th class="text-center" style="font-size:11px;width: 20px;text-transform:capitalize"><span style="{{ $thSpanInlineStyles }}"><b>Slno</b></span></th>
            <th class="text-center" style="{{$thInlineStyles}}"><span style="{{ $thSpanInlineStyles }}"><b>Id</b></span></th>
            <th class="text-center" style="{{ $thInlineStyles }}"><span style="{{ $thSpanInlineStyles }}"><b>Title</b></span></th>
            @foreach($commanData['tableDetails'][$tab]['columns_active'] as $value)
                @php
                    $className = '';
                    if($value->category_name && ($value->category_name=='product ncr' || $value->category_name=='process ncr' || $value->category_name=='vendor ncr')){
                        $className = 'productCategoryHeader';
                    }
                @endphp
                <th class="text-center" style="{{ $thInlineStyles }}">
                    <span class="text-center" style="{{ $thSpanInlineStyles }}">
                        <b>{{ $className ? ucfirst($value->display_label) : ucfirst($value->column_name)}}</b>
                    </span>
                </th>
            @endforeach

            <th class="text-center" style="{{ $thInlineStyles }}"><span style="{{ $thSpanInlineStyles }}"><b>Process Stage</b></span></th>
            <th class="text-center" style="{{ $thInlineStyles }}"><span style="{{ $thSpanInlineStyles }}"><b>Action Status</b></span></th>
        </tr>
        </thead>
        <tbody>
        @foreach($commanData[$tab] as $issue)
            @php
                $rr = $issue->id;
                $date = $issue->duedate;
                $today = Date("Y-m-d");
                $color = null;
                $division = $issue->division ? $issue->division->name : '';
                $sub_division = $issue->area ? $issue->area->name : '';
                $responsible_names = isset($commanData['userForResponsibility'])
                    ? $commanData['userForResponsibility']
                        ->whereIn('email', explode(',', $issue->correction_responsibility))
                        ->pluck('name')
                        ->toArray()
                    : [];
                $responsible_names = implode(',', $responsible_names);
                $cat = $issue->cat ? $issue->cat->categoryname : '';
                $dept = $issue->correctionDept ? $issue->correctionDept->departmentname : '';
                $team_leader = $issue->teamLead;
                $team_leader_email = $team_leader ? $team_leader->email : null;
                $investigation_team = [];
                foreach($issue->investigators as $investigator) {
                    $investigation_team[] = $investigator->email;
                }
                $investigation_array = ['Actions Completed', 'rejected'];
                $productFieldValues = $issue->ncrFieldValueRelation->keyBy('field_name');
                $processFieldValues = $issue->processNcrFieldValueRelation->keyBy('field_name');
                $vendorFieldValues = $issue->vendorNcrFieldValueRelation->keyBy('field_name');
            @endphp
            <tr>
                <!-- Slno -->
                <td class="text-center" style="font-size:11px;width: 10px;"><span
                            style="font-size:10px;width: 10px;">{{ $loop->iteration}}</span></td>
                <!-- Id -->
                @if($issue->category=="Internal Audit")
                    <td class="text-center" style="font-size:11px;color:#0033cc"><span
                                style="font-size:10px;color:#0033cc">{{$issue->issueid}}</span></td>
                @elseif($issue->category=="External Audit")
                    <td class="text-center" style="font-size:11px;color:orange"><span
                                style="font-size:10px;color:orange">{{$issue->issueid}}</span></td>
                @elseif($issue->category=="Management Review")
                    <td class="text-center" style="font-size:11px;color:#33cc33"><span
                                style="font-size:10px;color:#33cc33">{{$issue->issueid}}</span></td>
                @elseif($issue->category=="Corrective Action")
                    <td class="text-center" style="font-size:11px;color:#ff0066"><span
                                style="font-size:10px;color:#ff0066">{{$issue->issueid}}</span></td>
                @else
                    <td class="text-center" style="font-size:12px;color:black"><span
                                style="font-size:10px;color:black">{{$issue->issueid}}</span></td>
                @endif
                <!-- Title -->
                <td class="text-center" style="font-size:11px;"><span
                            style="font-size:10px;">{{$issue->title}}</td>
                @foreach($commanData['tableDetails'][$tab]['columns_active'] as $value)
                    @php
                        if($value->category_name && $value->category_name=='product ncr'){
                            $fieldData = $productFieldValues[$value->field_name] ?? null;
                            $fieldValue = $fieldData->value ?? null;
                            $fieldDisplayLabel = $fieldData->display_label ?? null;
                        }else if($value->category_name && $value->category_name=='process ncr'){
                            $fieldData = $processFieldValues[$value->field_name] ?? null;
                            $fieldValue = $fieldData->value ?? null;
                            $fieldDisplayLabel = $fieldData->display_label ?? null;
                        }else if($value->category_name && $value->category_name=='vendor ncr'){
                            $fieldData = $vendorFieldValues[$value->field_name] ?? null;
                            $fieldValue = $fieldData->value ?? null;
                            $fieldDisplayLabel = $fieldData->display_label ?? null;
                        }
                    @endphp
                    @switch($value->column_name)
                        {{-- ------------------ --}}
                        {{--  Category          --}}
                        {{-- ------------------ --}}
                        @case('category')
                            <td class="text-center" style="font-size:11px;">
                                @if($cat=="Internal Audit")
                                    <span style="font-size:10px;color:#0033cc">{{ $cat }}</span>
                                @elseif($cat=="External Audit")
                                    <span style="font-size:10px;color:orange">{{$cat}}</span>
                                @elseif($cat=="Management Review")
                                    <span style="font-size:10px;color:#33cc33">{{$cat}}</span>
                                @elseif($cat=="Corrective Action")
                                    <span style="font-size:10px;color:black">{{$cat}}</span>
                                @elseif($issue->category=='Legal Requirement')
                                    <span style="font-size:10px;color:#ff0066">{{$issue->category}}</span>
                                @elseif($issue->category=='Incident Investigation')
                                    <span style="font-size:10px;color:orange">{{$issue->category}}</span>
                                @elseif($issue->category=="Management Review")
                                    <span style="font-size:10px;color:#33cc33">{{$issue->category}}</span>
                                @else
                                    <span style="font-size:10px;color:black">{{$cat ? $cat : '-'}}</span>
                                @endif
                            </td>
                            @break
                            <!-- Type -->
                        @case('type')
                            <td class="text-center" style="font-size:12px;">
                                @if($issue->type=='' || $issue->type=='-')
                                    <span class="status status-blue">-</span>
                                @elseif($issue->typ)
                                    <span style="font-size:10px;background-color:{{ $issue->typ->background_color }};color:{{ $issue->typ->text_color}}" class="badge-class">{{$issue->typ ? $issue->typ->name : ''}}</span>
                                @else
                                    <span class="status status-blue"></span>
                                @endif
                            </td>
                            @break
                            <!-- Department -->
                        @case('department')
                            <td class="text-center" style="font-size:12px;"><span
                                        style="font-size:10px;">{{$dept?$dept:'-'}}</span></td>
                            @break
                            <!-- Division -->
                        @case('division')
                            <td class="text-center" style="font-size:12px;"><span
                                        style="font-size:10px;">{{$division?$division:'-'}}</span></td>
                            @break
                            <!-- Subdivision -->
                        @case('subdivision')
                            <td class="text-center" style="font-size:12px;"><span
                                        style="font-size:10px;">{{$sub_division?$sub_division:'-'}}</span>
                            </td>
                            @break
                            <!-- Raisedby -->
                        @case('raisedby')
                            <td class="text-center" style="font-size:12px;"><span
                                        style="font-size:10px;">{{$issue->postedByCorrective ? $issue->postedByCorrective->name : '-'}}</span>
                            </td>
                            @break
                            <!-- Financial loss -->
                        @case('financial loss')
                            <td class="text-center" style="font-size:12px;"><span
                                        style="font-size:10px;">{{$issue->financial_loss ? $issue->financial_loss : '-'}}</span>
                            </td>
                            @break
                            <!-- Potential cost saving -->
                        @case('potential cost saving')
                            <td class="text-center" style="font-size:12px;"><span
                                        style="font-size:10px;">{{$issue->potential_cost_savings ? $issue->potential_cost_savings : '-'}}</span>
                            </td>
                            @break
                            <!-- Responsibility -->
                        @case('responsibility')
                            <td class="text-center" style="font-size:12px;width:100px !important;"><span
                                        style="font-size:10px;word-break: break-all;">{{ $responsible_names ?  $responsible_names : '-'}}</span>
                            </td>
                            @break
                            <!-- Email remainder for action -->
                        @case('email remainder for action')
                            <td class="text-center" style="font-size:12px;"><span
                                        style="font-size:10px;">{{$issue->frequency ? $issue->frequency : '-'}}</span>
                            </td>
                            @break
                            <!-- Due date -->
                        @case('due date')
                            <td class="text-center" style="font-size:11px;"><span
                                        style="font-size:10px;">{{$issue->duedate ? date('d-m-Y',strtotime($issue->duedate)) : '-'}}</span>
                            </td>
                            @break
                            <!-- Investigation team lead -->
                        @case('investigation team lead')
                            <td class="text-center" style="font-size:11px;"><span
                                        style="font-size:10px;">{{$issue->teamLead ? $issue->teamLead->name : '-'}}</span>
                            </td>
                            @break
                            <!-- Investigation target date -->
                        @case('investigation target date')
                            <td class="text-center" style="font-size:11px;"><span
                                        style="font-size:10px;">
                                    {{$issue->investigation_date ? date('d-m-Y',strtotime($issue->investigation_date)) :'-'}}
                                </span></td>
                            @break
                            <!-- Correction target date -->
                        @case('correction target date')
                            <td class="text-center" style="font-size:11px;"><span
                                        style="font-size:10px;">
                                    @if($issue->correction_target_date!=null)
                                        {{ $issue->correction_target_date ? date('d-m-Y',strtotime($issue->correction_target_date)) :'-' }}
                                    @else
                                        -
                                    @endif
                                </span></td>
                            @break
                            <!-- Source -->
                        @case('source')
                            <td class="text-center" style="font-size:11px;"><span
                                        style="font-size:10px;">
                                    {{$issue->source ? $issue->source : '-'}}
                                </span></td>
                            @break
                            <!-- Added date -->
                        @case('added date')
                            <td class="text-center" style="font-size:11px;"><span
                                        style="font-size:10px;">
                                    {{$issue->created_at ? date('d-m-Y',strtotime($issue->created_at)) : date('d-m-Y',strtotime($issue->postdate)) }}
                                </span></td>
                            @break
                            <!-- Action reviewed by -->
                        @case('action reviewed by')
                            <td class="text-center" style="font-size:11px;"><span
                                        style="font-size:10px;">
                                    {{$issue->actionReviewedByCorrective ? $issue->actionReviewedByCorrective->name : '-'}}
                                </span></td>
                            @break
                            <!-- Action reviewed date -->
                        @case('action reviewed date')
                            <td class="text-center" style="font-size:11px;"><span
                                        style="font-size:10px;">
                                    {{$issue->action_reviewed_at ? date('d-m-Y',strtotime($issue->action_reviewed_at)) : '-' }}
                                </span></td>
                            @break
                            <!-- Closure reviewed by -->
                        @case('closure reviewed by')
                            <td class="text-center" style="font-size:11px;"><span
                                        style="font-size:10px;">
                                    {{$issue->closedByCorrective ? $issue->closedByCorrective->name : '-'}}
                                </span></td>
                            @break
                            <!-- Closure reviewed date -->
                        @case('closure reviewed date')
                            <td class="text-center" style="font-size:11px;"><span
                                        style="font-size:10px;">
                                    {{$issue->closed ? date('d-m-Y',strtotime($issue->closed)) : '-' }}
                                </span></td>
                            @break
                        @case('detection stage')
                            <td class="text-center" style="font-size:11px;">
                                {{$issue->categoryDetectionStage ? $issue->categoryDetectionStage->name : '-'}}
                            </td>
                        @break
                        @case('job order number')
                            <td class="text-center" style="font-size:11px;">
                                @if($fieldValue)
                                    <span style="font-size:10px;">{{ $fieldValue }}<span> <br>
                                    @if($fieldDisplayLabel && $fieldDisplayLabel != $value->display_label)
                                        <span class="custom-badge">
                                            {{ $fieldDisplayLabel }}
                                        </span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                        @break
                        @case('product serial number')
                            <td class="text-center" style="font-size:11px;">
                                @if($fieldValue)
                                    <span style="font-size:10px;">{{ $fieldValue }}<span> <br>
                                    @if($fieldDisplayLabel && $fieldDisplayLabel != $value->display_label)
                                        <span class="custom-badge">
                                            {{ $fieldDisplayLabel }}
                                        </span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                        @break
                        @case('product name')
                            <td class="text-center" style="font-size:11px;">
                                @if($fieldValue)
                                    <span style="font-size:10px;">{{ $fieldValue }}<span> <br>
                                    @if($fieldDisplayLabel && $fieldDisplayLabel != $value->display_label)
                                        <span class="custom-badge">
                                            {{ $fieldDisplayLabel }}
                                        </span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                        @break
                        @case('product code')
                            <td class="text-center" style="font-size:11px;">
                                @if($fieldValue)
                                    <span style="font-size:10px;">{{ $fieldValue }}<span> <br>
                                    @if($fieldDisplayLabel && $fieldDisplayLabel != $value->display_label)
                                        <span class="custom-badge">
                                            {{ $fieldDisplayLabel }}
                                        </span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                        @break
                        @case('batch')
                            <td class="text-center" style="font-size:11px;">
                                @if($fieldValue)
                                    <span style="font-size:10px;">{{ $fieldValue }}<span> <br>
                                    @if($fieldDisplayLabel && $fieldDisplayLabel != $value->display_label)
                                        <span class="custom-badge">
                                            {{ $fieldDisplayLabel }}
                                        </span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                        @break
                        @case('production line')
                            <td class="text-center" style="font-size:11px;">
                                @if($fieldValue)
                                    <span style="font-size:10px;">{{ $fieldValue }}<span> <br>
                                    @if($fieldDisplayLabel && $fieldDisplayLabel != $value->display_label)
                                        <span class="custom-badge">
                                            {{ $fieldDisplayLabel }}
                                        </span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                        @break
                        @case('date of manufacture')
                            <td class="text-center" style="font-size:11px;">
                                @if($fieldValue)
                                    <span style="font-size:10px;">{{ $fieldValue ? date('d-m-Y', strtotime($fieldValue)) : '-' }}<span> <br>
                                    @if($fieldDisplayLabel && $fieldDisplayLabel != $value->display_label)
                                        <span class="custom-badge">
                                            {{ $fieldDisplayLabel }}
                                        </span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                        @break
                        @case('quantity affected')
                            <td class="text-center" style="font-size:11px;">
                                @if($fieldValue)
                                    <span style="font-size:10px;">{{ $fieldValue }}<span> <br>
                                    @if($fieldDisplayLabel && $fieldDisplayLabel != $value->display_label)
                                        <span class="custom-badge">
                                            {{ $fieldDisplayLabel }}
                                        </span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                        @break
                        @case('process name')
                            <td class="text-center" style="font-size:11px;">
                                @if($fieldValue)
                                    <span style="font-size:10px;">{{ $fieldValue }}<span> <br>
                                    @if($fieldDisplayLabel && $fieldDisplayLabel != $value->display_label)
                                        <span class="custom-badge">
                                            {{ $fieldDisplayLabel }}
                                        </span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                        @break
                        @case('machine / equipment id')
                            <td class="text-center" style="font-size:11px;">
                                @if($fieldValue)
                                    <span style="font-size:10px;">{{ $fieldValue }}<span> <br>
                                    @if($fieldDisplayLabel && $fieldDisplayLabel != $value->display_label)
                                        <span class="custom-badge">
                                            {{ $fieldDisplayLabel }}
                                        </span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                        @break
                        @case('operator / shift')
                            <td class="text-center" style="font-size:11px;">
                                @if($fieldValue)
                                    <span style="font-size:10px;">{{ $fieldValue }}<span> <br>
                                    @if($fieldDisplayLabel && $fieldDisplayLabel != $value->display_label)
                                        <span class="custom-badge">
                                            {{ $fieldDisplayLabel }}
                                        </span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                        @break
                        @case('date of process')
                            <td class="text-center" style="font-size:11px;">
                                @if($fieldValue)
                                    <span style="font-size:10px;">{{ $fieldValue ? date('d-m-Y', strtotime($fieldValue)) : '-' }}<span> <br>
                                    @if($fieldDisplayLabel && $fieldDisplayLabel != $value->display_label)
                                        <span class="custom-badge">
                                            {{ $fieldDisplayLabel }}
                                        </span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                        @break
                        @case('vendor name')
                            <td class="text-center" style="font-size:11px;">
                                @if($fieldValue && isset($commanData['vendors'][$fieldValue]))
                                    <span style="font-size:10px;">{{ $commanData['vendors'][$fieldValue] }}<span> <br>
                                    @if($fieldDisplayLabel && $fieldDisplayLabel != $value->display_label)
                                        <span class="custom-badge">
                                            {{ $fieldDisplayLabel }}
                                        </span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                        @break
                        @case('item serial number')
                            <td class="text-center" style="font-size:11px;">
                                @if($fieldValue)
                                    <span style="font-size:10px;">{{ $fieldValue }}<span> <br>
                                    @if($fieldDisplayLabel && $fieldDisplayLabel != $value->display_label)
                                        <span class="custom-badge">
                                            {{ $fieldDisplayLabel }}
                                        </span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                        @break
                        @case('purchase order (PO) number')
                            <td class="text-center" style="font-size:11px;">
                                @if($fieldValue)
                                    <span style="font-size:10px;">{{ $fieldValue }}<span> <br>
                                    @if($fieldDisplayLabel && $fieldDisplayLabel != $value->display_label)
                                        <span class="custom-badge">
                                            {{ $fieldDisplayLabel }}
                                        </span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                        @break
                        @case('receiving report (RR) number')
                            <td class="text-center" style="font-size:11px;">
                                @if($fieldValue)
                                    <span style="font-size:10px;">{{ $fieldValue }}<span> <br>
                                    @if($fieldDisplayLabel && $fieldDisplayLabel != $value->display_label)
                                        <span class="custom-badge">
                                            {{ $fieldDisplayLabel }}
                                        </span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                        @break
                        @case('delivery date')
                            <td class="text-center" style="font-size:11px;">
                                @if($fieldValue)
                                    <span style="font-size:10px;">{{ $fieldValue ? date('d-m-Y', strtotime($fieldValue)) : '-' }}<span> <br>
                                    @if($fieldDisplayLabel && $fieldDisplayLabel != $value->display_label)
                                        <span class="custom-badge">
                                            {{ $fieldDisplayLabel }}
                                        </span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                        @break
                        @default
                            @break
                    @endswitch
                @endforeach
                <!-- Process stage -->
                <td class="text-center" style="font-size:11px;">
                    @if($issue->processstage=='Awaiting actions')
                        <span style="font-size:10px;color: #737373;background-color: #e6e6e6;border-color: #fbf4ea;">Awaiting actions</span>
                    @elseif($issue->processstage == 'Correction Proposed- RC Awaiting' || $issue->processstage == 'Actions Proposed- Review Awaiting' || $issue->processstage == 'RC Updated- Correction Awaiting')
                        <span style="font-size:10px;color: #25499c;background-color: #d9e2f6;border-color: #d9e2f6;">{{$issue->processstage}}</span>
                    @elseif($issue->processstage=='Action Reviewed, OK')
                        <span style="font-size:10px;color: #176044;background-color: #e0edcf;border-color: #bff0dd;">Action Approved</span>
                    @elseif($issue->processstage=='Action Reviewed, Not OK' /*|| $issue->processstage=='rejected'*/)
                        <span style="font-size:10px;color: #af1e1e;background-color: #f8d4d4;border-color: #fbeaea;">Action Rejected</span>
                    @elseif($issue->processstage=='rejected')
                        <span style="font-size:10px;color: #af1e1e;background-color: #f8d4d4;border-color: #fbeaea;">Entry Rejected</span>
                    @elseif($issue->processstage=='Need More Action' || $issue->processstage=='Entry Under Review')
                        <span style="font-size:10px;color: #af6f1e;background-color: #fbdece;border-color: #fbf4ea;">{{$issue->processstage}}</span>
                    @elseif($issue->processstage=='Actions Completed')
                        <span style="font-size:10px;color: #25499c;background-color: #d9e2f6;border-color: #d9e2f6;">{{$issue->processstage}}</span>
                    @elseif($issue->status == 'closed' && $issue->closed <= $issue->correction_target_date) 
                        <span style="font-size:10px;color: #176044;background-color: #e0edcf;border-color: #bff0dd;">{{$issue->status}}</span>
                    @elseif($issue->status == 'closed' && $issue->closed > $issue->correction_target_date)
                        <span style="font-size:10px;color: #af6f1e;background-color: #fbdece;border-color: #fbf4ea;">Closed After Due</span>
                    @else 
                        <span style="font-size:10px;color: #176044;background-color: #e0edcf;border-color: #bff0dd;">-</span>
                    @endif
                </td>
                <!-- Action status -->
                <td class="text-center" style="font-size:12px;"><span
                            style="font-size:10px;">{{ $issue->average_percent }}%</span>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif