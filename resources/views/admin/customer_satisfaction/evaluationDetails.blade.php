@extends('admin.datatable')
@section('content')
<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
    }

    #myImg {
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
    }

    #myImg:hover {
        opacity: 0.7;
    }

    /* The Modal (background) */
    .modal1 {
        display: none;
        /* Hidden by default */
        position: fixed;
        /* Stay in place */
        z-index: 1;
        /* Sit on top */
        padding-top: 100px;
        /* Location of the box */
        left: 0;
        top: 0;
        width: 100%;
        /* Full width */
        height: 100%;
        /* Full height */
        overflow: auto;
        /* Enable scroll if needed */
        background-color: rgb(0, 0, 0);
        /* Fallback color */
        background-color: rgba(0, 0, 0, 0.9);
        /* Black w/ opacity */
    }

    /* Modal Content (image) */
    .modal1-content {
        margin: auto;
        display: block;
        width: 1200px;
        max-width: 1900px;
        height: auto;
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
    .modal1-content,
    #caption {
        -webkit-animation-name: zoom;
        -webkit-animation-duration: 0.6s;
        animation-name: zoom;
        animation-duration: 0.6s;
    }

    @-webkit-keyframes zoom {
        from {
            -webkit-transform: scale(0)
        }

        to {
            -webkit-transform: scale(1)
        }
    }

    @keyframes zoom {
        from {
            transform: scale(0)
        }

        to {
            transform: scale(1)
        }
    }

    /* The Close Button */
    .close {
        position: relative;
        top: 2px;
        right: 120px;
        /*margin-right:100px;*/
        color: white;
        font-size: 20px;
        font-weight: normal;
        transition: 0.3s;
    }
    .close1{
        float: right;
        font-size: 1.5rem;
        font-weight: 600;
        line-height: 1;
        color: #000;
        text-shadow: 0 1px 0 #fff;
        opacity: .5;
    }

    .close:hover,
    .close:focus {
        color: white;
        text-decoration: none;
        cursor: pointer;
    }

    /* 100% Image Width on Smaller Screens */
    @media only screen and (max-width: 700px) {
        .modal1-content {
            width: 100%;
        }
    }

</style>
<div class="content">
        @if(Session::has('message'))
        <div class="alert alert-primary">
            <a class="close1" data-dismiss="alert">×</a>
            {!!Session::get('message')!!}
        </div>
        @endif

        @if(Session::has('msg'))
        <div class="alert alert-primary">
            <a class="close1" data-dismiss="alert">×</a>
            {!!Session::get('msg')!!}
        </div>
        @endif
    <div class="block block-rounded">
        <div class="block-content block-content-full" style="width:84%;">

            <form action="{{route('customer.excel_upload')}}" enctype="multipart/form-data" method="POST" files="true">
                @csrf
                @if(count($errors) > 0 )
                @foreach($errors->all() as $error)
                <div class='alert alert-warning'>
                    {{ $error }}
                </div>
                @endforeach
                @endif

                @if(Session::has('errmsg'))
                <div class="alert alert-danger">
                    <a class="close" data-dismiss="alert">×</a>
                    {!!Session::get('errmsg')!!}
                </div>
                @endif

                {{-- <div class="form-group row" style="display:inline-block;width:49%;">
                    <label class="col col-form-label" style="font-size:13px;" for="Uploadfile">Upload</label>
                    <div class="col">
                        <input type="file" class="form-control" style="font-size: 13px;" id="excel_data" name="excel_data" value="" required />
                        <input type="submit" value="Upload Details" class="btn btn-primary" style="font-size:10px;/*margin-left:350px;margin-top:-60px*/" />
                    </div>
                </div> --}}
                <div class="row">
                <div class="form-group col-sm-8">
                    <label class="/*col col-form-label*/" style="font-size:13px;" for="Uploadfile">Upload</label>
                    <div class="input-group">

                        {{-- <input type="email" class="form-control form-control-alt" id="example-group3-input2-alt" name="example-group3-input2-alt" placeholder="Email"> --}}
                        <input type="file" class="form-control" style="font-size: 13px;" id="excel_data" name="excel_data" value="" required />
                        <div class="input-group-append">
                            {{-- <button type="button" class="btn btn-dark">Submit</button> --}}
                            <input type="submit" value="Upload Details" class="btn btn-primary" style="font-size:10px;/*margin-left:350px;margin-top:-60px*/" />
                        </div>
                    </div>
                </div>

                <div class="form-group col-sm-4 mt-4 pt-2">
                    {{-- <div class="col"> --}}
                        <a href="#" class="button btn btn-warning font-weight-normal" style="font-size:10px;font-family:Arial, Helvetica, sans-serif;" data-toggle="modal" data-target="#note">Note (*important)</a>
                    {{-- </div> --}}
                </div>
            </div>
                <!-- Note starts -->
                <div class="modal fade" id="note" tabindex="-1" role="dialog" aria-labelledby="modal-block-fadein" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="block block-rounded block-themed block-transparent mb-0">
                                <div class="block-header bg-primary-dark">
                                    <h3 class="block-title">*Important</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                            <i class="fa fa-fw fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="block-content font-size-sm">
                                    <div class="table-responsive">
                                        <span style="" style="font-size:10px;">1) The Checklist Details Excel Sheet Should have the Headings 'company, location, product/service, email, phone, customer_survey_emails' respectively and should be in the same sequence.</span>
                                        <br /><br /><span style="" style="font-size:10px;">2) Checklist Details Should Be In Single Excel Sheet.</span>
                                        <br /><br /><span style="" style="font-size:10px;">3) Company and Product/Service and Email are mandatory fields.</span>
                                        <br><br><span class="text-primary">Below is the Sample Excel Format, Click on the Image to view</span><br><br>

                                        <!-- sample excel file image starts here -->
                                        <img id="myImg" src="{{ asset('images/customer_satisfaction/check.png') }}" alt="Sample Excel File Format" title="Sample Excel File Format" style="width:100%;max-width:300px">
                                        <!-- The Modal -->
                                        <div id="myModal" class="modal1">
                                            <span class="close"><i class="fa fa-2x fa-window-close" style="color:white;"></i></span>
                                            <img class="modal1-content" id="img01">
                                            <div id="caption"></div>
                                        </div>
                                        <!--sample excel file image starts here -->
                                        <br><br>
                                        <span class="text-danger">Warning : Please do not merge cells</span>
                                        <br><br>
                                    </div>
                                </div>
                                <div class="block-content block-content-full text-right border-top">
                                    <button type="button" class="btn btn-alt-primary mr-1" data-dismiss="modal" style="font-size:10px;">Close</button>
                                    <button type="button" class="btn btn-primary" data-dismiss="modal" style="font-size:10px;">Ok</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Note ends -->
            </form>
        </div>
    </div>

    <!-- Dynamic Table Full -->
    <div class="block block-rounded">
        <div class="block-header">
            <h3 class="block-title">MS.08.01 <span class="text-danger">{{ ucfirst($config->name) }}</span>
                <input type="hidden" value="{{ $config->id }}" name="config_id" id="config_id" />
                {{-- <button class="btn btn-warning" onClick="editName()">edit</button>--}}
                <a href="#" class="btn btn-sm btn-alt-primary" style="font-size:10px;" title="Edit" data-toggle="modal" data-target="#editTableNameModal"><i class="fa fa-fw fa-pencil-alt"></i></a>
                <div class="modal fade" id="editTableNameModal" tabindex="-1" role="dialog" aria-labelledby="editTableNameModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editTableNameModalLabel">Edit Table Name</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">

                                <div class="alert alert-success" id="successBtnchecktablenameedit" style="display:none">
                                    Table Name Updated Successfully
                                </div>

                                <ul class="alert alert-danger" id="dangerValidationtablenameedit" style="display:none"></ul>

                                <div class="form-group">
                                    <label for="table_name" class="col-form-label" style="font-size:12px;">
                                        Table Name <span style="color:red">*</span></label>
                                    <input type="text" class="form-control" style="font-size:12px;" name="table_name" id="table_name" value="{{$config->name}}" required>
                                </div>

                            </div>


                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close
                                </button>
                                <button type="button" class="btn btn-primary" style="font-size:12px" onclick="updatetablename('{{$config->id}}')">Update
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </h3>
            <ol class="breadcrumb breadcrumb-alt">
                <li class="breadcrumb-item">Customer Satisfaction</li>
            </ol>
        </div>
        <div class="block-content block-content-full" style="width:84%;">
            <div class="table-responsive">
            <table class="table table-bordered table-striped table-vcenter js-dataTable-full" id="product_table">
                <thead>
                    <tr>
                        <th class="text-left" style="width: 2%;font-size:10px;text-transform:capitalize">SL No.</th>
                        <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Question
                        </th>
                        <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Weightage</th>
                        <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php $number = 1; ?>
                    @foreach($customer_product_type_details as $product_item)

                    {{csrf_field()}}

                    <tr>

                        <td style="font-size:10px" class="text-left">
                            <span>{{ $number }}</span>
                        </td>


                        <td style="font-size:10px" class="text-left">
                            <span>{{$product_item->question}}</span>
                        </td>

                        <td style="font-size:10px" class="text-left">
                            <span>{{$product_item->score}}</span>
                        </td>
                        <td style="font-size:10px" class="text-left">

                            <?php $number++; ?>

                            <div class="btn-group">
                                <a href="{{ route('admin.customer.editQstn', ['id' => $product_item->id])}}" class="btn btn-sm btn-alt-primary" style="font-size:10px;" data-toggle="tooltip" title="Edit"><i class="fa fa-fw fa-pencil-alt"></i></a>
                                <a href="{{ route('admin.customer.deleteQstn', ['id' => $product_item->id])}}" style="font-size:10px;" onclick="return ddlqts()" class="btn btn-sm btn-alt-primary" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>
                            </div>
                        </td>
                    </tr>

                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
    </div>
    <!-- END Dynamic Table Full -->

    <div class="block block-rounded">
        <div class="block-header">
            <h3 class="block-title">MS.08.02 Service</h3>
            <ol class="breadcrumb breadcrumb-alt">
                <li class="breadcrumb-item">Customer Satisfaction</li>
            </ol>
        </div>
        <div class="block-content block-content-full" style="width:84%;">
            <div class="table-responsive">
            <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
            <table class="table table-bordered table-striped table-vcenter js-dataTable-full" id="service_table">
                <thead>
                    <tr>
                        <th class="text-left" style="width: 2%;font-size:10px;text-transform:capitalize">SL No.</th>
                        <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Question
                        </th>
                        <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Weightage</th>
                        <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php $number = 1; ?>
                    @foreach($customer_service_type_details as $service_item)

                    {{csrf_field()}}

                    <tr>

                        <td style="font-size:10px" class="text-left">
                            <span>{{ $number }}</span>
                        </td>
                        <td style="font-size:10px" class="text-left">
                            <span>{{$service_item->question}}</span>
                        </td>

                        <td style="font-size:10px" class="text-left">
                            <span>{{$service_item->score}}</span>
                        </td>
                        <td style="font-size:10px" class="text-left">
                            <?php $number++; ?>
                            <div class="btn-group">
                                <a href="{{ route('admin.customer.editQstn', ['id' => $service_item->id])}}" style="font-size:10px;" class="btn btn-sm btn-alt-primary" data-toggle="tooltip" title="Edit"><i class="fa fa-fw fa-pencil-alt"></i></a>
                                <a href="{{ route('admin.customer.deleteQstn', ['id' => $service_item->id])}}" style="font-size:13px;" onclick="return ddlqts()" class="btn btn-sm btn-alt-primary" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>
                            </div>
                        </td>
                    </tr>

                    @endforeach

                </tbody>
            </table>
            </div>
        </div>
    </div>
    <div class="block block-rounded">
        <div class="block-header">
            <h3 class="block-title">MS.08.03 Project</h3>
            <ol class="breadcrumb breadcrumb-alt">
                <li class="breadcrumb-item">Customer Satisfaction</li>
            </ol>
        </div>
        <div class="block-content block-content-full" style="width:84%;">
            <div class="table-responsive">
            <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
            <table class="table table-bordered table-striped table-vcenter js-dataTable-full" id="project_table">
                <thead>
                    <tr>
                        <th class="text-left" style="width: 2%;font-size:10px;text-transform:capitalize">SL No.</th>
                        <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Question
                        </th>
                        <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Weightage</th>
                        <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php $number = 1; ?>
                    @foreach($customer_project_type_details as $project_item)

                    {{csrf_field()}}

                    <tr>

                        <td style="font-size:10px" class="text-left">
                            <span>{{ $number }}</span>
                        </td>
                        <td style="font-size:10px" class="text-left">
                            <span>{{$project_item->question}}</span>
                        </td>
                        <td style="font-size:10px" class="text-left">
                            <span>{{$project_item->score}}</span>
                        </td>
                        <td style="font-size:10px" class="text-left">
                            <?php $number++; ?>
                            <div class="btn-group">
                                <a href="{{ route('admin.customer.editQstn', ['id' => $project_item->id])}}" style="font-size:10px;" class="btn btn-sm btn-alt-primary" data-toggle="tooltip" title="Edit"><i class="fa fa-fw fa-pencil-alt"></i></a>
                                <a href="{{ route('admin.customer.deleteQstn', ['id' => $project_item->id])}}" style="font-size:13px;" onclick="return ddlqts()" class="btn btn-sm btn-alt-primary" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
        <!-- END Dynamic Table Full -->
    </div>
</div>
<script src="{{ asset('js/jquery.min.js') }}"></script>

<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    function addQstnButtonInsteadOfSearch(table_selector, table_selector_filter, url) {
        $(table_selector).one("preInit.dt", function() {

            let button = $(`<label><a href="${url}" style="font-size: 13px;" class="btn btn-primary text-right">Add Questions</a></label>`);

            $(table_selector_filter).empty().append(button);

            button.button();
        });

    }

    $(document).ready(function() {


        addQstnButtonInsteadOfSearch("#product_table", "#product_table_filter", "{{ route('admin.customer.addQstn', ['type' => 'product']) }}")
        addQstnButtonInsteadOfSearch("#service_table", "#service_table_filter", "{{ route('admin.customer.addQstn', ['type' => 'service']) }}")
        addQstnButtonInsteadOfSearch("#project_table", "#project_table_filter", "{{ route('admin.customer.addQstn', ['type' => 'project']) }}")
    });

    function ddlqts() {
        mess1 = "Are you sure?";
        x = confirm(mess1);
        return x;
    }

    // function editName() {
    //     $.ajax({
    //         data: {
    //             id: $('#config_id').val(),
    //             name: $('#product_name').val(),
    //             _token: CSRF_TOKEN
    //         },
    //         type: 'post',
    //
    //         success: function () {
    //             window.location.reload();
    //         },
    //         error: function (response) {
    //             window.location.reload();
    //             console.log(response);
    //         }
    //     });
    // }

    function updatetablename(id) {
        $.ajax({
            data: {
                name: $('#table_name').val()
                , id: $('#config_id').val()
                , _token: CSRF_TOKEN
            }
            , url: "{{ route('config.customer.editName') }}"
            , type: "POST"
            , success: function(result) {
                $('#successBtntablenameedit').css('display', 'block');
                $('#dangerValidationtablenameedit').css('display', 'none');
                setTimeout(function() {
                    window.location.reload();
                }, 1000);
            }
            , error: function(err) {
                $('#successBtntablenameedit').css('display', 'none');
                $('#dangerValidationtablenameedit').css('display', 'block');
                for (let i in err.responseJSON.errors) {
                    $('#dangerValidationtablenameedit').append(`<li>${err.responseJSON.errors[i]}</li>`)
                }
            }
        });
    }

</script>

{{-- for opening excel image --}}
<script>
    // Get the modal
    var modal = document.getElementById("myModal");
    // Get the image and insert it inside the modal - use its "alt" text as a caption
    var img = document.getElementById("myImg");
    var modalImg = document.getElementById("img01");
    var captionText = document.getElementById("caption");
    img.onclick = function() {
        modal.style.display = "block";
        modalImg.src = this.src;
        captionText.innerHTML = this.alt;
    }
    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];
    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }
    // $(".close1").on('click', function() {
    //     modal.style.display = "none";
    // })

</script>

@endsection
