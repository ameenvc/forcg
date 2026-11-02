@extends('pages.datatables')
@section('exportadd')



<div class="input-group input-group-sm">
@if(in_array(Auth::user()->email,$doctors))
    <a type="button" href="{{url('/user/occupationhealth/addotherinjuryillness')}}" class="btn btn-sm btn-dual" style="margin-left:8px;">
        <i class="fa fa-1x fa-plus" style="color:grey;"></i>
    </a>

@endif
</div>
@endsection

@section('content')
<div class="bg-body-light">
    <div class="content content-full" style="width:100%;">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
                           
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-alt">
                                <li class="breadcrumb-item">Occupational Health</li>
                                <li class="breadcrumb-item" aria-current="page">
                                    <a class="link-fx" href="">Other Cases Health Record</a>
                                </li>
                                   
                        </ol>
                </nav>
        </div>
    </div>
</div>

<div class="content" style="width:100%;">
     <div class="block block-rounded" >
        <div class="block-content block-content-full"> 
        <!-- <div style="overflow-x:hidden;overflow-y:auto;" id="content">
                   <div class="row" style="margin-left:25px;margin-right:25px;margin-bottom:5px;border-bottom:1px solid #CCCCCC">
                        <div class="col-lg-12">                       
                           <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                   <ol class="breadcrumb breadcrumb-alt">
                                           <li class="breadcrumb-item">Occupational Health</li>
                                           <li class="breadcrumb-item" aria-current="page">
                                               <a class="link-fx" href="">Other Cases Health Record</a>
                                           </li>
                                              
                                   </ol>
                           </nav>
                        </div>
                   </div> -->
        
          @if(in_array(Auth::user()->email,$doctors))
            <!-- content starts here -->
              <div class="row">
                        <div class="col-lg-12">
                           <!-- @if(Session::has('msg'))
                                <div class="alert alert-primary">
                                    <a class="close" data-dismiss="alert">×</a>
                                    {!!Session::get('msg')!!}
                                </div>
                            @endif                    -->
                            <div id="successmessage">

                            </div>

                              <div class="block block-rounded">
                                <ul class="nav nav-tabs nav-tabs-alt" data-toggle="tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#btabs-alt-static-home">Other Injury Records</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#btabs-alt-static-profile">Other Illness Records</a>
                                    </li>
                                    
                                </ul>
                                <div class="block-content tab-content">
                                    <!-- Injury register starts here -->
                                   <div class="tab-pane active" id="btabs-alt-static-home" role="tabpanel">
                                   <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">S.No</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">Injured Person</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">Name</th>
                                    <th style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;width:12%" class="text-center">Employee Number</th>
                                    <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">Department</th>
                                    <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">Date of Injury</th>
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
                                                $injuredpersons=0;
                                                $today = Date("Y-m-d");
                                                $color = null ;?>
                                <tr>
                                    <td><input class="checkbox" type="checkbox" id="{{$emp->id}}" name="id[]"></td>
                                   
                                    <td class="text-center" style="font-size:10px;">{{$no=$no+1}}</td>
                                    <td class="text-center" style="font-size:10px;">
                                              @if($emp->injured_person_type=='contractor')
                                                Contract Worker
                                              @else
                                               {{ucfirst($emp->injured_person_type)}}
                                              @endif
                                    </td>
                                    <td class="text-center" style="font-size:10px;">{{$emp->e_name}}</td>
                                    <td>
                                        <em class="text-mute" style="font-size:10px;">{{$emp->e_number}}</em>
                                    </td>
                                   
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
                                   
                                    <td class="" style="font-size:10px;">
                                        <em class="text-muted">{{$emp->dateofinjury}}</em>
                                    </td>
                                    
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
                                                            <th style="width:70px;">Injured Person</th>
                                                            <td>
                                                                @if($emp->injured_person_type=='contractor')
                                                                    Contract Worker
                                                                @else
                                                                {{ucfirst($emp->injured_person_type)}}
                                                                @endif
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <th style="width:70px;">Employee Number</th>
                                                            <td>{{$emp->e_number}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th style="width:70px;"> Name</th>
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
                                                    @if(in_array(Auth::user()->email,$doctors))
                                                    <a class='dropdown-item' href="/user/occupationhealth/update/otherinjuryrecord/{{$emp->id}}" style="font-size:10px;">Update Record</a>
                                                    @endif                                      
                                            </td>
                                        </div>

                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                                   </div>
                                   <!-- Injury register ends here -->

                                    <!-- Illness register starts here -->
                                    <div class="tab-pane" id="btabs-alt-static-profile" role="tabpanel">
                                       <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                                           
                                       <thead>
                                    <tr>
                                    <th></th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">S.No</th>
                                        <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">Illed Person</th>    
                                        <th style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">Name</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">Employee Number</th>
                                       
                                        <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">Department</th>

                                        <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">Date of Illness</th>
                                        <!-- <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">Symptom</th>
                                        <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">Location</th>
                                       
                                        <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">Is Illness Pandemic</th> -->
                                        <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">Status</th>

                                        <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">Created By</th>
                                        <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">Created Date</th>

                                        <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">View</th>
                                        <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $no=0;?>
                                                @foreach($empillrecord as $empill)
                                                <?php
                                                $today = Date("Y-m-d");
                                                $color = null ;?>
                                    <tr>
                                    <td><input class="checkbox" type="checkbox" id="{{$empill->id}}" name="id[]" ></td>
                                        <td class="text-center" style="font-size:10px;">{{$no=$no+1}}</td>
                                        <td class="text-center" style="font-size:10px;">
                                              @if($emp->illed_person_type=='contractor')
                                                Contract Worker
                                              @else
                                               {{ucfirst($empill->illed_person_type)}}
                                              @endif
                                        </td>
                                        <td class="text-center" style="font-size:10px;">{{$empill->e_name}}</td>
                                        <td>
                                            <em class="text-mute" style="font-size:10px;">{{$empill->e_number}}</em>
                                        </td>
                                        <td class="text-center" style="font-size:10px;">
                                            @if(is_numeric($empill->department))
                                                @foreach($departments as $dep)
                                                    @if($empill->department==$dep->id)
                                                        {{$dep->departmentname}}
                                                        @break
                                                    @endif
                                                @endforeach
                                                @else
                                                {{$empill->department}}
                                            @endif
                                        </td>
                                        <td class="" style="font-size:10px;">
                                           <em class="text-muted">{{$empill->dateofillness}}</em>
                                        </td>
                                      

                                        <td class="" style="font-size:12px;">
                                           <em class="text-muted">
                                           @if($empill->status=='Cured')
                                            <span class="badge badge-success">{{$empill->status}}</span>
                                            @elseif($empill->status=='In Progress')
                                            <span class="badge badge-warning">{{$empill->status}}</span>
                                            @elseif($empill->status=='Fatality')
                                            <span class="badge badge-danger">{{$empill->status}}</span>
                                            @else
                                            <span class="badge badge-info">{{$empill->status}}</span>
                                            @endif
                                          </em>
                                        </td>


                                        <td class="" style="font-size:10px;">
                                           <em class="text-muted">{{$empill->creator_name}}</em>
                                        </td>
                                        <td class="" style="font-size:10px;">
                                           <em class="text-muted">{{$empill->created_date}}</em>
                                        </td>

                                        <td>
                                        <button type="button" class="btn btn-primary" style="font-size:10px;width: 60px;" data-toggle="modal" data-id="{{ $empill->id }}" data-target="#viewModal{{$empill->id}}"><strong>View</strong> </button>
                                        <div id="viewModal{{$empill->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h2 class="modal-title">Detailed View</h2>
                                                </div>
                                                <table class="table table-striped">
                                                                            <tr>
                                                                                <th style="width:70px;">Report Number</th>
                                                                                <td>{{$empill->report_num}}</td>
                                                                            </tr>
                                                                             
                                                                            <?php
                                                                                $category = DB::table('health_categories')->where('id',$empill->category_id)->value('name');
                                                                            ?>
                                                                            <tr>
                                                                                <th style="width:70px;">Category</th>
                                                                                <td>{{$category ? $category : '-' }}</td>
                                                                            </tr>

                                                                            <tr>
                                                                               <th  style="width:70px;">Illed Person Type</th>
                                                                               <td>
                                                                                    @if($emp->illed_person_type=='contractor')
                                                                                        Contract Worker
                                                                                    @else
                                                                                    {{ucfirst($empill->illed_person_type)}}
                                                                                    @endif
                                                                               </td>
                                                                            </tr>

                                                                            <tr>
                                                                               <th  style="width:70px;">Employee Number</th>
                                                                                <td>{{$empill->e_number}}</td>
                                                                            </tr>

                                                                            <tr>
                                                                                <th  style="width:70px;">Employee Name</th>
                                                                                <td>{{$empill->e_name}}</td>
                                                                            </tr>

                                                                            <tr>
                                                                                <th  style="width:70px;">DOB</th>
                                                                                <td>{{$empill->dob}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th  style="width:70px;">Department</th>
                                                                                <td>
                                                                                  @if(is_numeric($empill->department))
                                                                                        @foreach($departments as $dep)
                                                                                            @if($empill->department==$dep->id)
                                                                                                {{$dep->departmentname}}
                                                                                                @break
                                                                                            @endif
                                                                                        @endforeach
                                                                                        @else
                                                                                        {{$empill->department}}
                                                                                    @endif
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th  style="width:70px;">Position</th>
                                                                                <td>{{$empill->position}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th  style="width:70px;">Date of Illness</th>
                                                                                <td>{{$empill->dateofillness}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th  style="width:70px;">Time of Illness</th>
                                                                                <td>{{$empill->timeofillness}}</td>
                                                                            </tr>

                                                                            <tr>
                                                                                <th style="width:70px;">Type of Illness</th>
                                                                                <td>
                                                                                    @if($empill->ill_type=='work_related')
                                                                                        Work Related
                                                                                    @else
                                                                                        Non-work Related
                                                                                    @endif
                                                                                </td>
                                                                            </tr>
                                                                            
                                                                            @if($empill->workrelated_illness!=NULL)
                                                                            <tr>
                                                                                <th style="width:70px;">Work Related Illness Details</th>
                                                                                <td>{{$empill->workrelated_illness}}</td>   
                                                                            </tr>
                                                                            @endif
                                                                          
                                                                            @if($empill->ill_type=='non_work_related')
                                                                            <tr>
                                                                                <th style="width:70px;">Non-Work Related Illness Details</th>
                                                                                <td>                                   
                                                                                    @if($empill->nonworkrelated_illness=='other')
                                                                                        {{$empill->nonworkrelated_illness_other}}
                                                                                    @else
                                                                                        <?php
                                                                                        $wrk = explode(',',$empill->nonworkrelated_illness);
                                                                                        foreach($wrk as $itm)
                                                                                        {
                                                                                            $illnessdata = DB::table('illness_non_workrelatedcases')->where('id',$itm)->value('name');
                                                                                                                                                                         
                                                                                        ?>
                                                                                        <br/>
                                                                                        {{$illnessdata ? $illnessdata : '-'}}
                                                                                        <?php } ?>
                                                                                    @endif
                                                                                </td>
                                                                            </tr>
                                                                        @endif
                                                                                                                                       
                                                                        <tr>
                                                                            <th style="width:70px;">Nature of Illness</th>
                                                                                <td>
                                                                                @if($empill->natureofillness=='-')
                                                                                    <?php
                                                                                    $ill_nature = DB::table('employee_illness_nature_details')->where('illness_id',$empill->id)->get();
                                                                                    foreach($ill_nature as $nature)
                                                                                    {
                                                                                        $natureillnessdata = DB::table('employee_health_illness_natures')->where('id',$nature->natureofillness)->value('name');
                                                                                        $naturedata = ucfirst(strtolower($natureillnessdata)); 
                                                                                                                
                                                                                    ?>
                                                                                    <br/>
                                                                                        {{$natureillnessdata ? $naturedata : '-'}}   
                                                                                    <?php
                                                                                        }
                                                                                    ?>
                                                                                @else
                                                                                    {{$emp->natureofillness}}
                                                                                @endif
                                                                                </td>
                                                                            </tr>

                                                                            <tr>
                                                                                <th  style="width:70px;">Symptoms</th>
                                                                                <td>{{$empill->symptoms}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th  style="width:70px;">Location</th>
                                                                                <td>{{$empill->location}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th  style="width:70px;">Cause of Injury</th>
                                                                                <td>{{$empill->causeofillness}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th  style="width:70px;">Is Illness Is Any Type of Pandemic?</th>
                                                                                <td>{{$empill->pandamic}}</td>
                                                                            </tr>
                                                                            @if($empill->pandamic=='yes')
                                                                            <tr>
                                                                                <th  style="width:70px;">Details</th>
                                                                                <td>{{$empill->pandamic_details}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th  style="width:70px;">Contacts</th>
                                                                                <td>{{$empill->pandemic_contacts}}</td>
                                                                            </tr>
                                                                            @endif
                                                                            <tr>
                                                                                <th  style="width:70px;">Was any Company Matters/Materials/Equipment Caused Illness?</th>
                                                                                <td>{{$empill->things_involved}}</td>
                                                                            </tr>
                                                                            @if($empill->things_involved==='yes')
                                                                            <tr>
                                                                                <th  style="width:70px;">Details</th>
                                                                                <td>{{$empill->things_involved_details}}</td>
                                                                            </tr>
                                                                            @endif

                                                                            <tr>
                                                                                <th  style="width:70px;">Has the Illness Been Reported To Workers Supervisor's?</th>
                                                                                <td>{{$empill->reported_to_supervisor}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th  style="width:70px;">Was Any Treatment Provided?</th>
                                                                                <td>{{$empill->treatment_provided}}</td>
                                                                            </tr>
                                                                            
                                                                            @if($empill->treatment_provided==='yes')
                                                                            <tr>
                                                                                <th  style="width:70px;">Treatment Details</th>
                                                                                <td>
                                                                                @if($empill->treatment_details=='other')
                                                                                    {{$empill->treatmentdetails_other}}
                                                                                @else
                                                                                <?php
                                                                                    $tret = explode(',',$empill->treatment_details);
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
                                                                                <th  style="width:70px;">Was the Illed Person Return to Work Following the Injury?</th>
                                                                                <td>{{$empill->illed_returnto_work}}</td>
                                                                            </tr>
                                                                            @if($empill->treatment_provided==='yes')
                                                                            <tr>
                                                                                <th  style="width:70px;">Details</th>
                                                                                <td>{{$empill->illed_returnto_work_details}}</td>
                                                                            </tr>
                                                                            @endif

                                                                            <tr>
                                                                                <th  style="width:70px;">No.of Leaves</th>
                                                                                <td>{{$empill->medical_leaves}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th  style="width:70px;">Status</th>
                                                                                <td>
                                                                                @if($empill->status=='Cured')
                                                                                    <span class="badge badge-success">{{$empill->status}}</span>
                                                                                    @elseif($empill->status=='In Progress')
                                                                                    <span class="badge badge-warning">{{$empill->status}}</span>
                                                                                    @elseif($empill->status=='Fatality')
                                                                                    <span class="badge badge-danger">{{$empill->status}}</span>
                                                                                    @else
                                                                                    <span class="badge badge-info">{{$empill->status}}</span>
                                                                                    @endif
                                                                                </td>
                                                                            </tr>
                                                                            
                                                                            <tr>
                                                                                <th  style="width:70px;">Created By</th>
                                                                                <td>{{$empill->creator_name}}</td>
                                                                            </tr><tr>
                                                                                <th  style="width:70px;">Creator Position</th>
                                                                                <td>{{$empill->creator_position}}</td>
                                                                            </tr><tr>
                                                                                <th  style="width:70px;">Creator Department</th>
                                                                                <td>
                                                                                    @if(is_numeric($empill->creator_department))
                                                                                        @foreach($departments as $dep)
                                                                                            @if($empill->creator_department==$dep->id)
                                                                                                {{$dep->departmentname}}
                                                                                                @break
                                                                                            @endif
                                                                                        @endforeach
                                                                                        @else
                                                                                        {{$empill->creator_department}}
                                                                                    @endif
                                                                                </td>
                                                                            </tr><tr>
                                                                                <th  style="width:70px;">Created Date</th>
                                                                                <td>{{$empill->created_date}}</td>
                                                                            </tr>
                                                                           
                                                                        </table>

                                                                        <!--For Displaying witnesses-->
                                                                        @if($empill->witness=='Yes')
                                                                        <div class="modal-header">
                                                                            <h4 class="modal-title"><b>Witnesses</b></h4>
                                                                        </div>

                                                                                <table class="table table-striped">
                                                                                <tr>
                                                                                <th class="text-center">SL No.</th>
                                                                                <th class="text-center">Name</th>
                                                                                <th class="text-center" >Phone or Address</th>

                                                                                </tr>
                                                                                <?php
                                                                                $x=0;
                                                                                ?>
                                                                                @foreach($illwitnesses as $wit)
                                                                                @if($wit->illness_id==$empill->id)
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

                                                         <button type="button"class="btn btn-primary dropdown-toggle" id="dropdown-default-primary"  style="font-size:10px;" data-toggle="dropdown">Actions</button>
                                                         <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark">
                                                             <!-- below is to  allow reviewer to initial review report -->
                                                                    @if(in_array(Auth::user()->email,$doctors))
                                                                        <a class='dropdown-item' href="/user/occupationhealth/update/otherillnessrecord/{{$empill->id}}" style="font-size:10px;">Update Record</a>
                                                                    @endif
                                                                   
                                                                         </td>
                                                                         </div>

                                    </tr>
                                    @endforeach

                                </tbody>


                                       </table>
                                    </div>
                                    <!-- Illness register ends here -->
                                </div>
                              </div>
                        </div>
              </div>
              <!-- content ends here -->
        
              @else
                   <div class="row" style="margin-left:25px;margin-right:25px;">
                        <div class="col-lg-12">
                           
                                <div class="text-danger text-center">
                                    
                                    You are not authorized to access this data
                                </div>
                              
                        </div>
                   </div>                 
              @endif
             
        </div>
        </div>
     </div>
   
       
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
{{----------delete request button ----------}}
    <script>
        $("#del").click(function () 
        {
            mess1 = "Are you sure?";
            x = confirm(mess1);
            if (x == true) 
            {
                mess2 = 'Record will be deleted permanently,Press OK to continue.';
                y = confirm(mess2);
                if (y == true) 
                {} 
                else 
                {
                    return false;
                }
            } 
            else 
            {
                return false;
            }
            var dataArr = new Array();
            if($('input:checkbox:checked').length > 0){
                $('input:checkbox:checked').each(function(){
                    dataArr.push($(this).attr('id'));
    
                    $.ajax({
                        type    :'get',
                        url     :"{{route('deleteothercasesrecord')}}"+"?val="+$(this).attr('id'),
                        success : function(response){
                                //window.location.href ="{{url('/user/occupationhealth/othercases')}}";
                                if(response.status == 200)
                                 {
                                    window.location.href ="{{url('/user/occupationhealth/othercases')}}";
                                    document.getElementById("successmessage").innerHTML +=
                                    "<div class='alert alert-success'><a class='close' data-dismiss='alert'>×</a>Records deleted successfully</div>";                                           
                                 }
                        },
                        error   :function(response){
                                alert(errResponse);
                        }
            });
                });

            }
            else{
                    alert('No Record Selected');
                }
        });
    </script>


<!-- topmenu listing starts here -->
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
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/occupationhealth/register">
                                            <div class="block-content text-center">
                                                <i class="far fa-list-alt fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Employee Health Record
                                                </p>
                                            </div>
                                        </a>                                  
                                    </div>

                                    <div class="col-6">                            
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/occupationhealth/injuryrecords">
                                            <div class="block-content text-center">
                                                <i class="far fa-list-alt fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                  Employee Injury Record
                                                </p>
                                            </div>
                                        </a>                  
                                    </div>

                                    <div class="col-6">                              
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/occupationhealth/illnessrecords">
                                            <div class="block-content text-center">
                                                <i class="far fa-list-alt fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                  Employee Illness Record
                                                </p>
                                            </div>
                                        </a>                        
                                    </div>
                                    
                                    <div class="col-6">                        
                                        <a class="block block-rounded block-link-shadow bg-body" href="">
                                            <div class="block-content text-center">
                                                <i class="far fa-list-alt fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 " style="margin-bottom:60px">
                                                  Analysis
                                                </p>
                                            </div>
                                        </a>                            
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
</div>
<!-- topmenu listing ends here -->
@endsection

@section('sideitem')
@if(Gate::allows('delete-doctor') || Gate::allows('delete-all'))
<div class="input-group input-group-sm ml-2">
 <button type="button"   id="del" value="id[]" class="btn btn-sm btn-dual" >
 <i class="fas fa-1x fa-trash" style="color:red;"></i>
 </button>
</div>
@endif
@endsection