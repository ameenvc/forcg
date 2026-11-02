@if(count($item))
<p>{{ $item_text }}</p>
@php
    $j = 0;
    $responsibilities = [];
    $correction_responsibilities = [];

    foreach($item as $x) {
        $responsibilities[$j] = $x->responsibility;
        $correction_responsibilities[$j] = isset($x->correction_responsibility) ? $x->correction_responsibility : '';
        $j++;
    }
    $responsibilities = array_unique($responsibilities);
    $responsibilities = implode(',', $responsibilities);
    $correction_responsibilities = array_unique($correction_responsibilities);
    $correction_responsibilities = implode(',', $correction_responsibilities);
@endphp
<table class="table table-bordered table-striped table-vcenter" style="margin-top:-20px;">
    <tr style="background-color:white">
        <th style="font-size:12px">responsibility</th>
    </tr>
    <tr style="background-color:white">
        <td><span class="label label-success" style="font-size:11px">{{ $user }}</span></td>
    </tr>
</table>
@endif

