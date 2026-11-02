@inject('Division', 'App\Division')
@inject('Area', 'App\Area')
<div class="bg-white p-3 push">
                        <!-- Toggle Navigation -->
                        <div class="d-lg-none">
                            <!-- Class Toggle, functionality initialized in Helpers.coreToggleClass() -->
                            <button type="button" class="btn btn-block btn-light d-flex justify-content-between align-items-center" data-toggle="class-toggle" data-target="#horizontal-navigation-hover-normal" data-class="d-none">
                                Checklist Menu 
                                <i class="fa fa-bars"></i>
                            </button>
                        </div>
                        <!-- END Toggle Navigation -->

                        <!-- Navigation -->
                        <div id="horizontal-navigation-hover-normal" class="d-none d-lg-block mt-2 mt-lg-0">
                            <ul class="nav-main nav-main-horizontal nav-main-hover">
                                <li class="nav-main-heading">Manage</li>
                                <li class="nav-main-item">
                                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                                        <i class="nav-main-link-icon si si-folder-alt"></i>
                                        <span class="nav-main-link-name">Departments</span>
                                    </a>
                                    <ul class="nav-main-submenu">
                                    @if(count($departments)>0)
                                      @foreach($departments as $department)
                                           <?php
                                                $checklist = DB::table('audit_checklist')
                                                                                     ->where('department_id',$department->id)
                                                                                     ->where('is_deleted',0)
                                                                                     ->get();
                                                
                                            ?>
                                        <li class="nav-main-item">
                                           @if(count($checklist)> 0)
                                            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                                           @else
                                           <a class="nav-main-link" href="javascript:void(0)">
                                           @endif
                                                <i class="nav-main-link-icon {{$department->icon}}"></i>
                                                <span class="nav-main-link-name">{{ucfirst($department->departmentname)}}</span>
                                            </a> 
                                           
                                             @if(count($checklist)> 0)
                                               <ul class="nav-main-submenu">
                                                 @foreach($checklist as $check)
                                                 <?php
                                                     $totalcheckpoints = DB::table('audit_checklist_points')->where('checklist_id',$check->id)->get();
                                                 ?>
                                                  <li class="nav-main-item">
                                                    <a class="nav-main-link" href="{{url('/user/internalaudit/checklistdetails/'.$check->id)}}">
                                                        <i class="nav-main-link-icon far fa-file-alt"></i>
                                                        <span class="nav-main-link-name">{{ucfirst($check->checklist_name)}}</span>
                                                        <span class="nav-main-link-badge badge badge-pill badge-success">{{count($totalcheckpoints)}}</span>
                                                    </a>
                                                  </li>
                                                  @endforeach
                                               </ul>
                                             @endif                                    
                                        </li> 
                                    @endforeach  
                                    @endif                
                                    </ul>
                                </li>
                                <li class="nav-main-item">
                                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                                        <i class="nav-main-link-icon si si-folder"></i>
                                        <span class="nav-main-link-name">Divisions</span>
                                    </a>
                                    <ul class="nav-main-submenu">
                                    @if(count($departments)>0)
                                      @foreach($departments as $department)
                                           <?php
                                                $divisions = $Division::where('department_id',$department->id)->get();                                             
                                            ?>
                                        <li class="nav-main-item">
                                           @if(count($divisions)> 0)
                                            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                                           @else
                                           <a class="nav-main-link" href="javascript:void(0)">
                                           @endif
                                                <i class="nav-main-link-icon {{$department->icon}}"></i>
                                                <span class="nav-main-link-name">{{ucfirst($department->departmentname)}}</span>
                                            </a>                                           
                                             @if(count($divisions)> 0)
                                               <ul class="nav-main-submenu">
                                                @foreach($divisions as $division)
                                                    <?php
                                                          $divchecklist = DB::table('audit_checklist')
                                                                                        ->where('division_id',$division->id)
                                                                                        ->where('is_deleted',0)
                                                                                        ->get();
                                                    
                                                    ?>
                                                    <li class="nav-main-item">
                                                        @if(count($divchecklist)> 0)
                                                            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                                                        @else
                                                            <a class="nav-main-link" href="javascript:void(0)">
                                                        @endif
                                                            <i class="nav-main-link-icon fa fa-clipboard-check"></i>
                                                            <span class="nav-main-link-name">{{ucfirst($division->name)}}</span>                             
                                                        </a>
                                                        @if(count($divchecklist)> 0)
                                                            <ul class="nav-main-submenu">
                                                                @foreach($divchecklist as $divcheck)
                                                                <?php
                                                                    $totaldivcheckpoints = DB::table('audit_checklist_points')->where('checklist_id',$divcheck->id)->get();
                                                                ?>
                                                                <li class="nav-main-item">
                                                                    <a class="nav-main-link" href="{{url('/user/internalaudit/checklistdetails/'.$divcheck->id)}}">
                                                                        <i class="nav-main-link-icon far fa-file-alt"></i>
                                                                        <span class="nav-main-link-name">{{ucfirst($divcheck->checklist_name)}}</span>
                                                                        <span class="nav-main-link-badge badge badge-pill badge-success">{{count($totaldivcheckpoints)}}</span>
                                                                    </a>
                                                                </li>
                                                                @endforeach
                                                            </ul>
                                                        @endif           
                                                    </li>
                                                @endforeach
                                               </ul>
                                             @endif                                    
                                        </li> 
                                    @endforeach  
                                    @endif                
                                    </ul>
                                </li>
                                <li class="nav-main-item">
                                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                                        <i class="nav-main-link-icon far fa-square"></i>
                                        <span class="nav-main-link-name">Sub-Divisions</span>
                                    </a>
                                    <ul class="nav-main-submenu">
                                    @if(count($departments)>0)
                                      @foreach($departments as $department)
                                           <?php
                                                $divisions = $Division::where('department_id',$department->id)->get();                                                                                   
                                            ?>
                                        <li class="nav-main-item">
                                           @if(count($divisions)> 0)
                                            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                                           @else
                                           <a class="nav-main-link" href="javascript:void(0)">
                                           @endif
                                                <i class="nav-main-link-icon {{$department->icon}}"></i>
                                                <span class="nav-main-link-name">{{ucfirst($department->departmentname)}}</span>
                                            </a> 
                                           
                                             @if(count($divisions)> 0)
                                               <ul class="nav-main-submenu">
                                                 @foreach($divisions as $division)
                                                 <?php
                                                    $sub_divisions = $Area::where('division_id',$division->id)->get();                                               
                                                  ?>
                                                  <li class="nav-main-item">
                                                      @if(count($sub_divisions)> 0)
                                                      <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                                                      @else
                                                      <a class="nav-main-link" href="javascript:void(0)">
                                                      @endif
                                                        <i class="nav-main-link-icon fa fa-clipboard-check"></i>
                                                        <span class="nav-main-link-name">{{ucfirst($division->name)}}</span>
                                                       
                                                    </a>
                                                    @if(count($divisions)> 0)
                                                    <ul class="nav-main-submenu">
                                                      @foreach($sub_divisions as $sub_division)
                                                        <?php
                                                           $areachecklist = DB::table('audit_checklist')
                                                                                     ->where('area_id',$sub_division->id)
                                                                                     ->where('is_deleted',0)
                                                                                     ->get();                          
                                                        ?>
                                                      <li class="nav-main-item">
                                                          @if(count($areachecklist)> 0)
                                                            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                                                          @else
                                                            <a class="nav-main-link" href="javascript:void(0)">
                                                          @endif
                                                            <i class="nav-main-link-icon far fa-images"></i>
                                                            <span class="nav-main-link-name">{{ucfirst($sub_division->name)}}</span>
                                                        </a>
                                                           @if(count($areachecklist)> 0)
                                                                <ul class="nav-main-submenu">
                                                                    @foreach($areachecklist as $areacheck)
                                                                    <?php
                                                                        $totalareacheckpoints = DB::table('audit_checklist_points')->where('checklist_id',$areacheck->id)->get();
                                                                    ?>
                                                                    <li class="nav-main-item">
                                                                        <a class="nav-main-link" href="{{url('/user/internalaudit/checklistdetails/'.$areacheck->id)}}">
                                                                            <i class="nav-main-link-icon far fa-file-alt"></i>
                                                                            <span class="nav-main-link-name">{{ucfirst($areacheck->checklist_name)}}</span>
                                                                            <span class="nav-main-link-badge badge badge-pill badge-success">{{count($totalareacheckpoints)}}</span>
                                                                        </a>
                                                                    </li>
                                                                    @endforeach
                                                                </ul>
                                                            @endif                
                                                      </li>
                                                      @endforeach
                                                    </ul>
                                                    @endif
                                                  </li>
                                                  @endforeach
                                               </ul>
                                             @endif                                    
                                        </li> 
                                    @endforeach  
                                    @endif                
                                    </ul>
                                </li>                              
                                <li class="nav-main-item">
                                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                                        <i class="nav-main-link-icon fa fa-globe-americas"></i>
                                        <span class="nav-main-link-name">Standard</span>
                                    </a>
                                    <ul class="nav-main-submenu">
                                      @foreach($isoclauses as $iso)
                                       <li class="nav-main-item">
                                            <a class="nav-main-link" href="javascript:void(0)">
                                               <i class="nav-main-link-icon fa fa-database"></i>
                                                <span class="nav-main-link-name">{{$iso}}</span>
                                            </a>
                                        </li>
                                       @endforeach
                                    </ul>
                                </li>

                                <li class="nav-main-item ml-10">
                                   @if(in_array(Auth::user()->email,$auditors))  
                                   <a class="nav-main-link" href="#" data-toggle="modal" data-target="#exampleModal">
                                        <i class="nav-main-link-icon fa fa-2x fa-file-medical" style="font-size:20px;"></i>
                                        <span class="nav-main-link-name ">Add checklist</span>                                      
                                    </a>
                                    @else
                                    <a class="nav-main-link" href="#">
                                        <i class="nav-main-link-icon fa fa-2x fa-file-medical" style="font-size:20px;"></i>
                                        <span class="nav-main-link-name">Add checklist</span>                                      
                                    </a>
                                    @endif
                                </li> 
                                
                            </ul>
                        </div>
                        <!-- END Navigation -->
                    </div>
                    <!-- END Horizontal Navigation - Hover Normal -->

<!-- add department checklist starts-->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Checklist</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <form id="add_checklist_ajax" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-success" id="successchecklistadded" style="display:none">
                        Checklist Successfully saved
                    </div>

                    <ul class="alert alert-danger" id="dangerchecklistadded" style="display:none"></ul>
                    <ul class="alert alert-danger" id="checklistnotadded" style="display:none"></ul>
                    <ul class="alert alert-danger" id="checklistalreadyadded" style="display:none"></ul>  
                       
                    <div class="form-group">
                        <label for="checklistname" class="col-form-label" style="font-size:12px;">Checklist Name: <span style="color:red;">*</span></label>
                        <input type="text" class="form-control" style="font-size:12px;" name="checklist_name" id="checklist_name" value="" required>
                    </div>

                    <div class="form-group">
                        <label for="department" class="col-form-label" style="font-size:12px;">Department: <span style="color:red;">*</span></label>
                         @if(isset($departmentActives) && count($departmentActives)>0)
                            <select class="form-control" name="department_id_chk" id="department_id_chk" 
                             style="font-size: 13px;" onchange="getdeptid(this.value)" required>
                              <option value="">Choose a Department</option>
                                @foreach($departmentActives as $dept)
                                <option value="{{$dept->id}}">{{$dept->departmentname}}</option>
                                @endforeach
                            </select>
                         @endif
                    </div>
                    <div class="form-group" style="display:none;" id="division_frm">
                        <label for="department" class="col-form-label" style="font-size:12px;">Division:</label>
                            <select class="form-control" name="division_id_chk" id="division_id_chk" 
                             style="font-size: 13px;" onchange="getdivisionid(this.value)">                            
                            </select>
                    </div>
                    <div class="form-group" style="display:none;" id="sub_div_frm">
                        <label for="department" class="col-form-label" style="font-size:12px;">Subdivision:</label>                  
                            <select class="form-control" name="sub_division_id_chk" id="sub_division_id_chk" style="font-size: 13px;">                       
                            </select>                     
                    </div>                  
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                    <button type="submit" class="btn btn-primary" style="font-size:12px">Submit</button>
                </div>
            </form>
            </div>
        </div>
    </div>

<!-- add department checklist  ends-->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

{{----------- button for add checklist------------}}

<script>
    
    let CSRF_TOKEN = "";
    window.onload = function() {
        CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    }
    
 function getdeptid(depts_id){
    $("#division_id_chk").val("");
    document.getElementById('sub_div_frm').style.display="none";
    $("#sub_division_id_chk").val("");
        $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.selectdivisiondetails')}}",
                    data: {
                                "depts_id_value": depts_id
                                , _token: CSRF_TOKEN
                            }          
                    ,success: function(response) {
                        divisionslist = response.divisions;
                         if(divisionslist.length>0){
                            document.getElementById('division_frm').style.display="";
                            let selectElement = $('#division_id_chk');
                            selectElement.empty(); 
                            selectElement.append($("<option value=''>Choose Division</option>"));                           
                            $.each(response.divisions, function (index, option) {
                                let optionElement = $('<option>', {
                                    value: option.id,
                                    text: option.name
                                });
                                selectElement.append(optionElement);
                            });
                         }
                         else{
                            document.getElementById('division_frm').style.display="none";
                         }              
                    }
                    ,error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }	
            });
    }
    
    function getdivisionid(divis_id){
        $("#sub_division_id_chk").val("");
        $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.selectsubdivisiondetails')}}",
                    data: {
                                "division_id_value": divis_id
                                , _token: CSRF_TOKEN
                            }          
                    ,success: function(response) {                     
                        subdivisionslist = response.subdivisions;
                         if(subdivisionslist.length>0){
                            document.getElementById('sub_div_frm').style.display="";
                            let selectElement = $('#sub_division_id_chk');
                            selectElement.empty(); 
                            selectElement.append($("<option value=''>Choose SubDivision</option>"));                          
                            $.each(response.subdivisions, function (index, option) {
                                let optionElement = $('<option>', {
                                    value: option.id,
                                    text: option.name
                                });
                                selectElement.append(optionElement);
                            });
                         }
                         else{
                            document.getElementById('sub_div_frm').style.display="none";
                         }               
                    }
                    ,error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }	
            });
    }


/**-------------add checklist starts---------------- */
   $(document).ready(function() {
                $('#add_checklist_ajax').submit(function(event) {
                    event.preventDefault();
                    var formData = $(this).serialize();
                    $.ajax({
                        type: 'POST',
                        url: '{{ route("addchecklist") }}',
                        data: formData
                        , _token: CSRF_TOKEN
                        ,success: function(response) {
                            console.log(response.status);
                            if(response.status == 200)
                            {
                                $('#checklistnotadded').hide();
                                $('#checklistalreadyadded').hide();
                                $('#dangerchecklistadded').hide();
                                $('#successchecklistadded').css('display', 'block');
                                setTimeout(function() {
                                    window.location.reload();
                                }, 1000);
                            }
                            else if(response.status == 403)
                            {
                                console.log(response.chkerrmsg);
                                var checklistalreadyexists = response.chkerrmsg;
                                $('#dangerchecklistadded').hide();
                                $('#checklistnotadded').hide();
                                $('#checklistalreadyadded').html(checklistalreadyexists); 
                                $('#checklistalreadyadded').css('display', 'block');
                            }
                        },                                    
                        error: function(err) { 
                            if(err.status === 500) {
                                $('#checklistnotadded').hide();
                                $('#checklistalreadyadded').hide();
                                $('#dangerchecklistadded').css('display', 'block');
                            }
                            else if (err.status === 422) {
                                var error_text='';                               
                                $.each(err.responseJSON.errors, function(i) {
                                $.each(err.responseJSON.errors[i], function(key, val) {
                                    error_text += '<li style="font-size:12px;">' + val + '</li>';
                              });
                            });                               
                                $('#checklistnotadded').html(error_text);
                                $('#dangerchecklistadded').hide();
                                $('#checklistalreadyadded').hide();
                                $('#checklistnotadded').css('display', 'block');
                            }                            
                        }
                    });
                });
           });

   /**-------------add checklist ends---------------- */ 

</script>
