@if($item)
<p>{{ $item_text }}</p>
<table class="table table-bordered table-striped table-vcenter" style="margin-top:-20px;">
    <tr style="background-color:white">
        <th style="font-size:12px">id</th>
        <th style="font-size:12px">name</th>
        <th style="font-size:12px">email</th>
        <th style="font-size:12px">edit</th>
    </tr>
    <tr style="background-color:white">
        <td><span class="label label-success" style="font-size:12px">{{ $item->id}}</span></td>
        <td><span class="label label-success" style="font-size:11px">{{ $item->name }}</span></td>
        <td><span class="label label-success" style="font-size:11px">{{ $item->email }}</span></td>
        <td><span class="label label-success" style="font-size:11px">
                <select id="{{ $item_select }}" name="{{ $item_select }}" class="form-control @error($item_select) is-invalid  @enderror" style="font-size: 13px;" required="Please">
                    <option value="0" selected="selected"> Select a user...</option>
                    @foreach($item_users as $u)
                    <option value="{{$u->id}},{{ $item->id}}">{{$u->name}}</option>
                    @endforeach
                </select>
                @error($item_select)
                <div class="invalid-feedback">{{ $message}}</div>
                @enderror
            </span></td>
    </tr>
</table>
@endif
