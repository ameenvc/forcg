<!-- Not fully implemented Modal -->
<div class="modal not-fully-implemented-popup"
     id="not-fully-implemented-{{ $mitigation->id }}" tabindex="-1" role="dialog"
     aria-hidden="true">
    <div class="modal-dialog m-dialog modal-xl" role="document">
        <div class="modal-content" style="text-align: left">
            <div class="modal-body m-body p-4">
                <div class="form-group" style="display:inline-block;width:99%;">
                    <label style="color: red">
                        Please update how much percentage of implementation of this mitigation action completed
                    </label>
                </div>
                <div class="form-group" style="display:inline-block;width:25%;">
                    <label class="col-sm-12 col-form-label"
                           style="font-size: 13px;" for="target_process">Update the percentage
                    </label>
                    <div class="col-sm-12">
                        <select class="form-control" name="target_process" id="target_process" style="font-size: 13px;">
                            <option value="">Choose the progress</option>
                            @foreach(config('constants.risk_management.mitigation.progresses') as $key => $progress)
                                @if(!in_array($key, ['not_started', 'completed']))
                                    <option value="{{ $key }}">{{ $progress }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <!-- Save button -->
                <div class="mt-5 d-flex justify-content-end">
                    <button type="button" class="btn btn-lg btn-outline-primary mx-3 px-5" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-lg btn-primary px-5 btn-update-not-fully-implemented-mitigation"
                            data-route="{{ route('mitigations.change.progress', $mitigation->id) }}"
                            data-redirect-route="{{ route('mitigations.index') }}">
                        Update
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

