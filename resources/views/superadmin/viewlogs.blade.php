@extends('superadmin.datatable')

@section('content')
  <!-- Main Container -->
  <?php $total_logins=0; ?>
@if(count($users)>0)
@foreach($users as $user)
@if(Cache::has('user-is-online-'.$user->email))
<?php $total_logins=$total_logins+1; ?>
@endif
@endforeach 
@endif
@if(count($admins)>0)
@foreach($admins as $admin)
@if(Cache::has('user-is-online-'.$admin->email))
<?php $total_logins=$total_logins+1; ?>
@endif
@endforeach
@endif

                  <!-- END Hero -->
                  <div class="bg-body-light">
                    <div class="content content-full">
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
                           
                            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-alt">
                                <li class="breadcrumb-item">Admin/User Logs</li>
                                   
                                    <li class="breadcrumb-item" aria-current="page">
                                    <a id="a1" onclick="" class="link-fx" href="/superadmin/home">Back</a>
                                    </li>
                                </ol>
                            </nav>
                            <div class="mt-3 mt-sm-0 ml-sm-3">
                                <div class="dropdown d-inline-block">
                                        @if($total_logins>0)
                                            <button type="button"  class="btn btn-sm btn-alt-primary" id="dropdown-analytics-overview" data-toggle="modal" data-target="#modal-block-fadein">Logins: {{$total_logins}}</button>
                                        @else
                                            <button type="button"  class="btn btn-sm btn-alt-primary" id="dropdown-analytics-overview"data-toggle="modal" data-target="#modal-block-fadein">Logins: {{$total_logins}}</button>
                                        @endif
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
<div class="col-md-12" >

</div>
            
        <!-- Fade In Block Modal -->
        <div class="modal fade" id="modal-block-fadein" tabindex="-1" role="dialog" aria-labelledby="modal-block-fadein" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="block block-rounded block-themed block-transparent mb-0">
                        <div class="block-header bg-primary-dark">
                            <h3 class="block-title">Current Logins</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                    <i class="fa fa-fw fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content font-size-sm">
                        <div class="table-responsive">
                        @if($total_logins>0)
                                <table class="table table-borderless table-striped table-vcenter">
                                    <thead>
                                        <tr>
                                            <th style="font-size:10px;" class="d-none d-sm-table-cell text-center">Name</th>
                                            <th style="font-size:10px;" class="d-none d-sm-table-cell text-center">Email</th>
                                            <th style="font-size:10px;" class="text-center" >Type</th>
                                            <th style="font-size:10px;" class="text-center" >Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    
                                    @foreach($admins as $admin)
                                    @if(Cache::has('user-is-online-'.$admin->email))
                                        <tr>
                                            
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
                                                    <span style="font-size:10px;">Online</span>
                                            </td>
                                           
                                        </tr>
                                    @endif
                                    @endforeach
                             
                                    @foreach($users as $user)
                                    @if(Cache::has('user-is-online-'.$user->email))
                                        <tr>
                                            
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
                                                    <span style="font-size:10px;">Online</span>
                                            </td>
                                           
                                        </tr>
                                    @endif
                                    @endforeach
                                    </tbody>
                                </table>
                                @endif
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
                            <!-- All Orders Table -->

                @if(count($todaylogs)>0)
                                                      <!-- Page Content -->
                <div class="content"  style="width:92%;margin-left:50px">
                    <!-- Dynamic Table Full -->
                    <div class="block block-rounded" >
                        {{-- <div class="block-header">
                            <h3 class="block-title">Dynamic Table <small>Full</small></h3>
                        </div> --}}
                        <div class="block-content block-content-full" style=" overflow-y:scroll;height:450px;display:block;" >
                       
                            <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->                            <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                                <thead>
                                        <tr>
                                            <th style="font-size:10px;" class="text-center" style="width: 90px;">SL No.</th>
                                            <th style="font-size:10px;" class="text-center" style="width:90px;">Name</th>
                                            <th style="font-size:10px;" class="text-center" style="width:90px;">Email</th>
                                            <th style="font-size:10px;" class="text-center"  style="width:90px;">Type</th>
                                            <th style="font-size:10px;" class="text-center" style="width:90px;">Date</th>
                                            <th style="font-size:10px;" class="text-center" style="width:60px;">Time</th>
                                            <th style="font-size:10px;" class="text-center" style="width:90px;">Device</th>
                                            <th style="font-size:10px;" class="text-center" style="width:90px;">Browser</th>
                                            <th style="font-size:10px;" class="text-center" style="width:90px;">IP Address</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i=0; ?>
                                    @foreach($todaylogs as $todaylog)
                                        <tr>
                                            <td class="text-center font-size-sm" style="word-break:break-all;max-width:1%">
                                                    <span style="font-size:10px;">{{$i+=1}}</span>
                                            </td>
                                            <td class="text-center font-size-sm" style="word-break:break-all;max-width:1%">
                                                    <span style="font-size:10px;">{{$todaylog->name}}</span>
                                            </td>
                                            <td class="text-center font-size-sm" style="word-break:break-all;max-width:1%">
                                                    <span style="font-size:10px;">{{$todaylog->email}}</span>
                                            </td>
                                            <td class="text-center font-size-sm" style="word-break:break-all;max-width:1%">
                                                    <span style="font-size:10px;">{{$todaylog->type}}</span>
                                            </td>
                                            <td class="text-center font-size-sm" style="word-break:break-all;max-width:1%">
                                                    <span style="font-size:10px;">{{$todaylog->last_login_date}}</span>
                                            </td>
                                            <td class="text-center font-size-sm" style="word-break:break-all;max-width:1%">
                                                    <span style="font-size:10px;">{{$todaylog->last_login_time}}</span>
                                            </td>
                                            <td class="text-center font-size-sm" style="word-break:break-all;max-width:1%">
                                                    <span style="font-size:10px;">{{$todaylog->device}}</span>
                                            </td>
                                            <td class="text-center font-size-sm" style="word-break:break-all;max-width:1%">
                                                    <span style="font-size:10px;">{{$todaylog->browser}}</span>
                                            </td>
                                            <td class="text-center font-size-sm" style="word-break:break-all;max-width:1%">
                                                    <span style="font-size:10px;">{{$todaylog->last_ip}}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    
                                    </tbody>
                            </table>
                            @endif
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