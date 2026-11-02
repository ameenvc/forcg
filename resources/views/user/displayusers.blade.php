@extends('user.userdatatable')


@section('content')

<style>
    .onlinestatus {
        position: absolute;
        top: 1;
        height: 8px;
        width: 8px;
        background-color: #4CBB17;
        border-radius: 50%;
        display: inline-block;
    }

    .offlinestatus {
        position: absolute;
        top: 1;
        height: 8px;
        width: 8px;
        background-color: #bbb;
        border-radius: 50%;
        display: inline-block;
    }

</style>


<!-- Main Container -->
<main id="main-container">
    <!-- Hero -->

    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content" style="width:92%;margin-left:50px;margin-top:-75px">

        @if(Session::has('message'))
        <div class="alert alert-primary">
            <a class="close" data-dismiss="alert">×</a>
            {!!Session::get('message')!!}
        </div>
        @endif
        <!-- Dynamic Table Full -->
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">Users Table</h3>

                <ol class="breadcrumb breadcrumb-alt" style="margin-left:53%;">
                    <li class="breadcrumb-item"></li>

            </div>
            <div class="block-content block-content-full" style="width:90%;margin-left:50px">
                <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                    <thead>
                        <tr>
                            <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">SL No.</th>
                            <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Profile Picture</th>
                            <th class="d-none d-sm-table-cell text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Employee ID</th>
                            <th class="d-none d-sm-table-cell text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Name</th>
                            <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Email</th>
                            <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Department</th>
                            <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Access</th>
                            <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Registered</th>
                            <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Last Seen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $number = 1; ?>
                        @foreach($users as $u)

                        @php
                        $regdt = $u->created_at;
                        $date = date('Y-m-d',strtotime($regdt));
                        $dt = explode('-',$date);
                        $regdateval = $regdt == null ? null : $dt[2]."-".$dt[1]."-".$dt[0];

                        $joindt = $u->user_joining_date;
                        $joindate = date('Y-m-d',strtotime($joindt));
                        $joindat = explode('-',$joindate);
                        $joindateval = $joindat[2]."-".$joindat[1]."-".$joindat[0];

                        @endphp


                        {{csrf_field()}}

                        <?php $id=$u->id; $dept=$u->deptid; $email=$u->email;$empid=$u->employeeid;$name=$u->name?>
                        <tr>

                            <td style="font-size:10px" class="text-left">
                                <span>{{ $number }}</span>
                            </td>
                            <td style="font-size:10px" class="text-left">
                            <span><img src="{{ ($u->profile_picture && File::exists(public_path('/storage/profile/'.$u->profile_picture))) ? asset("storage/profile/".$u->profile_picture) : asset('/images/avatar10.jpg')}}" class="avatar"></span>
                            </td>
                            @if($u->employeeid==0)
                            <td style="font-size:10px" class="text-left">
                                <span>Not Set</span>
                            </td>
                            @else
                            <td style="font-size:10px" class="text-left">
                                <span>{{$u->employeeid}}</span>
                            </td>
                            @endif
                            <?php $number++; ?>
                            <td style="font-size:10px" class="text-left">


                                @if(Cache::has('user-is-online-' . $u->email))
                                <span class="onlinestatus"></span>
                                @else
                                <span class="offlinestatus"></span>
                                @endif &nbsp;&nbsp;&nbsp;
                                <span> {{$u->name}} </span>

                            </td>
                            <td style="font-size:10px" class="text-left">
                                <span>{{$u->email}}</span>
                            </td>
                            @if($u->deptid==NULL)
                            <td style="font-size:10px" class="text-left">
                                <span>Not Set</span>
                            </td>
                            @else
                            <td style="font-size:10px" class="text-left">
                                <span>{{$u->deptid}}</span>
                            </td>
                            @endif
                            @if($u->type==0)
                            <td style="font-size:10px" class="text-left">
                                <span>User</span>
                            </td>
                            @else
                            <td style="font-size:10px" class="text-left">
                                <span>Admin</span>
                            </td>
                            @endif

                            @if($u->user_joining_date==NULL)
                            <td style="font-size:10px" class="text-left">
                                <span>{{$regdateval}}</span>
                            </td>
                            @else
                            <td style="font-size:10px" class="text-left">
                                <span>{{$joindateval}}</span>
                            </td>
                            @endif

                            @if($u->last_seen != null)
                            <td style="font-size:10px" class="text-left">
                                <span> {{ \Carbon\Carbon::parse($u->last_seen)->diffForHumans() }}</span>
                            </td>
                            @else
                            <td style="font-size:10px" class="text-left">
                                <span>No data</span>
                            </td>
                            @endif
                            <!--
					   <td style="font-size:10px" class="text-left">
                                                    <span>{{$regdateval}}</span>
                                            </td>
-->
                        </tr>
                        @endforeach

                    </tbody>
                </table>

            </div>
        </div>
        <!-- END Dynamic Table Full -->


    </div>
    <!-- END Page Content -->
</main>
<!-- END Main Container -->


</body>
</html>
@endsection
