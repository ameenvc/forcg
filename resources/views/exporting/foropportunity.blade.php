<style>
    .text-center{ text-align: center }
</style>
@if(isset($commanData['datas']) && count($commanData['datas'])>0)
    <table border='1' style='border-collapse:collapse;' bordercolor='black'  align='center'>
        <thead>
            <tr>
                <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize" ><span style="text-transform:capitalize">S.No.</span></th>
                <th class="text-center" style="font-size:12px;width: 150px;text-transform:capitalize" ><span style="text-transform:capitalize">ID</span></th>
                <th class="text-center" style="font-size:12px;width: 200px;text-transform:capitalize"><span style="text-transform:capitalize">Department</span></th>
                <th class="text-center" style="font-size:12px;width: 200px;text-transform:capitalize"><span style="text-transform:capitalize">Responsibility</span></th>
                <th class="text-center" style="font-size:12px;width: 250px;text-transform:capitalize"><span style="text-transform:capitalize">Opportunity Name</span></th>
                <th class="text-center" style="font-size:12px;width: 100px;text-transform:capitalize"><span style="text-transform:capitalize">Target Date</span></th>
                <th class="text-center" style="font-size:12px;width: 250px;text-transform:capitalize"><span style="text-transform:capitalize">Type</span></th>
                <th class="text-center" style="font-size:12px;width: 80px;text-transform:capitalize"><span style="text-transform:capitalize">Added Date</span></th>
                <th class="text-center" style="font-size:12px;width: 100px;text-transform:capitalize"><span style="text-transform:capitalize">Positive Impact</span></th>
                <th class="text-center" style="font-size:12px;width: 150px;text-transform:capitalize"><span style="text-transform:capitalize">Process Stage</span></th>
                <th class="text-center" style="font-size:12px;width: 100px;text-transform:capitalize"><span style="text-transform:capitalize">Action Status</span></th>   
            </tr>                   
        </thead>
        <tbody>
            @foreach($commanData['datas'] as $value)
            <tr>
                @php 
                    $today = Date("Y-m-d");
                @endphp
                <td class="text-center "style="font-size:12px;"><span style="font-size:12px;">{{ $loop->iteration }}</span></td>
                <td class="text-center "style="font-size:12px;"><span style="font-size:12px;">{{ $value->dep_id }}</span></td>
                <td class="text-center "style="font-size:12px;"><span style="font-size:12px;">{{ optional($value->dept)->departmentname ?? '-'}}</span></td>
                <td class="text-center "style="font-size:12px;"><span style="font-size:12px;">{{ $value->byresponsibility ? $value->byresponsibility->name.' - '.$value->byresponsibility->employeeid : '-'}}</span></td>
                <td class="text-center "style="font-size:12px;"><span style="font-size:12px;">{{ $value->oppname }}</span></td>
                @if($value->tardate > $today)
                <td class="text-center "style="font-size:12px;"><span style="color: #176044;background-color: #e0edcf;font-size:12px;">{{ date('d-m-Y',strtotime($value->tardate)) }}</span></td>
                @else 
                <td class="text-center "style="font-size:12px;"><span style="color: #af1e1e;background-color: #f8d4d4;font-size:12px;">{{ date('d-m-Y',strtotime($value->tardate)) }}</span></td>
                @endif
                <td class="text-center "style="font-size:12px;">
                    <span style="font-size:12px;">
                        @if($value->typeRelation && count($value->typeRelation))
                            @foreach($value->typeRelation as $typeRelation)
                                {{ $typeRelation->name }}
                            @endforeach
                        @endif
                    </span>
                </td>
                <td class="text-center "style="font-size:12px;"><span style="font-size:12px;">{{ date('d-m-Y',strtotime($value->createdat)) }}</span></td>
                @if($value->impact=="High")
                <td class="text-center "style="font-size:12px;"><span style="color: #af1e1e;background-color: #f8d4d4;font-size:12px;">{{$value->impact }}</span></td>
                @elseif($value->impact=="Moderate")
                <td class="text-center "style="font-size:12px;"><span style="font-size:12px;color: #af6f1e;background-color: #fbdece;">{{$value->impact }}</span></td>
                @else
                <td class="text-center "style="font-size:12px;"><span style="color: #176044;background-color: #e0edcf;font-size:12px;">{{$value->impact }}</span></td>
                @endif
                <td class="text-center" style="font-size:12px;">
                    @if($value->status=='Approved')
                        <span style="font-size:12px;color: #176044;background-color: #e0edcf;border-color: #bff0dd;">{{$value->status}}</span>
                    @elseif($value->status=='Disapproved')
                        <span style="font-size:12px;color: #af1e1e;background-color: #f8d4d4;border-color: #fbeaea;">{{$value->status}}</span>
                    @elseif($value->status=='Review Pending')
                        <span style="font-size:12px;color: #af6f1e;background-color: #fbdece;border-color: #fbf4ea;">{{$value->status}}</span>
                    @elseif($value->status=='Completed')
                        <span style="font-size:12px;color: #25499c;background-color: #d9e2f6;border-color: #d9e2f6;">{{$value->status}}</span>
                    @else 
                        <span style="font-size:12px;color: #176044;background-color: #e0edcf;border-color: #bff0dd;">-</span>
                    @endif
                </td>
                <td class="text-center "style="font-size:12px;">
                    <span style="font-size:12px;">
                        @if($value->actionstatus=='Progress-25%')
                            25%
                        @elseif($value->actionstatus=='Partially Completed-50%')
                            50%
                        @elseif($value->actionstatus=='Completed-100%')
                            100%
                        @elseif($value->actionstatus=='Not Started-0%')
                            0%
                        @else 
                            0%
                        @endif
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endif