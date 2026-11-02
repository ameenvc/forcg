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
                                <a class="link-fx" href="">Edit</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">
                                <a class="link-fx" href="/user/auditmgmt">Back</a>
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
                        <div class="col-lg-10">
                            <!-- Form Horizontal - Default Style -->
                            @if(count($errors)>0)
                                @foreach($errors->all() as $error)
                                    <div class="alert alert-warning">
                                                {{$error}}
                                    </div>
                                @endforeach
                            @endif
                            <form class="mb-5" action="{{route('user.actionsystem.hodreviewsubmit', $issue->id)}}"
                                  enctype="multipart/form-data"  method="post" files="true">
                            {{csrf_field()}}
                                <input type="hidden" name="posted" value="{{$issue->id}}">
                                <div class="form-group" style="display:inline-block;width:49%;">
                                    <label class="col-sm-12 col-form-label" for="raised" style="font-size:13px;">Issue id</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="name" name="name" style="font-size:13px;" value="{{$issue->issueid}}" readonly="">
                                    </div>
                                </div>
                                <div class="form-group" style="display:inline-block;width:49%;">
                                    <label class="col-sm-12 col-form-label" for="raised" style="font-size:13px;">Raised by</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="name" name="name" style="font-size:13px;" value="{{$issue->postedby}}" readonly="" >
                                    </div>
                                </div>


                                <div class="form-group" style="display:inline-block;width:49%;">
                                    <label class="col-sm-12 col-form-label" for="title" style="font-size:13px;">Title</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="title" style="font-size:13px;" name="title" value="{{$issue->title}}"  readonly="" >
                                    </div>
                                </div>

                                <!--<div class="form-group" style="display:inline-block;width:49%;">
                                                <label  class="col-sm-12 col-form-label" for="category" style="font-size:13px;">Category</label>
                                                    <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="category" style="font-size:13px;" name="category" value="{{$issue->category}}" readonly="" >
                                                </div>
                                            </div>-->

                                <div class="form-group" style="display:inline-block;width:49%;">
                                    <label  class="col-sm-12 col-form-label" for="department" style="font-size:13px;">Department</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="department" style="font-size:13px;"
                                               name="department" value="{{ $department ? $department->departmentname : '' }}" readonly="">
                                    </div>
                                </div>

                                <div class="form-group" style="display:inline-block;width:98.5%;">
                                    <label class="col-sm-12 col-form-label" for="type" style="font-size:13px;">Type</label>
                                    @php
                                        $type_name = App\Type::where('order', $issue->type)->first();
                                        $type_name = $type_name ? $type_name->name : '';
                                    @endphp
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="type" name="type" value="{{$type_name}}"  readonly=""  style="font-size:13px;" >
                                        {{-- <select class="form-control" name = "type" required="true" style="font-size:13px;" readonly="">
                                           <option value=" {{$issue->type }}" style="font-size:13px;"> {{$issue->type }}</option></br>
                                           @if(count($type)>0)
                                               @foreach($type as $typ)
                                                   @if($issue->type != $typ->name)
                                                       <option value=" {{$typ->name}}" style="font-size:13px;"> {{$typ->name}}</option></br>
                                                   @endif
                                               @endforeach
                                           @endif
                                       </select> --}}
                                    </div>
                                </div>


                                <div class="form-group" style="display:inline-block;width:98.5%;">
                                    <label class="col-sm-12 col-form-label" for="observation" style="font-size:13px;">Observation</label>
                                    <div class="col-sm-12">
                                        <textarea type="text" class="form-control" id="observation" style="font-size:13px;" name="observation" placeholder="Enter observation"  readonly="">{{$issue->observation}}</textarea>
                                    </div>
                                </div>

                                <div class="form-group" style="display:inline-block;width:98.5%;">
                                    <label class="col-sm-12 col-form-label" for="agreedaction" style="font-size:13px;">Recommended action</label>
                                    <div class="col-sm-12">
                                        <textarea type="text" class="form-control" id="agreedaction" style="font-size:13px;" name="agreedaction"  readonly="" >{{$issue->agreedaction}}</textarea>
                                    </div>
                                </div>
                                <div class="form-group" style="display:inline-block;width:49%;">
                                    <label class="col-sm-12 col-form-label" for="responsibility" style="font-size:13px;">Responsibility</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="responsibility" style="font-size:13px;" name="responsibility"  readonly="" value=" {{$resp}}">
                                    </div>
                                </div>
                                {{-- <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="agreedaction" style="font-size:13px;">Responsibility</label>
                                    <div class="col-sm-8">
                                    <select class="form-control" name = "responsibility" required="true" style="font-size:13px;">

                                    <option    value=" {{$resp}}"> {{ $resp }}</option><br>
                                    @if(count($user)> 0 )
                                        @foreach($user as $use)
                                            <option    value=" {{$use->email}}"> {{ $use->name }}</option><br>
                                        @endforeach
                                    @endif
                                </select>
                                    </div>
                                </div> --}}

                                <div class="form-group" style="display:inline-block;width:49%;">
                                    <label class="col-sm-12 col-form-label" for="date" style="font-size:13px;">Due date</label>
                                    <div class="col-sm-12">
                                        <input type="date" class="js-datepicker form-control" style="font-size:13px;" id="date" name="duedate" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy" value="{{$issue->duedate}}" readonly="">
                                    </div>
                                </div>


                                <div class="form-group" style="display:inline-block;width:98.5%;">
                                    <label class="col-sm-12 col-form-label" for="correction" style="font-size:13px;">Correction</label>
                                    <div class="col-sm-12">
                                        <textarea  rows="3" class="form-control" id="correction" style="font-size:13px;" name="correction"  >{{$issue->correction}}</textarea>
                                    </div>
                                </div>

                                <div class="form-group" style="display:inline-block;width:98.5%;">
                                    <label class="col-sm-12 col-form-label" for="rootcause" style="font-size:13px;">Root cause</label>
                                    <div class="col-sm-12">
                                        <textarea  rows="3" class="form-control" id="rootcause" style="font-size:13px;" name="rootcause"  >{{$issue->rootcause}}</textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-12 col-form-label" for="correctiveaction" style="font-size:13px;">Corrective action</label>
                                    <div class="col-sm-12">
                                        <textarea  rows="3" class="form-control" id="correctiveaction" style="font-size:13px;" name="correctiveaction"  >{{$issue->correctiveaction}}</textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 col-form-label" for="download"  style="font-size:13px;">Attached file</label>
                                    <div class="col-sm-8">
                                        <button type="button" class="btn btn-primary btn-small" style="font-size:12px;width: 150px;" data-toggle="modal" data-id="{{ $issue->id }}" data-target="#viewModal{{$issue->id}}"><strong>Attachments&nbsp(Issues)</strong> </button>
                                        <button type="button" class="btn btn-primary btn-small" style="font-size:12px;width: 155px;" data-toggle="modal" data-id="" data-target="#viewModalAction"><strong>Attachments&nbsp(Actions)</strong> </button>
                                        <div id="viewModal{{$issue->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h3 class="modal-title">Attachments</h3>
                                                    </div>
                                                    @if(count($rec1) > 0)
                                                    <table class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                                                        <div class="row justify-content-center">
                                                            <thead>
                                                            <tr class="table-secondary">
                                                                <th>S.No</th>
                                                                <th>Attachments (issues)</th>
                                                                <th></th>
                                                            </tr>
                                                            </thead>
                                                            <?php $no=0;?>                                                          
                                                            @foreach($rec1 as $value1)
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="font-size:15px;">{{ $no=$no+1}}</td>
                                                                        <td style="font-size:15px;width: 100%;">{{ $value1->issue_file }}</td>
                                                                        <td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success"  href="{{asset("storage/issue_files/".$value1->issue_file)}}" download="{{$value1->issue_file}}" >Download</a></td>
                                                                    </tr>
                                                                </tbody>
                                                            @endforeach
                                                    </table>
                                                    @else
                                                        <div class="row justify-content-center">
                                                            <h4 class="text-danger">No Files</h4>
                                                        </div>
                                                    @endif
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="viewModalAction" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h3 class="modal-title">Attachments</h3>
                                                    </div>
                                                    @if( count($rec2) > 0)
                                                    <table class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                                                        <div class="row justify-content-center">
                                                            <thead>
                                                            <tr class="table-secondary">
                                                                <th>S.No</th>
                                                                <th>Attachments (actions)</th>
                                                                <th></th>

                                                            </tr>
                                                            </thead>
                                                            <?php $n=0;?>                                                        
                                                                @foreach($rec2 as $value2)
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="font-size:15px">{{ $n=$n+1}}</td>
                                                                        <td style="font-size:15px;width: 100%;">{{ $value2->action_file }}</td>
                                                                        <td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success"  href="{{asset("storage/issue_files/".$value2->action_file)}}" download="{{$value2->action_file}}" >Download</a></td>
                                                                    </tr>
                                                                </tbody>
                                                                @endforeach                                                                     
                                                    </table>
                                                    @else
                                                        <div class="row justify-content-center">
                                                            <h4 class="text-danger">No Files</h4>
                                                        </div>
                                                    @endif
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group d-flex justify-content-end">
                                    <button type="submit"
                                            name="action"
                                            value="approve"
                                            class="btn btn-success mx-2"
                                            style="font-size:13px;">Approve</button>
                                    <button type="submit"
                                            name="action"
                                            value="reject"
                                            class="btn btn-danger mx-2"
                                            style="font-size:13px;">Reject</button>
                                </div>
                            </form>
                            <!-- END Form Horizontal - Default Style -->

                        </div>
                    </div>
                </div>
            </div>
            <!-- END Horizontal -->
        </div>
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
                <!-- <div class="col-6">

                                <a class="block block-rounded block-link-shadow bg-body" href="/user/actionsystem/addissue">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="fas fa-plus-circle fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Add Issue
                                        </p>
                                    </div>
                                </a>

                            </div> -->
                            <div class="col-6">
                                <!-- Products -->
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/auditmgmt">
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
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/actionsystem/folders">
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
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/actionsystem/openissue">
                                    <div class="block-content text-center" style="height:130px">
                                    <i class="fas fa-clone fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            My Open NCRs
                                        </p>
                                    </div>
                                </a>
                                <!-- END Sales -->
                            </div>
                            <div class="col-6">
                                <!-- Sales -->
                                <a class="block block-rounded block-link-shadow bg-body mb-2" href="/user/actionsystem/analysis">
                                    <div class="block-content text-center" style="height:130px">
                                    <i class="fas fa-clone fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Analysis
                                        </p>
                                    </div>
                                </a>
                                <!-- END Sales -->
                            </div>


                            <div class="col-6">
                                <!-- Sales -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href='/user/internalaudit/analysis'>
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                           Audit Schedule Analysis
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
</html>
@endsection
