@extends('pages.datatables')
@section('exportadd')
{{-- <div class="input-group input-group-sm" style="display: none">
    <a href="" class="btn btn-sm btn-dual" style="margin-left:0px;" onclick="return fnCovertTable('print')">
        <i class="fa fa-print  fa-1x" style="color:grey"></i>
    </a>
</div> --}}
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
    .scrollable {
        height: 100%;
        overflow-x: scroll;
        overflow-y: scroll;
    }
    .scrollable3 {
        height: 50%;
        overflow-x: scroll;
        overflow-y: scroll;
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
    .select2-results__options{
        font-size:12px !important;
        /* font-weight:bold !important; */
    }
    .headingselect {
        white-space: break-spaces;
        line-height: 1rem;
    }
    .titleheading {
        font-size: 10px !important;
        text-transform: capitalize !important;
        position: sticky !important;
        position: relative !important;
        font-weight:bold !important;
        background-color:#fff;
    }
    .titleheading2{
        min-width: 100px !important;
    }
</style>
<?php $data1=$data=$data3=[];?>
<input type="hidden" value="a3" id="tabselected">
<div id="page-loader" class="show"></div>
<!-- Page Content -->
<div class="content" style="width:100%;">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item font-w700">My Complaints</li>
            <li class="breadcrumb-item">
                Customer Complaint
            </li>
        </ol>
    </nav>
    <!-- Dynamic Table Full -->
    <div class="block block-rounded">
        <div class="block-content block-content-full">
            <div id="content" class="table-responsive" style="height:468px !important;">
                <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full " id="tabledata">
                    <thead>
                        <tr style="position: sticky; top: 0;z-index:100;">
                            <th class="text-center titleheading px-1" data-orderable="false">S.No</th>
                            <th class="text-center titleheading titleheading2 px-1" data-orderable="false">Complaint ID</th>
                            <th class="text-center titleheading titleheading2 px-1" data-orderable="false">&nbsp&nbspTitle&nbsp&nbsp</th>
                            <th  data-orderable="false" data-column-name="customer" class="text-center titleheading titleheading2 px-1">Customer</th>
                            <th id="producthead" data-orderable="false" data-column-name="product" class="text-center titleheading titleheading2 px-1">Product</th>
                            <th id="typehead" data-column-name="type" data-orderable="false" class="text-center titleheading titleheading2 px-1">Type</th>
                            <th id="departmenthead" data-column-name="department" data-orderable="false" class="text-center titleheading titleheading2 px-1">Department</th>
                            <th id="processstageshead" data-column-name="Process Stage" data-orderable="false" class="text-center titleheading titleheading2 px-1">Process&nbspStage</th>
                            <th class="text-center titleheading titleheading2 px-1" data-orderable="false">Action Status</th>
                            <th class="text-center titleheading px-1" data-orderable="false">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=0;?>
                        @foreach($data2 as $value)
                        <?php   $rr=$value->id;
                            $date = $value->tardate;
                            $today = Date("Y-m-d");?>
                        <tr data-id="{{$value->id}}">
                            <td class="text-center px-1" style="font-size:10px;">{{$no=$no+1}}</td>
                            <td class="text-center px-1" style="font-size:10px;">{{ $value->trackno }}</td>
                            <td class="font-w600 text-center px-1" style="font-size:10px;">
                                <a href="{{route('complaint.detail',['id'=>$value->id])}}">{{$value->title}}</a>
                            </td>

                            @php
                                $product = DB::table('products')->whereIn('id',explode(',',$value->product))->pluck('product')->toArray();
                                $customer = DB::table('customer')->where('id',$value->customer)->value('customer');
                            @endphp
                            <td class="text-center px-1" style="font-size:10px;">
                                {{$customer ? $customer : $value->customer}}
                            </td>

                            <td class="text-center px-1">

                                @if($value->product)
                                @foreach($product as $item)
                                    <span style="display: none;">{{ $item . "_f" }}</span>
                                @endforeach
                                @endif
                                <em class="text-muted " style="font-size:10px;">{{$product ? implode(',',$product) : $value->product}}</em>
                            </td>
                            <td class="text-center px-1" style="font-size:12px;">
                                @if($value->type=="Low")
                                    <span class="status status-success">{{$value->type}}</span>
                                @elseif($value->type=="Moderate")
                                    <span class="status status-warning">{{$value->type}}</span>
                                @elseif($value->type=="High")
                                    <span class="status status-red">{{$value->type}}</span>
                                @endif
                            </td>
                            <td class="text-center px-1" style="font-size:10px;">
                            @php
                                $depname = DB::table('departments')->where('id',$value->dept)->value('departmentname');
                            @endphp
                                <em class="text-muted">{{ $depname ? $depname : $value->dept}}</em>
                            </td>
                            <td class="text-center px-1" style="font-size:12px;">
                                @if($value->status=='Under Review')
                                <span class="status status-gray">{{$value->status}}</span>
                                @elseif($value->status=='Actions Proposed')
                                <span class="badge badge bg-flat" style="color:white">{{$value->status}}</span>
                                @elseif($value->status=='Action Reviewed, OK')
                                <span class="status status-success">Action Approved</span>
                                @elseif($value->status=='Action Reviewed, Not OK')
                                <span class="status status-red">Action Rejected</span>
                                @elseif($value->status=='Need More Action')
                                <span class="status status-warning">{{$value->status}}</span>
                                @elseif($value->status=='Actions Completed')
                                <span class="status status-blue">{{$value->status}}</span>
                                @elseif($value->status=='Approved')
                                <span class="status status-amethyst-light">{{$value->status}}</span>
                                @elseif($value->status=='Rejected')
                                <span class="status status-red">{{$value->status}}</span>
                                @elseif($value->status == 'Correction Proposed- RC Awaiting' || $value->status == 'Actions Proposed- Review Awaiting' || $value->status == 'RC Updated- Correction Awaiting')
                                <span class="status status-blue">{{$value->status}}</span>
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
                                            @if ($value->status =='Approved'|| $value->status=="RC Updated- Correction Awaiting")
                                            <a class="dropdown-item" style="font-size:10px;" href="{{url('/user/complaint/proposeaction?val='.$rr)}}">Propose Action</a>
                                            @endif
                                            @if ($value->status=="Actions Proposed- Review Awaiting" || $value->status=="Correction Proposed- RC Awaiting" || $value->status=="Action Reviewed, Not OK" || $value->status=="Need More Action" || $value->status=="Action Reviewed, OK")
                                            <a class="dropdown-item" style="font-size:10px;" href="{{url('/user/complaint/editproposeaction?val='.$rr)}}">Edit</a>
                                            @endif
                                            @if($value->status =='Action Reviewed, OK')
                                            <a href="{{ url('/user/complaint/updateprogress',['id' => $rr])}}" style="font-size:10px;" class="dropdown-item">Update Progress</a>
                                            @endif
                                        </div>
                                        <a href="#" class="btn btn-sm btn-alt-primary btn-view-detail " data-route="{{ $value->id }}" data-toggle="tooltip" data-placement="top" title="View">
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
        <!-- END Dynamic Table Full -->
    </div>
    <!-- END Page Content -->
    <!-- END Main Container -->
    <!-- END Footer -->
</div>
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
<div class="complaint-area"></div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
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
    })
</script>
<script>
    $(document).ready(function(){
        $("#tabledata").one("preInit.dt", function() {
            let table = $('#tabledata').DataTable();

            let allProducts = "{{ $allproducts }}".split(',');
            let productOptions = allProducts.map(product => `<option value="${product}">${product}</option>`).join('');
            let productDropdown = $(`
                <select class="headingselect text-center" style="border:none;-webkit-appearance: none;min-width:fit-content;background-color:#fff;font-weight:600 !important;" id="product_dropdown">
                    <option value="">Product&nbsp;&nbsp;&#9207;</option>
                    ${productOptions}
                </select>
            `);
            $('#producthead').html(productDropdown);
            $("#product_dropdown").on('change', function() {
                let selectedValue = $(this).val();
                let firstOption = $('#product_dropdown option:first');
                if (selectedValue) {
                    table.columns(4).search(selectedValue+'_f').draw();
                    $(this).addClass('text-primary');
                    $("#product_dropdown option").css('color', 'black');
                } else {
                table.column(4).search('').draw();
                $(this).removeClass('text-primary');
                $('#product_dropdown').val('');
                }
                firstOption.html('Product&nbsp;&nbsp;&#9207;');
            });


            let allTypes = "{{ $types}}".split(',');
            let typeOptions = allTypes.map(type => `<option value="${type}">${type}</option>`).join('');
            let typeDropdown = $(`
                <select class="headingselect text-center" style="border:none;-webkit-appearance: none;min-width:fit-content;background-color:#fff;font-weight:600 !important;" id="type_dropdown">
                    <option value="">Type&nbsp;&nbsp;&#9207;</option>
                    ${typeOptions}
                </select>
            `);
            $('#typehead').html(typeDropdown);
            $("#type_dropdown").on('change', function() {
                let selectedValue = $(this).val();
                let firstOption = $('#type_dropdown option:first');
                if (selectedValue) {
                    table.columns(5).search('^' + selectedValue + '$', true, false).draw();
                    $(this).addClass('text-primary');
                    $("#type_dropdown option").css('color', 'black');
                } else {
                table.column(5).search('').draw();
                $(this).removeClass('text-primary');
                $('#type_dropdown').val('');
                }
                firstOption.html('Type&nbsp;&nbsp;&#9207;');
            });


            let allDepartments = "{{ $alldepartments}}".split(',');
            let departmentOptions = allDepartments.map(department => `<option value="${department}">${department}</option>`).join('');
            let departmentDropdown = $(`
                <select class="headingselect text-center" style="border:none;-webkit-appearance: none;min-width:fit-content;background-color:#fff;font-weight:600 !important;" id="department_dropdown">
                    <option value="">Department&nbsp;&nbsp;&#9207;</option>
                    ${departmentOptions}
                </select>
            `);
            $('#departmenthead').html(departmentDropdown);
            $("#department_dropdown").on('change', function() {
                let selectedValue = $(this).val();
                let firstOption = $('#department_dropdown option:first');
                if (selectedValue) {
                    table.columns(6).search('^' + selectedValue + '$', true, false).draw();
                    $(this).addClass('text-primary');
                    $("#department_dropdown option").css('color', 'black');
                } else {
                table.column(6).search('').draw();
                $(this).removeClass('text-primary');
                $('#department_dropdown').val('');
                }
                firstOption.html('Department&nbsp;&nbsp;&#9207;');
            });

            let allProcessstages = "{{ $processstages}}".split(',');
            let processstagesOptions = allProcessstages.map(processstages => `<option value="${processstages}">${processstages}</option>`).join('');
            let processstagesDropdown = $(`
                <select class="headingselect text-center" style="border:none;-webkit-appearance: none;min-width:fit-content;background-color:#fff;font-weight:600 !important;" id="processstages_dropdown">
                    <option value="">Process&nbspStage&nbsp;&nbsp;&#9207;</option>
                    ${processstagesOptions}
                </select>
            `);
            $('#processstageshead').html(processstagesDropdown);
            $("#processstages_dropdown").on('change', function() {
                let selectedValue = $(this).val();
                let firstOption = $('#processstages_dropdown option:first');
                if (selectedValue) {
                    table.columns(7).search('^' + selectedValue + '$', true, false).draw();
                    $(this).addClass('text-primary');
                    $("#processstages_dropdown option").css('color', 'black');
                } else {
                table.column(7).search('').draw();
                $(this).removeClass('text-primary');
                $('#processstages_dropdown').val('');
                }
                firstOption.html('Process&nbspStage&nbsp;&nbsp;&#9207;');
            });
        });
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
            var e = Swal.mixin({
                buttonsStyling: !1,
                customClass: {
                    confirmButton: "btn btn-success m-1",
                    cancelButton: "btn btn-danger m-1",
                    input: "form-control"
                }
            });
            e.fire("Warning", "You are not authorized to add a new complaint. Please contact the admins.", "warning")
        }
    });
</script>
<script>
    One.loader('show');
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
        let tab;
        let table;
        let headings;

        tab = 'myComplaint';
        table = $('#tabledata').DataTable();

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

@endsection
