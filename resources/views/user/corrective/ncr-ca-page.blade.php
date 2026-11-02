@extends('pages.datatables')
@section('exportadd')
{{-- <div class="input-group input-group-sm">
    <a href="" class="btn btn-sm btn-dual" style="margin-left:0px;" onclick="return fnCovertTable('print')">
        <i class="fa fa-print  fa-1x" style="color:grey"></i>
    </a>
</div> --}}
<div class="input-group input-group-sm">
    <button type="button" class=" btn-sm btn-dual btn btn-default dropdown-toggle mr-0 mb-0" style="margin-left:8px; " data-toggle="dropdown">
        <i class="fa fa-file-export fa-1x" style="color:grey;"></i>
    </button>
    <ul class="dropdown-menu" role="menu" style="min-width:10px;text-align:center;">
        <li>
            <a href="{{ route('corrective.exportData', 'pdf') }}" class="export-link"
                style="padding-right:16px !important;padding-left:16px !important;color:black;width:50px;font-size:10px;border:none;">
                Pdf
            </a>
        </li>
        <li>
            <a href="{{ route('corrective.exportData', 'excel') }}" class="export-link"
                style="padding-right:16px !important;padding-left:16px !important;color:black;width:50px;font-size:10px;border:none;">
                Excel
            </a>
        </li>
        <li>
            <a href="{{ route('corrective.exportData', 'word') }}" class="export-link"
                style="padding-right:16px !important;padding-left:16px !important;color:black;width:50px;font-size:10px;border:none;">
                Word
            </a>
        </li>
    </ul>
</div>

<div class="input-group input-group-sm">
    <a type="button" href="{{url('/user/corrective/addcorrective')}}" class="btn btn-sm btn-dual" style="margin-left:8px;" style="">
        <i class="fa fa-1x fa-plus" style="color:grey;"></i>
    </a>
</div>
@endsection
@section('content')
    <link rel="stylesheet" href="{{ versionedCache('css/register_filter.css') }}">
    <link rel="stylesheet" href="{{ versionedCache('css/workflow.css') }}">
    <link rel="stylesheet" href="{{ versionedCache('css/user/corrective/ncr-ca-style.css') }}">
    <script>
        window.exportFilters = {
            open: {},
            my_open: {},
            closed: {}
        };
    </script>
    <input type="hidden" value="a1" id="tabselected">
    <div class="alert alert-primary" id="columnremovaldanger" style="display:none;font-size:12px;">
        Internal server error, Unable to process request
    </div>
    <div class="alert alert-danger" id="columnremovaldangernotremoved" style="display:none"></div>
    <!-- Page Content -->
    <div class="content" style="width:100%;">
        @if(Session::has('msg'))
            <div class="alert alert-warning flash-message alert-dismissible" style="font-size:14px; position: relative;">
                <a class="close" data-dismiss="alert" style="position: absolute;right: 10px;color:#0a0a0a;cursor: pointer;">×</a>
                {{ Session::get('msg') }}
            </div>
        @endif
        @if (Session::has('successMsg'))
            <div class="alert alert-success flash-message alert-dismissible" style="font-size:14px; position: relative;">
                <a class="close" data-dismiss="alert" style="position: absolute;right: 10px;color:#0a0a0a;cursor: pointer;">×</a>
                {{ Session::get('successMsg') }}
            </div>
        @endif
        @if (Session::has('dangerMsg'))
            <div class="alert alert-danger flash-message alert-dismissible" style="font-size:14px; position: relative;">
                <a class="close" data-dismiss="alert" style="position: absolute;right: 10px;color:#0a0a0a;cursor: pointer;">×</a>
                {{ Session::get('dangerMsg') }}
            </div>
        @endif
        @if (Session::has('infoMsg'))
            <div class="alert alert-primary flash-message alert-dismissible" style="font-size:14px; position: relative;">
                <a class="close" data-dismiss="alert" style="position: absolute;right: 10px;color:#0a0a0a;cursor: pointer;">×</a>
                {{ Session::get('infoMsg') }}
            </div>
        @endif
        <!-- heading part starts -->
        <div class="block-header block-header-default">
            <div class="block-options" style="margin-left:-25px; font-size: 1rem;">
                <span style="text-transform: uppercase;font-weight:bold" class="font-w700">Register</span>
                <span style="font-weight:normal;font-size:15px;color: gray;">NCR & CA</span>
            </div>
            <div>
                <table width="100%" style="padding-top:5px;padding-right:15px;">
                    <tr>
                        <td align="right">
                            <a href="{{ route('corrective.category.clear.filter') }}" class="btn btn-sm btn-alt-primary d-none" id="clearFilterBtn">
                                <span data-toggle="tooltip" title="Click here to clear filter">
                                    x Clear Filter
                                </span>
                            </a>
                            <a href="#" class="btn btn-sm btn-alt-primary btn-configure-table" data-toggle="dropdown"
                                data-placement="top">
                                <span data-toggle="tooltip" title="Click here to apply category filter">
                                    <img src="{{ asset('images/icons/filter.png') }}" alt="filter" style="font-size:17px;cursor:pointer;height:18px;width:18px;">
                                </span>
                            </a>
                            <div class="dropdown-menu font-size-sm scrollable2"  aria-labelledby="dropdown-dropleft-outline-dark">
                                <label class="dropdown-item d-flex align-items-center gap-2 rounded px-2 py-2 mb-1" data-target="product-ncr-section" style="font-size:14px;cursor:pointer;">
                                    <input type="radio" name="filter" value="product" class="filter-radio" {{ (isset($lastSelectedCategory) && $lastSelectedCategory=='product') ? 'checked' : '' }}>
                                    <span class="fw-medium text-dark">&nbsp;Product NCR</span>
                                </label>

                                <label class="dropdown-item filter-option d-flex align-items-center gap-2 rounded px-2 py-2 mb-1" data-target="process-ncr-section" style="font-size:14px;cursor:pointer;">
                                    <input type="radio" name="filter" value="process" class="filter-radio" {{ (isset($lastSelectedCategory) && $lastSelectedCategory=='process') ? 'checked' : '' }}>
                                    <span class="fw-medium text-dark">&nbsp;Process NCR</span>
                                </label>

                                <label class="dropdown-item d-flex align-items-center gap-2 rounded px-2 py-2" data-target="vendor-nc-section" style="font-size:14px;cursor:pointer;">
                                    <input type="radio" name="filter" value="vendor" class="filter-radio" {{ (isset($lastSelectedCategory) && $lastSelectedCategory=='vendor') ? 'checked' : '' }}>
                                    <span class="fw-medium text-dark">&nbsp;Vendor NCR</span>
                                </label>
                            </div>
                        </td>
                        <td align="right">
                            <a href="#" class="btn btn-sm btn-alt-primary btn-configure-table" data-toggle="dropdown"
                                data-placement="top">
                                <span data-toggle="tooltip" title="Click here to configure column">
                                    <img src="{{ asset('images/icons/table-configure-icon.png') }}" alt="filter" style="font-size:17px;cursor:pointer;height:18px;width:18px;">
                                </span>
                            </a>
                            <div class="dropdown-menu font-size-sm scrollable2"  aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownMenu">
                                <div class="product-ncr-section {{ (isset($lastSelectedCategory) && $lastSelectedCategory=='product') ? '' : 'd-none' }}">
                                    @foreach($productColumnsDetails['open']['columns'] as $column)
                                        <a href="{{ route('corrective.category.table.column.activation',['id'=>$column->id]) }}" class="dropdown-item text-dark" style="font-size:14px;">
                                            <i class="si si-plus" style="font-size:12px;"></i>&nbsp;{{ucfirst($column->display_label)}}
                                        </a>
                                    @endforeach
                                    <hr style="margin-top:-1px;margin-bottom:-1px;">
                                </div>
                                <div class="process-ncr-section {{ (isset($lastSelectedCategory) && $lastSelectedCategory=='process') ? '' : 'd-none' }}">
                                    @foreach($processColumnsDetails['open']['columns'] as $column)
                                        <a href="{{ route('corrective.category.table.column.activation',['id'=>$column->id]) }}" class="dropdown-item text-dark" style="font-size:14px;">
                                            <i class="si si-plus" style="font-size:12px;"></i>&nbsp;{{ucfirst($column->display_label)}}
                                        </a>
                                    @endforeach
                                    <hr style="margin-top:-1px;margin-bottom:-1px;">
                                </div>
                                <div class="vendor-ncr-section {{ (isset($lastSelectedCategory) && $lastSelectedCategory=='vendor') ? '' : 'd-none' }}">
                                    @foreach($vendorColumnsDetails['open']['columns'] as $column)
                                        <a href="{{ route('corrective.category.table.column.activation',['id'=>$column->id]) }}" class="dropdown-item text-dark" style="font-size:14px;">
                                            <i class="si si-plus" style="font-size:12px;"></i>&nbsp;{{ucfirst($column->display_label)}}
                                        </a>
                                    @endforeach
                                    <hr style="margin-top:-1px;margin-bottom:-1px;">
                                </div>
                                <div class="open_corrective_export">
                                    @foreach($tableDetails['open']['columns_non_active'] as $value)
                                        <a href="{{ route('corrective.register.table.column.activation',['id'=>$value->id]) }}" class="dropdown-item text-dark" style="font-size:14px;"
                                            id="table_column_value"><i class="si si-plus" style="font-size:12px;"></i>&nbsp;{{ucfirst($value->column_name)}}</a>
                                    @endforeach
                                    @if($tableDetails['open']['columns_names_enabled_by_user']>0)
                                        <a href="{{ route('corrective.register.table.column.defaultview') }}" class="btn btn-sm btn-alt-primary" style="font-size:12px;margin-top:10px;margin-left:25px;margin-bottom:10px;">
                                            Reset to default table
                                        </a>
                                    @endif
                                </div>
                                <div class="my_corrective_export d-none">
                                    @foreach($tableDetails['my_open']['columns_non_active'] as $value)
                                    <a href="{{ route('corrective.myopen.table.column.activation',['id'=>$value->id]) }}" class="dropdown-item text-dark" style="font-size:14px;"
                                        id="table_column_value"><i class="si si-plus" style="font-size:12px;"></i>&nbsp;{{ucfirst($value->column_name)}}</a>
                                    @endforeach
                                    @if($tableDetails['my_open']['columns_names_enabled_by_user']>0)
                                        <a href="{{ route('corrective.myopen.table.column.defaultview') }}" class="btn btn-sm btn-alt-primary" style="font-size:12px;margin-top:10px;margin-left:25px;margin-bottom:10px;">
                                            Reset to default table
                                        </a>
                                    @endif
                                </div>
                                <div class="closed_corrective_export d-none">
                                    @foreach($tableDetails['closed']['columns_non_active'] as $value)
                                        <a href="{{ route('corrective.closed.table.column.activation',['id'=>$value->id]) }}" class="dropdown-item text-dark" style="font-size:14px;"
                                            id="table_column_value"><i class="si si-plus" style="font-size:12px;"></i>&nbsp;{{ucfirst($value->column_name)}}</a>
                                    @endforeach
                                    @if($tableDetails['closed']['columns_names_enabled_by_user']>0)
                                        <a href="{{ route('corrective.closed.table.column.defaultview') }}" class="btn btn-sm btn-alt-primary" style="font-size:12px;margin-top:10px;margin-left:25px;margin-bottom:10px;">
                                            Reset to default table
                                        </a>
                                    @endif
                                </div>

                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <!-- Ends heading part  -->
        <div class="block block-rounded">
            <!-- Dynamic Table Full -->
            <div class="block-content block-content-full">
                <ul class="nav nav-tabs nav-tabs-alt" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="#tab-1" data-toggle="tab" onclick="toggleTab('#tab-1')">Open</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tab-2" data-toggle="tab" onclick="toggleTab('#tab-2')">My Open</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tab-3" data-toggle="tab" onclick="toggleTab('#tab-3')">Closed</a>
                    </li>
                </ul>
                <div id="content" class="tab-content mt-4">
                    <div class="tab-pane active table-responsive scrollable" id="tab-1" role="tabpanel">
                        {!!$open!!}
                    </div>
                    <div class="tab-pane table-responsive-2 scrollable" id="tab-2" role="tabpanel">
                        {!!$myopen!!}
                    </div>
                    <div class="tab-pane table-responsive scrollable table-responsive-3" id="tab-3" role="tabpanel">
                        {!!$closed!!}
                    </div>
                </div>
            </div>
            <!-- END Dynamic Table Full -->
        </div>
        <!-- END Page Content -->
    </div>
    <!-- END Page Content -->
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
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/corrective/corrissues">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="far fa-list-alt fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Register
                                        </p>
                                    </div>
                                </a>
                                <!-- END Products -->
                            </div>
                            <div class="col-6">

                                <a class="block block-rounded block-link-shadow bg-body mb-0" href='/user/corrective/analysis'>
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Analysis
                                        </p>
                                    </div>
                                </a>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Apps Modal -->
    <div class="corrective-area"></div>
    <div id="view-detail"></div>
    <!-- Column Header Dropdown Menu  starts-->
    <div id="dropdownMenucol">
        <form id="remove_column_ajax" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="remove_column_id" id="remove_column_id"/>
            <input type="hidden" name="remove_column_url" id="remove_column_url"/>
            <button type="submit" class="btn btn-sm btn-danger" style="font-size:10px;">
                <i class="fa fa-fw fa-times"></i> Remove field
            </button>
        </form>
    </div>
    <!-- Ends Column Header Dropdown Menu-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="{{ versionedCache('js/corrective/ncr-ca-javascript.js') }}"></script>
    @section('sideitem')
        {{-- @if(Gate::allows('edit-corrective') || Gate::allows('edit-all'))
        <div class="input-group input-group-sm ml-2">
            <button type="button" style="margin-left:460px;" class="btn btn-sm btn-dual" id="edit" value=id[]>
                <i class="fa fa-1x fa-edit" style="color:grey;"></i>
            </button>
        </div>
        @endif --}}
        @if(Gate::allows('delete-corrective') || Gate::allows('delete-all'))
        <div class="input-group input-group-sm ml-2">
            <button type="button" class="btn btn-sm btn-dual" id="del" value=id[]>
                <i class="fas fa-1x fa-trash" style="color:red;"></i>
            </button>
        </div>
        @endif
    @endsection
    {{-- @section('notification')
    <div class="dropdown d-inline-block ml-2">
        <button type="button" class="btn btn-sm btn-dual" id="page-header-notifications-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-fw fa-bell"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0 border-0 font-size-sm" aria-labelledby="page-header-notifications-dropdown">
            <div class="p-2 bg-primary-dark text-center rounded-top">
                <h5 class="dropdown-header text-uppercase text-white">Notifications</h5>
            </div>
            <ul class="nav-items mb-0">
                <li>
                    <a class="text-dark media py-2">
                        <div class="mr-2 ml-3">
                            <i class="fa fa-fw fa-check-circle text-success"></i>
                        </div>
                        <div class="media-body pr-2">
                            <div class="font-w600">A new issue raised</div>
                            <span class="font-w500 text-muted">An issue raised in</span>
                        </div>
                    </a>
                </li>
            </ul>

        </div>
    </div>
    @endsection --}}
    <script>
        $(document).ready(function () {
            setTimeout(function () {
                $('.flash-message').fadeOut('slow', function () {
                    $(this).remove();
                });
            }, 5000); // 5000 ms = 5 seconds
        });
    </script>
@endsection
