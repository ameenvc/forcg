<label class="ml-3 mb-3">Attachments</label>
<div class="ml-3 mb-4" style="min-height: 2rem">
    <label>・Attachments of Issues</label>
    
    <div class="d-flex" style="flex-flow: wrap">
        @if(count($issuedocs) > 0)
            @foreach($issuedocs as $issue_item)
                @if($issue_item && $issue_item != 'NULL')
                    @if(File::exists(public_path('/storage/issue_files/'. $issue_item)))
                        <div class="btn-group" role="group" aria-label="Issue File Actions">
                            <a href="{{ asset('storage/issue_files/' . $issue_item) }}"
                               download="{{ $issue_item }}"
                               class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Download Issue File"
                               style="font-size: 11px; background: #176044; border-color: #176044; margin-bottom: 1rem;">
                                {{ $issue_item }}
                            </a>
                            @if(Gate::allows('delete-corrective') || Gate::allows('delete-all') || in_array(Auth::user()->email,$issue_reviewers) || $corrective->postedby==Auth::user()->employeeid)
                            <button type="button"
                                    class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete Issue File"
                                    style="font-size: 11px; margin-bottom: 1rem;"
                                    onclick="deleteIssueFile('{{ $issue_item }}')">
                                <i class="fa fa-times"></i>
                            </button>
                            @endif
                        </div>
                        &nbsp;&nbsp;
                    @else
                        <div class="btn-group" role="group" aria-label="Issue File Actions">
                            <a href="#" class="btn btn-success btn-sm mr-3" data-toggle="tooltip" data-placement="top" title="Download Issue File"
                            style="font-size: 11px; background: #176044; border-color: #176044; margin-bottom: 1rem;">
                                {{ $issue_item }}
                            </a>
                            @if(Gate::allows('delete-corrective') || Gate::allows('delete-all') || in_array(Auth::user()->email,$issue_reviewers) || $corrective->postedby==Auth::user()->employeeid)
                            <button type="button"
                                    class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete Issue File"
                                    style="font-size: 11px; margin-bottom: 1rem;"
                                    onclick="deleteIssueFile('{{ $issue_item }}')">
                                <i class="fa fa-times"></i>
                            </button>
                            @endif
                        </div>
                        &nbsp;&nbsp;
                    @endif
                @else
                    <label class="text-secondary fw-500 ml-2">No issue attachments added</label>
                @endif
            @endforeach
        @else
            <label class="text-secondary fw-500 ml-2">No issue attachments added</label>
        @endif
    </div>
</div>
<div class="ml-3" style="min-height: 2rem">
    <label>・Attachments of Actions</label>
    <div class="d-flex" style="flex-flow: wrap">
        @if(count($actiondocs) > 0)
            @foreach($actiondocs as $action_item)
                @if($action_item && $action_item != 'NULL')
                    @if(File::exists(public_path('/storage/issue_files/'. $action_item)))
                    <div class="btn-group" role="group" aria-label="Action File Actions">
                        
                        <a href="{{asset("storage/issue_files/". $action_item)}}"
                           download="{{ $action_item }}" data-toggle="tooltip" data-placement="top" title="Download Action File"
                           class="btn btn-success btn-sm"
                           style="font-size: 11px; background: #176044; border-color: #176044; margin-bottom: 1rem;">
                            {{ $action_item }}
                        </a>
                        @if(Gate::allows('delete-corrective') || Gate::allows('delete-all') || in_array(Auth::user()->email,$issue_reviewers) || $corrective->postedby==Auth::user()->employeeid)
                        <button type="button"
                                class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete Action File"
                                style="font-size: 11px; margin-bottom: 1rem;"
                                onclick="deleteActionFile('{{ $action_item }}')">
                            <i class="fa fa-times"></i>
                        </button>
                        @endif
                    </div>&nbsp;&nbsp;
                    @else
                    <div class="btn-group" role="group" aria-label="Issue File Actions">

                        <a href="#" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Download Action File"
                           style="font-size: 11px; background: #176044; border-color: #176044; margin-bottom: 1rem;">
                            {{ $action_item }}
                        </a>
                        @if(Gate::allows('delete-corrective') || Gate::allows('delete-all') || in_array(Auth::user()->email,$issue_reviewers) || $corrective->postedby==Auth::user()->employeeid)
                        <button type="button"
                                class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete Action File"
                                style="font-size: 11px; margin-bottom: 1rem;"
                                onclick="deleteActionFile('{{ $action_item }}')">
                            <i class="fa fa-times"></i>
                        </button>
                        @endif
                    </div>&nbsp;&nbsp;
                    @endif
                @else
                    <label class="text-secondary fw-500 ml-2">No action attachments added</label>
                @endif
            @endforeach
        @else
            <label class="text-secondary fw-500 ml-2">No action attachments added</label>
        @endif
    </div>
</div>