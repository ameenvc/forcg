@extends('layouts.backend')
@section('content')
    <style>
        .label-invalid {
            color: red;
            font-size: 13px;
        }
        .spinner {
            display: inline-block;
            -webkit-transition: opacity 0.25s, width 0.25s;
            -moz-transition: opacity 0.25s, width 0.25s;
            -o-transition: opacity 0.25s, width 0.25s;
            transition: opacity 0.25s, width 0.25s;
        }
    </style>

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
                                    <form class="mb-5" action="{{route ('user.actionsystem.updateopenissue')}}" enctype="multipart/form-data" id="propose_action_frm"  method="post" files="true">
                                    {{csrf_field()}}
                                        @if(count($data)>0)
                                            @foreach($data as $dat)
                                                <?php $rr=$dat->id;
                                                 $dept = DB::table('departments')->where('id',$dat->department)->value('departmentname');
                                                ?>
                                                <input type="hidden" name="posted" value="{{$dat->id}}">
                                                    <div class="form-group" style="display:inline-block;width:49%;">
                                                        <label class="col-sm-12 col-form-label" for="raised"  style="font-size:13px;">Issue id</label>
                                                        <div class="col-sm-12">
                                                            <input type="text" class="form-control" id="name" name="name" value="{{$dat->issueid}}" readonly=""  style="font-size:13px;">
                                                        </div>
                                                    </div>
                                                    <div class="form-group" style="display:inline-block;width:49%;">
                                                        <label class="col-sm-12 col-form-label" for="raised"  style="font-size:13px;">Raised by</label>
                                                        <div class="col-sm-12">
                                                            <input type="text" class="form-control" id="name" name="name"  value="{{$dat->postedby}}" readonly=""  style="font-size:13px;">
                                                        </div>
                                                    </div>


                                                    <div class="form-group" style="display:inline-block;width:49%;">
                                                        <label class="col-sm-12 col-form-label" for="title"  style="font-size:13px;">Title</label>
                                                        <div class="col-sm-12">
                                                            <input type="text" class="form-control" id="title" name="title" value="{{$dat->title}}"  readonly=""  style="font-size:13px;" >
                                                        </div>
                                                    </div>

                                                    <!--<div class="form-group" style="display:inline-block;width:49%;">
                                                        <label  class="col-sm-12 col-form-label" for="category"  style="font-size:13px;">Category</label>
                                                            <div class="col-sm-12">
                                                            <input type="text" class="form-control" id="category" name="category" value="{{$dat->category}}" readonly=""  style="font-size:13px;">
                                                        </div>
                                                    </div>-->

                                                    <div class="form-group" style="display:inline-block;width:49%;">
                                                        <label  class="col-sm-12 col-form-label" for="department"  style="font-size:13px;">Department</label>
                                                            <div class="col-sm-12">
                                                            <input type="text" class="form-control" id="department" name="department" value="{{$dept}}" readonly=""  style="font-size:13px;">
                                                        </div>
                                                    </div>

                                                    <div class="form-group" style="display:inline-block;width:98.5%;">
                                                        <label class="col-sm-12 col-form-label" for="type"  style="font-size:13px;">Type</label>
                                                        @php
                                                            $type = App\Type::where('order', $dat->type)->first();
                                                            $type_name = $type ? $type->name : '';
                                                        @endphp
                                                            <div class="col-sm-12">
                                                                <input type="text" class="form-control" id="type" name="type" value="{{$type_name}}"  readonly=""  style="font-size:13px;" >
                                                            {{-- <select class="form-control" name = "type" required="true" style="font-size:13px;" readonly="">
                                                                <option value=" {{$dat->type }}"  style="font-size:13px;"> {{$dat->type }}</option></br>
                                                                @if(count($type)>0)
                                                                    @foreach($type as $typ)
                                                                        @if($dat->type != $typ->name)
                                                                            <option value=" {{$typ->name}}" style="font-size:13px;"> {{$typ->name}}</option></br>
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </select> --}}
                                                        </div>
                                                    </div>


                                                    <div class="form-group" style="display:inline-block;width:98.5%;">
                                                        <label class="col-sm-12 col-form-label" for="observation"  style="font-size:13px;">Observation</label>
                                                        <div class="col-sm-12">
                                                            <textarea type="text" class="form-control" id="observation" name="observation" placeholder="Enter observation"  readonly=""  style="font-size:13px;">{{$dat->observation}}</textarea>
                                                        </div>
                                                    </div>

                                                    <div class="form-group" style="display:inline-block;width:98.5%;">
                                                        <label class="col-sm-12 col-form-label" for="agreedaction"  style="font-size:13px;">Recommended action</label>
                                                        <div class="col-sm-12">
                                                            <textarea type="text" class="form-control" id="agreedaction" name="agreedaction"  readonly=""  style="font-size:13px;" >{{$dat->agreedaction}}</textarea>
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
                                                        <label class="col-sm-12 col-form-label" for="date"  style="font-size:13px;">Due date</label>
                                                        <div class="col-sm-12">
                                                            <input type="date" class="js-datepicker form-control" id="date" name="duedate" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy" value="{{$dat->duedate}}" readonly=""  style="font-size:13px;">
                                                        </div>
                                                    </div>


                                                <div class="form-group" style="display:inline-block;width:98.5%;">
                                                    <label class="col-sm-12 col-form-label" for="correction"  style="font-size:13px;">Correction<span class="text-danger">*</span></label>
                                                    <div class="col-sm-12">
                                                        <textarea  rows="3" class="form-control" id="correction" name="correction"  style="font-size:13px;" required>{{$dat->correction}}</textarea>
                                                        <label class="label-invalid error-correction"></label>
                                                    </div>
                                                </div>

                                                <div class="form-group" style="display:inline-block;width:98.5%;">
                                                    <label class="col-sm-12 col-form-label" for="rootcause"  style="font-size:13px;">Root cause</label>
                                                    <div class="col-sm-12" style="padding-bottom:10px;">
                                                      <?php
                                                           $m = 0;
                                                           $items_per_row = 3;
                                                        ?>
                                                        @foreach($rootcorrectives as $rootcorrective)
                                                        <button type="button" class="btn btn-sm btn-outline-primary"  style="margin-right:4px;margin-left:10px;margin-bottom:10px;" onclick="return passvalue('{{$rootcorrective->id}}');">{{$rootcorrective->title}}</button>
                                                        <?php
                                                           $m++;
                                                           if($m % $items_per_row == 0){ ?><br><?php } ?>
                                                        @endforeach

                                                    </div>
                                                    <div class="col-sm-12">
                                                        <textarea  rows="3" class="form-control" id="rootcause" name="rootcause"   style="font-size:13px;">{{$dat->rootcause}}</textarea>
                                                    </div>
                                                </div>

                                                <div class="form-group" style="display:inline-block;width:98.5%;">
                                                    <label class="col-sm-12 col-form-label" for="correctiveaction"  style="font-size:13px;">Corrective action</label>
                                                    <div class="col-sm-12">
                                                        <textarea  rows="3" class="form-control" id="correctiveaction" name="correctiveaction"  style="font-size:13px;" >{{$dat->correctiveaction}}</textarea>
                                                    </div>
                                                </div>

                                                    <div class="form-group">
                                                        <label class="col-sm-12 col-form-label" for="download"  style="font-size:13px;">Attached file</label>
                                                        <div class="col-sm-12">
                                                            <button type="button" class="btn btn-primary btn-small" style="font-size:12px;width: 150px;" data-toggle="modal" data-id="{{ $dat->id }}" data-target="#viewModal{{$dat->id}}"><strong>Attachments&nbsp(Issues)</strong> </button>
                                                            <div id="viewModal{{$dat->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                                            <div class="modal-dialog modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h3 class="modal-title">Attachments</h3>
                                                                    </div>
                                                                    @if(count($rec1) > 0)   
                                                                    <table class="table table-responsive table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">                                
                                                                                                                     
                                                                                    <tr class="table-secondary">
                                                                                        <th>S.No</th>
                                                                                        <th>Attachments (issues)</th>
                                                                                        <th></th>
                                                                                    </tr>                                                                         
                                                                                <?php $no=0;?>                                                                     
                                                                                @foreach($rec1 as $value1)           
                                                                                    <tr>
                                                                                        <td style="font-size:15px;">{{ $no=$no+1}}</td>
                                                                                        <td style="font-size:15px;width: 100%;">{{ $value1->issue_file }}</td>
                                                                                        <td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success"  href="{{asset("storage/issue_files/".$value1->issue_file)}}" download="{{$value1->issue_file}}" >Download</a></td>
                                                                                    </tr>                                                                                    
                                                                                @endforeach
                                                                            
                                                                    </table>
                                                                    @else
                                                                       <div align="center"><h4 class="text-danger">No Files</h4></div>
                                                                    @endif
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                <div class="form-group row" style="margin-left:1px;">
                                                    <label class="col-sm-12 col-form-label" for="download" style="font-size: 13px;"></label>
                                                        <div class="col-sm-12">
                                                            <button type="button" class="btn btn-primary btn-small" style="font-size:12px;width: 155px;" data-toggle="modal" data-id="" data-target="#viewModal"><strong>Attachments&nbsp(Actions)</strong> </button>
                                                            <div id="viewModal" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                                            <div class="modal-dialog modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h3 class="modal-title">Attachments</h3>
                                                                    </div>
                                                                    @if(count($rec2) > 0)
                                                                        <table class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">                                                      
                                                                                <tr class="table-secondary">
                                                                                    <th>S.No</th>
                                                                                    <th>Attachments (Actions)</th>
                                                                                    <th></th>
                                                                                </tr>                                           
                                                                            <?php $n=0;?>                                                                        
                                                                            @foreach($rec2 as $value2)                                                                           
                                                                                <tr>
                                                                                    <td style="font-size:15px;">{{ $n=$n+1}}</td>
                                                                                    <td style="font-size:15px;width: 100%;">{{ $value2->action_file }}</td>
                                                                                    <td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success"  href="{{asset("storage/issue_files/".$value2->action_file)}}" download="{{$value2->action_file}}" >Download</a></td>
                                                                                </tr>
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

                                                <div class="form-group" style="display:inline-block;width:98.5%;">
                                                    <label class="col-sm-12 col-form-label" for="action_file" style="font-size:13px;">Attach Action Files(avoid '#' and '&' in file names)</label>
                                                    <div class="col-sm-12">
                                                        <input type="file" class="form-control" id="action_file" style="font-size:13px;" name="action_file[]" multiple>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                  <label class="col-sm-12 col-form-label" for="agreedaction"  style="font-size:13px;">Do you think these actions are fully implemented? <span class="text-danger">*</span></label>
                                                    <div class="col-sm-12">
                                                        <input style="margin-right:4px;" type="radio" value="yes" id="yes"  name="confirm_propose" required><label style="font-size: 13px;">Yes</label>
                                                        <input style="margin-right:4px;margin-left:10px;" type="radio" value="no" id="no"  name="confirm_propose" ><label style="font-size: 13px;">No</label>
                                                    </div>
                                                    <label class="label-invalid error-confirm_propose" style="font-size: 13px; margin-left:10px;"></label>
                                                </div>
                                                <div class="form-group">
                                                    <input type="hidden" name="is_need_verify_input" id="is_need_verify_input">
                                                    <input type="hidden" name="hod_user_input" id="hod_user_input">
                                                </div>

                                                <div class="form-group">
                                                    @if(in_array($dat->type, $typeHasManageWorkflow))
                                                    <button type="button"
                                                            data-id="{{ $dat->id }}"
                                                            class="btn btn-primary js-update-btn" style="font-size: 13px;margin-left:88%;" >Update</button>
                                                    @else
                                                        <button type="submit" for="submit"
                                                                class="btn btn-primary"
                                                                data-toggle="click-ripple"
                                                                onclick="return confirm('Are you sure to propose the actions?')"
                                                                style="font-size: 13px;margin-left:88%;" >Update</button>
                                                    @endif
                                                </div>
                                                <div id="need_auditee_verify_modal_{{ $dat->id }}" class="modal fadebd-example-modal-lg"
                                                     tabindex="-1" role="dialog" aria-hidden="false">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header" style="border-bottom: 1px solid #ddd">
                                                                <h5 class="modal-title">Confirm dialog</h5>
                                                                <button type="button" class="close"
                                                                        style="right: 10px; color: gray"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body" style="border-bottom: 1px solid #ddd">
                                                                <div class="form-group">
                                                                    <label class="form-label d-block">Do you need to verify the proposed actions by the auditee manager? <span class="text-danger">*</span></label>
                                                                    <label class="radio-inline">
                                                                        <input type="radio" class="mx-1 js_need_verify"
                                                                               style="vertical-align: middle" name="is_need_verify" value="1">
                                                                        <span style="vertical-align: middle">Yes</span>
                                                                    </label>
                                                                    <label class="radio-inline mx-3">
                                                                        <input type="radio" class="mx-1 js_need_verify"
                                                                               style="vertical-align: middle" name="is_need_verify" value="2">
                                                                        <span style="vertical-align: middle">No</span>
                                                                    </label>
                                                                    <div class="label-invalid error-is_need_verify"></div>
                                                                </div>
                                                                <div class="form-group d-none hod-user-block">
                                                                    <label class="form-label" for="hod_user">HOD user <span class="text-danger">*</span></label>
                                                                    <select class="form-control" name="hod_user" style="font-size: 13px" id="hod_user">
                                                                        <option value="">Please choose user</option>
                                                                        @foreach($userList as $user)
                                                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div class="label-invalid error-hod_user"></div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                                                                <button type="button" class="btn btn-primary btn-sm js-confirm-need-review">
                                                                    Confirm
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
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

    </div>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script>
        let CSRF_TOKEN = "";
                window.onload = function() {
                    CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                }

        function passvalue(id)
        {
            var data_id = id;

            $.ajax({
                    data: {
                            "data_id": data_id,
                            _token: CSRF_TOKEN
                        }
                    , url: "{{ route('audit.displayrootcorrectiveajax')}}"
                    , type: "POST"
                    , success:function(response) {
                        console.log(response.rootdata)
                        console.log(response.correctdata)
                        if(response.status == 200)
                                {
                                    var rootlist = response.rootdata;
                                    var roothtmldatas='';

                                    if(rootlist.length>0){
                                        roothtmldatas=rootlist;
                                     }
                                    else{
                                        roothtmldatas=null;
                                    }

                                    var correctivelist = response.correctdata;
                                    var correcthtmldatas='';
                                    const removespace = new RegExp(/\s{3,}/, 'ig');
                                    const correctivelistdisplay = correctivelist.replace(removespace, '\n');

                                    if(correctivelist.length>0){
                                        correcthtmldatas=correctivelistdisplay;
                                     }
                                    else{
                                        correcthtmldatas=null;
                                    }
                                    $('#rootcause').html(roothtmldatas);
                                    $('#correctiveaction').html(correcthtmldatas);


                                }

                        }

                    });
        }

        $('.js_need_verify').on('click', function () {
            $('.error-is_need_verify').html('')
            if ($(this).val() === "1") {
                $('.hod-user-block').removeClass('d-none');
            } else {
                $('.hod-user-block').addClass('d-none');
            }
            $('#is_need_verify_input').val($(this).val())
        })
        $("#hod_user").on("change", function() {
            $('.error-hod_user').html('')
            $('#hod_user_input').val($(this).val())
        });
        $('.js-confirm-need-review').on('click', function () {
            const isNeedAuditeeVerify = $('#is_need_verify_input').val();
            const hodUser = $('#hod_user_input').val();
            if(isNeedAuditeeVerify) {
                if(isNeedAuditeeVerify === '1' && !hodUser) {
                    $('.error-hod_user').html('This field is required.')
                } else {
                    $('#propose_action_frm').submit();
                }
            } else {
                $('.error-is_need_verify').html('This field is required.')
            }
        })
        $('.js-update-btn').on('click', function (e) {
            e.preventDefault()
            let isFormValid = true;
            if(!$('textarea[name=correction]').val()) {
                $('.error-correction').html('This field is required.')
                isFormValid = false
            } else {
                $('.error-correction').html('')
            }
            if(!$('input[name=confirm_propose]:checked').val()) {
                $('.error-confirm_propose').html('This field is required.')
                isFormValid = false
            } else {
                $('.error-confirm_propose').html('');
            }
            if(isFormValid) {
                const issueId = $(this).attr('data-id');
                if($('#need_auditee_verify_modal_' + issueId).length) {
                    $('#need_auditee_verify_modal_' + issueId).modal('show')
                } else {
                    alert('An error occurred during processing. Please reload page!')
                }
            }
        })
    </script>


@endsection
