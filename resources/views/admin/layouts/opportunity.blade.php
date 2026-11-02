@if(count($opportunities) > 0)
<p>Opportunities</p>
<table class="table table-bordered table-striped table-vcenter" style="margin-top:-20px;">
    <tr style="background-color:white">
        <th style="font-size:12px">responsibility</th>
        <th style="font-size:12px">edit</th>
    </tr>
    @php
       $j = 0;
       $responsibilities = [];

       foreach($opportunities as $o) {
          $responsibilities[$j] = $o->responsibility;
          $j++;
       }
       $responsibilities = array_unique($responsibilities);
       $responsibilities = implode(',', $responsibilities);
    @endphp
    <tr style="background-color:white">
        <td><span class="label label-success" style="font-size:11px">{{ $responsibilities }}</span></td>
        <td><span class="label label-success" style="font-size:11px">
                <select name="opportunity" id="opportunity" class="form-control @error('opportunity') is-invalid  @enderror" style="font-size: 13px;" required="Please">
                    <option value="0" selected="selected"> Select a user...</option>
                    @foreach($users as $u)
                    <option value="{{$u->email}}">{{$u->name}}</option>
                    @endforeach
                </select>
                @error('opportunity')
                <div class="invalid-feedback">{{ $message}}</div>
                @enderror
            </span></td>
    </tr>
</table>
@endif
