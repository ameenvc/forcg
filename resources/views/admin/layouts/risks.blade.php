@if(count($risks) > 0)
<p>risks</p>
<table class="table table-bordered table-striped table-vcenter" style="margin-top:-20px;">
    <tr style="background-color:white">
        <th style="font-size:12px">risk_owner</th>
        <th style="font-size:12px">edit</th>
    </tr>
    {{-- @php
    $j = 0;
    $owners = [];

    foreach($risks as $r) {
    $owners[$j] = $r->risk_owner;
    $j++;
    }
    $owners = array_unique($owners);
    $owners = implode(',', $owners);
    @endphp --}}
    <tr style="background-color:white">
        <td><span class="label label-success" style="font-size:11px">{{ $user->email }}</span></td>
        <td><span class="label label-success" style="font-size:11px">
                <select name="risks" id="risks" class="form-control @error('risks') is-invalid  @enderror" style="font-size: 13px;" required="Please">
                    <option value="0" selected="selected"> Select a user...</option>
                    @foreach($users as $u)
                    <option value="{{$u->email}}">{{$u->name}}</option>
                    @endforeach
                </select>
                @error('risks')
                <div class="invalid-feedback">{{ $message}}</div>
                @enderror
            </span></td>
    </tr>
</table>
@endif
