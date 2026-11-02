@extends('admin.datatable')
@section('css_after')
<style>
    #myImg {
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
    }

    #myImg:hover {
        opacity: 0.7;
    }

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

    .close {
        position: relative;
        top: 2px;
        right: 120px;
        /*margin-right:100px;*/
        color: white;
        font-size: 20px;
        font-weight: normal;
        transition: 0.3s;
        cursor: pointer;
    }

    .close1 {
        float: right;
        font-size: 1.5rem;
        font-weight: 600;
        line-height: 1;
        color: #000;
        text-shadow: 0 1px 0 #fff;
        opacity: .5;
        cursor: pointer;
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
@endsection
@section('content')
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
            <form action="{{route('admin.product.excel_upload')}}" enctype="multipart/form-data" method="POST" files="true">
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
                    <a class="close1" data-dismiss="alert">×</a>
                    {!!Session::get('errmsg')!!}
                </div>
                @endif
                <div class="row">
                <div class="form-group col-sm-8">
                    <label class="/*col col-form-label*/" style="font-size:13px;" for="Uploadfile">Upload <span style="color:red">*</span></label>
                    <div class="input-group">

                        <input type="file" class="form-control" style="font-size: 13px;" id="excel_data" name="excel_data" value="" required />
                        <div class="input-group-append">
                            <input type="submit" value="Upload Details" class="btn btn-primary" style="font-size:10px;/*margin-left:350px;margin-top:-60px*/" />
                        </div>
                    </div>
                </div>

                <div class="form-group col-sm-4">
                    <div class="pt-4 mt-1">
                        <a href="#" class="button btn btn-warning font-weight-normal" style="font-size:10px;font-family:Arial, Helvetica, sans-serif;" data-toggle="modal" data-target="#note">Note (*important)</a>
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
                                        <span style="" style="font-size:10px;">1) Excel Sheet Should have the Column Name as 'Product_Service'.</span>
                                        <br /><br /><span style="" style="font-size:10px;">2) Product/Service List Details Should Be In Single Excel Sheet.</span>
                                        <br /><br /><span style="" style="font-size:10px;">3) Product_Service is a mandatory field.</span>
                                        <br><br><span class="text-primary">Below is the Sample Excel Format, Click on the Image to view</span><br><br>

                                        <!-- sample excel file image starts here -->
                                        <img id="myImg" src="{{ asset('images/product_service/check.png') }}" alt="Sample Excel File Format" title="Sample Excel File Format" style="width:100%;max-width:300px">
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
                </div>
            </form>
        </div>
    </div>
    <!-- Dynamic Table Full -->
    <div class="block block-rounded">
        <div class="block-header">
            <h3 class="block-title">Product/Service</h3>
        </div>
        <div class="block-content block-content-full" style="width:84%;">
            <div class="table-responsive">
                <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                    <thead>
                        <tr>
                            <th class="text-left" style="width: 1%;font-size:10px;text-transform:capitalize">SL No.</th>
                            <th class="d-sm-table-cell text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Product/Service</th>

                            <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Actions</th>


                        </tr>
                    </thead>
                    <tbody>
                        <?php $number = 1; ?>
                        @foreach($product as $c)

                        {{csrf_field()}}

                        <?php $id = $c->id ; $name=$c->name ?>
                        <tr>

                            <td style="font-size:10px" class="text-left">
                                <span>{{ $number }}</span>
                            </td>


                            <?php $number++; ?>
                            <td style="font-size:10px" class="text-left">
                                <span>{{$c->product}}</span>
                            </td>

                            <td class="text-left">
                                <div class="btn-group">


                                    <a href="{{url('/admin/editproduct?val='.$id)}}" class="btn btn-sm btn-alt-primary" style="font-size:10px" data-toggle="tooltip" title="Edit"><i class="fa fa-fw fa-pencil-alt"></i>
                                    </a>
                                    <a href="{{url('/admin/removeproduct?val='.$id)}}" class="btn btn-sm btn-alt-primary" onclick="return confirm('Are you sure?')" style="font-size:10px" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>

                            </td>
                        </tr>
                        @endforeach

                    </tbody>

                </table>
            </div>
            <div class="row p-2 mt-1">
                <div class="col-sm-6"></div>
                <div class="col-sm-6 text-right">
                    <form method="post" action="{{url('/admin/addproduct')}}" onsubmit="return validation()">
                        {{csrf_field()}}
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" style="font-size: 13px" placeholder="Enter product/service" name="product" id="type" required>
                                <div class="input-group-append">
                                    <input type="submit" name="update" class="btn btn-primary" style="font-size:12px" value="Add">
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
        
    </div>
    <!-- END Page Content -->
</div>
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

</script>
@endsection
