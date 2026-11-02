<style>
    .table thead th {
        vertical-align: middle;
    }
</style>
<div id="viewModal{{$authority->id}}" class="modal fadebd-example-modal-lg scrollable" tabindex="-1" role="dialog" aria-hidden="false" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Detailed View</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered mb-0">
                    <tr>
                        <th style="width:195px;font-size:15px;">Name</th>
                        <td style="font-size:15px;">{{$authority->name}}</td>
                    </tr>
                    <tr>
                        <th style="width:195px;font-size:15px;">Status</th>
                        <td style="font-size:15px;">
                            @if($authority->status == 'Approved')
                                <span class="status status-success">{{$authority->status}}</span>
                            @elseif($authority->status == 'Pending Review')
                                <span class="status status-warning">{{$authority->status}}</span>
                            @else
                                <span class="status status-red">{{$authority->status}}</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th style="width:195px;font-size:15px;">Created On / By</th>
                        <td style="font-size:15px;">
                            {{$authority->created_at}}
                            @if($authority->creator && $authority->creator->name)
                                / {{$authority->creator->name}}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th style="width:195px;font-size:15px;">Last Modified On / By</th>
                        @if($authority->modified_at)
                        <td style="font-size:15px;">
                                {{$authority->modified_at}}
                            @if($authority->modifier && $authority->modifier->name)
                                / {{$authority->modifier->name}}
                            @endif
                        </td>
                        @else
                        <td style="font-size:15px;">--</td>
                        @endif
                    </tr>
                    @if($authority->updated_name)
                    <tr>
                        <th style="width:195px;font-size:15px;">Initial Name / Updated Name</th>
                        <td style="font-size:15px;">{{ $authority->initial_name  }} / {{ $authority->updated_name  }}</td>
                    </tr>
                    @endif
                </table>
                <div class="p-3 bg-light rounded border">
                    <h6 class="fw-bold mb-2" style="font-size:15px;">Legal Documents</h6>
                    <table class="table table-sm table-hover mb-0 table-bordered">
                        <thead>
                            <tr>
                                <th class="titleheading text-center" style="width: 5%;font-size:13px;" data-orderable="false">S.No.</th>
                                <th class="titleheading text-center" style="width: 30%;font-size:13px;" data-orderable="false">Document Name</th>
                                <th class="titleheading text-center" style="width: 15%;font-size:13px;" data-orderable="false">Status</th>
                                <th class="titleheading text-center" style="width: 25%;font-size:13px;" data-orderable="false">Created On / By</th>
                                <th class="titleheading text-center" style="width: 25%;font-size:13px;" data-orderable="false">Last Modified On / By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($authority->complianceLegalDocuments->isEmpty())
                                <tr>
                                    <td colspan="5" class="text-center text-danger" style="font-size:13px;">No Legal Documents found.</td>
                                </tr>
                            @else
                                @foreach($authority->complianceLegalDocuments as $doc)
                                    <tr class="text-center" style="font-size:13px;">
                                        <td class="text-center" style="padding-top: 13px !important;">{{ $loop->iteration }}</td>
                                        <td class="text-center" style="padding-top: 13px !important;">{{ $doc->name }}</td>
                                        <td class="text-center" style="padding-top: 13px !important;">
                                            @if($doc->status == 'Approved')
                                                <span class="status status-success">{{$doc->status}}</span>
                                            @elseif($doc->status == 'Pending Review')
                                                <span class="status status-warning">{{$doc->status}}</span>
                                            @else
                                                <span class="status status-red">{{$doc->status}}</span>
                                            @endif
                                        </td>
                                        <td class="text-center" style="padding-top: 13px !important;">
                                            {{$doc->created_at}}
                                            @if($doc->creator && $doc->creator->name)
                                                / {{$doc->creator->name}}
                                            @endif
                                        </td>
                                        <td class="text-center" style="padding-top: 13px !important;">
                                            @if($doc->modified_at)
                                                {{$doc->modified_at}}
                                                @if($doc->modifier && $doc->modifier->name)
                                                    / {{$doc->modifier->name}}
                                                @endif
                                            @else
                                                --
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>