@extends('layouts.backend')
@section('content')
<!-- Hero -->
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">

            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">HSE Observations</li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="">Closure Review</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="/user/hseobservation/issues">Back</a>
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
    <div class="block block-rounded" style="box-shadow: 0px 0px 15px;background-color: #ffffff;">
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-10">
                    <!-- Form Horizontal - Default Style -->
                    <form class="mb-5" enctype="multipart/form-data" files="true">
                        {{csrf_field()}}
                        <input type="hidden" name="posted" value="{{Auth::user()->email}}">
                        <input type="hidden" name="postedby" value="{{Auth::user()->employeeid}}">
                        <?php $rr=$data->id ?>
                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="raised" style="font-size:13px;">Raised By</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="name" name="name" value="{{Auth::user()->name}}" readonly="" style="font-size:13px;">
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="category" style="font-size:13px;">Category</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="category" name="category" value="{{$data->categoryName}}" readonly="" style="font-size:13px;">
                            </div>
                        </div>
                        <div class="form-group" style="display:inline-block;width:49%">
                            <label class="col-sm-12 col-form-label" for="department" style="font-size:13px;">Department</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="department" style="font-size:13px;" name="department" value="{{$data->departmentHse?$data->departmentHse->departmentname:$data->department}}" readonly="">

                            </div>
                        </div>
                        @if($data->division)
                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" style="font-size:13px;" for="division">Division</label>
                            <div class="col-sm-12">
                                <div class="form-group" style="color:#666666">
                                    <input type="text" class="form-control" style="font-size: 13px;" value="{{$data->division->name}}" readonly>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($data->subdiv)
                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" style="font-size:13px;" for="department">Subdivision</label>
                            <div class="col-sm-12">
                                <div class="form-group" style="color:#666666">
                                    <input type="text" class="form-control" style="font-size: 13px;" value="{{$data->subdiv->name}}" readonly>
                                </div>
                            </div>
                        </div>
                        @endif



                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="type" style="font-size:13px;">Type <span class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <select class="form-control" name="type" required="true" style="font-size:13px;">
                                    @if(count($type)>0)
                                    @foreach($type as $typ)
                                    <option value="{{$typ->id}}" {{$data->type==$typ->id ?'selected':''}} style="font-size:13px;"> {{$typ->hsetype}}</option></br>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="title" style="font-size:13px;">Title <span class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="title" name="title" value="{{$data->title}}" style="font-size:13px;">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-12 col-form-label" for="download" style="font-size:13px;">Attached File <span class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-primary btn-small" style="font-size:12px;width: 150px;" data-toggle="modal" data-id="{{ $data->id }}" data-target="#viewModal{{$data->id}}"><strong>Attachments&nbsp(Issues)</strong> </button>
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
                                                            <th>Attachments (Issues)</th>
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
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 col-form-label" for="download" style="font-size: 13px;"></label>
                            <div class="col-sm-8">

                                <button type="button" class="btn btn-primary btn-small" style="font-size:12px;width: 160px;" data-toggle="modal" data-id="" data-target="#viewModal"><strong>Attachments&nbsp(Actions)</strong> </button>
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
                                                            <th>Attachments (Actions)</th>
                                                            <th></th>

                                                        </tr>
                                                    </thead>
                                                    <?php $n=0;?>

                                                    @if( count($rec2) > 0)
                                                    @foreach($rec2 as $value2)
                                                    <tbody>
                                                        <tr>
                                                            <td style="font-size:15px;width: ;">{{ $n=$n+1}}</td>
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
                            </div>
                        </div>


                        <div class="form-group" style="display:inline-block;width:98.5%;">
                            <label class="col-sm-12 col-form-label" for="observation" style="font-size:13px;">Observation</label>
                            <div class="col-sm-12">
                                <textarea type="text" class="form-control" id="observation" name="observation" placeholder="Enter observation" style="font-size:13px;">{{$data->observation}}</textarea>
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:98.5%;">
                            <label class="col-sm-12 col-form-label" for="agreedaction" style="font-size:13px;">Recommended Action</label>
                            <div class="col-sm-12">
                                <textarea type="text" class="form-control" id="agreedaction" name="agreedaction" style="font-size:13px;">{{$data->agreedaction}}</textarea>
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:98.5%;">
                            <label class="col-sm-12 col-form-label" for="correction" style="font-size:13px;">Correction</label>
                            <div class="col-sm-12">
                                <textarea rows="3" class="form-control" id="correction" name="correction" style="font-size:13px;">{{$data->correction}}</textarea>
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:98.5%;">
                            <label class="col-sm-12 col-form-label" for="rootcause" style="font-size:13px;">Root cause</label>
                            <div class="col-sm-12">
                                <textarea rows="3" class="form-control" id="rootcause" name="rootcause" style="font-size:13px;">{{$data->rootcause}}</textarea>
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:98.5%;">
                            <label class="col-sm-12 col-form-label" for="correctiveaction" style="font-size:13px;">Corrective action</label>
                            <div class="col-sm-12">
                                <textarea rows="3" class="form-control" id="correctiveaction" name="correctiveaction" style="font-size:13px;">{{$data->correctiveaction}}</textarea>
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="date" style="font-size:13px;">Due date</label>
                            <div class="col-sm-12">
                                <input type="date" class="js-datepicker form-control" id="date" name="duedate" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy" value="{{$data->duedate}}" style="font-size:13px;">
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="impl_status" style="font-size:13px;">Implementation status</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="impl_status" name="impl_status" value="{{$data->impl_status}}" readonly="" style="font-size:13px;">
                            </div>
                        </div>
                        <br>
                        <div style=";" class="form-group">
                            <a class='btn btn-primary btn-small' onclick="return confirm('Are You Sure to Submit Issue for Need More Action?')" href="{{url('/user/hseobservation/changes?val='.$rr)}}" style="font-size:13px; margin-left:68%;">Need More Action</a>
                            <a class='btn btn-primary btn-small' onclick="return confirm('Are You Sure to Close the Issue?')" href="{{url('/user/hseobservation/closed?val='.$rr)}}" style="font-size:13px;">Close Issue</a>
                        </div>
                    </form>
                    <!-- END Form Horizontal - Default Style -->

                </div>
            </div>
        </div>
    </div>
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
<script type="text/javascript">
    $(document).ready(function() {
        $('#sel').select2({});
        $('#sel_freq').select2({});
    });

</script>
@endsection

