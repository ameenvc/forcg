@extends('superadmin.datatable')

@section('content')
  <!-- Main Container -->
<?php 
if($rem<=0)
$rem=0;
$i=0;
$j=0;
?>

                <!-- END Hero -->
                <div class="bg-body-light">
                    <div class="content content-full">
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
                           
                            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-alt">
                                <li class="breadcrumb-item">Manage Company Size</li>
                                   
                                    <li class="breadcrumb-item" aria-current="page">
                                    <a id="a1" onclick="" class="link-fx" href="/superadmin/home">Back</a>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <!-- END Hero -->
         <!--end of increse size-->
@if($size==0)
                <!-- Page Content -->
                <div class="content"  style="margin-left:40px;">
                    <!-- Quick Overview -->
                    <div class="row" >
                        <div class="col-6 col-lg-2">
                            <a class="block block-rounded block-link-shadow text-center">
                                <div class="block-content block-content-full">
                                    <div class="font-size-h2 text-primary" >0</div>
                                </div>
                                <div class="block-content py-2 bg-body-light">
                                    <p class="font-w600 font-size-sm text-muted mb-0" style="font-size: 13px;">
                                        Total Size
                                    </p>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-lg-3">
                            <a class="block block-rounded block-link-shadow text-center" >
                                <div class="block-content block-content-full">
                                    <div class="font-size-h2 text-dark" >0</div>
                                </div>
                                <div class="block-content py-2 bg-body-light">
                                    <p class="font-w600 font-size-sm text-muted mb-0" style="font-size: 13px;">
                                        Current Approved
                                    </p>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-lg-3">
                            <a class="block block-rounded block-link-shadow text-center" >
                                <div class="block-content block-content-full">
                                    <div class="font-size-h2 text-dark" >0</div>
                                </div>
                                <div class="block-content py-2 bg-body-light">
                                    <p class="font-w600 font-size-sm text-muted mb-0" style="font-size: 13px;">
                                        Remaining Size
                                    </p>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-6 col-lg-4">
                        <form action="{{route('superadmin.upcomsize')}}" method="post">
                        @csrf
                            <a class="block block-rounded block-link-shadow text-center" >
                                <div class="block-content block-content-full">
                                    <div class="text-dark">
                                    <div class="form-group" style="display:inline;">
                                    <div class="custom-control custom-checkbox custom-control-dark mb-0 " style="display:inline;margin-left:-10px;" >
                                            <input type="checkbox" onchange="check()" class="custom-control-input" id="inc" name="inc" >
                                            <label class="custom-control-label font-w600 font-size-sm text-muted" for="inc" style="font-size: 13px;" >Increase</label>
                                        </div>
                                        <div class="custom-control custom-checkbox custom-control-dark mb-0" style="display:inline;" >
                                            <input type="checkbox" onchange="check()" class="custom-control-input" id="dec" name="dec">
                                            <label class="custom-control-label font-w600 font-size-sm text-muted" for="dec"  style="font-size: 13px;">Decrease</label>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <div class="block-content py-2 bg-body-light" style="height:59px;">
                                    <p class="font-w600 font-size-sm text-muted mb-0">
                                    <div class="input-group">

                                        <input type="text" class="form-control font-size-sm" style="width:60px;display:inline;margin-left:-10px;"   style="font-size: 13px;" id="name" name="size" placeholder="Company Size">
                                            <button type="submit" onclick="return func()" style="width:70px;display:inline;margin-left:-10px;"   class="btn btn-dark font-size-sm" style="font-size: 13px;" >Submit</button>
                                    </div>
                                    </p>
                                </div>
                            </a>
                    </form>
                    </div>
                    </div>
                @else
                 <!-- Page Content -->
                 <div class="content" style="margin-left:50px;">
                    <!-- Quick Overview -->
                    <div class="row">
                        <div class="col-8 col-lg-2">
                            <a class="block block-rounded block-link-shadow text-center" >
                                <div class="block-content block-content-full">
                                    <div class="font-size-h2 text-primary" >{{$size}}</div>
                                </div>
                                <div class="block-content py-2 bg-body-light">
                                    <p class="font-w600 font-size-sm text-muted mb-0" style="font-size: 13px;">
                                        Total Size
                                    </p>
                                </div>
                            </a>
                        </div>
                        <div class="col-8 col-lg-3">
                            <a class="block block-rounded block-link-shadow text-center" >
                                <div class="block-content block-content-full">
                                    <div class="font-size-h2 text-dark" >{{count($users)}}</div>
                                </div>
                                <div class="block-content py-2 bg-body-light">
                                    <p class="font-w600 font-size-sm text-muted mb-0" style="font-size: 13px;">
                                        Current Approved
                                    </p>
                                </div>
                            </a>
                        </div>
                        <div class="col-8 col-lg-3">
                            <a class="block block-rounded block-link-shadow text-center" >
                                <div class="block-content block-content-full">
                                    <div class="font-size-h2 text-dark" >{{$rem}}</div>
                                </div>
                                <div class="block-content py-2 bg-body-light">
                                    <p class="font-w600 font-size-sm text-muted mb-0" style="font-size: 13px;">
                                        Remaining Size
                                    </p>
                                </div>
                            </a>
                        </div>
                    
                    
                        <div class="col-8 col-lg-4">
                        <form action="{{route('superadmin.upcomsize')}}" method="post">
                        @csrf
                            <a class="block block-rounded block-link-shadow text-center" >
                                <div class="block-content block-content-full">
                                    <div class="text-dark">
                                    <div class="form-group" style="display:inline;">
                                    <div class="custom-control custom-checkbox custom-control-dark mb-0 " style="display:inline;margin-left:-10px;" >
                                            <input type="checkbox" onchange="check()" class="custom-control-input" id="inc" name="inc" >
                                            <label class="custom-control-label font-w600 font-size-sm text-muted" for="inc" style="font-size: 13px;">Increase</label>
                                        </div>
                                        <div class="custom-control custom-checkbox custom-control-dark mb-0" style="display:inline;margin-left:70px;" >
                                            <input type="checkbox" onchange="check()" class="custom-control-input" id="dec" name="dec">
                                            <label class="custom-control-label font-w600 font-size-sm text-muted" for="dec" style="font-size: 13px;">Decrease</label>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <div class="block-content py-2 bg-body-light" style="height:59px;">
                                    <p class="font-w600 font-size-sm text-muted mb-0">
                                    <div class="input-group">

                                        <input type="text" class="form-control font-size-sm" style="width:60px;display:inline;margin-left:-10px;"  style="font-size: 13px;" id="name" name="size" placeholder="Company Size">
                                            <button type="submit" onclick="return func()" style="width:70px;display:inline;margin-left:-10px;"  style="font-size: 13px;"  class="btn btn-dark font-size-sm" >Submit</button>
                                    </div>
                                    </p>
                                </div>
                            </a>
                    </form>
                    </div>
                    </div>
                    <!-- END Quick Overview -->
                @endif
</div>
                    <!-- All Orders -->
                    <!--increase the size-->
                            <!-- END Search Form -->

                            <!-- All Orders Table -->
                                         <!-- Page Content -->
                <div class="content"  style="width:92%;margin-left:50px;margin-top:-30px;">
                    <!-- Dynamic Table Full -->
                    <div class="block block-rounded" >
                        {{-- <div class="block-header">
                            <h3 class="block-title">Dynamic Table <small>Full</small></h3>
                        </div> --}}
                        <div class="block-content block-content-full" style=" overflow-y:scroll;height:450px;display:block;" >
                            <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->                            <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                                <thead>
                                        <tr>
                                            <th style="font-size:10px;" class="text-center" style="width: 100px;">SL No.</th>
                                            <th style="font-size:10px;" class="d-none d-sm-table-cell text-center" style="width: 120px;">Name</th>
                                            <th style="font-size:10px;" class="d-none d-sm-table-cell text-center" style="width: 120px;">Email</th>
                                            <th style="font-size:10px;" class="text-center" style="width:100px;">Online Status</th>
                                            <th style="font-size:10px;" class="text-center" style="width:100px;" >Type</th>
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
                                            @if(Cache::has('user-is-online-'.$admin->email))
                                                    <span style="font-size:10px;">Online</span>
                                                    @else
                                                    <span style="font-size:10px;">Idle/Offline</span>
                                                    @endif
                                            </td>
                                            <td class="text-center font-size-sm">
                                                    <span style="font-size:10px;">Admin</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @endif
                                  
                                    @if(count($users)>0)
                                    <?php $i=1; ?>
                                    @foreach($users as $user)
                                        <tr>
                                            <td class="text-center font-size-sm">
                                                    <span style="font-size:10px;">{{$i+=1}}</span>
                                            </td>
                                            <td class="text-center font-size-sm">
                                                    <span style="font-size:10px;">{{$user->name}}</span>
                                            </td>
                                            <td class="text-center font-size-sm">
                                                    <span style="font-size:10px;">{{$user->email}}</span>
                                            </td>
                                            
                                            <td class="text-center font-size-sm">
                                                    @if(Cache::has('user-is-online-'.$user->email))
                                                    <span style="font-size:10px;">Online</span>
                                                    @else
                                                    <span style="font-size:10px;">Idle/Offline</span>
                                                    @endif
                                            </td>
                                            <td class="text-center font-size-sm">
                                                    <span style="font-size:10px;">User</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @endif
                                    </tbody>
                                </table>
                           
                               
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



