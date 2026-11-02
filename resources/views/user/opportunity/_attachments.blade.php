<label class="mb-3">Attachments</label>
<div class="mb-4" style="min-height: 2rem">
    <label>・Attachments</label>
    <div class="d-flex" style="flex-flow: wrap">
        @if(count($files) > 0)
            @foreach($files as $file)
                @if(File::exists(public_path('/storage/opportunity/'. $file)))
                    <div class="btn-group" role="group" aria-label="File Actions">
                        <a href="{{ asset('storage/opportunity/' . $file) }}"
                            download="{{ $file }}"
                            class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Download Attachment"
                            style="font-size: 11px; background: #176044; border-color: #176044; margin-bottom: 1rem;">
                            {{ $file }}
                        </a>
                        {{-- @if(Gate::allows('delete-corrective') || Gate::allows('delete-all') || in_array(Auth::user()->email,$issue_reviewers) || $corrective->postedby==Auth::user()->employeeid)
                        <button type="button"
                                class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete Issue File"
                                style="font-size: 11px; margin-bottom: 1rem;"
                                onclick="deleteIssueFile('{{ $file }}')">
                            <i class="fa fa-times"></i>
                        </button>
                        @endif --}}
                    </div>
                    &nbsp;&nbsp;
                @else
                    <div class="btn-group" role="group" aria-label="File Actions">
                        <a href="#" class="btn btn-success btn-sm mr-3" data-toggle="tooltip" data-placement="top" title="Download Attachment"
                        style="font-size: 11px; background: #176044; border-color: #176044; margin-bottom: 1rem;">
                            {{ $file }}
                        </a>
                        {{-- @if(Gate::allows('delete-corrective') || Gate::allows('delete-all') || in_array(Auth::user()->email,$issue_reviewers) || $corrective->postedby==Auth::user()->employeeid)
                        <button type="button"
                                class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete Issue File"
                                style="font-size: 11px; margin-bottom: 1rem;"
                                onclick="deleteIssueFile('{{ $issue_item }}')">
                            <i class="fa fa-times"></i>
                        </button>
                        @endif --}}
                    </div>
                    &nbsp;&nbsp;
                @endif
            @endforeach
        @else
            <label class="text-secondary fw-500 ml-2">No attachments added</label>
        @endif
    </div>
</div>
