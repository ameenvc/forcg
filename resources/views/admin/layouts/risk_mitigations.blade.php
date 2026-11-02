@if(count($mitigations))
<p>mitigations</p>
<table class="table table-bordered table-striped table-vcenter" style="margin-top:-20px;">
    <tr style="background-color:white">
        <th style="font-size:12px">owner</th>
        <th style="font-size:12px">edit</th>
    </tr>
    {{-- @php
    $j = 0;
    $owners = [];

    foreach($mitigations as $m) {
    $owners[$j] = $m->user ? $m->user->email : '';
    $j++;
    }
    $owners = array_unique(array_filter($owners));
    $owners = implode(',', $owners);
    @endphp --}}
    <tr style="background-color:white">
        <td><span class="label label-success" style="font-size:11px">{{ $user->email }}</span></td>
        <td><span class="label label-success" style="font-size:11px">
                <select name="mitigations" id="mitigations" class="form-control @error('mitigations') is-invalid  @enderror" style="font-size: 13px;" required="Please">
                    <option value="0" selected="selected"> Select a user...</option>
                    @foreach($users as $u)
                    <option value="{{$u->id}}">{{$u->name}}</option>
                    @endforeach
                </select>
                @error('mitigations')
                <div class="invalid-feedback">{{ $message}}</div>
                @enderror
            </span></td>
    </tr>
</table>
@endif
