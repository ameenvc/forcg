@if(count($deps))
<p>Departments</p>
<table class="table table-bordered table-striped table-vcenter" style="margin-top:-20px;">
    <tr style="background-color:white">
        <th style="font-size:12px">id</th>
        <th style="font-size:12px">departmentname</th>
        <th style="font-size:12px">hod</th>
        <th style="font-size:12px">hodname</th>
        <th style="font-size:12px">hodposition</th>
        <th style="font-size:12px">icon</th>
        <th style="font-size:12px">code</th>
        <th style="font-size:12px">edit</th>
    </tr>
    @php $i=1; @endphp
    @foreach($deps as $dep)
    <tr style="background-color:white">
        <td><span class="label label-success" style="font-size:12px">{{ $dep->id}}</span></td>
        <td><span class="label label-success" style="font-size:11px">{{ $dep->departmentname }}</span></td>
        <td><span class="label label-success" style="font-size:11px">{{ $dep->hod }}</span></td>
        <td><span class="label label-success" style="font-size:11px">{{ $dep->hodname }}</span></td>
        <td><span class="label label-success" style="font-size:11px">{{ $dep->hodposition }}</span></td>
        <td><span class="label label-success" style="font-size:11px">{{ $dep->icon }}</span></td>
        <td><span class="label label-success" style="font-size:11px">{{ $dep->code }}</span></td>
        <td><span class="label label-success" style="font-size:11px">
                <button type="button" class="btn btn-primary test" data-toggle="modal" style="font-size:12px" data-target="#exampleModal" data-id="{{ $dep->id }}">
                    Edit
                </button>
            </span></td>
    </tr>
    @php $i++; @endphp

    @endforeach
</table>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success" id="successBtn" style="display:none">
                    successfully saved
                </div>
                <div class="alert alert-danger" id="dangerBtn" style="display:none">
                </div>
                <div class="form-group">
                    <select id="dept" name="dept" class="form-control @error('dept') is-invalid  @enderror" style="font-size: 13px;" required>
                        <option value="0" selected="selected"> Select a user...</option>
                        @foreach($users as $u)
                        <option value="{{$u->id}}">{{$u->name}}</option>
                        @endforeach
                    </select>
                    @error('dept')
                    <div class="invalid-feedback">{{ $message}}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="hodposition" name="hodposition" id="hodposition">
                    @error('hodposition')
                    <div class="invalid-feedback">{{ $message}}</div>
                    @enderror
                </div>
                <input type="hidden" name="deptId" id="deptId">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                <button type="button" class="btn btn-primary" style="font-size:12px" onclick="saveChanges()">Save changes</button>
            </div>

        </div>
    </div>
</div>
@endif
