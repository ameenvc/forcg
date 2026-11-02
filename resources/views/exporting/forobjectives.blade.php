@if( count($data) > 0)
<?php //dd($data); ?>
<table style="display:none;" class="table table-responsive table-bordered table-striped table-vcenter " id="headerTable1">
    <thead>
        <tr>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Objectives ID</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"> <span style="font-size:10px;">Department</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"> <span style="font-size:10px;">Objectives<br> Name </span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"> <span style="font-size:10px;">Target</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"> <span style="font-size:10px;">Program<br>Span</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"> <span style="font-size:10px;">Target Date</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"> <span style="font-size:10px;">Frequency of<br>Monitoring</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Reviewed Date</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"> <span style="font-size:10px;">Process Stage</span></th>

            <th class="text-center" style="font-size:11px;width: 120px;"> <span style="font-size:10px;">Added Date</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Objectives Desription</th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Responsibility</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Period : From</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Period : To</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Methodology</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Resources</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Reviewer Remarks</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Reviewer Proposal</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Approvar Remarks</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Approver Proposal</span></th>

        </tr>

    </thead>
    @foreach($data as $value)
    <?php
                                $rr=$value->Oid;
                                $day = $value->tardate;
                                $d = strtotime($day);
                                $date = date('Y-m-d',$d);
                                $dt = $value->tardate;
                                $dat=strtotime($dt);
                                $dates = date('Y-m-d',$dat);
                                $cats=$value->department;
                                $cat=strtoupper(substr($cats,0,3));
                                ?>
    <?php $status=$value->status;
                                $color=null;
                                ?>
    @if ($status == "Review Pending")
    <?php $color="red";?>
    @elseif($status == "Reviewed")
    <?php $color="orange";?>
    @elseif($status == "Resubmit")
    <?php $color="yellow";?>
    @else
    <?php $color="#40ff00";?>
    @endif
    <tbody>
        <tr>
            @if($value->status=="Rejected")
            <td class="text-center " style="background-color:red;font-size:11px;width: 100px;color:white"><span class="text-center " style="background-color:red;font-size:10px;width: 100px;color:white">{{ $value->objective_id }}</span></td>
            @elseif($value->status=="Review Pending")
            <td class="text-center " style="background-color:orange;font-size:11px;width: 100px;color:black"><span class="text-center " style="background-color:orange;font-size:10px;width: 100px;color:black">{{ $value->objective_id }}</span></td>
            @elseif($value->status=="Reviewed")
            <td class="text-center " style="background-color:yellow;font-size:11px;width: 100px;color:black"><span class="text-center " style="background-color:yellow;font-size:10px;width: 100px;color:black">{{ $value->objective_id }}</span></td>
            @else
            <td class="text-center " style="background-color:#a6ff00;font-size:11px;width: 100px;color:black"><span class="text-center " style="background-color:#a6ff00;font-size:10px;width: 100px;color:black">{{ $value->objective_id }}</span></td>
            @endif
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->dep ? $value->dep->departmentname : '-' }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->objectives }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->otarget }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->span }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->tardate }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->frequency }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->reviewed_at }}</span></td>
            @if($value->status=="Rejected")
            <td class="text-center " style="background-color:red;font-size:11px;width: 100px;color:white"><span class="text-center " style="background-color:red;font-size:10px;width: 100px;color:white">{{$value->status }}</span></td>
            @elseif($value->status=="Review Pending")
            <td class="text-center " style="background-color:orange;font-size:11px;width: 100px;color:black"><span class="text-center " style="background-color:orange;font-size:10px;width: 100px;color:black">{{ $value->status }}</span></td>
            @elseif($value->status=="Reviewed")
            <td class="text-center " style="background-color:yellow;font-size:11px;width: 100px;color:black"><span class="text-center " style="background-color:yellow;font-size:10px;width: 100px;color:black">{{ $value->status }}</span></td>
            @else
            <td class="text-center " style="background-color:#a6ff00;font-size:11px;width: 100px;color:black"><span class="text-center " style="background-color:#a6ff00;font-size:10px;width: 100px;color:black">{{ $value->status }}</span></td>
            @endif

            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->createdat }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->description }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->responsibility }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->fromdate }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->todate }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->methodology }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->resources }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->remarks }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->proposal }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->higherremarks }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->higherproposal }}</span></td>


        </tr>
    </tbody>
    @endforeach
</table>
@endif

@if( count($data1) > 0)
<?php //dd($data); ?>
<table style="display:none;" class="table table-responsive table-bordered table-striped table-vcenter " id="headerTable2">
    <thead>
        <tr>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Objectives ID</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"> <span style="font-size:10px;">Department</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"> <span style="font-size:10px;">Objectives<br> Name </span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"> <span style="font-size:10px;">Target</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"> <span style="font-size:10px;">Program<br>Span</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"> <span style="font-size:10px;">Target Date</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"> <span style="font-size:10px;">Frequency of<br>Monitoring</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Reviewed Date</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"> <span style="font-size:10px;">Process Stage</span></th>

            <th class="text-center" style="font-size:11px;width: 120px;"> <span style="font-size:10px;">Added Date</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Objectives Desription</th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Responsibility</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Period : From</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Period : To</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Methodology</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Resources</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Reviewer Remarks</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Reviewer Proposal</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Approvar Remarks</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Approver Proposal</span></th>

        </tr>

    </thead>
    @foreach($data1 as $value)
    <?php
                                $rr=$value->Oid;
                                $day = $value->tardate;
                                $d = strtotime($day);
                                $date = date('Y-m-d',$d);
                                $dt = $value->tardate;
                                $dat=strtotime($dt);
                                $dates = date('Y-m-d',$dat);
                                $cats=$value->department;
                                $cat=strtoupper(substr($cats,0,3));
                                ?>
    <?php $status=$value->status;
                                $color=null;
                                ?>
    @if ($status == "Review Pending")
    <?php $color="red";?>
    @elseif($status == "Reviewed")
    <?php $color="orange";?>
    @elseif($status == "Resubmit")
    <?php $color="yellow";?>
    @else
    <?php $color="#40ff00";?>
    @endif
    <tbody>
        <tr>
            @if($value->status=="Rejected")
            <td class="text-center " style="background-color:red;font-size:11px;width: 100px;color:white"><span class="text-center " style="background-color:red;font-size:10px;width: 100px;color:white"><?=$cat?>{{ $value->count }}</span></td>
            @elseif($value->status=="Review Pending")
            <td class="text-center " style="background-color:orange;font-size:11px;width: 100px;color:black"><span class="text-center " style="background-color:orange;font-size:10px;width: 100px;color:black"><?=$cat?>{{ $value->count }}</span></td>
            @elseif($value->status=="Reviewed")
            <td class="text-center " style="background-color:yellow;font-size:11px;width: 100px;color:black"><span class="text-center " style="background-color:yellow;font-size:10px;width: 100px;color:black"><?=$cat?>{{ $value->count }}</span></td>
            @else
            <td class="text-center " style="background-color:#a6ff00;font-size:11px;width: 100px;color:black"><span class="text-center " style="background-color:#a6ff00;font-size:10px;width: 100px;color:black"><?=$cat?>{{ $value->count }}</span></td>
            @endif
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->dep ? $value->dep->departmentname : '-' }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->objectives }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->otarget }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->span }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->tardate }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->frequency }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->reviewed_at }}</span></td>
            @if($value->status=="Rejected")
            <td class="text-center " style="background-color:red;font-size:11px;width: 100px;color:white"><span class="text-center " style="background-color:red;font-size:10px;width: 100px;color:white">{{$value->status }}</span></td>
            @elseif($value->status=="Review Pending")
            <td class="text-center " style="background-color:orange;font-size:11px;width: 100px;color:black"><span class="text-center " style="background-color:orange;font-size:10px;width: 100px;color:black">{{ $value->status }}</span></td>
            @elseif($value->status=="Reviewed")
            <td class="text-center " style="background-color:yellow;font-size:11px;width: 100px;color:black"><span class="text-center " style="background-color:yellow;font-size:10px;width: 100px;color:black">{{ $value->status }}</span></td>
            @else
            <td class="text-center " style="background-color:#a6ff00;font-size:11px;width: 100px;color:black"><span class="text-center " style="background-color:#a6ff00;font-size:10px;width: 100px;color:black">{{ $value->status }}</span></td>
            @endif

            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->createdat }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->description }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->responsibility }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->fromdate }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->todate }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->methodology }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->resources }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->remarks }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->proposal }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->higherremarks }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->higherproposal }}</span></td>


        </tr>
    </tbody>
    @endforeach
</table>
@endif

@if( count($data2) > 0)
<?php //dd($data); ?>
<table style="display:none;" class="table table-responsive table-bordered table-striped table-vcenter " id="headerTable3">
    <thead>
        <tr>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Objectives ID</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"> <span style="font-size:10px;">Department</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"> <span style="font-size:10px;">Objectives<br> Name </span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"> <span style="font-size:10px;">Target</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"> <span style="font-size:10px;">Program<br>Span</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"> <span style="font-size:10px;">Target Date</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"> <span style="font-size:10px;">Frequency of<br>Monitoring</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Reviewed Date</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"> <span style="font-size:10px;">Process Stage</span></th>

            <th class="text-center" style="font-size:11px;width: 120px;"> <span style="font-size:10px;">Added Date</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Objectives Desription</th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Responsibility</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Period : From</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Period : To</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Methodology</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Resources</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Reviewer Remarks</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Reviewer Proposal</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Approvar Remarks</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Approver Proposal</span></th>

        </tr>

    </thead>
    @foreach($data2 as $value)
    <?php
                                $rr=$value->Oid;
                                $day = $value->tardate;
                                $d = strtotime($day);
                                $date = date('Y-m-d',$d);
                                $dt = $value->tardate;
                                $dat=strtotime($dt);
                                $dates = date('Y-m-d',$dat);
                                $cats=$value->department;
                                $cat=strtoupper(substr($cats,0,3));
                                ?>
    <?php $status=$value->status;
                                $color=null;
                                ?>
    @if ($status == "Review Pending")
    <?php $color="red";?>
    @elseif($status == "Reviewed")
    <?php $color="orange";?>
    @elseif($status == "Resubmit")
    <?php $color="yellow";?>
    @else
    <?php $color="#40ff00";?>
    @endif
    <tbody>
        <tr>
            @if($value->status=="Rejected")
            <td class="text-center " style="background-color:red;font-size:11px;width: 100px;color:white"><span class="text-center " style="background-color:red;font-size:10px;width: 100px;color:white"><?=$cat?>{{ $value->count }}</span></td>
            @elseif($value->status=="Review Pending")
            <td class="text-center " style="background-color:orange;font-size:11px;width: 100px;color:black"><span class="text-center " style="background-color:orange;font-size:10px;width: 100px;color:black"><?=$cat?>{{ $value->count }}</span></td>
            @elseif($value->status=="Reviewed")
            <td class="text-center " style="background-color:yellow;font-size:11px;width: 100px;color:black"><span class="text-center " style="background-color:yellow;font-size:10px;width: 100px;color:black"><?=$cat?>{{ $value->count }}</span></td>
            @else
            <td class="text-center " style="background-color:#a6ff00;font-size:11px;width: 100px;color:black"><span class="text-center " style="background-color:#a6ff00;font-size:10px;width: 100px;color:black"><?=$cat?>{{ $value->count }}</span></td>
            @endif
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->dep ? $value->dep->departmentname : '-' }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->objectives }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->otarget }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->span }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->tardate }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->frequency }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->reviewed_at }}</span></td>
            @if($value->status=="Rejected")
            <td class="text-center " style="background-color:red;font-size:10px;width: 100px;color:white"><span class="text-center " style="background-color:red;font-size:10px;width: 100px;color:white">{{$value->status }}</span></td>
            @elseif($value->status=="Review Pending")
            <td class="text-center " style="background-color:orange;font-size:11px;width: 100px;color:black"><span class="text-center " style="background-color:orange;font-size:10px;width: 100px;color:black">{{ $value->status }}</span></td>
            @elseif($value->status=="Reviewed")
            <td class="text-center " style="background-color:yellow;font-size:11px;width: 100px;color:black"><span class="text-center " style="background-color:yellow;font-size:10px;width: 100px;color:black">{{ $value->status }}</span></td>
            @else
            <td class="text-center " style="background-color:#a6ff00;font-size:11px;width: 100px;color:black"><span class="text-center " style="background-color:#a6ff00;font-size:10px;width: 100px;color:black">{{ $value->status }}</span></td>
            @endif

            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->createdat }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->description }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->responsibility }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->fromdate }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->todate }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->methodology }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->resources }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->remarks }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->proposal }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->higherremarks }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->higherproposal }}</span></td>


        </tr>
    </tbody>
    @endforeach
</table>
@endif

@if( count($data3) > 0)
<?php //dd($data); ?>
<table style="display:none;" class="table table-responsive table-bordered table-striped table-vcenter " id="headerTable4">
    <thead>
        <tr>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Objectives ID</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"> <span style="font-size:10px;">Department</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"> <span style="font-size:10px;">Objectives<br> Name </span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"> <span style="font-size:10px;">Target</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"> <span style="font-size:10px;">Program<br>Span</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"> <span style="font-size:10px;">Target Date</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"> <span style="font-size:10px;">Frequency of<br>Monitoring</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Reviewed Date</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"> <span style="font-size:10px;">Process Stage</span></th>

            <th class="text-center" style="font-size:11px;width: 120px;"> <span style="font-size:10px;">Added Date</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Objectives Desription</th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Responsibility</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Period : From</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Period : To</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Methodology</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Resources</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Reviewer Remarks</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Reviewer Proposal</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Approvar Remarks</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Approver Proposal</span></th>

        </tr>

    </thead>
    @foreach($data3 as $value)
    <?php
                                $rr=$value->Oid;
                                $day = $value->tardate;
                                $d = strtotime($day);
                                $date = date('Y-m-d',$d);
                                $dt = $value->tardate;
                                $dat=strtotime($dt);
                                $dates = date('Y-m-d',$dat);
                                $cats=$value->department;
                                $cat=strtoupper(substr($cats,0,3));
                                ?>
    <?php $status=$value->status;
                                $color=null;
                                ?>
    @if ($status == "Review Pending")
    <?php $color="red";?>
    @elseif($status == "Reviewed")
    <?php $color="orange";?>
    @elseif($status == "Resubmit")
    <?php $color="yellow";?>
    @else
    <?php $color="#40ff00";?>
    @endif
    <tbody>
        <tr>
            @if($value->status=="Rejected")
            <td class="text-center " style="background-color:red;font-size:11px;width: 100px;color:white"><span class="text-center " style="background-color:red;font-size:10px;width: 100px;color:white"><?=$cat?>{{ $value->count }}</span></td>
            @elseif($value->status=="Review Pending")
            <td class="text-center " style="background-color:orange;font-size:11px;width: 100px;color:black"><span class="text-center " style="background-color:orange;font-size:10px;width: 100px;color:black"><?=$cat?>{{ $value->count }}</span></td>
            @elseif($value->status=="Reviewed")
            <td class="text-center " style="background-color:yellow;font-size:11px;width: 100px;color:black"><span class="text-center " style="background-color:yellow;font-size:10px;width: 100px;color:black"><?=$cat?>{{ $value->count }}</span></td>
            @else
            <td class="text-center " style="background-color:#a6ff00;font-size:11px;width: 100px;color:black"><span class="text-center " style="background-color:#a6ff00;font-size:10px;width: 100px;color:black"><?=$cat?>{{ $value->count }}</span></td>
            @endif
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->dep ? $value->dep->departmentname : '-' }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->objectives }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->otarget }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->span }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->tardate }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->frequency }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->reviewed_at }}</span></td>
            @if($value->status=="Rejected")
            <td class="text-center " style="background-color:red;font-size:11px;width: 100px;color:white"><span class="text-center " style="background-color:red;font-size:10px;width: 100px;color:white">{{$value->status }}</span></td>
            @elseif($value->status=="Review Pending")
            <td class="text-center " style="background-color:orange;font-size:11px;width: 100px;color:black"><span class="text-center " style="background-color:orange;font-size:10px;width: 100px;color:black">{{ $value->status }}</span></td>
            @elseif($value->status=="Reviewed")
            <td class="text-center " style="background-color:yellow;font-size:11px;width: 100px;color:black"><span class="text-center " style="background-color:yellow;font-size:10px;width: 100px;color:black">{{ $value->status }}</span></td>
            @else
            <td class="text-center " style="background-color:#a6ff00;font-size:11px;width: 100px;color:black"><span class="text-center " style="background-color:#a6ff00;font-size:10px;width: 100px;color:black">{{ $value->status }}</span></td>
            @endif

            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->createdat }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->description }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->responsibility }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->fromdate }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->todate }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->methodology }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->resources }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->remarks }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->proposal }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->higherremarks }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->higherproposal }}</span></td>


        </tr>
    </tbody>
    @endforeach
</table>
@endif

@if( count($data4) > 0)
<?php //dd($data); ?>
<table style="display:none;" class="table table-responsive table-bordered table-striped table-vcenter " id="headerTable5">
    <thead>
        <tr>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Objectives ID</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"> <span style="font-size:10px;">Department</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"> <span style="font-size:10px;">Objectives<br> Name </span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"> <span style="font-size:10px;">Target</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"> <span style="font-size:10px;">Program<br>Span</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"> <span style="font-size:10px;">Target Date</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"> <span style="font-size:10px;">Frequency of<br>Monitoring</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Reviewed Date</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"> <span style="font-size:10px;">Process Stage</span></th>

            <th class="text-center" style="font-size:11px;width: 120px;"> <span style="font-size:10px;">Added Date</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Objectives Desription</th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Responsibility</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Period : From</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Period : To</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Methodology</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Resources</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Reviewer Remarks</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Reviewer Proposal</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Approvar Remarks</span></th>
            <th class="text-center" style="font-size:11px;width: 120px;"><span style="font-size:10px;">Approver Proposal</span></th>

        </tr>

    </thead>
    @foreach($data4 as $value)
    <?php
                                $rr=$value->Oid;
                                $day = $value->tardate;
                                $d = strtotime($day);
                                $date = date('Y-m-d',$d);
                                $dt = $value->tardate;
                                $dat=strtotime($dt);
                                $dates = date('Y-m-d',$dat);
                                $cats=$value->department;
                                $cat=strtoupper(substr($cats,0,3));
                                ?>
    <?php $status=$value->status;
                                $color=null;
                                ?>
    @if ($status == "Review Pending")
    <?php $color="red";?>
    @elseif($status == "Reviewed")
    <?php $color="orange";?>
    @elseif($status == "Resubmit")
    <?php $color="yellow";?>
    @else
    <?php $color="#40ff00";?>
    @endif
    <tbody>
        <tr>
            @if($value->status=="Rejected")
            <td class="text-center " style="background-color:red;font-size:11px;width: 100px;color:white"><span class="text-center " style="background-color:red;font-size:10px;width: 100px;color:white"><?=$cat?>{{ $value->count }}</span></td>
            @elseif($value->status=="Review Pending")
            <td class="text-center " style="background-color:orange;font-size:11px;width: 100px;color:black"><span class="text-center " style="background-color:orange;font-size:10px;width: 100px;color:black"><?=$cat?>{{ $value->count }}</span></td>
            @elseif($value->status=="Reviewed")
            <td class="text-center " style="background-color:yellow;font-size:11px;width: 100px;color:black"><span class="text-center " style="background-color:yellow;font-size:10px;width: 100px;color:black"><?=$cat?>{{ $value->count }}</span></td>
            @else
            <td class="text-center " style="background-color:#a6ff00;font-size:11px;width: 100px;color:black"><span class="text-center " style="background-color:#a6ff00;font-size:10px;width: 100px;color:black"><?=$cat?>{{ $value->count }}</span></td>
            @endif
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->dep ? $value->dep->departmentname : '-' }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->objectives }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->otarget }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->span }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->tardate }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->frequency }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->reviewed_at }}</span></td>
            @if($value->status=="Rejected")
            <td class="text-center " style="background-color:red;font-size:11px;width: 100px;color:white"><span class="text-center " style="background-color:red;font-size:10px;width: 100px;color:white">{{$value->status }}</span></td>
            @elseif($value->status=="Review Pending")
            <td class="text-center " style="background-color:orange;font-size:11px;width: 100px;color:black"><span class="text-center " style="background-color:orange;font-size:1px;width: 100px;color:black">{{ $value->status }}</span></td>
            @elseif($value->status=="Reviewed")
            <td class="text-center " style="background-color:yellow;font-size:11px;width: 100px;color:black"><span class="text-center " style="background-color:yellow;font-size:10px;width: 100px;color:black">{{ $value->status }}</span></td>
            @else
            <td class="text-center " style="background-color:#a6ff00;font-size:11px;width: 100px;color:black"><span class="text-center " style="background-color:#a6ff00;font-size:10px;width: 100px;color:black">{{ $value->status }}</span></td>
            @endif

            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->createdat }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->description }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->responsibility }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->fromdate }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->todate }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->methodology }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->resources }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->remarks }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->proposal }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->higherremarks }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->higherproposal }}</span></td>


        </tr>
    </tbody>
    @endforeach
</table>
@endif

<script type="text/javascript">
    var tab_text

    function fnCovertTable(type) {
        if (document.getElementById('tabselected').value == 'a1') {
            exptable = "headerTable1";
            pdfvalue = "tvalue1";
            x = 11;
        } else if (document.getElementById('tabselected').value == 'a2') {
            exptable = "headerTable2";
            pdfvalue = "tvalue2";
            x = 11;
        } else if (document.getElementById('tabselected').value == 'a3') {
            exptable = "headerTable3";
            pdfvalue = "tvalue3";
            x = 11;
        } else if (document.getElementById('tabselected').value == 'a4') {
            exptable = "headerTable4";
            pdfvalue = "tvalue4";
            x = 11;
        } else if (document.getElementById('tabselected').value == 'a5') {
            exptable = "headerTable5";
            pdfvalue = "tvalue5";
            x = 11;
        }
        tab_text = "<table border='1' style='border-collapse:collapse;' bordercolor='black'  align='center' >";
        var textRange;
        var j = 0;
        var i = 0;
        tab = document.getElementById(exptable); // id of table
        for (j = 0; j < tab.rows.length; j++) {
            tab_text = tab_text + "<tr>"
            for (i = 0; i < tab.rows[j].cells.length - x; i++) {
                tab_text = tab_text + "<td>"
                tab_text = tab_text + tab.rows[j].cells[i].innerHTML + "</td>";
            }
            tab_text = tab_text + "</tr>";
        }

        tab_text = tab_text + "</table>";
        tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, ""); //remove if u want links in your table
        tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
        tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params
        if (type == 'excel') {
            tab_text = "<table border='1' style='border-collapse:collapse;' bordercolor='black'  align='center' >";
            var textRange;
            var j = 0;
            var i = 0;
            tab = document.getElementById(exptable); // id of table
            for (j = 0; j < tab.rows.length; j++) {
                tab_text = tab_text + "<tr>"
                tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
            }

            tab_text = tab_text + "</table>";
            tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, ""); //remove if u want links in your table
            tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
            tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params
            var ua = window.navigator.userAgent;
            var msie = ua.indexOf("MSIE ");
            if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) // If Internet Explorer
            {
                txtArea1.document.open("txt/html", "replace");
                txtArea1.document.write(tab_text);
                txtArea1.document.close();
                txtArea1.focus();
                sa = txtArea1.document.execCommand("SaveAs", true, "Say Thanks to Sumit.xls");
            } else //other browser not tested on IE 11
                sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
            return (sa);
        } else if (type == 'word') {
            var ua = window.navigator.userAgent;
            var msie = ua.indexOf("MSIE ");
            if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) // If Internet Explorer
            {
                txtArea1.document.open("txt/html", "replace");
                txtArea1.document.write(tab_text);
                txtArea1.document.close();
                txtArea1.focus();
                sa = div.innerHTML.document.execCommand("SaveAs", true, "Setilas.doc");
            } else //other browser not tested on IE 11
                sa = window.open('data:application/msword,' + encodeURIComponent(tab_text));

            return (sa);
        } else if (type == 'print') {
            var oldPage = document.body.innerHTML;
            document.body.innerHTML = tab_text;
            window.print();
            document.body.innerHTML = oldPage;
        } else if (type == "screenshot") {
            oldpage = document.body.innerHTML;
            document.body.innerHTML = tab_text;
            document.body.innerHTML = document.body.innerHTML + "<br/><button value='10' style='float:right'  class='btn btn-success' onclick='saveme()' id='save' download='file-name.png'>Save</button><span></span><button style='float:right' class='btn btn-success' onclick='back()'>Back</button><a id='blank'></a>";
        } else if (type == "pdf") {
            tab_text = "<table border='1' style='border-collapse:collapse;' bordercolor='black'  align='center' >";
            var textRange;
            var j = 0;
            var i = 0;
            tab = document.getElementById(exptable); // id of table
            tab_text = tab_text + "<tr>"
            for (i = 0; i < tab.rows[0].cells.length - x; i++) {
                tab_text = tab_text + "<th>"
                tab_text = tab_text + tab.rows[0].cells[i].innerHTML + "</th>";
            }
            tab_text = tab_text + "</tr>"
            for (j = 1; j < tab.rows.length; j++) {
                tab_text = tab_text + "<tr>"
                for (i = 0; i < tab.rows[j].cells.length - x; i++) {
                    tab_text = tab_text + "<td>"
                    tab_text = tab_text + tab.rows[j].cells[i].innerHTML + "</td>";
                }
                tab_text = tab_text + "</tr>";
            }

            tab_text = tab_text + "</table>";

            tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, ""); //remove if u want links in your table
            tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
            tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params


            document.getElementById(pdfvalue).value = tab_text;
            return true;

        } else if (type == 'magnify') {
            tab_text = "<table border='1' style='border-collapse:collapse;' bordercolor='black'  align='center' >";
            var textRange;
            var j = 0;
            var i = 0;
            tab = document.getElementById('mainTable'); // id of table
            for (j = 0; j < tab.rows.length; j++) {
                tab_text = tab_text + "<tr>"
                tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
            }

            tab_text = tab_text + "</table>";
            tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, ""); //remove if u want links in your table
            tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
            tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params
            oldpage = document.body.innerHTML;
            var div1 = document.createElement('div');
            div1.setAttribute("id", "div1");
            var div2 = document.createElement('div');
            div2.setAttribute("id", "div2");
            div1.innerHTML = tab_text;
            div2.innerHTML = "<button type='button' class='btn btn-success'  onclick='back()'>Reset</button>"
            document.body.innerHTML = ''
            document.body.appendChild(div1);
            document.body.appendChild(div2);

        }
    }

</script>
