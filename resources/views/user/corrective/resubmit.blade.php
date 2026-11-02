@extends('layouts.multiselect')

@section('content')

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<style>
    .titleheading {
        font-size: 10px !important;
        text-transform: capitalize !important;
        position: sticky !important;
        position: relative !important;
        top: 2px !important;
        font-weight: 600 !important;
    }

    .td-class {
        text-align: center !important;
        font-size: 10px !important;
    }

    .swal-modal {
        width: 600px !important;
    }

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
    .border-3 {
        border-width: 3px !important;
    }

    .font-15{
        font-size:12px !important;
    }

</style>
<!-- END Hero -->

<!-- Page Content -->
<div class="content" style="">
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
                    Resubmit Issue
                </li>
            </ol>
            {{-- <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/user/corrective/corrissues">Corrective Management</a>
                </li>
            </ol> --}}
        </nav>
        <div class="block-content block-content-full space-y-3">
            <nav class="d-flex flex-column flex-lg-row items-center justify-content-between gap-2">
                <a href="javascript:void(0)" class="btn btn-lg btn-alt-secondary bg-transparent w-100 text-start fs-sm d-flex align-items-center justify-content-between gap-3">
                    <div class="flex-grow-0 rounded-circle border border-3 border-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        1
                    </div>
                    <div class="flex-grow-1 text-left ml-2">
                        <div class="font-15">Resubmit Issue</div>
                        <div class="fw-normal">Fill all required</div>
                    </div>
                </a>
                @if(!in_array(Auth::user()->email, $issue_reviewers))
                <a href="javascript:void(0)" class="btn btn-lg btn-alt-secondary bg-transparent w-100 text-start fs-sm d-flex align-items-center justify-content-between gap-3">
                    <div class="flex-grow-0 rounded-circle bg-body-dark d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        2
                    </div>
                    <div class="flex-grow-1 text-left ml-2">
                        <div class="font-15">Entry Review</div>
                        <div class="fw-normal">Open CA</div>
                    </div>
                </a>
                @endif

                <a href="javascript:void(0)" class="btn btn-lg btn-alt-secondary bg-transparent w-100 text-start fs-sm d-flex align-items-center justify-content-between gap-3">
                    <div class="flex-grow-0 rounded-circle bg-body-dark d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        {{!in_array(Auth::user()->email, $issue_reviewers) ? 3 : 2}}
                    </div>
                    <div class="flex-grow-1 text-left ml-2">
                        <div class="font-15">ProposeAction</div>
                        <div class="fw-normal">My Open CA</div>
                    </div>
                </a>
                <a href="javascript:void(0)" class="btn btn-lg btn-alt-secondary bg-transparent w-100 text-start fs-sm d-flex align-items-center justify-content-between gap-3">
                    <div class="flex-grow-0 rounded-circle bg-body-dark d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        {{!in_array(Auth::user()->email, $issue_reviewers) ? 4 : 3}}
                    </div>
                    <div class="flex-grow-1 text-left ml-2">
                        <div class="font-15">Review Action</div>
                        <div class="fw-normal">Open CA</div>
                    </div>
                </a>
                <a href="javascript:void(0)" class="btn btn-lg btn-alt-secondary bg-transparent w-100 text-start fs-sm d-flex align-items-center justify-content-between gap-3">
                    <div class="flex-grow-0 rounded-circle bg-body-dark d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        {{!in_array(Auth::user()->email, $issue_reviewers) ? 5 : 4}}
                    </div>
                    <div class="flex-grow-1 text-left ml-2">
                        <div class="font-15">Update Progress</div>
                        <div class="fw-normal">My Open CA</div>
                    </div>
                </a>
                <a href="javascript:void(0)" class="btn btn-lg btn-alt-secondary bg-transparent w-100 text-start fs-sm d-flex align-items-center justify-content-between gap-3">
                    <div class="flex-grow-0 rounded-circle bg-body-dark d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        {{!in_array(Auth::user()->email, $issue_reviewers) ? 6 : 5}}
                    </div>
                    <div class="flex-grow-1 text-left ml-2">
                        <div class="font-15">Closure Review</div>
                        <div class="fw-normal">Open CA</div>
                    </div>
                </a>


            </nav>
        </div>
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Form Horizontal - Default Style -->
                    <form class="mb-5" onsubmit="return approveIssueCorr(event)" action="{{url('/user/corrective/resubmit')}}" enctype="multipart/form-data" method="post" files="true">
                        {{csrf_field()}}
                        <input type="hidden" name="postedby" value="{{Auth::user()->employeeid}}">

                        @include('sweet::alert')

                        @if(Session::has('msg'))
                        <div class="alert alert-primary">
                            <a class="close" data-dismiss="alert">×</a>
                            {!!Session::get('msg')!!}
                        </div>
                        @endif
                        @if(count($data)>0)
                        @foreach($data as $dat)
                        <?php $rr=$dat->id ?>
                        <input type="hidden" name="id" value="{{$dat->id}}">
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="raised" style="font-size:13px;">Raised by</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="name" style="font-size:13px;" name="name" value="{{Auth::user()->name}}" readonly="">
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="title" style="font-size:13px;">Title <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="title" style="font-size:13px;" name="title" value="{{$dat->title}}" required>
                                </div>
                            </div>

                            <div class="form-group col-sm-6" id="category-div">
                                <label class="col-sm-12 col-form-label" for="category" style="font-size:13px;">Category <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select class="form-control js-select2" name="category" id="category" style="font-size:13px;" onchange="showSource($(this).val())" required>
                                        <option value="" selected disabled>Please select the category name</option></br>
                                        @if($dat->category=='Legal Requirement')
                                        <option value="{{$dat->category}}" selected>{{$dat->category}}</option>
                                        @elseif($dat->category=='Incident Investigation')
                                        <option value="{{$dat->category}}" selected>{{$dat->category}}</option>
                                        @endif
                                        @if(count($categories)>0)
                                        @foreach($categories as $cat)
                                        <option value="{{$cat->id}}" style="font-size:13px;" {{ intval($dat->category) === $cat->id ? 'selected' : ''}}> {{$cat->categoryname}}</option></br>
                                        @endforeach
                                        @endif
                                        @if($dat->cat && $dat->cat->deleted_at!='')
                                        <option value="{{$dat->cat->id}}" selected>{{$dat->cat->categoryname}}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="form-group d-none col-sm-6" id="source_field">
                                <label class="col-sm-12 col-form-label" for="source" style="font-size:13px;">Source</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="source" style="font-size:13px;" name="source" value="{{ $dat->source }}">
                                </div>
                            </div>

                            <div class="form-group d-none col-sm-6" id="detection_stage_field">
                                <label class="col-sm-12 col-form-label" for="detection_stage_field" style="font-size:13px;">{{$detectionStageLabel ? $detectionStageLabel->name : 'Detection Stage'}} <span class="text-danger">{{in_array(Auth::user()->email,$issue_reviewers) ? '*' : '' }}</span></label>
                                <div class="col-sm-12">
                                    <select class="form-control js-select2" name="detectionStage" id="detectionStage" style="font-size:13px;width:100%;" data-placeholder="Select where detected">
                                        <option></option>
                                        @if(count($detectionStages)>0)
                                            @foreach($detectionStages as $stage)
                                            <option value="{{$stage->id}}" {{$stage->id==$dat->detection_stage ? 'selected' : ''}} style="font-size:13px;">{{$stage->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="department" style="font-size:13px;">Responsible Department for correction <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select required class="form-control js-select2" name="department" id="department" style="font-size:13px;" onchange="chooseDivision($(this).val(), $('#division_val').val())">
                                        @if(count($departments)>0)
                                        @foreach($departments as $dep)
                                        <option value="{{$dep->id}}" style="font-size:13px;" {{ $dep->id == $dat->correction_department ? 'selected' : '' }}> {{$dep->departmentname}}</option></br>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" id="division_val" value="{{ $dat->division_id }}" />
                            <input type="hidden" id="area_val" value="{{ $dat->area_id }}" />

                            <div class="form-group col-sm-6" id="division">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="division">Division</label>
                                <div class="col-sm-12">
                                    <select class="form-control js-select2" id="division_select" name="division" style="font-size: 13px;width:100%;" placeholder="Division" onchange="chooseArea($(this).val(), $('#area_val').val())">
                                        <option value="0" disabled>Choose Division</option></br>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-sm-6" id="area">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="area">Sub Division</label>
                                <div class="col-sm-12" id="action_number" name="action">
                                    <select class="form-control js-select2" id="area_select" name="area" style="font-size: 13px;width:100%;" placeholder="Sub Division">
                                        <option value="0" disabled>Choose Sub Division</option></br>
                                    </select>
                                </div>
                            </div>


                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="type" style="font-size:13px;">Type <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select class="form-control js-select2" name="type" required="true" style="font-size:13px;">
                                        @if($dat->type=='' || $dat->type=='-')
                                        <option value="-" selected>-</option>
                                        @endif
                                        @if(count($type)>0)
                                        @foreach($type as $typ)
                                        <option value="{{$typ->order}}" style="font-size:13px;" {{ $dat->typ=== $typ->order  && ($dat->typ->name === $typ->name ) ? 'selected' : '' }}> {{$typ->name}}</option></br>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" for="observation" style="font-size:13px;">Observation <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <textarea type="text" class="form-control" id="observation" style="font-size:13px;" name="observation" required placeholder="Enter observation">{{$dat->observation}}</textarea>
                                </div>
                            </div>
                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" for="name" style="font-size:13px;">Is this a previously occurred NCR?</label>
                                <div class="col-sm-12">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="ncr_yes" name="ncr_question" value="yes" {{$dat->is_previously_occurred=='yes' ? 'checked' : ''}}>
                                        <label class="form-check-label" for="ncr_yes" style="font-size:13px;">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="ncr_no" name="ncr_question" value="no" {{$dat->is_previously_occurred=='no' ? 'checked' : ''}}>
                                        <label class="form-check-label" for="ncr_no" style="font-size:13px;">No</label>
                                    </div>   
                                </div>
                            </div>
                            <div class="form-group {{$dat->is_previously_occurred=='yes' ? '' : 'd-none'}} col-sm-6" id="no_of_occurrence_div">
                                <label class="col-sm-12 col-form-label" for="no_of_occurrence" style="font-size:13px;">No. of Occurrence</label>
                                <div class="col-sm-12">
                                    <input type="number" min="1" placeholder="Enter No. of Occurrence" class="form-control" id="no_of_occurrence" style="font-size:13px;" name="no_of_occurrence" value="{{$dat->no_of_occurrence}}">
                                </div>
                            </div>
                            <div class="form-group {{$dat->is_previously_occurred=='yes' ? '' : 'd-none'}} col-sm-6" id="previous_ncr_reference_div">
                                <label class="col-sm-12 col-form-label" for="previous_ncr_reference" style="font-size:13px;">Previous NCR Reference</label>
                                <div class="col-sm-12">
                                    <select class="form-control" name="previous_ncr_reference" id="previous_ncr_reference" style="font-size:13px;width:100%;">
                                        <option></option> 
                                        
                                    </select>
                                </div>
                                <div class="col-sm-12 mt-2 {{$dat->previous_ncr_reference!=0 || $dat->previous_ncr_reference==null ? 'd-none' : ''}}" id="otherNcrReferenceDiv">
                                    <input type="text" class="form-control" id="other_ncr" style="font-size:13px;" name="other_ncr" placeholder="Enter Previous NCR Reference" value="{{$dat->other_previous_ncr_reference}}">
                                </div>
                            </div>
                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" for="agreedaction" style="font-size:13px;">Recommended action</label>
                                <div class="col-sm-12">
                                    <textarea type="text" class="form-control" id="agreedaction" style="font-size:13px;" name="agreedaction">{{$dat->agreedaction}}</textarea>
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="financial_loss" style="font-size:13px;">Financial Loss</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="financial_loss" style="font-size:13px;" name="financial_loss" value="{{ $dat->financial_loss }}" {{!in_array(Auth::user()->email, $issue_reviewers) ? 'readonly' : ''}}>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="potential_cost_savings" style="font-size:13px;">Potential Cost Savings</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="potential_cost_savings" style="font-size:13px;" name="potential_cost_savings" value="{{ $dat->potential_cost_savings }}" {{!in_array(Auth::user()->email, $issue_reviewers) ? 'readonly' : ''}}>
                                </div>
                            </div>


                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="action_file" style="font-size:13px;">Attach issue related files(avoid '#' and '&' in file names)</label>
                                <div class="col-sm-12">
                                    <input type="file" class="form-control" id="attachment" style="font-size: 13px;" name="file[]" multiple>
                                </div>
                            </div>
                            <div class="form-group col-sm-6" id="attachmentsDiv">
                                <label class="col-sm-12 col-form-label" for="download" style="font-size:13px;">Attached file</label>
                                <div class="col-sm-12" >
                                    <button type="button" class="btn btn-primary btn-small" style="font-size:12px;width: 150px;" data-toggle="modal" data-id="{{ $dat->id }}" data-target="#viewModal{{$dat->id}}"><strong>Attachments&nbsp(Issues)</strong> </button>
                                    @if( count($actiondocs) > 0 )
                                    <button type="button" class="btn btn-primary btn-small" style="font-size:12px;width: 157px;" data-toggle="modal" data-id="" data-target="#viewModal"><strong>Attachments&nbsp(Actions)</strong> </button>
                                    @endif
                                    <div id="viewModal{{$dat->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h3 class="modal-title">Attachments</h3>
                                                </div>

                                                <table id="issueAttachmentTable" class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                                                    <div class="row justify-content-center">
                                                        <thead>
                                                            <tr class="table-secondary">
                                                                <th style="width:5%;">S.No</th>
                                                                <th style="width:65%;">Attachments (Issues)</th>
                                                                <th style="width:30%;">Actions</th>

                                                            </tr>
                                                        </thead>
                                                        <?php $sno=0; ?>

                                                        @if( count($issuedocs) > 0 )
                                                        @foreach($issuedocs as $value1)

                                                        @if( $value1!="NULL" )
                                                        <tbody>
                                                            <tr>
                                                                <td style="font-size:10px; ;">{{ $sno=$sno+1}}</td>
                                                                <td style="font-size:12px;">{{ $value1}}</td>
                                                                <td><a style="font-size:11px;" class="btn btn-success" href="{{asset("storage/issue_files/".$value1)}}" download="{{$value1}}">Download</a>
                                                                    @if(Gate::allows('delete-corrective') || Gate::allows('delete-all') || in_array(Auth::user()->email,$issue_reviewers) || $dat->postedby==Auth::user()->employeeid)
                                                                    <button class="btn btn-secondary remove-issue-attachment" data-filename="{{ $value1 }}" style="font-size:11px;">Remove</button>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                        @else
                                                        <div class="row justify-content-center">
                                                            <h2>No Files</h2>
                                                        </div>
                                                        @endif
                                                        {{-- @endif  --}}
                                                        @endforeach
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
                            <div id="viewModal" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h3 class="modal-title">Attachments</h3>
                                        </div>

                                        <table id="actionAttachmentTable"class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                                            <div class="row justify-content-center">
                                                <thead>
                                                    <tr class="table-secondary">
                                                        <th style="width:5%;">S.No</th>
                                                        <th style="width:65%;">Attachments (actions)</th>
                                                        <th style="width:30%;">Actions</th>

                                                    </tr>
                                                </thead>
                                                <?php $n=0; ?>

                                                @if( count($actiondocs) > 0 )
                                                @foreach($actiondocs as $value2)

                                                @if( $value2!="NULL" && $value2!="")
                                                <tbody>
                                                    <tr>
                                                        <td style="font-size:10px; ;">{{ $n=$n+1}}</td>
                                                        <td style="font-size:12px;">{{ $value2}}</td>
                                                        <td>
                                                            <a style="font-size:11px;" class="btn btn-success" href="{{asset("storage/issue_files/".$value2)}}" download="{{$value2}}">Download</a>
                                                            {{-- <button class="btn btn-secondary remove-action-attachment" data-filename="{{ $value2 }}" style="font-size:11px;">Remove</button> --}}
                                                        </td>
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
                                        </table>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="date" style="font-size:13px;">Recommended due date <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="date" class="js-datepicker form-control" style="font-size:13px;" id="date" name="duedate" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy" value="{{$dat->correction_target_date}}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12 text-right">
                                <button for="submittl" type="submit" class="btn btn-primary" style="font-size:13px;">Resubmit</button>
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
</div>
<!-- END Main Container -->

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
                            <!-- END Sales -->
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@php 
    $mode = $data[0]->cat && ($data[0]->cat->is_pnc_cat=='yes' || $data[0]->cat->is_process_cat=='yes' || $data[0]->cat->is_vendor_cat=='yes') ? 'edit' : 'blank';
@endphp
<!-- END Apps Modal -->
<script src="{{ asset( 'js/oneui.core.min.js')}}"></script>
<script src="{{ asset( 'js/oneui.app.min.js')}}"></script>
<script src="{{ asset( 'js/plugins/select2/js/select2.full.min.js')}}"></script>
<script>
    jQuery(function() {
        One.helpers(['flatpickr', 'colorpicker', 'maxlength', 'select2', 'masked-inputs', 'rangeslider']);
    });

</script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#sel_not').select2({});
    });
    var removedIssueFilenames = [];
    // $(document).on('click', '.remove-action-attachment', function () {
    //     var filename = $(this).data('filename');
    //     $(this).closest('tr').remove();
    //     removedActionFilenames.push(filename);
    //     $('#actionAttachmentTable tbody tr').each(function(index) {
    //         $(this).find('td:first').text(index + 1);
    //         $(this).find('td:first').css('font-size', '10px');
    //     });
    // });
    $(document).on('click', '.remove-issue-attachment', function () {
        var filename = $(this).data('filename');
        $(this).closest('tr').remove();
        removedIssueFilenames.push(filename);
        $('#issueAttachmentTable tbody tr').each(function(index) {
            $(this).find('td:first').text(index + 1);
            $(this).find('td:first').css('font-size', '10px');
        });
    });
    function approveIssueCorr(event){
        if(confirm('Do you want to proceed?')){
            $('<input />').attr('type', 'hidden')
                .attr('name', 'removedIssueFilenames')
                .attr('value', JSON.stringify(removedIssueFilenames))
                .appendTo($('#attachmentsDiv'));
            // $('<input/>').attr('type', 'hidden')
            //     .attr('name', 'removedActionFilenames')
            //     .attr('value', JSON.stringify(removedActionFilenames))
            //     .appendTo($('#attachmentsDiv'));
            return true;
        }
        event.preventDefault();
        return false;
    }

</script>
@php
    $isReviewer = in_array(Auth::user()->email,$issue_reviewers);  
@endphp
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    let department = '{{ $dat->department }}';
    let area_id = '{{$dat->area_id}}';
    let show_area = 0;
    let area_data = [];
    let area_selector = $('#area');
    let area_select_selector = $('#area_select');
    let division_selector = $('#division');
    let division_select_selector = $('#division_select');
    let division_select = '';
    area_select_selector.val(area_id);
    area_selector.removeClass('d-none').addClass('d-inline-block');
    var previous_ncr_reference = "{{$dat->previous_ncr_reference}}";
    chooseDivision($('#department').val(), $('#division_val').val());

    function chooseDivision(id, division_id) {
        division_select_selector.empty();
        let get_areas_url = '{{ route("admin.departmentAreas.getAreas", ":id") }}';
        get_areas_url = get_areas_url.replace(':id', id);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            }
            , method: 'get'
            , url: get_areas_url
            , async: false
            , success: function(resp) {
                show_area = resp.data.length;
                area_data = resp.data;
            }
            , error: function(err) {
                console.log(err);
            }
        });

        let the_url = '{{ route("admin.departmentDivisions.getDivisions", ":id") }}';
        the_url = the_url.replace(':id', id);
        let option_text = "";
        let option_text1 = "";
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            }
            , method: 'get'
            , url: the_url
            , async: false
            , success: function(resp) {
                if (!resp.data.length) {
                    division_selector.removeClass('d-inline-block').addClass('d-none');
                    area_selector.removeClass('d-inline-block').addClass('d-none');
                    area_select_selector.val(0);
                    division_select_selector.val(0);
                } else {
                    if (show_area) {
                        area_selector.removeClass('d-none');
                        area_selector.addClass('d-inline-block');
                        for (var i = 0; i < area_data.length; i++) {
                            let if_selected = area_data[i].id === Number(area_id) ? 'selected' : '';
                            option_text1 += `<option value="${area_data[i].id}" ${if_selected}>${area_data[i].name}</option>`
                        }
                        area_select_selector.empty().append('<option value="0">Choose Sub Division</option></br>' + option_text1);

                    } else {
                        area_selector.removeClass('d-inline-block');
                        area_selector.addClass('d-none');
                        area_select_selector.val(0);
                    }

                    division_selector.removeClass('d-none').addClass('d-inline-block');
                    for (var i = 0; i < resp.data.length; i++) {
                        let if_selected = resp.data[i].id == Number(division_id) ? 'selected' : '';
                        //if (id != department) if_selected = '';
                        option_text += `<option value="${resp.data[i].id}" ${if_selected}>${resp.data[i].name}</option>`
                    }
                    division_select_selector.empty().append(`<option value="0">Choose Division</option></br>` + option_text);
                    division_select = division_select_selector.val();
                }

            }
            , error: function(err) {
                console.log(err);
            }
        });

    }


    chooseArea(division_select, $('#area_val').val());

    function chooseArea(id, area_id) {
        let the_url = '{{ route("admin.departmentDivisions.getAreasOfADivision", ":id") }}';
        the_url = the_url.replace(':id', id);
        let option_text = "";
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            }
            , method: 'get'
            , url: the_url
            , success: function(resp) {
                if (!resp.data.length) {
                    area_selector.removeClass('d-inline-block');
                    area_selector.addClass('d-none');
                    area_select_selector.val(0);
                } else {
                    area_selector.removeClass('d-none');
                    area_selector.addClass('d-inline-block');
                    for (var i = 0; i < resp.data.length; i++) {
                        let if_selected = resp.data[i].id === Number(area_id) ? 'selected' : '';
                        option_text += `<option value="${resp.data[i].id}" ${if_selected}>${resp.data[i].name}</option>`
                    }
                    area_select_selector.empty().append('<option value="0">Choose Sub Division</option></br>' + option_text);
                    let area_select = area_select_selector.val();
                }
            }
            , error: function(err) {
                console.log(err);
            }
        });
    }

    var categoryId = $('#category').val();
    var detectionStageValue = $('#detectionStage').val();
    showSource(categoryId);

    function showSource(id) {
        let the_url = "{{ route('user.corrective.enableSource', ':id')}}";
        the_url = the_url.replace(':id', id);

        let source_selector = $('#source_field');
        let detection_selector = $('#detection_stage_field');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            }
            , method: 'get'
            , url: the_url
            , success: function(response) {
                let source_enabled = response.data;
                if (source_enabled) {
                    source_selector.removeClass('d-none');
                    source_selector.addClass('d-inline-block');
                } else {
                    source_selector.removeClass('d-inline-block');
                    source_selector.addClass('d-none');
                }
                if(response.enable_detection_stage){
                    detection_selector.removeClass('d-none')
                    '{{$isReviewer}}'== true ? $('#detectionStage').prop('required',true) : $('#detectionStage').prop('required',false);
                }else{
                    detection_selector.addClass('d-none');
                    $('#detectionStage').prop('required',false)
                }
                if(categoryId==id){
                    $('#source').val('{{$dat->source}}');
                    $('#detectionStage').val(detectionStageValue).trigger('change');
                }else{
                    $('#detectionStage').val('').trigger('change');
                    $('#source').val('');
                }

            }
            , error: function(err) {
                console.log(err);
            }
        });
        const category = id;
        const mode = '{{$mode}}';
        const dataid = '{{$data[0]->id}}';
        $.ajax({ 
            method: 'GET', 
            url: window.location.origin + `/user/corrective/get-product-ncr-fields/${category}/${mode}/${dataid}`, 
            success: function(response) {
                $('.productNcrFieldClass').remove();
                if(response.fields_exist){
                    $('#source_field').after(response.fields);
                    $('.dropdown-ncr-field').select2();
                }
            }
            , error: function(err) {
                console.log(err);
            }
        });
    }

</script>
<script src="{{versionedCache('js/corrective/edit_corrective.js')}}"></script>
@endsection
