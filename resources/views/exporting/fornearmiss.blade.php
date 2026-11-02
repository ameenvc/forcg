@if(count($issues)>0)
<?php //dd($data); ?>
<table style="display:none;" class="table table-responsive table-bordered table-striped table-vcenter table2" id="headerTable1">
    <thead>
        <tr>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Id</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Department</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Type</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Reported by Id</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Reported by name</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Area</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Title</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Post Date</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Target Date</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Status</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Process<br>Stage</span></th>
            <th class="text-center" style="font-size:12px;width: 200px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Action<br>Status</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Observation</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Responsibility</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Recommended Action</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Correction</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Root Cause</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Corrective Action</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Remarks</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Action Review Remarks</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Posted By</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Last Reviewed</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Approved By</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Issue Closed By</span></th>

        </tr>

    </thead>
    <?php $no=0;?>
    @foreach($issues as $issue)
    <?php

                    $rr=$issue->id;
                    // $cats=$issue->Area;
                    // $cat=strtoupper(substr($cats,0,3));
                    $date = $issue->targetdate;
                    $today = Date("Y-m-d");
                    $color = null ;?>

    <tbody>
        <tr>

            <td class="text-center " style="font-size:12px;width: 50px;color:black"><span style="font-size:10px;width: 50px;color:black">{{$issue->issueid}}</span></td>


            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->department}}</span></td>

            @if($issue->type=="Low")
            <td class="text-center " style="background-color:#baeb34;font-size:11px;width: 50px;color:black"><span style="background-color:#baeb34;font-size:10px;width: 50px;color:black">{{$issue->type}}</span></span></td>
            @elseif($issue->type=="Moderate"||$issue->type=="Medium")
            <td class="text-center " style="background-color:#f29018;;font-size:11px;width: 50px;color:black"><span style="background-color:#f29018;;font-size:10px;width: 50px;color:black">{{$issue->type}}</span></span></td>
            @else
            <td class="text-center " style="background-color:red;font-size:11px;width: 50px;color:white"><span style="background-color:red;font-size:10px;width: 50px;color:white">{{$issue->type}}</span></td>
            @endif
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->reportedId}}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->reportedName}}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->area}}</span></td>

            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->title}}</td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{date('M/d/Y',strtotime($issue->created_at))}}</span></span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{date('M/d/Y',strtotime($issue->targetdate))}}</span></span></td>
            @if ($issue->status =='open' && $today <= $date) <td class="text-center " style="background-color:blue;font-size:12px;width: 50px;color:white"><span style="background-color:blue;font-size:10px;width: 50px;color:white">{{$issue->status}}</span></td>
                @elseif ($issue->status == 'open' && $today > $date)
                <?php $issue->status='open,due' ?>
                <td class="text-center " style="background-color:red;font-size:12px;width: 50px;color:white"><span style="background-color:red;font-size:10px;width: 50px;color:white">{{$issue->status}}</span></td>
                @elseif($issue->status == 'closed' && $today <= $date) <td class="text-center " style="background-color:#a6ff00;font-size:12px;width: 50px;color:black"><span style="background-color:#a6ff00;font-size:10px;width: 50px;color:black">{{$issue->status}}</span></td>
                    @elseif($issue->status == 'closed' && $today > $date)
                    <?php $issue->status='closed after due' ?>
                    <td class="text-center " style="background-color:#7eab44;font-size:12px;width: 50px;color:black"><span style="background-color:#7eab44;font-size:10px;width: 50px;color:black">{{$issue->status}}</span></td>
                    @else
                    <td class="text-center " style="background-color:red;font-size:12px;width: 50px;color:black"><span style="background-color:white;font-size:10px;width: 50px;color:black">{{$issue->status}}</span></td>

                    @endif
                    <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->processstage}}</span></td>
                    <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->impl_status}}</span></td>
                    <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;"><?php echo nl2br($issue->observation )?></span></td>
                    <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->responsibility}}</span></td>
                    <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;"><?php echo nl2br($issue->agreedaction)?></span></td>
                    <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;"><?php echo nl2br($issue->correction )?></span></td>
                    <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;"><?php echo nl2br($issue->rootcause )?></span></td>
                    <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;"><?php echo nl2br($issue->correctiveaction )?></span></td>
                    <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;"><?php echo nl2br($issue->remarks)?></span></td>
                    <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;"><?php echo nl2br($issue->action_remarks)?></span></td>
                    <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->postedby}}</span></td>
                    <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->updated_at}}</span></td>
                    <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->approvedby}}</span></td>
                    <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->closedby}}</span></td>

        </tr>
    </tbody>
    @endforeach
</table>
@endif


@if(count($issues1)>0)
<?php //dd($data); ?>
<table style="display:none;" class="table table-responsive table-bordered table-striped table-vcenter table2" id="headerTable2">
    <thead>
        <tr>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Id</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Department</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Type</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Reported by Id</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Reported by name</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Area</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Title</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Agreed action</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Post Date</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Target Date</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Status</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Process<br>Stage</span></th>
            <th class="text-center" style="font-size:12px;width: 200px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Action<br>Status</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Observation</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Responsibility</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Correction</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Root Cause</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Corrective Action</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Remarks</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Action Review Remarks</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Posted By</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Last Reviewed</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Approved By</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Issue Closed By</span></th>

        </tr>

    </thead>
    <?php $no=0;?>
    @foreach($issues1 as $issue)
    <?php

                    $rr=$issue->id;
                    // $cats=$issue->Area;
                    // $cat=strtoupper(substr($cats,0,3));
                    $date = $issue->targetdate;
                    $today = Date("Y-m-d");
                    $color = null ;?>

    <tbody>
        <tr>

            <td class="text-center " style="font-size:12px;width: 50px;color:black"><span style="font-size:10px;width: 50px;color:black">{{$issue->issueid}}</span></td>

            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->department}}</span></td>

            @if($issue->type=="Low")
            <td class="text-center " style="background-color:#baeb34;font-size:11px;width: 50px;color:black"><span style="background-color:#baeb34;font-size:10px;width: 50px;color:black">{{$issue->type}}</span></span></td>
            @elseif($issue->type=="Moderate"||$issue->type=="Medium")
            <td class="text-center " style="background-color:#f29018;;font-size:11px;width: 50px;color:black"><span style="background-color:#f29018;;font-size:10px;width: 50px;color:black">{{$issue->type}}</span></span></td>
            @else
            <td class="text-center " style="background-color:red;font-size:11px;width: 50px;color:white"><span style="background-color:red;font-size:10px;width: 50px;color:white">{{$issue->type}}</span></td>
            @endif
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->reportedId}}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->reportedName}}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->area}}</span></td>

            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->title}}</td>
            <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;"><?php echo nl2br($issue->agreedaction)?></span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{date('M/d/Y',strtotime($issue->created_at))}}</span></span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{date('M/d/Y',strtotime($issue->targetdate))}}</span></span></td>
            @if($issue->status == 'closed' && $issue->closed <= $date) <td class="text-center " style="background-color:#a6ff00;font-size:12px;width: 50px;color:white"><span style="background-color:#a6ff00;font-size:10px;width: 50px;color:black">{{$issue->status}}</span></td>
                @elseif($issue->status == 'closed' && $issue->closed > $date)
                <?php $issue->status='closed after due' ?>
                <td class="text-center " style="background-color:red;font-size:12px;width: 50px;color:white"><span style="background-color:red;font-size:10px;width: 50px;color:white">{{$issue->status}}</span></td>
                @else
                <td class="text-center " style="background-color:blue;font-size:12px;width: 50px;color:white"><span style="background-color:blue;font-size:10px;width: 50px;color:white">{{$issue->status}}</span></td>

                @endif

                <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->processstage}}</span></td>
                <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->impl_status}}</span></td>
                <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;"><?php echo nl2br($issue->observation )?></span></td>
                <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->responsibility}}</span></td>
                <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;"><?php echo nl2br($issue->correction )?></span></td>
                <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;"><?php echo nl2br($issue->rootcause )?></span></td>
                <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;"><?php echo nl2br($issue->correctiveaction )?></span></td>
                <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;"><?php echo nl2br($issue->remarks)?></span></td>
                <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;"><?php echo nl2br($issue->action_remarks)?></span></td>
                <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->postedby}}</span></td>
                <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->updated_at}}</span></td>
                <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->approvedby}}</span></td>
                <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->closedby}}</span></td>

        </tr>
    </tbody>
    @endforeach
</table>
@endif


@if(count($issues2)>0)
<?php //dd($data); ?>
<table style="display:none;" class="table table-responsive table-bordered table-striped table-vcenter table2" id="headerTable3">
    <thead>
        <tr>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Id</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Department</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Type</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Reported by Id</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Reported by name</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Area</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Title</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Post Date</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Target Date</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Status</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Process<br>Stage</span></th>
            <th class="text-center" style="font-size:12px;width: 200px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Action<br>Status</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Observation</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Responsibility</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Agreed action</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Correction</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Root Cause</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Corrective Action</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Remarks</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Action Review Remarks</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Posted By</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Last Reviewed</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Approved By</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Issue Closed By</span></th>

        </tr>

    </thead>
    <?php $no=0;?>
    @foreach($issues2 as $issue)
    <?php

                    $rr=$issue->id;
                    // $cats=$issue->Area;
                    // $cat=strtoupper(substr($cats,0,3));
                    $date = $issue->targetdate;
                    $today = Date("Y-m-d");
                    $color = null ;?>

    <tbody>
        <tr>

            <td class="text-center " style="font-size:12px;width: 50px;color:black"><span style="font-size:10px;width: 50px;color:black">{{$issue->issueid}}</span></td>

            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->department}}</span></td>

            @if($issue->type=="Low")
            <td class="text-center " style="background-color:#baeb34;font-size:11px;width: 50px;color:black"><span style="background-color:#baeb34;font-size:10px;width: 50px;color:black">{{$issue->type}}</span></span></td>
            @elseif($issue->type=="Moderate"||$issue->type=="Medium")
            <td class="text-center " style="background-color:#f29018;;font-size:11px;width: 50px;color:black"><span style="background-color:#f29018;;font-size:10px;width: 50px;color:black">{{$issue->type}}</span></span></td>
            @else
            <td class="text-center " style="background-color:red;font-size:11px;width: 50px;color:white"><span style="background-color:red;font-size:10px;width: 50px;color:white">{{$issue->type}}</span></td>
            @endif
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->reportedId}}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->reportedName}}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->area}}</span></td>

            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->title}}</td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{date('M/d/Y',strtotime($issue->created_at))}}</span></span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{date('M/d/Y',strtotime($issue->targetdate))}}</span></span></td>
            @if ($issue->status =='open' && $today <= $date) <td class="text-center " style="background-color:blue;font-size:12px;width: 50px;color:white"><span style="background-color:blue;font-size:10px;width: 50px;color:white">{{$issue->status}}</span></td>
                @elseif ($issue->status == 'open' && $today > $date)
                <?php $issue->status='open,due' ?>
                <td class="text-center " style="background-color:red;font-size:12px;width: 50px;color:white"><span style="background-color:red;font-size:10px;width: 50px;color:white">{{$issue->status}}</span></td>
                @elseif($issue->status == 'closed' && $today <= $date) <td class="text-center " style="background-color:#a6ff00;font-size:12px;width: 50px;color:black"><span style="background-color:#a6ff00;font-size:10px;width: 50px;color:black">{{$issue->status}}</span></td>
                    @elseif($issue->status == 'closed' && $today > $date)
                    <?php $issue->status='closed after due' ?>
                    <td class="text-center " style="background-color:#7eab44;font-size:12px;width: 50px;color:black"><span style="background-color:#7eab44;font-size:10px;width: 50px;color:black">{{$issue->status}}</span></td>
                    @else
                    <td class="text-center " style="background-color:red;font-size:12px;width: 50px;color:black"><span style="background-color:white;font-size:10px;width: 50px;color:black">{{$issue->status}}</span></td>

                    @endif

                    <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->processstage}}</span></td>
                    <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->impl_status}}</span></td>
                    <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;"><?php echo nl2br($issue->observation )?></span></td>
                    <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->responsibility}}</span></td>
                    <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;"><?php echo nl2br($issue->agreedaction)?></span></td>
                    <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;"><?php echo nl2br($issue->correction )?></span></td>
                    <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;"><?php echo nl2br($issue->rootcause )?></span></td>
                    <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;"><?php echo nl2br($issue->correctiveaction )?></span></td>
                    <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;"><?php echo nl2br($issue->remarks)?></span></td>
                    <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;"><?php echo nl2br($issue->action_remarks)?></span></td>
                    <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->postedby}}</span></td>
                    <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->updated_at}}</span></td>
                    <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->approvedby}}</span></td>
                    <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->closedby}}</span></td>

        </tr>
    </tbody>
    @endforeach
</table>
@endif


@if(count($issues3)>0)
<?php //dd($data); ?>
<table style="display:none;" class="table table-responsive table-bordered table-striped table-vcenter table2" id="headerTable4">
    <thead>
        <tr>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Id</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Area</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Department</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Type</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Reported by Id</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Reported by name</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Title</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Post Date</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Target Date</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Status</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Process<br>Stage</span></th>
            <th class="text-center" style="font-size:12px;width: 200px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Action<br>Status</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Observation</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Responsibility</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Agreed action</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Correction</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Root Cause</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Corrective Action</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Attachments (Issue)</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Attachments (Action)</span> </th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Remarks</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Action Review Remarks</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Posted By</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Last Reviewed</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Approved By</span></th>
            <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Issue Closed By</span></th>

        </tr>

    </thead>
    <?php $no=0;?>
    @foreach($issues3 as $issue)
    <?php

                    $rr=$issue->id;
                    $date = $issue->targetdate;
                    $today = Date("Y-m-d");
                    $color = null ;?>

    <tbody>
        <tr>
            @if($issue->Area=="Internal Audit")
            <td class="text-center" style="font-size:11px;width: 50px;color:#0033cc"><span style="font-size:11px;width: 50px;color:#0033cc">{{$issue->issueid}}</span></td>
            @elseif($issue->Area=="External Audit")
            <td class="text-center " style="font-size:11px;width: 50px;color:orange"><span style="font-size:11px;width: 50px;color:orange">{{$issue->issueid}}</span></td>
            @elseif($issue->Area=="Management Review")
            <td class="text-center " style="font-size:11px;width: 50px;color:#33cc33"><span style="font-size:11px;width: 50px;color:#33cc33">{{$issue->issueid}}</span></td>
            @elseif($issue->Area=="Corrective Action")
            <td class="text-center " style="font-size:11px;width: 50px;color:#ff0066"><span style="font-size:11px;width: 50px;color:#ff0066">{{$issue->issueid}}</span></td>
            @else
            <td class="text-center " style="font-size:12px;width: 50px;color:black"><span style="font-size:11px;width: 50px;color:black">{{$issue->issueid}}</span></td>
            @endif

            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$issue->Area}}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$issue->department}}</span></td>
            @if($issue->type=="Low")
            <td class="text-center " style="background-color:#baeb34;font-size:11px;width: 50px;color:black"><span style="background-color:#baeb34;font-size:11px;width: 50px;color:black">{{$issue->type}}</span></span></td>
            @elseif($issue->type=="Moderate"||$issue->type=="Medium")
            <td class="text-center " style="background-color:#f29018;;font-size:11px;width: 50px;color:black"><span style="background-color:#f29018;;font-size:11px;width: 50px;color:black">{{$issue->type}}</span></span></td>
            @else
            <td class="text-center " style="background-color:red;font-size:11px;width: 50px;color:white"><span style="background-color:red;font-size:11px;width: 50px;color:white">{{$issue->type}}</span></td>
            @endif
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->reportedId}}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->reportedName}}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$issue->title}}</td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{date('M/d/Y',strtotime($issue->created_at))}}</span></span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{date('M/d/Y',strtotime($issue->targetdate))}}</span></span></td>
            @if ($issue->status =='open' && $today <= $date) <td class="text-center " style="background-color:blue;font-size:12px;width: 50px;color:white"><span style="background-color:blue;font-size:12px;width: 50px;color:white">{{$issue->status}}</span></td>
                @elseif ($issue->status == 'open' && $today > $date)
                <?php $issue->status='open,due' ?>
                <td class="text-center " style="background-color:red;font-size:12px;width: 50px;color:white"><span style="background-color:red;font-size:12px;width: 50px;color:white">{{$issue->status}}</span></td>
                @elseif($issue->status == 'closed' && $today <= $date) <td class="text-center " style="background-color:#a6ff00;font-size:12px;width: 50px;color:black"><span style="background-color:#a6ff00;font-size:12px;width: 50px;color:black">{{$issue->status}}</span></td>
                    @elseif($issue->status == 'closed' && $today > $date)
                    <?php $issue->status='closed after due' ?>
                    <td class="text-center " style="background-color:#7eab44;font-size:12px;width: 50px;color:black"><span style="background-color:#7eab44;font-size:12px;width: 50px;color:black">{{$issue->status}}</span></td>
                    @else
                    <td class="text-center " style="background-color:red;font-size:12px;width: 50px;color:black"><span style="background-color:white;font-size:12px;width: 50px;color:black">{{$issue->status}}</span></td>

                    @endif
                    <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$issue->processstage}}</span></td>
                    <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:11px;width: 50px;" {{$issue->impl_status}}</span></td>
                    <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:11px;width: 50px;"><?php echo nl2br($issue->observation )?></span></td>
                    <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$issue->responsibility}}</span></td>
                    <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:11px;width: 50px;"><?php echo nl2br($issue->agreedaction)?></span></td>
                    <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:11px;width: 50px;"><?php echo nl2br($issue->correction )?></span></td>
                    <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:11px;width: 50px;"><?php echo nl2br($issue->rootcause )?></span></td>
                    <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:11px;width: 50px;"><?php echo nl2br($issue->correctiveaction )?></span></td>
                    <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$issue->file }}</span></td>
                    <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$issue->action_file}}</span></td>
                    <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:11px;width: 50px;"><?php echo nl2br($issue->remarks)?></span></td>
                    <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:11px;width: 50px;"><?php echo nl2br($issue->action_remarks)?></span></td>
                    <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$issue->postedby}}</span></td>
                    <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$issue->updated_at}}</span></td>
                    <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$issue->approvedby}}</span></td>
                    <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$issue->closedby}}</span></td>

        </tr>
    </tbody>
    @endforeach
</table>
@endif




<script type="text/javascript">
    function fnCovertTable(type) {
        if (document.getElementById('tabselected').value == 'a1') {
            exptable = "headerTable1";
            pdfvalue = "tvalue1";
        } else if (document.getElementById('tabselected').value == 'a2') {
            exptable = "headerTable2";
            pdfvalue = "tvalue2";
        } else if (document.getElementById('tabselected').value == 'a3') {
            exptable = "headerTable3";
            pdfvalue = "tvalue3";
        } else if (document.getElementById('tabselected').value == 'a4') {
            exptable = "headerTable4";
            pdfvalue = "tvalue4";
        }
        tab_text = "<table border='1' style='border-collapse:collapse;' bordercolor='black'  align='center' >";
        var textRange;
        var j = 0;
        var i = 0;
        tab = document.getElementById(exptable); // id of table
        for (j = 0; j < tab.rows.length; j++) {
            tab_text = tab_text + "<tr>"
            for (i = 0; i < tab.rows[j].cells.length - 14; i++) {
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
            for (i = 0; i < tab.rows[0].cells.length - 14; i++) {
                tab_text = tab_text + "<th>"
                tab_text = tab_text + tab.rows[0].cells[i].innerHTML + "</th>";
            }
            tab_text = tab_text + "</tr>"
            for (j = 1; j < tab.rows.length; j++) {
                tab_text = tab_text + "<tr>"
                for (i = 0; i < tab.rows[j].cells.length - 14; i++) {
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
