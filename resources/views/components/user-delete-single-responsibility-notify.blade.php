@if($item)
<p>{{ $item_text }}</p>
<table class="table table-bordered table-striped table-vcenter" style="margin-top:-20px;">
    <tr style="background-color:white">
        <!-- <th style="font-size:12px">id</th> -->
        <th style="font-size:12px">name</th>
        <th style="font-size:12px">email</th>
        
    </tr>
    <tr style="background-color:white">
        <!-- <td><span class="label label-success" style="font-size:12px">{{ $item->id}}</span></td> -->
        <td><span class="label label-success" style="font-size:11px">{{ $item->name }}</span></td>
        <td><span class="label label-success" style="font-size:11px">{{ $item->email }}</span></td>
        
    </tr>
</table>
@endif
