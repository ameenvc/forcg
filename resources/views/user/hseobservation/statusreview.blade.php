@extends('layouts.backend')
@section('content')
<style>
    .border-3 {
        border-width: 3px !important;
    }

    .font-15{
        font-size:12px !important;
    }
    .breadcrumb>li+li:before {
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
<!-- Page Content -->
<div class="content">
    <!-- Horizontal -->
    <div class="block block-rounded" style="box-shadow: 0px 0px 15px;background-color: #ffffff;">
        <nav aria-label="breadcrumb" style="display: flex; justify-content: space-between; background: #f6f7f9;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item font-w700">
                    <a href="{{ route('hseobservation.index') }}" class="text-black">
                        HSE Observations
                    </a>
                </li>
                <li class="breadcrumb-item" aria-current="page">
                    Review Status
                </li>
            </ol>
        </nav>
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Form Horizontal - Default Style -->
                    <form class="mb-5" action="{{route ('hseobservation.approved')}}" enctype="multipart/form-data" method="post" files="true">
                        {{csrf_field()}}
                        <input type="hidden" name="posted" value="{{Auth::user()->email}}">
                        <input type="hidden" name="postedby" value="{{Auth::user()->employeeid}}">
                        <?php $rr=$data->id ?>
                        <input type="hidden" name="id" value="{{$data->id}}">
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="raised" style="font-size:13px;">Issue id</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="name" style="font-size:13px;" name="name" value="{{$data->issueid}}" readonly="">
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="title" style="font-size:13px;">Title <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="title" style="font-size:13px;" name="title" value="{{$data->title}}" required>
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="category" style="font-size:13px;">Category</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="category" style="font-size:13px;" name="category" value="{{$data->categoryName}}" readonly="">
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="department" style="font-size:13px;">Department</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="department" style="font-size:13px;" name="department" value="{{$data->departmentHse?$data->departmentHse->departmentname:$data->department}}" readonly="">

                                </div>
                            </div>
                            @if($data->division)
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="division">Division</label>
                                <div class="col-sm-12">
                                    <div class="form-group" style="color:#666666">
                                        <input type="text" class="form-control" style="font-size: 13px;" value="{{$data->division->name}}" readonly>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if($data->subdiv)
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="department">Subdivision</label>
                                <div class="col-sm-12">
                                    <div class="form-group" style="color:#666666">
                                        <input type="text" class="form-control" style="font-size: 13px;" value="{{$data->subdiv->name}}" readonly>
                                    </div>
                                </div>
                            </div>
                            @endif


                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="type" style="font-size:13px;">Type</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="type" style="font-size:13px;" name="type" value="{{$data->type}}" readonly="">

                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="date" style="font-size:13px;">Due date <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="date" class="form-control" style="font-size:13px;" id="date" name="duedate" required value="{{$data->duedate}}">
                                </div>
                            </div>


                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" for="observation" style="font-size:13px;">Observation <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <textarea type="text" class="form-control" id="observation" style="font-size:13px;" required name="observation" placeholder="Enter observation">{{$data->observation}}</textarea>
                                </div>
                            </div>

                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" for="agreedaction" style="font-size:13px;">Recommended action</label>
                                <div class="col-sm-12">
                                    <textarea type="text" class="form-control" style="font-size:13px;" id="agreedaction" name="agreedaction">{{$data->agreedaction}}</textarea>
                                </div>
                            </div>


                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" for="correction" style="font-size:13px;">Correction <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <textarea rows="3" class="form-control" id="correction" style="font-size:13px;" required name="correction">{{$data->correction}}</textarea>
                                </div>
                            </div>

                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" for="rootcause" style="font-size:13px;">Root cause <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <textarea rows="3" class="form-control" id="rootcause" style="font-size:13px;" required name="rootcause">{{$data->rootcause}}</textarea>
                                </div>
                            </div>

                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" for="correctiveaction" style="font-size:13px;">Corrective action <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <textarea rows="3" class="form-control" id="correctiveaction" style="font-size:13px;" required name="correctiveaction">{{$data->correctiveaction}}</textarea>
                                </div>
                            </div>

                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" for="download" style="font-size:13px;">Attached file</label>
                                <div class="col-sm-12">
                                    <button type="button" class="btn btn-primary btn-small" style="font-size:12px;width: 150px;" data-toggle="modal" data-id="{{ $data->id }}" data-target="#viewModal{{$data->id}}"><strong>Attachments&nbsp(Issues)</strong> </button>
                                    <button type="button" class="btn btn-primary btn-small" style="font-size:12px;width: 157px;" data-toggle="modal" data-id="" data-target="#viewModal"><strong>Attachments&nbsp(actions)</strong> </button>
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="impl_status" style="font-size:13px;">Implementation status</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="impl_status" style="font-size:13px;" name="impl_status" value="{{$data->impl_status}}" readonly="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12 text-right">
                                <a class='btn btn-primary btn-small' href="{{url('/user/hseobservation/reviewed_notok?val='.$rr)}}" style="font-size:13px;">Reject Action</a>
                                <button type="submit" for="submit" class="btn btn-primary" data-toggle="click-ripple" style="font-size:13px;" onclick="return confirm('Are you sure to approve the actions?')">Approve Action</button>
                            </div>
                        </div>
                    </form>
                    <!-- END Form Horizontal - Default Style -->

                </div>
            </div>
        </div>
    </div>
    <!-- END Horizontal -->
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
                        <div class="col-6">
                            <!-- Products -->
                            <a class="block block-rounded block-link-shadow bg-body" href="/user/hseobservation/issues">
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
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/hseobservation/closedissue">
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
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/hseobservation/openissue">
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
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/hseobservation/analysis">
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
    <!-- </div>-->



</div>
<!-- END Apps Modal -->
<div id="viewModal{{$data->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
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
                            <th>Attachments (issues)</th>
                            <th></th>

                        </tr>
                    </thead>
                    <?php $o=0;?>
                    @if( count($rec1) > 0)
                    @foreach($rec1 as $value1)

                    <tbody>
                        <tr>
                            <td style="font-size:15px;width: ;">{{ $o=$o+1}}</td>
                            <td style="font-size:15px;width: 100%;">{{ $value1->issue_file }}</td>
                            <td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success" href="{{asset("storage/issue_hse/".$value1->issue_file)}}" download="{{$value1->issue_file}}">Download</a></td>
                        </tr>
                    </tbody>
                    @endforeach
                    <div class="row justify-content-center">
                        {{$rec1->links()}}
                    </div>
                    @else
                    <div class="row justify-content-center">
                        <h2>No Files</h2>
                    </div>
                    @endif

            </table>

            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div id="viewModal" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
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
                            <th>Attachments (actions)</th>
                            <th></th>

                        </tr>
                    </thead>
                    <?php $no=0;?>

                    @if( count($rec2) > 0)
                    @foreach($rec2 as $value2)
                    <tbody>
                        <tr>
                            <td style="font-size:15px;width: ;">{{ $no=$no+1}}</td>
                            <td style="font-size:15px;width: 100%;">{{ $value2->action_file }}</td>
                            <td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success" href="{{asset("storage/issue_hse/".$value2->action_file)}}" download="{{$value2->action_file}}">Download</a></td>
                        </tr>
                    </tbody>

                    @endforeach
                    <div class="row justify-content-center">
                        {{$rec2->links()}}
                    </div>
                    @else
                    <div class="row justify-content-center">
                        <h2>No Files</h2>
                    </div>
                    @endif
            </table>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection
