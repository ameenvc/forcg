@extends('pages.datatables')
@section('exportadd')
    <div class="input-group input-group-sm">
        <a href="" class="btn btn-sm btn-dual" style="margin-left:0;" onclick="return fnCovertTable('print')">
            <i class="fa fa-print  fa-1x" style="color:grey"></i>

        </a>
    </div>
    {{-- <div class="input-group input-group-sm">
        <button type="button" class=" btn-sm btn-dual btn btn-default dropdown-toggle mr-0 mb-0"
                style="margin-left:8px; " data-toggle="dropdown">
            <i class="fa fa-file-export fa-1x" style="color:grey;"></i>
        </button>
        <ul class="dropdown-menu" role="menu" style="min-width:10px;text-align:center;">
            <li>
                <!--change route as below-->
                <form method="post" action="{{url('/user/convert/file/'.'tvalue2')}}" style="margin-bottom: 0">
                    @csrf
                    <button onclick="return fnCovertTable('pdf')"
                            style="width:60px;  font-size:10px;border:none;background-color:white;">Pdf
                    </button>
                    <!--change hidden input type id as below-->
                    <input type="hidden" id="tvalue2" name="tvalue2"/>
                </form>
            </li>
            <li>
                <button onclick="fnCovertTable('excel')"
                        style="width:60px;  font-size:10px;border:none;background-color:white;">Excel
                </button>
            </li>
            <li>
                <button onclick="fnCovertTable('word')"
                        style="width:60px;  font-size:10px;border:none;background-color:white;">Word
                </button>
            </li>
        </ul>
    </div> --}}

    <div class="input-group input-group-sm">
        <a type="button" href="{{url('/user/complaint/addcustomer')}}" class="btn btn-sm btn-dual"
           style="margin-left:8px;">
            <i class="fa fa-1x fa-plus" style="color:grey;"></i>
        </a>
    </div>
@endsection
@section('content')
<style>
    .link-style {
        font-size: 12px;
        background-color: #F1F3F4;
        /*margin: auto;*/
        text-align: center;
        border: solid 1px #F1F3F4;
        border-radius: 5px;
        padding: 5px;
        width: 80%;
    }

    .link-input {
        border: none;
        background-color: #F1F3F4;
        /*width: inherit;*/
        width:230px;
    }

    .checkbox-position {
        position: fixed;
        left: 600px;
    }

    .cursor_pointer {
        cursor: pointer;
    }

    .border-none {
        border: none !important;
    }

    .tooltip1 {
        position: relative;
        display: inline-block;
    }

    .tooltip1 .tooltiptext {
        visibility: hidden;
        width: 140px;
        background-color: #555;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px;
        position: absolute;
        z-index: 1;
        bottom: 150%;
        left: 50%;
        margin-left: -75px;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .tooltip1 .tooltiptext::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: #555 transparent transparent transparent;
    }

    .tooltip1:hover .tooltiptext {
        visibility: visible;
        opacity: 1;
    }

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
</style>
<?php $data = $data2 = $data3 = []; ?>
<input type="hidden" value="a2" id="tabselected">
<div id="page-loader" class="show"></div>
<!-- Page Content -->
<div class="content" style="width:100%;">
    <!-- Dynamic Table Full -->
    <div class="block block-rounded" style="margin-left:-1%;">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb d-flex justify-content-between align-items-center">
                <div class="d-flex breadcrumb-slash">
                    <li class="breadcrumb-item font-w700">Customer List</li>
                    <li class="breadcrumb-item">Customer Satisfaction Management</li>
                </div>
                {{-- <li class="breadcrumb-item">
                    <a href="#" class="btn btn-sm btn-alt-primary" data-id="uploadModal" data-toggle="tooltip" data-placement="top" title="Upload Customer Details" onclick="UploadCustomer()">
                        <i class="fa fa-upload text-center" style="font-size:13px;cursor:pointer"></i> Upload
                    </a>
                </li> --}}
            </ol>
            
        </nav>
        <div class="block-content block-content-full">
            <div id="content" class="table-responsive">
                <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                <table class="table table-bordered table-striped table-vcenter" id="customerTable" style="width:100%;">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5%;position:sticky;top:-1" id="firstColumn"><input class="" type="checkbox" id="selectAll" name="id[]"></th>
                            <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 5%;position:sticky;top:-1">S.No</th>
                            <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 10%;position:sticky;top:-1">Customer Name</th>
                            <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 15%;position:sticky;top:-1">Location</th>
                            <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 16%;position:sticky;top:-1">Contact</th>
                            <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 15%;position:sticky;top:-1">Product/Service</th>
                            <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 5%;position:sticky;top:-1">Actions</th>
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
        <div class="modal fade" id="request_survey" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Request survey</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="alert alert-success" id="successBtn" style="display:none;font-size:12px;">
                            Mail sent successfully!
                        </div>

                        <ul class="alert alert-danger" id="dangerBtn" style="display:none;font-size:12px;"></ul>
                        <input type="hidden" name="request_survey_id" id="request_survey_id">
                        <div class="form-group">
                            <label for="survey_forms" class="col-form-label"  style="font-size:12px;"> Survey forms <span class="text-danger">*</span></label>
                            <label for="survey_forms"></label>
                            <select class="form-control js-select2" name="survey_forms" id="survey_forms"  onchange="changeType()" style="font-size:12px;" required>
                                <option value="0" selected>Choose Type</option>
                                <option value="product">Product</option>
                                <option value="service">Service</option>
                                <option value="project">Project</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="customer_emails" class="col-form-label" style="font-size:12px;">Customer email addresses to contact for survey</label>
                            <p class="text-danger" style="font-size:10px;">please separate emails with comma</p>
                            <input type="text" class="form-control" style="font-size:12px;position:relative;bottom:10px;" name="customer_emails" id="customer_emails" required />
                        </div>

                
                        <div class="form-group row">
                            <div class="col-8">
                                <label for="send_survey_link" class="col-form-label"  style="font-size:12px;">Copy the survey link</label>
                            </div>
                            <div class="col-4">
                                <div class="custom-control custom-switch custom-control-success mb-1">
                                    <input type="checkbox" name="send_survey_link" id="send_survey_link" class="custom-control-input checkbox-position" onchange="toggleShowingSurveyLink()">
                                    <label class="custom-control-label" for="send_survey_link"></label>
                                </div>
                            </div>

                        </div>
                        <div class="link-style ml-0 row d-none" id="link_of_survey">
                            <div class="col p-0">
                                <label for="link_of_survey">
                                    <input type="text" class="link-input" id="link_to_copy" disabled>
                                </label>
                            </div>
                            <div class="col text-right">
                                <div class="tooltip1">
                                    <button onclick="copyFormLink()" onmouseout="outFunc()"class="border-none">
                                        <span class="tooltiptext" id="myTooltip">Copy to clipboard</span>
                                        <i class="fa fa-copy cursor_pointer" onclick="copyFormLink()"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="font-size:12px">Cancel</button>
                        <button type="button" class="btn btn-primary"style="font-size:12px" id="send_button"onclick="sendEmail()">Send</button>
                    </div>

                </div>
            </div>
        </div>
        <div class="modal fade" id="auto_survey" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Set Auto survey
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-8">
                                <label for="auto_survey_switch" class="col-form-label" style="font-size:12px;">Auto Survey</label>
                            </div>
                            <div class="col-4">
                                <div
                                    class="custom-control custom-switch custom-control-success mb-1">
                                    <input type="checkbox" name="auto_survey_switch" id="auto_survey_switch" class="custom-control-input checkbox-position" value="" >
                                    <input type="hidden" name="auto_surveyID" id="auto_surveyID">
                                    <input type="hidden" name="auto_surveyEmail" id="auto_survey_email">
                                    <label class="custom-control-label" for="auto_survey_switch"></label>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="font-size:12px">Cancel</button>
                        <button type="button" class="btn btn-primary" style="font-size:12px" id="send_button" onclick="sendEmail()">Send</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<script src="{{ asset('js/jquery.min.js') }}"></script>
<script>
    $(document).ready(function(){
        var table = $('#customerTable').DataTable({
            processing:true,
            serverSide:true,
            ajax:{
                url : "{{url('/user/complaint/getAllCustomers')}}",
                type: 'GET',
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
                    render: function (data,type,row) {
                        let id = row.id;
                        let emails = row.customer_survey_emails || '';
                        emails = emails.replace(/'/g, "\\'");
                        return `
                            <div class="btn-group m-auto d-flex justify-content-center">
                                <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown"
                                    data-placement="top" title="Actions" data-id="tool${id}" id="drop${id}">
                                    <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer"></i>
                                </a>
                                <div class="dropdown-menu font-size-sm" aria-labelledby="drop${id}">
                                    <a href="#" style="font-size:10px" class="dropdown-item" data-toggle="modal"
                                         onclick="ViewRequestSurveyModal(${id},'${emails}')">Request Survey</a>
                                    <div class="dropdown-divider"></div>
                                    <a href="#" style="font-size:10px" class="dropdown-item" data-toggle="modal"
                                        onclick="ViewAutoSurveyModal(${id},'${emails}')">Set Auto Survey</a>
                                    <div class="dropdown-divider"></div>
                                    <a href="{{url('/user/customer/history/${id}')}}" style="font-size:10px" class="dropdown-item">Survey History</a>
                                    <div class="dropdown-divider"></div>
                                    <a href="#" style="font-size:10px" class="dropdown-item">Analysis</a>
                                </div>
                                <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="tooltip" data-id="${id}" title="View" onclick="ViewBtn(${id})">
                                    <i class="far fa-eye text-center" style="font-size:10px;cursor:pointer"></i>
                                </a>
                            </div>`;
                    },
                    orderable: false
                }
            ],
            pageLength: 25,
            order: [[0, 'desc']],
            search: true,
            drawCallback: function() {
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
                [25, 50, 75, 100, 1000,1500,2000],
                ['25', '50', '75', '100', '1000','1500','2000']
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
    One.loader('show');
    function ViewBtn(id) {
        var formData = new FormData();
        formData.append('id',id);
        $.ajax({
            url: "{{ url('/user/complaint/viewcustomermodal') }}",
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
    function ViewRequestSurveyModal(id,survey_mail){
        $('#request_survey_id').val(id);
        $('#customer_emails').val(survey_mail);
        $('#send_survey_link').attr('checked',false);
        $('#request_survey').modal('show');

    }
    function ViewAutoSurveyModal(id,survey_mail){
        $('#auto_surveyID').val(id);
        $('#auto_survey_email').val(survey_mail);
        $('#auto_survey').modal('show');
    }
</script>
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    let app_url = "{{ $app_url }}";
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
                        window.location.href = response.redirect_url;
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

</script>
<script>
    function toggleShowingSurveyLink() {
        let typeVal = $(`#survey_forms`).val();
        let id = $('#request_survey_id').val();
        $(`#link_to_copy`).val('user/customer/showQstn/' + id + (typeVal !== '0' ? '/' + typeVal : ''));
        let selector = $(`#link_of_survey`);
        if (document.getElementById(`send_survey_link`).checked) {
            selector.removeClass('d-none');
            selector.addClass('d-contents');
        } else {
            selector.addClass('d-none');
            selector.removeClass('d-contents');
        }
    }

    function copyFormLink() {
        let copyText = document.getElementById(`link_to_copy`);
        let typeVal = $(`#survey_forms`).val();
        let shortUrl = "";
        let id = $('#request_survey_id').val();
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        let url = 'user/customer/showQstn/' + id + (typeVal !== '0' ? '/' + typeVal : '');
        navigator.clipboard.writeText(app_url + url);
        let tooltip = document.getElementById(`myTooltip`);
        tooltip.innerHTML = "Copied the link";
    }

    function outFunc() {
        let tooltip = document.getElementById(`myTooltip`);
        tooltip.innerHTML = "Copy to clipboard";
    }

    function changeType() {
        if (document.getElementById(`send_survey_link`).checked) {
            toggleShowingSurveyLink();
        }
    }

    function sendEmail() {
        let typeVal = $(`#survey_forms`).val();
        let customer_emails = null;
        if($('#customer_emails').val()==''){
            customer_emails = $(`#auto_survey_email`).val();
        }else{
            customer_emails = $('#customer_emails').val();
        }
        let id = null;
        if($('#request_survey_id').val()==''){
            id = $('#auto_surveyID').val();
        }else{
            id = $('#request_survey_id').val();
        }
        let success_selector = $('#successBtn');
        let error_selector = $('#dangerBtn');
        error_selector.empty();
        error_selector.css('display', 'none');
        $(`#send_button`).attr('disabled', true);
        $.ajax({
            type: 'post'
            , data: {
                type: typeVal,
                id: id,
                customer_emails: customer_emails,
                _token: CSRF_TOKEN
            }
            , url: "{{route('customer.ajax_send_mail')}}"
            , headers: {
                'accept': 'application/json'
            }
            , success: function () {
                $(`#send_button`).attr('disabled', false);
                success_selector.css('display', 'block');
                error_selector.css('display', 'none');
            }
            , error: function (err) {
                $(`#send_button`).attr('disabled', false);
                error_selector.css('display', 'block');
                success_selector.css('display', 'none');
                if(err.status === 500){
                    console.log(err);
                    error_selector.append(`<li>Some error happend. we will try to solve it soon.</li>`)
                } else if(err.status === 422){
                    for (let i in err.responseJSON.errors) {
                        error_selector.append(`<li>${err.responseJSON.errors[i]}</li>`)
                    }
                }
            }
        });
    }
</script>

<script>
    function autoResizeDiv(height) {
        document.getElementById('content').style.height = (height) + 'px';
    }

    window.onresize = autoResizeDiv;
    autoResizeDiv(window.innerHeight - 100);

</script>

<!-- Apps Modal -->
<!-- Opens from the modal toggle button in the header -->
<div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps"
        aria-hidden="true">
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

                            <a class="block block-rounded block-link-shadow bg-body"
                                href="/user/complaint/addcustomer">
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

                            <a class="block block-rounded block-link-shadow bg-body mb-0"
                                href="/user/complaintmgmt">
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
                            <a class="block block-rounded block-link-shadow bg-body mb-0"
                                href="/user/complaint/list">
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
                            <a class="block block-rounded block-link-shadow bg-body mb-0"
                                href="/user/complaint/closedc" style="margin-top:-16px">
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
@section('sideitem')
    {{-- @foreach($mr as $r) --}}
    {{-- @if(Auth::user()->email==$r) --}}
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
    {{-- @endif --}}
    {{-- @endforeach --}}
@endsection
