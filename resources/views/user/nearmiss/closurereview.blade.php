@extends('layouts.backend')
@section('content')
<!doctype html>
<html lang="en">
<body>
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">

                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Near Miss</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Closure Review</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="{{ route('nearmiss.index')}}">Back</a>
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
                            @if(count($data)>0)
                            @foreach($data as $dat)
                            <?php $rr=$dat->id ?>
                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="raised" style="font-size:13px;">Raised By</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="name" name="name" value="{{Auth::user()->name}}" readonly="" style="font-size:13px;">
                                </div>
                            </div>



                            {{-- <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="department" style="font-size:13px;">Department<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select class="form-control" name="department" required="true" style="font-size:13px;">
                                        <option value=" {{$dat->department}}" style="font-size:13px;"> {{$dat->department}}</option></br>
                            @if(count($departments)>0)
                            @foreach($departments as $dep)
                            @if($dat->department != $dep->departmentname)
                            <option value=" {{$dep->departmentname}}" style="font-size:13px;"> {{$dep->departmentname}}</option></br>
                            @endif
                            @endforeach
                            @endif
                            </select>
                    </div>
                </div> --}}

                <div class="form-group" style="display:inline-block;width:49%;">
                    <label class="col-sm-12 col-form-label" for="department" style="font-size:13px;">Department</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="department" style="font-size:13px;" name="department" value="{{$dat->department}}" readonly="">
                    </div>
                </div>
                @if($dat->division)
                <div class="form-group" style="display:inline-block;width:49%;">
                    <label class="col-sm-12 col-form-label" style="font-size:13px;" for="division">Division</label>
                    <div class="col-sm-12">
                        <div class="form-group" style="color:#666666">
                            <input type="text" class="form-control" style="font-size: 13px;" value="{{$dat->division->name}}" readonly>
                        </div>
                    </div>
                </div>
                @endif
                @if($dat->subdiv)
                <div class="form-group" style="display:inline-block;width:49%;">
                    <label class="col-sm-12 col-form-label" style="font-size:13px;" for="department">Subdivision</label>
                    <div class="col-sm-12">
                        <div class="form-group" style="color:#666666">
                            <input type="text" class="form-control" style="font-size: 13px;" value="{{$dat->subdiv->name}}" readonly>
                        </div>
                    </div>
                </div>
                @endif

                <div class="form-group" style="display:inline-block;width:49%;">
                    <label class="col-sm-12 col-form-label" for="type" style="font-size:13px;">Type<span class="text-danger">*</span></label>
                    <div class="col-sm-12">
                        <select class="form-control" name="type" required="true" style="font-size:13px;">
                            <option value=" {{$dat->type }}" style="font-size:13px;"> {{$dat->type }}</option></br>
                            @if(count($type)>0)
                            @foreach($type as $typ)
                            @if($dat->type != $typ->name)
                            <option value=" {{$typ->name}}" style="font-size:13px;"> {{$typ->name}}</option></br>
                            @endif
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="form-group" style="display:inline-block;width:49%;">
                    <label class="col-sm-12 col-form-label" for="title" style="font-size:13px;">Title</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="title" name="title" value="{{$dat->title}}" style="font-size:13px;">
                    </div>
                </div>

                <div class="form-group" style="display:inline-block;width:98.5%;">
                    <label class="col-sm-12 col-form-label" for="observation" style="font-size:13px;">Observation</label>
                    <div class="col-sm-12">
                        <textarea type="text" class="form-control" id="observation" name="observation" placeholder="Enter observation" style="font-size:13px;">{{$dat->observation}}</textarea>
                    </div>
                </div>

                <div class="form-group" style="display:inline-block;width:98.5%;">
                    <label class="col-sm-12 col-form-label" for="agreedaction" style="font-size:13px;">Recommended Action</label>
                    <div class="col-sm-12">
                        <textarea type="text" class="form-control" id="agreedaction" name="agreedaction" style="font-size:13px;">{{$dat->agreedaction}}</textarea>
                    </div>
                </div>
                @php
                $issuedocs=explode(',',$dat->file);
                $actiondocs=explode(',',$dat->action_file);
                @endphp
                <div class="form-group">
                    <label class="col-sm-4 col-form-label" for="download" style="font-size:13px;">Attached file</label>
                    <div class="col-sm-8">
                        <button type="button" class="btn btn-primary btn-small" style="font-size:12px;width: 155px;" data-toggle="modal" data-id="" data-target="#viewModal1"><strong>Attachments&nbsp(Issues)</strong> </button>
                        <div id="viewModal1" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
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
                                                    <th>Attachments</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <?php $n=0; ?>

                                            @if( count($issuedocs) > 0 )
                                            @foreach($issuedocs as $value1)

                                            @if( $value1!="NULL" )
                                            <tbody>
                                                <tr>
                                                    <td style="font-size:10px; ;">{{ $n=$n+1}}</td>
                                                    <td style="font-size:15px;width: 100%;">{{ $value1}}</td>


                                                    @if(File::exists(public_path('/storage/nearmiss/'.$value1)))
                                                    <td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success" href="{{asset("storage/nearmiss/".$value1)}}" download="{{$value1}}">Download</a></td>

                                                    @else
                                                    <td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success" href="{{asset("storage/issue_files/".$value1)}}" download="{{$value1}}">Download</a></td>

                                                    @endif

                                                    {{-- <td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success"  href="{{asset("storage/nearmiss/".$value1)}}" href="{{asset("storage/issue_files/".$value1)}}" download="{{$value1}}" >Download</a></td> --}}
                                                </tr>
                                            </tbody>
                                            @else
                                            <div class="row justify-content-center">
                                                <h2>No Files</h2>
                                            </div>
                                            @endif
                                            @endforeach
                                            @else
                                            <div class="row justify-content-center">
                                                <h2>No Files</h2>
                                            </div>
                                            @endif
                                        </div>
                                    </table>


                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row" style="margin-left:-423px;">
                    <label class="col-sm-4 col-form-label" for="download" style="font-size: 13px;"></label>
                    <div class="col-sm-8">

                        <button type="button" class="btn btn-primary btn-small" style="font-size:12px;width: 155px;" data-toggle="modal" data-id="" data-target="#viewModal"><strong>Attachments&nbsp(Actions)</strong> </button>
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
                                            <?php $o=0; ?>

                                            @if( count($actiondocs) > 0 )
                                            @foreach($actiondocs as $value2)

                                            @if( $value2!="NULL" && $value2!="")
                                            <tbody>
                                                <tr>
                                                    <td style="font-size:10px; ;">{{ $o=$o+1}}</td>
                                                    <td style="font-size:15px;width: 100%;">{{ $value2}}</td>

                                                    @if(File::exists(public_path('/storage/nearmiss/'.$value2)))
                                                    <td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success" href="{{asset("storage/nearmiss/".$value2)}}" download="{{$value2}}">Download</a></td>

                                                    @else
                                                    <td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success" href="{{asset("storage/issue_files/".$value2)}}" download="{{$value2}}">Download</a></td>

                                                    @endif
                                                </tr>
                                            </tbody>
                                            @else
                                            <div class="row justify-content-center">
                                                <h2>No Files</h2>
                                            </div>
                                            @endif
                                            @endforeach
                                            @else
                                            <div class="row justify-content-center">
                                                <h2>No Files</h2>
                                            </div>
                                            @endif
                                        </div>
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
                    <label class="col-sm-12 col-form-label" for="correction" style="font-size:13px;">Correction</label>
                    <div class="col-sm-12">
                        <textarea rows="3" class="form-control" id="correction" name="correction" style="font-size:13px;">{{$dat->correction}}</textarea>
                    </div>
                </div>

                <div class="form-group" style="display:inline-block;width:98.5%;">
                    <label class="col-sm-12 col-form-label" for="rootcause" style="font-size:13px;">Root cause</label>
                    <div class="col-sm-12">
                        <textarea rows="3" class="form-control" id="rootcause" name="rootcause" style="font-size:13px;">{{$dat->rootcause}}</textarea>
                    </div>
                </div>

                <div class="form-group" style="display:inline-block;width:98.5%;">
                    <label class="col-sm-12 col-form-label" for="correctiveaction" style="font-size:13px;">Corrective action</label>
                    <div class="col-sm-12">
                        <textarea rows="3" class="form-control" id="correctiveaction" name="correctiveaction" style="font-size:13px;">{{$dat->correctiveaction}}</textarea>
                    </div>
                </div>

                <div class="form-group" style="display:inline-block;width:49%;">
                    <label class="col-sm-12 col-form-label" for="date" style="font-size:13px;">Target date</label>
                    <div class="col-sm-12">
                        <input type="date" class="js-datepicker form-control" id="date" name="targetdate" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy" value="{{$dat->targetdate}}" style="font-size:13px;">
                    </div>
                </div>

                <div class="form-group" style="display:inline-block;width:49%;">
                    <label class="col-sm-12 col-form-label" for="impl_status" style="font-size:13px;">Implementation status</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="impl_status" name="impl_status" value="{{$dat->impl_status}}" readonly="" style="font-size:13px;">
                    </div>
                </div>
                <br>
                <div style=";" class="form-group">
                    <a class='btn btn-primary btn-small' onclick="return confirm('Are You Sure to Submit Issue for Need More Action?')" href="{{ route('nearmiss.changes', ["id" => $rr])}}" style="font-size:13px; margin-left:68%;">Need More Action</a>
                    <a class='btn btn-primary btn-small' onclick="return confirm('Are You Sure to Close the Issue?')" href="{{ route('nearmiss.closed', ["id" => $rr])}}" style="font-size:13px;">Close Issue</a>
                </div>
                @endforeach
                @endif
                </form>
                <!-- END Form Horizontal - Default Style -->

            </div>
        </div>
    </div>
    </div>
    <!-- END Horizontal -->
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
                                <a class="block block-rounded block-link-shadow bg-body" href="{{ route('nearmiss.index')}}">
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
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="{{ route('nearmiss.closedissue')}}">
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
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="{{ route('nearmiss.openissue')}}">
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
                                <!-- Sales -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="{{ route('nearmiss.analysis')}}">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="fas fa-clone fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Analysis
                                        </p>
                                    </div>
                                </a>
                                <!-- END Sales -->
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- </div>-->
    </div>
    <!-- END Apps Modal -->
</body>

<script type="text/javascript">
    $(document).ready(function() {
        $('#sel').select2({});
    });
    $(document).ready(function() {
        $('#sel_freq').select2({});
    });

</script>


</html>
@endsection
