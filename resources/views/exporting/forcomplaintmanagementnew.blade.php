@php
    $thInlineStyles = 'font-size:11px;';
    $thSpanInlineStyles = 'font-size:10px;';
    $tdInlineStyles = 'font-size:11px;';
    $tdSpanInlineStyles = 'font-size:10px;';
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
    .badge {
        display: inline-block;
        padding: 4px 8px;
        font-size: 12px;
        font-weight: 600;
        border-radius: 4px;
        color: #fff;
    }

    .badge-success {
        background: #198754;
    }

    .badge-warning {
        background: #ffc107;
        color: #000;
    }

    .badge-danger {
        background: #dc3545;
    }

    .badge-secondary {
        background-color: #6c757d;
    }

    .bg-flat {
        background-color: #f1f3f5;
        color: #495057;
    }

    .badge-info {
        background-color: #0dcaf0;

    }

    .bg-amethyst-light {
        background-color: #f3e8ff;
        color: #6f42c1;
    }

    .bg-city-light {
        background-color: #e9edf2;
        color: #4a5568;
    }

    .status {
        display: inline-block;
        padding: 7px 5px 5px 7px;
        font-size: 75%;
        font-weight: 600;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.25rem;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .status-red {
        color: #af1e1e;
        background-color: #f8d4d4;
        border-color: #fbeaea;
    }

    .status-warning {
        color: #af6f1e;
        background-color: #fbdece;
        border-color: #fbf4ea;
    }

    .status-gray {
        color: #737373;
        background-color: #e6e6e6;
        border-color: #fbf4ea;
    }

    .status-blue {
        color: #25499c;
        background-color: #d9e2f6;
        border-color: #d9e2f6;
    }

    .status-success {
        color: #176044;
        background-color: #e0edcf;
        border-color: #bff0dd;
    }

    .tag {
        display:inline-block;
        font-size:7px;
        color:#555;
        background:#f2f2f2;
        padding:2px 6px;
        border-radius:4px;
        margin-top:2px;
    }

</style>

@if (isset($data) && count($data) > 0)
   <table class="table2" border='1' style='width: 100%; border-collapse:collapse;' bordercolor='black'  align='center'
           id="headerTableOpen">
        <thead>
            <tr>
                @foreach ($headers as $header)
                    <th class="text-center" style="{{$thInlineStyles}}"><span style="{{ $thSpanInlineStyles }}">{{ $header['label']}}</span></th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php
                $row = 0;
            @endphp
            @foreach ($data as $d)
                @php
                    $rowspan = max($d->products->count(),1);
                @endphp
                @if ($tab == "category" && !$d->products->isEmpty() && $rowspan > 1)
                    @foreach ($d->products as $index => $product)
                        <tr>
                            @if ($index == 0)
                                <td class="text-center" style="{{$tdInlineStyles}}" rowspan="{{$rowspan}}"><span class="text-center" style="{{$tdSpanInlineStyles}}">{{ ++$row }}</span></td>
                                <td class="text-center" style="{{$tdInlineStyles}}" rowspan="{{$rowspan}}"><span class="text-center" style="{{$tdSpanInlineStyles}}">{{ $d->trackno }}</span></td>
                                <td class="text-center" style="{{$tdInlineStyles}}" rowspan="{{$rowspan}}"><span class="text-center" style="{{$tdSpanInlineStyles}}">{{ $d->title }}</span></td>
                                <td class="text-center" style="{{$tdInlineStyles}}" rowspan="{{$rowspan}}"><span class="text-center {{getProductTypeStyle($d->type, 'pdf')}}" style="{{$tdSpanInlineStyles}}">{{ $d->type }}</span></td>
                                <td class="text-center" style="{{$tdInlineStyles}}" rowspan="{{$rowspan}}"><span class="text-center " style="{{$tdSpanInlineStyles}}">{{ $d->customerDetails ? $d->customerDetails->customer : $d->customer }}</span></td>
                                <td class="text-center" style="{{$tdInlineStyles}}" rowspan="{{$rowspan}}"><span class="text-center" style="{{$tdSpanInlineStyles}}">{{ $d->responsiblePerson ? $d->responsiblePerson->name : $d->responsibility }}</span></td>
                                <td class="text-center" style="{{$tdInlineStyles}}" rowspan="{{$rowspan}}"><span class="text-center" style="{{$tdSpanInlineStyles}}">{{ $d->department ? $d->department->departmentname : $d->dept }}</span></td>
                                <td class="text-center" style="{{$tdInlineStyles}}" rowspan="{{$rowspan}}"><span class="text-center {{getDueDateStyle($d->tardate, 'pdf')}}" style="{{$tdSpanInlineStyles}}">{{ $d->tardate ? date('d-m-Y', strtotime($d->tardate)) : '-'}}</span></td>
                            @endif
                            <td class="text-center" style="{{$tdInlineStyles}}"><span class="text-center" style="{{$tdSpanInlineStyles}}">{{ $product->cost_impact ?? '-' }}</span></td>
                            <td class="text-center" style="{{$tdInlineStyles}}"><span class="text-center" style="{{$tdSpanInlineStyles}}">{{ $product->complaint_category ? getComplaintCategoriesFromStringIds($product->complaint_category) : '-' }}</span></td>
                            @if ($index == 0)
                                <td class="text-center" style="{{$tdInlineStyles}}" rowspan="{{$rowspan}}"><span class="text-center {{getProcessStageStyle($d->status, 'pdf')}}" style="{{$tdSpanInlineStyles}}">{{ getProcessStageValue($d->status) }}</span></td>
                                <td class="text-center" style="{{$tdInlineStyles}}" rowspan="{{$rowspan}}"><span class="text-center" style="{{$tdSpanInlineStyles}}">{{ $d->average_percent.'%' }}</span></td>
                            @endif
                        </tr>
                    @endforeach
                @else
                    <tr>
                        @php
                            $fieldRules = json_decode($d->complaint_template_section_field_rules, true);
                        @endphp
                        @foreach ($headers as $header)
                            @php
                                $className = "";
                                $columnValue = "";
                                $columnHtmlValue = "";
                                $tag = "";
                                switch ($header['name']) {
                                    case 'S.No':
                                        $columnValue = ++$row;
                                        break;
                                    case 'Complaint ID';
                                        $columnValue = $d->trackno;
                                        break;
                                    case 'Title';
                                        $columnValue = $d->title;
                                        break;
                                    case 'type':
                                        $columnValue = $d->type;
                                        $className = getProductTypeStyle($d->type, 'pdf');
                                        break;
                                    case 'customer';
                                        $columnValue = $d->customerDetails ? $d->customerDetails->customer : $d->customer;
                                        if ($tab != "category") {
                                           $tag = App\Helpers\Complaint\ComplaintHelper::getTagForExport($fieldRules, $header['label'], 'customer', 'Customer Name');
                                        }
                                        break;
                                    case 'department';
                                        $columnValue = $d->department ? $d->department->departmentname : $d->dept;
                                        break;
                                    case 'responsibility':
                                        $columnValue = $d->responsiblePerson ? $d->responsiblePerson->name : $d->responsibility;
                                        break;
                                    case 'due date';
                                        if ($d->tardate) {
                                            $columnValue = date('d-m-Y', strtotime($d->tardate));
                                            $className = getDueDateStyle($d->tardate, 'pdf');
                                        } else {
                                            $columnValue = '-';
                                        }
                                        break;
                                    case 'raisedby';
                                        $columnValue = $d->raisedByPerson ? $d->raisedByPerson->name : $d->addedby;
                                        break;
                                    case 'added date':
                                        $columnValue = $d->created_at ? date('d-m-Y',strtotime($d->created_at)) : '-';
                                        break;
                                    case 'complaint received by';
                                        $columnValue = $d->recievedByPerson ? $d->recievedByPerson->emp_name : $d->employee;
                                        break;
                                    case 'complaint received date';
                                        $columnValue = $d->crdate ? date('d-m-Y', strtotime($d->crdate)) : '-';
                                        break;
                                    case 'mode of complaint':
                                        $columnValue = ucfirst($d->mode_of_complaint);
                                        break;
                                    case 'customer phone';
                                        $columnValue = $d->customer_phone;
                                        $tag = App\Helpers\Complaint\ComplaintHelper::getTagForExport($fieldRules, $header['label'], 'customerphone', 'Customer Phone');
                                    break;
                                    case 'customer email';
                                        $columnValue = $d->customer_email;
                                        $tag = App\Helpers\Complaint\ComplaintHelper::getTagForExport($fieldRules, $header['label'], 'customeremail', 'Customer Email');
                                        break;
                                    case 'product':
                                        $columnValue = getProductNames(explode(',', $d->product));
                                        $tag = App\Helpers\Complaint\ComplaintHelper::getTagForExport($fieldRules, $header['label'], 'product', 'Product / Service');
                                        break;
                                    case 'sale order no.';
                                        $columnValue = $d->sale_order_no;
                                        $tag = App\Helpers\Complaint\ComplaintHelper::getTagForExport($fieldRules, $header['label'], 'saleorder', 'Sale Order Number');
                                        break;
                                    case 'delivery no.';
                                        $columnValue = $d->delivery_no;
                                        $tag = App\Helpers\Complaint\ComplaintHelper::getTagForExport($fieldRules, $header['label'], 'deliveryno', 'Delivery Number');
                                        break;
                                    case 'delivery date':
                                        $columnValue = $d->delivery_date ? date('d-m-Y', strtotime($d->delivery_date)) : '-' ;
                                        $tag = App\Helpers\Complaint\ComplaintHelper::getTagForExport($fieldRules, $header['label'], 'deliverydate', 'Delivery Date');
                                        break;
                                    case 'job order no.';
                                        $columnValue = $d->job_order_no;
                                        $tag = App\Helpers\Complaint\ComplaintHelper::getTagForExport($fieldRules, $header['label'], 'job_order_no', 'Job Order Number');
                                        break;
                                    case 'product serial no.';
                                        $columnValue = $d->product_serial_no;
                                        $tag = App\Helpers\Complaint\ComplaintHelper::getTagForExport($fieldRules, $header['label'], 'product_serial_no', 'Product Serial Number');
                                        break;
                                    case 'project name':
                                        $columnValue = $d->project_name;
                                        $tag = App\Helpers\Complaint\ComplaintHelper::getTagForExport($fieldRules, $header['label'], 'project_name', 'Project Name');
                                        break;
                                    case 'warranty coverage';
                                        $columnValue = $d->warranty_coverage;
                                        $className = getWarrantyCoverageStyle($d->warranty_coverage, 'pdf');
                                        break;
                                    case 'plant';
                                        $columnValue = $d->plantDetails ? $d->plantDetails->plant_name : $d->plant;
                                        break;
                                    case 'complaint category':
                                        $columnValue = $d->complaintCategory ? $d->complaintCategory['category_name'] : $d->complaint_category_id;
                                        break;
                                    case 'vendor';
                                        $columnValue = isset($d->vendor) ? $d->vendor['vendor_name'] : $d->vendor_id;
                                        break;
                                    case 'division';
                                        $columnValue = $d->division ? $d->division->name : $d->division_id;;
                                        break;
                                    case 'subdivision':
                                        $columnValue = $d->subDivision ? $d->subDivision->name : $d->area_id;;
                                        break;
                                    case 'financial loss';
                                        $columnValue = $d->financial_loss;
                                        break;
                                    case 'potential cost saving';
                                        $columnValue = $d->potential_cost_savings;
                                        break;
                                    case 'email reminder for action':
                                        $columnValue = $d->eremainder;
                                        break;
                                    case 'investigation team lead';
                                        $columnValue = $d->teamLead ? $d->teamLead->name : $d->team_lead_id;
                                        break;
                                    case 'investigation target date';
                                        $columnValue = $d->investigation_date ? date('d-m-Y', strtotime($d->investigation_date)): '-';
                                        break;
                                    case 'reviewed by':
                                        $columnValue = $d->reviewedBy ? $d->reviewedBy->name : $d->reviewed_by;
                                        break;
                                    case 'reviewed date';
                                        $columnValue = $d->reviewedat ? date('d-m-Y',strtotime($d->reviewedat)) : '-';
                                        break;
                                    case 'closure reviewed by';
                                        $columnValue = $d->closedBy ? $d->closedBy->name : $d->closed_by;
                                        break;
                                    case 'closure reviewed date':
                                        $columnValue = $d->closed_at ? date('d-m-Y',strtotime($d->closed_at)) : '-';
                                        break;
                                    case 'issue authenticity':
                                        $columnValue = $d->issue_authenticity;
                                        break;
                                    case 'source of issue':
                                        $columnValue = $d->source_of_issue;
                                        break;
                                    case 'cost impact':
                                        $costImpact = $d->products->pluck('cost_impact')->toArray();
                                        $columnValue = count($costImpact) == 1 ?  $costImpact[0]: '-';
                                        break;
                                    case 'product complaint category':
                                        $complaintCategories = $d->products->pluck('complaint_category')->toArray();
                                        $columnValue = count($complaintCategories) == 1 ?  getComplaintCategoriesFromStringIds($complaintCategories[0]): '-';
                                        break;
                                    case 'Process Stage':
                                        $columnValue = getProcessStageValue($d->status);
                                        $className = getProcessStageStyle($d->status, 'pdf');
                                        break;
                                    case 'Action Status':
                                        $columnValue = $d->average_percent.'%';
                                        break;
                                    default:
                                        $columnValue = 'invalid';
                                        break;
                                }
                            @endphp
                            <td class="text-center" style="{{$tdInlineStyles}}">
                                @if ($columnHtmlValue)
                                    {!!$columnHtmlValue!!}
                                @elseif (isset($className) && $className)
                                    <span style="{{$tdSpanInlineStyles}}" class="{{ $className }}">{{$columnValue}}</span>
                                    @if ($tag)
                                        <br><span class="tag">{{$tag}}</span>
                                    @endif
                                @else
                                    <span class="text-center" style="{{$tdSpanInlineStyles}}">{{ $columnValue ?? '-' }}</span>
                                    @if ($tag)
                                        <br><span class="tag">{{$tag}}</span>
                                    @endif
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endif

            @endforeach
        </tbody>
   </table>
@endif
