<div id="changeInvestigationModal{{$issue->id}}"
     class="modal fadebd-example-modal-lg change-investigation-modal"
     aria-labelledby="changeInvestigationModalLabel" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changeInvestigationModalLabel">Change investigation team</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="change_team_form{{ $issue->id }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-body m-body p-4">
                    <div class="alert alert-success d-none w-100" id="success_change_investigation{{ $issue->id}}">
                        Data added successfully
                    </div>

                    <ul class="alert alert-danger d-none w-100" id="error_change_investigation{{ $issue->id}}" style="font-size:12px;">
                        Internal server error, we are trying to fix them soon
                    </ul>

                    <ul class="alert alert-warning d-none w-100" id="validation_error_change_investigation{{ $issue->id}}"></ul>

                    <div class="form-group d-inline-block" style="width:100%;">
                        <label class="col-sm-12 col-form-label" for="lead" style="font-size:13px;" id="lead_append{{ $rr }}">Team leader</label>
                        <div class="col-sm-12">
                            <select class="js-select2 form-control form-select w-100" name="lead"
                                    data-multiple="false"
                                    id="lead{{ $rr }}" style="font-size:13px;width:100%;" placeholder="Choose Team leader">
                                <option value="0" selected="" disabled style="font-size:13px;">Choose Team Leader</option>
                                @if(count($users)> 0 )
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}" style="font-size:13px;" {{ intval($issue->team_lead_id) == $user->id ? 'selected' : ''}}>{{ $user->name }}</option><br>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>


                    <div class="form-group d-inline-block w-100">
                        @php
                            $corrective_investigators = $issue->correctiveInvestigators->pluck('user_id')->toArray();
                        @endphp
                        <label class="col-sm-12 col-form-label" style="font-size:13px;" id="team_append{{ $rr }}">Team members</label>
                        <div class="col-sm-12">
                            <select class="form-select js-select2" name="team[]" id="team{{ $rr }}" style="width: 100%;" data-multiple="true" multiple>
                                @if(count($users)> 0 )
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}" style="font-size:13px;" {{ in_array($user->id, $corrective_investigators) ? 'selected' : ''}}>{{ $user->name }}</option><br>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                    <button type="button" class="btn btn-primary btn-change-investigation"
                            data-id="{{ $issue->id }}"
                            style="font-size:12px">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>