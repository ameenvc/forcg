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
        .scrollable{
            height:100%;
            overflow-x: scroll;
            overflow-y: scroll;
        }
    </style>

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
                        Edit
                    </li>
                </ol>
                {{-- <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="/user/corrective/corrissues">Corrective Management</a>
                    </li>
                </ol> --}}
            </nav>

            <div class="block-content block-content-full">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- Form Horizontal - Default Style -->
                        {{-- @if(count($errors)>0)
                        @foreach($errors->all() as $error)
                        <div class="alert alert-warning">
                            {{$error}}
                    </div>
                    @endforeach
                    @endif --}}
                    @include('sweet::alert')
                    <form class="mb-5" action="{{route ('user.corrective.update')}}" enctype="multipart/form-data" method="post" files="true">
                        {{csrf_field()}}
                        @if(count($data)>0)
                        @foreach($data as $dat)
                        @php $rr=$dat->id ;
                        $dept = DB::table('departments')->where('id',$dat->department)->value('departmentname');
                        @endphp
                        <input type="hidden" name="posted" value="{{$dat->id}}">
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="raised" style="font-size:13px;">Issue id</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="name" style="font-size:13px;" name="name" value="{{$dat->issueid}}" readonly="">
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="raised" style="font-size:13px;">Raised by</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="name" style="font-size:13px;" name="name" value="{{$dat->postedby}}" readonly="">
                                </div>
                            </div>


                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="title" style="font-size:13px;">Title <span style="color:red">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="title" style="font-size:13px;" name="title" value="{{$dat->title}}" required>
                                </div>
                            </div>

                            <div class="form-group col-sm-6" id="category-div">
                                <label class="col-sm-12 col-form-label" for="category" style="font-size:13px;">Category <span style="color:red">*</span></label>
                                <div class="col-sm-12">

                                    <select class="form-control" name="category" id="category" required="true" style="font-size:13px;" placeholder="Choose Category" onchange="showSource($(this).val())">
                                        <option value="" selected disabled>Please select the category name</option></br>
                                        @if($dat->category=='Legal Requirement')
                                        <option value="{{$dat->category}}" selected>{{$dat->category}}</option>
                                        @endif
                                        @if(count($categories)>0)
                                            @foreach($categories as $cat)
                                            @if($cat->categoryname != "Internal Audit")
                                            <option value="{{$cat->id}}" {{ intval($dat->category) === $cat->id ? 'selected' : '' }}> {{$cat->categoryname}}</option></br>
                                            @endif
                                            @endforeach
                                        @endif
                                        @if($dat->cat && $dat->cat->deleted_at!='')
                                        <option value="{{$dat->cat->id}}" selected>{{$dat->cat->categoryname}}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            {{-- @if($enable_source) --}}
                            <div class="form-group d-none col-sm-6" id="source_field">
                                <label class="col-sm-12 col-form-label" for="source" style="font-size:13px;">Source</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="source" style="font-size:13px;" name="source" value="{{ $dat->source }}">
                                </div>
                            </div>
                            {{-- @endif --}}
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
                                <label class="col-sm-12 col-form-label" for="type" style="font-size:13px;">Type <span style="color:red">*</span></label>
                                <div class="col-sm-12">
                                    <select class="form-control" name="type" required="true" style="font-size:13px;" required>
                                        {{-- <option value=" {{$dat->type }}" style="font-size:13px;"> {{$dat->type }}</option></br> --}}
                                        @if(count($type)>0)
                                        @foreach($type as $typ)
                                        {{-- @if($dat->type != $typ->name) --}}
                                        <option value="{{$typ->order}}" style="font-size:13px;" {{ $dat->typ && $dat->typ->name === $typ->name ? 'selected' : ''}}> {{$typ->name}}</option></br>
                                        {{-- @endif --}}
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="department" style="font-size:13px;">Department<span style="color:red">*</span></label>
                                <div class="col-sm-12">
                                    <select class="form-control" name="department" id="department" style="font-size:13px;" required="true" placeholder="Choose a Department" onchange="chooseDivision($(this).val(), $('#division_val').val())">
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
                                    <select class="form-control" id="division_select" name="division" style="font-size: 13px;" placeholder="Division" onchange="chooseArea($(this).val(), $('#area_val').val())">
                                        <option value="0" disabled>Choose Division</option></br>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-sm-6" id="area">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="area">Sub Division</label>
                                <div class="col-sm-12" id="action_number" name="action">
                                    <select class="form-control" id="area_select" name="area" style="font-size: 13px;" placeholder="Sub Division">
                                        <option value="0" disabled>Choose Sub Division</option></br>
                                    </select>
                                </div>
                            </div>
                            


                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" for="observation" style="font-size:13px;">Observation <span style="color:red">*</span></label>
                                <div class="col-sm-12">
                                    <textarea type="text" class="form-control" id="observation" style="font-size:13px;" name="observation" placeholder="Enter observation" required>{{$dat->observation}}</textarea>
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
                                    <span id="error_financial_loss" style="color:red; font-size: 12px; margin-left:10px;"></span>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="potential_cost_savings" style="font-size:13px;">Potential Cost Savings</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="potential_cost_savings" style="font-size:13px;" name="potential_cost_savings" value="{{ $dat->potential_cost_savings }}" {{!in_array(Auth::user()->email, $issue_reviewers) ? 'readonly' : ''}}>
                                    <span id="error_potential_cost_savings" style="color:red; font-size: 12px; margin-left:10px;"></span>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="action_file" style="font-size:13px;">Attach issue related files(avoid '#' and '&' in file names)</label>
                                <div class="col-sm-12">
                                    <input type="file" class="form-control" id="action_file" style="font-size: 13px;" name="action_file[]" multiple>
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="download" style="font-size:13px;">Attached files</label>
                                <div class="col-sm-12">
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

                                                <table class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
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
                                                                <td style="font-size:10px;">{{ $sno=$sno+1}}</td>
                                                                <td style="font-size:12px;">{{ $value1}}</td>
                                                                <td><a style="font-size:11px;" class="btn btn-success" href="{{asset("storage/issue_files/".$value1)}}" download="{{$value1}}">Download</a></td>
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

                                        <table class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                                            <div class="row justify-content-center">
                                                <thead>
                                                    <tr class="table-secondary">
                                                        <th style="width:5%;">S.No</th>
                                                        <th style="width:65%;">Attachments (Actions)</th>
                                                        <th style="width:30%;">Actions</th>

                                                    </tr>
                                                </thead>
                                                <?php $n=0; ?>

                                                @if( count($actiondocs) > 0 )
                                                @foreach($actiondocs as $value2)

                                                @if( $value2!="NULL" && $value2!="")
                                                <tbody>
                                                    <tr>
                                                        <td style="font-size:10px;">{{ $n=$n+1}}</td>
                                                        <td style="font-size:12px;">{{ $value2}}</td>
                                                        <td><a style="font-size:11px;" class="btn btn-success" href="{{asset("storage/issue_files/".$value2)}}" download="{{$value2}}">Download</a></td>
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
                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" for="action_file" style="font-size:13px;">Attach action files(avoid '#' and '&' in file names)</label>
                                <div class="col-sm-12">
                                    <input type="file" class="form-control" id="action_file" style="font-size: 13px;" name="action_file[]" multiple>
                                </div>
                            </div>
                            <div class="form-group col-sm-12" style="margin-bottom:10px;">
                                    <label class="col-sm-6 col-form-label" for="action_file" style="font-size:13px;">Is MOC Required? <span class="text-danger">*</span></label>
                                    <div class="col-sm-6">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_moc_required" id="moc_yes" value="yes" {{ $dat->is_moc_required === 'yes' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="moc_yes" style="font-size:13px;">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_moc_required" id="moc_no" value="no" {{ $dat->is_moc_required === 'no' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="moc_no" style="font-size:13px;">No</label>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $dnoneClass = $dat->is_moc_required === 'yes' ? '' : 'd-none';
                                @endphp
                                <div class="form-group moc-detail {{$dnoneClass}} col-sm-4">
                                    <label class="col-sm-12 col-form-label" for="download" style="font-size:13px;">MOC Title <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" placeholder="Enter Title" style="font-size: 13px;" id="mocTitle" name="mocTitle" value="{{$dat->is_moc_required === 'yes' && $dat->moc ? $dat->moc->title : $dat->title}}">
                                    </div>
                                </div>
                                @if($dat->is_moc_required === 'yes')
                                <div class="form-group moc-detail {{$dnoneClass}} col-sm-4">
                                    <label class="col-sm-12 col-form-label" style="font-size:13px;">Status</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" style="font-size: 13px;"  value="{{$dat->moc ? $dat->moc->status : ''}}" readonly>
                                    </div>
                                </div>
                                <div class="form-group moc-detail {{$dnoneClass}} col-sm-4">
                                    <label class="col-sm-12 col-form-label" style="font-size:13px;">MOC ID</label>
                                    <div class="col-sm-12">
                                        <a href="#" class="view-moc-detail" style="font-size:13px;" data-toggle="tooltip" title="Click to view MOC details"
                                            data-route="{{ $dat->moc ? $dat->moc->id : ''}}">
                                            {{$dat->moc ? $dat->moc->crid : ''}}
                                        </a>
                                    </div>
                                </div>
                                @endif
                                @if($dat->is_moc_required === 'no')
                                <div class="form-group moc-detail d-none col-sm-4">
                                    <label class="col-sm-12 col-form-label" style="font-size:13px;">Review Authority <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <select class="js-select2 form-control" name="reviewAuthority" id="reviewAuthority"  style="font-size:13px;width:100%;" data-placeholder="Choose Review Authority">
                                            <option value=""></option>
                                            @if(count($reviewers)>0)
                                            @foreach($reviewers as $reviewer)
                                            <option value="{{$reviewer->id}}">{{$reviewer->name}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group moc-detail d-none col-sm-4">
                                    <label class="col-sm-12 col-form-label" style="font-size:13px;">Approve Authority <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <select class="js-select2 form-control" name="approveAuthority" id="approveAuthority"  style="font-size:13px;width:100%;" data-placeholder="Choose Approve Authority">
                                            <option value=""></option>
                                            @if(count($approvers)>0)
                                            @foreach($approvers as $approver)
                                            <option value="{{$approver->id}}">{{$approver->name}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                @endif
                                <div class="form-group moc-detail {{$dnoneClass}} col-sm-12">
                                    <label class="col-sm-12 col-form-label" style="font-size:13px;">Change description(specific to the NCR/CA)<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <textarea class="form-control" id="changeDescription" placeholder="Enter change description" name="changeDescription" style="font-size:13px;">{{$dat->moc ? $dat->change_description : ''}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12 text-right">
                                    <button type="submit" for="submit" class="btn btn-primary" data-toggle="click-ripple" style="font-size:13px;" onclick="return confirm('Are you sure to propose the actions?')">Update</button>
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

    <div id="view-detail"></div>
<!-- Below include file contains validation logic for financial loss and potential cost saving -->
@include('user.corrective.common.financialLossAndPotentialCostSavingValidation')

@php
    $mode = $data[0]->cat && ($data[0]->cat->is_pnc_cat=='yes' || $data[0]->cat->is_process_cat=='yes' || $data[0]->cat->is_vendor_cat=='yes') ? 'edit' : 'blank';
@endphp
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
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
                console.log(source_enabled);
                if (source_enabled) {
                    source_selector.removeClass('d-none');
                    source_selector.addClass('d-inline-block');
                } else {
                    source_selector.removeClass('d-inline-block');
                    source_selector.addClass('d-none');
                }
                if(response.enable_detection_stage){
                    detection_selector.removeClass('d-none')
                   $('#detectionStage').prop('required',true);
                }else{
                    detection_selector.addClass('d-none');
                    $('#detectionStage').prop('required',false);
                }
                if(categoryId==id){
                    $('#source').val('{{$data[0]->source}}');
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
                    // $('.dropdown-ncr-field').select2();
                    $('.dropdown-ncr-field').addClass('js-select2');
                }
            }
            , error: function(err) {
                console.log(err);
            }
        });
    }

</script>

<script type="text/javascript">
    let department = '{{ $dat->department }}';
    let area_id = '{{$dat->area_id}}';
    let show_area = 0;
    let area_data = [];
    let area_selector = $('#area');
    let area_select_selector = $('#area_select');
    let division_selector = $('#division');
    let division_select_selector = $('#division_select');
    let division_select = 0;
    area_select_selector.val(area_id);
    var previous_ncr_reference = "{{$dat->previous_ncr_reference}}";
    area_selector.removeClass('d-none').addClass('d-inline-block');
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

</script>
@php
    $statusOfMoc = $data[0]->moc ? $data[0]->moc->status : '';
    $title = $data[0]->moc ? $data[0]->moc->title : $data[0]->title;
    $changeDesc = $data[0]->change_description;
@endphp
<script>
    const statusOfMoc = '{{$statusOfMoc}}';
    $('input[name="is_moc_required"]').on('change', function () {
    const selectedValue = $(this).val();
    if (selectedValue === 'no' && (statusOfMoc === 'Reviewed' || statusOfMoc === 'Approved')) {

       Swal.fire({
            title: 'Warning',
            text: 'This MOC has already progressed beyond the Draft stage and cannot be deleted. Please close or cancel the MOC from the Change Management module.',
            icon: 'warning',
        });

        $('input[name="is_moc_required"][value="yes"]').prop('checked', true);

        $('.moc-detail').removeClass('d-none');
        $('#mocTitle, #changeDescription')
            .attr('required', true);
        return;
    }

    // Normal behavior
    if (selectedValue === 'yes') {
        $('.moc-detail').removeClass('d-none');
        $('#mocTitle, #reviewAuthority, #approveAuthority, #changeDescription')
            .attr('required', true);
        $('#mocTitle').val(@json($title));
        $('#changeDescription').val(@json($changeDesc));
        $('#reviewAuthority, #approveAuthority').val(null).trigger('change');
    } else {
        $('.moc-detail').addClass('d-none');
        $('#mocTitle, #reviewAuthority, #approveAuthority, #changeDescription')
            .attr('required', false);
    }
    });
</script>
<script>
    $('body').on('click', '.view-moc-detail', function () {
        const id = $(this).attr('data-route');
        $.ajax({
            url : window.location.origin+`/user/context/changelog/${id}/show`,
            type: 'GET',
            success: function (data) {
                if (data.success) {
                    $('#view-detail').html(data.view)
                    const modalEle = $(`#viewModal${data.id}`);
                    modalEle.modal('show');
                } else {
                    alert('Data is not found')
                }
            },
            error: function () {
                alert('Handle error');
            }
        })
    });
</script>
<script src="{{versionedCache('js/corrective/edit_corrective.js')}}"></script>



</html>
@endsection
