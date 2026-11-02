@extends('layouts.backend')
@section('content')
<!doctype html>
<html lang="en">

                <div class="bg-body-light">
                    <div class="content content-full">
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">

                            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-alt">
                                    <li class="breadcrumb-item">Customer Complaint</li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="">Disapprove</a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="/user/complaintmgmt">Back</a>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <!-- END Hero -->

                <!-- Page Content -->
                <div class="content">


                   <!-- Horizontal -->
                    <div class="block block-rounded">

                        <div class="block-content block-content-full">
                            <div class="row">

                                <div class="col-lg-11">
<form action="{{route('complaintdissapprove')}}" method="post" >
    {{ csrf_field() }}
    {{-- <div class="jumbotron jumbotron-fluid"> --}}
            <div class="container">
              {{-- <p style="font-family:Arial;font-size:18px;margin-left:-20px" > - </p> --}}
              <h2 class="display-5" style="font-family:Arial;margin-left:200px">Reason For Disapproving</h2>
              <textarea rows="10"  class="form-control" name="reason" required></textarea>
              <br>
              <input type="hidden" name="id" value="{{$id}}">
              <input type="submit" value="Disapprove" class="btn btn-primary"style="font-size:13px;height:35px;margin-left:79%;" onclick="return confirm('Are You Sure To Disapprove')">
              <input type="reset" value="Reset"class="btn btn-primary"style="font-size:13px;height:35px;">
            </div>
        </form>

    </div>
</div>
</div>
</div>
</main>
</body>


</html>
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
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/complaint/closedc"style="margin-top:-16px">
                                    <div class="block-content text-center"style="height:130px">
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
<!-- END Apps Modal -->


</body>
</html>
@endsection




