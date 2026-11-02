@extends('pages.datatables')
@section('exportadd')
{{-- <div class="input-group input-group-sm" style="display: none">
    <a href="" class="btn btn-sm btn-dual" style="margin-left:0px;" onclick="return fnCovertTable('print')">
        <i class="fa fa-print  fa-1x" style="color:grey"></i>
    </a>
</div> --}}
@php
    $papersize = 'a4';
    $no_of_columns = count($table_columns_active);
    if ( $no_of_columns >= 7 && $no_of_columns <= 14) {
        $papersize = 'a3';
    } elseif ($no_of_columns > 14 && $no_of_columns < 20) {
        $papersize = 'a2';
    } elseif ($no_of_columns >= 20) {
        $papersize = 'a1';
    }
@endphp
<div class="input-group input-group-sm">
    <button type="button" class=" btn-sm btn-dual btn btn-default dropdown-toggle mr-0 mb-0" data-toggle="dropdown">
        <i class="fa fa-file-export fa-1x" style="color:grey;"></i>
    </button>
    <ul class="dropdown-menu" role="menu" style="min-width:10px;text-align:center;">
        <li><button title="Pdf" onclick="onClickExport('pdf')" style="width:50px;  font-size:10px;border:none;background-color:white;">Pdf</button></li>
        <li><button onclick="onClickExport('excel')" style="width:60px;  font-size:10px;border:none;background-color:white;">Excel</button></li>
        <li><button onclick="onClickExport('word')" style="width:60px;  font-size:10px;border:none;background-color:white;">Word</button></li>
    </ul>
</div>

<div class="input-group input-group-sm">
    <a type="button" class="btn btn-sm btn-dual" style="margin-left:8px;" style="" id="addComplaint">
        <i class="fa fa-1x fa-plus" style="color:grey;"></i>
    </a>
</div>
@endsection
@section('content')
<style>
    .scrollable {
        height: 100%;
        overflow-x: scroll;
        overflow-y: scroll;
    }
    .scrollable2 {
        max-height: 60%;
        overflow-x: scroll;
    }
    .breadcrumb > li + li:before {
        display: none !important;
    }
    .breadcrumb {
        font-size: 15px;
        padding: 0.75rem 1.25rem;
        background: #F6F7F9;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }
    .breadcrumb li:first-child {
        font-size: 1rem;
        text-transform: uppercase;
    }
    .breadcrumb li:last-child {
        font-size: 1rem;
        color: gray;
    }
    .td-class{
        font-size:12px;
        padding:4px !important;
    }
    .status-red {
        color: #af1e1e;
        background-color: #f8d4d4;
        border-color: #fbeaea;
    }

    .status-red-light {
        color: #e27e7e;
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

    .status-success-light {
        color: #488a71;
        background-color: #e0edcf;
        border-color: #bff0dd;
    }

    .status-amethyst-light {
        background-color: #f3e8ff;
        color: #6f42c1;
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
    .select2-results__options{
        font-size:12px !important;
        /* font-weight:bold !important; */
    }
    .titleheading {
        font-size: 10px !important;
        text-transform: capitalize !important;
        position: sticky !important;
        position: relative !important;
        font-weight:bold !important;
    }
    .titleheading2{
        min-width: 120px !important;
    }
    .disabled-link {
        pointer-events: none;
        color: #999 !important;
        text-decoration: none;
    }
    .headingselect {
        white-space: break-spaces;
        line-height: 1rem;
    }
    .breadcrumb-slash >li+li:before {
        display: none !important;
    }
    #dropdownMenucol {
        display: none;
        position: absolute;
        z-index: 1000;
        background-color: white;
        list-style: none;
        margin: 0;
        border: none;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        border-radius: .2rem;
    }
    #dropdownMenucol li {
        padding: 5px 5px;
    }
    #dropdownMenucol li a {
        text-decoration: none;
        color: black;
        display: block;
    }
    #dropdownMenucol li a:hover {
        background-color: #f0f0f0;
    }
    .btn-configure-table {
        border-radius: 50%;
        background: white;
        border: 2px solid;
    }
    .btn-configure-table:hover {
        background: #0a0a0a;
        border: 2px solid white;
    }
    .dragging {
        opacity: 0.5;
        background-color: #f0f0f0;
        border: 2px solid #aaa;
    }
    td.titleheading {
        cursor: move;
    }
    .header-arrow {
        position: relative;
        padding-right: 15px;
    }
    .header-arrow::after {
        /* content: 'X';  */
        position: absolute;
        right: 5px;
        top: 50%;
        transform: translateY(-50%);
        opacity: 0;
        transition: opacity 0.2s ease;
        color: red;
        font-size: 13px;
    }

    .header-arrow:hover::after {
        opacity: 1;
    }

    .header-arrow.desc::after {
        content: '\2193';
    }
    .even{
        background-color: #ffffff !important;
    }
    @media (min-width: 760px) {
        #tabledata_filter {
            position: fixed !important;
            right: 70px !important;
            text-align: right !important;
        }
        #tabledata_length{
            position: fixed !important;
            text-align: left !important;
        }
        div.table-responsive > div.dataTables_wrapper > div.row {
            margin-bottom: 2.5rem !important;
        }
    }
    .horizontal-scroll-active-2 #tabledata2_filter {
        position: fixed !important;
        right: 60px !important;
        text-align: right !important;
    }
    .horizontal-scroll-active-2 #tabledata2_length {
        position: fixed !important;
        text-align: left !important;
    }
    .horizontal-scroll-active-2 > div.dataTables_wrapper > div.row {
        margin-bottom: 2.5rem !important;
    }
    .nested {
        display: none;
        padding-left: 20px;
    }
    .filter-container {
        background-color: #fff;
    }
    .filter-header {
        cursor: pointer;
        font-size: 10px;
    }
    .filter-content {
        display: none;
        position: absolute;
        background: white;
        width: 100%;
        border: 1px solid #e1e6e9;
        box-shadow: 2px 2px 2px #e1e6e9;
        max-height: 200px;
        overflow: scroll;
    }
    .date-filter {
        padding: 5px 5px;
        cursor: pointer;
        text-align: left;
        transition: background-color 0.3s;
        position: relative;
        display: flex;
        justify-content: flex-start;
        align-items: center;
    }
    .date-filter.month {
        padding: 5px 15px;
        border-left: 1px dashed;
    }
    .date-filter.day {
        padding: 5px 30px;
        border-left: 1px dashed;
    }
    .date-filter.month::before,.date-filter.day::before {
        content: " ";
        border-bottom: 1px dashed black;
        position: absolute;
        left: 0px;
        top: 12px;
    }
    .date-filter.month::before {
        width: 15px;
    }
    .date-filter.day::before {
        width: 29px;
    }
    .expand-icon {
        padding: 0.5px 5px;
        border: 1px solid #d9e2f6;
        margin-right: 2px;
        color: #24499c;
    }
    .nested {
        display: none;
        padding-left: 15px;
    }
    .nested:not(.year) {
        border-left: 1px dashed;
    }
    .cke-filter {
        margin-right: 5px;
    }
    #sno::after {
        content: "";
    }
</style>
<button type="button" class="js-swal-warning d-none addcomplaint"></button>
<input type="hidden" value="a4" id="tabselected">
<div id="page-loader" class="show"></div>
<!-- Page Content -->
<div class="alert alert-danger" id="columnremovaldangernotremoved" style="display:none"></div>
<div class="content" style="width:100%;">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb d-flex justify-content-between align-items-center">
            <div class="d-flex breadcrumb-slash">
                <li class="breadcrumb-item font-w700">Closed Complaints</li>
                <li class="breadcrumb-item">Customer Complaint</li>
            </div>
            @if ($data3->count() > 0)
                <li class="breadcrumb-item" id="configuretable" style="display: {{ $activeTemplate->title == 'Product Template' ? 'block' : 'none' }}">
                    <a href="#" class="btn btn-sm btn-alt-secondary btn-configure-table" data-toggle="dropdown" data-placement="top">
                        <span data-toggle="tooltip" title="Click here to configure column">
                        <i class="fa fa-sliders-h text-center" style="font-size:17px;cursor:pointer"></i>
                        </span>
                    </a>
                    <div class="dropdown-menu font-size-sm scrollable2" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownMenu">
                        @foreach($table_columns_non_active as $value)
                            <a href="{{ route('complaint.closed.table.column.activation',['id'=>$value->id])}}" class="dropdown-item text-dark" style="font-size:11px;"
                                id="table_column_value{{$value->id}}"><i class="si si-plus" style="font-size:10px;"></i>&nbsp;
                                @switch($value->column_name)
                                    @case('customer')
                                        {{$product_custom_fields['customer']['field_display_label']}}
                                        @break

                                    @case('product')
                                        {{$product_custom_fields['product']['field_display_label']}}
                                        @break

                                    @case('customer phone')
                                        {{$product_custom_fields['customerphone']['field_display_label']}}
                                        @break

                                    @case('customer email')
                                        {{$product_custom_fields['customeremail']['field_display_label']}}
                                        @break

                                    @case('sale order no.')
                                        {{$product_custom_fields['saleorder']['field_display_label']}}
                                        @break

                                    @case('delivery no.')
                                        {{$product_custom_fields['deliveryno']['field_display_label']}}
                                        @break

                                    @case('delivery date')
                                        {{$product_custom_fields['deliverydate']['field_display_label']}}
                                        @break
                                    @default
                                        {{ucwords($value->column_name)}}
                                @endswitch
                            </a>
                        @endforeach
                        @if($table_columns_names_enabled_by_user>0)
                        <a href="{{route('complaint.closed.table.column.defaultview')}}" class="btn btn-sm btn-alt-primary" style="font-size:12px;margin-top:10px;margin-left:25px;margin-bottom:10px;">
                            Reset to default table
                        </a>
                        @endif
                    </div>
                </li>
            @endif
            @if ($dataProject->count() > 0)
                <li class="breadcrumb-item" id="configuretableProject" style="display: {{ $activeTemplate->title == 'Project Template' ? 'block' : 'none' }}">
                    <a href="#" class="btn btn-sm btn-alt-secondary btn-configure-table" data-toggle="dropdown" data-placement="top">
                        <span data-toggle="tooltip" title="Click here to configure column">
                        <i class="fa fa-sliders-h text-center" style="font-size:17px;cursor:pointer"></i>
                        </span>
                    </a>
                    <div class="dropdown-menu font-size-sm scrollable2" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownMenu">
                        @foreach($project_table_columns_non_active as $value)
                            <a href="{{ route('complaint.closed.table.column.activation',['id'=>$value->id])}}" class="dropdown-item text-dark" style="font-size:11px;"
                                id="table_column_value{{$value->id}}"><i class="si si-plus" style="font-size:10px;"></i>&nbsp;
                                @switch($value->column_name)
                                    @case('customer')
                                        {{$project_custom_fields['customer']['field_display_label']}}
                                        @break

                                    @case('product')
                                        {{$project_custom_fields['product']['field_display_label']}}
                                        @break

                                    @case('customer phone')
                                        {{$project_custom_fields['customerphone']['field_display_label']}}
                                        @break

                                    @case('customer email')
                                        {{$project_custom_fields['customeremail']['field_display_label']}}
                                        @break

                                    @case('job order no.')
                                        {{$project_custom_fields['job_order_no']['field_display_label']}}
                                        @break

                                    @case('product serial no.')
                                        {{$project_custom_fields['product_serial_no']['field_display_label']}}
                                        @break

                                    @case('project name')
                                        {{$project_custom_fields['project_name']['field_display_label']}}
                                        @break

                                    @case('job history')
                                        {{$project_custom_fields['job_history']['field_display_label']}}
                                        @break
                                    @case('source of issue')
                                        {{$project_custom_fields['source_of_issue']['field_display_label']}}
                                        @break
                                    @default
                                        {{ucwords($value->column_name)}}
                                @endswitch
                            </a>
                        @endforeach
                        @if($project_table_columns_names_enabled_by_user>0)
                        <a href="{{route('complaint.closed.table.column.defaultviewProject')}}" class="btn btn-sm btn-alt-primary" style="font-size:12px;margin-top:10px;margin-left:25px;margin-bottom:10px;">
                            Reset to default table
                        </a>
                        @endif
                    </div>
                </li>
            @endif
        </ol>
    </nav>
    <!-- Dynamic Table Full -->
    <div class="block block-rounded">
        <div class="block-content block-content-full">
            <ul class="nav nav-tabs nav-tabs-alt" role="tablist">
                @if ($data3->count() > 0)
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTemplate->title == 'Product Template' ? 'active' : '' }}" href="#tab-1" data-toggle="tab" onclick="toggleTab('#tab-1')">Register{{ $dataProject->count() > 0 ? '(Product)' : ''}}</a>
                    </li>
                @endif
                @if ($dataProject->count() > 0)
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTemplate->title == 'Project Template' ? 'active' : '' }}" href="#tab-2" data-toggle="tab" onclick="toggleTab('#tab-2')">Register{{ $data3->count() > 0 ? '(Project)' : ''}}</a>
                    </li>
                @endif
            </ul>
            <div id="content" class="tab-content mt-4">
                <div  class="tab-pane {{ $activeTemplate->title == 'Product Template' ? 'active' : '' }} table-responsive scrollable" id="tab-1" role="tabpanel">
                    <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full" id="tabledata">
                        <thead >
                            <tr style="position: sticky; top: 0;z-index:5;">
                                <td id='sno' class="text-center titleheading  px-1" style="background-color:#fff;font-size:10px;font-weight:bold !important;cursor:pointer;" draggable="false" data-orderable="false">
                                    S.No
                                </td>
                                <td class="text-center titleheading  titleheading2 px-1" draggable="false" style="background-color:#fff;font-size:10px;font-weight:bold !important;cursor:pointer;" data-orderable="false">
                                    Complaint ID
                                </td>
                                <td class="text-center titleheading titleheading2 px-1" draggable="false" style="background-color:#fff;font-size:10px;font-weight:bold !important;cursor:pointer;" data-orderable="false">
                                    Title
                                </td>
                                @foreach($table_columns_active as $active)
                                    @php
                                        switch($active->column_name){
                                            case 'customer':
                                                $label = $product_custom_fields['customer']['field_display_label'] ?? $active->column_name;
                                                break;

                                            case 'product':
                                                $label = $product_custom_fields['product']['field_display_label'] ?? $active->column_name;
                                                break;

                                            case 'customer phone':
                                                $label = $product_custom_fields['customerphone']['field_display_label'] ?? $active->column_name;
                                                break;

                                            case 'customer email':
                                                $label = $product_custom_fields['customeremail']['field_display_label'] ?? $active->column_name;
                                                break;

                                            case 'job order no.':
                                                $label = $product_custom_fields['job_order_no']['field_display_label'] ?? $active->column_name;
                                                break;

                                            case 'product serial no.':
                                                $label = $product_custom_fields['product_serial_no']['field_display_label'] ?? $active->column_name;
                                                break;

                                            case 'project name':
                                                $label = $product_custom_fields['project_name']['field_display_label'] ?? $active->column_name;
                                                break;

                                            case 'job history':
                                                $label = $product_custom_fields['job_history']['field_display_label'] ?? $active->column_name;
                                                break;

                                            default:
                                                $label = $active->column_name;
                                        }
                                    @endphp

                                    @if($active->enabled_by_user!==0)
                                        <td data-orderable="false" class="text-center titleheading titleheading2 px-1" style="background-color:#fff;font-size:10px;font-weight:bold !important;"
                                            data-column-id="{{ $active->id }}"
                                            data-column-name="{{$active->column_name}}"
                                            onmouseover="return removecolumn('{{$active->id}}')">
                                            {{$label}}
                                        </td>
                                    @else
                                        <td
                                            id="{{ $active->column_name==='issue authenticity' ? 'issueauthhead' : ''}}"
                                            class="text-center titleheading titleheading2 px-1"
                                            style="background-color:#fff;font-size:10px;font-weight:bold !important;"
                                            data-orderable="false"
                                            data-column-id="{{ $active->id }}"
                                            data-column-name="{{$active->column_name}}">
                                            {{$active->column_name}}
                                        </td>
                                    @endif
                                @endforeach
                                <td class="text-center titleheading titleheading2 px-1" draggable="false" style="background-color:#fff;font-size:10px;font-weight:bold !important;cursor:pointer;" data-orderable="false">
                                    Process Stage
                                </td>
                                <td class="text-center titleheading titleheading2 px-1" draggable="false" style="background-color:#fff;font-size:10px;font-weight:bold !important;cursor:pointer;" data-orderable="false">
                                    Action Status
                                </td>
                                <td class="text-center px-1 titleheading" style="background-color:#fff;cursor:pointer;" data-orderable="false" draggable="false">Actions</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no=0;?>
                            @foreach($data3 as $value)
                            <?php
                                $rr=$value->id;
                                $date = $value->tardate;
                                $today = Date("Y-m-d");
                                $closedate=date('Y-m-d',strtotime($value->closed_at));
                                $product = DB::table('products')->whereIn('id',explode(',',$value->product))->pluck('product')->toArray();
                                $customer = $customersData[$value->customer] ?? $value->customer;
                                $depname = $departmentsData[$value->dept] ?? '--';
                                $res = $responsibilitiesData[$value->responsibility]??"--";
                                $raisedby = $raisedbyRecordsData[$value->addedby]??"--";
                                $receivedby = $receivedbyRecordsData[$value->employee]??'--';
                                $plantname = $plantsData[$value->plant]??"--";
                                $categoryComplaint = isset($value->complaint_category_id) ? $categoryNamesData[$value->complaint_category_id] : "--";
                                $subdiv = $subdivisionsData[$value->area_id]??"--";
                                $div = $divisionsData[$value->division_id]??"--";
                                $leadername =$usersData[$value->team_lead_id]??'--';
                                $reviewedbyname = $usersData[$value->reviewed_by]??'--';
                                $closedbyname = $usersData[$value->closed_by]??'--';
                                $fieldRules = json_decode($value->complaint_template_section_field_rules, true);
                            ?>
                            <tr data-id="{{$value->id}}">
                                <td class="text-center px-1" style="font-size:10px;">{{ $no = $no + 1 }}</td>
                                <td class="text-center px-1" style="font-size:10px;">
                                    {{$value->trackno}}
                                </td>
                                <td class="text-center font-w600 px-1" style="font-size:10px;word-wrap: break-word;">
                                    <a href="{{ route('complaint.detail', $value->id) }}"  data-toggle="tooltip" title="Click to view details" data-position="top">{{$value->title}}</a>
                                </td>
                                @foreach($table_columns_active as $column_c)
                                    @switch($column_c->column_name)
                                        @case('customer')
                                            <td class="text-center px-1" style="font-size:10px;">
                                                {{$customer ? $customer : $value->customer}}
                                                <span style="display: none;">
                                                    {{$customer ? $customer."_f" : $value->customer."_f"}}
                                                </span>
                                                <?php
                                                    $tag = App\Helpers\Complaint\ComplaintHelper::getTag($fieldRules, $product_custom_fields, 'customer', 'Customer Name');
                                                ?>
                                                @if ($tag)
                                                    <br>
                                                    <span style="
                                                        display:inline-block;
                                                        font-size:9px;
                                                        color:#555;
                                                        background:#f2f2f2;
                                                        padding:2px 6px;
                                                        border-radius:4px;
                                                        margin-top:2px;
                                                    ">
                                                        {{ $tag }}
                                                    </span>
                                                @endif
                                            </td>

                                            @break
                                        @case('product')
                                            <td class="text-center px-1" style="font-size:10px;">
                                                @if($value->product)
                                                    @foreach($product as $item)
                                                        <span style="display: none;">{{ $item . "_f" }}</span>
                                                    @endforeach
                                                @endif
                                                <em class="text-muted " style="font-size:10px;">{{$product ? implode(',',$product) : $value->product}}</em>
                                                <?php
                                                    $tag = App\Helpers\Complaint\ComplaintHelper::getTag($fieldRules, $product_custom_fields, 'product', 'Product / Service');
                                                ?>
                                                @if ($tag)
                                                    <br>
                                                    <span style="
                                                        display:inline-block;
                                                        font-size:9px;
                                                        color:#555;
                                                        background:#f2f2f2;
                                                        padding:2px 6px;
                                                        border-radius:4px;
                                                        margin-top:2px;
                                                    ">
                                                        {{ $tag }}
                                                    </span>
                                                @endif

                                            </td>
                                            @break
                                        @case('type')
                                            <td class="text-center px-1" style="font-size:12px;">
                                                @if($value->type=="Low")
                                                <span class="status status-success">{{$value->type}}</span>
                                                @elseif($value->type=="Moderate")
                                                <span class="status status-warning">{{$value->type}}</span>
                                                @elseif($value->type=="High")
                                                <span class="status status-red">{{$value->type}}</span>
                                                @else
                                                <span>--</span>
                                                @endif
                                            </td>
                                            @break
                                        @case('department')
                                            <td class="text-center px-1" style="font-size:10px;">
                                                <em class="text-muted">{{ $depname ? $depname : $value->dept}}</em>
                                                <span style="display: none;">{{ $depname ? $depname."_f" : $value->dept."_f" }}</span>
                                            </td>
                                            @break
                                        @case('responsibility')
                                            <td class="text-center px-1" style="font-size:10px;">
                                                {{$res}}
                                                <span style="display: none;">{{ $res . "_f" }}</span>
                                            </td>
                                            @break
                                        @case('due date')
                                            <td class="text-center px-1" style="font-size:10px;">
                                                {{$value->tardate ? date('d-m-Y', strtotime($value->tardate)) : '--'}}
                                            </td>
                                            @break
                                        @case('raised by')
                                            @if(in_array('raised by',$table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{$raisedby }}
                                                    <span style="display: none;">{{ $raisedby . "_f" }}</span>
                                                </td>
                                            @endif
                                            @break
                                        @case('complaint received by')
                                            @if(in_array('complaint received by',$table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{ $receivedby??"--" }}
                                                    <span style="display: none;">{{ $receivedby . "_f" }}</span>
                                                </td>
                                            @endif
                                            @break
                                        @case('complaint received date')
                                            @if(in_array('complaint received date',$table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{$value->crdate ? date('d-m-Y', strtotime($value->crdate)) : '--'}}
                                                </td>
                                            @endif
                                            @break
                                        @case('mode of complaint')
                                            @if(in_array('mode of complaint',$table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;word-wrap: break-word;">
                                                    {{ ucfirst($value->mode_of_complaint)??"--"  }}
                                                </td>
                                            @endif
                                            @break
                                        @case('customer phone')
                                            @if(in_array('customer phone',$table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{ $value->customer_phone ?? "--"}}
                                                    <?php
                                                        $tag = App\Helpers\Complaint\ComplaintHelper::getTag($fieldRules, $product_custom_fields, 'customerphone', 'Customer Phone');
                                                    ?>
                                                    @if ($tag)
                                                        <br>
                                                        <span style="
                                                            display:inline-block;
                                                            font-size:9px;
                                                            color:#555;
                                                            background:#f2f2f2;
                                                            padding:2px 6px;
                                                            border-radius:4px;
                                                            margin-top:2px;
                                                        ">
                                                            {{$tag}}
                                                        </span>
                                                    @endif

                                                </td>
                                            @endif
                                            @break
                                        @case('customer email')
                                            @if(in_array('customer email',$table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{ $value->customer_email }}
                                                    <?php
                                                        $tag = App\Helpers\Complaint\ComplaintHelper::getTag($fieldRules, $product_custom_fields, 'customeremail', 'Customer Email');
                                                    ?>
                                                    @if ($tag)
                                                        <br>
                                                        <span style="
                                                            display:inline-block;
                                                            font-size:9px;
                                                            color:#555;
                                                            background:#f2f2f2;
                                                            padding:2px 6px;
                                                            border-radius:4px;
                                                            margin-top:2px;
                                                        ">
                                                            {{ $tag }}
                                                        </span>
                                                    @endif
                                                </td>
                                            @endif
                                            @break
                                        @case('sale order no.')
                                            @if(in_array('sale order no.',$table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{ $value->sale_order_no ?? "--"}}
                                                    <?php
                                                        $tag = App\Helpers\Complaint\ComplaintHelper::getTag($fieldRules, $product_custom_fields, 'saleorder', 'Sale Order Number');
                                                    ?>
                                                    @if ($tag)
                                                        <br>
                                                        <span style="
                                                            display:inline-block;
                                                            font-size:9px;
                                                            color:#555;
                                                            background:#f2f2f2;
                                                            padding:2px 6px;
                                                            border-radius:4px;
                                                            margin-top:2px;
                                                        ">
                                                            {{ $tag }}
                                                        </span>
                                                    @endif
                                                </td>
                                            @endif
                                            @break
                                        @case('delivery no.')
                                            @if(in_array('delivery no.',$table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{ $value->delivery_no ??"--"}}
                                                    <?php
                                                        $tag = App\Helpers\Complaint\ComplaintHelper::getTag($fieldRules, $product_custom_fields, 'deliveryno', 'Delivery Number');
                                                    ?>
                                                    @if ($tag)
                                                        <br>
                                                        <span style="
                                                            display:inline-block;
                                                            font-size:9px;
                                                            color:#555;
                                                            background:#f2f2f2;
                                                            padding:2px 6px;
                                                            border-radius:4px;
                                                            margin-top:2px;
                                                        ">
                                                            {{ $tag }}
                                                        </span>
                                                    @endif
                                                </td>
                                            @endif
                                            @break
                                        @case('delivery date')
                                            @if(in_array('delivery date',$table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{ $value->delivery_date ?date('d-m-Y',strtotime($value->delivery_date)): "--"}}
                                                    <?php
                                                        $tag = App\Helpers\Complaint\ComplaintHelper::getTag($fieldRules, $product_custom_fields, 'deliverydate', 'Delivery Date');
                                                    ?>
                                                    @if ($tag)
                                                        <br>
                                                        <span style="
                                                            display:inline-block;
                                                            font-size:9px;
                                                            color:#555;
                                                            background:#f2f2f2;
                                                            padding:2px 6px;
                                                            border-radius:4px;
                                                            margin-top:2px;
                                                        ">
                                                            {{ $tag }}
                                                        </span>
                                                    @endif
                                                </td>
                                            @endif
                                            @break
                                        @case('issue authenticity')
                                            @if(in_array('issue authenticity',$table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{ $value->issue_authenticity ??"--" }}
                                                    <span class="d-none">{{$value->issue_authenticity ? 'f_'.$value->issue_authenticity:''}}</span>
                                                </td>
                                            @endif
                                            @break
                                        @case('plant')
                                            @if(in_array('plant',$table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{ $plantname ?? "--"}}
                                                    <span class="d-none">{{$plantname ? $plantname."_f":''}}</span>
                                                </td>
                                            @endif
                                            @break
                                        @case('vendor')
                                            @if(in_array('vendor',$table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{ isset($value->vendor) ? $value->vendor['vendor_name'] : ''}}
                                                </td>
                                            @endif
                                            @break
                                        @case('division')
                                            @if(in_array('division',$table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{ $div ??"--" }}
                                                    <span class="d-none">{{$div ? $div."_f":''}}</span>
                                                </td>
                                            @endif
                                            @break
                                        @case('subdivision')
                                            @if(in_array('subdivision',$table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{ $subdiv ??"--" }}
                                                    <span class="d-none">{{$subdiv ? $subdiv."_f":''}}</span>
                                                </td>
                                            @endif
                                            @break
                                        @case('financial loss')
                                            @if(in_array('financial loss',$table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{ $value->financial_loss??"--" }}
                                                </td>
                                            @endif
                                            @break
                                        @case('potential cost saving')
                                            @if(in_array('potential cost saving',$table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{ $value->potential_cost_savings??"--" }}
                                                </td>
                                            @endif
                                            @break
                                        @case('email reminder for action')
                                            @if(in_array('email reminder for action',$table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{$value->eremainder??"--"}}
                                                </td>
                                            @endif
                                            @break
                                        @case('investigation team lead')
                                            @if(in_array('investigation team lead',$table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{ $leadername??"--" }}
                                                    <span class="d-none">{{$leadername ? $leadername."_f":''}}</span>
                                                </td>
                                            @endif
                                            @break
                                        @case('investigation target date')
                                            @if(in_array('investigation target date',$table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{ $value->investigation_date ? date('d-m-Y', strtotime($value->investigation_date)):"--" }}
                                                </td>
                                            @endif
                                            @break
                                        @case('added date')
                                            @if(in_array('added date',$table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{ $value->created_at?date('d-m-Y',strtotime($value->created_at)):"--" }}
                                                </td>
                                            @endif
                                            @break
                                        @case('reviewed by')
                                            @if(in_array('reviewed by',$table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{ $reviewedbyname?$reviewedbyname:"--" }}
                                                    <span class="d-none">{{$reviewedbyname ? $reviewedbyname."_f":''}}</span>
                                                </td>
                                            @endif
                                            @break
                                        @case('reviewed date')
                                            @if(in_array('reviewed date',$table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{$value->reviewedat?date('d-m-Y',strtotime($value->reviewedat)):"--"}}
                                                </td>
                                            @endif
                                            @break
                                        @case('closure reviewed by')
                                            @if(in_array('closure reviewed by',$table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{ $closedbyname?$closedbyname:"--" }}
                                                    <span class="d-none">{{$closedbyname ? $closedbyname."_f":''}}</span>
                                                </td>
                                            @endif
                                            @break
                                        @case('closure reviewed date')
                                            @if(in_array('closure reviewed date',$table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{$value->closed_at?date('d-m-Y',strtotime($value->closed_at)):"--"}}
                                                </td>
                                            @endif
                                            @break
                                        @default
                                            @break
                                    @endswitch
                                @endforeach
                                <td class="text-center px-1" style="font-size:12px;">
                                    @if($closedate <= $date)
                                    <span class="status status-success">{{$value->status}}</span>
                                    @elseif( $closedate > $date)
                                    <span class="status status-warning">{{$value->status}}</span>
                                    @endif
                                </td>
                                <td class="text-center px-1" style="font-size:10px;">
                                    <div class="progress mb-1" style="height: 5px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{$value->average_percent}}%;" aria-valuenow="{{$value->average_percent}}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <p class="fs-xs fw-semibold mb-0">{{$value->average_percent}}%</p>
                                </td>
                                <div class="dropdown font-size-sm">
                                    <td>
                                        <div class="btn-group m-auto d-flex justify-content-center">
                                            <a href="#" data-route="{{$value->id}}" class="btn btn-sm btn-alt-primary btn-view-detail" data-id="{{ $value->id }}" data-toggle="tooltip" data-placement="top" title="View">
                                                <i class="far fa-eye text-center " style="font-size:10px;cursor:pointer"></i>
                                            </a>
                                        </div>
                                    </td>
                                </div>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane {{ $activeTemplate->title == 'Project Template' ? 'active' : '' }} table-responsive-2 scrollable" id="tab-2" role="tabpanel">
                    <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full" id="tabledata2">
                        <thead >
                            <tr style="position: sticky; top: 0;z-index:5;">
                                <td class="text-center px-1" data-orderable="false" style="background-color:#fff;"></td>
                                <td class="text-center titleheading  px-1" style="background-color:#fff;font-size:10px;font-weight:bold !important;cursor:pointer;" draggable="false" data-orderable="false">
                                    S.No
                                </td>
                                <td class="text-center titleheading  titleheading2 px-1" draggable="false" style="background-color:#fff;font-size:10px;font-weight:bold !important;cursor:pointer;" data-orderable="false">
                                    Complaint ID
                                </td>
                                <td class="text-center titleheading titleheading2 px-1" draggable="false" style="background-color:#fff;font-size:10px;font-weight:bold !important;cursor:pointer;" data-orderable="false">
                                    Title
                                </td>
                                @foreach($project_table_columns_active as $active)
                                    @php
                                        $label = $active->column_name;

                                        switch($active->column_name){
                                            case 'customer':
                                                $label = $project_custom_fields['customer']['field_display_label'] ?? $active->column_name;
                                                break;

                                            case 'product':
                                                $label = $project_custom_fields['product']['field_display_label'] ?? $active->column_name;
                                                break;

                                            case 'customer phone':
                                                $label = $project_custom_fields['customerphone']['field_display_label'] ?? $active->column_name;
                                                break;

                                            case 'customer email':
                                                $label = $project_custom_fields['customeremail']['field_display_label'] ?? $active->column_name;
                                                break;

                                            case 'job order no.':
                                                $label = $project_custom_fields['job_order_no']['field_display_label'] ?? $active->column_name;
                                                break;

                                            case 'product serial no.':
                                                $label = $project_custom_fields['product_serial_no']['field_display_label'] ?? $active->column_name;
                                                break;

                                            case 'project name':
                                                $label = $project_custom_fields['project_name']['field_display_label'] ?? $active->column_name;
                                                break;

                                            case 'job history':
                                                $label = $project_custom_fields['job_history']['field_display_label'] ?? $active->column_name;
                                                break;
                                            case 'source of issue':
                                                $label = $project_custom_fields['source_of_issue']['field_display_label'] ?? $active->column_name;
                                                break;
                                            default:
                                                $label = $active->column_name;
                                        }
                                    @endphp

                                    <td
                                        data-orderable="false"
                                        id="{{ $active->column_name==='issue authenticity' ? 'issueauthhead_project' : ''}}"
                                        class="text-center titleheading {{ $active->column_name==='S.No' ? '' : 'titleheading2'}} px-1"
                                        style="background-color:#fff;font-size:10px;font-weight:bold !important;"
                                        data-column-id="{{ $active->id }}"
                                        data-column-name="{{$active->column_name}}"
                                        @if($active->enabled_by_user!==0)
                                            onmouseover="return removecolumn('{{ $active->id }}')"
                                        @endif
                                    >
                                        {{ $label }}
                                    </td>
                                @endforeach
                                <td id="processstageshead2" class="text-center titleheading titleheading2 px-1" draggable="false" style="background-color:#fff;font-size:10px;font-weight:bold !important;cursor:pointer;" data-orderable="false">
                                    Process Stage
                                </td>
                                <td class="text-center titleheading titleheading2 px-1" draggable="false" style="background-color:#fff;font-size:10px;font-weight:bold !important;cursor:pointer;" data-orderable="false">
                                    Action Status
                                </td>
                                <td class="text-center px-1 titleheading" style="background-color:#fff;cursor:pointer;" data-orderable="false" draggable="false">Actions</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no=0;?>
                            @foreach($dataProject as $value)
                            <?php
                                $rr=$value->id;
                                $date = $value->tardate;
                                $today = Date("Y-m-d");
                                $closedate=date('Y-m-d',strtotime($value->closed_at));
                                $product = DB::table('products')->whereIn('id',explode(',',$value->product))->pluck('product')->toArray();
                                $customer = $customersData[$value->customer] ?? $value->customer;
                                $depname = $departmentsData[$value->dept] ?? '--';
                                $res = $responsibilitiesData[$value->responsibility]??"--";
                                $raisedby = $raisedbyRecordsData[$value->addedby]??"--";
                                $receivedby = $receivedbyRecordsData[$value->employee]??'--';
                                $plantname = $plantsData[$value->plant]??"--";
                                $categoryComplaint = isset($value->complaint_category_id) ? $categoryNamesData[$value->complaint_category_id] : "--";
                                $subdiv = $subdivisionsData[$value->area_id]??"--";
                                $div = $divisionsData[$value->division_id]??"--";
                                $leadername =$usersData[$value->team_lead_id]??'--';
                                $reviewedbyname = $usersData[$value->reviewed_by]??'--';
                                $closedbyname = $usersData[$value->closed_by]??'--';
                                $fieldRules = json_decode($value->complaint_template_section_field_rules, true);
                            ?>
                            <tr data-id="{{$value->id}}">
                                <td><input class="text-center " style="font-size:10px;" type="checkbox" id="{{ $value->id}}" name="id[]"></td>
                                <td class="text-center px-1" style="font-size:10px;">{{ $no = $no + 1 }}</td>
                                <td class="text-center px-1" style="font-size:10px;">
                                    {{$value->trackno}}
                                </td>
                                <td class="text-center font-w600 px-1" style="font-size:10px;word-wrap: break-word;">
                                    <a href="{{ route('complaint.detail', $value->id) }}"  data-toggle="tooltip" title="Click to view details" data-position="top">{{$value->title}}</a>
                                </td>
                                @foreach($project_table_columns_active as $column_c)
                                    @switch($column_c->column_name)
                                        @case('customer')
                                            <td class="text-center px-1" style="font-size:10px;">
                                                {{$customer ? $customer : $value->customer}}
                                                <?php
                                                    $tag = App\Helpers\Complaint\ComplaintHelper::getTag($fieldRules, $project_custom_fields, 'customer', 'Customer Name');
                                                ?>
                                                @if ($tag)
                                                    <br>
                                                    <span style="
                                                        display:inline-block;
                                                        font-size:9px;
                                                        color:#555;
                                                        background:#f2f2f2;
                                                        padding:2px 6px;
                                                        border-radius:4px;
                                                        margin-top:2px;
                                                    ">
                                                        {{ $tag }}
                                                    </span>
                                                @endif
                                            </td>
                                            @break
                                        @case('type')
                                            <td class="text-center px-1" style="font-size:12px;">
                                                @if($value->type=="Low")
                                                <span class="badge badge-success">{{$value->type}}</span>
                                                @elseif($value->type=="Moderate")
                                                <span class="badge badge-warning">{{$value->type}}</span>
                                                @elseif($value->type=="High")
                                                <span class="badge badge-danger">{{$value->type}}</span>
                                                @else
                                                <span>--</span>
                                                @endif
                                            </td>
                                            @break
                                        @case('department')
                                            <td class="text-center px-1" style="font-size:10px;">
                                                <em class="text-muted">{{ $depname ? $depname : $value->dept}}</em>
                                                <span style="display: none;">{{ $depname ? $depname."_f" : $value->dept."_f" }}</span>
                                            </td>
                                            @break
                                        @case('responsibility')
                                            <td class="text-center px-1" style="font-size:10px;">
                                                {{$res}}
                                                <span style="display: none;">{{ $res . "_f" }}</span>
                                            </td>
                                            @break
                                        @case('due date')
                                            <td class="text-center px-1" style="font-size:10px;">
                                                {{$value->tardate ? date('d-m-Y', strtotime($value->tardate)) : '--'}}
                                            </td>
                                            @break
                                        @case('issue authenticity')
                                            @if(in_array('issue authenticity',$table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{ $value->issue_authenticity ??"--" }}
                                                    <span class="d-none">{{$value->issue_authenticity ? 'f_'.$value->issue_authenticity:''}}</span>
                                                </td>
                                            @endif
                                            @break
                                        @case('source of issue')
                                            @if(in_array('source of issue',$project_table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{ isset($value->source_of_issue) ? $value->source_of_issue : '' }}
                                                    <?php
                                                        $tag = App\Helpers\Complaint\ComplaintHelper::getTag($fieldRules, $project_custom_fields, 'source_of_issue', 'Source of Issue');
                                                    ?>
                                                    @if ($tag)
                                                        <br>
                                                        <span style="
                                                            display:inline-block;
                                                            font-size:9px;
                                                            color:#555;
                                                            background:#f2f2f2;
                                                            padding:2px 6px;
                                                            border-radius:4px;
                                                            margin-top:2px;
                                                        ">
                                                            {{ $tag }}
                                                        </span>
                                                    @endif
                                                </td>
                                            @endif
                                            @break
                                        @case('raisedby')
                                            @if(in_array('raisedby',$project_table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{$raisedby }}
                                                    <span style="display: none;">{{ $raisedby . "_f" }}</span>
                                                </td>
                                            @endif
                                            @break
                                        @case('added date')
                                            @if(in_array('added date',$project_table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{ $value->created_at?date('d-m-Y',strtotime($value->created_at)):"--" }}
                                                </td>
                                            @endif
                                            @break
                                        @case('complaint received by')
                                            @if(in_array('complaint received by',$project_table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{ $receivedby??"--" }}
                                                    <span style="display: none;">{{ $receivedby . "_f" }}</span>
                                                </td>
                                            @endif
                                            @break
                                        @case('complaint received date')
                                            @if(in_array('complaint received date',$project_table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{$value->crdate ? date('d-m-Y', strtotime($value->crdate)) : '--'}}
                                                </td>
                                            @endif
                                            @break
                                        @case('mode of complaint')
                                            @if(in_array('mode of complaint',$project_table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;word-wrap: break-word;">
                                                    {{ ucfirst($value->mode_of_complaint)??"--"  }}
                                                </td>
                                            @endif
                                            @break
                                        @case('customer phone')
                                            @if(in_array('customer phone',$project_table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{ $value->customer_phone ?? "--"}}
                                                    <?php
                                                        $tag = App\Helpers\Complaint\ComplaintHelper::getTag($fieldRules, $project_custom_fields, 'customerphone', 'Customer Phone');
                                                    ?>
                                                    @if ($tag)
                                                        <br>
                                                        <span style="
                                                            display:inline-block;
                                                            font-size:9px;
                                                            color:#555;
                                                            background:#f2f2f2;
                                                            padding:2px 6px;
                                                            border-radius:4px;
                                                            margin-top:2px;
                                                        ">
                                                            {{$tag}}
                                                        </span>
                                                    @endif
                                                </td>
                                            @endif
                                            @break
                                        @case('customer email')
                                            @if(in_array('customer email',$project_table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{ $value->customer_email }}
                                                    <?php
                                                        $tag = App\Helpers\Complaint\ComplaintHelper::getTag($fieldRules, $project_custom_fields, 'customeremail', 'Customer Email');
                                                    ?>
                                                    @if ($tag)
                                                        <br>
                                                        <span style="
                                                            display:inline-block;
                                                            font-size:9px;
                                                            color:#555;
                                                            background:#f2f2f2;
                                                            padding:2px 6px;
                                                            border-radius:4px;
                                                            margin-top:2px;
                                                        ">
                                                            {{ $tag }}
                                                        </span>
                                                    @endif
                                                </td>
                                            @endif
                                            @break
                                        @case('product')
                                            <td class="text-center px-1" style="font-size:10px;">
                                                @if($value->product)
                                                    @foreach($product as $item)
                                                        <span style="display: none;">{{ $item . "_f" }}</span>
                                                    @endforeach
                                                @endif
                                                <em class="text-muted " style="font-size:10px;">{{$product ? implode(',',$product) : $value->product}}</em>
                                                <?php
                                                    $tag = App\Helpers\Complaint\ComplaintHelper::getTag($fieldRules, $project_custom_fields, 'product', 'Product / Service');
                                                ?>
                                                @if ($tag)
                                                    <br>
                                                    <span style="
                                                        display:inline-block;
                                                        font-size:9px;
                                                        color:#555;
                                                        background:#f2f2f2;
                                                        padding:2px 6px;
                                                        border-radius:4px;
                                                        margin-top:2px;
                                                    ">
                                                        {{ $tag }}
                                                    </span>
                                                @endif
                                            </td>
                                            @break
                                        @case('job order no.')
                                            @if(in_array('job order no.',$project_table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{ $value->job_order_no ?? "--"}}
                                                    <?php
                                                        $tag = App\Helpers\Complaint\ComplaintHelper::getTag($fieldRules, $project_custom_fields, 'job_order_no', 'Job Order Number');
                                                    ?>
                                                    @if ($tag)
                                                        <br>
                                                        <span style="
                                                            display:inline-block;
                                                            font-size:9px;
                                                            color:#555;
                                                            background:#f2f2f2;
                                                            padding:2px 6px;
                                                            border-radius:4px;
                                                            margin-top:2px;
                                                        ">
                                                            {{ $tag}}
                                                        </span>
                                                    @endif
                                                </td>
                                            @endif
                                            @break
                                        @case('product serial no.')
                                            @if(in_array('product serial no.',$project_table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{ $value->product_serial_no ??"--"}}
                                                    <?php
                                                        $tag = App\Helpers\Complaint\ComplaintHelper::getTag($fieldRules, $project_custom_fields, 'product_serial_no', 'Product Serial Number');
                                                    ?>
                                                    @if ($tag)
                                                        <br>
                                                        <span style="
                                                            display:inline-block;
                                                            font-size:9px;
                                                            color:#555;
                                                            background:#f2f2f2;
                                                            padding:2px 6px;
                                                            border-radius:4px;
                                                            margin-top:2px;
                                                        ">
                                                            {{ $tag }}
                                                        </span>
                                                    @endif
                                                </td>
                                            @endif
                                            @break
                                        @case('project name')
                                            @if(in_array('project name',$project_table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{ $value->project_name ?? "--"}}
                                                    <?php
                                                        $tag = App\Helpers\Complaint\ComplaintHelper::getTag($fieldRules, $project_custom_fields, 'project_name', 'Project Name');
                                                    ?>
                                                    @if ($tag)
                                                        <br>
                                                        <span style="
                                                            display:inline-block;
                                                            font-size:9px;
                                                            color:#555;
                                                            background:#f2f2f2;
                                                            padding:2px 6px;
                                                            border-radius:4px;
                                                            margin-top:2px;
                                                        ">
                                                            {{$tag}}
                                                        </span>
                                                    @endif
                                                </td>
                                            @endif
                                            @break
                                        @case('warranty coverage')
                                            @if(in_array('warranty coverage',$project_table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    @if (isset($value->warranty_coverage) && $value->warranty_coverage)
                                                        @if ($value->warranty_coverage == 'Under Warranty')
                                                            <span class="status status-success" style="font-size:10px;">{{ $value->warranty_coverage }}</span>
                                                        @elseif ($value->warranty_coverage == 'Out of Warranty')
                                                            <span class="status status-red" style="font-size:10px;">{{ $value->warranty_coverage }}</span>
                                                        @endif
                                                    @else
                                                        <span>--</span>
                                                    @endif
                                                </td>
                                            @endif
                                            @break
                                        @case('plant')
                                            @if(in_array('plant',$project_table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{ $plantname ?? "--"}}
                                                    <span class="d-none">{{$plantname ? $plantname."_f":''}}</span>
                                                </td>
                                            @endif
                                            @break
                                        @case('complaint category')
                                            @if(in_array('complaint category',$project_table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{ $categoryComplaint}}
                                                </td>
                                            @endif
                                            @break
                                        @case('vendor')
                                            @if(in_array('vendor',$project_table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{ isset($value->vendor) ? $value->vendor['vendor_name'] : ''}}
                                                </td>
                                            @endif
                                            @break
                                        @case('division')
                                            @if(in_array('division',$project_table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{ $div ??"--" }}
                                                    <span class="d-none">{{$div ? $div."_f":''}}</span>
                                                </td>
                                            @endif
                                            @break
                                        @case('subdivision')
                                            @if(in_array('subdivision',$project_table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{ $subdiv ??"--" }}
                                                    <span class="d-none">{{$subdiv ? $subdiv."_f":''}}</span>
                                                </td>
                                            @endif
                                            @break
                                        @case('financial loss')
                                            @if(in_array('financial loss',$project_table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{ $value->financial_loss??"--" }}
                                                </td>
                                            @endif
                                            @break
                                        @case('potential cost saving')
                                            @if(in_array('potential cost saving',$project_table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{ $value->potential_cost_savings??"--" }}
                                                </td>
                                            @endif
                                            @break
                                        @case('email reminder for action')
                                            @if(in_array('email reminder for action',$project_table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{$value->eremainder??"--"}}
                                                </td>
                                            @endif
                                            @break
                                        @case('investigation team lead')
                                            @if(in_array('investigation team lead',$project_table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{ $leadername??"--" }}
                                                    <span class="d-none">{{$leadername ? $leadername."_f":''}}</span>
                                                </td>
                                            @endif
                                            @break
                                        @case('investigation target date')
                                            @if(in_array('investigation target date',$project_table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{ $value->investigation_date ? date('d-m-Y', strtotime($value->investigation_date)):"--" }}
                                                </td>
                                            @endif
                                            @break
                                            @case('added date')
                                            @if(in_array('added date',$project_table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{ $value->created_at?date('d-m-Y',strtotime($value->created_at)):"--" }}
                                                </td>
                                            @endif
                                            @break
                                        @case('reviewed by')
                                            @if(in_array('reviewed by',$project_table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{ $reviewedbyname?$reviewedbyname:"--" }}
                                                    <span class="d-none">{{$reviewedbyname ? $reviewedbyname."_f":''}}</span>
                                                </td>
                                            @endif
                                            @break
                                        @case('reviewed date')
                                            @if(in_array('reviewed date',$project_table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{$value->reviewedat?date('d-m-Y',strtotime($value->reviewedat)):"--"}}
                                                </td>
                                            @endif
                                            @break
                                        @case('closure reviewed by')
                                            @if(in_array('closure reviewed by',$project_table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{ $closedbyname?$closedbyname:"--" }}
                                                    <span class="d-none">{{$closedbyname ? $closedbyname."_f":''}}</span>
                                                </td>
                                            @endif
                                            @break
                                        @case('closure reviewed date')
                                            @if(in_array('closure reviewed date',$project_table_columns_names_active))
                                                <td class="text-center px-1" style="font-size:10px;">
                                                    {{$value->closed_at?date('d-m-Y',strtotime($value->closed_at)):"--"}}
                                                </td>
                                            @endif
                                            @break
                                        @default
                                        @break
                                    @endswitch
                                @endforeach
                                <td class="text-center px-1" style="font-size:12px;">
                                    @if($closedate <= $date)
                                    <span class="badge badge-success">{{$value->status}}</span>
                                    @elseif( $closedate > $date)
                                    <span class="badge badge-warning">{{$value->status}}</span>
                                    @endif
                                </td>
                                <td class="text-center px-1" style="font-size:10px;">
                                    <div class="progress mb-1" style="height: 5px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{$value->average_percent}}%;" aria-valuenow="{{$value->average_percent}}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <p class="fs-xs fw-semibold mb-0">{{$value->average_percent}}%</p>
                                </td>
                                <div class="dropdown font-size-sm">
                                    <td>
                                        <div class="btn-group m-auto d-flex justify-content-center">
                                            <a href="#" data-route="{{$value->id}}" class="btn btn-sm btn-alt-primary btn-view-detail" data-id="{{ $value->id }}" data-toggle="tooltip" data-placement="top" title="View">
                                                <i class="far fa-eye text-center " style="font-size:10px;cursor:pointer"></i>
                                            </a>
                                        </div>
                                    </td>
                                </div>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- END Dynamic Table Full -->


    </div>
    <!-- END Page Content -->
</div>
<!-- Column Header Dropdown Menu  starts-->
<div id="dropdownMenucol">
    <form id="remove_column_ajax" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="remove_column_id" id="remove_column_id"/>
        <button type="submit" class="btn btn-sm btn-danger" style="font-size:10px;">
            <i class="fa fa-fw fa-times"></i> Remove field
        </button>
    </form>
</div>
<div class="complaint-area"></div>
<!-- Ends Column Header Dropdown Menu-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="{{ versionedCache('js/datatable_extension_for_closedcomplaint.js') }}"></script>
<script>
    function removecolumn(id){
        $('#remove_column_id').val(id);
    }

    function getColumnIndexByDataColumnName(tableId, columnName) {
        return $(`${tableId} thead td[data-column-name="${columnName}"]`).index();
    }

    function toggleTab(tabName) {
        if(tabName==='#tab-1'){
            $('#configuretable').css('display', 'block');
            $('#configuretableProject').css('display', 'none');
            // $('#tabselected').val('a1');
            // $('#tvalue1').val('tvalue1');
        }else if(tabName==='#tab-2'){
            $('#configuretable').css('display', 'none');
            $('#configuretableProject').css('display', 'block');
            // $('#tabselected').val('a1');
            // $('#tvalue1').val('tvalue1');
        }
    }
    //-----remove datatable column  starts----//
    $(document).ready(function() {
        $('#remove_column_ajax').submit(function(event) {
            event.preventDefault();
            var formData = $(this).serialize();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: '/user/complaint/closed-table/removecolumn',
                data: formData
                ,success: function(response) {
                    if(response.status == 200){
                        window.location.reload();
                    }
                }
                ,error: function(err) {
                    if(err.status === 500) {
                        $('#columnremovaldanger').css('display', 'block');
                    }
                    else if (err.status === 422) {
                        var error_text='';
                        $.each(err.responseJSON.errors, function(i) {
                            $.each(err.responseJSON.errors[i], function(key, val) {
                                error_text += '<li style="font-size:12px;">' + val + '</li>';
                            });
                        });
                        $('#columnremovaldangernotremoved').html(error_text);
                        $('#columnremovaldanger').hide();
                    }
                }
            });
        });
    });

    //---------ends remove datatable column  ----//

</script>
<script>
    $(document).ready(function(){
        $('body').on('click', '.headingselect', function () {
            if (!$(this).find('.filter-content').length) {
                $('.filter-content').hide();
            }
        });
        $("#tabledata").one("preInit.dt", function() {
            let table = $('#tabledata').DataTable();
            var columnIndex = table.column('#issueauthhead').index();
            let allAuthenticity = "{{ $allauthenticity}}".split(',');
            let authenticityOptions = allAuthenticity.map(authenticity => `<option value="${authenticity}">${authenticity}</option>`).join('');
            let authenticityDropdown = $(`
                <select class="headingselect text-center"  style="border:none;-webkit-appearance: none;min-width:fit-content;background-color:#fff;font-weight:600 !important;" id="issueauth_dropdown">
                    <option value="">Issue Authenticity&nbsp;&nbsp;&#9207;</option>
                    ${authenticityOptions}
                </select>
            `);
            $('#issueauthhead').html(authenticityDropdown);
            $("#issueauth_dropdown").on('change', function() {
                let selectedValue = $(this).val();
                let firstOption = $('#issueauth_dropdown option:first');
                if (selectedValue) {
                    table.columns(columnIndex).search("f_"+selectedValue).draw();
                    $(this).addClass('text-primary');
                    $("#issueauth_dropdown option").css('color', 'black');
                } else {
                    table.column(columnIndex).search('').draw();
                    $(this).removeClass('text-primary');
                    $('#issueauth_dropdown').val('')
                }
                firstOption.html('Issue Authenticity&nbsp;&nbsp;&#9207;');
            });
        });
        $("#tabledata2").one("preInit.dt", function() {
            let table = $('#tabledata2').DataTable();
            var columnIndex = table.column('#issueauthhead_project').index();
            let allAuthenticity = "{{ $allauthenticity}}".split(',');
            let authenticityOptions = allAuthenticity.map(authenticity => `<option value="${authenticity}">${authenticity}</option>`).join('');
            let authenticityDropdown = $(`
                <select class="headingselect text-center"  style="border:none;-webkit-appearance: none;min-width:fit-content;background-color:#fff;font-weight:600 !important;" id="issueauth_dropdown_project">
                    <option value="">Issue Authenticity&nbsp;&nbsp;&#9207;</option>
                    ${authenticityOptions}
                </select>
            `);
            $('#issueauthhead_project').html(authenticityDropdown);
            $("#issueauth_dropdown_project").on('change', function() {
                let selectedValue = $(this).val();
                let firstOption = $('#issueauth_dropdown_project option:first');
                if (selectedValue) {
                    table.columns(columnIndex).search("f_"+selectedValue).draw();
                    $(this).addClass('text-primary');
                    $("#issueauth_dropdown_project option").css('color', 'black');
                } else {
                    table.column(columnIndex).search('').draw();
                    $(this).removeClass('text-primary');
                    $('#issueauth_dropdown_project').val('')
                }
                firstOption.html('Issue Authenticity&nbsp;&nbsp;&#9207;');
            });
        });

        const handleScroll = (container, className) => {
            let lastScrollLeft = 0;
            let horizontalScrollActive = false;

            container.addEventListener('scroll', () => {
                const currentScrollLeft = container.scrollLeft;

                if (currentScrollLeft !== 0) {
                    if (!horizontalScrollActive) {
                        container.classList.add(className);
                        horizontalScrollActive = true;
                    }
                } else {
                    container.classList.remove(className);
                    horizontalScrollActive = false;
                }

                lastScrollLeft = currentScrollLeft;
            });
        };

        handleScroll(document.querySelector('.table-responsive'), 'horizontal-scroll-active');
        handleScroll(document.querySelector('.table-responsive-2'), 'horizontal-scroll-active-2');
    });
</script>
<script>
    const viewDetailsModal = (id) => {
        $('.modal.show').modal('hide');
        $.ajax({
            url : window.location.protocol + "//" + window.location.host +`/user/complaint/${id}/show`,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                if (data.success) {
                    $('.complaint-area').html(data.view)
                    const modalEle = $(`#viewModal${data.id}`);
                    modalEle.modal('show');
                } else {
                    alert('Data is not found');
                }
            },
            error: function () {
                alert('Handle error');
            }
        });
    }
</script>
<script>
    $('body').on('click', '.btn-view-detail', function () {
        const id = $(this).attr('data-route');
        viewDetailsModal(id);
    });
    const complaintcolumndetails = <?=$table_columns_active?>;
    enableDragDrop('#tabledata', '{{ route("complaint.closed.table.column.updateposition") }}', function () {
        location.reload();
    });

    if(complaintcolumndetails.length > 0) {
        const $preInitData = {
            'product': {
                'options': "{{ $allproducts }}",
                'display_name': "{{$product_custom_fields['product']['field_display_label']}}",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {
                    'filter_postfix': '_f',
                },
            },
            'vendor': {
                'options': "{{ $productVendors }}",
                'display_name': "Vendor",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {

                },
            },
            'type': {
                'options': "{{ $types }}",
                'display_name': "Type",
                'is_enable_remove': false,
                'is_enable_filter': true,
                'option': {},
            },
            'department': {
                'options': "{{$alldepartments}}",
                'display_name': "Department",
                'is_enable_remove': false,
                'is_enable_filter': true,
                'option': {
                    'filter_postfix': '_f',
                },
            },
            'customer': {
                'options': "{{$all_customers}}",
                'display_name': "{{$product_custom_fields['customer']['field_display_label']}}",
                'is_enable_remove': false,
                'is_enable_filter': true,
                'option': {

                },
            },
            'division': {
                'options': "{{$alldivisionnames}}",
                'display_name': "Division",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {
                    'filter_postfix': '_f',
                },
            },
            'subdivision': {
                'options': "{{ $allsubdivisionnames }}",
                'display_name': "Subdivision",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {
                    'filter_postfix': '_f',
                },
            },
            'raised by': {
                'options': "{{$allraisedbynames}}",
                'display_name': "Raised By",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {
                    'filter_postfix': '_f',
                },
            },
            'responsibility': {
                'options': "{{ $responsibility }}",
                'display_name': "Responsibility",
                'is_enable_remove': false,
                'is_enable_filter': true,
                'option': {
                    'filter_postfix': '_f',
                },
            },
            'due date': {
                'options': "{{ $years }}",
                'display_name': "Due Date",
                'is_enable_remove': false,
                'is_enable_filter': true,
                'option': {
                    'is_date_filter' : true
                },
            },
            'complaint received by': {
                'options': "{{$allrecievedbynames}}",
                'display_name': "Complaint Received By",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {
                    'filter_postfix': '_f',
                },
            },
            'complaint received date': {
                'options': "{{$complaintRecievedDates}}",
                'display_name': "Complaint Received Date",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {
                    'is_date_filter' : true
                },
            },
            'mode of complaint': {
                'options': "{{$modeofcomplaint}}",
                'display_name': "Mode of Complaint",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {},
            },
            'plant': {
                'options': "{{$allplants}}",
                'display_name': "Plant",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {
                    'filter_postfix': '_f',
                },
            },
            'investigation team lead': {
                'options': "{{$allteamleaders}}",
                'display_name': "Investigation Team Lead",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {
                    'filter_postfix': '_f',
                },
            },
            'investigation target date': {
                'options': "{{$inyears}}",
                'display_name': "Investigation Target Date",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {
                    'is_date_filter' : true
                },
            },
            'customer phone': {
                'options': "",
                'display_name': "customer phone",
                'is_enable_remove': true,
                'is_enable_filter': false,
                'option': {},
            },
            'customer email': {
                'options': "",
                'display_name': "customer email",
                'is_enable_remove': true,
                'is_enable_filter': false,
                'option': {},
            },
            'sale order no.': {
                'options': "",
                'display_name': "sale order no.",
                'is_enable_remove': true,
                'is_enable_filter': false,
                'option': {},
            },
            'delivery no.': {
                'options': "",
                'display_name': "delivery no.",
                'is_enable_remove': true,
                'is_enable_filter': false,
                'option': {},
            },
            'delivery date': {
                'options': "{{ $deliverydateyears }}",
                'display_name': "delivery date",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {
                    'is_date_filter' : true
                },
            },
            'financial loss': {
                'options': "",
                'display_name': "financial loss",
                'is_enable_remove': true,
                'is_enable_filter': false,
                'option': {},
            },
            'potential cost saving': {
                'options': "",
                'display_name': "potential cost saving",
                'is_enable_remove': true,
                'is_enable_filter': false,
                'option': {},
            },
            'email reminder for action': {
                'options': "",
                'display_name': "email reminder for action",
                'is_enable_remove': true,
                'is_enable_filter': false,
                'option': {},
            },
            'added date': {
                'options': "{{$addedyears}}",
                'display_name': "Added Date",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {
                    'is_date_filter' : true
                },
            },
            'reviewed by': {
                'options': "{{$allreviewedby}}",
                'display_name': "Reviewed By",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {
                    'filter_postfix': '_f',
                },
            },
            'reviewed date': {
                'options': "{{ $revieweddateyears }}",
                'display_name': "Reviewed Date",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {
                    'is_date_filter' : true
                },
            },
            'closure reviewed by': {
                'options': "{{$allclosedby}}",
                'display_name': "Closure Reviewed By",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {
                    'filter_postfix': '_f',
                },
            },
            'closure reviewed date': {
                'options': "{{$closedatyears}}",
                'display_name': "Closure Reviewed Date",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {
                    'is_date_filter' : true
                },
            },
        };
        for (let index = 0; index < complaintcolumndetails.length; index++) {
            if($preInitData[complaintcolumndetails[index]['column_name']]) {
                const $columnData = $preInitData[complaintcolumndetails[index]['column_name']];
                const $columnIndex = getColumnIndexByDataColumnName('#tabledata', complaintcolumndetails[index]['column_name']);
                if ($columnData['is_enable_remove']) {
                    enableRemoveColumn('#tabledata', complaintcolumndetails[index]['column_name'], $columnIndex);
                }
                if ($columnData['is_enable_filter'] && $columnIndex !== -1) {
                    enableHeaderFilterForComplaint(
                        '#tabledata',
                        complaintcolumndetails[index]['column_name'].replace(/ /g, '-'),
                        $columnData['options'],
                        $columnData['display_name'],
                        $columnIndex,
                        $columnData['option']
                    );
                }
            }
        }
    }

    const projectComplaintColumnDetails = <?=$project_table_columns_active?>;
    enableDragDrop('#tabledata2', '{{ route("complaint.closed.table.column.updateposition") }}', function () {
        location.reload();
    });

    if(projectComplaintColumnDetails.length > 0) {
        const $preInitData = {
            'product': {
                'options': "{{ $allproducts }}",
                'display_name': "{{$project_custom_fields['product']['field_display_label']}}",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {
                    'filter_postfix': '_f',
                },
            },
            'source of issue': {
                'options': "{{ $projectSourceOfIssues }}",
                'display_name': "{{$project_custom_fields['source_of_issue']['field_display_label']}}",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {

                },
            },
            'vendor': {
                'options': "{{ $projectVendors }}",
                'display_name': "Vendor",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {

                },
            },
            'type': {
                'options': "{{ $types }}",
                'display_name': "Type",
                'is_enable_remove': false,
                'is_enable_filter': true,
                'option': {},
            },
            'department': {
                'options': "{{$alldepartments}}",
                'display_name': "Department",
                'is_enable_remove': false,
                'is_enable_filter': true,
                'option': {
                    'filter_postfix': '_f',
                },
            },
            'division': {
                'options': "{{$alldivisionnames}}",
                'display_name': "Division",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {
                    'filter_postfix': '_f',
                },
            },
            'subdivision': {
                'options': "{{ $allsubdivisionnames }}",
                'display_name': "Subdivision",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {
                    'filter_postfix': '_f',
                },
            },
            'raisedby': {
                'options': "{{$allraisedbynames}}",
                'display_name': "Raised By",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {
                    'filter_postfix': '_f',
                },
            },
            'responsibility': {
                'options': "{{ $responsibility }}",
                'display_name': "Responsibility",
                'is_enable_remove': false,
                'is_enable_filter': true,
                'option': {
                    'filter_postfix': '_f',
                },
            },
            'due date': {
                'options': "{{ $years }}",
                'display_name': "Due Date",
                'is_enable_remove': false,
                'is_enable_filter': true,
                'option': {
                    'is_date_filter' : true
                },
            },
            'complaint received by': {
                'options': "{{$allrecievedbynames}}",
                'display_name': "Complaint Received By",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {
                    'filter_postfix': '_f',
                },
            },
            'complaint received date': {
                'options': "{{$complaintRecievedDates}}",
                'display_name': "Complaint Received Date",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {
                    'is_date_filter' : true
                },
            },
            'mode of complaint': {
                'options': "{{$modeofcomplaint}}",
                'display_name': "Mode of Complaint",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {},
            },
            'plant': {
                'options': "{{$allplants}}",
                'display_name': "Plant",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {
                    'filter_postfix': '_f',
                },
            },
            'investigation team lead': {
                'options': "{{$allteamleaders}}",
                'display_name': "Investigation Team Lead",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {
                    'filter_postfix': '_f',
                },
            },
            'investigation target date': {
                'options': "{{$inyears}}",
                'display_name': "Investigation Target Date",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {
                    'is_date_filter' : true
                },
            },
            'customer': {
                'options': "{{$all_customers}}",
                'display_name': "{{$project_custom_fields['customer']['field_display_label']}}",
                'is_enable_remove': false,
                'is_enable_filter': true,
                'option': {

                },
            },
            'customer phone': {
                'options': "",
                'display_name': "customer phone",
                'is_enable_remove': true,
                'is_enable_filter': false,
                'option': {},
            },
            'customer email': {
                'options': "",
                'display_name': "customer email",
                'is_enable_remove': true,
                'is_enable_filter': false,
                'option': {},
            },
            'job order no.': {
                'options': "",
                'display_name': "job order no",
                'is_enable_remove': true,
                'is_enable_filter': false,
                'option': {},
            },
            'project name': {
                'options': "",
                'display_name': "project name",
                'is_enable_remove': true,
                'is_enable_filter': false,
                'option': {},
            },
            'warranty coverage': {
                'options': "{{implode(', ',array_column(App\Constants\Constants::WARRANTY_COVERAGES, 'title'))}}",
                'display_name': "Warranty Coverage",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {

                },
            },
            'job history': {
                'options': "",
                'display_name': "job history",
                'is_enable_remove': true,
                'is_enable_filter': false,
                'option': {},
            },
            'product serial no.': {
                'options': "",
                'display_name': "product serial no.",
                'is_enable_remove': true,
                'is_enable_filter': false,
                'option': {},
            },
            'complaint category': {
                'options': "{{ $allComplaintProjectCategories }}",
                'display_name': "Complaint Category",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {

                },
            },
            'financial loss': {
                'options': "",
                'display_name': "financial loss",
                'is_enable_remove': true,
                'is_enable_filter': false,
                'option': {},
            },
            'potential cost saving': {
                'options': "",
                'display_name': "potential cost saving",
                'is_enable_remove': true,
                'is_enable_filter': false,
                'option': {},
            },
            'email reminder for action': {
                'options': "",
                'display_name': "email reminder for action",
                'is_enable_remove': true,
                'is_enable_filter': false,
                'option': {},
            },
            'added date': {
                'options': "{{$addedyears}}",
                'display_name': "Added Date",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {
                    'is_date_filter' : true
                },
            },
            'reviewed by': {
                'options': "{{$allreviewedby}}",
                'display_name': "Reviewed By",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {
                    'filter_postfix': '_f',
                },
            },
            'reviewed date': {
                'options': "{{ $revieweddateyears }}",
                'display_name': "Reviewed Date",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {
                    'is_date_filter' : true
                },
            },
            'closure reviewed by': {
                'options': "{{$allclosedby}}",
                'display_name': "Closure Reviewed By",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {
                    'filter_postfix': '_f',
                },
            },
            'closure reviewed date': {
                'options': "{{$closedatyears}}",
                'display_name': "Closure Reviewed Date",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {
                    'is_date_filter' : true
                },
            },
        };


        for (let index = 0; index < projectComplaintColumnDetails.length; index++) {
            const column = projectComplaintColumnDetails[index];
            if ($preInitData[column.column_name]) {
                const columnData = $preInitData[column.column_name];
                // const columnIndex = getColumnIndexByHeaderName('#tabledata2', column.column_name);
                const columnIndex = getColumnIndexByDataColumnName('#tabledata2', column.column_name);
                if (columnData.is_enable_remove) {
                    enableRemoveColumn('#tabledata2', column.column_name, columnIndex);
                }
                if (columnData.is_enable_filter && columnIndex !== -1) {
                    enableHeaderFilterForComplaint(
                        '#tabledata2',
                        column.column_name.replace(/ /g, '-'),
                        columnData.options,
                        columnData.display_name,
                        columnIndex,
                        columnData.option,
                        'project'
                    );
                }
            }
        }
    }

    One.loader('show');
</script>
<script>
    !function(e) {
        var n = {};

        function t(o) {
            if (n[o]) return n[o].exports;
            var r = n[o] = {
                i: o,
                l: !1,
                exports: {}
            };
            return e[o].call(r.exports, r, r.exports, t), r.l = !0, r.exports
        }
        t.m = e, t.c = n, t.d = function(e, n, o) {
            t.o(e, n) || Object.defineProperty(e, n, {
                enumerable: !0,
                get: o
            })
        }, t.r = function(e) {
            "undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(e, Symbol.toStringTag, {
                value: "Module"
            }), Object.defineProperty(e, "__esModule", {
                value: !0
            })
        }, t.t = function(e, n) {
            if (1 & n && (e = t(e)), 8 & n) return e;
            if (4 & n && "object" == typeof e && e && e.__esModule) return e;
            var o = Object.create(null);
            if (t.r(o), Object.defineProperty(o, "default", {
                    enumerable: !0,
                    value: e
                }), 2 & n && "string" != typeof e)
                for (var r in e) t.d(o, r, function(n) {
                    return e[n]
                }.bind(null, r));
            return o
        }, t.n = function(e) {
            var n = e && e.__esModule ? function() {
                return e.default
            } : function() {
                return e
            };
            return t.d(n, "a", n), n
        }, t.o = function(e, n) {
            return Object.prototype.hasOwnProperty.call(e, n)
        }, t.p = "", t(t.s = 4)
    }({
        4: function(e, n, t) {
            e.exports = t(5)
        },
        5: function(e, n) {
            function t(e, n) {
                for (var t = 0; t < n.length; t++) {
                    var o = n[t];
                    o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(e, o.key, o)
                }
            }
            var o = function() {

                var n, o, r;
                return n = e, r = [{
                    key: "sweetAlert2",
                    value: function() {
                        var e = Swal.mixin({
                            buttonsStyling: !1,
                            customClass: {
                                confirmButton: "btn btn-success m-1",
                                cancelButton: "btn btn-danger m-1",
                                input: "form-control"
                            }
                        });
                        jQuery(".addcomplaint").on("click", (function(n) {
                            e.fire("Warning", "You are not authorized to add a new complaint. Please contact the admins.", "warning")
                        }))
                    }
                }, {
                    key: "init",
                    value: function() {
                        this.sweetAlert2()
                    }
                }], (o = null) && t(n.prototype, o), r && t(n, r), e
            }();
            jQuery((function() {
                o.init()
            }))
        }
    });
</script>

@php
    $iscr = in_array(Auth::user()->email,$complaintregisters);
@endphp
<script>
    var iscr = "{{$iscr}}";
    $('#addComplaint').click(function(){
        if(iscr){
            window.location.href = "{{ url('/user/complaint/add') }}";
        }else{
            $('.addcomplaint').click();
        }
    });
    function autoResizeDiv(height) {
        document.getElementById('content').style.height = (height) + 'px';
    }
    window.onresize = autoResizeDiv;
    autoResizeDiv(window.innerHeight - 100);
</script>

<script>
    function capitalizeWords(str) {
        return str
            .split(' ')
            .map(word => word.charAt(0).toUpperCase() + word.slice(1))
            .join(' ');
    }

    function onClickExport(type) {
        var activeTabPane = $('.tab-pane.active');
        let tab;
        let data;
        let table;
        let headings;
        switch (activeTabPane.attr('id')) {
            case "tab-1":
                tab = 'closed_product';
                table = $('#tabledata').DataTable();
                break;
            case "tab-2":
                tab = 'closed_project';
                table = $('#tabledata2').DataTable();
                break;
            default:
                break;
        }

        headings = table.columns().header().toArray().map(function(th, header) {
            var name = $(th).attr('data-column-name') || $(th).text().trim();
            var label = $(th).attr('data-column-label') || capitalizeWords(name);
            return {
                name: name,
                label: label
            }
        });

        var ids = [];

        table.rows({ search: 'applied' }).every(function () {
            ids.push($(this.node()).data('id'));
        });

        console.log(ids);
        console.log(headings);
        $.ajax({
            url: "{{ route('complaint.exportData') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                format: type,
                tab: tab,
                headings: headings,
                ids: ids
            },
            xhrFields: {
                responseType: 'blob'
            },
            success: function(blob, status, xhr) {

                // Get filename from response header
                let filename = "download";

                const disposition = xhr.getResponseHeader('Content-Disposition');
                if (disposition && disposition.indexOf('filename=') !== -1) {
                    filename = disposition
                        .split('filename=')[1]
                        .replace(/"/g, '');
                }

                // Create download link
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');

                a.href = url;
                a.download = filename;

                document.body.appendChild(a);
                a.click();

                a.remove();
                window.URL.revokeObjectURL(url);
            },
            error: function(xhr) {
                console.error(xhr);
            }
        });
    }
</script>
<!-- END Footer -->
<!-- Apps Modal -->
<!-- Opens from the modal toggle button in the header -->
<div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="block block-rounded block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Options</h3>
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

                            <a class="block block-rounded block-link-shadow bg-body" href="/user/complaint/addcustomer">
                                <div class="block-content text-center">
                                    <i class="fas fa-plus-square fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Add Customers
                                    </p>
                                </div>
                            </a>
                            <!-- END Products -->
                        </div>
                        <div class="col-6">
                            <!-- Sales -->

                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/complaintmgmt">
                                <div class="block-content text-center">
                                    <i class="far fa-list-alt fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        <br/>Register
                                    </p>
                                </div>
                            </a>
                            <!-- END Sales -->
                        </div>
                        <div class="col-6">

                            <!-- Payments -->
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/complaint/list">
                                <div class="block-content text-center">
                                    <i class="fas fa-address-card fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Customer List
                                    </p>
                                </div>
                            </a>
                            <!-- END Payments -->
                        </div>
                        <div class="col-6">

                            <!-- Payments -->
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/complaint/my">
                                <div class="block-content text-center">
                                    <i class="fas fa-clone fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        My Complaints
                                    </p>
                                </div>
                            </a>
                            <!-- END Payments -->
                        </div>
                        <div class="col-6">
                            <br>
                            <!-- Payments -->
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/complaint/closedc" style="margin-top:-16px">
                                <div class="block-content text-center" style="height:130px">
                                    <i class="fas fa-check-circle fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        <br/>Closed
                                    </p>
                                </div>
                            </a>
                            <!-- END Payments -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
