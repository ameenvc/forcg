<div class="modal fade racm-popup" id="racmModal"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ isset($racm) ? 'Edit RACM' : 'Add New RACM' }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Risk factor title -->
                <div class="form-group" id="doc_name_div">
                    <label for="risk_factor_title">Risk factor title<span style="color:red;">*</span></label><br>
                    @php
                        $riskFactorNames = config('constants.risk_management.racm.risk_factor_name');
                    @endphp
                    @foreach(array_keys($riskFactorNames) as $key => $item)
                        <div class="form-check form-check-inline">
                            <button type="button"
                                    class="btn btn-sm btn-outline-primary auto-fill_risk-factor-name"
                                    id="btn-{{ $key }}"
                                    style="margin-right:4px;margin-bottom:10px;font-size:11px;"
                                    data-name="{{ $item }}"
                                    data-description="{{ $riskFactorNames[$item] }}">{{ $item }}</button>
                        </div>
                    @endforeach
                    <input type="text" class="form-control" id="risk_factor_title"
                           style="font-size:13px;"
                           value="{{ isset($racm) ? $racm->risk_factor_title : '' }}"
                           name="risk_factor_title">
                    <div class="label-invalid error-risk_factor_title"></div>
                </div>
                <!-- Process name -->
                <div class="form-group" id="doc_owner_div">
                    <label for="process_id">Process name<span style="color:red;">*</span></label>
                    <select class="js-select2 form-control" id="process_id" required
                            name="process_id" style="font-size: 13px;width:100%">
                        <option value="">Choose Process name</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}"
                                    {{ isset($racm) && $racm->process_id == $department->id ? 'selected' : '' }}>
                                {{ $department->departmentname }}
                            </option>
                        @endforeach
                    </select>
                    <div class="label-invalid error-process_id"></div>
                </div>
                <!-- Risk factor description -->
                <div class="form-group" id="docnumber_div">
                    <label class="col-form-label" for="risk_factor_description">Risk factor description<span style="color:red;">*</span></label>
                    <textarea name="risk_factor_description" id="risk_factor_description"
                              style="font-size:13px;"
                              rows="2" class="form-control">{{ isset($racm) ? $racm->risk_factor_description : '' }}</textarea>
                    <div class="label-invalid error-risk_factor_description"></div>
                </div>
                <!-- High -->
                <div class="form-group" id="doc_name_div">
                    <label for="high">High</label>
                    <input type="text" class="form-control"
                           style="font-size:13px;"
                           value="{{ isset($racm) ? $racm->high : '' }}"
                           id="high" name="high">
                    <div class="label-invalid error-high"></div>
                </div>
                <!-- Moderate -->
                <div class="form-group" id="doc_name_div">
                    <label for="moderate">Moderate</label>
                    <input type="text" class="form-control"
                           style="font-size:13px;"
                           value="{{ isset($racm) ? $racm->moderate : '' }}"
                           id="moderate" name="moderate">
                    <div class="label-invalid error-moderate"></div>
                </div>
                <!-- Low -->
                <div class="form-group" id="doc_name_div">
                    <label for="low">Low</label>
                    <input type="text" class="form-control"
                           style="font-size:13px;"
                           value="{{ isset($racm) ? $racm->low : '' }}"
                           id="low" name="low">
                    <div class="label-invalid error-low"></div>
                </div>
                <!-- Unit -->
                <div class="form-group" id="doc_name_div">
                    <label for="unit">Unit</label>
                    <select class="form-control" id="unit" name="unit" style="font-size:13px;width:100%;">
                        <option value="">Choose Unit</option>
                        <option value="2"
                                {{ isset($racm) && $racm->unit == 2 ? 'selected' : '' }}>%</option>
                        <option value="3"
                                {{ isset($racm) && $racm->unit == 3 ? 'selected' : '' }}>No sign</option>
                        <option value="4"
                                {{ isset($racm) && $racm->unit == 4 ? 'selected' : '' }}>Other</option>
                    </select>
                    <input type="text" class="form-control {{ isset($racm) && $racm->unit == 4 ? '' : 'd-none' }} mt-2"
                           placeholder="Please input the unit..."
                           value="{{ isset($racm) ? $racm->unit_other : '' }}"
                           id="unit_other" name="unit_other">
                    <div class="label-invalid error-unit"></div>
                    <div class="label-invalid error-unit_other"></div>
                </div>
                <!-- Reviewers -->
                {{-- <div class="form-group" id="doc_name_div">
                    <label for="reviewer_id">Reviewers <span style="color:red;">*</span></label>
                    <select class="js-select2 form-control" id="reviewer_id" required
                            name="reviewer_id" style="font-size: 13px;width:100%">
                        <option value="">Choose Reviewers name</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}"
                                    {{ isset($racm) && $racm->reviewer_id == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="label-invalid error-reviewer_id"></div>
                </div> --}}
                <!-- Approvers -->
                {{-- <div class="form-group" id="doc_name_div">
                    <label for="reviewer_id">Approvers <span style="color:red;">*</span></label>
                    <select class="js-select2 form-control" id="approver_id" required
                            name="approver_id" style="font-size: 13px;width:100%">
                        <option value="">Choose Approvers name</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}"
                                    {{ isset($racm) && $racm->approver_id == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="label-invalid error-approver_id"></div>
                </div> --}}
            </div>
            <div class="modal-footer" id="btn_div">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button"
                        data-route="{{ isset($racm) ? '/user/riskmgmt/racm/'.$racm->id.'/update' : '/user/riskmgmt/racm' }}"
                        id="save_racm" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>