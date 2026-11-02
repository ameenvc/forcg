<style>
    .table thead th {
        vertical-align: middle;
    }
    .titleheading {
        font-size: 12px !important;
        text-transform: capitalize !important;
        position: sticky !important;
        position: relative !important;
        font-weight:bold !important;
    }
    .scrollable {
        height: 100%;
        overflow-x: scroll;
        overflow-y: scroll;
    }
</style>
<div id="viewHistory" class="modal fadebd-example-modal-lg scrollable" tabindex="-1" role="dialog" aria-hidden="false" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">History</h3>
            </div>
            <div class="table-responsive">
                <div class="p-3 bg-light rounded border">
                    <table class="table table-sm table-hover table-bordered">
                        <thead>
                            <tr>
                                <th class="titleheading text-center" style="width: 5%;font-size:12px;;" data-orderable="false">Version</th>
                                <th class="titleheading text-center" style="width: 30%;font-size:12px;;" data-orderable="false">Previous Dispaly Label</th>
                                <th class="titleheading text-center" style="width: 30%;font-size:12px;;" data-orderable="false">Updated Display Label</th>
                                <th class="titleheading text-center" style="width: 20%;font-size:12px;;" data-orderable="false">Modified By</th>
                                <th class="titleheading text-center" style="width: 15%;font-size:12px;;" data-orderable="false">Modified On</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($histories->isEmpty())
                                <tr>
                                    <td colspan="5" class="text-center text-danger" style="font-size:12px;;">No History found.</td>
                                </tr>
                            @else
                                @foreach($histories as $history)
                                    <tr class="text-center" style="font-size:12px;;">
                                        <td class="text-center" style="padding-top: 13px !important;">{{ $loop->iteration }}</td>
                                        <td class="text-center" style="padding-top: 13px !important;">{{ $history->previous_name }}</td>
                                        <td class="text-center" style="padding-top: 13px !important;">{{ $history->updated_name }}</td>
                                        <td class="text-center" style="padding-top: 13px !important;">
                                            @if($history->modifierDetails && $history->modifierDetails->name)
                                                {{$history->modifierDetails->name}}
                                            @elseif($history->modifier=='System')
                                                {{$history->modifier}}
                                            @else
                                                --
                                            @endif
                                        </td>
                                        <td class="text-center" style="padding-top: 13px !important;">{{ $history->modified_at }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>