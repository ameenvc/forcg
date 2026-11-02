<table class="table table-bordered table-custom">
    <thead>
        <tr>
            <th style="width: 5%">ID</th>
            <th style="width: 20%">Root cause</th>
            <th style="width: 20%">Corrective Action</th>
            <th style="width: 15%">Department</th>
            <th style="width: 15%">Responsible User</th>
            <th style="width: 10%">Target Date</th>
        </tr>
    </thead>
    <tbody>
        @if(count($rootcause)>0 && $rootcause[0] != '')
        @foreach($rootcause as $item)
            @php
                $dept = isset($department[$count - 1]) ? App\Departments::where('id', $department[$count - 1])->value('departmentname') : '';
                $resp = isset($responsibility[$count - 1]) ? App\User::where('email', $responsibility[$count - 1])->value('name') : '';
                $respProfile = isset($responsibility[$count - 1]) ? App\User::where('email', $responsibility[$count - 1])->value('profile_picture') : '';
                $rootcauseText = isset($rootcause[$count - 1]) ? $rootcause[$count - 1] : '';
                $first_RCs_array = $corrective->RCs_added_by_responsible_person ? explode('$', $corrective->RCs_added_by_responsible_person) : null;
                $second_RCs_array = $corrective->RCs_added_by_investigation_team ? explode('$', $corrective->RCs_added_by_investigation_team) : null;
                $second_RCs = null;
                if($corrective->RCs_added_by_responsible_person && $corrective->RCs_added_by_investigation_team && count($first_RCs_array) > count($second_RCs_array)) {
                    $second_RCs = array_diff($first_RCs_array, $second_RCs_array);
                } elseif($corrective->RCs_added_by_responsible_person && $corrective->RCs_added_by_investigation_team && count($first_RCs_array) < count($second_RCs_array)) {
                    $second_RCs = array_diff($second_RCs_array, $first_RCs_array);
                }

                $rootcause_added_by_responsible_person = $corrective->RCs_added_by_responsible_person ? explode('$', $corrective->RCs_added_by_responsible_person) : null;
                $rootcause_added_by_investigation_team = $second_RCs;
            @endphp
            <tr>
                <td>{{ $count }}</td>
                <td style="text-align: left">{{ $rootcauseText }}</td>
                <td>{{ isset($correctiveaction[$count - 1] ) ? $correctiveaction[$count - 1] : ''}}</td>
                <td>{{ $dept }}</td>
                <td>
                    <div class="d-flex p-2 align-items-center root-cause-resp" style="background: #25499C24; border-radius: 10px">
                        @if($respProfile && File::exists(public_path('/storage/profile/'.$respProfile)))
                            <img src="{{ asset('/storage/profile/'.$respProfile) }}" class="img-avatar" style="width: 28px; height: 28px">
                        @else
                            <img src="{{asset('/images/avatar10.jpg')}}" class="img-avatar" style="width: 28px; height: 28px">
                        @endif
                        <div class="text-left ml-2">
                            <span style="color: #25499C" class="font-weight-bold">{{ $resp }}</span>
                            <span style="font-size: 8px" class="font-weight-bold font-italic">
                                {{-- @if(
                                        (isset($investigationTeams) && isset($responsibility[$count - 1])
                                        && count($investigationTeams) > 0
                                        && in_array($responsibility[$count - 1], $investigationTeams->pluck('user.email')->toArray())
                                    )
                                    || (isset($responsibility[$count - 1]) && $corrective->teamLead && $corrective->teamLead->email == $responsibility[$count - 1])
                                )
                                    Investigation Team
                                @else
                                    Responsible User
                                @endif --}}

                                @if($rootcause_added_by_investigation_team && count($rootcause_added_by_investigation_team) && in_array($item, $rootcause_added_by_investigation_team))
                                Investigation Team
                                @elseif($rootcause_added_by_responsible_person && count($rootcause_added_by_responsible_person) && in_array($item, $rootcause_added_by_responsible_person))
                                Responsible User
                                @endif

                            </span>
                        </div>
                    </div>
                </td>
                <td>{{ isset($target_date[$count - 1]) && $target_date[$count - 1] ? date('d-m-Y', strtotime($target_date[$count - 1])) : '-'}}</td>
            </tr>
            @php
                $count++;
            @endphp
        @endforeach
        @else 
            <tr>
                <td colspan="6" class="text-center text-secondary fw-500">No root cause identified</td>
            </tr>
        @endif
    </tbody>
</table>