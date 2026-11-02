{{-- Start Investigation Modal --}}
<div id="investigationModal{{$issue->id}}"
     class="modal fadebd-example-modal-lg investigation-modal scrollable"
     aria-labelledby="investigationModalLabel" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="investigationModalLabel">Investigation Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form{{ $issue->id }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-body m-body p-4">
                    <div class="alert alert-primary d-none w-100"
                         id="success_investigation{{ $issue->id}}">
                        Root cause updated successfully
                    </div>

                    <ul class="alert alert-danger d-none w-100"
                        id="error_investigation{{ $issue->id}}"
                        style="font-size:12px;">
                        Internal server error, we are trying to fix them soon
                    </ul>

                    <ul class="alert alert-warning d-none w-100"
                        id="validation_error_investigation{{ $issue->id}}"></ul>

                    <input type="hidden" name="corrective_id"
                           id="corrective_id{{ $issue->id }}" value="{{ $issue->id }}">

                    <div class="form-group d-inline-block w-100">
                        <label class="col-sm-12 col-form-label"
                               style="font-size:13px;">1. Was this a one-time occurrence, or does it happen regularly?</label>
                        <div class="col-sm-12">
                            <input type="radio" value="one_time" name="occurrence"
                                    {{ $issue->occurrence === 'one_time' ? 'checked' : '' }}>
                            <label style="font-size: 13px;" class="ml-1">One time</label>
                            <input type="radio" value="regularly" name="occurrence"
                                    {{ $issue->occurrence === 'regularly' ? 'checked' : '' }}>
                            <label style="font-size: 13px;" class="ml-1">Regularly</label>
                        </div>
                    </div>
                    <div class="form-group d-inline-block w-100">
                        <label class="col-sm-12 col-form-label"
                               style="font-size:13px;">2. What processes or systems are related to the problem?</label>
                        <div class="col-sm-12">
                            <input type="text" style="font-size:13px;"
                                   class="form-control" name="system_related_problems"
                                   id="system_related_problems{{ $rr }}"
                                   value="{{ $issue->system_related_problems }}" />
                        </div>
                    </div>
                    <div class="form-group d-inline-block w-100">
                        <label class="col-sm-12 col-form-label"
                               style="font-size:13px;">3. Were there any recent changes or modifications?</label>
                        <div class="col-sm-12">
                            <input type="radio" value="1"
                                   name="recent_changes" {{ $issue->recent_changes === 1 ? 'checked' : '' }}>
                            <label style="font-size: 13px;" class="ml-1">Yes</label>
                            <input type="radio" value="-1"
                                   name="recent_changes" {{ $issue->recent_changes === -1 ? 'checked' : '' }}>
                            <label style="font-size: 13px;" class="ml-1">No</label>
                        </div>
                    </div>
                    <div class="form-group d-inline-block w-100">
                        <label class="col-sm-12 col-form-label"
                               style="font-size:13px;">4. Did everyone follow established procedures?</label>
                        <div class="col-sm-12">
                            <input type="radio" value="1" name="follow_procedures"
                                    {{ $issue->follow_procedures === 1 ? 'checked' : '' }}>
                            <label style="font-size: 13px;" class="ml-1">Yes</label>
                            <input type="radio" value="-1" name="follow_procedures"
                                    {{ $issue->follow_procedures === -1 ? 'checked' : '' }}>
                            <label style="font-size: 13px;" class="ml-1">No</label>
                        </div>
                    </div>
                    <div class="form-group d-inline-block w-100">
                        <label class="col-sm-12 col-form-label" style="font-size:13px;"
                               id="factors_append{{ $rr }}">5. What are the contributing factors?</label>
                        @php
                            $factors_array = explode(',', $issue->contributing_factors);
                            $external_factors_array = explode(',', $issue->external_factors);
                        @endphp
                        <div class="col-sm-12">
                            <select class="form-control js-select2" name="contributing_factors[]"
                                    id="factors{{ $rr }}" style="width: 100%;" multiple onchange="displayDetails('{{ $rr }}')">
                                <option value="human" {{ in_array('human', $factors_array)? 'selected' : '' }}></option>>Human</option>
                                <option value="technical" {{ in_array('technical', $factors_array)? 'selected' : '' }}>Technical</option>
                                <option value="organizational" {{ in_array('organizational', $factors_array)? 'selected' : '' }}>Organizational</option>
                                <option value="material" {{ in_array('material', $factors_array)? 'selected' : '' }}>Material</option>
                                <option value="machine" {{ in_array('machine', $factors_array)? 'selected' : '' }}>Machine</option>
                                <option value="design" {{ in_array('design', $factors_array)? 'selected' : '' }}>Design</option>
                                <option value="method" {{ in_array('method', $factors_array)? 'selected' : '' }}>Method</option>
                                <option value="envi_aspects" {{ in_array('envi_aspects', $factors_array)? 'selected' : '' }}>Environmental aspects</option>
                                <option value="others" {{ in_array('others', $factors_array)? 'selected' : '' }}>Others</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group w-100 d-none" id="factors_details{{ $issue->id }}">
                        <label class="col-sm-12 col-form-label"
                               style="font-size:13px;">Contributing factors details</label>
                        <div class="col-sm-12">
                            <input type="text" style="font-size:13px;" class="form-control"
                                   name="factors_details" id="factors_details_val{{ $rr }}"
                                   value="{{ $issue->contributing_factors_details }}" />
                        </div>
                    </div>
                    <div class="form-group d-inline-block w-100">
                        <label class="col-sm-12 col-form-label"
                               style="font-size:13px;" id="influence_append{{ $rr }}">
                            6. Are there any external factors or influences?
                        </label>
                        <div class="col-sm-12">
                            <select class="form-control js-select2"
                                    name="external_factors[]" id="influence{{ $issue->id }}"
                                    style="width: 100%;" multiple onchange="displayInfluenceDetails('{{ $rr }}')">
                                <option value="market_conditions"
                                        {{ in_array('market_conditions', $external_factors_array) ? 'selected' : '' }}>
                                    market conditions
                                </option>
                                <option value="regulatory_changes"
                                        {{ in_array('regulatory_changes', $external_factors_array) ? 'selected' : ''}}>
                                    regulatory changes
                                </option>
                                <option value="external_events"
                                        {{ in_array('external_events', $external_factors_array) ? 'selected' : ''}}>
                                    external events
                                </option>
                                <option value="others"
                                        {{ in_array('others', $external_factors_array) ? 'selected' : ''}}>
                                    Others
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group w-100 d-none" id="influence_details{{ $issue->id }}">
                        <label class="col-sm-12 col-form-label" style="font-size:13px;">External factors details</label>
                        <div class="col-sm-12">
                            <input type="text" style="font-size:13px;"
                                   class="form-control" name="influence_details"
                                   id="influence_details_val{{ $rr }}"
                                   value="{{ $issue->external_factors_details}}" />
                        </div>
                    </div>
                    <div class="form-group d-inline-block w-100">
                        <label class="col-sm-12 col-form-label" style="font-size:13px;">7. Was the issue foreseeable?</label>
                        <div class="col-sm-12">
                            <input type="radio" value="1"
                                   name="issue_foreseeable"
                                   id="foreseeable_yes_{{ $issue->id}}"
                                   onchange="ifChecked('{{ $issue->id }}')"
                                    {{ $issue->issue_foreseeable === 1 ? 'checked' : '' }}>
                            <label style="font-size: 13px;" class="ml-1">Yes</label>
                            <input type="radio" value="-1"
                                   name="issue_foreseeable"
                                   id="foreseeable_no_{{ $issue->id}}"
                                   onchange="ifChecked('{{ $issue->id }}')"
                                    {{ $issue->issue_foreseeable === -1 ? 'checked' : '' }}>
                            <label style="font-size: 13px;" class="ml-1">No</label>
                        </div>
                    </div>
                    <div class="form-group d-none w-100" id="prevent_block_{{ $issue->id }}">
                        <label class="col-sm-12 col-form-label" style="font-size:13px;">Why wasn't it prevented?</label>
                        <div class="col-sm-12">
                            <input type="text" style="font-size:13px;"
                                   class="form-control" name="prevent_reason"
                                   id="prevent_reason{{ $rr }}" value="{{ $issue->prevent_reason }}" />
                        </div>
                    </div>
                    <div class="table-responsive ml-3 mt-4">
                        <table class="table table-bordered table-striped table-vcenter" id="root_cause_table{{ $issue->id }}">
                            <thead class="bg-white">
                            <tr>
                                <th class="text-center titleheading" style="width: 5%">S #</th>
                                <th class="text-center titleheading" style="width: 20%">Root Cause</th>
                                <th class="text-center titleheading" style="width: 20%">Corrective Action</th>
                                <th class="text-center titleheading" style="width: 20%">Department</th>
                                <th class="text-center titleheading" style="width: 15%">Responsibility</th>
                                <th class="text-center titleheading" style="width: 15%">Target Date</th>
                                <th style="width: 5%"></th>
                            </tr>
                            </thead>
                            @php
                                $rootcause = explode('$', $issue->rootcause);
                                $count_default_value = count($rootcause) - 1;
                                $cnt = count($rootcause) + 1;
                                $correctiveaction = explode('$', $issue->correctiveaction);
                                $responsibility = explode(',', $issue->responsibility);
                                $department = explode(',', $issue->department);
                                $target_date = explode(',', $issue->target_date);
                                $count = 1;
                            @endphp
                            <tbody>
                            <input type="hidden" id="cnt{{ $rr}}" value="{{ $cnt }}">
                            @foreach($rootcause as $item)
                                <tr id="tr{{ $issue->id }}{{ $count }}">
                                    <td class="td-class">{{ $count }}</td>
                                    <td class="td-class">
                                        <textarea class="form-control"
                                                  data-foo="r{{ $rr }}"
                                                  name="rootcause[]" id="rootcause{{ $count -1}}{{ $issue->id }}"
                                                  cols="30" rows="5"
                                                  style="font-size:13px;">{{ isset($rootcause[$count - 1]) ? $rootcause[$count - 1] : ''}}</textarea>
                                    </td>
                                    <td class="td-class">
                                        <textarea class="form-control"
                                                  data-foo="c{{ $rr }}"
                                                  name="correctiveaction[]"
                                                  id="correctiveaction{{ $count - 1 }}{{ $issue->id }}"
                                                  cols="30" rows="5"
                                                  style="font-size:13px;">{{ isset($correctiveaction[$count -1]) ? $correctiveaction[$count -1] : ''}}</textarea>
                                    </td>
                                    <td class="td-class">
                                        <select class="form-control" name="department[]"
                                                data-foo="d{{ $rr }}"
                                                id="department{{ $count - 1}}{{ $rr }}"
                                                style="font-size:13px;" required="true" placeholder="Choose a Department">
                                            <option value="0" selected="" disabled style="font-size:13px;">Choose a Department</option>
                                               <!-- Include active departments -->
                                                @if (count($departments) > 0)
                                                    @foreach ($departments as $dep)
                                                        <option value="{{ $dep->id }}" style="font-size:13px;"
                                                            {{ isset($department[$count - 1]) && intval($department[$count - 1]) === $dep->id ? 'selected' : '' }}>
                                                            {{ $dep->departmentname }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                                <!-- Include inactive department if already selected -->
                                                @if (isset($department[$count - 1]))
                                                    @php
                                                        $inactiveDept = App\Departments::where('id', $department[$count - 1])
                                                            ->where('is_active', false)
                                                            ->first();
                                                    @endphp
                                                    @if ($inactiveDept)
                                                        <option value="{{ $inactiveDept->id }}" style="font-size:13px;" selected>
                                                            {{ $inactiveDept->departmentname }}
                                                        </option>
                                                    @endif
                                                @endif
                                        </select>
                                    </td>
                                    <td class="td-class">
                                        <select class="form-control" name="responsibility[]"
                                                data-foo="resp{{ $rr }}" id="responsibility{{ $count - 1}}{{ $rr }}"
                                                style="font-size:13px;" required="true" placeholder="Choose Responsibility">
                                            <option value="0" selected="" disabled style="font-size:13px;">Choose Responsibility</option>
                                            @if(count($users)> 0 )
                                                @foreach($users as $item)
                                                    <option value="{{$item->email}}"
                                                            style="font-size:13px;" {{ isset($responsibility[$count - 1]) && strval($responsibility[$count - 1]) === $item->email ? 'selected' : '' }}>
                                                        {{ $item->name }}
                                                    </option><br>
                                                @endforeach
                                            @endif
                                        </select>
                                    </td>
                                    <td class="td-class">
                                        <input type="date" class="js-datepicker form-control" data-foo="tar{{ $rr }}" style="font-size:13px;" id="target_date{{ $count - 1}}{{ $rr }}" name="target_date[]" data-week-start="1" data-autoclose="true" data-today-highlight="true" placeholder="dd/mm/yy" data-date-format="dd/mm/yy" value="{{ isset($target_date[$count - 1]) ?  $target_date[$count - 1] : ''}}">
                                    </td>
                                    @if($count === 1)
                                        <td class="td-class">
                                            <button type="button" name="add" class="btn btn-sm btn-light" data-toggle="tooltip" title="Add" onclick="addRaw('{{ $issue->id }}')">
                                                <i class="fa fa-fw fa-plus"></i>
                                            </button>
                                        </td>
                                    @else
                                        <td class="text-center">
                                            <button type="button" name="remove" class="btn btn-sm btn-light" data-toggle="tooltip" title="Remove" onclick="remove_tr('{{ $issue->id }}', '{{ $count + 1 }}')">
                                                <i class="fa fa-fw remove-tr fa-times text-danger"></i>
                                            </button>
                                        </td>

                                    @endif

                                </tr>
                                @php
                                    $count++;
                                @endphp
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                    <div class="col-sm-12" style="padding-bottom:10px;">
                        @php
                            $m = 0;
                            $items_per_row = 5;
                        @endphp
                        <input type="hidden" id="suggestion_val" />
                        <input type="hidden" id="count_val{{ $rr }}" value="{{ $count_default_value }}">
                        @foreach($rootcorrectives as $rootcorrective)
                            <button type="button" class="btn btn-sm btn-outline-primary"
                                    style="margin-right:4px;margin-left:10px;margin-bottom:10px;"
                                    onclick="return passvalue('{{$rootcorrective->id}}', '{{ $issue->id }}');">
                                {{$rootcorrective->title}}
                            </button>
                            @php
                                $m++;
                            @endphp
                            @if ($m % $items_per_row == 0)<br>@endif
                        @endforeach

                    </div>
                    <div class="form-group" style="display:inline-block;width:49%">
                        <label class="col-sm-12 col-form-label" for="action_file" style="font-size:13px;">Attach issues related files(avoid '#' and '&' in file names)</label>
                        <div class="col-sm-12">
                            <input type="file" class="form-control" id="issue_file" style="font-size: 13px;" name="issue_file[]" multiple>
                        </div>
                    </div>
                    <div class="form-group" style="display:inline-block;width:49%">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-secondary btn-small" style="font-size:13px;" data-toggle="modal" data-id="{{ $issue->id }}" data-target="#viewModal4{{$issue->id}}"><strong>View Attachments(Issues)</strong> </button>
                            <div id="viewModal4{{$issue->id}}" class="modal fadebd-example-modal-lg modal-second" tabindex="-1" role="dialog" aria-hidden="false">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h3 class="modal-title">Attachments (issues)</h3>
                                        </div>
                                        <div class="modal-body">
                                            <table id="issueAttachmentTable" class="table table-responsive table-hover table-bordered table-striped p-5" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                                                <thead>
                                                <tr class="table-secondary">
                                                    <th style="width: 5%">S.No</th>
                                                    <th style="width:65%;">Attachments</th>
                                                    <th style="width: 30%">Actions</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @php
                                                    $isNoFile = true;
                                                    $no = 0;
                                                @endphp
                                                @if(count($issueFiles) > 0 )
                                                    @foreach($issueFiles as $value1)
                                                        @if($value1 !="NULL" && $value1 != "")
                                                            <tr>
                                                                <td style="font-size:10px; text-align: center">{{ $no + 1}}</td>
                                                                <td style="font-size:12px;">{{ $value1}}</td>
                                                                <td>
                                                                    <a style="font-size:10px;" class="btn btn-success btn-sm"
                                                                        href="{{asset("storage/issue_files/".$value1)}}"
                                                                        download="{{ $value1 }}">Download</a>
                                                                    @if(Gate::allows('delete-corrective') || Gate::allows('delete-all') || in_array(Auth::user()->email,$issue_reviewers) || $issue->postedby==Auth::user()->employeeid)
                                                                    <button class="btn btn-secondary btn-sm remove-issue-attachment" data-filename="{{ $value1 }}" style="font-size:10px;" title="Delete">Remove</button>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            @php
                                                                $isNoFile = false;
                                                                $no += 1;
                                                            @endphp
                                                        @endif
                                                    @endforeach
                                                @endif
                                                @if($isNoFile)
                                                    <tr>
                                                        <td colspan="3" class="text-center" style="color: grey">No Files</td>
                                                    </tr>
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" style="display:inline-block;width:49%">
                        <label class="col-sm-12 col-form-label" for="action_file" style="font-size:13px;">Attach action related files(avoid '#' and '&' in file names)</label>
                        <div class="col-sm-12">
                            <input type="file" class="form-control" id="action_file" style="font-size: 13px;" name="action_file[]" multiple>
                        </div>
                    </div>

                    <div class="form-group" style="display:inline-block;width:49%">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-secondary btn-small" style="font-size:13px;" data-toggle="modal" data-id="{{ $issue->id }}" data-target="#viewModal5{{$issue->id}}"><strong>View Attachments(Actions)</strong> </button>
                            <div id="viewModal5{{$issue->id}}" class="modal fadebd-example-modal-lg modal-second" tabindex="-1" role="dialog" aria-hidden="false">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h3 class="modal-title">Attachments (action)</h3>
                                        </div>
                                        <div class="modal-body">
                                            <table id="actionAttachmentTable" class="table table-responsive table-hover table-bordered table-striped p-5" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                                            <thead>
                                                <tr class="table-secondary">
                                                    <th style="width: 5%">S.No</th>
                                                    <th style="width: 65%;">Attachments</th>
                                                    <th style="width: 30%">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $isNoFile = true;
                                                    $no = 0;
                                                @endphp
                                                @if(count($actionFiles) > 0 )
                                                    @foreach($actionFiles as $value2)
                                                        @if($value2 !="NULL" && $value2 != "")
                                                            <tr>
                                                                <td style="font-size:10px; text-align: center">{{ $no + 1}}</td>
                                                                <td style="font-size:12px;">{{ $value2 }}</td>
                                                                <td><a style="font-size:10px;" class="btn btn-success"
                                                                        href="{{asset("storage/issue_files/".$value2)}}"
                                                                        download="{{ $value2 }}">Download</a>
                                                                    @if(Gate::allows('delete-corrective') || Gate::allows('delete-all') || in_array(Auth::user()->email,$issue_reviewers) || $issue->postedby==Auth::user()->employeeid)
                                                                    <button class="btn btn-secondary btn-sm remove-action-attachment" data-filename="{{ $value2 }}" style="font-size:10px;" title="Delete">Remove</button>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            @php
                                                                $isNoFile = false;
                                                                $no += 1;
                                                            @endphp
                                                        @endif
                                                    @endforeach
                                                @endif
                                                @if($isNoFile)
                                                    <tr>
                                                        <td colspan="3" class="text-center" style="color: grey">No Files</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="attachmentsDiv"></div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                    <button type="button" class="btn btn-primary btn-submit-investigation"
                            data-id="{{ $issue->id }}"
                            style="font-size:12px">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let counter = Number('{{ $cnt }}');
     function passvalue(id, issue_id) {
        $('#suggestion_val').val(id);
        let count_val = $('#count_val' + issue_id).val();

        $.ajax({
            data: {
                "data_id": id
                , _token: CSRF_TOKEN
            }
            , url: "{{ route('corrective.displayrootcorrectiveajax')}}"
            , type: "POST"
            , success: function(response) {
                if (response.status == 200) {
                    var rootlist = response.rootdata;
                    var roothtmldatas = '';

                    if (rootlist.length > 0) {
                        roothtmldatas = rootlist;
                    } else {
                        roothtmldatas = null;
                    }

                    var correctivelist = response.correctdata;
                    var correcthtmldatas = '';
                    const removespace = new RegExp(/\s{3,}/, 'ig');
                    const correctivelistdisplay = correctivelist.replace(removespace, '\n');

                    if (correctivelist.length > 0) {
                        correcthtmldatas = correctivelistdisplay;
                    } else {
                        correcthtmldatas = null;
                    }

                    $('#rootcause' + (count_val) + issue_id).val(roothtmldatas);
                    $('#correctiveaction' + (count_val) + issue_id).val(correcthtmldatas);

                }

            }

        });
    }
    let departments_text = "{!! $departments_text !!}";
    let users_text = "{!! $users_text !!}";

    function addRaw(id, cnt) {
        $('#count_val' + id).val(counter - 1);
        let text_to_append_to_table = `<tr id="tr${id}${counter}">
            <td class="td-class">${ counter++ }</td>
            <td><textarea class="form-control" data-foo="r${id}" name="rootcause[]" id="rootcause${counter - 2}${id}" cols="30" rows="5" style="font-size:13px;"></textarea></td>
            <td><textarea class="form-control" data-foo="c${id}" name="correctiveaction[]" id="correctiveaction${counter - 2}${id}" cols="30" rows="5" style="font-size:13px;"></textarea></td>
            <td>
                <select class="form-control department" data-foo="d${id}" name="department[]" id="department${counter - 2}${id}" style="font-size:13px;" required="true" placeholder="Choose a Department">
                    <option value="0" selected="" disabled style="font-size:13px;">Choose a Department</option>
                    ` + departments_text +
            `
                </select>
            </td>
            <td class="td-class">
                <select class="form-control" name="responsibility[]" data-foo="resp${id}" id="responsibility${counter - 2}${id}" style="font-size:13px;" required="true" placeholder="Choose Responsibility">
                    <option value="0" selected="" disabled style="font-size:13px;">Choose Responsibility</option>` +
            users_text +
            `
                </select>
            </td>
            <td>
                <input type="date" class="js-datepicker form-control" data-foo="tar${id}" style="font-size:13px;" name="target_date[]" id="target_date${counter - 2}${id}" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy">
            </td>

            <td class="text-center"><button type="button" name="remove" class="btn btn-sm btn-light" data-toggle="tooltip" title="Remove" onclick="remove_tr(${id},${counter})">
                    <i class="fa fa-fw remove-tr fa-times text-danger"></i></button></td>
        </tr>`;

        $("#root_cause_table" + id).append(text_to_append_to_table);
    }

    function remove_tr(id, index_param) {
        $('#count_val' + id).val(index_param - 3);
        $('#tr' + id + (index_param - 1)).remove();
        counter -= 1;
    }
</script>
