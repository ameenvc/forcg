@extends('superadmin.datatable')

@section('content')
  <!-- Main Container -->
  
<?php
date_default_timezone_set("Asia/Kolkata");
$j=0;
$i=0;
?>
<?php $total_logins=0; ?>
   <!-- Services -->

   <div class="bg-body-light">
                    <div class="content content-full">
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
                           
                            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-alt">
                                <li class="breadcrumb-item">Control Device Logins</li>
                                   
                                    <li class="breadcrumb-item" aria-current="page">
                                    <a id="a1" onclick="" class="link-fx" href="/superadmin/home">Back</a>
                                    </li>
                                </ol>
                            </nav>
                            <div class="mt-3 mt-sm-0 ml-sm-3">
                                <div class="dropdown d-inline-block" style="margin-top:25px">
                                <form action="{{route('superadmin.updatelogins')}}" method="post">
                                @csrf
                                <div class="form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" style="width:150px;font-size:13px;" id="devices" name="devices" placeholder="Enter Logins Per ID">
                                    <div class="input-group-append">
                                        <button type="submit"  class="btn btn-dark" style="font-size:13px;" >Submit</button>
                                    </div>
                                </div>
                                </div>
                                </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                    <!--end of setting logins per email-id-->
                   
                  

                <!-- Page Content -->
                <div class="content"  style="width:92%;margin-left:40px;">
                    <!-- Dynamic Table Full -->
                    <div class="block block-rounded" >
                        {{-- <div class="block-header">
                            <h3 class="block-title">Dynamic Table <small>Full</small></h3>
                        </div> --}}
                        <div class="block-content block-content-full" style=" overflow-y:scroll;height:450px;display:block;">
                            <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->                            <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                                <thead>
                                        <tr>
                                            <th style="font-size:10px;" class="text-center" style="width: 100px;">SL No.</th>
                                            <th style="font-size:10px;" class="d-none d-sm-table-cell text-center">Name</th>
                                            <th style="font-size:10px;" class="d-none d-sm-table-cell text-center">Email</th>
                                            <th style="font-size:10px;" class="text-center" >Type</th>
                                            <th style="font-size:10px;" class="text-center" >Current Devices Logged</th>
                                            <th style="font-size:10px;" class="text-center" >Check</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @if(count($admins)>0)
                                    @foreach($admins as $admin)
                                        <tr>
                                            <td class="text-center font-size-sm">
                                                    <span style="font-size:10px;">1</span>
                                            </td>
                                            <td class="text-center font-size-sm">
                                                    <span style="font-size:10px;">{{$admin->name}}</span>
                                            </td>
                                            <td class="text-center font-size-sm">
                                                    <span style="font-size:10px;">{{$admin->email}}</span>
                                            </td>
                                            <td class="text-center font-size-sm">
                                                    <span style="font-size:10px;">Admin</span>
                                            </td>
                                            <td class="text-center font-size-sm">
                                                    <span style="font-size:10px;">{{$admin->logdevices}}</span>
                                            </td>
                                            <td class="text-center font-size-sm">
                                            @if($admin->logdevices>0)
                                                <button type="button" style="font-size:10px;" class="btn btn-alt-success push" data-toggle="modal" data-target="#modal-block-fadein">Check</button>
                                            @else
                                                <button type="button" style="font-size:10px;" disabled class="btn btn-alt-primary push" data-toggle="modal" data-target="#modal-block-fadein">Check</button>
                                            @endif
                                          

                                            <!-- Fade In Block Modal -->
        @if($admin->logdevices>0)
        <div class="modal fade" id="modal-block-fadein" tabindex="-1" role="dialog" aria-labelledby="modal-block-fadein" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="block block-rounded block-themed block-transparent mb-0">
                        <div class="block-header bg-primary-dark">
                            <h3 class="block-title">Current Deices Logged</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                    <i class="fa fa-fw fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content font-size-sm">
                        <div class="table-responsive">
                                <table class="table table-borderless table-striped table-vcenter">
                                    <thead>
                                        <tr>
                                            <th style="font-size:10px;" class="d-none d-sm-table-cell text-center">Name</th>
                                            <th style="font-size:10px;" class="d-none d-sm-table-cell text-center">Email</th>
                                            <th style="font-size:10px;" class="text-center" >Date</th>
                                            <th style="font-size:10px;" class="text-center" >Time</th>
                                            <th style="font-size:10px;" class="text-center" >Browser</th>
                                            <th style="font-size:10px;" class="text-center" >Ip</th>
                                            <th style="font-size:10px;" class="text-center" >Device</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    
                                    @foreach($logs as $log)
                                    @if($log->email==$admin->email)
                                        <tr>
                                            
                                            <td class="text-center font-size-sm">
                                                    <span style="font-size:10px;">{{$log->name}}</span>
                                            </td>
                                            <td class="text-center font-size-sm">
                                                    <span style="font-size:10px;">{{$log->email}}</span>
                                            </td>
                                            <td class="text-center font-size-sm">
                                                    <span style="font-size:10px;">{{$log->last_login_date}}</span>
                                            </td>
                                            <td class="text-center font-size-sm">
                                                    <span style="font-size:10px;">{{$log->last_login_time}}</span>
                                            </td>
                                            <td class="text-center font-size-sm">
                                                    <span style="font-size:10px;">{{$log->browser}}</span>
                                            </td>
                                            <td class="text-center font-size-sm">
                                                    <span style="font-size:10px;">{{$log->last_ip}}</span>
                                            </td>
                                            <td class="text-center font-size-sm">
                                                    <span style="font-size:10px;">{{$log->device}}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <?php $i=$i+1; ?>
                                    @endif
                                    @if($i==$admin->logdevices)
                                    <?php $i=0; ?>
                                    @break;
                                    @endif
                                    @endforeach
                                </table>
                            </div>
                        </div>
                        <div class="block-content block-content-full text-right border-top">
                            <button type="button" class="btn btn-alt-primary mr-1" data-dismiss="modal" style="font-size:10px;">Close</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal" style="font-size:10px;">Ok</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Fade In Block Modal -->
        @endif
        </td>
    </tr>
@endforeach
@endif

<!--display users logins per id -->
@if(count($users)>0)
<?php $j=1; ?>
                                    @foreach($users as $user)
                                        <tr>
                                            <td class="text-center font-size-sm">
                                                    <span style="font-size:10px;">{{$j+=1}}</span>
                                            </td>
                                            <td class="text-center font-size-sm">
                                                    <span style="font-size:10px;">{{$user->name}}</span>
                                            </td>
                                            <td class="text-center font-size-sm">
                                                    <span style="font-size:10px;">{{$user->email}}</span>
                                            </td>
                                            <td class="text-center font-size-sm">
                                                    <span style="font-size:10px;">User</span>
                                            </td>
                                            <td class="text-center font-size-sm">
                                                    <span style="font-size:10px;">{{$user->logdevices}}</span>
                                            </td>
                                            <td class="text-center font-size-sm">
                                            @if($user->logdevices>0)
                                                <button style="font-size:10px;" type="button" class="btn btn-alt-success push" data-toggle="modal" data-target="#show{{$user->id}}">Check</button>
                                            @else
                                                <button style="font-size:10px;" type="button" disabled class="btn btn-alt-primary push" data-toggle="modal" data-target="#show{{$user->id}}">Check</button>
                                            @endif
                                          

                                            <!-- Fade In Block Modal -->
        @if($user->logdevices>0)
        <div class="modal fade" id="show{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-block-fadein" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="block block-rounded block-themed block-transparent mb-0">
                        <div class="block-header bg-primary-dark">
                            <h3 class="block-title">Current Deices Logged</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                    <i class="fa fa-fw fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content font-size-sm">
                        <div class="table-responsive">
                                <table class="table table-borderless table-striped table-vcenter">
                                    <thead>
                                        <tr>
                                            <th style="font-size:10px;" class="d-none d-sm-table-cell text-center">Name</th>
                                            <th style="font-size:10px;" class="d-none d-sm-table-cell text-center">Email</th>
                                            <th style="font-size:10px;" class="text-center" >Date</th>
                                            <th style="font-size:10px;" class="text-center" >Time</th>
                                            <th style="font-size:10px;" class="text-center" >Browser</th>
                                            <th style="font-size:10px;" class="text-center" >Ip</th>
                                            <th style="font-size:10px;" class="text-center" >Device</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    
                                    @foreach($logs as $log)
                                    @if($log->email==$user->email)
                                        <tr>
                                            
                                            <td class="text-center font-size-sm">
                                                    <span style="font-size:10px;">{{$log->name}}</span>
                                            </td>
                                            <td class="text-center font-size-sm">
                                                    <span style="font-size:10px;">{{$log->email}}</span>
                                            </td>
                                            <td class="text-center font-size-sm">
                                                    <span style="font-size:10px;">{{$log->last_login_date}}</span>
                                            </td>
                                            <td class="text-center font-size-sm">
                                                    <span style="font-size:10px;">{{$log->last_login_time}}</span>
                                            </td>
                                            <td class="text-center font-size-sm">
                                                    <span style="font-size:10px;">{{$log->browser}}</span>
                                            </td>
                                            <td class="text-center font-size-sm">
                                                    <span style="font-size:10px;">{{$log->last_ip}}</span>
                                            </td>
                                            <td class="text-center font-size-sm">
                                                    <span style="font-size:10px;">{{$log->device}}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <?php $i=$i+1; ?>
                                    @endif
                                    @if($i==$user->logdevices)
                                    <?php $i=0; ?>
                                    @break;
                                    @endif
                                    @endforeach
                                </table>
                            </div>
                        </div>
                        <div class="block-content block-content-full text-right border-top">
                            <button type="button" class="btn btn-alt-primary mr-1" data-dismiss="modal" style="font-size:10px;">Close</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal" style="font-size:10px;">Ok</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Fade In Block Modal -->
        @endif
        </td>
    </tr>
@endforeach
@endif
</tbody>
                            </table>
                        </div>
                    </div>
                    <!-- END Dynamic Table Full -->

                   
                </div>
                <!-- END Page Content -->
            
            <!-- END Main Container -->


            <script type="text/javascript">
function check()
{
  var inc=document.getElementById('inc');
  var dec=document.getElementById('dec');
  if(inc.checked)
      dec.checked=false;
  else
      inc.checked=false;
}
function func()
{
    if(document.getElementById('inc').checked)
     return true;
    else if(document.getElementById('dec').checked) {
     if(document.getElementById('rem').innerHTML=="0"){
     alert("You cannot decrease the company size as company already reached specified size");
     return false;
     }
     else if(document.getElementById('size').value>parseInt(document.getElementById('rem').innerHTML,10))
     {
     alert("You cannot decrease the company size greater than the remaining size");
     return  false;
     }
     else
     return true;
    }
    else
        alert('please choose option');
    return false;
}
</script>
@endsection



