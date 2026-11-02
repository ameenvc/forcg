@extends('layouts.backend')
@section('content')
<!doctype html>
<html lang="en">
<head>
    <style>
        .breadcrumb>li+li:before {
            display: none !important;
        }

        .breadcrumb {
            font-size: 15px;
            padding: 0.75rem 1.25rem;
            background: #F6F7F9;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }

        .risk-span-class {
            display: block;
            font-weight: bold;
            height: calc(1.5em + 0.75rem + 2px);
            padding: 0.375rem 0.75rem;
            font-size: 13px;
        }

        .high-rank {
            background: #F8D7D6;
            color: #E93A2D;
        }

        .moderate-rank {
            background: #FDF3D1;
            color: #F5C342;
        }

        .other-rank {
            background: #E5F0DB;
            color: #4EAD5B;
        }

    </style>
</head>
<body>

    <!-- Page Content -->
    <div class="content">


        <!-- Horizontal -->
        <div class="block block-rounded" style="box-shadow: 0px 0px 15px;background-color: #ffffff;">
            <nav aria-label="breadcrumb" style="display: flex; justify-content: space-between; background: #f6f7f9;">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item font-w700">
                        <a href="/user/corrective/corrissues" class="text-black">
                            NCR & Corrective Actions
                        </a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        Suggest Changes
                    </li>
                </ol>
                {{-- <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="/user/corrective/corrissues">Corrective Management</a>
                    </li>
                </ol> --}}
            </nav>

            <div class="block-content block-content-full">
                <!-- <div class="row">
                                <div class="col-lg-8"> -->
                <!-- Form Horizontal - Default Style -->
                <form action="{{route('corrective.suggestchanges')}}" method="post">
                    {{csrf_field()}}
                    <div class="jumbotron jumbotron-fluid">
                        <div class="container">
                            <input type="hidden" name="percent_status" value="{{ $status}}">
                            <textarea rows="4" class="form-control" name="remarks" required></textarea>
                            <br>

                            <div class="form-group col-sm-12" style="display:inline-block;width:98.5%;">
                                <label class="col-form-label" style="font-size: 13px;">Actions required to be modified again?</label>
                                <input style="margin-right:4px;margin-left:10px;" type="radio" value="yes" id="yes2" name="action_modified" required><label style="font-size: 13px;">Yes</label>
                                <input style="margin-right:4px;margin-left:10px;" type="radio" value="no" id="no2" name="action_modified"><label style="font-size: 13px;">No</label>
                            </div>

                            <input type="hidden" name="id" value="{{$id}}">
                            <input type="submit" value="Submit" class="btn btn-primary" style="font-size:13px;margin-left:83% " onclick="return confirm('Are You Sure To Submit')">
                            <input type="reset" value="Reset" class="btn btn-primary" style="font-size:13px;">
                        </div>
                    </div>
                </form>
                <!-- END Form Horizontal - Default Style -->

                <!-- </div>
                            </div> -->
            </div>
        </div>
        <!-- END Horizontal -->

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
                                    <!-- Sales -->
                                    <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/corrective/closedissue">
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-check-square fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Closed Issues
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Sales -->
                                </div>
                                <div class="col-6">
                                    <!-- Sales -->
                                    <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/corrective/openissue">
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-clone fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                My Open Issues
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Sales -->
                                </div>
                                <div class="col-6">

                                    <a class="block block-rounded block-link-shadow bg-body mb-0" href='/user/corrective/analysis' style="margin-top:6px">
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

</body>

<script src="{{ asset('js/jquery.min.js')}}"></script>
<script type="text/javascript">
    // $(document).ready(function() {
    //     $('#sel').select2({});
    //     $('#sel_freq').select2({});
    // });

</script>


</html>
@endsection
