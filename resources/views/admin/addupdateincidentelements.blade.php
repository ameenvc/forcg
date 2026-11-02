@extends('admin.nav')
@section('content')
<div class="content">
    <!-- Full Table -->
    <div class="block block-rounded">
        <div class="block-header">
            <h3 class="block-title">MS.11.01 Incident Type Table</h3>
            <ol class="breadcrumb breadcrumb-alt">
                <li class="breadcrumb-item">Incident Report</li>
            </ol>
        </div>
        <div class="block-content block-content-full" style="width:84%;">
            <div class="table-responsive">
                @if(count($incidents)>0)
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                    <thead>
                        <tr>
                            <th class="text-left" style="width: 1%;font-size:10px;text-transform:capitalize">Sl.no</th>
                            <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Incident Type</th>
                            <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $number = 1; ?>
                        @foreach($incidents as $incident)
                        <?php
                            $opt=$incident->id.'1';
                            $inid=$incident->id.'2';
                            $num=$incident->id.'3';
                        ?>
                        {{csrf_field()}}
                        <tr> 
                            <?php $id = $incident->id ; ?>
                            <td style="font-size:10px" class="text-left">{{ $number++}}</td>
                            <form method="POST" action="{{url('/admin/updatedelete/incidenttype')}}">
                                @csrf
                                <input name="id" type="hidden" class="text-left" value="{{$incident->id}}" style="display:none">
                                <input name="opt" id="{{$opt}}" style="display:none" class="text-left">
                                <td><input class="text-left form-control border" name="updated" id="{{$inid}}" type="text" value="{{$incident->incident_type}}" readonly style="border:none;font-size:10px"></td>
                                <td class="text-left">
                                    <button id="{{$num}}" onclick="return writable('{{$inid}}','{{$num}}','{{$opt}}')" class="btn btn-sm btn-alt-primary" style="font-size:10px">Edit</button>
                                    <button class="btn btn-sm btn-alt-primary" style="font-size:10px" onclick="return  deleteconf('{{$opt}}')">Remove</button>
                                </td>
                            </form>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <h2 style="font-size:13px">No Incident Types</h2>
                @endif
            </div>
            <div class="row">
                <div class="col-sm-6"></div>
                <div class="col-sm-6 text-right">
                    <form method="post" action="{{url('/admin/addIncidentType')}}">
                        {{csrf_field()}}
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="example-text-input" placeholder="Enter Incident type" style="font-size:13px;" required name="incident_type">
                                <div class="input-group-append">
                                    <input type="submit" name="update" class="btn btn-primary" style="font-size:12px" value="Add">
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>        
    </div>
    <div class="block block-rounded">
        <div class="block-header">
            <h3 class="block-title">MS.11.02 Incident Prevention Method Table</h3>
            <ol class="breadcrumb breadcrumb-alt">
                <li class="breadcrumb-item">Incident Report</li>
            </ol>
        </div>
        <div class="block-content block-content-full" style="width:84%;">
            <div class="table-responsive">
                @if(count($incident_prevention)>0)
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                    <thead>
                        <tr>
                            <th class="text-left" style="width: 1%;font-size:10px;text-transform:capitalize">Sl.no</th>
                            <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Prevention Method</th>
                            <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php  $number=1  ?>
                        @foreach($incident_prevention as $ip)
                        <?php
                            $opt=$ip->id.'4';
                            $ipid=$ip->id.'5';
                            $num=$ip->id.'6';
                        ?> 
                        <tr style="background-color:#F2F3F4">
                            <?php $id = $ip->id?>
                            <td style="font-size:10px" class="text-left">{{ $number++ }}</td>
                            {{csrf_field()}}
                            <form method="POST" action="{{url('/admin/updatedelete/incidentprevention')}}">
                                @csrf
                                <input name="id" type="hidden" value="{{$ip->id}}" style="display:none" class="text-left">
                                <input name="opt" id="{{$opt}}" style="display:none" class="text-left">
                                <td>
                                    <input class="text-left form-control border" name="updated" id="{{$ipid}}" style="font-size:10px" type="text" value="{{$ip->incident_prevent}}" readonly style="border:none;width:400px">
                                </td>
                                <td class="text-left">
                                    <button id="{{$num}}" onclick="return writable('{{$ipid}}','{{$num}}','{{$opt}}')" class="btn btn-sm btn-alt-primary" style="font-size:10px">Edit</button>
                                    <button class="btn btn-sm btn-alt-primary" style="font-size:10px" onclick="return  deleteconf('{{$opt}}')">Remove</button>
                                </td>
                            </form>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <h2 style="font-size:13px">No Incident Prevention methods</h2>
                @endif
            </div>
            <div class="row">
                <div class="col-sm-6"></div>
                <div class="col-sm-6 text-right">
                    <form method="post" action="{{url('/admin/addIncidentPrevention')}}">
                        {{csrf_field()}}
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="example-text-input" style="font-size:13px;" placeholder="  Enter prevention Method" required name="incident_prevention">
                                <div class="input-group-append">
                                    <input type="submit" name="update" class="btn btn-primary" style="font-size:12px" value="Add">
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
        
    </div>
    <div class="block block-rounded">
        <div class="block-header">
            <h3 class="block-title">MS.11.03 Incident Protection Kit Table</h3>
            <ol class="breadcrumb breadcrumb-alt">
                <li class="breadcrumb-item">Incident Report</li>
            </ol>
        </div>
        <div class="block-content block-content-full" style="width:84%;">
            <div class="table-responsive">
            @if(count($incident_prevention)>0)
            <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                <thead>
                    <tr>
                        <th class="text-left" style="width: 2%;font-size:10px;text-transform:capitalize">Sl.no</th>
                        <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Protection Kit</th>
                        <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php  $number=1  ?>
                    @foreach($protection as $prot)
                    <?php
                        $opt=$prot->id.'7';
                        $ipid=$prot->id.'8';
                        $num=$prot->id.'9';
                    ?>
                    <tr style="background-color:#F2F3F4">
                        <?php $id = $ip->id; ?>
                        <td style="font-size:10px" class="text-left">{{ $number++ }}</td>

                        <form method="POST" action="{{url('/admin/updatedelete/incidentprotection')}}">
                            @csrf
                            <input name="id" class="text-left" type="hidden" value="{{$prot->id}}" style="display:none">
                            <input name="opt" class="text-left" id="{{$opt}}" style="display:none">
                            <td>
                                <input name="updated" id="{{$ipid}}" type="text" value="{{$prot->kit}}" class="text-left form-control border" readonly style="border:none;width:400px;font-size:10px">
                            </td>


                            <td class="text-left">
                                <button id="{{$num}}" onclick="return writable('{{$ipid}}','{{$num}}','{{$opt}}')" class="btn btn-sm btn-alt-primary" style="font-size:10px">Edit</button>
                                <button class="btn btn-sm btn-alt-primary" style="font-size:10px" onclick="return  deleteconf('{{$opt}}')">Remove</button>
                            </td>
                        </form>
                        {{csrf_field()}}
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <h2 style="font-size:13px">No Incident Prevention methods</h2>
            @endif
            </div>
            <div class="row">
                <div class="col-sm-6"></div>
                <div class="col-sm-6 text-right">
                    <form method="post" action="{{url('/admin/addIncidentProtection')}}">
                        {{csrf_field()}}
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="example-text-input" style="font-size:13px;" placeholder="Enter protection kit" required name="incident_protection">
                                <div class="input-group-append">
                                    <input type="submit" name="update" class="btn btn-primary" style="font-size:12px" value="Add">
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
        
    </div>
</div>
<script type="text/javascript">
    function writable(id, num, opt) {
        if (document.getElementById(num).innerHTML == "Edit") {
            document.getElementById(id).readOnly = false;
            document.getElementById(num).innerHTML = "Update";
            return false;
        } else {
            document.getElementById(opt).value = 1;
            return true;
        }
    }

    function deleteconf(id) {
        document.getElementById(id).value = 0;
        return confirm('This data will be deleted permanently,Press OK to continue')
    }

    function showhideAnc(disp, hide1, hide2) {
        document.getElementById(disp).style.display = "";
        sessionStorage.setItem("divtodisplay", disp);
        document.getElementById(hide1).style.display = "none";
        document.getElementById(hide2).style.display = "none";
    }

</script>
@endsection
