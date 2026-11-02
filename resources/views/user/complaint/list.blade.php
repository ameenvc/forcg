@extends('pages.datatables')
@section('exportadd')
<div class="input-group input-group-sm">
    <a href="" class="btn btn-sm btn-dual" style="margin-left:0px;" onclick="return fnCovertTable('print')">

        <i class="fa fa-print  fa-1x" style="color:grey"></i>

    </a>
</div>
{{--<div class="input-group input-group-sm">
    <button type="button" class=" btn-sm btn-dual btn btn-default dropdown-toggle mr-0 mb-0" style="margin-left:8px; " data-toggle="dropdown">
        <i class="fa fa-file-export fa-1x" style="color:grey;"></i>
    </button>
    <ul class="dropdown-menu" role="menu" style="min-width:10px;text-align:center;">
        <li>
            <!--change route as below-->
            <form method="post" action="{{url('/user/convert/file/'.'tvalue2')}}" style="margin-bottom: 0px">
                @csrf
                <button onclick="return fnCovertTable('pdf')" style="width:60px;  font-size:10px;border:none;background-color:white;">Pdf</i></button>
                <!--change hidden input type id as below-->
                <input type="hidden" id="tvalue2" name="tvalue2" />
            </form>
        </li>
        <li><button onclick="fnCovertTable('excel')" style="width:60px;  font-size:10px;border:none;background-color:white;">Excel</button></li>
        <li><button onclick="fnCovertTable('word')" style="width:60px;  font-size:10px;border:none;background-color:white;">Word</button></li>
    </ul>
</div>--}}

<div class="input-group input-group-sm">
    <a type="button" href="{{url('/user/complaint/addcustomer')}}" class="btn btn-sm btn-dual" style="margin-left:8px;" style="">
        <i class="fa fa-1x fa-plus" style="color:grey;"></i>
    </a>
</div>
@endsection
@section('content')
<style>
    .font-15{
        font-size:12px !important;
    }
    /*------------ Customized DataTables Styles--------------------*/
    #customerTable td{
        font-size: 10px;
        text-align: center;
    }
    .dataTables_length label::before,
    .dataTables_length label::after {
        content: '';
    }
    #customerTable_filter label::before,
    #customerTable_filter label::after {
        content: '';
    }

    #firstColumn::after{
        content: '' !important;
    }
    /*------------ Customized DataTables End--------------------*/
    .breadcrumb-slash >li+li:before {
        display: none !important;
    }
    .select2-results__options{
        font-size:12px !important;
    }
    .breadcrumb {
        font-size: 15px;
        padding: 0.75rem 1.25rem;
        background: #F6F7F9;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }
    #myImg {
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
    }

    #myImg:hover {opacity: 0.7;}

    /* The Modal (background) */
    .modal1 {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        padding-top: 100px; /* Location of the box */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
    }

    /* Modal Content (image) */
    .modal1-content {
        margin: auto;
        display: block;
        width: 1200px;
        max-width: 1900px;
        height:auto;
    }

    /* Caption of Modal Image */
    #caption {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        text-align: center;
        color: #ccc;
        padding: 10px 0;
        height: 150px;
    }

    /* Add Animation */
    .modal1-content, #caption {
        -webkit-animation-name: zoom;
        -webkit-animation-duration: 0.6s;
        animation-name: zoom;
        animation-duration: 0.6s;
    }

    @-webkit-keyframes zoom {
    from {-webkit-transform:scale(0)}
    to {-webkit-transform:scale(1)}
    }

    @keyframes zoom {
    from {transform:scale(0)}
    to {transform:scale(1)}
    }

    /* The Close Button */
    #myModal > .close {
        position: relative;
        top: 2px;
        right: 125px;
        /*margin-right:100px;*/
        color: white;
        font-size: 20px;
        font-weight: normal;
        transition: 0.3s;
    }

    #myModal > .close:hover,
    #myModal > .close:focus {
        color: white;
        text-decoration: none;
        cursor: pointer;
    }

    /* 100% Image Width on Smaller Screens */
    @media only screen and (max-width: 700px){
        .modal1-content {
            width: 100%;
        }
    }
</style>
<?php $data=$data2=$data3=[];?>
<input type="hidden" value="a2" id="tabselected">
<!-- Page Content -->
<div id="page-loader" class="show"></div>
<div class="content" style="width:100%;">
    <!-- Dynamic Table Full -->
    @if(count($errors) >0 )
        @foreach($errors->all() as $error)
        <div class='alert alert-warning'>
                {{ $error }}
        </div>
        @endforeach
    @endif
    @if(Session::has('msg'))
        <div class="alert alert-primary">
            <a class="close" data-dismiss="alert">×</a>
                {!!Session::get('msg')!!}
        </div>
    @endif
    <div class="block block-rounded" style="box-shadow: 0px 0px 15px;background-color: #ffffff;">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb d-flex justify-content-between align-items-center">
                <div class="d-flex breadcrumb-slash">
                    <li class="breadcrumb-item font-w700">Customer List</li>
                    <li class="breadcrumb-item">Customer Complaint</li>
                </div>
                <li class="breadcrumb-item">
                    <a href="#" class="btn btn-sm btn-alt-primary" data-id="uploadModal" data-toggle="tooltip" data-placement="top" title="Upload Customer Details" onclick="UploadCustomer()">
                        <i class="fa fa-upload text-center" style="font-size:13px;cursor:pointer"></i> Upload
                    </a>
                </li>
            </ol>

        </nav>
        <div class="block-content block-content-full">
            <div id="content" class="table-responsive">
                <table class="table table-bordered table-striped table-vcenter" id="customerTable" style="width:100%;">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5%;position:sticky;" id="firstColumn"><input class="" type="checkbox" id="selectAll" name="id[]" data-toggle="tooltip" title="Select All" data-position="top"></th>
                            <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 5%;position:sticky;top:-1">S.No</th>
                            <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 10%;position:sticky;top:-1">Customer Name</th>
                            <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 15%;position:sticky;top:-1">Location</th>
                            <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 16%;position:sticky;top:-1">Contact</th>
                            <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 15%;position:sticky;top:-1">Product/Service</th>
                            <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 5%;position:sticky;top:-1">View</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END Dynamic Table Full -->
        <div id="viewModal" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title">Detailed View</h2>
                    </div>
                    <table class="table table-striped">
                        <tr>
                            <th style="width:70px">Customer Name </th>
                            <td id="customer"></td>
                        </tr>
                        <tr>
                            <th style="width:70px">Customer Code </th>
                            <td id="customercode"></td>
                        </tr>
                        <tr>
                            <th>Location</th>
                            <td id="location"></td>
                        </tr>
                        <tr>
                            <th>Contact</th>
                            <td id="contact"></td>
                        </tr>
                        <tr>
                            <th>Product/Service</th>
                            <td id="product"></td>
                        </tr>
                        <tr>
                            <th>Country</th>
                            <td id="country"></td>
                        </tr>
                        <tr>
                            <th>State</th>
                            <td id="state"></td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td id="phone"></td>
                        </tr>
                        <tr>
                            <th>Customer email addresses to contact for survey</th>
                            <td id="extraemails"></td>
                        </tr>
                    </table>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="uploadModal" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="block-header block-header-default bg-primary-dark">
                <h3 class="block-title text-white">Upload Customer Details</h3>
                <div class="block-options">
                  <button type="button" class="btn-block-option text-white" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-fw fa-times"></i>
                  </button>
                </div>
            </div>
            <div class="block-content fs-sm">
                <div class="row">
                    <div class="form-group col-sm-12">
                        <div class="form-group col-sm-12">
                            <label for="Upload Customer Excel" style="font-size:12px;" class="form-control-label col-sm-12">Upload Customer Details</label>
                            <div class="col-sm-6 input-group">
                                <input type="file" name="customer_details" id="customer_details" class="form-control" style="font-size:11px;" required>
                                <button type="button" class="btn btn-primary btn-sm input-group-append" id="upload" style="font-size:11px;" onclick="uploadExcel()">Upload Details</button>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="col-sm-12">
                                <span style="font-size:12px;">1) The Customer Details Excel Sheet Should have the Headings <strong>'customer_name, products, country, state, location, customer_code, email, phone, customer_email_addresses'</strong> respectively and should be in the same sequence.</span>
                            <br/><span style="font-size:12px;">2) Customer Details Should Be In Single Excel Sheet.</span>
                            <br/><span style="font-size:12px;">3) Customer Name, Products Name, Country Name and Customer Code are mandatory fields.</span>
                            <br/><span style="font-size:12px;">4) Products Name must be similar to the Product/Service table and also present in the Product/Service table.</span>
                            <br><span style="font-size:12px;" class="text-primary">Below is the Sample Excel Format, Click on the Image to view</span><br><br>
                            <!-- sample excel file image starts here -->
                            <img id="myImg" src="{{ asset('images/complaint/add_customer_excel_example.png') }}" alt="Sample Excel File Format" title="Sample Excel File Format" style="width:100%;max-width:300px">
                            <!-- The Modal -->
                            <div id="myModal" class="modal1">
                                <span class="close"><i class="fa fa-2x fa-window-close" style="color:white;"></i></span>
                                <img class="modal1-content" id="img01" style="height:100%;">
                                <div id="caption"></div>
                            </div>
                            <!--sample excel file image starts here -->
                            <br><br>
                            <span class="text-danger">Warning : Please do not merge cells</span>
                            <br><br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        var table = $('#customerTable').DataTable({
            processing:true,
            serverSide:true,
            ajax:{
                url: window.location.protocol + "//" + window.location.host + "/user/complaint/getAllCustomers",
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataSrc: function(json) {
                    return json.data;
                }
            },
            columns: [
                {
                    data: 'id',
                    render: function (data, type, row, meta) {
                        return `<input class="text-center font-size-sm selectItem" type="checkbox" id="${row.id}" name="id[]">`;
                    },
                    orderable: false
                },
                {
                    data: 'id',
                    render: function (data, type, row, meta) {
                        // return meta.row + 1;
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    orderable: false
                },
                { data: 'customer', render: function(data) { return `<a href="#">${data}</a>`; }},
                { data: 'location', render: function(data) { return data ? data : '--'; }},
                { data: 'email', render: function(data) { return data ? data : '--'; }},
                { data: 'product_name', render: function (data) { return data ? data : '--';}},
                {
                    data: 'id',
                    render: function (data) {
                        return `<a href="#" class="btn btn-sm btn-alt-primary" data-toggle="tooltip" data-id="${data}" title="View" onclick="ViewBtn(${data})">
                                    <i class="far fa-eye text-center" style="font-size:10px;cursor:pointer"></i>
                                </a>`;
                    },
                    orderable: false
                }
            ],
            pageLength: 25,
            order: [[0, 'desc']],
            search: true,
            drawCallback: function() {
                $('#selectAll').prop('checked', false);
                $('[data-toggle="tooltip"]').tooltip();
            },
            language: {
                info: "Page <strong>_PAGE_</strong> of <strong>_PAGES_</strong>",
                infoEmpty: "No entries to show",
                infoFiltered: "",
                paginate: {
                    previous: '<i class="fa fa-angle-left"></i>',
                    next: '<i class="fa fa-angle-right"></i>',
                },
            },
            lengthMenu: [
                [25, 50, 75, 100, 500,1000,1500,2000],
                ['25', '50', '75', '100', '500','1000','1500','2000']
            ],
        });
        $('#customerTable_filter input').attr("placeholder","Search..");

        $('.dataTables_length label').each(function(){
            var $label = $(this);
            $label.contents().filter(function() {
                return this.nodeType === Node.TEXT_NODE;
            }).remove();
            $label.find('select').prevAll().remove();
            $label.find('select').nextAll().remove();
        });
        $('#customerTable_filter label').each(function(){
            var $label = $(this);
            $label.contents().filter(function() {
                return this.nodeType === Node.TEXT_NODE;
            }).remove();
            $label.find('input').prevAll().remove();
        });
        $('#selectAll').on('click', function() {
            var rows = table.rows({ 'search': 'applied' }).nodes();
            $('input[type="checkbox"]', rows).prop('checked', this.checked);
        });
        $('#customerTable tbody').on('change', 'input[type="checkbox"]', function() {
            if (!this.checked) {
                $('#selectAll').prop('checked', false);
            }
            if ($('input.selectItem:checked').length === $('input.selectItem').length) {
                $('#selectAll').prop('checked', true);
            }
        });
    });
</script>
<script>
    One.loader('show');
    function ViewBtn(id) {
        var formData = new FormData();
        formData.append('id',id);
        $.ajax({
            url: window.location.protocol + "//" + window.location.host +'/user/complaint/viewcustomermodal',
            type: 'POST',
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                'accept': 'application/json'
            },
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#customer').text(response.customername);
                $('#customercode').text(response.customercode);
                $('#location').text(response.location);
                $('#contact').text(response.contact);
                $('#product').text(response.products);
                $('#country').text(response.country);
                $('#state').text(response.state);
                $('#phone').text(response.phone);
                $('#extraemails').text(response.customer_survey_emails);
                $('#viewModal').modal('show');
            },
        });
    }
    function UploadCustomer(){
        $('#uploadModal').modal('show');
    }
    function uploadExcel(){
        var formData = new FormData();
        var fileInput = $('#customer_details')[0].files[0];
        if (fileInput) {
            formData.append('customer_details', fileInput);
            Swal.fire({
                text: 'Please wait! Processing your request...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            $.ajax({
                url: "{{ url('/user/complaint/addcustomerexcel') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                    'accept': 'application/json'
                },
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        Swal.close();
                        Swal.fire({
                            icon: 'success',
                            text: response.message
                        });
                        setTimeout(function() {
                            window.location.reload();
                        }, 2000);
                    } else {
                        if (response.errors) {
                            var err = response.errors;
                            if(response.errors.customer_details){
                                err = response.errors.customer_details;
                            }
                            Swal.close();
                            Swal.fire({
                                icon: 'warning',
                                text: err
                            });
                        } else {
                            Swal.close();
                            Swal.fire({
                                icon: 'error',
                                text: response.message
                            });
                        }
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        text: 'Internal Server Error, we are trying to fix them soon.'
                    });
                },
            });
        } else {
            Swal.fire({
                text: "Please select a file to upload."
                , icon: "error"
                , showCancelButton: true
                , showConfirmButton: false
                , cancelButtonColor: "#d33"
                , cancelButtonText: "Close"
            , });
        }
    }
</script>
<script>
    // Get the modal
    var modal = document.getElementById("myModal");
    // Get the image and insert it inside the modal - use its "alt" text as a caption
    var img = document.getElementById("myImg");
    var modalImg = document.getElementById("img01");
    var captionText = document.getElementById("caption");
    img.onclick = function(){
        modal.style.display = "block";
        modalImg.src = this.src;
        captionText.innerHTML = this.alt;
    }
    // Get the <span> element that closes the modal
    // var span = document.getElementsByClassName("close")[0];
    var span = document.querySelector('#myModal > .close');
    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }
</script>
{{-- --------edit  button ------------}}
<script>
    $("#edit").click(function() {
        var dataArr = new Array();
        var group = new Array();
        var d;
        var all;
        var selectAll;
        if ($('input:checkbox:checked').length > 0) {
            $('input:checkbox:checked').each(function() {
                var id = $(this).attr('id');
                if (id !== 'selectAll') {
                    dataArr.push(id);
                }
            });
            all = dataArr.join(", ");
            group.push(all);
            if(all){
                Swal.fire({
                    text: 'Please wait! You are being redirected to the edit page...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                var formData2  = new FormData();
                formData2.append('id',all);
                $.ajax({
                    type: 'POST',
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                        'accept': 'application/json'
                    },
                    data: formData2,
                    processData: false,
                    contentType: false,
                    url: "{{route('editcustomer')}}",
                    success: function(response) {
                        window.location.assign(response.redirect_url);
                    },
                    error: function(response) {
                        console.log(response);
                    }
                });
            }else{
                Swal.fire({
                    text: "No Record Selected."
                    , icon: "error"
                    , showCancelButton: true
                    , showConfirmButton: false
                    , cancelButtonColor: "#d33"
                    , cancelButtonText: "Close"
            , });
            }

        } else {
            Swal.fire({
                text: "No Record Selected."
                , icon: "error"
                , showCancelButton: true
                , showConfirmButton: false
                , cancelButtonColor: "#d33"
                , cancelButtonText: "Close"
            , });
        }
    });
    $("#del").click(function() {
        var dataArr = new Array();
        var group = new Array();
        var d;
        var all;
        if ($('input:checkbox:checked').length > 0) {
            $('input:checkbox:checked').each(function() {
                var id = $(this).attr('id');
                if (id !== 'selectAll') {
                    dataArr.push(id);
                }
            });
            all = dataArr.join(", ");
            var formData = new FormData();
            if(all){
                formData.append('id',all);
                Swal.fire({
                    text: 'Please wait! Processing your request...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                $.ajax({
                    url: "{{route('deletecustomer')}}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                        'accept': 'application/json'
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        Swal.close();
                        Swal.fire({
                            icon: 'success',
                            text: "Customers have been deleted successfully!"
                        });
                        setTimeout(function() {
                            window.location.reload();
                        }, 2000);
                    }
                    , error: function(response) {
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            text: "Internal Server Error, we are trying to fix them soon."
                        });
                    }
                });
            }else{
                Swal.fire({
                    text: "No Record Selected."
                    , icon: "error"
                    , showCancelButton: true
                    , showConfirmButton: false
                    , cancelButtonColor: "#d33"
                    , cancelButtonText: "Close"
            , });
            }


        } else {
            Swal.fire({
                text: "No Record Selected."
                , icon: "error"
                , showCancelButton: true
                , showConfirmButton: false
                , cancelButtonColor: "#d33"
                , cancelButtonText: "Close"
            , });
        }
    });

    function autoResizeDiv(height) {
        document.getElementById('content').style.height = (height) + 'px';
    }
    window.onresize = autoResizeDiv;
    autoResizeDiv(window.innerHeight - 100);

</script>
@section('sideitem')
@if(Gate::allows('edit-complaint') || Gate::allows('edit-all'))
<div class="input-group input-group-sm ml-2">
    <button type="button" class="btn btn-sm btn-dual" id="edit" value=id[]>
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
@endsection
