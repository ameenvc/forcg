@if($auditor)
<p>auditors</p>
<table class="table table-bordered table-striped table-vcenter" style="margin-top:-20px;">
    <tr style="background-color:white">
        <th style="font-size:12px">id</th>
        <th style="font-size:12px">name</th>
        <th style="font-size:12px">email</th>
        <th style="font-size:12px">certificate</th>
        <th style="font-size:12px">experience	</th>
        <th style="font-size:12px">edit</th>
    </tr>
    <tr style="background-color:white">
        <td><span class="label label-success" style="font-size:12px">{{ $auditor->id}}</span></td>
        <td><span class="label label-success" style="font-size:11px">{{ $auditor->auditorsname }}</span></td>
        <td><span class="label label-success" style="font-size:11px">{{ $auditor->email }}</span></td>
        <td><span class="label label-success" style="font-size:11px">{{ $auditor->certificate }}</span></td>
        <td><span class="label label-success" style="font-size:11px">{{ $auditor->experience}}</span></td>
        <td><span class="label label-success" style="font-size:11px">
            <button type="button" class="btn btn-primary auditorEditBtn" data-toggle="modal" style="font-size:12px" data-target="#auditorModal" data-id="{{ $auditor->id }}">
                Edit
            </button>
            </span></td>
    </tr>
</table>
@endif
<div class="modal fade" id="auditorModal" tabindex="-1" role="dialog" aria-labelledby="auditorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="auditorModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success" id="successAuditorBtn" style="display:none">
                    successfully saved
                </div>
                <div class="alert alert-danger" id="dangerAuditorBtn" style="display:none">
                </div>
                <div class="form-group">
                    @if($auditor)
                    <select id="auditor" name="auditor" class="form-control @error('auditor') is-invalid  @enderror" style="font-size: 13px;" required="Please">
                        <option value="0" selected="selected"> Select a user...</option>
                        @foreach($auditor_users as $u)
                        <option value="{{$u->id}},{{ $auditor->id}}">{{$u->name}}</option>
                        @endforeach
                    </select>
                    @error('auditor')
                    <div class="invalid-feedback">{{ $message}}</div>
                    @enderror
                    @endif
                </div>
                <div class="form-group">
                    <textarea cols="20" name="certificate" id="certificate" rows="5" class="form-control" placeholder="Certificate"></textarea>
                    {{-- <input type="text" class="form-control" placeholder="certificate" name="certificate" id="certificate"> --}}
                    @error('certificate')
                    <div class="invalid-feedback">{{ $message}}</div>
                    @enderror
                </div>
                <div class="form-group">
                    {{-- <input type="text" class="form-control" placeholder="experience" name="experience" id="experience"> --}}
                    <textarea cols="20" name="experience" id="experience" rows="5" class="form-control" placeholder="Experience"></textarea>
                    @error('experience')
                    <div class="invalid-feedback">{{ $message}}</div>
                    @enderror
                </div>
                <input type="hidden" name="auditorId" id="auditorId">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                <button type="button" class="btn btn-primary" style="font-size:12px" onclick="saveAuditorChanges()">Save changes</button>
            </div>

        </div>
    </div>
</div>
