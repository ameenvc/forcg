@extends('pages.datatables')
@section('exportadd')
<div class="input-group input-group-sm">
    <a href="" class="btn btn-sm btn-dual" style="margin-left:0px;" onclick="return fnCovertTable('print')">
        <i class="fa fa-print  fa-1x" style="color:grey"></i>
    </a>
</div>
<div class="input-group input-group-sm">
    <button type="button" class=" btn-sm btn-dual btn btn-default dropdown-toggle mr-0 mb-0" style="margin-left:8px; " data-toggle="dropdown">
        <i class="fa fa-file-export fa-1x" style="color:grey;"></i>
    </button>
    <ul class="dropdown-menu" role="menu" style="min-width:10px;text-align:center;">
        <li>
            <!--change route as below-->
            <form method="post" action="{{url('/user/convert/file/'.'tvalue1')}}" style="margin-bottom: 0px">
                @csrf
                <button onclick="return fnCovertTable('pdf')" style="width:60px;  font-size:10px;border:none;background-color:white;">Pdf</i></button>
                <!--change hidden input type id as below-->
                <input type="hidden" id="tvalue1" name="tvalue1" />
            </form>
        </li>
        <li><button onclick="fnCovertTable('excel')" style="width:60px;  font-size:10px;border:none;background-color:white;">Excel</button></li>
        <li><button onclick="fnCovertTable('word')" style="width:60px;  font-size:10px;border:none;background-color:white;">Word</button></li>
    </ul>
</div>
@endsection
@section('content')
<style>
    @media screen and (max-width: 1800px) {
        .top-right {
            position: absolute;
            top: -10px;
            margin-left:34px !important;
        }
        
      }
        @media screen and (max-width: 1400px) {
        .top-right {
            position: absolute;
            top: -10px;
            margin-left:30px !important;
        }
      }
        @media screen and (max-width: 1200px) {
        .top-right {
            position: absolute;
            top: -10px;
            margin-left:30px !important;
        }
      }
        @media screen and (max-width: 1020px) {
        .top-right {
            position: absolute;
            top: -10px;
            margin-left:30px !important;

        }
      }

        @media screen and (max-width: 900px) {
        .top-right {
            position: absolute;
            top: -10px;
            margin-left:25px !important;
        }
    }
      @media screen and (max-width: 600px) {
        .top-right {
            position: absolute;
            top: -10px;
            margin-left:25px !important;
        }
      }

      @media screen and (max-width: 300px) {
        .top-right {
            position: absolute;
            top: -10px;
            margin-left:30px !important;
        }
      }
        .avatar-dropdown-content{
            margin-left:120%;
        }
        table tr:first-child .avatar-dropdown-content{
            top: 0;
            margin-left:120%;
        }
        table tr:last-child .avatar-dropdown-content{
            top: -170%;
            margin-left:120%;
        }
    .status-warning {
        color: #af6f1e;
        background-color: #fbdece;
        border-color: #fbf4ea;
    }
    .status-red {
        color: #af1e1e;
        background-color: #f8d4d4;
        border-color: #fbeaea;
    }

    .status-blue {
        color: #25499c;
        background-color: #d9e2f6;
        border-color: #d9e2f6;
    }

    .status {
        display: inline-block;
        padding: 7px 5px 5px 7px;
        font-size: 75%;
        font-weight: 600;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.25rem;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    .scrollable {
        height: 100%;
        overflow-x: scroll;
        overflow-y: scroll;
    }
    #content{
        height:550px !important;
    }
    .disabled-link {
        pointer-events: none;
        color: #999;
        text-decoration: none;
    }
    
</style>
<link rel="stylesheet" href="{{ asset('css/register_stage.css') }}">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/plugins/summernote/summernote-bs4.min.js') }}"></script>
<?php
    $data1=[];
    $data2=[];
    ?>
<input type="hidden" value="a1" id="tabselected">
<div class="content" style="width:100%;">
    <div class="block block-rounded" >

        <div class="block-content block-content-full">
            <div class="scrollable table-responsive" id="content">
                <table class="move" width="15%" style="margin-left:670px">
                    <tr>
                        <th>
                            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-alt" style="margin-right:-200px;margin-bottom:-250px;">
                                    <li class="breadcrumb-item" style="margin-left:-600px;margin-top:-4px">Compliance Management</li>
                                    <li class="breadcrumb-item" aria-current="page" style="margin-top:-4px">
                                        <a class="link-fx" href="">License/Permits</a>
                                    </li>
                                </ol>
                            </nav>
                        </th>
                    </tr>
                </table>
                @if(Session::has('msg'))
                <div class="alert alert-primary">
                    <a class="close" data-dismiss="alert" style="cursor: pointer">×</a>
                    {!!Session::get('msg')!!}
                </div>
                @endif
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full" id="tabledata">

                    <thead style="position: sticky; top: 0;z-index:5;background-color:#ffffff;">
                        <tr>
                            <td class="text-center" style="font-size:10px;width:5%;text-transform:capitalize;">
                                <span style="position:relative;top:2px;font-weight:600;">Slno</span>
                            </td>
                            <td class="text-center" style="font-size:10px;width:5%;text-transform:capitalize;">
                                <span style="position:relative;top:2px;font-weight:600;">ID</span>
                            </td>
                            <td class="text-center" style="font-size:10px;width:20%;text-transform:capitalize;">
                                <span style="position:relative;top:2px;font-weight:600;">License/Permit Name</span>
                            </td>
                            <td class="text-center" style="font-size:10px;width:10%;text-transform:capitalize;">
                                <span style="position:relative;top:2px;font-weight:600;">License Number</span>
                            </td>
                            <td class="text-center" style="font-size:10px;text-transform:capitalize;width:5%;">
                                <i class="far fa-user" style="font-size:14px;">
                            </td>
                            <td class="text-center" style="width:25%;text-transform:capitalize;font-size:10px;"><span style="position:relative;top:2px;font-weight:600;">Responsible Person</span></td>
                            <td class="text-center" style="width:10%;text-transform:capitalize;font-size:10px;"><span style="position:relative;top:2px;font-weight:600;">Expiry Date</span></td>
                            <td class="text-center" style="width:10%;text-transform:capitalize;font-size:10px;"><span style="position:relative;top:2px;font-weight:600;">Status</span></td>
                            <td class="text-center" style="font-size:10px;width:5%;text-transform:capitalize;">
                                <span style="position:relative;top:2px;font-weight:600;">Actions</span>
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $no=0; $today = Date("Y-m-d"); ?>
                    @foreach($data as $d)
                    <tr>
                        <td class="text-center" style="font-size:10px;">{{++$no}}</td>
                        <td class="text-center" style="font-size:10px;">{{ $d->compliance_auth_id }}</td>
                        <td class="text-center" style="font-size:10px;">{{ $d->name }}</td>
                        <td class="text-center" style="font-size:10px;">{{ $d->licensenumber }}</td>
                        <td>
                            @php
                                $responsibles = explode(',',$d->responsible);
                                $first_responsible = App\User::where('id',$responsibles[0])->first();
                            @endphp
                            <div class="main-img-aud-container">
                                @if($first_responsible)
                                    <img src="{{($first_responsible->profile_picture && File::exists(public_path('/storage/profile/'.$first_responsible->profile_picture))) ? asset('storage/profile/'.$first_responsible->profile_picture) : asset('/images/avatar10.jpg')}}" class="avatar" title="{{$first_responsible->name}}"/>
                                    @if(count($responsibles) > 1)
                                    <div class="top-right">
                                        <div class="avatar-dropdown">
                                            <div style="top:2px;" class="avatar-dot text-center">+{{count($responsibles) - 1}} </div>
                                            <div class="avatar-dropdown-content">
                                                @foreach($responsibles as $responsible_arr)
                                                    @if($responsible_arr!=$first_responsible->id)
                                                        @php $responsible_lists = App\User::where('id',$responsible_arr)->first(); @endphp
                                                        @if($responsible_lists)
                                                            <a href="#">
                                                                <div class="row">
                                                                    <div class="col-md-3">
                                                                        <img style="margin-bottom:5px;" src="{{($responsible_lists->profile_picture && File::exists(public_path('/storage/profile/'.$responsible_lists->profile_picture))) ? asset('storage/profile/'.$responsible_lists->profile_picture) : asset('/images/avatar10.jpg')}}" class="avatar" title="{{$responsible_lists->name}}"/>
                                                                    </div>
                                                                    <div class="col-md-9 text-left">
                                                                        <span style="top: 50%;position: absolute;vertical-align:middle;margin-top:-1em;">{{ucfirst($responsible_lists->name)}}</span>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @endif
                            </div>
                        </td>
                        <td style="font-size:10px;" class="text-center">
                            @php
                                $responsibles = explode(',',$d->responsible);
                            @endphp 
                            @foreach($responsibles as $res)
                                @php $r = App\User::where('id',$res)->value('name'); @endphp
                            <em class="text-muted " style="font-size:10px;">{{ $r ? ucwords($r) : '' }},</em>
                            @endforeach
                        </td>
                        <td style="font-size:10px;" class="text-center">
                            <em class="text-muted " style="font-size:10px;">{{ date('d-m-Y',strtotime($d->expirydate)) }}</em>
                        </td>
                        <td style="font-size:13px;" class="text-center">
                            @if($d->status == 'Expired')
                            <span class="status status-red">Expired</span>
                            @elseif($d->status == 'Active')
                            <span class="status status-blue">Active</span>
                            @else
                            <span class="status status-warning">Near to expiry</span>
                            @endif
                        </td>
                        <div class="dropdown font-size-sm">
                            <td>
                                <div class="btn-group m-auto d-flex justify-content-center">
                                    <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" data-placement="top" title="Actions" id="drop{{$d->id}}">
                                        <i class="fa fa-fw fa-ellipsis-v" style="font-size:12px;cursor:pointer"></i>
                                    </a>
                                    @php $addby = App\User::where('id',$d->postedby)->value('email'); @endphp
                                    <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownMenu{{ $d->id }}">
                                        @if(Gate::allows('edit-compliance') || in_array(Auth::user()->email, $coordinatorsArray) || Auth::user()->email === $addby)         
                                        <a href="{{route('user.compliance.editlicensepermit',['id' => $d->id])}}" class="dropdown-item" style="font-size:10px;">
                                            <i class="fa fa-1x fa-edit" style="color:grey;font-size:10px;"></i><span class="ml-1">Edit</span>
                                        </a>
                                        @else
                                        <a class="dropdown-item disabled-link" style="font-size:10px;">
                                            <i class="fa fa-1x fa-edit" style="font-size:10px;"></i><span class="ml-1">Edit</span>
                                        </a>
                                        @endif
                                        @if(Gate::allows('delete-compliance') || in_array(Auth::user()->email, $coordinatorsArray) || Auth::user()->email === $addby)
                                        <a href="#" style="font-size:10px" class="dropdown-item" onclick="deletelicense('{{$d->id}}')">
                                            <i class="fas fa-1x fa-trash" style="color:red;font-size:10px;"></i><span class="ml-1">Delete</span>
                                        </a>
                                        @else
                                        <a style="font-size:10px" class="dropdown-item disabled-link">
                                            <i class="fas fa-1x fa-trash" style="font-size:10px;"></i><span class="ml-1">Delete</span>
                                        </a>
                                        @endif
                                        <a href="{{route('user.compliance.renewlicensepermit',['id' => $d->id])}}" class="dropdown-item" style="font-size:10px;">
                                            <i class="fa fa-1x fa-sync" style="color:grey;font-size:10px;"></i><span class="ml-1">Renew License</span>
                                        </a>
                                    </div>
                                    <a href="#" class="btn btn-sm btn-alt-primary" data-id="{{ $d->id }}" data-toggle="tooltip" data-placement="top" title="View" onclick="ViewBtn({{$d->id}})">
                                        <i class="far fa-eye text-center " style="font-size:10px;cursor:pointer"></i>
                                    </a>
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-sm btn-alt-primary" data-toggle="tooltip" data-target="#exampleModal{{$d->id}}" data-placement="top" title="View Attachments" onclick="ViewBtn1({{$d->id}})"><i class="fas fa-paperclip" style="font-size:10px;cursor:pointer"></i></button>
                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModal{{$d->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h3 class="modal-title">Attachment</h3>
                                                    <a class="close" data-dismiss="modal" style="cursor: pointer">×</a>
                                                </div>
                                                <table class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                                                    <div class="row justify-content-center">
                                                        <thead>
                                                            <tr class="table-secondary">
                                                                <th>S.No</th>
                                                                <th>Attachment</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <?php $o=0; ?>
                                                        @if($d->file!='')
                                                            <tbody>
                                                                @php $files = explode(',',$d->file); @endphp
                                                                @foreach($files as $f)
                                                                <tr>
                                                                    <td style="font-size:10px; ;">{{ $o=$o+1}}</td>
                                                                    <td style="font-size:15px;width: 100%;">{{ $f}}</td>
                                                                    <td>
                                                                        <a style="margin-left:70px;font-size:11px;" class="btn btn-sm btn-outline-primary border-0" href="{{asset('storage/compliance_files/'.$f)}}" download="{{$f}}"><i class="fa fa-download" aria-hidden="true"></i></a>
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        @else
                                                        <div class="row justify-content-center">
                                                            <h2>No Files</h2>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </table>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-dark btn-sm" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                                <div id="viewModal{{$d->id}}" class="modal fadebd-example-modal-lg scrollable" tabindex="-1" role="dialog" aria-hidden="false">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <h2 class="modal-title">Detailed View</h2>
                                            </div>
                                            <div class="table-responsive">
                                            <table class="table table-striped">
                                                <tr>
                                                    <th style="width:70px">ID</th>
                                                    <td>{{ $d->compliance_auth_id }}</td>
                                                </tr>
                                                <tr>
                                                    <th style="width:70px">License/Permit Name</th>
                                                    <td>{{ $d->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>License Number</th>
                                                    <td>{{ $d->licensenumber }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Responsible Person</th>
                                                    <td>
                                                        @php $responsibles = explode(',',$d->responsible); @endphp
                                                        @foreach($responsibles as $res)
                                                        @php $r = App\User::where('id',$res)->value('name'); @endphp
                                                        {{ $r ? ucwords($r) : '' }},
                                                        @endforeach
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Expiry Date</th>
                                                    <td>{{ date('d-m-Y',strtotime($d->expirydate))}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Effective Date</th>
                                                    <td>{{ $d->effectivedate ? date('d-m-Y',strtotime($d->effectivedate)):''}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Status</th>
                                                    <td>
                                                        @if($d->status == 'Expired')
                                                        <span class="status status-red">Expired</span>
                                                        @elseif($d->status == 'Active')
                                                        <span class="status status-blue">Active</span>
                                                        @else
                                                        <span class="status status-warning">Near to expiry</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Renewal Notification Interval</th>
                                                    <td>{{$d->frequency}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Renewal Requirements</th>
                                                    <td>
                                                        {!! $d->renewal_requirements !!}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th>Associated Regulations</th>
                                                    <td>
                                                        {!! $d->associated_regulations !!}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    @php $p = App\User::where('id',$d->postedby)->value('name'); @endphp
                                                    <th>Added by</th>
                                                    <td>{{$p}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Added date</th>
                                                    <td>{{date('d-m-Y',strtotime($d->created_at))}}</td>
                                                </tr>
                                                <tr>
                                                    @php $u = App\User::where('id',$d->updatedby)->value('name'); @endphp
                                                    @if($u)
                                                    <th>Updated by</th>
                                                    <td>{{$u}}</td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    @php $renewby = App\User::where('id',$d->renewedby)->value('name'); @endphp
                                                    @if($renewby)
                                                    <th>Renewed by</th>
                                                    <td>{{$renewby}}</td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    @if($d->renewed_at)
                                                    <th>Renewed date</th>
                                                    <td>{{date('d-m-Y',strtotime($d->renewed_at))}}</td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th>Attachments</th>
                                                    <td>
                                                        <div>
                                                            <button data-toggle="modal"  data-target="#viewModal5{{$d->id}}"  data-id="{{ $d->id }}" class="btn btn-secondary btn-small" style="font-size:13px;width: 150px;"><strong>View Attachment</strong></button>
                                                            <div id="viewModal5{{$d->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                                                <div class="modal-dialog modal-lg">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h3 class="modal-title">Attachment</h3>
                                                                            <a class="close" data-dismiss="modal" style="cursor: pointer">×</a>
                                                                        </div>
                                                                        <table class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                                                                            <div class="row justify-content-center">
                                                                                <thead>
                                                                                    <tr class="table-secondary">
                                                                                        <th>S.No</th>
                                                                                        <th>Attachment</th>
                                                                                        <th></th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <?php $o=0; ?>
                                                                                @if($d->file!='')
                                                                                    <tbody>
                                                                                        @php $files = explode(',',$d->file); @endphp
                                                                                        @foreach($files as $f)
                                                                                        <tr>
                                                                                            <td style="font-size:10px; ;">{{ $o=$o+1}}</td>
                                                                                            <td style="font-size:15px;width: 100%;">{{ $f}}</td>
                                                                                            <td>
                                                                                                <a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success" href="{{asset('storage/compliance_files/'.$f)}}" download="{{$f}}">Download</a>
                                                                                            </td>
                                                                                        </tr>
                                                                                        @endforeach
                                                                                    </tbody>
                                                                                @else
                                                                                <div class="row justify-content-center">
                                                                                    <h2>No Files</h2>
                                                                                </div>
                                                                                @endif
                                                                            </div>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                </div>
                             
                            </td>
                        </div>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- END Dynamic Table Full -->
</div>
<!-- Apps Modal -->
<!-- Opens from the modal toggle button in the header -->
<div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="block block-rounded block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Options</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content block-content-full">
                    <div class="row gutters-tiny">
                        <div class="col-6">
                            <!-- Products -->
                            <a class="block block-rounded block-link-shadow bg-body" href="/user/compliancemgmt">
                                <div class="block-content text-center">
                                    <i class="far fa-list-alt fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        <br />
                                        Register
                                    </p>
                                </div>
                            </a>
                            <!-- END Products -->
                        </div>
                        <div class="col-6">
                            <!-- Sales -->
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/compliance/myactions">
                                <div class="block-content text-center">
                                    <i class="fas fa-clone fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        <br>My Actions
                                    </p>
                                </div>
                            </a>
                            <!-- END Sales -->
                        </div>
                        <div class="col-6">
                            <!-- Payments -->
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href='/user/compliance/companalysis'>
                                <div class="block-content text-center">
                                    <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Analysis
                                    </p>
                                </div>
                            </a>
                            <!-- END Payments -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Ends App Model -->
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    function ViewBtn(id) {
        $('#viewModal' + id).modal('show');
    }
    function ViewBtn1(id) {
        $('#exampleModal' + id).modal('show');
    }
</script>
<script>
    function deletelicense(id) {
        if (confirm('Are you sure you want to delete this?')) {
            $.ajax({
                type: 'POST',
                url: "{{ route('user.compliance.deletelicensepermit') }}",
                data: {
                    id: id,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if(response.status == 200)
                    {
                        window.location.reload();
                    }
                    else if(response.status==404){
                        alert('License/Permit has not been deleted successfully.')
                    }
                },
                error: function(error) {
                    // Handle error, e.g., show an error message
                    alert('License/Permit has not been deleted successfully.')
                }
            });
        }
    }
</script>