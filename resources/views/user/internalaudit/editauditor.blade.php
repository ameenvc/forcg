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
                        <li class="breadcrumb-item">Audit Management</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Edit Auditors</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="/user/auditschedule">Back</a>
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
        <div class="block block-rounded" style="box-shadow: 0 0 15px;background-color: #ffffff;">
            <div class="block-content block-content-full">
                <div class="row">
                    <div class="col-lg-10">
                        <form class="mb-5" action="{{url('/user/internalaudit/editauditor')}}" enctype="multipart/form-data"  method="post" >
                            {{ csrf_field() }}
				            @if(count($errors) >0 )
					            @foreach($errors->all() as $error)
						            <div class='alert alert-warning'>
							            {{ $error }}
						            </div>
					            @endforeach
                            @endif

				            @if(count($data)>0)
                                @foreach($data as $dat)

                        <div class="form-group" style="display:inline-block;width:33%;">
                            <label class="col-sm-12 col-form-label" for="auditorsemail"style="font-size: 13px;">Name of Auditor<span class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <div class="form-group"style="color:#666666">
                                    <select class="js-select2 form-control"style="font-size: 13px;" id="auditorsemail" name="auditorsemail" style="width: 100%;" data-placeholder="Choose one..">
                                        @if(count($users) > 0 )
                                            @if(!$users->where('email', $dat->email)->count())
                                                <option selected="" disabled>Choose Auditor</option>
                                            @endif
                                            @foreach($users as $value)
                                                <option value="{{$value->email}}"
                                                    {{ old('auditorsemail', $dat->email) == $value->email ? 'selected' : '' }}>
                                                    {{ $value->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" style="display:inline-block;width:33%;">
                            <label class="col-sm-12 col-form-label" for="experience" style="font-size: 13px;">
                                Total Audit Experience
                            </label>
                            <div class="col-sm-12">
                                <input type="text" style="font-size: 13px;"
                                       value="{{ old('experience', $dat->experience) }}"
                                       class="form-control" id="experience"
                                       name="experience">
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:33%;">
                            <label class="col-sm-12 col-form-label" for="overall_experience" style="font-size: 13px;">
                                Overall Experience
                            </label>
                            <div class="col-sm-12">
                                <input type="text" style="font-size: 13px;"
                                       value="{{ old('overall_experience', $dat->overall_experience) }}"
                                       class="form-control" id="overall_experience"
                                       name="overall_experience">
                            </div>
                        </div>
                        <div class="form-group" style="display:inline-block;width:99%;">
                            <label class="col-sm-12 col-form-label" for="certifications_licensing" style="font-size: 13px;">
                                Certifications and Licensing(List all audit Certificates)<span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-12">
                                <textarea style="font-size: 13px;"
                                          class="form-control" id="certifications_licensing"
                                          name="certifications_licensing">{{ old('certifications_licensing', $dat->certifications_licensing) }}</textarea>
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:99%;">
                            <label class="col-sm-12 col-form-label" for="specialization_area" style="font-size: 13px;">
                                Area of Specialization
                            </label>
                            <div class="col-sm-12">
                                <textarea style="font-size: 13px;"
                                      class="form-control" id="specialization_area"
                                      name="specialization_area">{{ old('specialization_area', $dat->specialization_area) }}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-12 col-form-label" for="download" style="font-size: 13px;">Attached Certificates</label>
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-secondary btn-small" style="font-size:13px;width: 150px;" data-toggle="modal" data-id="{{ $dat->id }}" data-target="#viewModal{{$dat->id}}"><strong>Attachments</strong> </button>
                                <div id="viewModal{{$dat->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
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
                                                            <th>Download</th>
                                                            <th>Delete</th>
                                                        </tr>
                                                    </thead>
                                                    <?php $no=0;?>
                                                    @if( count($mul) > 0)
                                                        @foreach($mul as $value1)

                                                        @if($value1->id==$dat->id)
                                                            <?php $id = property_exists($value1, 'Sno') ? $value1->Sno : $value1->sno ?>
                                                            <tbody>
                                                                <tr>
                                                                    <td style="font-size:13px;width: ;">{{ $no=$no+1}}</td>
                                                                    <td style="font-size:13px;width: 100%;">{{ $value1->certificate }}</td>
                                                                    <td><a style="margin-left:70px;font-size:12px;width: 80px;" class="btn btn-success"  href="{{asset("storage/images/".$value1->certificate)}}" download="{{$value1->certificate}}" >Download</a></td>
                                                                    <td style="font-size:12px;"><a href="{{url('/user/internalaudit/deletefile?val='.$id)}}" onclick="return confirm('The attachment will be deleted permanently,Press OK to continue')" class="btn btn-primary" style="font-size:13px;">Delete</a></td>
                                                                </tr>
                                                            </tbody>
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
                                                    <button type="button" class="btn btn-dark"style="font-size:13px;" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" style="display:inline-block;width:71%;margin-top:-25px;">
                            <label class="col-sm-12 col-form-label" for="file" style="font-size: 13px;">Attach certificates(avoid '#' and '&' in file names) </label>
                            <div class="col-sm-12">
                                <input type="file"style="font-size: 13px;" class="form-control" name="file[]" multiple>
                            </div>
                        </div>
					@endforeach
				 @endif
                <input type="hidden" name="id" value="{{ $dat->id}}">
                <div>
                    <button type="submit" for="submit" class="btn btn-primary"style="font-size:13px; margin-left:60.5%;" data-toggle="click-ripple" onclick="return confirm('Are you sure to submit the request?')">SUBMIT</button>
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

                                    <div class="col-6">
                                        <!-- Products -->
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/auditschedule">
                                            <div class="block-content text-center">
                                                <i class="far fa-list-alt fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Audit Schedule
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Products -->
                                    </div>
                                    <div class="col-6">
                                        <!-- Sales -->
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/internalaudit/auditorslist">
                                            <div class="block-content text-center">
                                                <i class="fas fa-address-card fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                   <br> Auditors List
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Sales -->
                                    </div>
                                    <div class="col-6">
                                        <!-- Payments -->
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/internalaudit/auditlog">
                                            <div class="block-content text-center">
                                                <i class="fas fa-calendar-check fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    <br>Audit Log
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Payments -->
                                    </div>
                                    <div class="col-6">
                                        <!-- Sales -->
                                        <a class="block block-rounded block-link-shadow bg-body mb-2" href="/user/internalaudit/closedaudits">
                                            <div class="block-content text-center">
                                                <i class="fas fa-check-circle fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Closed Audits
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Sales -->
                                    </div>
                                    <div class="col-6">
                                        <!-- Sales -->
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href='/user/internalaudit/analysis'>
                                            <div class="block-content text-center">
                                                <i class="fas fa-chart-bar fa-2x text-primary"></i>
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
            </div>
<!-- END Apps Modal -->


</body>
</html>
@endsection
