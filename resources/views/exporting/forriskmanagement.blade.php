<style>
    .text-center{ text-align: center }
</style>
@if(isset($commanData['datas']) && count($commanData['datas'])>0)
    <table border='1' style='border-collapse:collapse;' bordercolor='black'  align='center'>
        <thead>
            <tr>
                <th class="text-center" style="font-size:11px;width: 50px;text-transform:capitalize" ><span style="text-transform:capitalize">S.No.</span></th>
                <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize" ><span style="text-transform:capitalize">ID</span></th>
                <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="text-transform:capitalize">Risk Group</span></th>
                <th class="text-center" style="font-size:11px;width: 200px;text-transform:capitalize"><span style="text-transform:capitalize">Risk Area</span></th>
                <th class="text-center" style="font-size:11px;width: 300px;text-transform:capitalize"><span style="text-transform:capitalize">Risk Name</span></th>
                <th class="text-center" style="font-size:11px;width: 60px;text-transform:capitalize"><span style="text-transform:capitalize">Risk</span></th>
                <th class="text-center" style="font-size:11px;width: 200px;text-transform:capitalize"><span style="text-transform:capitalize">Risk Owner</span></th>
                <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="text-transform:capitalize">Risk Response</span></th>
                <th class="text-center" style="font-size:11px;width: 200px;text-transform:capitalize"><span style="text-transform:capitalize">Process Stage</span></th>   
            </tr>                   
        </thead>
        <tbody> 
            @foreach($commanData['datas'] as $value)
                @php
                    $bgcolor=null;
                    $class=null;
                    // Rank color
                    if($value->rank == "High"){
                        $colorin = '#af1e1e';
                        $colorbg = '#f8d4d4';
                    }elseif($value->rank == "Moderate"){
                        $colorin = '#cc8800';
                        $colorbg = '#ffebcc';
                    }else{
                        $colorin = '#176044';
                        $colorbg = '#e0edcf';
                    }
                    // Status color
                    if($value->action_status == "Active Risk"){
                        $bgcolor ='#e0edcf';
                        $class = "#176044";
                    }elseif($value->action_status == "Under review" || $value->action_status == "Awaiting Periodic Review"){
                        $class = '#af6f1e';
                        $bgcolor ='#fbdece';
                    }elseif($value->action_status == "Action under review"){
                        $bgcolor ='#e6e6e6';
                        $class = "black";
                    }elseif($value->action_status == "Rejected"){
                        $bgcolor ='#f8d4d4';
                        $class = "#af1e1e";
                    }elseif($value->action_status == "Completed"){
                        $bgcolor ='#d9e2f6';
                        $class = "#25499c";
                    }elseif($value->action_status == "Closed"){
                        $bgcolor ='#fbdece';
                        $class="black";
                    }
                @endphp
                
                <tr>
                    <td class="text-center "style="font-size:12px;"><span style="">{{ $loop->iteration}}</span></td>
                    <td class="text-center "style="font-size:12px;"><span style="">{{ $value->grp_id}}</span></td>
                    <td class="text-center "style="font-size:12px;"><span style="">{{ $value->riskgroup }}</span></td>
                    <td class="text-center "style="font-size:12px;"><span style="">{{ $value->department ? $value->department->departmentname : '-' }}</span></td>
                    <td class="text-center "style="font-size:12px;"><span style="">{{ $value->riskname }}</span></td>

                    <td class="text-center" style="font-size:12px;">
                        <span style="background-color:{{$colorbg}};color:{{$colorin}};">
                            {{$value->rank }}
                        </span>
                    </td>
                    <td class="text-center "style="font-size:12px;">
                        <span style="">
                            @if($value->risk_owner_users->isNotEmpty())
                                @foreach($value->risk_owner_users as $user)
                                    {{ $user->name }} - {{ $user->employeeid }}<br>
                                @endforeach
                            @else
                                -
                            @endif
                        </span>
                    </td>
                    <td class="text-center" style="font-size:12px;">
                        <span style="">{{$value->risk_response }}</span>
                    </td>                    
                    <td class="text-center" style="font-size:12px;">
                        <span  style="background-color:{{$bgcolor}};color:{{$class}};">
                            {{ $value->action_status}}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif