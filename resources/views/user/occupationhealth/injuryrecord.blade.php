@extends('pages.datatables')
@section('exportadd')

<div class="input-group input-group-sm">
    <a href="" class="btn btn-sm btn-dual" style="margin-left:0px;" onclick="return fnCovertTable('print')">

        <i class="fa fa-print  fa-1x" style="color:grey"></i>

    </a>
</div>

<!--<div class="input-group input-group-sm">
    <a type="button" href="{{url('/user/add/injuryrecord/form')}}" class="btn btn-sm btn-dual" style="margin-left:8px;" style="">
        <i class="fa fa-1x fa-plus" style="color:grey;"></i>
    </a>
</div>-->
@endsection
@section('content')
<!doctype html>
<html lang="en">

<body>



    <!-- Page Content -->
    <div class="content" style="width:100%;">
        <!-- Dynamic Table Full -->
        <div class="block block-rounded">
            {{-- <div class="block-header">
                            <h3 class="block-title">Dynamic Table <small>Full</small></h3>
                        </div> --}}
            <div class="block-content block-content-full">
                <div style="overflow-x:hidden;overflow-y:auto;" id="content">
                    <table class="move" width="15%" style="margin-left:670px">
                        <tr>

                            <th>
                                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                    <ol class="breadcrumb breadcrumb-alt" style="margin-right:-200px;margin-bottom:-250px;">
                                        <li class="breadcrumb-item" style="margin-left:-595px;margin-top:-8px">Occupational Health</li>
                                        <li class="breadcrumb-item" aria-current="page" style="margin-top:-8px">
                                            <a class="link-fx" href="">Injury Record</a>
                                        </li>
                                    </ol>
                                </nav>
                            </th>
                        </tr>

                        @if(Session::has('delmsg'))
                            <div class="alert alert-success">
                                <a class="close" data-dismiss="alert">×</a>
                                {!!Session::get('delmsg')!!}
                            </div>
                            @endif
                        <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                        <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">S.No</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">Employee Number</th>
                                    <th style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;width:12%" class="text-center">Name</th>
                                    <!-- <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">DOB</th> -->
                                    <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">Department</th>

                                    <!--  <th  style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">Position</th> -->
                                    <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">Date of Injury</th>
                                    <!-- <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">Injured Body Part</th>
                                    <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">Location</th>
                                    <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">Number of Leaves</th> -->
                                    <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;width:9%">Status</th>
                                    <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">Created By</th>
                                    <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">Created Date</th>

                                    <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">View</th>
                                    <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no=0;?>
                                @foreach($emprecord as $emp)
                                <?php
                                                //    foreach($users as $user){
                                                //    if($incident->injuredperson==$user->email)
                                                //     $injureperson=$user->name;
                                                //    }
                                                // $rr=$issue->id;
                                                $injuredpersons=0;
                                                $today = Date("Y-m-d");
                                                $color = null ;?>
                                <tr>
                                    <td><input class="checkbox" type="checkbox" id="{{$emp->id}}" name="id[]"></td>
                                    <td class="text-center" style="font-size:10px;">{{$no=$no+1}}</td>
                                    <td class="text-center" style="font-size:10px;">{{$emp->e_number}}</td>
                                    <td>
                                        <em class="text-mute" style="font-size:10px;">{{$emp->e_name}}</em>
                                    </td>
                                    <!--
                                        <td class="" style="font-size:10px;">
                                           <em class="text-muted">{{$emp->dob}}</em>
                                        </td>
                                                -->
                                    <td class="" style="font-size:10px;">
                                        <em class="text-muted">
                                          @if(is_numeric($emp->department))
                                                @foreach($departments as $dep)
                                                    @if($emp->department==$dep->id)
                                                        {{$dep->departmentname}}
                                                        @break
                                                    @endif
                                                @endforeach
                                                @else
                                                {{$emp->department}}
                                            @endif
                                       </em>
                                    </td>
                                    <!--
                                        <td class="" style="font-size:10px;">
                                           <em class="text-muted">{{$emp->position}}</em>
                                        </td> -->
                                    <td class="" style="font-size:10px;">
                                        <em class="text-muted">{{$emp->dateofinjury}}</em>
                                    </td>
                                    <!-- <td class="" style="font-size:10px;">
                                        <em class="text-muted">
                                             @if($emp->bodily_location=='other')
                                                {{$emp->body_injured_other}}
                                             @else
                                                {{$emp->bodily_location}}
                                             @endif
                                        </em>
                                    </td>
                                    <td class="" style="font-size:10px;">
                                        <em class="text-muted">{{$emp->location}}</em>
                                    </td>

                                    <td class="" style="font-size:10px;">
                                        <em class="text-muted">{{$emp->medical_leaves}}</em>
                                    </td> -->
                                    <td class="" style="font-size:12px;">
                                        <em class="text-muted">
                                            @if($emp->status=='Cured')
                                            <span class="badge badge-success">{{$emp->status}}</span>
                                            @elseif($emp->status=='In Progress')
                                            <span class="badge badge-warning">{{$emp->status}}</span>
                                            @elseif($emp->status=='Fatality')
                                            <span class="badge badge-danger">{{$emp->status}}</span>
                                            @else
                                            <span class="badge badge-info">{{$emp->status}}</span>
                                            @endif
                                        </em>
                                    </td>

                                    <td class="" style="font-size:10px;">
                                        <em class="text-muted">{{$emp->creator_name}}</em>
                                    </td>
                                    <td class="" style="font-size:10px;">
                                        <em class="text-muted">{{$emp->created_date}}</em>
                                    </td>

                                    <td>
                                        <button type="button" class="btn btn-primary" style="font-size:10px;width: 60px;" data-toggle="modal" data-id="{{ $emp->id }}" data-target="#viewModal{{$emp->id}}"><strong>View</strong> </button>
                                        <div id="viewModal{{$emp->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h2 class="modal-title">Detailed View</h2>
                                                    </div>
                                                    <table class="table table-striped">
                                                        <tr>
                                                            <th style="width:70px;">Report Number</th>
                                                            <td>{{$emp->report_num}}</td>
                                                        </tr>
                                                        <?php
                                                          $category = DB::table('health_categories')->where('id',$emp->category_id)->value('name');
                                                        ?>
                                                        <tr>
                                                            <th style="width:70px;">Category</th>
                                                            <td>{{$category ? $category : '-' }}</td>
                                                        </tr>

                                                        <tr>
                                                            <th style="width:70px;">Employee Number</th>
                                                            <td>{{$emp->e_number}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th style="width:70px;">Employee Name</th>
                                                            <td>{{$emp->e_name}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th style="width:70px;">DOB</th>
                                                            <td>{{$emp->dob}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th style="width:70px;">Department</th>
                                                            <td>
                                                            @if(is_numeric($emp->department))
                                                                @foreach($departments as $dep)
                                                                    @if($emp->department==$dep->id)
                                                                        {{$dep->departmentname}}
                                                                        @break
                                                                    @endif
                                                                @endforeach
                                                                @else
                                                                {{$emp->department}}
                                                            @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th style="width:70px;">Position</th>
                                                            <td>{{$emp->position}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th style="width:70px;">Date of Injury</th>
                                                            <td>{{$emp->dateofinjury}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th style="width:70px;">Time of Injury</th>
                                                            <td>{{$emp->timeofinjury}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th style="width:70px;">Type of Injury</th>
                                                            <td>
                                                                @if($emp->injury_type=='work_related')
                                                                    Work Related
                                                                @else
                                                                    Non-work Related
                                                                @endif
                                                            </td>
                                                        </tr>

                                                        @if($emp->injury_type=='work_related')
                                                        <tr>
                                                            <th style="width:70px;">Work Related Injury Details</th>
                                                            <td>                                   
                                                                @if($emp->workrelated_injury=='other')
                                                                    {{$emp->workrelated_injury_other}}
                                                                @else
                                                                    <?php
                                                                    $wrk = explode(',',$emp->workrelated_injury);
                                                                    foreach($wrk as $itm)
                                                                    {
                                                                        $injurydata = DB::table('injury_workrelatedcases')->where('id',$itm)->value('name');
                                                                        $indata = ucfirst(strtolower($injurydata));                                                                                   
                                                                    ?>
                                                                    <br/>
                                                                    {{$injurydata ? $indata : '-'}}
                                                                    <?php } ?>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                       @endif

                                                        @if($emp->nonwork_related_injury!=NULL)
                                                        <tr>
                                                            <th style="width:70px;">Non-Work Related Injury Details</th>
                                                            <td>{{$emp->nonwork_related_injury}}</td>   
                                                        </tr>
                                                        @endif

                                                        <!-- <tr>
                                                            <th style="width:70px;">Nature of Injury</th>
                                                            <td>
                                                              @if(is_numeric($emp->natureofinjury))
                                                                {{$emp->injuryNature ? $emp->injuryNature->name :''}}
                                                              @else
                                                              {{$emp->natureofinjury}}
                                                              @endif
                                                            </td>                                   
                                                        </tr> -->

                                                        <tr>
                                                          <th style="width:70px;">Nature of Injury</th>
                                                            <td>
                                                              @if($emp->natureofinjury=='-')
                                                                <?php
                                                                  $injury_nature = DB::table('employee_injury_nature_details')->where('injury_id',$emp->id)->get();
                                                                  foreach($injury_nature as $nature)
                                                                  {
                                                                    $natureinjurydata = DB::table('employee_health_injury_natures')->where('id',$nature->natureofinjury)->value('name');
                                                                    $naturedata = ucfirst(strtolower($natureinjurydata)); 
                                                                                            
                                                                ?>
                                                                <br/>
                                                                    {{$natureinjurydata ? $naturedata : '-'}}   
                                                                <?php
                                                                    }
                                                                ?>
                                                              @else
                                                                {{$emp->natureofinjury}}
                                                              @endif
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <th style="width:70px;">Injured Body Part (Body Location)</th>
                                                            <td>
                                                                @if($emp->bodily_location=='other')
                                                                   {{$emp->body_injured_other}}
                                                                @else
                                                                   {{$emp->bodily_location}}
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th style="width:70px;">Location</th>
                                                            <td>{{$emp->location}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th style="width:70px;">Cause of Injury</th>
                                                            <td>{{$emp->causeofinjury}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th style="width:70px;">Was any Plant, Equipment, Substance or Thing Involved in Injury?</th>
                                                            <td>{{$emp->things_involved}}</td>
                                                        </tr>
                                                        @if($emp->things_involved==='yes')
                                                        <tr>
                                                            <th style="width:70px;">Details</th>
                                                            <td>{{$emp->things_involved_details}}</td>
                                                        </tr>
                                                        @endif

                                                        <tr>
                                                            <th style="width:70px;">Has the Injury Been Reported To Workers Supervisor's?</th>
                                                            <td>{{$emp->reported_to_supervisor}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th style="width:70px;">Was Any Treatment Provided?</th>
                                                            <td>{{$emp->treatment_provided}}</td>
                                                        </tr>

                                                        @if($emp->treatment_provided==='yes')
                                                        <tr>
                                                            <th style="width:70px;">Treatment Details</th>
                                                            <td>
                                                                @if($emp->treatment_details=='other')
                                                                    {{$emp->treatmentdetails_other}}
                                                                @else
                                                                    <?php
                                                                    $tret = explode(',',$emp->treatment_details);
                                                                    foreach($tret as $itm)
                                                                    {
                                                                        $treatdata = DB::table('health_treatments')->where('id',$itm)->value('name');                                                                                                  
                                                                    ?>
                                                                    <br/>
                                                                    {{$treatdata ? $treatdata : '-'}}
                                                                    <?php } ?>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @endif

                                                        <tr>
                                                            <th style="width:70px;">Was the Injured Person Return to Work Following the Injury?</th>
                                                            <td>{{$emp->injured_returnto_work}}</td>
                                                        </tr>
                                                        @if($emp->treatment_provided==='yes')
                                                        <tr>
                                                            <th style="width:70px;">Details</th>
                                                            <td>{{$emp->injured_returnto_work_details}}</td>
                                                        </tr>
                                                        @endif
                                                        

                                                        <tr>
                                                            <th style="width:70px;">No.of Leaves</th>
                                                            <td>{{$emp->medical_leaves}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th style="width:70px;">Status</th>
                                                            <td>
                                                                @if($emp->status=='Cured')
                                                                <span class="badge badge-success">{{$emp->status}}</span>
                                                                @elseif($emp->status=='In Progress')
                                                                <span class="badge badge-warning">{{$emp->status}}</span>
                                                                @elseif($emp->status=='Fatality')
                                                                <span class="badge badge-danger">{{$emp->status}}</span>
                                                                @else
                                                                <span class="badge badge-info">{{$emp->status}}</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                       
                                                        <tr>
                                                            <th style="width:70px;">Created By</th>
                                                            <td>{{$emp->creator_name}}</td>
                                                        </tr>
                                                        
                                                        <tr>
                                                            <th style="width:70px;">Creator Position</th>
                                                            <td>{{$emp->creator_position}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th style="width:70px;">Creator Department</th>
                                                            <td>
                                                            @if(is_numeric($emp->creator_department))
                                                                @foreach($departments as $dep)
                                                                    @if($emp->creator_department==$dep->id)
                                                                        {{$dep->departmentname}}
                                                                        @break
                                                                    @endif
                                                                @endforeach
                                                                @else
                                                                {{$emp->creator_department}}
                                                            @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th style="width:70px;">Created Date</th>
                                                            <td>{{$emp->created_date}}</td>
                                                        </tr>
                                                    </table>                             

                                                    <!--For Displaying witnesses-->        
                                                    @if($emp->witness=='Yes')
                                                    <div class="modal-header">
                                                        <h4 class="modal-title"><b>Witnesses</b></h4>
                                                    </div>                   
                                                    <table class="table table-striped">
                                                        <tr>
                                                            <th class="text-center">SL No.</th>
                                                            <th class="text-center">Name</th>
                                                            <th class="text-center">Phone or Address</th>

                                                        </tr>
                                                        <?php
                                                           $x=0;
                                                        ?>
                                                        @foreach($witnesses as $wit)
                                                            @if($wit->injury_id == $emp->id)
                                                                <tr>
                                                                    <td class="text-center">{{$x+=1}}</td>
                                                                    <td class="text-center">{{$wit->name}}</td>
                                                                    <td class="text-center" style="white-space:pre-wrap;word-wrap:break-word;max-width:55px;">{{$wit->number}}</td>

                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    </table>                               
                                                    @endif

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <div class="dropdown">

                                        <div class="dropdown font-size-sm">
                                            <td class="font-w600 font-size-sm">

                                                <button type="button" class="btn btn-primary dropdown-toggle" id="dropdown-default-primary" style="font-size:10px;" data-toggle="dropdown">Actions</button>
                                                <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark">
                                                    <!-- below is to  allow reviewer to initial review report -->
                                                    @foreach($doctors as $doct)
                                                    @if($doct->email===Auth::user()->email)
                                                    <a class='dropdown-item' href="/user/update/injuryrecord/{{$emp->id}}" style="font-size:10px;">Update Record</a>
                                                    @endif
                                                    @endforeach

                                            </td>
                                        </div>

                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
                        {{----------delete request button ----------}}
                        <script>
                            $("#del").click(function() {
                                mess1 = "Are you sure?";
                                    x = confirm(mess1);
                                    if (x == true) {
                                        mess2 = 'Injury Record will be deleted permanently,Press OK to continue.';
                                        y = confirm(mess2);
                                        if (y == true) {} else {
                                            return false;
                                        }
                                    } else {
                                        return false;
                                    }
                                var dataArr = new Array();
                                if ($('input:checkbox:checked').length > 0) {
                                    $('input:checkbox:checked').each(function() {
                                        dataArr.push($(this).attr('id'));
                                        // console.log(dataArr); 
                                        $.ajax({
                                            type: 'get'
                                            , url: "{{route('deleteemplyinjury')}}" + "?val=" + $(this).attr('id')
                                            , success: function(response) {
                                                // alert(response);
                                                window.location.href = "{{url('/user/occupationhealth/injuryrecords')}}";
                                                //  redirect('/user/doc/masterlist')->withData($data);
                                            }
                                            , error: function(response) {
                                                alert(errResponse);
                                            }
                                        });
                                    });

                                } else {
                                    alert('No Record Selected');
                                }
                            });

                            function autoResizeDiv(height) {
                                document.getElementById('content').style.height = (height) + 'px';
                            }
                            window.onresize = autoResizeDiv;
                            autoResizeDiv(window.innerHeight - 100);

                        </script>
                        {{-- --------edit  button ------------}}
                        <script>
                            $("#edit").click(function() {
                                var dataArr = new Array();
                                var group = new Array();
                                var d;
                                var all;
                                if ($('input:checkbox:checked').length > 0) {
                                    $('input:checkbox:checked').each(function() {
                                        dataArr.push($(this).attr('id'));
                                        //  console.log(dataArr);

                                        all = "" + dataArr;
                                        group.push(all); // push all checkbox values into an array
                                        //  console.log(group);


                                    });
                                    $.ajax({
                                        type: 'get'
                                        , url: "{{route('employeehealthrecords')}}" + "?val=" + (all)
                                        , success: function(response) {
                                            // alert(response);

                                            window.location.href = "{{route('employeehealthrecords')}}" + "?val=" + (all);
                                            // console.log(response);

                                        }
                                        , error: function(response) {
                                            alert(errResponse);
                                        }
                                    });

                                } else {
                                    alert('No Record Selected');
                                }
                            });

                        </script>

                </div>
            </div>
            <!-- END Dynamic Table Full -->


        </div>
        <!-- END Page Content -->
        {{-- </main> --}}
        <!-- END Main Container -->


        <!-- END Footer -->

        <!-- Apps Modal -->
        <!-- Opens from the modal toggle button in the header -->
        <div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="block block-rounded block-themed block-transparent mb-0">
                        <div class="block-header bg-primary-dark">
                            <h3 class="block-title">More</h3>
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
                                    <a class="block block-rounded block-link-shadow bg-body" href="/user/occupationhealth/register">
                                        <div class="block-content text-center">
                                            <i class="far fa-list-alt fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Employee Health Record
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Products -->
                                </div>
                                <div class="col-6">
                                    <!-- Products -->
                                    <a class="block block-rounded block-link-shadow bg-body" href="/user/occupationhealth/injuryrecords">
                                        <div class="block-content text-center">
                                            <i class="far fa-list-alt fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Employee Injury Record
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Products -->
                                </div>
                                <div class="col-6">
                                    <!-- Products -->
                                    <a class="block block-rounded block-link-shadow bg-body" href="/user/occupationhealth/illnessrecords">
                                        <div class="block-content text-center">
                                            <i class="far fa-list-alt fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Employee Illness Record
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Products -->
                                </div>

                                <div class="col-6">
                                    <!-- Products -->
                                    <a class="block block-rounded block-link-shadow bg-body" href="{{ url('/user/occupationhealth/analysis') }}">
                                        <div class="block-content text-center">
                                            <i class="far fa-list-alt fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2" style="margin-bottom:60px">
                                                Analysis
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Products -->
                                </div>
                                <!--<div class="col-6">

                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/risks/closedrisks">
                                            <div class="block-content text-center">
                                                <i class="si si-plane fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Closed Risks
                                                </p>
                                            </div>
                                        </a>

                                    </div>
                                    <div class="col-6">

                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/risks/analysis">
                                            <div class="block-content text-center">
                                                <i class="si si-wallet fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Analysis
                                                </p>
                                            </div>
                                        </a>

                                    </div>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Apps Modal -->


</body>
</html>
@endsection


@section('sideitem')
{{-- @foreach($mr as $mrs) --}}
{{-- @if(Auth::user()->email==$mrs) --}}
<!--@if(Gate::allows('edit-doctor') || Gate::allows('edit-all'))
<div class="input-group input-group-sm ml-2">
    <button type="button" class="btn btn-sm btn-dual" style="margin-left:398px;" id="edit" value="id[]">
        <i class="fa fa-1x fa-edit" style="color:grey;"></i>
    </button>
</div>
@endif-->
@if(Gate::allows('delete-doctor') || Gate::allows('delete-all'))
<div class="input-group input-group-sm ml-2">
    <button type="button" id="del" value="id[]" class="btn btn-sm btn-dual">
        <i class="fas fa-1x fa-trash" style="color:red;"></i>
    </button>
</div>
@endif
{{-- @endif --}}
{{-- @endforeach --}}
@endsection
