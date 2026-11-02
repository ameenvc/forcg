@extends('pages.datatables')
@section('exportadd')
{{-- <div id="print2" class="input-group input-group-sm" style="display: none">
    <a href="" class="btn btn-sm btn-dual" style="margin-left:0px;" onclick="onClickPrint()"">
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
<div id="print" class="input-group input-group-sm">
    <button type="button" class=" btn-sm btn-dual btn btn-default dropdown-toggle mr-0 mb-0" data-toggle="dropdown">
        <i class="fa fa-file-export fa-1x" style="color:grey;"></i>
    </button>
    <ul class="dropdown-menu" role="menu" style="min-width:10px;text-align:center;">
        <li><button title="Pdf" onclick="onClickExport('pdf')" style="width:50px;  font-size:10px;border:none;background-color:white;">Pdf</button></li>
        <li><button title="excel" onclick="onClickExport('excel')" style="width:60px;  font-size:10px;border:none;background-color:white;">Excel</button></li>
        <li><button title="word" onclick="onClickExport('word')" style="width:60px;  font-size:10px;border:none;background-color:white;">Word</button></li>
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

         /* Define styles for horizontal scroll only */
        .horizontal-scroll-active #tabledata_filter{
            position: fixed !important;
            right: 60px !important;
            text-align: right !important;
        }
        .horizontal-scroll-active #tabledata_length{
            position: fixed !important;
            text-align: left !important;
        }
        /* Remove margin-bottom during horizontal scroll */
        .horizontal-scroll-active > div.dataTables_wrapper > div.row {
            margin-bottom: 2.5rem !important;
        }
         /* Define styles for horizontal scroll only */
        .horizontal-scroll-active-3 #tabledata_1_filter {
            position: fixed !important;
            right: 60px !important;
            text-align: right !important;
        }
        .horizontal-scroll-active-3 #tabledata_1_length {
            position: fixed !important;
            text-align: left !important;
        }
        /* Remove margin-bottom during horizontal scroll */
        .horizontal-scroll-active-3 > div.dataTables_wrapper > div.row {
            margin-bottom: 2.5rem !important;
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
</style>
<button type="button" class="js-swal-warning d-none addcomplaint"></button>
<?php $data1=$data2=$data3=[];?>
<input type="hidden" value="a1" id="tabselected">
<div id="page-loader" class="show"></div>
<!-- Page Content -->
<div class="alert alert-danger" id="columnremovaldangernotremoved" style="display:none"></div>
<div class="content" style="width:100%;">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb d-flex justify-content-between align-items-center">
            <div class="d-flex breadcrumb-slash">
                <li class="breadcrumb-item font-w700">Register</li>
                <li class="breadcrumb-item">Customer Complaint</li>
            </div>
            @if ($data->count() > 0)
                <li class="breadcrumb-item" id="configuretable" style="display: {{ $activeTemplate->title == 'Product Template' ? 'block' : 'none' }}">
                    <a href="#" class="btn btn-sm btn-alt-secondary btn-configure-table" data-toggle="dropdown" data-placement="top">
                        <span data-toggle="tooltip" title="Click here to configure column">
                        <i class="fa fa-sliders-h text-center" style="font-size:17px;cursor:pointer"></i>
                        </span>
                    </a>
                    <div class="dropdown-menu font-size-sm scrollable2" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownMenu">
                        @foreach($table_columns_non_active as $value)
                            <a href="{{ route('complaint.register.table.column.activation',['id'=>$value->id])}}" class="dropdown-item text-dark" style="font-size:11px;"
                                id="table_column_value"><i class="si si-plus" style="font-size:10px;"></i>&nbsp;
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
                        <a href="{{route('complaint.register.table.column.defaultview')}}" class="btn btn-sm btn-alt-primary" style="font-size:12px;margin-top:10px;margin-left:25px;margin-bottom:10px;">
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
                            <a href="{{ route('complaint.register.table.column.activation',['id'=>$value->id])}}" class="dropdown-item text-dark" style="font-size:11px;"
                                id="table_column_value"><i class="si si-plus" style="font-size:10px;"></i>&nbsp;
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

                                    @default
                                        {{ucwords($value->column_name)}}
                                @endswitch
                            </a>
                        @endforeach
                        @if($project_table_columns_names_enabled_by_user>0)
                        <a href="{{route('complaint.register.table.column.defaultviewProject')}}" class="btn btn-sm btn-alt-primary" style="font-size:12px;margin-top:10px;margin-left:25px;margin-bottom:10px;">
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
                @if ($data->count() > 0)
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTemplate->title == 'Product Template' ? 'active' : '' }}" href="#tab-1" data-toggle="tab" onclick="toggleTab('#tab-1')">Register{{ $dataProject->count() > 0 ? '(Product)' : ''}}</a>
                    </li>
                @endif
                @if ($dataProject->count() > 0)
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTemplate->title == 'Project Template' ? 'active' : '' }}" href="#tab-2" data-toggle="tab" onclick="toggleTab('#tab-2')">Register{{ $data->count() > 0 ? '(Project)' : ''}}</a>
                    </li>
                @endif
                @if ($data->count() > 0)
                    <li class="nav-item">
                        <a class="nav-link" href="#tab-3" data-toggle="tab" onclick="toggleTab('#tab-3')">Complaint Category</a>
                    </li>
                @endif
            </ul>
            <div id="content" class="tab-content mt-4">
                <div class="tab-pane {{ $activeTemplate->title == 'Product Template' ? 'active' : '' }} table-responsive scrollable" id="tab-1" role="tabpanel">
                    <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full" id="tabledata">
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
                                @foreach($table_columns_active as $active)
                                    @php
                                        $label = $active->column_name;
                                        $customLabel = true;
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

                                            case 'sale order no.':
                                                $label = $product_custom_fields['saleorder']['field_display_label'] ?? $active->column_name;
                                                break;

                                            case 'delivery no.':
                                                $label = $product_custom_fields['deliveryno']['field_display_label'] ?? $active->column_name;
                                                break;

                                            case 'delivery date':
                                                $label = $product_custom_fields['deliverydate']['field_display_label'] ?? $active->column_name;
                                                break;

                                            default:
                                                $label = $active->column_name;
                                                $customLabel = false;
                                        }
                                    @endphp

                                    <td
                                        data-orderable="false"
                                        class="text-center titleheading {{ $active->column_name==='S.No' ? '' : 'titleheading2'}} px-1"
                                        style="background-color:#fff;font-size:10px;font-weight:bold !important;"
                                        data-column-id="{{ $active->id }}"
                                        data-column-name="{{$active->column_name}}"
                                        data-column-label="{{ $customLabel ? $label : ucwords($label) }}"
                                        @if($active->enabled_by_user!==0)
                                            onmouseover="return removecolumn('{{ $active->id }}')"
                                        @endif
                                    >
                                        {{ $label }}
                                    </td>
                                @endforeach
                                <td data-column-name="Process Stage" data-column-label="Process Stage" id="processstageshead" class="text-center titleheading titleheading2 px-1" draggable="false" style="background-color:#fff;font-size:10px;font-weight:bold !important;cursor:pointer;" data-orderable="false">
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
                            @foreach($data as $value)
                            <?php
                                $rr=$value->id;
                                $date = $value->tardate;
                                $today = Date("Y-m-d");
                                $product = DB::table('products')->whereIn('id',explode(',',$value->product))->pluck('product')->toArray();
                                $customer = $customersData[$value->customer] ?? $value->customer;
                                $depname = $departmentsData[$value->dept] ?? '--';
                                $res = $responsibilitiesData[$value->responsibility]??"--";
                                $raisedby = $raisedbyRecordsData[$value->addedby]??"--";
                                $receivedby = $receivedbyRecordsData[$value->employee]??'--';
                                $plantname = $plantsData[$value->plant]??"--";
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
                                @foreach($table_columns_active as $column_c)
                                    @switch($column_c->column_name)
                                        @case('customer')
                                            <td class="text-center px-1" style="font-size:10px;">
                                                {{$customer ? $customer : $value->customer}}
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
                                                @if ($value->tardate)
                                                    @if ($value->tardate <= date('Y-m-d'))
                                                        <span class="status status-red" data-toggle="tooltip" title='Due' style="font-size:10px;">{{date('d-m-Y', strtotime($value->tardate))}}</span>
                                                    @else
                                                        <span class="status status-success" style="font-size:10px;">{{date('d-m-Y', strtotime($value->tardate))}}</span>
                                                    @endif
                                                @else
                                                    <span>--</span>
                                                @endif
                                            </td>
                                            @break
                                        @case('raisedby')
                                            @if(in_array('raisedby',$table_columns_names_active))
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
                                                        // displayLabel = (display Label during complaint creation) != (current display lable in config) ? then print display label when created else empty string
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
                                                    {{ $value->delivery_date ?? "--"}}
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
                                    @if($value->status=='Under Review')
                                    <span class="status status-gray">{{$value->status}}</span>
                                    <span style="display: none;">{{ 'e_'.$value->status . "_f" }}</span>
                                    @elseif($value->status=='Actions Proposed')
                                    <span class="badge badge bg-flat" style="color:white">{{$value->status}}</span>
                                    <span style="display: none;">{{ 'e_'.$value->status . "_f" }}</span>
                                    @elseif($value->status=='Action Reviewed, OK')
                                    <span class="status status-success">Action Approved</span>
                                    <span style="display: none;">e_Action Approved_f</span>
                                    @elseif($value->status=='Action Reviewed, Not OK')
                                    <span class="status status-red">Action Rejected</span>
                                    <span style="display: none;">e_Action Rejected_f</span>
                                    @elseif($value->status=='Need More Action')
                                    <span class="status status-warning">{{$value->status}}</span>
                                    <span style="display: none;">{{ 'e_'.$value->status . "_f" }}</span>
                                    @elseif($value->status=='Actions Completed')
                                    <span class="status status-blue">{{$value->status}}</span>
                                    <span style="display: none;">{{ 'e_'.$value->status . "_f" }}</span>
                                    @elseif($value->status=='Approved')
                                    <span class="status status-amethyst-light">{{$value->status}}</span>
                                    <span style="display: none;">{{ 'e_'.$value->status . "_f" }}</span>
                                    @elseif($value->status=='Rejected')
                                    <span class="status status-red">{{$value->status}}</span>
                                    <span style="display: none;">{{ 'e_'.$value->status . "_f" }}</span>
                                    @elseif($value->status == 'Correction Proposed- RC Awaiting' || $value->status == 'Actions Proposed- Review Awaiting' || $value->status == 'RC Updated- Correction Awaiting')
                                    <span class="status status-blue">{{$value->status}}</span>
                                    <span style="display: none;">{{ 'e_'.$value->status . "_f" }}</span>
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
                                            <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" data-placement="top" title="Actions" id="drop{{$value->id}}">
                                                <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer"></i>
                                            </a>
                                            @php
                                                $auth_id = App\User::where('email',Auth::User()->email)->value('id');
                                                $team_members_list = App\ComplaintInvestigator::where('complaint_id',$value->id)->pluck('user_id')->toArray();
                                            @endphp
                                            <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownMenu{{ $value->id }}">
                                                @foreach($email as $rep)
                                                    @if(Auth::user()->email == $rep)
                                                        @if($value->status!='Under Review' && $value->status!='Rejected')
                                                        <a href="#" class="dropdown-item" style="font-size:10px;" onclick="theAlert({{ $value->id }})">Add attachment</a>
                                                        @endif
                                                        @if($value->status=='Under Review')
                                                            <a href="{{url('/user/complaint/review?val='.$rr)}}" class="dropdown-item" style="font-size:10px;">Entry Review</a>
                                                        @elseif($value->status=='Actions Proposed' || $value->status=="Actions Proposed- Review Awaiting")
                                                            <a href="{{url('/user/complaint/reviewaction?val='.$rr)}}" class="dropdown-item" style="font-size:10px;">Review Action</a>
                                                        @elseif($value->status=='Actions Completed')
                                                            <a href="{{url('/user/complaint/closure?val='.$rr)}}" class="dropdown-item" style="font-size:10px;">Closure Review</a>
                                                        @endif
                                                    @endif
                                                @endforeach
                                                @if((Auth::user()->employeeid == $value->addedby)&&($value->status=='Rejected'))
                                                    <a href="{{url('/user/complaint/resubmit?val='.$rr)}}" class="dropdown-item" style="font-size:10px;">Resubmit</a>
                                                @endif
                                                @if($value->status=="Approved" || $value->status=="RC Updated- Correction Awaiting" || $value->status=="Actions Proposed- Review Awaiting" || $value->status=="Correction Proposed- RC Awaiting" || $value->status=="Action Reviewed, Not OK" || $value->status=="Action Reviewed, OK" || $value->status=="Need More Action")
                                                    <button class="dropdown-item {{ $auth_id == $value->team_lead_id || in_array($auth_id,$team_members_list) ? '' : 'disabled-link'}}" style="font-size:10px;" data-toggle="modal" data-target="#investigationModal{{ $value->id }}" {{ $value->status == 'Actions Completed' ? 'disabled disabled-link' : ''}} onclick="showInvestigationModal('{{ $value->id }}')">Add Investigation Report</button>
                                                @endif
                                                @if(Gate::allows('complaint-responsibility-change') && $value->status!='Under Review' && $value->status!="Actions Completed" && $value->status!="Rejected")
                                                <button class="dropdown-item btn-change-resp" data-route="{{$value->id}}" style="font-size:10px;">Change Responsibility</button>
                                                @endif
                                            </div>
                                            <a href="#" data-route="{{$value->id}}" class="btn btn-sm btn-alt-primary btn-view-detail" data-id="{{ $value->id }}" data-toggle="tooltip" data-placement="top" title="View">
                                                <i class="far fa-eye text-center " style="font-size:10px;cursor:pointer"></i>
                                            </a>
                                        </div>

                                        {{-- Start Investigation Modal --}}
                                        @if($value->status=="Approved" || $value->status=="RC Updated- Correction Awaiting" || $value->status=="Actions Proposed- Review Awaiting" || $value->status=="Correction Proposed- RC Awaiting" || $value->status=="Action Reviewed, Not OK" || $value->status=="Action Reviewed, OK" || $value->status=="Need More Action")
                                        @if(($auth_id == $value->team_lead_id || in_array($auth_id,$team_members_list)) && !($value->status == 'Actions Completed'))
                                        <div id="investigationModal{{$value->id}}" class="modal fadebd-example-modal-lg scrollable" aria-labelledby="investigationModalLabel" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-xl" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header block-themed">
                                                        <h5 class="modal-title" id="investigationModalLabel">Investigation Details</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form enctype="multipart/form-data" method="post">
                                                        @csrf

                                                        <div class="modal-body m-body p-4">
                                                            <div class="alert alert-success d-none w-100" id="success_investigation{{ $value->id}}">
                                                                Data added successfully
                                                            </div>

                                                            <ul class="alert alert-danger d-none w-100" id="error_investigation{{ $value->id}}" style="font-size:12px;">
                                                                Internal server error, we are trying to fix them soon
                                                            </ul>

                                                            <ul class="alert alert-warning d-none w-100" id="validation_error_investigation{{ $value->id}}"></ul>

                                                            <input type="hidden" name="complaint_id" id="complaint_id{{ $value->id }}" value="{{ $value->id }}">

                                                            <div class="form-group d-inline-block w-100">
                                                                <label class="col-sm-12 col-form-label" style="font-size:13px;">1. Was this a one-time occurrence, or does it happen regularly?</label>
                                                                <div class="col-sm-12">
                                                                    <input type="radio" value="one_time" name="occurrence{{$value->id}}" {{ $value->occurrence == 'one_time' ? 'checked' : '' }}><label style="font-size: 13px;" class="ml-1">One time</label>
                                                                    <input type="radio" value="regularly" name="occurrence{{$value->id}}" {{ $value->occurrence == 'regularly' ? 'checked' : '' }}><label style="font-size: 13px;" class="ml-1">Regularly</label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group d-inline-block w-100">
                                                                <label class="col-sm-12 col-form-label" style="font-size:13px;">2. What processes or systems are related to the problem?</label>
                                                                <div class="col-sm-12">
                                                                    <input type="text" style="font-size:13px;" class="form-control" name="system_related_problems" id="system_related_problems{{ $value->id }}" value="{{ $value->system_related_problems }}" />
                                                                </div>
                                                            </div>
                                                            <div class="form-group d-inline-block w-100">
                                                                <label class="col-sm-12 col-form-label" style="font-size:13px;">3. Were there any recent changes or modifications?</label>
                                                                <div class="col-sm-12">
                                                                    <input type="radio" value="1" name="recent_changes{{$value->id}}" {{ $value->recent_changes == 1 ? 'checked' : '' }}><label style="font-size: 13px;" class="ml-1">Yes</label>
                                                                    <input type="radio" value="-1" name="recent_changes{{$value->id}}" {{ $value->recent_changes == -1 ? 'checked' : '' }}><label style="font-size: 13px;" class="ml-1">No</label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group d-inline-block w-100">
                                                                <label class="col-sm-12 col-form-label" style="font-size:13px;">4. Did everyone follow established procedures?</label>
                                                                <div class="col-sm-12">
                                                                    <input type="radio" value="1" name="follow_procedures{{$value->id}}" {{ $value->follow_procedures == 1 ? 'checked' : '' }}><label style="font-size: 13px;" class="ml-1">Yes</label>
                                                                    <input type="radio" value="-1" name="follow_procedures{{$value->id}}" {{ $value->follow_procedures == -1 ? 'checked' : '' }}><label style="font-size: 13px;" class="ml-1">No</label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group d-inline-block w-100">
                                                                <label class="col-sm-12 col-form-label" style="font-size:13px;" id="factors_append{{ $value->id }}">5. What are the contributing factors?</label>
                                                                @php
                                                                $factors_array = explode(',', $value->contributing_factors);
                                                                $external_factors_array = explode(',', $value->external_factors);
                                                                @endphp
                                                                <div class="col-sm-12">
                                                                    <select class="form-control" name="contributing_factors[]" id="factors{{ $value->id }}" style="width: 100%;" multiple onchange="displayDetails('{{ $value->id }}')">
                                                                        <option value="human" {{ in_array('human', $factors_array)? 'selected' : '' }}>Human</option>
                                                                        <option value="technical" {{ in_array('technical', $factors_array)? 'selected' : '' }}>Technical</option>
                                                                        <option value="organizational" {{ in_array('organizational', $factors_array)? 'selected' : '' }}>Organizational</option>
                                                                        <option value="material" {{ in_array('material', $factors_array)? 'selected' : '' }}>Material</option>
                                                                        <option value="machine" {{ in_array('machine', $factors_array)? 'selected' : '' }}>Machine</option>
                                                                        <option value="design" {{ in_array('design', $factors_array)? 'selected' : '' }}>Design</option>
                                                                        <option value="method" {{ in_array('method', $factors_array)? 'selected' : '' }}>Method</option>
                                                                        <option value="envi_aspects" {{ in_array('envi_aspects', $factors_array)? 'selected' : '' }}>Environmental aspects</option>
                                                                        <option value="others" {{ in_array('others', $factors_array)? 'selected' : '' }}>Others</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group w-100 d-none" id="factors_details{{ $value->id }}">
                                                                <label class="col-sm-12 col-form-label" style="font-size:13px;">Contributing factors details</label>
                                                                <div class="col-sm-12">
                                                                    <input type="text" style="font-size:13px;" class="form-control" name="factors_details" id="factors_details_val{{ $value->id }}" value="{{ $value->contributing_factors_details }}" />
                                                                </div>
                                                            </div>
                                                            <div class="form-group d-inline-block w-100">
                                                                <label class="col-sm-12 col-form-label" style="font-size:13px;" id="influence_append{{ $value->id }}">6. Are there any external factors or influences?</label>
                                                                <div class="col-sm-12">
                                                                    <select class="form-control js-select2" name="external_factors[]" id="influence{{ $value->id }}" style="width: 100%;" multiple onchange="displayInfluenceDetails('{{ $value->id }}')">
                                                                        <option value="market_conditions" {{ in_array('market_conditions', $external_factors_array) ? 'selected' : '' }}>Market conditions</option>
                                                                        <option value="regulatory_changes" {{ in_array('regulatory_changes', $external_factors_array) ? 'selected' : ''}}>Regulatory changes</option>
                                                                        <option value="external_events" {{ in_array('external_events', $external_factors_array) ? 'selected' : ''}}>External events</option>
                                                                        <option value="others" {{ in_array('others', $external_factors_array) ? 'selected' : ''}}>Others</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="form-group w-100 d-none" id="influence_details{{ $value->id }}">
                                                                <label class="col-sm-12 col-form-label" style="font-size:13px;">External factors details</label>
                                                                <div class="col-sm-12">
                                                                    <input type="text" style="font-size:13px;" class="form-control" name="influence_details" id="influence_details_val{{ $value->id }}" value="{{ $value->external_factors_details}}" />
                                                                </div>
                                                            </div>
                                                            <div class="form-group d-inline-block w-100">
                                                                <label class="col-sm-12 col-form-label" style="font-size:13px;">7. Was the issue foreseeable?</label>
                                                                <div class="col-sm-12">
                                                                    <input type="radio" value="1" name="issue_foreseeable{{$value->id}}" id="foreseeable_yes_{{ $value->id}}" onchange="ifChecked('{{ $value->id }}')" {{ $value->issue_foreseeable == 1 ? 'checked' : '' }}><label style="font-size: 13px;" class="ml-1">Yes</label>
                                                                    <input type="radio" value="-1" name="issue_foreseeable{{$value->id}}" id="foreseeable_no_{{ $value->id}}" onchange="ifChecked('{{ $value->id }}')" {{ $value->issue_foreseeable == -1 ? 'checked' : '' }}><label style="font-size: 13px;" class="ml-1">No</label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group d-none w-100" id="prevent_block_{{ $value->id }}">
                                                                <label class="col-sm-12 col-form-label" style="font-size:13px;">Why wasn't it prevented?</label>
                                                                <div class="col-sm-12">
                                                                    <input type="text" style="font-size:13px;" class="form-control" name="prevent_reason" id="prevent_reason{{ $value->id }}" value="{{ $value->prevent_reason }}" />
                                                                </div>
                                                            </div>
                                                            <div class="table-responsive mt-4">
                                                                <table class="table table-bordered table-striped table-vcenter" id="root_cause_table{{ $value->id }}">
                                                                    <thead class="bg-white">
                                                                        <tr>
                                                                            <th class="text-center titleheading" style="width:1%;">S #</th>
                                                                            <th class="text-center titleheading" style="width:38%;">Root Cause</th>
                                                                            <th class="text-center titleheading" style="width:38%;">Corrective Action</th>
                                                                            <th class="text-center titleheading" style="width:14%;">Responsibility</th>
                                                                            <th class="text-center titleheading" style="width:8%;">Target Date</th>
                                                                            <th style="width:1%;"></th>
                                                                        </tr>
                                                                    </thead>
                                                                    @php
                                                                    $records = DB::table('complaint_root_cause_corrective_action_details')->where('complaint_id',$value->id)->get();
                                                                    $num = 0;
                                                                    $users = DB::table('users')->where('is_email_verified',true)->get();
                                                                    $departments = DB::table('departments')->where('is_active',true)->get();
                                                                    @endphp
                                                                    <tbody id="dynamictable{{$value->id}}">
                                                                        @if(count($records)<=0)
                                                                            <tr>
                                                                                <td>{{ ++$num }}</td>
                                                                                <td class="td-class"><textarea class="form-control" name="rootcause{{$value->id}}[]" id="rootcause{{$num}}" cols="30" rows="5" style="font-size:13px;" placeholder="Enter Root Cause"></textarea></td>
                                                                                <td class="td-class"><textarea class="form-control" name="correctiveaction{{$value->id}}[]" id="correctiveaction{{$num}}" cols="30" rows="5" style="font-size:13px;" placeholder="Enter Corrective Action"></textarea></td>
                                                                                <td class="td-class">
                                                                                    <select class="form-control js-select2 department{{$value->id}}" name="department{{$value->id}}[]" id="department{{ $num }}" style="font-size:13px;width:100%;" required="true" placeholder="Choose a Department">
                                                                                        <option value="0" selected="" disabled style="font-size:13px;">Choose a Department</option>
                                                                                        @if(count($departments)>0)
                                                                                        @foreach($departments as $dep)
                                                                                        <option value="{{$dep->id}}" style="font-size:13px;">{{$dep->departmentname}}</option></br>
                                                                                        @endforeach
                                                                                        @endif
                                                                                    </select>
                                                                                    <select class="form-control js-select2 responsibility{{$value->id}}" name="responsibility{{$value->id}}[]" id="responsibility{{ $num }}" style="font-size:13px;width:100%;" required="true" placeholder="Choose Responsibility">
                                                                                        <option value="0" selected="" disabled style="font-size:13px;">Choose Responsibility</option>
                                                                                        @if(count($users)> 0 )
                                                                                        @foreach($users as $item)
                                                                                        <option value="{{$item->id}}" style="font-size:13px;">{{ $item->name }}</option><br>
                                                                                        @endforeach
                                                                                        @endif
                                                                                    </select>
                                                                                </td>
                                                                                <td class="td-class">
                                                                                    <input type="date" class="js-datepicker form-control" style="font-size:12px;" id="target_date{{ $num}}" name="target_date{{$value->id}}[]" data-week-start="1" data-autoclose="true" data-today-highlight="true" placeholder="dd/mm/yy" data-date-format="dd/mm/yy">
                                                                                </td>
                                                                                <td class="td-class">
                                                                                    <button type="button" name="add" class="btn btn-sm btn-light" data-toggle="tooltip" title="Add" onclick="addRaw('{{ $value->id }}')">
                                                                                        <i class="fa fa-fw fa-plus"></i>
                                                                                    </button>
                                                                                </td>
                                                                            </tr>
                                                                        @else
                                                                            @foreach($records as $rec)
                                                                            <tr id="row{{$num+1}}">
                                                                                <td>{{ ++$num }}</td>
                                                                                <input type="hidden" name="rid{{$value->id}}[]" value="{{$rec->id}}">
                                                                                <td class="td-class"><textarea class="form-control" name="rootcause{{$value->id}}[]" id="rootcause{{$num}}" cols="30" rows="5" style="font-size:13px;" >{{$rec->root_cause}}</textarea></td>
                                                                                <td class="td-class"><textarea class="form-control" name="correctiveaction{{$value->id}}[]" id="correctiveaction{{$num}}" cols="30" rows="5" style="font-size:13px;">{{$rec->corrective_action}}</textarea></td>
                                                                                <td class="td-class">
                                                                                    <select class="form-control js-select2 department{{$value->id}}" name="department{{$value->id}}[]" id="department{{ $num }}" style="font-size:13px;width:100%;" required="true" placeholder="Choose a Department">
                                                                                        <option value="0" selected="" disabled style="font-size:13px;">Choose a Department</option>
                                                                                            <!-- Include active departments -->
                                                                                            @if(count($departments) > 0)
                                                                                                @foreach ($departments as $dep)
                                                                                                    <option value="{{ $dep->id }}" style="font-size:13px;"
                                                                                                        {{ isset($rec->department) && intval($rec->department) === $dep->id ? 'selected' : '' }}>
                                                                                                        {{ $dep->departmentname }}
                                                                                                    </option>
                                                                                                @endforeach
                                                                                            @endif
                                                                                        <!-- Include inactive department if already selected -->
                                                                                            @if (isset($rec->department))
                                                                                                @php
                                                                                                    $inactiveDept = App\Departments::where('id', $rec->department)
                                                                                                        ->where('is_active', false)
                                                                                                        ->first();
                                                                                                @endphp
                                                                                                @if ($inactiveDept)
                                                                                                    <option value="{{ $inactiveDept->id }}" style="font-size:13px;" selected>
                                                                                                        {{ $inactiveDept->departmentname }}
                                                                                                    </option>
                                                                                                @endif
                                                                                            @endif
                                                                                    </select>
                                                                                    <select class="form-control js-select2 responsibility{{$value->id}}" name="responsibility{{$value->id}}[]" id="responsibility{{ $num }}" style="font-size:13px;width:100%;" required="true" placeholder="Choose Responsibility">
                                                                                        <option value="0" selected="" disabled style="font-size:13px;">Choose Responsibility</option>
                                                                                        @if(count($users)> 0 )
                                                                                        @foreach($users as $item)
                                                                                        <option value="{{$item->id}}" style="font-size:13px;" {{$rec->responsibility == $item->id ? 'selected' : ''}}>{{ $item->name}}</option><br>
                                                                                        @endforeach
                                                                                        @endif
                                                                                    </select>
                                                                                </td>
                                                                                <td class="td-class">
                                                                                    <input type="date" class="js-datepicker form-control" style="font-size:13px;" id="target_date{{ $num}}" name="target_date{{$value->id}}[]" data-week-start="1" data-autoclose="true" data-today-highlight="true" placeholder="dd/mm/yy" data-date-format="dd/mm/yy" value="{{ $rec->target_date}}">
                                                                                </td>
                                                                                @if($num === 1)
                                                                                <td class="td-class">
                                                                                    <button type="button" name="add" class="btn btn-sm btn-light" data-toggle="tooltip" title="Add" onclick="addRaw('{{ $value->id }}')">
                                                                                        <i class="fa fa-fw fa-plus"></i>
                                                                                    </button>
                                                                                </td>
                                                                                @else
                                                                                <td class="text-center">
                                                                                    <button type="button" name="remove" class="btn btn-sm btn-light" data-toggle="tooltip" title="Remove" onclick="remove_tr('#row{{$num}}','{{$value->id}}')">
                                                                                        <i class="fa fa-fw remove-tr fa-times text-danger"></i>
                                                                                    </button>
                                                                                </td>

                                                                                @endif

                                                                            </tr>
                                                                            @endforeach
                                                                        @endif
                                                                    </tbody>

                                                                    <script>

                                                                        var count = {{$num}};
                                                                        var divcounter = {{$num}}
                                                                        function addRaw(record_id) {
                                                                            count++;
                                                                            let newrow = `<tr id="row${count}">
                                                                                <td><span style="font-size:12px;">${count}</span></td>
                                                                                <td class="td-class"><textarea class="form-control" "cols="25" name="rootcause${record_id}[]" id="rootcause${count}" rows="4" style="font-size:12px;" placeholder="Enter Root Cause"></textarea></td>
                                                                                <td class="td-class"><textarea class="form-control" cols="25" name="correctiveaction${record_id}[]" id="correctiveaction${count}" rows="4" style="font-size:12px;" placeholder="Enter Corrective Action"></textarea></td>
                                                                                <td class="td-class">

                                                                                    <div class="col-sm-12 px-0">
                                                                                        <select class="form-control js-select2 dep_select" id="department${count}" name="department${record_id}[]" style="font-size: 13px;width:100%;">
                                                                                            <option value="" selected>Choose a Department</option>
                                                                                            @if(count($departments)>0)
                                                                                            @foreach($departments as $dep)
                                                                                            <option value="{{$dep->id}}"> {{$dep->departmentname}}</option>
                                                                                            @endforeach
                                                                                            @endif
                                                                                        </select>
                                                                                    </div>

                                                                                    <div class="col-sm-12 mt-1 px-0">
                                                                                        <select class="js-select2 form-select res_select" id="responsibility${count}" name="responsibility${record_id}[]" style="font-size: 13px;width:100%;">
                                                                                            <option value="" selected>Choose Responsibility</option>
                                                                                            @if(count($user)> 0 )
                                                                                            @foreach($user as $use)
                                                                                            <option value="{{$use->id}}"> {{ $use->name }}</option>
                                                                                            @endforeach
                                                                                            @endif
                                                                                        </select>
                                                                                    </div>

                                                                                </td>
                                                                                <td class="td-class">
                                                                                    <input type="date" id="corr_tardate${count}" class="form-control" name="target_date${record_id}[]" style="font-size:12px;">
                                                                                </td>
                                                                                <td class="text-center"><button type="button" name="remove" style="font-size:13px;" class="btn btn-sm btn-light remove-tr" data-toggle="tooltip" title="Remove" onclick="remove_tr('#row${count}',${record_id})">
                                                                                    <i class="fa fa-fw fa-times text-danger"></i></button>
                                                                                </td>
                                                                            </tr>`;
                                                                            $("#dynamictable"+record_id).append(newrow);
                                                                            $('.dep_select').select2();
                                                                            $('.res_select').select2();
                                                                            // setBlurFocusNewRow('#rootcause'+count);
                                                                            // setBlurFocusNewRow('#correctiveaction'+count);
                                                                            // setBlurFocusNewRow('#corr_tardate'+count);
                                                                            // setColorEvent(count);
                                                                            updateCount(record_id);
                                                                        }
                                                                        function remove_tr(row_id,record_id){
                                                                            $(row_id).remove();
                                                                            updateCount(record_id);
                                                                        }
                                                                        function updateCount(record_id) {
                                                                            $('#dynamictable'+record_id+' tr').each(function(index) {
                                                                                $(this).find('td:first').text(index+1);
                                                                            });
                                                                        }
                                                                    </script>
                                                                </table>

                                                            </div>

                                                            <div class="col-sm-12" style="padding-bottom:10px;">
                                                                @php
                                                                    $m = 0;
                                                                    $items_per_row = 5;
                                                                @endphp
                                                                @foreach($rootcorrectives as $rootcorrective)
                                                                <button type="button" class="btn btn-sm btn-outline-primary" style="margin-right:4px;margin-left:4px;margin-bottom:10px;" onclick="return passvalue('{{$rootcorrective->id}}', '{{ $value->id }}');">{{$rootcorrective->title}}</button>
                                                                <?php
                                                                    $m++;
                                                                    if($m % $items_per_row == 0){ ?><br><?php } ?>
                                                                @endforeach

                                                            </div>
                                                            <div class="form-group d-inline-block" style="width:49%;">
                                                                <label class="col-sm-12 col-form-label" for="action_file" style="font-size:13px;">Attach issues related files(avoid '#' and '&' in file names)</label>
                                                                <div class="col-sm-12">
                                                                    <input type="file" class="form-control" id="issue_file{{ $value->id }}" style="font-size: 13px;" name="issue_file[]" multiple>
                                                                </div>
                                                            </div>
                                                            <div class="form-group d-inline-block" style="width:49%;">
                                                                <label class="col-sm-12 col-form-label" for="action_file" style="font-size:13px;">Attach action related files(avoid '#' and '&' in file names)</label>
                                                                <div class="col-sm-12">
                                                                    <input type="file" class="form-control" id="action_file{{ $value->id }}" style="font-size: 13px;" name="action_file[]" multiple>
                                                                </div>
                                                            </div>
                                                            <div class="form-group d-inline-block w-100">
                                                                <label class="col-sm-12 col-form-label" style="font-size:13px;">Attached Files</label>
                                                                <div class="col-sm-12">
                                                                    @php
                                                                        $issuedocs = App\ComplaintIssueFile::where('complaint_id',$value->id)->where('is_deleted',false)->get();
                                                                        $actiondocs = App\ComplaintActionFile::where('complaint_id',$value->id)->where('is_deleted',false)->get();
                                                                        $files = DB::table('complaintfiles')->where('id',$value->id)->pluck('files')->toArray();
                                                                    @endphp
                                                                    @if(count($files)>0)
                                                                    <a data-toggle="modal"  data-target="#viewModal101{{$value->id}}"  data-id="{{ $value->id }}" class="btn btn-secondary text-white btn-small col-sm-12" style="font-size:11px;width: 180px;"><strong>View Complaint Files</strong></a>
                                                                    <div id="viewModal101{{$value->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                                                        <div class="modal-dialog modal-lg">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h3 class="modal-title">Attachments</h3>
                                                                                </div>
                                                                                <table class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                                                                                    <div class="row justify-content-center">
                                                                                        <thead>
                                                                                            <tr class="table-secondary">
                                                                                                <th>S.No</th>
                                                                                                <th>Attachment</th>
                                                                                                <th>Actions</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <?php $o=0; ?>
                                                                                        @if(count($files)>0)
                                                                                            <tbody>
                                                                                                @foreach($files as $f)
                                                                                                <tr>
                                                                                                    <td style="font-size:10px; ;">{{ $o=$o+1}}</td>
                                                                                                    <td style="font-size:13px;width:70%;">{{ $f}}</td>
                                                                                                    <td style="width:100%;">
                                                                                                        <a style="font-size:11px;width: 80px;" class="btn btn-success" href="{{asset('storage/complaint/'.$f)}}" download="{{$f}}">Download</a>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                @endforeach
                                                                                            </tbody>
                                                                                        @else
                                                                                        <div class="row justify-content-center">
                                                                                            <h2>No Files</h2>
                                                                                        </div>
                                                                                        @endif
                                                                                    </div>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    @endif
                                                                    <a class="btn btn-secondary btn-small text-white" style="font-size:11px;width: 180px;" data-toggle="modal" data-id="{{ $value->id }}" data-target="#viewModal102{{$value->id}}"><strong>View Attachments(Issues)</strong> </a>
                                                                    <div id="viewModal102{{$value->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                                                        <div class="modal-dialog modal-lg">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h3 class="modal-title">Attachments(Issues)</h3>
                                                                                </div>
                                                                                <table class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                                                                                    <div class="row justify-content-center">
                                                                                        <thead>
                                                                                            <tr class="table-secondary">
                                                                                                <th style="width:5%;">S.No</th>
                                                                                                <th style="width:65%;">Attachments</th>
                                                                                                <th style="width:30%;">Actions</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <?php $sno=0; ?>

                                                                                        <tbody>
                                                                                            @if(count($issuedocs) > 0)
                                                                                            @foreach($issuedocs as $value1)
                                                                                            <tr>
                                                                                                <td style="font-size:10px;">{{$sno=$sno+1}}</td>
                                                                                                <td style="font-size:13px;width: 100%;">{{ $value1->issue_file}}</td>
                                                                                                <td><a style="font-size:11px;width: 80px;" class="btn btn-success" href="{{asset("storage/complaint/".$value1->issue_file)}}" download="{{$value1->issue_file}}">Download</a></td>
                                                                                            </tr>
                                                                                            @endforeach
                                                                                            @else
                                                                                            <tr>
                                                                                                <td colspan="3" style="font-size:14px;" class="text-center text-danger">No Files</td>
                                                                                            </tr>
                                                                                            @endif
                                                                                        </tbody>

                                                                                    </div>

                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <a class="btn btn-secondary btn-small text-white" style="font-size:11px;width: 180px;" data-toggle="modal" data-id="{{ $value->id }}" data-target="#viewModal103{{$value->id}}"><strong>View Attachments(Action)</strong> </a>
                                                                    <div id="viewModal103{{$value->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                                                        <div class="modal-dialog modal-lg">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h3 class="modal-title">Attachments(Action)</h3>
                                                                                </div>
                                                                                <table class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                                                                                    <div class="row justify-content-center">
                                                                                        <thead>
                                                                                            <tr class="table-secondary">
                                                                                                <th style="width:5%;">S.No</th>
                                                                                                <th style="width:65%;">Attachments</th>
                                                                                                <th style="width:30%;">Actions</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <?php $sno=0; ?>

                                                                                        <tbody>
                                                                                            @if(count($actiondocs) > 0)
                                                                                            @foreach($actiondocs as $value1)
                                                                                            <tr>
                                                                                                <td style="font-size:10px; ;">{{$sno=$sno+1}}</td>
                                                                                                <td style="font-size:13px;width: 100%;">{{ $value1->action_file}}</td>
                                                                                                <td><a style="font-size:11px;width: 80px;" class="btn btn-success" href="{{asset("storage/complaint/".$value1->action_file)}}" download="{{$value1->action_file}}">Download</a></td>
                                                                                            </tr>
                                                                                            @endforeach
                                                                                            @else
                                                                                            <tr>
                                                                                                <td colspan="3" style="font-size:14px;" class="text-center text-danger">No Files</td>
                                                                                            </tr>
                                                                                            @endif
                                                                                        </tbody>

                                                                                    </div>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                                                            <button type="button" class="btn btn-primary" style="font-size:12px" onclick="addReport('{{$value->id}}')">Submit</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @endif
                                        {{-- End Investigation Modal --}}
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
                                        $customLabel = true;

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

                                            default:
                                                $label = $active->column_name;
                                                $customLabel = false;
                                                break;
                                        }
                                    @endphp

                                    <td
                                        data-orderable="false"
                                        class="text-center titleheading {{ $active->column_name==='S.No' ? '' : 'titleheading2'}} px-1"
                                        style="background-color:#fff;font-size:10px;font-weight:bold !important;"
                                        data-column-id="{{ $active->id }}"
                                        data-column-name="{{$active->column_name}}"
                                        data-column-label="{{ $customLabel ? $label : ucwords($label) }}"
                                        @if($active->enabled_by_user!==0)
                                            onmouseover="return removecolumn('{{ $active->id }}')"
                                        @endif
                                    >
                                        {{ $label }}
                                    </td>
                                @endforeach
                                <td data-column-name="Process Stage"  data-column-label="Process Stage" id="processstageshead2" class="text-center titleheading titleheading2 px-1" draggable="false" style="background-color:#fff;font-size:10px;font-weight:bold !important;cursor:pointer;" data-orderable="false">
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
                                                @if ($value->tardate)
                                                    @if ($value->tardate <= date('Y-m-d'))
                                                        <span class="status status-red" data-toggle="tooltip" title='Due' style="font-size:10px;">{{date('d-m-Y', strtotime($value->tardate))}}</span>
                                                    @else
                                                        <span class="status status-success" style="font-size:10px;">{{date('d-m-Y', strtotime($value->tardate))}}</span>
                                                    @endif
                                                @else
                                                    <span>--</span>
                                                @endif
                                            </td>
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
                                            @break;
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
                                    @if($value->status=='Under Review')
                                    <span class="status status-gray">{{$value->status}}</span>
                                    <span style="display: none;">{{ 'e_'.$value->status . "_f" }}</span>
                                    @elseif($value->status=='Actions Proposed')
                                    <span class="badge badge bg-flat" style="color:white">{{$value->status}}</span>
                                    <span style="display: none;">{{ 'e_'.$value->status . "_f" }}</span>
                                    @elseif($value->status=='Action Reviewed, OK')
                                    <span class="status status-success">Action Approved</span>
                                    <span style="display: none;">e_Action Approved_f</span>
                                    @elseif($value->status=='Action Reviewed, Not OK')
                                    <span class="status status-red">Action Rejected</span>
                                    <span style="display: none;">e_Action Rejected_f</span>
                                    @elseif($value->status=='Need More Action')
                                    <span class="status status-warning">{{$value->status}}</span>
                                    <span style="display: none;">{{ 'e_'.$value->status . "_f" }}</span>
                                    @elseif($value->status=='Actions Completed')
                                    <span class="status status-blue">{{$value->status}}</span>
                                    <span style="display: none;">{{ 'e_'.$value->status . "_f" }}</span>
                                    @elseif($value->status=='Approved')
                                    <span class="status status-amethyst-light">{{$value->status}}</span>
                                    <span style="display: none;">{{ 'e_'.$value->status . "_f" }}</span>
                                    @elseif($value->status=='Rejected')
                                    <span class="status status-red">{{$value->status}}</span>
                                    <span style="display: none;">{{ 'e_'.$value->status . "_f" }}</span>
                                    @elseif($value->status == 'Correction Proposed- RC Awaiting' || $value->status == 'Actions Proposed- Review Awaiting' || $value->status == 'RC Updated- Correction Awaiting')
                                    <span class="status status-blue">{{$value->status}}</span>
                                    <span style="display: none;">{{ 'e_'.$value->status . "_f" }}</span>
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
                                            <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" data-placement="top" title="Actions" id="drop{{$value->id}}">
                                                <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer"></i>
                                            </a>
                                            @php
                                                $auth_id = App\User::where('email',Auth::User()->email)->value('id');
                                                $team_members_list = App\ComplaintInvestigator::where('complaint_id',$value->id)->pluck('user_id')->toArray();
                                            @endphp
                                            <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownMenu{{ $value->id }}">
                                                @foreach($email as $rep)
                                                    @if(Auth::user()->email == $rep)
                                                        @if($value->status!='Under Review' && $value->status!='Rejected')
                                                        <a href="#" class="dropdown-item" style="font-size:10px;" onclick="theAlert({{ $value->id }})">Add attachment</a>
                                                        @endif
                                                        @if($value->status=='Under Review')
                                                            <a href="{{url('/user/complaint/review?val='.$rr)}}" class="dropdown-item" style="font-size:10px;">Entry Review</a>
                                                        @elseif($value->status=='Actions Proposed' || $value->status=="Actions Proposed- Review Awaiting")
                                                            <a href="{{url('/user/complaint/reviewaction?val='.$rr)}}" class="dropdown-item" style="font-size:10px;">Review Action</a>
                                                        @elseif($value->status=='Actions Completed')
                                                            <a href="{{url('/user/complaint/closure?val='.$rr)}}" class="dropdown-item" style="font-size:10px;">Closure Review</a>
                                                        @endif
                                                    @endif
                                                @endforeach
                                                @if((Auth::user()->employeeid == $value->addedby)&&($value->status=='Rejected'))
                                                    <a href="{{url('/user/complaint/resubmit?val='.$rr)}}" class="dropdown-item" style="font-size:10px;">Resubmit</a>
                                                @endif
                                                @if($value->status=="Approved" || $value->status=="RC Updated- Correction Awaiting" || $value->status=="Actions Proposed- Review Awaiting" || $value->status=="Correction Proposed- RC Awaiting" || $value->status=="Action Reviewed, Not OK" || $value->status=="Action Reviewed, OK" || $value->status=="Need More Action")
                                                    <button class="dropdown-item {{ $auth_id == $value->team_lead_id || in_array($auth_id,$team_members_list) ? '' : 'disabled-link'}}" style="font-size:10px;" data-toggle="modal" data-target="#investigationModal{{ $value->id }}" {{ $value->status == 'Actions Completed' ? 'disabled disabled-link' : ''}} onclick="showInvestigationModal('{{ $value->id }}')">Add Investigation Report</button>
                                                @endif
                                                @if(Gate::allows('complaint-responsibility-change') && $value->status!='Under Review' && $value->status!="Actions Completed" && $value->status!="Rejected")
                                                <button class="dropdown-item btn-change-resp" data-route="{{$value->id}}" style="font-size:10px;">Change Responsibility</button>
                                                @endif
                                            </div>
                                            <a href="#" data-route="{{$value->id}}" class="btn btn-sm btn-alt-primary btn-view-detail" data-id="{{ $value->id }}" data-toggle="tooltip" data-placement="top" title="View">
                                                <i class="far fa-eye text-center " style="font-size:10px;cursor:pointer"></i>
                                            </a>
                                        </div>

                                        {{-- Start Investigation Modal --}}
                                        @if($value->status=="Approved" || $value->status=="RC Updated- Correction Awaiting" || $value->status=="Actions Proposed- Review Awaiting" || $value->status=="Correction Proposed- RC Awaiting" || $value->status=="Action Reviewed, Not OK" || $value->status=="Action Reviewed, OK" || $value->status=="Need More Action")
                                        @if(($auth_id == $value->team_lead_id || in_array($auth_id,$team_members_list)) && !($value->status == 'Actions Completed'))
                                        <div id="investigationModal{{$value->id}}" class="modal fadebd-example-modal-lg scrollable" aria-labelledby="investigationModalLabel" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-xl" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header block-themed">
                                                        <h5 class="modal-title" id="investigationModalLabel">Investigation Details</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form enctype="multipart/form-data" method="post">
                                                        @csrf

                                                        <div class="modal-body m-body p-4">
                                                            <div class="alert alert-success d-none w-100" id="success_investigation{{ $value->id}}">
                                                                Data added successfully
                                                            </div>

                                                            <ul class="alert alert-danger d-none w-100" id="error_investigation{{ $value->id}}" style="font-size:12px;">
                                                                Internal server error, we are trying to fix them soon
                                                            </ul>

                                                            <ul class="alert alert-warning d-none w-100" id="validation_error_investigation{{ $value->id}}"></ul>

                                                            <input type="hidden" name="complaint_id" id="complaint_id{{ $value->id }}" value="{{ $value->id }}">

                                                            <div class="form-group d-inline-block w-100">
                                                                <label class="col-sm-12 col-form-label" style="font-size:13px;">1. Was this a one-time occurrence, or does it happen regularly?</label>
                                                                <div class="col-sm-12">
                                                                    <input type="radio" value="one_time" name="occurrence{{$value->id}}" {{ $value->occurrence == 'one_time' ? 'checked' : '' }}><label style="font-size: 13px;" class="ml-1">One time</label>
                                                                    <input type="radio" value="regularly" name="occurrence{{$value->id}}" {{ $value->occurrence == 'regularly' ? 'checked' : '' }}><label style="font-size: 13px;" class="ml-1">Regularly</label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group d-inline-block w-100">
                                                                <label class="col-sm-12 col-form-label" style="font-size:13px;">2. What processes or systems are related to the problem?</label>
                                                                <div class="col-sm-12">
                                                                    <input type="text" style="font-size:13px;" class="form-control" name="system_related_problems" id="system_related_problems{{ $value->id }}" value="{{ $value->system_related_problems }}" />
                                                                </div>
                                                            </div>
                                                            <div class="form-group d-inline-block w-100">
                                                                <label class="col-sm-12 col-form-label" style="font-size:13px;">3. Were there any recent changes or modifications?</label>
                                                                <div class="col-sm-12">
                                                                    <input type="radio" value="1" name="recent_changes{{$value->id}}" {{ $value->recent_changes == 1 ? 'checked' : '' }}><label style="font-size: 13px;" class="ml-1">Yes</label>
                                                                    <input type="radio" value="-1" name="recent_changes{{$value->id}}" {{ $value->recent_changes == -1 ? 'checked' : '' }}><label style="font-size: 13px;" class="ml-1">No</label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group d-inline-block w-100">
                                                                <label class="col-sm-12 col-form-label" style="font-size:13px;">4. Did everyone follow established procedures?</label>
                                                                <div class="col-sm-12">
                                                                    <input type="radio" value="1" name="follow_procedures{{$value->id}}" {{ $value->follow_procedures == 1 ? 'checked' : '' }}><label style="font-size: 13px;" class="ml-1">Yes</label>
                                                                    <input type="radio" value="-1" name="follow_procedures{{$value->id}}" {{ $value->follow_procedures == -1 ? 'checked' : '' }}><label style="font-size: 13px;" class="ml-1">No</label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group d-inline-block w-100">
                                                                <label class="col-sm-12 col-form-label" style="font-size:13px;" id="factors_append{{ $value->id }}">5. What are the contributing factors?</label>
                                                                @php
                                                                $factors_array = explode(',', $value->contributing_factors);
                                                                $external_factors_array = explode(',', $value->external_factors);
                                                                @endphp
                                                                <div class="col-sm-12">
                                                                    <select class="form-control" name="contributing_factors[]" id="factors{{ $value->id }}" style="width: 100%;" multiple onchange="displayDetails('{{ $value->id }}')">
                                                                        <option value="human" {{ in_array('human', $factors_array)? 'selected' : '' }}>Human</option>
                                                                        <option value="technical" {{ in_array('technical', $factors_array)? 'selected' : '' }}>Technical</option>
                                                                        <option value="organizational" {{ in_array('organizational', $factors_array)? 'selected' : '' }}>Organizational</option>
                                                                        <option value="material" {{ in_array('material', $factors_array)? 'selected' : '' }}>Material</option>
                                                                        <option value="machine" {{ in_array('machine', $factors_array)? 'selected' : '' }}>Machine</option>
                                                                        <option value="design" {{ in_array('design', $factors_array)? 'selected' : '' }}>Design</option>
                                                                        <option value="method" {{ in_array('method', $factors_array)? 'selected' : '' }}>Method</option>
                                                                        <option value="envi_aspects" {{ in_array('envi_aspects', $factors_array)? 'selected' : '' }}>Environmental aspects</option>
                                                                        <option value="others" {{ in_array('others', $factors_array)? 'selected' : '' }}>Others</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group w-100 d-none" id="factors_details{{ $value->id }}">
                                                                <label class="col-sm-12 col-form-label" style="font-size:13px;">Contributing factors details</label>
                                                                <div class="col-sm-12">
                                                                    <input type="text" style="font-size:13px;" class="form-control" name="factors_details" id="factors_details_val{{ $value->id }}" value="{{ $value->contributing_factors_details }}" />
                                                                </div>
                                                            </div>
                                                            <div class="form-group d-inline-block w-100">
                                                                <label class="col-sm-12 col-form-label" style="font-size:13px;" id="influence_append{{ $value->id }}">6. Are there any external factors or influences?</label>
                                                                <div class="col-sm-12">
                                                                    <select class="form-control js-select2" name="external_factors[]" id="influence{{ $value->id }}" style="width: 100%;" multiple onchange="displayInfluenceDetails('{{ $value->id }}')">
                                                                        <option value="market_conditions" {{ in_array('market_conditions', $external_factors_array) ? 'selected' : '' }}>Market conditions</option>
                                                                        <option value="regulatory_changes" {{ in_array('regulatory_changes', $external_factors_array) ? 'selected' : ''}}>Regulatory changes</option>
                                                                        <option value="external_events" {{ in_array('external_events', $external_factors_array) ? 'selected' : ''}}>External events</option>
                                                                        <option value="others" {{ in_array('others', $external_factors_array) ? 'selected' : ''}}>Others</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="form-group w-100 d-none" id="influence_details{{ $value->id }}">
                                                                <label class="col-sm-12 col-form-label" style="font-size:13px;">External factors details</label>
                                                                <div class="col-sm-12">
                                                                    <input type="text" style="font-size:13px;" class="form-control" name="influence_details" id="influence_details_val{{ $value->id }}" value="{{ $value->external_factors_details}}" />
                                                                </div>
                                                            </div>
                                                            <div class="form-group d-inline-block w-100">
                                                                <label class="col-sm-12 col-form-label" style="font-size:13px;">7. Was the issue foreseeable?</label>
                                                                <div class="col-sm-12">
                                                                    <input type="radio" value="1" name="issue_foreseeable{{$value->id}}" id="foreseeable_yes_{{ $value->id}}" onchange="ifChecked('{{ $value->id }}')" {{ $value->issue_foreseeable == 1 ? 'checked' : '' }}><label style="font-size: 13px;" class="ml-1">Yes</label>
                                                                    <input type="radio" value="-1" name="issue_foreseeable{{$value->id}}" id="foreseeable_no_{{ $value->id}}" onchange="ifChecked('{{ $value->id }}')" {{ $value->issue_foreseeable == -1 ? 'checked' : '' }}><label style="font-size: 13px;" class="ml-1">No</label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group d-none w-100" id="prevent_block_{{ $value->id }}">
                                                                <label class="col-sm-12 col-form-label" style="font-size:13px;">Why wasn't it prevented?</label>
                                                                <div class="col-sm-12">
                                                                    <input type="text" style="font-size:13px;" class="form-control" name="prevent_reason" id="prevent_reason{{ $value->id }}" value="{{ $value->prevent_reason }}" />
                                                                </div>
                                                            </div>
                                                            <div class="table-responsive mt-4">
                                                                <table class="table table-bordered table-striped table-vcenter" id="root_cause_table{{ $value->id }}">
                                                                    <thead class="bg-white">
                                                                        <tr>
                                                                            <th class="text-center titleheading" style="width:1%;">S #</th>
                                                                            <th class="text-center titleheading" style="width:38%;">Root Cause</th>
                                                                            <th class="text-center titleheading" style="width:38%;">Corrective Action</th>
                                                                            <th class="text-center titleheading" style="width:14%;">Responsibility</th>
                                                                            <th class="text-center titleheading" style="width:8%;">Target Date</th>
                                                                            <th style="width:1%;"></th>
                                                                        </tr>
                                                                    </thead>
                                                                    @php
                                                                    $records = DB::table('complaint_root_cause_corrective_action_details')->where('complaint_id',$value->id)->get();
                                                                    $num = 0;
                                                                    $users = DB::table('users')->where('is_email_verified',true)->get();
                                                                    $departments = DB::table('departments')->where('is_active',true)->get();
                                                                    @endphp
                                                                    <tbody id="dynamictable{{$value->id}}">
                                                                        @if(count($records)<=0)
                                                                            <tr>
                                                                                <td>{{ ++$num }}</td>
                                                                                <td class="td-class"><textarea class="form-control" name="rootcause{{$value->id}}[]" id="rootcause{{$num}}" cols="30" rows="5" style="font-size:13px;" placeholder="Enter Root Cause"></textarea></td>
                                                                                <td class="td-class"><textarea class="form-control" name="correctiveaction{{$value->id}}[]" id="correctiveaction{{$num}}" cols="30" rows="5" style="font-size:13px;" placeholder="Enter Corrective Action"></textarea></td>
                                                                                <td class="td-class">
                                                                                    <select class="form-control js-select2 department{{$value->id}}" name="department{{$value->id}}[]" id="department{{ $num }}" style="font-size:13px;width:100%;" required="true" placeholder="Choose a Department">
                                                                                        <option value="0" selected="" disabled style="font-size:13px;">Choose a Department</option>
                                                                                        @if(count($departments)>0)
                                                                                        @foreach($departments as $dep)
                                                                                        <option value="{{$dep->id}}" style="font-size:13px;">{{$dep->departmentname}}</option></br>
                                                                                        @endforeach
                                                                                        @endif
                                                                                    </select>
                                                                                    <select class="form-control js-select2 responsibility{{$value->id}}" name="responsibility{{$value->id}}[]" id="responsibility{{ $num }}" style="font-size:13px;width:100%;" required="true" placeholder="Choose Responsibility">
                                                                                        <option value="0" selected="" disabled style="font-size:13px;">Choose Responsibility</option>
                                                                                        @if(count($users)> 0 )
                                                                                        @foreach($users as $item)
                                                                                        <option value="{{$item->id}}" style="font-size:13px;">{{ $item->name }}</option><br>
                                                                                        @endforeach
                                                                                        @endif
                                                                                    </select>
                                                                                </td>
                                                                                <td class="td-class">
                                                                                    <input type="date" class="js-datepicker form-control" style="font-size:12px;" id="target_date{{ $num}}" name="target_date{{$value->id}}[]" data-week-start="1" data-autoclose="true" data-today-highlight="true" placeholder="dd/mm/yy" data-date-format="dd/mm/yy">
                                                                                </td>
                                                                                <td class="td-class">
                                                                                    <button type="button" name="add" class="btn btn-sm btn-light" data-toggle="tooltip" title="Add" onclick="addRaw('{{ $value->id }}')">
                                                                                        <i class="fa fa-fw fa-plus"></i>
                                                                                    </button>
                                                                                </td>
                                                                            </tr>
                                                                        @else
                                                                            @foreach($records as $rec)
                                                                            <tr id="row{{$num+1}}">
                                                                                <td>{{ ++$num }}</td>
                                                                                <input type="hidden" name="rid{{$value->id}}[]" value="{{$rec->id}}">
                                                                                <td class="td-class"><textarea class="form-control" name="rootcause{{$value->id}}[]" id="rootcause{{$num}}" cols="30" rows="5" style="font-size:13px;" >{{$rec->root_cause}}</textarea></td>
                                                                                <td class="td-class"><textarea class="form-control" name="correctiveaction{{$value->id}}[]" id="correctiveaction{{$num}}" cols="30" rows="5" style="font-size:13px;">{{$rec->corrective_action}}</textarea></td>
                                                                                <td class="td-class">
                                                                                    <select class="form-control js-select2 department{{$value->id}}" name="department{{$value->id}}[]" id="department{{ $num }}" style="font-size:13px;width:100%;" required="true" placeholder="Choose a Department">
                                                                                        <option value="0" selected="" disabled style="font-size:13px;">Choose a Department</option>
                                                                                            <!-- Include active departments -->
                                                                                            @if(count($departments) > 0)
                                                                                                @foreach ($departments as $dep)
                                                                                                    <option value="{{ $dep->id }}" style="font-size:13px;"
                                                                                                        {{ isset($rec->department) && intval($rec->department) === $dep->id ? 'selected' : '' }}>
                                                                                                        {{ $dep->departmentname }}
                                                                                                    </option>
                                                                                                @endforeach
                                                                                            @endif
                                                                                        <!-- Include inactive department if already selected -->
                                                                                            @if (isset($rec->department))
                                                                                                @php
                                                                                                    $inactiveDept = App\Departments::where('id', $rec->department)
                                                                                                        ->where('is_active', false)
                                                                                                        ->first();
                                                                                                @endphp
                                                                                                @if ($inactiveDept)
                                                                                                    <option value="{{ $inactiveDept->id }}" style="font-size:13px;" selected>
                                                                                                        {{ $inactiveDept->departmentname }}
                                                                                                    </option>
                                                                                                @endif
                                                                                            @endif
                                                                                    </select>
                                                                                    <select class="form-control js-select2 responsibility{{$value->id}}" name="responsibility{{$value->id}}[]" id="responsibility{{ $num }}" style="font-size:13px;width:100%;" required="true" placeholder="Choose Responsibility">
                                                                                        <option value="0" selected="" disabled style="font-size:13px;">Choose Responsibility</option>
                                                                                        @if(count($users)> 0 )
                                                                                        @foreach($users as $item)
                                                                                        <option value="{{$item->id}}" style="font-size:13px;" {{$rec->responsibility == $item->id ? 'selected' : ''}}>{{ $item->name}}</option><br>
                                                                                        @endforeach
                                                                                        @endif
                                                                                    </select>
                                                                                </td>
                                                                                <td class="td-class">
                                                                                    <input type="date" class="js-datepicker form-control" style="font-size:13px;" id="target_date{{ $num}}" name="target_date{{$value->id}}[]" data-week-start="1" data-autoclose="true" data-today-highlight="true" placeholder="dd/mm/yy" data-date-format="dd/mm/yy" value="{{ $rec->target_date}}">
                                                                                </td>
                                                                                @if($num === 1)
                                                                                <td class="td-class">
                                                                                    <button type="button" name="add" class="btn btn-sm btn-light" data-toggle="tooltip" title="Add" onclick="addRaw('{{ $value->id }}')">
                                                                                        <i class="fa fa-fw fa-plus"></i>
                                                                                    </button>
                                                                                </td>
                                                                                @else
                                                                                <td class="text-center">
                                                                                    <button type="button" name="remove" class="btn btn-sm btn-light" data-toggle="tooltip" title="Remove" onclick="remove_tr('#row{{$num}}','{{$value->id}}')">
                                                                                        <i class="fa fa-fw remove-tr fa-times text-danger"></i>
                                                                                    </button>
                                                                                </td>

                                                                                @endif

                                                                            </tr>
                                                                            @endforeach
                                                                        @endif
                                                                    </tbody>

                                                                    <script>

                                                                        var count = {{$num}};
                                                                        var divcounter = {{$num}}
                                                                        function addRaw(record_id) {
                                                                            count++;
                                                                            let newrow = `<tr id="row${count}">
                                                                                <td><span style="font-size:12px;">${count}</span></td>
                                                                                <td class="td-class"><textarea class="form-control" "cols="25" name="rootcause${record_id}[]" id="rootcause${count}" rows="4" style="font-size:12px;" placeholder="Enter Root Cause"></textarea></td>
                                                                                <td class="td-class"><textarea class="form-control" cols="25" name="correctiveaction${record_id}[]" id="correctiveaction${count}" rows="4" style="font-size:12px;" placeholder="Enter Corrective Action"></textarea></td>
                                                                                <td class="td-class">

                                                                                    <div class="col-sm-12 px-0">
                                                                                        <select class="form-control js-select2 dep_select" id="department${count}" name="department${record_id}[]" style="font-size: 13px;width:100%;">
                                                                                            <option value="" selected>Choose a Department</option>
                                                                                            @if(count($departments)>0)
                                                                                            @foreach($departments as $dep)
                                                                                            <option value="{{$dep->id}}"> {{$dep->departmentname}}</option>
                                                                                            @endforeach
                                                                                            @endif
                                                                                        </select>
                                                                                    </div>

                                                                                    <div class="col-sm-12 mt-1 px-0">
                                                                                        <select class="js-select2 form-select res_select" id="responsibility${count}" name="responsibility${record_id}[]" style="font-size: 13px;width:100%;">
                                                                                            <option value="" selected>Choose Responsibility</option>
                                                                                            @if(count($user)> 0 )
                                                                                            @foreach($user as $use)
                                                                                            <option value="{{$use->id}}"> {{ $use->name }}</option>
                                                                                            @endforeach
                                                                                            @endif
                                                                                        </select>
                                                                                    </div>

                                                                                </td>
                                                                                <td class="td-class">
                                                                                    <input type="date" id="corr_tardate${count}" class="form-control" name="target_date${record_id}[]" style="font-size:12px;">
                                                                                </td>
                                                                                <td class="text-center"><button type="button" name="remove" style="font-size:13px;" class="btn btn-sm btn-light remove-tr" data-toggle="tooltip" title="Remove" onclick="remove_tr('#row${count}',${record_id})">
                                                                                    <i class="fa fa-fw fa-times text-danger"></i></button>
                                                                                </td>
                                                                            </tr>`;
                                                                            $("#dynamictable"+record_id).append(newrow);
                                                                            $('.dep_select').select2();
                                                                            $('.res_select').select2();
                                                                            // setBlurFocusNewRow('#rootcause'+count);
                                                                            // setBlurFocusNewRow('#correctiveaction'+count);
                                                                            // setBlurFocusNewRow('#corr_tardate'+count);
                                                                            // setColorEvent(count);
                                                                            updateCount(record_id);
                                                                        }
                                                                        function remove_tr(row_id,record_id){
                                                                            $(row_id).remove();
                                                                            updateCount(record_id);
                                                                        }
                                                                        function updateCount(record_id) {
                                                                            $('#dynamictable'+record_id+' tr').each(function(index) {
                                                                                $(this).find('td:first').text(index+1);
                                                                            });
                                                                        }
                                                                    </script>
                                                                </table>

                                                            </div>

                                                            <div class="col-sm-12" style="padding-bottom:10px;">
                                                                @php
                                                                    $m = 0;
                                                                    $items_per_row = 5;
                                                                @endphp
                                                                @foreach($rootcorrectives as $rootcorrective)
                                                                <button type="button" class="btn btn-sm btn-outline-primary" style="margin-right:4px;margin-left:4px;margin-bottom:10px;" onclick="return passvalue('{{$rootcorrective->id}}', '{{ $value->id }}');">{{$rootcorrective->title}}</button>
                                                                <?php
                                                                    $m++;
                                                                    if($m % $items_per_row == 0){ ?><br><?php } ?>
                                                                @endforeach

                                                            </div>
                                                            <div class="form-group d-inline-block" style="width:49%;">
                                                                <label class="col-sm-12 col-form-label" for="action_file" style="font-size:13px;">Attach issues related files(avoid '#' and '&' in file names)</label>
                                                                <div class="col-sm-12">
                                                                    <input type="file" class="form-control" id="issue_file{{ $value->id }}" style="font-size: 13px;" name="issue_file[]" multiple>
                                                                </div>
                                                            </div>
                                                            <div class="form-group d-inline-block" style="width:49%;">
                                                                <label class="col-sm-12 col-form-label" for="action_file" style="font-size:13px;">Attach action related files(avoid '#' and '&' in file names)</label>
                                                                <div class="col-sm-12">
                                                                    <input type="file" class="form-control" id="action_file{{ $value->id }}" style="font-size: 13px;" name="action_file[]" multiple>
                                                                </div>
                                                            </div>
                                                            <div class="form-group d-inline-block w-100">
                                                                <label class="col-sm-12 col-form-label" style="font-size:13px;">Attached Files</label>
                                                                <div class="col-sm-12">
                                                                    @php
                                                                        $issuedocs = App\ComplaintIssueFile::where('complaint_id',$value->id)->where('is_deleted',false)->get();
                                                                        $actiondocs = App\ComplaintActionFile::where('complaint_id',$value->id)->where('is_deleted',false)->get();
                                                                        $files = DB::table('complaintfiles')->where('id',$value->id)->pluck('files')->toArray();
                                                                    @endphp
                                                                    @if(count($files)>0)
                                                                    <a data-toggle="modal"  data-target="#viewModal101{{$value->id}}"  data-id="{{ $value->id }}" class="btn btn-secondary text-white btn-small col-sm-12" style="font-size:11px;width: 180px;"><strong>View Complaint Files</strong></a>
                                                                    <div id="viewModal101{{$value->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                                                        <div class="modal-dialog modal-lg">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h3 class="modal-title">Attachments</h3>
                                                                                </div>
                                                                                <table class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                                                                                    <div class="row justify-content-center">
                                                                                        <thead>
                                                                                            <tr class="table-secondary">
                                                                                                <th>S.No</th>
                                                                                                <th>Attachment</th>
                                                                                                <th>Actions</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <?php $o=0; ?>
                                                                                        @if(count($files)>0)
                                                                                            <tbody>
                                                                                                @foreach($files as $f)
                                                                                                <tr>
                                                                                                    <td style="font-size:10px; ;">{{ $o=$o+1}}</td>
                                                                                                    <td style="font-size:13px;width:70%;">{{ $f}}</td>
                                                                                                    <td style="width:100%;">
                                                                                                        <a style="font-size:11px;width: 80px;" class="btn btn-success" href="{{asset('storage/complaint/'.$f)}}" download="{{$f}}">Download</a>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                @endforeach
                                                                                            </tbody>
                                                                                        @else
                                                                                        <div class="row justify-content-center">
                                                                                            <h2>No Files</h2>
                                                                                        </div>
                                                                                        @endif
                                                                                    </div>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    @endif
                                                                    <a class="btn btn-secondary btn-small text-white" style="font-size:11px;width: 180px;" data-toggle="modal" data-id="{{ $value->id }}" data-target="#viewModal102{{$value->id}}"><strong>View Attachments(Issues)</strong> </a>
                                                                    <div id="viewModal102{{$value->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                                                        <div class="modal-dialog modal-lg">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h3 class="modal-title">Attachments(Issues)</h3>
                                                                                </div>
                                                                                <table class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                                                                                    <div class="row justify-content-center">
                                                                                        <thead>
                                                                                            <tr class="table-secondary">
                                                                                                <th style="width:5%;">S.No</th>
                                                                                                <th style="width:65%;">Attachments</th>
                                                                                                <th style="width:30%;">Actions</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <?php $sno=0; ?>

                                                                                        <tbody>
                                                                                            @if(count($issuedocs) > 0)
                                                                                            @foreach($issuedocs as $value1)
                                                                                            <tr>
                                                                                                <td style="font-size:10px;">{{$sno=$sno+1}}</td>
                                                                                                <td style="font-size:13px;width: 100%;">{{ $value1->issue_file}}</td>
                                                                                                <td><a style="font-size:11px;width: 80px;" class="btn btn-success" href="{{asset("storage/complaint/".$value1->issue_file)}}" download="{{$value1->issue_file}}">Download</a></td>
                                                                                            </tr>
                                                                                            @endforeach
                                                                                            @else
                                                                                            <tr>
                                                                                                <td colspan="3" style="font-size:14px;" class="text-center text-danger">No Files</td>
                                                                                            </tr>
                                                                                            @endif
                                                                                        </tbody>

                                                                                    </div>

                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <a class="btn btn-secondary btn-small text-white" style="font-size:11px;width: 180px;" data-toggle="modal" data-id="{{ $value->id }}" data-target="#viewModal103{{$value->id}}"><strong>View Attachments(Action)</strong> </a>
                                                                    <div id="viewModal103{{$value->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                                                        <div class="modal-dialog modal-lg">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h3 class="modal-title">Attachments(Action)</h3>
                                                                                </div>
                                                                                <table class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                                                                                    <div class="row justify-content-center">
                                                                                        <thead>
                                                                                            <tr class="table-secondary">
                                                                                                <th style="width:5%;">S.No</th>
                                                                                                <th style="width:65%;">Attachments</th>
                                                                                                <th style="width:30%;">Actions</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <?php $sno=0; ?>

                                                                                        <tbody>
                                                                                            @if(count($actiondocs) > 0)
                                                                                            @foreach($actiondocs as $value1)
                                                                                            <tr>
                                                                                                <td style="font-size:10px; ;">{{$sno=$sno+1}}</td>
                                                                                                <td style="font-size:13px;width: 100%;">{{ $value1->action_file}}</td>
                                                                                                <td><a style="font-size:11px;width: 80px;" class="btn btn-success" href="{{asset("storage/complaint/".$value1->action_file)}}" download="{{$value1->action_file}}">Download</a></td>
                                                                                            </tr>
                                                                                            @endforeach
                                                                                            @else
                                                                                            <tr>
                                                                                                <td colspan="3" style="font-size:14px;" class="text-center text-danger">No Files</td>
                                                                                            </tr>
                                                                                            @endif
                                                                                        </tbody>

                                                                                    </div>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                                                            <button type="button" class="btn btn-primary" style="font-size:12px" onclick="addReport('{{$value->id}}')">Submit</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @endif
                                        {{-- End Investigation Modal --}}
                                    </td>
                                </div>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane table-responsive scrollable table-responsive-3" id="tab-3" role="tabpanel">
                    {!!$complaintCategoriesTable!!}
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
<div class="resp-area"></div>
<!-- Ends Column Header Dropdown Menu-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="{{ versionedCache('js/datatable_extension_for_complaint.js') }}"></script>
<script>
    function removecolumn(id){
        $('#remove_column_id').val(id);
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
                url: '/user/complaint/register-table/removecolumn',
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
    function toggleTab(tabName) {
        if(tabName==='#tab-1'){
            $('#configuretable').css('display', 'block');
            $('#configuretableProject').css('display', 'none');
            $('#print').css('display', 'block')
            // $('#print2').css('display', 'block')
            // $('#tabselected').val('a1');
            // $('#tvalue1').val('tvalue1');
        }else if(tabName==='#tab-2'){
            $('#configuretable').css('display', 'none');
            $('#configuretableProject').css('display', 'block');
            $('#print').css('display', 'block')
            // $('#print2').css('display', 'block')
            // $('#tabselected').val('a1');
            // $('#tvalue1').val('tvalue1');
        }else if(tabName==='#tab-3'){
            $('#configuretable').css('display', 'none');
            $('#configuretableProject').css('display', 'none');
            $('#print').css('display', 'block')
            // $('#print2').css('display', 'none')
            // $('#tabselected').val('a5');
            // $('#tvalue1').val('tvalue5');
        }
    }
</script>
<script>
    $(document).ready(function () {
        function initializeDropdowns(tableId, dropdownConfigs) {
            $(tableId).one("preInit.dt", function () {
                let table = $(tableId).DataTable();
                dropdownConfigs.forEach(config => {
                    let { dropdownId, columnHeaderId, optionsArray, placeholder,isDateFilter} = config;
                    let columnIndex = table.column(columnHeaderId).index();
                    let optionsHTML = optionsArray.map(opt => `<option value="${opt}">${opt}</option>`).join('');
                    let dropdownHTML = '';
                    if (isDateFilter) {
                        const tree = createTree(optionsArray);
                        dropdownHTML = buildTreeHtml(tree, placeholder.replace(/ /g, '-'), placeholder);
                    } else {
                        const minWidth = placeholder === 'Complaint Categories' ? '234px' : 'fit-content';
                        dropdownHTML = `
                            <select class="headingselect text-center" style="border:none;-webkit-appearance: none;min-width:${minWidth};background-color:#fff;font-weight:600 !important;" id="${dropdownId}">
                                <option value="">${placeholder}&nbsp;&nbsp;&#9207;</option>
                                ${optionsHTML}
                            </select>
                        `;
                    }
                    $(columnHeaderId).html(dropdownHTML);
                    if (isDateFilter) {
                        const dateFilterElement = $('.' + placeholder.replace(/ /g, '-'));
                        dateFilterElement.find('.filter-header').click(function() {
                            $(this).next('.filter-content').slideToggle();
                        });
                        dateFilterElement.find('.expand').click(function(e) {
                            e.stopPropagation();
                            const dateFilter = $(this).closest('.date-filter')
                            if (dateFilter.next('.nested').length) {
                                dateFilter.next('.nested').slideToggle();
                                $(this).text($(this).text() === '-' ? '+' : '-');
                            }
                        });
                        dateFilterElement.find(".cke-filter").change(function() {
                            const eleName = $(this).attr('data-name')
                            const value = $(this).val();
                            let searchDataList = []
                            if(this.checked) {
                                if (eleName === "year") {
                                    dateFilterElement.find(".cke-filter[data-year=" + value + "]")
                                        .prop('checked', true);
                                }
                                else if (eleName === "month") {
                                    dateFilterElement.find(".cke-filter[data-month=" + value + "]")
                                        .prop('checked', true);
                                }
                            } else {
                                if (eleName === "year") {
                                    dateFilterElement.find(".cke-filter[data-year=" + value + "]")
                                        .prop('checked', false);
                                } else if (eleName === "month") {
                                    dateFilterElement.find(".cke-filter[data-month=" + value + "]")
                                        .prop('checked', false);
                                }
                            }

                            const yearHasBeenSelected = $(this).attr('data-year');
                            const monthHasBeenSelected = $(this).attr('data-month');

                            let isSelectAllMonth = true;
                            dateFilterElement.find(".cke-filter[data-month=" + monthHasBeenSelected + "]").each(function (index, item) {
                                if(!$(item).is(":checked")) {
                                    isSelectAllMonth = false;
                                    return false;
                                }
                            })
                            if (isSelectAllMonth) {
                                dateFilterElement.find(".cke-filter[data-name=month][value="+ monthHasBeenSelected + "]")
                                    .prop('checked', true);
                            } else {
                                dateFilterElement.find(".cke-filter[data-name=month][value="+ monthHasBeenSelected + "]")
                                    .prop('checked', false);
                            }
                            let isSelectAllYear = false;
                            dateFilterElement.find(".cke-filter[data-year=" + yearHasBeenSelected + "]").each(function (index, item) {
                                if($(item).is(":checked")) {
                                    isSelectAllYear = true;
                                    return false;
                                }
                            })
                            if (isSelectAllYear) {
                                dateFilterElement.find(".cke-filter[data-name=year][value="+ yearHasBeenSelected + "]")
                                    .prop('checked', true);
                            } else {
                                dateFilterElement.find(".cke-filter[data-name=year][value="+ yearHasBeenSelected + "]")
                                    .prop('checked', false);
                            }

                            dateFilterElement.find(".cke-filter[data-name=month]").each(function (index, item) {
                                if($(item).is(":checked")) {
                                    searchDataList.push($(item).val())
                                }
                            })
                            const searchKeyWord = searchDataList.join('|');
                            console.log(searchKeyWord);
                            table.column(columnIndex).search(searchKeyWord,true,false).draw()
                            $(this).closest('.filter-content').slideToggle();
                        });
                    }else{
                        $(`#${dropdownId}`).on('change', function () {
                            let selectedValue = $(this).val();
                            let firstOption = $(`#${dropdownId} option:first`);

                            if (selectedValue) {
                                table.column(columnIndex).search('e_' + selectedValue + '_f').draw();
                                $(this).addClass('text-primary');
                                $("#" + dropdownId + " option").css('color', 'black')
                            } else {
                                table.column(columnIndex).search('').draw();
                                $(this).removeClass('text-primary').val('');
                            }
                            firstOption.html(`${placeholder}&nbsp;&nbsp;&#9207;`);
                        });
                    }
                });
            });
        }

        // Prepare dropdown data from Blade
        let processStages = "{{ $processstages }}".split(',');
        let types101 = "{{ $types }}".split(',');
        let responsibilities101 = "{{ $responsibility }}".split(',');
        let departments101 = "{{ $alldepartments }}".split(',');
        let complaintCategories101 = "{{ $allComplaintCategories }}".split(',');
        let dueDates101 = "{{ $years }}".split(',');
        // Initialize dropdowns for table #tabledata
        initializeDropdowns('#tabledata', [
            {
                dropdownId: 'processstages_dropdown',
                columnHeaderId: '#processstageshead',
                optionsArray: processStages,
                placeholder: 'Process Stage',
                isDateFilter:false
            }
        ]);

        initializeDropdowns('#tabledata2', [
            {
                dropdownId: 'processstages_dropdown2',
                columnHeaderId: '#processstageshead2',
                optionsArray: processStages,
                placeholder: 'Process Stage',
                isDateFilter:false
            }
        ]);



        // Initialize dropdowns for table #tabledata_1
        initializeDropdowns('#tabledata_1', [
            {
                dropdownId: 'processstages101_dropdown',
                columnHeaderId: '#processstageshead101',
                optionsArray: processStages,
                placeholder: 'Process Stage',
                isDateFilter:false
            },
            {
                dropdownId: 'types101_dropdown',
                columnHeaderId: '#types101',
                optionsArray: types101,
                placeholder: 'Type',
                isDateFilter:false
            },
            {
                dropdownId: 'responsibilities101_dropdown',
                columnHeaderId: '#responsibilities101',
                optionsArray: responsibilities101,
                placeholder: 'Responsibility',
                isDateFilter:false
            },
            {
                dropdownId: 'departments101_dropdown',
                columnHeaderId: '#departments101',
                optionsArray: departments101,
                placeholder: 'Department',
                isDateFilter:false
            },
            {
                dropdownId: 'complaintcategories101_dropdown',
                columnHeaderId: '#complaintcategories101',
                optionsArray: complaintCategories101,
                placeholder: 'Complaint Categories',
                isDateFilter:false
            },
            {
                dropdownId: 'duedates101_dropdown',
                columnHeaderId: '#duedates101',
                optionsArray: dueDates101,
                placeholder: 'Due Date',
                isDateFilter:true
            }
        ]);
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
    handleScroll(document.querySelector('.table-responsive-3'), 'horizontal-scroll-active-3');
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

    $('body').on('click', '.btn-change-resp', function () {
        const id = $(this).attr('data-route');
        $.ajax({
            url : window.location.protocol + "//" + window.location.host +`/user/complaint/${id}/change-responsibilty`,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                if (data.success) {
                    $('.resp-area').html(data.view)
                    const modalEle = $(`#responsibilityModal`);
                    modalEle.modal('show');
                } else {
                    console.log(data);
                    alert('Data is not found');
                }
            },
            error: function (response) {
                alert('Handle error');
                console.log(response);
            }
        })
    });
</script>
<script>
    function getColumnIndexByDataId(tableId, columnName) {
        return $(`${tableId} thead td[data-column-name="${columnName}"]`).index();
    }
    const complaintcolumndetails = <?=$table_columns_active?>;
    enableDragDrop('#tabledata', '{{ route("complaint.register.table.column.updateposition") }}', function () {
        location.reload();
    });
    enableDragDrop('#tabledata2', '{{ route("complaint.register.table.column.updateposition")}}', function () {
        location.reload();
    });

    if(complaintcolumndetails.length > 0) {
        const $preInitData = {
            'customer': {
                'options': "{{$all_customers}}",
                'display_name': "{{$product_custom_fields['customer']['field_display_label']}}",
                'is_enable_remove': false,
                'is_enable_filter': true,
                'option': {

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
            'product': {
                'options': "{{ $allproducts }}",
                'display_name': "{{$product_custom_fields['product']['field_display_label']}}",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {
                    'filter_postfix': '_f',
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
            // 'process stage': {
            //     'options': "{{$processstages}}",
            //     'display_name': "Process Stage",
            //     'is_enable_remove': false,
            //     'is_enable_filter': true,
            //     'filter_postfix': '_f',
            // },
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
                'options': "",
                'display_name': "delivery date",
                'is_enable_remove': true,
                'is_enable_filter': false,
                'option': {},
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
            const column = complaintcolumndetails[index];

            if($preInitData[column.column_name]) {
                console.log(column.column_name)
                const columnData = $preInitData[column.column_name];
                // const $columnIndex = getColumnIndexByHeaderName('#tabledata', complaintcolumndetails[index]['column_name']);
                const columnIndex = getColumnIndexByDataId('#tabledata', column.column_name);

                if (columnData.is_enable_remove && columnIndex !== -1) {
                    enableRemoveColumn('#tabledata', column.column_name, columnIndex);
                }
                if (columnData.is_enable_filter && columnIndex !== -1) {
                    enableHeaderFilterForComplaint(
                        '#tabledata',
                        column.column_name.replace(/ /g, '-'),
                        columnData.options,
                        columnData.display_name,
                        columnIndex,
                        columnData.option
                    );
                }
            }
        }
    }

    const projectComplaintColumnDetails = <?=$project_table_columns_active?>;
    if(projectComplaintColumnDetails.length > 0) {
        const $preInitData = {
            'customer': {
                'options': "{{$all_customers}}",
                'display_name': "{{$project_custom_fields['customer']['field_display_label']}}",
                'is_enable_remove': false,
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
            'product': {
                'options': "{{ $allproducts }}",
                'display_name': "{{$project_custom_fields['product']['field_display_label']}}",
                'is_enable_remove': true,
                'is_enable_filter': true,
                'option': {
                    'filter_postfix': '_f',
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
            // 'process stage': {
            //     'options': "{{$processstages}}",
            //     'display_name': "Process Stage",
            //     'is_enable_remove': false,
            //     'is_enable_filter': true,
            //     'filter_postfix': '_f',
            // },
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

                // const columnIndex2 = getColumnIndexByHeaderName('#tabledata2', column.column_name);
                const columnIndex = getColumnIndexByDataId('#tabledata2', column.column_name);

                if (columnData.is_enable_remove && columnIndex !== -1) {
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
    function in_array(needle, haystack) {
        var length = haystack.length;
        for (var i = 0; i < length; i++) {
            if (haystack[i] == needle)
                return true;
        }
        return false;
    }

    function displayDetails(id) {
        let factors_details_selector = $('#factors_details' + id);
        if (in_array('others', $('#factors' + id).val())) {
            factors_details_selector.removeClass('d-none');
            factors_details_selector.addClass('d-inline-block');
        } else {
            factors_details_selector.removeClass('d-inline-block');
            factors_details_selector.addClass('d-none');
        }
    }

    function displayInfluenceDetails(id) {
        let influence_details_selector = $('#influence_details' + id);
        if (in_array('others', $('#influence' + id).val())) {
            influence_details_selector.removeClass('d-none');
            influence_details_selector.addClass('d-inline-block');
        } else {
            influence_details_selector.removeClass('d-inline-block');
            influence_details_selector.addClass('d-none');
        }

    }
    function ifChecked(id) {
        if ($('#foreseeable_yes_' + id).is(":checked")) {
            $('#prevent_block_' + id).removeClass('d-none');
            $('#prevent_block_' + id).addClass('d-inline-block');
        } else if ($('#foreseeable_no_' + id).is(":checked")) {
            $('#prevent_block_' + id).addClass('d-none');
            $('#prevent_block_' + id).removeClass('d-inline-block');
        }
    }

    function showInvestigationModal(id) {
        $("#influence" + id).select2();
        $("#factors" + id).select2();
        $(".department" + id).select2();
        $(".responsibility" + id).select2();


        let factors_array = $('#factors' + id).val();
        let influence_array = $("#influence" + id).val();
        let factors_details_selector = $('#factors_details' + id);
        let influence_details_selector = $('#influence_details' + id);

        if (in_array('others', factors_array)) {
            factors_details_selector.removeClass('d-none').addClass('d-inline-block');
        } else {
            factors_details_selector.removeClass('d-inline-block').addClass('d-none');
        }

        if (in_array('others', influence_array)) {
            influence_details_selector.removeClass('d-none').addClass('d-inline-block');
        } else {
            influence_details_selector.removeClass('d-inline-block').addClass('d-none');
        }

        if ($('#foreseeable_yes_' + id).is(":checked")) {
            $('#prevent_block_' + id).removeClass('d-none');
            $('#prevent_block_' + id).addClass('d-inline-block');
        } else if ($('#foreseeable_no_' + id).is(":checked")) {
            $('#prevent_block_' + id).addClass('d-none');
            $('#prevent_block_' + id).removeClass('d-inline-block');
        }
    }
    function passvalue(id,record_id)
    {
        $.ajax({
            data: {
                    "data_id": id,
                    _token: CSRF_TOKEN
                }
            , url: "{{ route('complaint.displayrootcorrectiveajax')}}"
            , type: "POST"
            , success:function(response) {
                if(response.status == 200)
                {
                    var rootlist = response.rootdata;
                    var roothtmldatas='';

                    if(rootlist.length>0){
                        roothtmldatas=rootlist;
                        }
                    else{
                        roothtmldatas=null;
                    }

                    var correctivelist = response.correctdata;
                    var correcthtmldatas='';
                    const removespace = new RegExp(/\s{3,}/, 'ig');
                    const correctivelistdisplay = correctivelist.replace(removespace, '\n');

                    if(correctivelist.length>0){
                        correcthtmldatas=correctivelistdisplay;
                    }
                    else{
                        correcthtmldatas=null;
                    }
                    let lastRow = $('#dynamictable'+record_id+' tr:last');
                    lastRow.find('textarea[name="rootcause'+record_id+'[]"]').val(roothtmldatas).css('background-color','#e8f0fe');
                    lastRow.find('textarea[name="correctiveaction'+record_id+'[]"]').val(correcthtmldatas).css('background-color','#e8f0fe');
                }
            }
        });
    }
    function getvalues(id) {
        var inps = document.querySelectorAll('[name="rootcause'+id+'[]"]');
        var rootcause_arr = [];
        for (var i = 0; i < inps.length; i++) {
            var inp = inps[i];
            if (inp.value.trim() !== "") {
                rootcause_arr.push(inp.value);
            }
        }

        return rootcause_arr;
    }
    function getrid(id) {
        var inps = document.querySelectorAll('[name="rid'+id+'[]"]');
        var arr = [];
        for (var i = 0; i < inps.length; i++) {
            var inp = inps[i];
            if (inp.value.trim() !== "") {
                arr.push(inp.value);
            }
        }

        return arr;
    }
    function getcorrectiveactionvalues(id) {
        var inps = document.querySelectorAll('[name="correctiveaction'+id+'[]"]');
        var arr = [];
        for (var i = 0; i < inps.length; i++) {
            var inp = inps[i];
            if (inp.value.trim() !== "") {
                arr.push(inp.value);
            }
        }
        return arr;
    }

    function getdeptvalues(id) {
        var inps = document.querySelectorAll('[name="department'+id+'[]"]');
        var arr = [];
        for (var i = 0; i < inps.length; i++) {
            var inp = inps[i];
            if (inp.value.trim() !== "") {
                arr.push(inp.value);
            }
        }
        return arr;
    }


    function getrespvalues(id) {
        var inps = document.querySelectorAll('[name="responsibility'+id+'[]"');
        var arr = [];
        for (var i = 0; i < inps.length; i++) {
            var inp = inps[i];
            if (inp.value.trim() !== "") {
                arr.push(inp.value);
            }
        }
        return arr;
    }


    function gettargetdatevalues(id) {
        var inps = document.querySelectorAll('[name="target_date'+id+'[]"]');
        var arr = [];
        for (var i = 0; i < inps.length; i++) {
            var inp = inps[i];
            if (inp.value.trim() !== "") {
                arr.push(inp.value);
            }
        }
        return arr;
    }
    function addReport(id){
        let error_investigation_selector = $('#error_investigation' + id);
        let validation_error_investigation_selector = $('#validation_error_investigation' + id);
        let success_investigation_selector = $('#success_investigation' + id);
        let occurence_selector = $("input[name='occurrence"+id+"']");
        let recent_changes_selector = $("input[name='recent_changes"+id+"']");
        let follow_procedures_selector = $("input[name='follow_procedures"+id+"']");
        let issue_foreseeable_selector = $("input[name='issue_foreseeable"+id+"']");
        let rootcause_arr = getvalues(id);
        let corr_arr = getcorrectiveactionvalues(id);
        let dept_arr = getdeptvalues(id);
        let resp_arr = getrespvalues(id);
        let tar_arr = gettargetdatevalues(id);
        let rid = getrid(id);
        let formData = new FormData();
        formData.append('complaint_id', $('#complaint_id' + id).val());
        formData.append('occurrence', occurence_selector.is(":checked") ? occurence_selector.filter(":checked").val() : 0);
        formData.append('system_related_problems', $("#system_related_problems" + id).val());
        formData.append('recent_changes', recent_changes_selector.is(":checked") ? recent_changes_selector.filter(":checked").val() : 0);
        formData.append('follow_procedures', follow_procedures_selector.is(":checked") ? follow_procedures_selector.filter(":checked").val() : 0);
        formData.append('issue_foreseeable', issue_foreseeable_selector.is(":checked") ? issue_foreseeable_selector.filter(":checked").val() : 0);
        formData.append('prevent_reason', $('#prevent_reason' + id).val());
        formData.append('factors_details', $("#factors_details_val" + id).val());
        formData.append('influence_details', $("#influence_details_val" + id).val());
        // formData.append('removedIssueFilenames',JSON.stringify(removedIssueFilenames));
        // formData.append('removedActionFilenames',JSON.stringify(removedActionFilenames))
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
        rid.forEach(function(item) {
            formData.append('rid[]', item);
        });
        let issueFiles = $('#issue_file'+id)[0].files;
        for (let i = 0; i < issueFiles.length; i++) {
            formData.append('issue_file[]', issueFiles[i]);
        }
        let actionFiles = $('#action_file'+id)[0].files;
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
            type: 'POST',
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                'accept': 'application/json'
            },
            url: '{{ route("user.complaint.submit_investigation_form") }}',
            data:formData,
            processData:false,
            contentType:false,
            success: function(response) {
                error_investigation_selector.removeClass('d-block');
                error_investigation_selector.addClass('d-none');

                validation_error_investigation_selector.removeClass('d-block')
                validation_error_investigation_selector.addClass('d-none');

                success_investigation_selector.removeClass('d-none');
                success_investigation_selector.addClass('d-block');

                setTimeout(function() {
                    window.location.reload();
                }, 2000);
            },
            error: function(err) {
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

    }
    function getChangeRespValues(id) {
        return Array.from(document.querySelectorAll(id))
            .map(inp => inp.value.trim())
            .filter(value => value !== "");
    }



    function changeResponsibility(){
        let error_resp_selector = $('#error_resp');
        let validation_error_resp_selector = $('#validation_error_resp');
        let success_resp_selector = $('#success_resp');
        var complaint_id = $('#complaint_id').val();
        var maindept = $('#maindept').val();
        var division = $('#division_select').val();
        var subdivision = $('#area_select').val();
        var mainresp = $('#mainresp').val();
        var rootids = getChangeRespValues('[name="rid[]"');
        var rootdepts = getChangeRespValues('[name="department[]"');
        var rootresp = getChangeRespValues('[name="responsibility[]"');
        $.ajax({
            type: 'POST',
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                'accept': 'application/json'
            },
            url: '{{ url("/user/complaint/changeRespRequest") }}',
            data: {
                complaint_id : complaint_id,
                maindept : maindept,
                division:division,
                subdivision:subdivision,
                mainresp : mainresp,
                rootids : rootids,
                rootdepts : rootdepts,
                rootresp : rootresp,
            },
            success: function(response) {
                console.log(response);
                error_resp_selector.removeClass('d-block');
                error_resp_selector.addClass('d-none');

                validation_error_resp_selector.removeClass('d-block')
                validation_error_resp_selector.addClass('d-none');

                success_resp_selector.removeClass('d-none');
                success_resp_selector.addClass('d-block');

                setTimeout(function() {
                    window.location.reload();
                }, 2000);
            },
            error: function(err) {
                if (err.status === 500) {
                    error_resp_selector.removeClass('d-none');
                    error_resp_selector.addClass('d-block');

                    validation_error_resp_selector.removeClass('d-block')
                    validation_error_resp_selector.addClass('d-none');

                    success_resp_selector.removeClass('d-block');
                    success_resp_selector.addClass('d-none');
                } else if (err.status === 422) {
                    success_resp_selector.removeClass('d-block');
                    success_resp_selector.addClass('d-none');

                    error_resp_selector.removeClass('d-block');
                    error_resp_selector.addClass('d-none');

                    validation_error_resp_selector.addClass('d-block')
                    validation_error_resp_selector.removeClass('d-none');

                    var error_text = '';
                    $.each(err.responseJSON.error, function(i, val) {
                        error_text += '<li style="font-size:12px;">' + val + '</li>';
                    });
                    validation_error_resp_selector.html(error_text);
                }
            }
        });
    }

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
                tab = 'product';
                table = $('#tabledata').DataTable();
                break;
            case "tab-2":
                tab = 'project';
                table = $('#tabledata2').DataTable();
                break;
            case "tab-3":
                tab = "category";
                table = $('#tabledata_1').DataTable();
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
        // data = table.rows({ search: 'applied' }).data().toArray();

        table.rows({ search: 'applied' }).every(function () {
            ids.push($(this.node()).data('id'));
        });

        //  let path = "{{ route('complaint.exportData', ['format' => '__FORMAT__', 'tab' => '__TAB__', 'headers' => '__HEADER__', 'ids' => '__IDS__']) }}";

        // path = path.replace('__FORMAT__', type)
        //         .replace('__TAB__', tab);
        // window.location.href = path;

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

    function onClickPrint() {
        // var oldPage = document.body.innerHTML;
        // document.body.innerHTML = tab_text;
        // window.print();
        // document.body.innerHTML = oldPage;
    }
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
</script>
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    function theAlert(id) {
        let url = '{{ route("complaint.saveActionAttachment", ":id") }}';
        url = url.replace(':id', id);
        Swal.fire({
            title: 'Select files'
            , html: `<label>Action Files: </label>&nbsp;<input type="file"  class="swal2-file" style="font-size:17px;" accept="image/*,.pdf,.doc,.docs,.xlsx,.xls,.csv" multiple>
                    <label>Issue Files: </label>&nbsp;<input type="file"  class="swal2-file" style="font-size:17px;" accept="image/*,.pdf,.doc,.docs,.xlsx,.xls,.csv" multiple>`
            , showCancelButton: true
            , confirmButtonText: 'Upload'
            , onBeforeOpen: () => {
                $(".swal2-file").change(function() {
                    var reader = new FileReader();
                    reader.readAsDataURL(this.files);
                });
            }
        }).then((file) => {
            var actionFiles = $('.swal2-file')[0].files;
            var issueFiles = $('.swal2-file')[1].files;
            if (actionFiles.length || issueFiles.length) {
                var formData = new FormData();
                $.each(actionFiles, function(i, x) {
                    formData.append('fileToUpload[]', x);
                });
                $.each(issueFiles, function(i, x) {
                    formData.append('fileToUpload1[]', x);
                });
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    }
                    , method: 'post'
                    , url: url
                    , data: formData
                    , processData: false
                    , contentType: false
                    , success: function(resp) {
                        Swal.fire({
                            title: 'Uploaded'
                            , text: 'Your files have been uploaded'
                            , icon: 'success'
                        })
                    }
                    , error: function() {
                        Swal.fire({
                            title: 'Oops'
                            , text: 'Something went wrong!'
                            , icon: 'error'
                        })
                    }
                })
            }else{
                Swal.fire({
                    title: 'Oops'
                    , text: 'No files selected.'
                    , icon: 'warning'
                })
            }
        })
    }

</script>
<script>
    function autoResizeDiv(height) {
        document.getElementById('content').style.height = (height) + 'px';
    }
    window.onresize = autoResizeDiv;
    autoResizeDiv(window.innerHeight - 100);

    $("#del").click(function() {
        var dataArr = new Array();
        if ($('input:checkbox:checked').length > 0) {
            $('input:checkbox:checked').each(function() {
                dataArr.push($(this).attr('id'));
                $.ajax({
                    type: 'get'
                    , url: "{{route('deletecomplaint')}}" + "?val=" + $(this).attr('id')
                    , success: function(response) {
                        window.location.href = "{{url('/user/complaintmgmt')}}";
                    }
                    , error: function(response) {
                        console.log(response);
                    }
                });
            });

        } else {
            alert('No Record Selected');
        }
    });

    $("#edit").click(function() {
        var dataArr = new Array();
        var group = new Array();
        var d;
        var all;
        if ($('input:checkbox:checked').length > 0) {
            $('input:checkbox:checked').each(function() {
                dataArr.push($(this).attr('id'));
                all = "" + dataArr;
                group.push(all); // push all checkbox values into an array
            });
            $.ajax({
                type: 'get'
                , url: "{{route('editreqcomplaint')}}" + "?val=" + (all)
                , success: function(response) {
                    window.location.href = "{{route('editreqcomplaint')}}" + "?val=" + (all);
                }
                , error: function(response) {
                    console.log(response);
                }
            });

        } else {
            alert('No Record Selected');
        }
    });
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
                        {{-- <p>

                        <div class="col-6">
                            <!-- CRM -->
                            <a class="block block-rounded block-link-shadow bg-body" href="/user/complaint/add">
                                <div class="block-content text-center">
                                    <i class="si si-speedometer fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                    Add Complaint
                                    </p>
                                </div>
                            </a>
                            <!-- END CRM -->
                        </div> --}}

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
                                        <br>Register
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
                                        <br>Closed
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

@section('sideitem')
@if(Gate::allows('edit-complaint') || Gate::allows('edit-all'))
<div class="input-group input-group-sm ml-2">
    <button type="button" style="margin-left:460px;" class="btn btn-sm btn-dual" id="edit" value=id[]>
        <i class="fa fa-1x fa-edit" style="color:grey;"></i>
    </button>
</div>
@endif
@if(Gate::allows('delete-complaint') || Gate::allows('delete-all'))
<div class="input-group input-group-sm ml-2">
    <button type="button" class="btn btn-sm btn-dual" id="del" value=id[]>
        <i class="fas fa-1x fa-trash" style="color:red;"></i>
    </button>
</div>
@endif
@endsection
@endsection
