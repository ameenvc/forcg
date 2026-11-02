
<!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
<table class="table table-bordered table-striped table-vcenter js-dataTable-full" id="tabledata-open">
    <thead>
        <tr style="position: sticky; top: -5px;z-index:100;">
            <td class="text-center titleheading sorting_disabled" style="background-color:#fff;"></td>
            <td class="text-center titleheading" draggable="false" data-orderable="false" style="cursor:pointer;background-color:#fff;">Slno</td>
            <td class="d-none"></td>
            <td class="text-center px-1 titleheading titleheading2" draggable="false" data-orderable="false" style="background-color:#fff;cursor:pointer;">Id</td>
            <td class="text-center px-1 titleheading titleheading2" draggable="false" data-orderable="false" style="background-color:#fff;cursor:pointer;">Title</td>
            @foreach($tableDetails['columns_active'] as $value)
                @php
                    $className = '';
                    if($value->category_name && $value->category_name=='product ncr'){
                        $className = 'productCategoryHeader';
                    }else if($value->category_name && $value->category_name=='process ncr'){
                        $className = 'processCategoryHeader';
                    }else if($value->category_name && $value->category_name=='vendor ncr'){
                        $className = 'vendorCategoryHeader';
                    }
                @endphp
                @if($className)
                    <td class="text-center titleheading px-1 titleheading2 {{$className}}" style="width: 8%;" 
                        @if($value->is_dragdropable) 
                            data-column-id="{{ $value->id }}" data-category-name="{{ $value->category_name }}" data-field-name="{{ $value->field_name }}"
                        @endif 
                        data-orderable="false" onmouseover="return removehidecolumn('{{$value->id}}','/user/corrective/category-table/removecolumn')">
                        {{$value->display_label}}
                    </td>
                @elseif($value->enabled_by_user!==0)
                    <td data-orderable="false" class="text-center titleheading px-1 titleheading2" 
                        @if($value->is_dragdropable)data-column-id="{{ $value->id }}" @endif
                        @if($value->column_name == 'due date')style="width: 12%" @endif
                        @if($value->column_name == 'added date')style="width: 8%" @endif
                        @if($value->column_name == 'action reviewed date')style="width: 8%" @endif
                        @if($value->column_name == 'correction target date')style="width: 8%" @endif
                        @if($value->column_name == 'closure reviewed date')style="width: 8%" @endif
                        onmouseover="return removehidecolumn('{{$value->id}}','/user/corrective/register-table/removecolumn')">
                        {{$value->column_name}}                                               
                    </td>
                @else
                    <td class="text-center titleheading px-1 titleheading2"
                        @if($value->is_dragdropable)data-column-id="{{ $value->id }}" @endif
                        style="@if($value->column_name == 'due date')width: 10%; @elseif($value->column_name == 'correction target date') min-width: 150px !important;@endif"
                        data-orderable="false">
                        {{$value->column_name}}
                    </td>
                @endif
            @endforeach

            <td id="statusselectdata" class="text-center titleheading px-1 titleheading2" draggable="false" style="background-color:#fff;cursor:pointer;" data-orderable="false" data-column-name="process stage">
                Process Stage
            </td>
            <td class="text-center px-1 titleheading titleheading2" draggable="false" data-orderable="false" style="background-color:#fff;cursor:pointer;">Action Status</td>
            <td class="d-none" data-orderable="false" id="category-filter-global">Category Filter</td>
            <td class="text-center freezelastcolumn" draggable="false" data-orderable="false" style="cursor:pointer;background-color: white !important;"> Actions</td>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 0;
            $isCorrectiveResponsibilityChangeAuthor = Gate::allows('corrective-responsibility-change');
            $isEditOrEditAllAuthor = Gate::allows('edit-corrective') || Gate::allows('edit-all');
        @endphp
        @foreach($issues as $issue)
        @php
            $rr=$issue->id;
            // $date = $issue->duedate;
            $today = Date("Y-m-d");
            $issuedocs=explode(',',$issue->file);
            $actiondocs=explode(',',$issue->action_file);
            $dept = $issue->correctionDept ? $issue->correctionDept->departmentname : '';
            $division = $issue->division ? $issue->division->name : '';
            $sub_division = $issue->area ? $issue->area->name : '';
            $responsible_names = $correctionResponsibilities->whereIn('email', explode(',', $issue->correction_responsibility))->pluck('name')->toArray();
            $responsible_names = implode(',', $responsible_names);
            $team_leader = $issue->teamLead;
            $team_leader_email = $team_leader ? $team_leader->email : null;
            $investigation_team = [];
            foreach($issue->investigators as $investigator) {
                $investigation_team[] = $investigator->email;
            }
            $investigation_array = ['Actions Completed', 'rejected'];
            $cat = $issue->cat ? $issue->cat->categoryname : '';
            $productFieldValues = $issue->ncrFieldValueRelation->keyBy('field_name');
            $processFieldValues = $issue->processNcrFieldValueRelation->keyBy('field_name');
            $vendorFieldValues = $issue->vendorNcrFieldValueRelation->keyBy('field_name');
        @endphp
        <tr>
            <td><input class="checkbox" type="checkbox" id="{{ $issue->id}}" name="id[]"></td> 
            <td class="text-center" style="font-size:10px;">{{ $loop->iteration}}</td>
            <td class="d-none">{{ $issue->id }}</td>                               
            <td class="text-center" style="font-size:12px;">
                @php
                    $target_date = $issue->correction_target_date ? $issue->correction_target_date : $today ;
                @endphp
                @if(($issue->status == 'open' || $issue->status == 'NULL')&& $today > $target_date)
                <span class="status status-red" data-toggle="popover" data-placement="right" data-content="Due">{{$issue->issueid}}</span>
                @else
                <span class="status status-success">{{$issue->issueid}}</span>
                @endif
            </td>
            <td class=" font-w600 text-center" style="font-size:10px;">
                @if($cat =="Incident Investigation")
                <a href="{{ route('corrective.detail', $issue->id) }}">Incident # {{$issue->title}}</a>
                @else
                <a href="{{ route('corrective.detail', $issue->id) }}">{{$issue->title}}</a>
                @endif
            </td>
            @foreach($tableDetails['columns_active'] as $value)
                @php
                    $className = '';
                    if($value->category_name && $value->category_name=='product ncr'){
                        $fieldData = $productFieldValues[$value->field_name] ?? null;
                        $fieldValue = $fieldData->value ?? null;
                        $fieldDisplayLabel = $fieldData->display_label ?? null;
                        $className = 'productCategoryHeader';
                    }else if($value->category_name && $value->category_name=='process ncr'){
                        $fieldData = $processFieldValues[$value->field_name] ?? null;
                        $fieldValue = $fieldData->value ?? null;
                        $fieldDisplayLabel = $fieldData->display_label ?? null;
                        $className = 'processCategoryHeader';
                    }else if($value->category_name && $value->category_name=='vendor ncr'){
                        $fieldData = $vendorFieldValues[$value->field_name] ?? null;
                        $fieldValue = $fieldData->value ?? null;
                        $fieldDisplayLabel = $fieldData->display_label ?? null;
                        $className = 'vendorCategoryHeader';
                    }
                @endphp
                @switch($value->column_name)
                    @case('category')
                        @if($cat=="Internal Audit")
                        <td class="text-center" style="font-size:10px;width: 5%;color:#0033cc">
                            {{ $cat }}
                            <div class="d-none">{{ $cat ? $cat . '_f' : '-' }}</div>
                        </td>
                        @elseif($cat=="External Audit")
                        <td class="text-center" style="font-size:10px;width: 5%;color:orange">
                            {{$cat}}
                            <div class="d-none">{{ $cat ? $cat . '_f' : '-' }}</div>
                        </td>
                        @elseif($cat=="Management Review")
                        <td class="text-center" style="font-size:10px;width: 5%;color:#33cc33">
                            {{$cat}}
                            <div class="d-none">{{ $cat ? $cat . '_f' : '-' }}</div>
                        </td>
                        @elseif($cat=="Corrective Action")
                        <td class="text-center" style="font-size:10px;width: 5%;color:black;">
                            {{$cat}}
                            <div class="d-none">{{ $cat ? $cat . '_f' : '-' }}</div>
                        </td>
                        @elseif($issue->category=='Legal Requirement')
                        <td class="text-center" style="font-size:10px;width: 5%;color:#ff0066">
                            {{$issue->category}}
                            <div class="d-none">{{ $issue->category ? $issue->category . '_f' : '-' }}</div>
                        </td>
                        @elseif($issue->category=='Incident Investigation')
                        <td class="text-center" style="font-size:10px;width: 5%;color:orange;">
                            {{$issue->category}}
                            <div class="d-none">{{ $issue->category ? $issue->category . '_f' : '-' }}</div>
                        </td>
                        @elseif($issue->category=="Management Review")
                        <td class="text-center" style="font-size:10px;width: 5%;color:#33cc33">
                            {{$issue->category}}
                            <div class="d-none">{{ $issue->category ? $issue->category . '_f' : '-' }}</div>
                        </td>
                        @else
                        <td class="text-center" style="font-size:10px;width: 5%;color:black">
                            {{$cat}}
                            <div class="d-none">{{ $cat ? $cat . '_f' : '-' }}</div>
                        </td>
                        @endif
                    @break
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
                    @case('department')
                        <td class="text-center" style="font-size:10px;">
                        {{$dept?$dept:'-'}}
                        </td>
                    @break
                    @case('division')
                        @if(in_array('division',$tableDetails['columns_names_active']))
                        <td class="text-center" style="font-size:10px;">
                        {{$division?$division:'-'}}
                        </td>
                        @endif
                    @break
                    @case('subdivision')
                        @if(in_array('subdivision',$tableDetails['columns_names_active']))
                        <td class="text-center" style="font-size:10px;">
                        {{$sub_division?$sub_division:'-'}}
                        </td>
                        @endif
                    @break
                    @case('raisedby')
                        @if(in_array('raisedby',$tableDetails['columns_names_active']))
                        <td class="text-center" style="font-size:10px;">
                        {{$issue->postedByCorrective ? $issue->postedByCorrective->name : '-'}}
                        </td>
                        @endif
                    @break
                    @case('financial loss')
                        @if(in_array('financial loss',$tableDetails['columns_names_active']))
                        <td class="text-center" style="font-size:10px;">
                        {{$issue->financial_loss ? $issue->financial_loss : '-'}}
                        </td>
                        @endif
                    @break
                    @case('potential cost saving')
                        @if(in_array('potential cost saving',$tableDetails['columns_names_active']))
                        <td class="text-center" style="font-size:10px;">
                        {{$issue->potential_cost_savings ? $issue->potential_cost_savings : '-'}}
                        </td>
                        @endif
                    @break
                    @case('responsibility')
                        @if(in_array('responsibility',$tableDetails['columns_names_active']))
                        <td class="text-center" style="font-size:10px;">
                        {{ $responsible_names ?  $responsible_names : '-'}}
                        </td>
                        @endif
                    @break
                    @case('email remainder for action')
                        @if(in_array('email remainder for action',$tableDetails['columns_names_active']))
                        <td class="text-center" style="font-size:10px;">
                        {{$issue->frequency ? $issue->frequency : '-'}}
                        </td>
                        @endif
                    @break
                    @case('due date')
                        @if(in_array('due date',$tableDetails['columns_names_active']))
                        <td class="text-center" style="font-size:10px;">
                            {{$issue->duedate ? date('d-m-Y',strtotime($issue->duedate)) : '-'}}
                        </td>
                        @endif
                    @break
                    @case('investigation team lead')
                        @if(in_array('investigation team lead',$tableDetails['columns_names_active']))
                        <td class="text-center" style="font-size:10px;">
                        {{$issue->teamLead ? $issue->teamLead->name : '-'}}
                        </td>
                        @endif
                    @break
                    @case('investigation target date')
                        @if(in_array('investigation target date',$tableDetails['columns_names_active']))
                        <td class="text-center" style="font-size:10px;">
                        @if($issue->investigation_date!=null)
                            {{ $issue->investigation_date ? date('d-m-Y',strtotime($issue->investigation_date)) :'--' }}
                        @else
                        --
                        @endif
                        </td>
                        @endif
                    @break
                    @case('correction target date')
                        @if(in_array('correction target date',$tableDetails['columns_names_active']))
                        <td class="text-center" style="font-size:10px;">
                        @if($issue->correction_target_date!=null)                                                                                    
                            {{ $issue->correction_target_date ? date('d-m-Y',strtotime($issue->correction_target_date)) :'--' }}                                                                                       
                        @else
                        --
                        @endif
                        </td>
                        @endif
                    @break
                    @case('source')
                        @if(in_array('source',$tableDetails['columns_names_active']))
                        <td class="text-center" style="font-size:10px;">
                        {{$issue->source ? $issue->source : '-'}}
                        </td>
                        @endif
                    @break
                    @case('added date')
                        @if(in_array('added date',$tableDetails['columns_names_active']))
                        <td class="text-center" style="font-size:10px;">
                        {{$issue->created_at ? date('d-m-Y',strtotime($issue->created_at)) : date('d-m-Y',strtotime($issue->postdate)) }}
                        </td>
                        @endif
                    @break
                    @case('action reviewed by')
                        @if(in_array('action reviewed by',$tableDetails['columns_names_active']))
                        <td class="text-center" style="font-size:10px;">
                        {{$issue->actionReviewedByCorrective ? $issue->actionReviewedByCorrective->name : '-'}}
                        </td>
                        @endif
                    @break
                    @case('action reviewed date')
                        @if(in_array('action reviewed date',$tableDetails['columns_names_active']))
                        <td class="text-center" style="font-size:10px;">
                        {{$issue->action_reviewed_at ? date('d-m-Y',strtotime($issue->action_reviewed_at)) : '-' }}
                        </td>
                        @endif
                    @break
                    @case('closure reviewed by')
                        @if(in_array('closure reviewed by',$tableDetails['columns_names_active']))
                        <td class="text-center" style="font-size:10px;">
                        {{$issue->closedByCorrective ? $issue->closedByCorrective->name : '-'}}
                        </td>
                        @endif
                    @break
                    @case('closure reviewed date')
                        @if(in_array('closure reviewed date',$tableDetails['columns_names_active']))
                        <td class="text-center" style="font-size:10px;">
                        {{$issue->closed ? date('d-m-Y',strtotime($issue->closed)) : '-' }}
                        </td>
                        @endif
                    @break
                    @case('detection stage')
                        @if(in_array('detection stage',$tableDetails['columns_names_active']))
                        <td class="text-center" style="font-size:10px;">
                            {{$issue->categoryDetectionStage ? $issue->categoryDetectionStage->name : '-'}}
                            <div class="d-none">{{ $issue->categoryDetectionStage ? $issue->categoryDetectionStage->name . '_f' : '-' }}</div>
                        </td>
                        @endif
                    @break
                    @case('job order number')
                        <td class="text-center {{ $className }} px-1" style="font-size:10px;">
                            @if($fieldValue)
                                {{ $fieldValue }} <br>
                                @if($fieldDisplayLabel && $fieldDisplayLabel != $value->display_label)
                                    <span class="custom-badge">
                                        {{ $fieldDisplayLabel }}
                                    </span>
                                @endif
                            <div class="d-none">{{ $fieldValue . '_openpostfix' }}</div>
                            @else
                                -
                            @endif
                        </td>
                    @break
                    @case('product serial number')
                        <td class="text-center {{ $className }} px-1" style="font-size:10px;">
                            @if($fieldValue)
                                {{ $fieldValue }} <br>
                                @if($fieldDisplayLabel && $fieldDisplayLabel != $value->display_label)
                                    <span class="custom-badge">
                                        {{ $fieldDisplayLabel }}
                                    </span>
                                @endif
                                <div class="d-none">{{ $fieldValue . '_openpostfix' }}</div>
                            @else
                                -
                            @endif
                        </td>
                    @break
                    @case('product name')
                        <td class="text-center {{ $className }} px-1" style="font-size:10px;">
                             @if($fieldValue)
                                {{ $fieldValue }} <br>
                                @if($fieldDisplayLabel && $fieldDisplayLabel != $value->display_label)
                                    <span class="custom-badge">
                                        {{ $fieldDisplayLabel }}
                                    </span>
                                @endif
                                <div class="d-none">{{ $fieldValue . '_openpostfix' }}</div>
                            @else
                                -
                            @endif
                        </td>
                    @break
                    @case('product code')
                        <td class="text-center {{ $className }} px-1" style="font-size:10px;">
                             @if($fieldValue)
                                {{ $fieldValue }} <br>
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
                        <td class="text-center {{ $className }} px-1" style="font-size:10px;">
                             @if($fieldValue)
                                {{ $fieldValue }} <br>
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
                        <td class="text-center {{ $className }} px-1" style="font-size:10px;">
                             @if($fieldValue)
                                {{ $fieldValue }} <br>
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
                        <td class="text-center {{ $className }} px-1" style="font-size:10px;">
                            @if($fieldValue)
                                {{ $fieldValue ? date('d-m-Y', strtotime($fieldValue)) : '-' }} <br>
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
                        <td class="text-center {{ $className }} px-1" style="font-size:10px;">
                            @if($fieldValue)
                                {{ $fieldValue }} <br>
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
                        <td class="text-center {{ $className }} px-1" style="font-size:10px;">
                            @if($fieldValue)
                                {{ $fieldValue }} <br>
                                @if($fieldDisplayLabel && $fieldDisplayLabel != $value->display_label)
                                    <span class="custom-badge">
                                        {{ $fieldDisplayLabel }}
                                    </span>
                                @endif
                                <div class="d-none">{{ $fieldValue . '_openpostfix' }}</div>
                            @else
                                -
                            @endif
                        </td>
                    @break
                    @case('machine / equipment id')
                        <td class="text-center {{ $className }} px-1" style="font-size:10px;">
                            @if($fieldValue)
                                {{ $fieldValue }} <br>
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
                        <td class="text-center {{ $className }} px-1" style="font-size:10px;">
                            @if($fieldValue)
                                {{ $fieldValue }} <br>
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
                        <td class="text-center {{ $className }} px-1" style="font-size:10px;">
                            @if($fieldValue)
                                {{ $fieldValue ? date('d-m-Y', strtotime($fieldValue)) : '-' }} <br>
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
                        <td class="text-center {{ $className }} px-1" style="font-size:10px;">
                            @if($fieldValue && isset($commanData['vendors'][$fieldValue]))
                                {{ $commanData['vendors'][$fieldValue] }} <br>

                                @if($fieldDisplayLabel && $fieldDisplayLabel != $value->display_label)
                                    <span class="custom-badge">
                                        {{ $fieldDisplayLabel }}
                                    </span>
                                @endif
                                <div class="d-none">{{ $commanData['vendors'][$fieldValue] . '_openpostfix' }}</div>
                            @else
                                -
                            @endif
                        </td>
                    @break
                    @case('item serial number')
                        <td class="text-center {{ $className }} px-1" style="font-size:10px;">
                            @if($fieldValue)
                                {{ $fieldValue }} <br>
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
                        <td class="text-center {{ $className }} px-1" style="font-size:10px;">
                            @if($fieldValue)
                                {{ $fieldValue }} <br>
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
                        <td class="text-center {{ $className }} px-1" style="font-size:10px;">
                            @if($fieldValue)
                                {{ $fieldValue }} <br>
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
                        <td class="text-center {{ $className }} px-1" style="font-size:10px;">
                            @if($fieldValue)
                                {{ $fieldValue ? date('d-m-Y', strtotime($fieldValue)) : '-' }} <br>
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
            <td class="text-center" style="font-size:13px;">
                @if($issue->processstage=='Awaiting actions')
                <span class="status status-gray">Awaiting actions</span>
                <div class="d-none">{{ $issue->processstage ? 'e_'. $issue->processstage . '_f' : '-' }}</div>
                @elseif($issue->processstage == 'Correction Proposed- RC Awaiting' || $issue->processstage == 'Actions Proposed- Review Awaiting' || $issue->processstage == 'RC Updated- Correction Awaiting')
                <span class="status status-blue">{{$issue->processstage}}</span>
                <div class="d-none">{{ $issue->processstage ? 'e_'. $issue->processstage . '_f' : '-' }}</div>
                @elseif($issue->processstage=='Action Reviewed, OK')
                <span class="status status-success">Action Approved</span>
                <div class="d-none">{{ 'e_'. 'Action Approved'.'_f'  }}</div>
                @elseif($issue->processstage=='Action Reviewed, Not OK' /*|| $issue->processstage=='rejected'*/)
                <span class="status status-red">Action Rejected</span>
                <div class="d-none">{{ 'e_'. 'Action Rejected'.'_f'  }}</div>
                @elseif($issue->processstage=='rejected')
                <span class="status status-red">Entry Rejected</span>
                <div class="d-none">{{ 'e_'. 'Entry Rejected'.'_f'  }}</div>
                @elseif($issue->processstage=='Need More Action' || $issue->processstage=='Entry Under Review')
                <span class="status status-warning">{{$issue->processstage}}</span>
                <div class="d-none">{{ $issue->processstage ? 'e_'. $issue->processstage . '_f' : '-' }}</div>
                @elseif($issue->processstage=='Actions Completed')
                <span class="status status-blue">{{$issue->processstage}}</span>
                <div class="d-none">{{ $issue->processstage ? 'e_'. $issue->processstage . '_f' : '-' }}</div>
                {{-- @elseif($issue->processstage=='Approved')
                    <span class="status status-success">{{$issue->processstage}}</span> --}}
                @endif
                        
            </td>
            <td style="font-size:10px;">
                <div class="progress mb-1" style="height: 5px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $issue->average_percent }}%;" aria-valuenow="{{ $issue->average_percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <p class="fs-xs fw-semibold mb-0">{{ $issue->average_percent }}%</p>
            </td>
            <td class="text-center d-none">
                <div class="d-none">{{ $issue->cat && $issue->cat->is_pnc_cat=='yes' ? 'product' : '' }}</div>
                <div class="d-none">{{ $issue->cat && $issue->cat->is_process_cat=='yes' ? 'process' : '' }}</div>
                <div class="d-none">{{ $issue->cat && $issue->cat->is_vendor_cat=='yes' ? 'vendor' : '' }}</div>
            </td>
            <td class="font-w600 font-size-sm freezelastcolumn">
                <div class="btn-group m-auto d-flex justify-content-center">
                    <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" data-placement="top" title="Actions" id="drop{{$issue->id}}">
                        <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer"></i>
                    </a>
                    @php
                        $disableLinkAttr = [
                            'add_attachment' => ['rejected'],
                            'change_investigation_team' => ['Actions Completed', 'rejected'],
                            'responsible_change' => ['Actions Completed', 'rejected'],
                        ]
                    @endphp
                    <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownMenu{{ $issue->id }}">
                        @foreach($issuereviewers as $rep)
                            @if(Auth::user()->email==$rep)
                                @if($issue->processstage != 'Entry Under Review')
                                    <!--// Add attachment //-->
                                    <a href="#" class="dropdown-item {{ in_array($issue->processstage, $disableLinkAttr['add_attachment']) ? 'disabled-link' : ''}}"
                                        style="font-size:10px;" onclick="theAlert({{ $issue->id }})">
                                        <i class="si si-paper-clip" style="font-size:10px;"></i> Add attachment
                                    </a>
                                    <!--// Change investigation team //-->
                                    @if($issue->is_req_rc_analysis_management_review=="yes")
                                    <button class="js-change-investigation-team dropdown-item {{ in_array($issue->processstage, $disableLinkAttr['change_investigation_team']) ? 'disabled-link' : ''}}"
                                            data-url="{{ route('corrective.change.investigation.team', $issue->id) }}"
                                            style="font-size:10px;"
                                            {{ $issue->processstage == 'Actions Completed' ? 'disabled' : ''}}>
                                        <i class="fa fa-users" style="font-size:10px;"></i> Change investigation team
                                    </button>
                                    @endif
                                @endif
                                <!--// Responsibility change //-->
                                @if($isCorrectiveResponsibilityChangeAuthor && $issue->processstage != 'Entry Under Review')
                                    <a class="dropdown-item {{ in_array($issue->processstage, $disableLinkAttr['responsible_change']) ? 'disabled-link' : ''}}"
                                            style="font-size:10px;" href="{{url('/user/corrective/editresp?val='.$rr)}}"
                                            {{ $issue->processstage == 'Actions Completed' ? 'disabled' : ''}}>
                                        <i class="fa fa-user-edit" style="font-size:10px;"></i> Responsibility change
                                    </a>
                                @endif
                                <!--// Review //-->
                                @if($issue->processstage == 'Entry Under Review')
                                    <a href="{{url('/user/corrective/approve?val='.$rr)}}"
                                        class="dropdown-item" style=" font-size:10px;">
                                        <i class="fas fa-redo-alt" style="font-size:10px;"></i> Review
                                    </a>
                                @endif
                                <!--// Review Action //-->
                                @if( $issue->processstage == 'Actions Proposed- Review Awaiting' && $issue->processstage != 'Entry Under Review')
                                    <a href="{{url('/user/corrective/reviewstatus?val='.$rr)}}"
                                        class="dropdown-item" style="font-size:10px;">
                                        <img src="{{ asset('images/icons/review.svg') }}"> Review Action
                                    </a>
                                <!--// Closure Review //-->
                                @elseif( $issue->processstage == 'Actions Completed' && $issue->processstage != 'Entry Under Review')
                                    <a href="{{url('/user/corrective/closure?val='.$rr)}}"
                                        class="dropdown-item" style="font-size:10px;">
                                        <i class="si si-close" style="font-size:10px;"></i> Closure Review
                                    </a>
                                @endif
                                <!--// Add Investigation Report //-->
                                @if($issue->team_lead_id || count($issue->investigators) && $issue->processstage != 'Entry Under Review')
                                    <button class="dropdown-item {{ in_array($issue->processstage, $investigation_array) || ($auth_email !== $team_leader_email && !in_array($auth_email, $investigation_team)) ? 'disabled-link' : ''}}"
                                            style="font-size:10px;"
                                            {{ $issue->processstage == 'Actions Completed' ? 'disabled disabled-link' : ''}}
                                        onclick="showInvestigationModal('{{ $issue->id }}', '{{ route('corrective.investigation', $issue->id) }}')">
                                        <i class="si si-plus" style="font-size:10px;"></i> Add Investigation Report
                                    </button>
                                @endif
                            @endif
                        @endforeach
                        <!--// Add Investigation Report //-->
                        @if(Auth::user()->email === $team_leader_email && !in_array(Auth::user()->email, $issuereviewers))
                            <button class="dropdown-item" style="font-size:10px;"
                                onclick="showInvestigationModal('{{ $issue->id }}', '{{ route('corrective.investigation', $issue->id) }}')">
                                <i class="si si-plus" style="font-size:10px;"></i> Add Investigation Report
                            </button>
                        @endif
                        <!--// Resubmit Issue //-->
                        @if(Auth::user()->employeeid === $issue->postedby && $issue->processstage=='rejected' && $issue->processstage != 'Entry Under Review')
                            <a onclick="cll('Are u sure to resubmit?')"
                                href="{{url('/user/corrective/resubmit?val='.$rr)}}"
                                class="dropdown-item" style="font-size:10px;">
                                <i class="fas fa-paper-plane" style="font-size:10px;"></i> Resubmit Issue
                            </a>
                        @endif
                        @if($issue->processstage != 'Entry Under Review')
                            @if($isEditOrEditAllAuthor)
                                @if($issue->processstage == 'Awaiting actions')
                                    <a style="font-size:10px;"
                                            class="dropdown-item" 
                                           href="{{url('/user/corrective/edit/'.$rr)}}">
                                        <i class="fas fa-edit" style="font-size:10px;"></i> Edit
                                    </a>
                                @else
                                    <a style="font-size:10px;" href="{{url('/user/corrective/edit-proposed/'.$rr)}}"
                                            class="dropdown-item {{ $issue->processstage == 'Actions Completed' || $issue->processstage == 'rejected' ? 'disabled-link' : ''}}"
                                           {{ $issue->processstage == 'Actions Completed' ? 'disabled' : ''}}>
                                        <i class="fas fa-edit" style="font-size:10px;"></i> Edit
                                    </a>
                                @endif
                            @endif
                        @endif
                    </div>
                    <a href="#" class="btn btn-sm btn-alt-primary btn-view-detail viewDetails"
                        data-route="{{ $issue->id }}">
                        <i class="far fa-eye text-center " style="font-size:10px;cursor:pointer"></i>
                    </a>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="{{ versionedCache('js/datatable_extension_corrective_open.js') }}"></script>

<script>
    var removedIssueFilenames = [];
    var removedActionFilenames = [];
    $(document).on('click', '.remove-action-attachment', function () {
        var filename = $(this).data('filename');
        $(this).closest('tr').remove();
        removedActionFilenames.push(filename);
        $('#actionAttachmentTable tbody tr').each(function(index) {
            $(this).find('td:first').text(index + 1);
            $(this).find('td:first').css('font-size', '10px');
        });
    });
    $(document).on('click', '.remove-issue-attachment', function () {
        var filename = $(this).data('filename');
        $(this).closest('tr').remove();
        removedIssueFilenames.push(filename);
        $('#issueAttachmentTable tbody tr').each(function(index) {
            $(this).find('td:first').text(index + 1);
            $(this).find('td:first').css('font-size', '10px');
        });
    });
</script>
<script>
    $(document).ready(function() {
        let issue_ids = "{{ $issue_ids }}";
        issue_ids = issue_ids.split(',');

        for (let i = 0; i < issue_ids.length; i++) {
            let id = `${issue_ids[i]}`;

            $(`#factors` + id).select2({
                dropdownParent: $(`#factors_append` + id)
            });
            $(`#influence` + id).select2({
                dropdownParent: $(`#influence_append` + id)
            });
            $(`#team` + id).select2({
                dropdownParent: $(`#team_append` + id)
            });
            $(`#lead` + id).select2({
                dropdownParent: $(`#lead_append` + id)
            });
        
            function getvalues(id) {
                var inps = document.querySelectorAll('[data-foo=r' + id + ']');
                var rootcause_arr = [];
                for (var i = 0; i < inps.length; i++) {
                    var inp = inps[i];
                    rootcause_arr[i] = inp.value;
                }

                return rootcause_arr;
            }

            function getcorrectiveactionvalues(id) {
                var inps = document.querySelectorAll('[data-foo=c' + id + ']');
                var arr = [];
                for (var i = 0; i < inps.length; i++) {
                    var inp = inps[i];
                    arr[i] = inp.value;
                }
                return arr;
            }

            function getdeptvalues(id) {
                var inps = document.querySelectorAll('[data-foo=d' + id + ']');
                var arr = [];
                for (var i = 0; i < inps.length; i++) {
                    var inp = inps[i];
                    arr[i] = inp.value;
                }
                return arr;
            }


            function getrespvalues(id) {
                var inps = document.querySelectorAll('[data-foo=resp' + id + ']');
                var arr = [];
                for (var i = 0; i < inps.length; i++) {
                    var inp = inps[i];
                    arr[i] = inp.value;
                }
                return arr;
            }


            function gettargetdatevalues(id) {
                var inps = document.querySelectorAll('[data-foo=tar' + id + ']');
                var arr = [];
                for (var i = 0; i < inps.length; i++) {
                    var inp = inps[i];
                    arr[i] = inp.value;
                }
                return arr;
            }


        }

        $('body').on('click', '.headingselect', function () {
            if (!$(this).find('.filter-content').length) {
                $('.filter-content').hide();
            }
        });

        $('body').on('click', '.btn-submit-investigation', function (e) {
            e.preventDefault();
            const id = $(this).attr('data-id');
            const submitButton = $(this);
            const modal = $(this).closest('.investigation-modal')
            let error_investigation_selector = modal.find('#error_investigation' + id);
            let validation_error_investigation_selector = modal.find('#validation_error_investigation' + id);
            let success_investigation_selector = modal.find('#success_investigation' + id);
            let occurence_selector = $("input[name='occurrence']");
            let recent_changes_selector = $("input[name='recent_changes']");
            let follow_procedures_selector = $("input[name='follow_procedures']");
            let issue_foreseeable_selector = $("input[name='issue_foreseeable']");
            let rootcause_arr = getvalues(id);
            let corr_arr = getcorrectiveactionvalues(id);
            let dept_arr = getdeptvalues(id);
            let resp_arr = getrespvalues(id);
            let tar_arr = gettargetdatevalues(id);
            let formData = new FormData();
            formData.append('corrective_id', $('#corrective_id' + id).val());
            formData.append('occurrence', occurence_selector.is(":checked") ? occurence_selector.filter(":checked").val() : 0);
            formData.append('system_related_problems', $("#system_related_problems" + id).val());
            formData.append('recent_changes', recent_changes_selector.is(":checked") ? recent_changes_selector.filter(":checked").val() : 0);
            formData.append('follow_procedures', follow_procedures_selector.is(":checked") ? follow_procedures_selector.filter(":checked").val() : 0);
            formData.append('issue_foreseeable', issue_foreseeable_selector.is(":checked") ? issue_foreseeable_selector.filter(":checked").val() : 0);
            formData.append('prevent_reason', $('#prevent_reason' + id).val());
            formData.append('factors_details', $("#factors_details_val" + id).val());
            formData.append('influence_details', $("#influence_details_val" + id).val());
            formData.append('removedIssueFilenames',JSON.stringify(removedIssueFilenames));
            formData.append('removedActionFilenames',JSON.stringify(removedActionFilenames))
            rootcause_arr.forEach(function(item) {
                formData.append('rootcause[]', item);
            });
            corr_arr.forEach(function(item) {
                formData.append('correctiveaction[]', item);
            });
            dept_arr.forEach(function(item) {
                formData.append('department[]', item);
            });
            resp_arr.forEach(function(item) {
                formData.append('responsibility[]', item); 
            });
            tar_arr.forEach(function(item) {
                formData.append('target_date[]', item);
            });
            let issueFiles = $('#issue_file')[0].files;
            for (let i = 0; i < issueFiles.length; i++) {
                formData.append('issue_file[]', issueFiles[i]);
            }
            let actionFiles = $('#action_file')[0].files;
            for (let i = 0; i < actionFiles.length; i++) {
                formData.append('action_file[]', actionFiles[i]);
            }
            var factors = $('#factors' + id).val().length ? $('#factors' + id).val() : '';
            var influence = $('#influence' + id).val().length ? $('#influence' + id).val() : '';
            if(factors){
                factors.forEach(function(item) {
                    formData.append('contributing_factors[]', item);
                });
            }else{
                formData.append('contributing_factors[]', "");
                formData.append('external_factors', "");
            }
            if(influence){
                influence.forEach(function(item) {
                    formData.append('external_factors[]', item);
                });
            }else{
                formData.append('external_factors[]', "");
            }
            $.ajax({
                type: 'POST'
                , headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                , url: '{{ route("user.corrective.submit_investigation_form") }}', 
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    submitButton.html('<i class="fa fa-spinner fa-spin"></i> Submit');
                }
                , success: function(response) {
                    error_investigation_selector.removeClass('d-block');
                    error_investigation_selector.addClass('d-none');

                    validation_error_investigation_selector.removeClass('d-block')
                    validation_error_investigation_selector.addClass('d-none');

                    success_investigation_selector.removeClass('d-none');
                    success_investigation_selector.addClass('d-block');

                    setTimeout(function() {
                        submitButton.html('Submit');
                        window.location.reload();
                    }, 2000);
                }
                , error: function(err) {
                    submitButton.html('Submit');
                    if (err.status === 500) {
                        error_investigation_selector.removeClass('d-none');
                        error_investigation_selector.addClass('d-block');

                        validation_error_investigation_selector.removeClass('d-block')
                        validation_error_investigation_selector.addClass('d-none');

                        success_investigation_selector.removeClass('d-block');
                        success_investigation_selector.addClass('d-none');
                    } else if (err.status === 422) {
                        success_investigation_selector.removeClass('d-block');
                        success_investigation_selector.addClass('d-none');

                        error_investigation_selector.removeClass('d-block');
                        error_investigation_selector.addClass('d-none');

                        validation_error_investigation_selector.addClass('d-block')
                        validation_error_investigation_selector.removeClass('d-none');

                        var error_text = '';
                        $.each(err.responseJSON.errors, function(i) {
                            $.each(err.responseJSON.errors[i], function(key, val) {
                                error_text += '<li style="font-size:12px;">' + val + '</li>';
                            });
                        });
                        validation_error_investigation_selector.html(error_text);


                    }
                }
            });
        }).on('click', '.btn-change-investigation', function (e) {
            e.preventDefault();
            const id = $(this).attr('data-id');
            const submitButton = $(this);
            const modal = $(this).closest('.change-investigation-modal')
            let error_investigation_selector = modal.find('#error_change_investigation' + id);
            let validation_error_investigation_selector = modal.find('#validation_error_change_investigation' + id);
            let success_investigation_selector = modal.find('#success_change_investigation' + id);
            let lead_id = $("#lead" + id).val();
            let team_members = $("#team" + id).val();

            $.ajax({
                type: 'POST'
                , headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                , url: '{{ route("user.corrective.submit_change_investigation_form") }}'
                , data: {
                    'corrective_id': id
                    , 'lead': lead_id
                    , 'team_members': team_members
                },
                beforeSend: function () {
                    submitButton.html('<i class="fa fa-spinner fa-spin"></i> Submit');
                }
                , success: function(response) {
                    error_investigation_selector.removeClass('d-block');
                    error_investigation_selector.addClass('d-none');

                    validation_error_investigation_selector.removeClass('d-block')
                    validation_error_investigation_selector.addClass('d-none');

                    success_investigation_selector.removeClass('d-none');
                    success_investigation_selector.addClass('d-block');

                    setTimeout(function() {
                        submitButton.html('Submit');
                        window.location.reload();
                    }, 2000);
                }
                , error: function(err) {
                    submitButton.html('Submit');
                    if (err.status === 500) {
                        error_investigation_selector.removeClass('d-none');
                        error_investigation_selector.addClass('d-block');

                        validation_error_investigation_selector.removeClass('d-block')
                        validation_error_investigation_selector.addClass('d-none');

                        success_investigation_selector.removeClass('d-block');
                        success_investigation_selector.addClass('d-none');
                    } else if (err.status === 422) {
                        success_investigation_selector.removeClass('d-block');
                        success_investigation_selector.addClass('d-none');

                        error_investigation_selector.removeClass('d-block');
                        error_investigation_selector.addClass('d-none');

                        validation_error_investigation_selector.addClass('d-block')
                        validation_error_investigation_selector.removeClass('d-none');

                        var error_text = '';
                        $.each(err.responseJSON.errors, function(i) {
                            $.each(err.responseJSON.errors[i], function(key, val) {
                                error_text += '<li style="font-size:12px;">' + val + '</li>';
                            });
                        });
                        validation_error_investigation_selector.html(error_text);


                    }
                }
            });
        })
    });
    // ------------------------------------table column filter starts------------------------------------------------//
    let opencorrectivecolumndetails = <?php echo $tableDetails['columns_active_for_js'];?>;
    
    enableDragDrop('#tabledata-open', '{{ route("corrective.register.table.column.updateposition") }}', function () {
        location.reload();
    });
    if(opencorrectivecolumndetails.length > 0) {
        const $preInitData = {
            'category': {
                'options': "{{ $allcategoriesname }}",
                'display_name': "Category",
                'is_enable_remove': false,
                'is_enable_filter': true,
                'option': {'filter_postfix' : '_f'},
                'strict_ness':false
            },
            'type': {
                'options': "{{ $alltypenames }}",
                'display_name': "Type",
                'is_enable_remove': false,
                'is_enable_filter': true,
                'option': {},
                'strict_ness':false
            },
            'department': {
                'options': "{{ $alldeptnames }}",
                'display_name': "Department",
                'is_enable_remove': false,
                'is_enable_filter': true,
                'option': {},
                'strict_ness':false
            },
            'division': {
                'options': "{{ $alldivisionnames }}",
                'display_name': "Division",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {},
                'strict_ness':true
            },
            'subdivision': {
                'options': "{{ $allsubdivisionnames }}",
                'display_name': "Subdivision",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {},
                'strict_ness':true
            },
            'raisedby': {
                'options': "{{ $allraisedbynames }}",
                'display_name': "Raisedby",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {},
                'strict_ness':false
            },
            'detection stage': {
                'options': "{{ $alldetectionstages }}",
                'display_name': "Detection Stage",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {'filter_postfix' : '_f'},
                'strict_ness':false
            },
            'financial loss': {
                'options': '',
                'display_name': '',
                'is_enable_remove': true,
                'is_enable_filter': false,
                'option': {},
                'strict_ness':false
            },
            'potential cost saving': {
                'options': '',
                'display_name': '',
                'is_enable_remove': true,
                'is_enable_filter': false,
                'option': {},
                'strict_ness':false
            },
            'responsibility': {
                'options': "{{ $allusernames }}",
                'display_name': "Responsibility",
                'is_enable_remove': false,
                'is_enable_filter': true,
                'option': {},
                'strict_ness':false
            },
            'email remainder for action': {
                'options': '',
                'display_name': '',
                'is_enable_remove': true,
                'is_enable_filter': false,
                'option': {},
                'strict_ness':false
            },
            'due date': {
                'options': "{{ $dueDateYears }}",
                'display_name': "Due Date",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {'is_date_filter' : true},
                'strict_ness':false
            },
            'investigation team lead': {
                'options': "{{ $allteamleadnames }}",
                'display_name': "Investigative Team Lead",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {},
                'strict_ness':true
            },
            'investigation target date': {
                'options': '{{ $investigtaion_target_dates }}',
                'display_name': "Investigative Target date",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option':  {'is_date_filter' : true},
                'strict_ness':false
            },
            'process stage': {
                'options': "{{ $allpstages }}",
                'display_name': "Process Stage",
                'is_enable_remove': false,
                'is_enable_filter': true,
                'option': {'filter_postfix' : '_f'},
                'strict_ness':false
            },
            'correction target date': {
                'options': '{{ $correctiontargetDateYears }}',
                'display_name': "Correction Target date",
                'is_enable_remove': false,
                'is_enable_filter': true,
                'option':  {'is_date_filter' : true},
                'strict_ness':false
            },
            'source': {
                'options': '{{$sourceNames}}',
                'display_name': "Source",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {},
                'strict_ness':true
            },
            'added date': {
                'options': "{{ $addDateYears }}",
                'display_name': "Added Date",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {'is_date_filter' : true},
                'strict_ness':false
            },
            'action reviewed by': {
                'options': "{{ $allactionreviewednames }}",
                'display_name': "Action Reviewed by",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {},
                'strict_ness':false
            },
            'action reviewed date': {
                'options': "{{ $actionReviewedDateYears }}",
                'display_name': "Action Reviewed date",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {'is_date_filter' : true},
                'strict_ness':false
            },
            'closure reviewed by': {
                'options': "{{ $allclosurereviewednames }}",
                'display_name': "Closure Reviewed by",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {},
                'strict_ness':false
            },
            'closure reviewed date': {
                'options': "{{ $closureReviewedDateYears }}",
                'display_name': "Closure Reviewed date",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {'is_date_filter' : true},
                'strict_ness':false
            },
            'product ncr_job order number': {
                'options': "{{$commanData['filters']['jobOrderFilters']['open']['product']}}",
                'display_name': "",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {'filter_postfix' : '_openpostfix'},
                'strict_ness':false
            },
            'product ncr_product serial number': {
                'options': "{{$commanData['filters']['productSerialFilters']['open']['product']}}",
                'display_name': "",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {'filter_postfix' : '_openpostfix'},
                'strict_ness':false
            },
            'product name': {
                'options': "{{$commanData['filters']['productNameFilters']['open']}}",
                'display_name': "",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {'filter_postfix' : '_openpostfix'},
                'strict_ness':false
            },
            'product code': {
                'options': "",
                'display_name': "",
                'is_enable_remove': true,
                'is_enable_filter': false,
                'option': {},
                'strict_ness':false
            },
            'batch': {
                'options': "",
                'display_name': "",
                'is_enable_remove': true,
                'is_enable_filter': false,
                'option': {},
                'strict_ness':false
            },
            'product ncr_production line': {
                'options': "",
                'display_name': "",
                'is_enable_remove': true,
                'is_enable_filter': false,
                'option': {},
                'strict_ness':false
            },
            'date of manufacture': {
                'options': "",
                'display_name': "",
                'is_enable_remove': true,
                'is_enable_filter': false,
                'option': {},
                'strict_ness':false
            },
            'product ncr_quantity affected': {
                'options': "",
                'display_name': "",
                'is_enable_remove': true,
                'is_enable_filter': false,
                'option': {},
                'strict_ness':false
            },
            'process ncr_job order number': {
                'options': "{{$commanData['filters']['jobOrderFilters']['open']['process']}}",
                'display_name': "",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {'filter_postfix' : '_openpostfix'},
                'strict_ness':false
            },
            'process ncr_product serial number': {
                'options': "{{$commanData['filters']['productSerialFilters']['open']['process']}}",
                'display_name': "",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {'filter_postfix' : '_openpostfix'},
                'strict_ness':false
            },
            'process ncr_production line': {
                'options': "",
                'display_name': "",
                'is_enable_remove': true,
                'is_enable_filter': false,
                'option': {},
                'strict_ness':false
            },
            'process ncr_quantity affected': {
                'options': "",
                'display_name': "",
                'is_enable_remove': true,
                'is_enable_filter': false,
                'option': {},
                'strict_ness':false
            },
            'process name': {
                'options': "{{$commanData['filters']['processNameFilters']['open']}}",
                'display_name': "",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {'filter_postfix' : '_openpostfix'},
                'strict_ness':false
            },
            'machine / equipment id': {
                'options': "",
                'display_name': "",
                'is_enable_remove': true,
                'is_enable_filter': false,
                'option': {},
                'strict_ness':false
            },
            'operator / shift': {
                'options': "",
                'display_name': "",
                'is_enable_remove': true,
                'is_enable_filter': false,
                'option': {},
                'strict_ness':false
            },
            'date of process': {
                'options': "",
                'display_name': "",
                'is_enable_remove': true,
                'is_enable_filter': false,
                'option': {},
                'strict_ness':false
            },
            'vendor ncr_job order number': {
                'options': "{{$commanData['filters']['jobOrderFilters']['open']['vendor']}}",
                'display_name': "",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {'filter_postfix' : '_openpostfix'},
                'strict_ness':false
            },
            'vendor ncr_product serial number': {
                'options': "{{$commanData['filters']['productSerialFilters']['open']['vendor']}}",
                'display_name': "",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {'filter_postfix' : '_openpostfix'},
                'strict_ness':false
            },
            'vendor name': {
                'options': "{{$commanData['filters']['vendorsForFilter']['open']}}",
                'display_name': "",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {'filter_postfix' : '_openpostfix'},
                'strict_ness':false
            },
            'vendor ncr_quantity affected': {
                'options': "",
                'display_name': "",
                'is_enable_remove': true,
                'is_enable_filter': false,
                'option': {},
                'strict_ness':false
            },
            'item serial number': {
                'options': "",
                'display_name': "",
                'is_enable_remove': true,
                'is_enable_filter': false,
                'option': {},
                'strict_ness':false
            },
            'purchase order (PO) number': {
                'options': "",
                'display_name': "",
                'is_enable_remove': true,
                'is_enable_filter': false,
                'option': {},
                'strict_ness':false
            },
            'receiving report (RR) number': {
                'options': "",
                'display_name': "",
                'is_enable_remove': true,
                'is_enable_filter': false,
                'option': {},
                'strict_ness':false
            },
            'delivery date': {
                'options': "",
                'display_name': "",
                'is_enable_remove': true,
                'is_enable_filter': false,
                'option': {},
                'strict_ness':false
            },
        };
        for (let index = 0; index < opencorrectivecolumndetails.length; index++) {
            const actualColumnName = (opencorrectivecolumndetails[index]['column_name'] || '').toLowerCase().trim();
            const categoryName = (opencorrectivecolumndetails[index]['category_name'] || '').toLowerCase().trim();
            
            
            // First try category + column_name
            let configKey = categoryName
                ? categoryName + '_' + actualColumnName
                : actualColumnName;
            
            // Fallback to only column_name for common columns
            if (!$preInitData[configKey]) {
                configKey = actualColumnName;
            }
            let categoryClass = '';

            if (categoryName === 'product ncr') {
                categoryClass = 'productCategoryHeader';
            } else if (categoryName === 'process ncr') {
                categoryClass = 'processCategoryHeader';
            } else if (categoryName === 'vendor ncr') {
                categoryClass = 'vendorCategoryHeader';
            }
            if($preInitData[configKey]) {
                const $columnData = $preInitData[configKey];
                
                const headerSearchText = opencorrectivecolumndetails[index]['display_label'] 
                    ? opencorrectivecolumndetails[index]['display_label'].trim()
                    : opencorrectivecolumndetails[index]['column_name'].trim();
                    
                const $columnIndex = getColumnIndexByHeaderName('#tabledata-open', headerSearchText,categoryClass);

                const displayLabel = opencorrectivecolumndetails[index]['display_label'];

                if ($columnData['is_enable_remove']) {
                    enableRemoveColumn('#tabledata-open', opencorrectivecolumndetails[index]['column_name'], $columnIndex)
                }
                if ($columnData['is_enable_filter'] && $columnIndex !== -1) {
                    enableHeaderFilter(
                        '#tabledata-open',
                        actualColumnName.replace(/ /g, '-'),
                        $columnData['options'],
                        displayLabel ? displayLabel : ($columnData['display_name'] || actualColumnName),
                        $columnIndex,
                        $columnData['option'],
                        $columnData['strict_ness']
                    )
                }
            }
        }
    }
    // ------------------------------------Ends table column filter------------------------------------------------//
</script>

<script>
    $(document).ready(function(){
        $("#tabledata-open").one("preInit.dt", function() {
            let tableElement = $(this);
            let tableId = tableElement.attr('id');
            let table = tableElement.DataTable();
            var columnIndexCategory = table.column('#category-filter-global').index();
            const originalZeroRecordsMsg = table.settings()[0].oLanguage.sZeroRecords;
            var columnIndex = table.column('#processstageshead').index();

            // Add index column
            table.on('draw.dt', function() {
                let pageInfo = table.page.info();
                table.rows({ page: 'current' }).every(function(rowIdx, tableLoop, rowLoop) {
                    $('td:eq(1)', this.node()).text(pageInfo.start + rowLoop + 1);
                });
            });

            let allProcessstages = "{{ $allpstages}}".split(',');
            let processstagesOptions = allProcessstages.map(processstages => `<option value="${processstages}">${processstages}</option>`).join('');
            let dropdownId = tableId + "_processstages_dropdown";
            let processstagesDropdown = $(`
                <select class="headingselect text-center" style="border:none;-webkit-appearance: none;min-width:fit-content;background-color:#fff;font-weight:600 !important;" id="${dropdownId}">
                    <option value="">Process&nbspStage&nbsp;&nbsp;&#9207;</option>
                    ${processstagesOptions}
                </select>
            `);
            tableElement.find('#processstageshead').html(processstagesDropdown);
            $(`#${dropdownId}`).on('change', function() {
                let selectedValue = $(this).val();
                let firstOption = $(this).find('option:first');
                if (selectedValue) {
                    table.columns(columnIndex).search('e_'+selectedValue + '_f').draw();
                    $(this).addClass('text-primary');
                    $(this).find("option").css('color', 'black');
                } else {
                    table.column(columnIndex).search('').draw();
                    $(this).removeClass('text-primary');
                    $(this).val('');
                }
                firstOption.html('Process&nbspStage&nbsp;&nbsp;&#9207;');
            });
            
            function applyRadioFilter(selectedValue) {
                if (selectedValue) {
                    table.settings()[0].oLanguage.sZeroRecords = `No NCRs found`;
                    table.columns(columnIndexCategory).search(selectedValue).draw();
                    if (selectedValue === 'product') {
                        $('.productCategoryHeader').show(); 
                        $('.processCategoryHeader').hide(); 
                        $('.vendorCategoryHeader').hide();
                        $('#clearFilterBtn').removeClass('d-none');
                    } else if (selectedValue === 'process') {
                        $('.productCategoryHeader').hide(); 
                        $('.processCategoryHeader').show(); 
                        $('.vendorCategoryHeader').hide();
                        $('#clearFilterBtn').removeClass('d-none');
                    } else if (selectedValue === 'vendor') {
                        $('.productCategoryHeader').hide(); 
                        $('.processCategoryHeader').hide(); 
                        $('.vendorCategoryHeader').show();
                        $('#clearFilterBtn').removeClass('d-none');
                    } else {
                        $('#clearFilterBtn').addClass('d-none');
                        $('.productCategoryHeader').hide(); 
                        $('.processCategoryHeader').hide(); 
                        $('.vendorCategoryHeader').hide();
                    }
                } else {
                    table.column(columnIndexCategory).search('').draw();
                }
            }
            $(document).on('change', '.filter-radio', function() {
                applyRadioFilter($(this).val());
            });
            applyRadioFilter($('.filter-radio:checked').val());
        });
    });
</script>
{{-----------edit button ------------}}
