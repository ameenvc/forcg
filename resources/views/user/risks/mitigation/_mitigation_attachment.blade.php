@once
<link rel="stylesheet" href="{{ asset('css/risk-tables.css') }}?v={{ filemtime(public_path('css/risk-tables.css')) }}">
@endonce
@if(isset($mitigation))
    <div id="viewModalAttachment{{ $mitigation->id }}"
         class="modal fade bd-example-modal-lg modal-second" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="display:flex; justify-content:space-between; align-items:center;">
                    <h3 class="modal-title">Attachments</h3>
                    <button type="button" class="btn-block-option btn-close" data-dismiss="modal" aria-label="Close" style="padding:0 10px;">
                        <i class="si si-close" style="font-size:20px;"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="risk-module-table table table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                        <thead>
                        <tr class="table-secondary">
                            <th>S.No</th>
                            <th>Attachments</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($mitigation->attachments)
                            @foreach($mitigation->attachments as $attachment)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $attachment->attachment }}</td>
                                    <td class="text-center">
                                        <a href="{{ asset("storage/mitigation/" . $attachment->attachment)}}"
                                           download="{{ $attachment->attachment }}"
                                           class="btn btn-sm btn-alt-dark"
                                           data-toggle="tooltip" style="font-size:11px;"
                                           title="Click here to download"> <i class="fa fa-download"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3">File not found</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif
