@extends('admin.datatable')
@section('content')
<style>
    #page-overlay {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        content: "";
        /* background-color: rgba(0, 0, 0, 0.6); */
        background-color: rgba(0, 0, 0, 0.1);
        z-index: 1033;
        opacity: 0;
        transition: opacity 0.28s ease-out;
        -webkit-transform: translateY(-100%);
                transform: translateY(-100%);
    }
    #nav-btn::before{
        content: "";
    }
</style>
    <!-- Page Content -->
   <div class="content">
       @if(Session::has('messagedeldept'))
        <div class="alert alert-primary">
            <a class="close" data-dismiss="alert">×</a>
            {!!Session::get('messagedeldept')!!}
        </div>
        @endif
        @if(Session::has('message'))
        <div class="alert alert-primary">
            <a class="close" data-dismiss="alert">×</a>
            {!!Session::get('message')!!}
        </div>
        @endif

        <!-- Dynamic Table Full -->
          <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">Departments Table</h3>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page" id="nav-btn">
                            <a href="#" class="btn btn-sm btn-alt-primary" style="font-size:12px;" data-toggle="modal"
                            data-target="#departmentModal">Add Departments</a>
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="alert alert-primary" id="enable_disable_dept_msg" style="font-size:12px;display:none;">
            </div>
           <!-- Department data lists starts  -->  
            <div class="block-content block-content-full" style="width:95%;">
                <div class="table-responsive">
                    <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                    <table class="table table-bordered table-striped js-dataTable-full">
                        <thead>
                            <tr>
                                <th class="text-left" style="width: 2%;font-size:10px;text-transform:capitalize">SL No.</th>
                                <th class="d-sm-table-cell text-left"
                                    style="width: 5%;font-size:10px;text-transform:capitalize">Department Name</th>
                                <th class="d-sm-table-cell text-left"
                                    style="width: 5%;font-size:10px;text-transform:capitalize">Code</th>
                                <th class="d-sm-table-cell text-left"
                                    style="width: 5%;font-size:10px;text-transform:capitalize">HOD</th>
                                <th class="d-sm-table-cell text-left"
                                    style="width: 5%;font-size:10px;text-transform:capitalize">HOD Assistants</th>
                                <th class="d-sm-table-cell text-left"
                                    style="width:2%;font-size:10px;text-transform:capitalize">Scope</th>
                                <th class="text-left" style="width: 2%;font-size:10px;text-transform:capitalize">Actions
                                </th>
                                <th 
                                    style="width:2%;">
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $number = 1; ?>
                            @foreach($departments as $c)

                            {{csrf_field()}}

                            <?php $id = $c->id ; $dept=$c->departmentname;$code=$c->code;$isoclauses=$c->isoclauses; $hod=$c->hodname; $hodp=$c->hodposition ; $hode=$c->hod?>
                            <tr>

                                <td style="font-size:10px" class="text-left">
                                    <span>{{ $number }}</span>
                                </td>

                                <td style="font-size:10px" class="text-left">
                                    <span>{{$c->departmentname}}</span>
                                </td>
                                <td style="font-size:10px" class="text-left">
                                    <span>{{$c->code}}</span>
                                </td>
                                <td style="font-size:10px" class="text-left">
                                    <span>{{$c->hodname}}</span>
                                </td>
                                <td style="font-size:10px" class="text-left">
                                    <span>{{$c->hod_assistants}}</span>
                                </td>
                                <td style="font-size:10px" class="text-left">
                                    @if($c->scopes_other==NULL)
                                    {{$c->scopes}}
                                    @else
                                    {{$c->scopes_other}}
                                    @endif
                                </td>

                                <?php $number++; ?>

                                <td class="text-left">
                                    <div class="btn-group">
                                        <a href="{{url('/admin/editDepartment?val='.$id.'&dept='.$dept.'&hod='.$hod.'&hodposition='.$hodp.'&code='.$code.'&isoclauses='.$isoclauses.'&hodemail='.$hode)}}"
                                            style="font-size:10px;" class="btn btn-sm btn-alt-primary" data-toggle="tooltip"
                                            title="Edit"><i class="fa fa-fw fa-pencil-alt"></i>
                                        </a>

                                        <a href="{{url('/admin/CarryDepartmentID?val='.$id)}}"
                                            class="btn btn-sm btn-alt-primary" style="font-size:10px;"
                                            onclick="return confirmdeptdelete()" data-toggle="tooltip" title="Remove"><i
                                                class="fa fa-fw fa-times"></i>
                                        </a>

                                        <a href="{{url('/admin/addhodassistants?val='.$id.'&dept='.$dept.'&hod='.$hod)}}"
                                            class="btn btn-sm btn-alt-primary" data-toggle="tooltip" style="font-size:10px;"
                                            title="Add HOD Assistants"> <i class="fa fa-fw fa-user"></i></a>
                                    

                                        <button type="button" class="btn btn-sm btn-alt-primary" style="font-size:10px"
                                            data-toggle="tooltip" title="Add New Divison"
                                            onclick="divisionBtn('{{$id}}')"><strong><i
                                                    class="fa fa-fw fa-plus"></i></strong>
                                        </button>

                                        <a href="#" class="btn btn-sm btn-alt-primary" style="font-size:10px;" 
                                        data-toggle="layout" data-action="side_overlay_toggle" 
                                        title="View Divisions" onclick="getdepartmentdetails('{{$id}},{{$c->departmentname}}')">
                                                <i class="far fa-eye text-center"></i>
                                        </a>

                                        <div id="division{{$id}}" class="modal fadebd-example-modal-lg" tabindex="-1"
                                            role="dialog" aria-hidden="false">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h2 class="modal-title">Add division</h2>
                                                    </div>
                                                    <div class="alert alert-success d-none ml-3 mr-3"
                                                        id="success_division_create_{{$id}}">Division created successfully
                                                    </div>
                                                    <ul class="alert alert-danger d-none ml-3 mr-3"
                                                        id="danger_division_create_{{$id}}"></ul>
                                                    <div class="col-lg-12">
                                                        <div class="form-group row">
                                                            <div class="col-sm-12">
                                                                <input type="text" class="form-control" name="name"
                                                                    id="division_name_{{$id}}" style="font-size: 13px;"
                                                                    placeholder="division name">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">

                                                            <div class="col-sm-12">
                                                                <select class="form-control" id="owner_id_{{$id}}"
                                                                    name="owner_id" style="font-size:13px" required>
                                                                    <option value="0">Please select division owner</option>
                                                                    @if(count($user)>0)
                                                                    @foreach($user as $use)
                                                                    <option required value="{{$use->id}}">{{$use->name}}
                                                                    </option>
                                                                    @endforeach
                                                                    @endif
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" value="{{ $id }}" name="department_id">
                                                    </div>


                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-primary"
                                                            onClick="saveDivision({{$id}})">Save</button>
                                                        <button type="button" class="btn btn-dark"
                                                            data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>

                                        
                                        </div>
                                </td>
                                <td>
                                <div class="custom-control custom-switch custom-control-success mb-1">
                                        <input type="checkbox" name="is_department_active_{{$id}}" id="is_department_active_{{$id}}"
                                            value="{{ $id }}"
                                            data-route="{{ route('admin.dept.enable.disable', $id) }}"
                                            class="custom-control-input js_is_active_dept"
                                            {{ $c->is_active ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_department_active_{{$id}}"
                                            data-toggle="tooltip" 
                                            title="{{ $c->is_active ? 'Click here to disable department' : 'Click here to enable department' }}">
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
         <!-- Department data lists ends  --> 
         
        <!-- side bar - division and subdivision starts here -->       
         <!-- Side Overlay-->
         <aside id="side-overlay">
                <!-- Side Header -->
                <div class="content-header border-bottom">                                 
                    <div class="ml-2">
                        <a class="text-danger text-capitalize font-w600 font-size-sm" href="javascript:void(0)" id="showdeptname"></a>
                    </div>  
                                
                    <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                    <a class="ml-auto btn btn-sm btn-alt-danger" href="javascript:void(0)" data-toggle="layout" data-action="side_overlay_close">
                        <i class="fa fa-fw fa-times"></i>
                    </a>
                </div>
                <!-- END Side Header -->

                <!-- Side Content -->
                <div class="content-side">
                    <!-- Side Overlay Tabs -->
                    <div class="block block-transparent pull-x pull-t">
                        <ul class="nav nav-tabs nav-tabs-alt nav-justified" data-toggle="tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" href="#so-overview">
                                    <i class="fa fa-fw fa-coffee text-gray mr-1"></i> Divisions
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#so-sales">
                                    <i class="fa fa-fw fa-chart-line text-gray mr-1"></i> Location
                                </a>
                            </li>
                        </ul>                   
                        <div class="block-content tab-content" style="overflow-x: hidden;overflow-y: auto">
                            <!-- Divisions Tab starts-->
                            <div class="tab-pane pull-x fade fade-left show active" id="so-overview" role="tabpanel">                                                       
                                 <table id="getdivisionnms" class="table table-bordered" width='100%'>                                 
                                 </table>                           
                            </div>
                            <!-- END Divisions Tab -->
                            <!-- Location Tab starts-->
                            <div class="tab-pane pull-x fade fade-right" id="so-sales" role="tabpanel">
                                <div class="block mb-0">   
                                   <table id="getlocationnms" class="table table-borderless" width='100%'>                                  
                                    </table>                                 
                                    <div class="block-content block-content-full block-content-sm bg-body-light">
                                        <div class="row">
                                            <div class="col-12" align="center">
                                                <a class="btn btn-sm btn-secondary" style="font-size:13px;width:150px;" href="#" data-toggle="modal" data-target="#addlocationModal">
                                                  <span data-toggle="tooltip" title="Click here to add locations under this department">Add location</span>
                                                </a>                                              
                                            </div>                    
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END Location Tab -->
                        </div>
                    </div>
                    <!-- END Side Overlay Tabs -->
                </div>
                <!-- END Side Content -->
            </aside>
            <!-- END Side Overlay -->
        <!-- side bar - division and subdivision ends here -->


        <!-- edit division  starts-->
        <div class="modal fade" id="editdivisionModal" tabindex="-1" role="dialog" aria-labelledby="editdivisionModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editdivisionModalLabel">Edit Division</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <form id="edit_departments_division_ajax" method="POST" enctype="multipart/form-data">
                        @csrf
                    <div class="modal-body">
                        <div class="alert alert-success" id="successdivisionupdate" style="display:none">
                            Division updated successfully
                        </div>

                        <ul class="alert alert-danger" id="dangerdivisionupdate" style="display:none">
                        Internal server error, we are trying to fix them soon
                        </ul>

                        <ul class="alert alert-danger" id="divisiondataupdtempty" style="display:none"></ul>

                        <div class="form-group">
                            <label for="department" class="col-form-label" style="font-size:12px;"> Department </label>
                            <input type="text" class="form-control" style="font-size:12px;" name="department_value" id="department_value" value="" readonly>
                        </div>

                        <div class="form-group">
                            <label for="divisionname" class="col-form-label" style="font-size:12px;"> Division Name <span style="color:red">*</span></label>
                            <input type="text" class="form-control" style="font-size:12px;" name="division_name_value" id="division_name_value" value="" >
                        </div>

                        <div class="form-group">
                            <label for="divisionowner" class="col-form-label" style="font-size:12px;"> Division Owner <span style="color:red">*</span></label>
                            <select class="form-control" id="division_owner" name="division_owner" style="font-size:12px" required>
                                <option value="">Please select division owner</option>
                            </select>
                        </div>                  
                    </div>
                    <input type="hidden" name="division_main_id" id="division_main_id" value=""/>             
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                        <button type="submit" class="btn btn-primary" style="font-size:12px">Update</button>
                    </div>
                </form>
                </div>
            </div>
        </div>

<!-- edit division ends-->

<!-- add subdivision  starts-->
<div class="modal fade" id="addsubdivisionModal" tabindex="-1" role="dialog" aria-labelledby="addsubdivisionModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addsubdivisionModalLabel">Add Subdivisions</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <form id="add_departments_sub_division_ajax" method="POST" enctype="multipart/form-data">
                    @csrf
                <div class="modal-body">
                    <div class="alert alert-success" id="successsubdivisionadded" style="display:none">
                        Subdivision added successfully
                    </div>

                    <ul class="alert alert-danger" id="dangersubdivisionadded" style="font-size:12px;display:none">
                       Internal server error, we are trying to fix them soon
                    </ul> 
                    
                    <ul class="alert alert-danger" id="subdivisionalreadyadded" style="font-size:12px;display:none">
                       This Subdivision already exists
                    </ul> 
                                      
                    <ul class="alert alert-danger" id="subdivisionnotadded" style="display:none"></ul>  

                    <div class="form-group">
                        <label for="division" class="col-form-label" style="font-size:12px;"> Division</label>
                        <input type="text" class="form-control" style="font-size:12px;" name="divi_name_value" id="divi_name_value" value="" readonly>
                    </div>

                    <div class="form-group">
                        <label for="subdivision" class="col-form-label" style="font-size:12px;"> Subdivision Name <span style="color:red">*</span></label>
                        <input type="text" class="form-control" style="font-size:12px;" 
                          name="sub_division_name_value" id="sub_division_name_value" value="" placeholder="Please enter Subdivision name" required>
                    </div>

                    <div class="form-group">
                        <label for="divisionowner" class="col-form-label" style="font-size:12px;"> Division Owner <span style="color:red">*</span></label>
                        <select class="form-control" id="division_owner" name="division_owner" style="font-size:12px" required>
                            <option value="">Please select division owner</option>
                            @if(count($user)>0)
                            @foreach($user as $use)
                            <option value="{{$use->id}}">{{$use->name}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>                  
                </div>
                 <input type="hidden" name="divi_main_id" id="divi_main_id" value=""/>             
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                    <button type="submit" class="btn btn-primary" style="font-size:12px">Add</button>
                </div>
            </form>
            </div>
        </div>
    </div>

<!-- add subdivision ends-->

    <!-- edit subdivision  starts-->
    <div class="modal fade" id="editsubdivisionModal" tabindex="-1" role="dialog" aria-labelledby="editsubdivisionModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editsubdivisionModalLabel">Edit Subdivision</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <form id="edit_departments_sub_division_ajax" method="POST" enctype="multipart/form-data">
                        @csrf
                    <div class="modal-body">
                        <div class="alert alert-success" id="successsubdivisionupdate" style="display:none">
                            Subdivision updated successfully
                        </div>

                        <ul class="alert alert-danger" id="dangersubdivisionupdate" style="display:none">
                        Internal server error, we are trying to fix them soon
                        </ul>

                        <ul class="alert alert-danger" id="subdivisionalreadyuptadded" style="font-size:12px;display:none">
                        This Subdivision already exists
                        </ul> 
                                        
                        <ul class="alert alert-danger" id="subdivisionnotuptadded" style="display:none"></ul>  

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label for="department" class="col-form-label" style="font-size:12px;"> Department </label>
                            <input type="text" class="form-control" style="font-size:12px;" name="depart_sub_div_value" id="depart_sub_div_value" value="" readonly>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label for="divisionname" class="col-form-label" style="font-size:12px;"> Division</label>
                            <input type="text" class="form-control" style="font-size:12px;" name="divi_sub_name_value" id="divi_sub_name_value" value="" readonly>
                        </div>

                        <div class="form-group">
                            <label for="subdivisionname" class="col-form-label" style="font-size:12px;"> Subdivision Name <span style="color:red">*</span></label>
                            <input type="text" class="form-control" style="font-size:12px;" name="sub_divi_name_value" id="sub_divi_name_value" value="" required>
                        </div>

                        <div class="form-group">
                            <label for="subdivisionowner" class="col-form-label" style="font-size:12px;"> Subdivision Owner <span style="color:red">*</span></label>
                            <select class="form-control" id="sub_division_owner" name="sub_division_owner" style="font-size:12px" required>
                                <option value="">Please select subdivision owner</option>
                            </select>
                        </div>                  
                    </div>
                    <input type="hidden" name="sub_division_main_id" id="sub_division_main_id" value=""/>
                    <input type="hidden" name="main_divi_id" id="main_divi_id" value=""/>             
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                        <button type="submit" class="btn btn-primary" style="font-size:12px">Update</button>
                    </div>
                </form>
                </div>
            </div>
        </div>

    <!-- edit subdivision ends-->

    <!-- add location  starts-->
    <div class="modal fade" id="addlocationModal" tabindex="-1" role="dialog" aria-labelledby="addlocationModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addlocationModalLabel">Add Location</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <form id="add_departments_location_ajax" method="POST" enctype="multipart/form-data">
                        @csrf
                    <div class="modal-body">
                        <div class="alert alert-success" id="successlocationadded" style="display:none">
                            Location added successfully
                        </div>

                        <ul class="alert alert-danger" id="dangerlocationadded" style="display:none">
                        Internal server error, we are trying to fix them soon
                        </ul>

                        <ul class="alert alert-danger" id="locationalreadyexists" style="font-size:12px;display:none">
                        This location already exists for this department
                        </ul> 
                                        
                        <ul class="alert alert-danger" id="locationnotadded" style="display:none"></ul>                    

                        <div class="form-group">
                            <label for="locationname" class="col-form-label" style="font-size:12px;"> Location <span style="color:red">*</span></label>
                            <input type="text" class="form-control" style="font-size:12px;" name="location_value" id="location_value" value="" required>
                        </div>      
                    </div>      
                    <input type="hidden" name="dept_locat_id" id="dept_locat_id" value=""/>             
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                        <button type="submit" class="btn btn-primary" style="font-size:12px">Submit</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    <!-- add location ends-->

    <!-- edit location  starts-->
    <div class="modal fade" id="editlocationModal" tabindex="-1" role="dialog" aria-labelledby="editlocationModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editlocationModalLabel">Edit Location</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <form id="edit_departments_location_ajax" method="POST" enctype="multipart/form-data">
                        @csrf
                    <div class="modal-body">
                        <div class="alert alert-success" id="successlocationupdate" style="display:none">
                            Location updated successfully
                        </div>

                        <ul class="alert alert-danger" id="dangerlocationupdate" style="display:none">
                        Internal server error, we are trying to fix them soon
                        </ul>

                        <ul class="alert alert-danger" id="locationalreadyupdatedexists" style="font-size:12px;display:none">
                        This location already exists
                        </ul> 
                                        
                        <ul class="alert alert-danger" id="locationnotupdated" style="display:none"></ul>  

                        <div class="form-group">
                            <label for="department" class="col-form-label" style="font-size:12px;"> Department </label>
                            <input type="text" class="form-control" style="font-size:12px;" name="depart_locat_value" id="depart_locat_value" value="" readonly>
                        </div>

                        <div class="form-group">
                            <label for="locationname" class="col-form-label" style="font-size:12px;"> Location <span style="color:red">*</span></label>
                            <input type="text" class="form-control" style="font-size:12px;" name="location_upt_value" id="location_upt_value" value="" required>
                        </div>      
                    </div>      
                    <input type="hidden" name="location_upt_id" id="location_upt_id" value=""/>             
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                        <button type="submit" class="btn btn-primary" style="font-size:12px">Update</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    <!-- edit location ends-->

    <!-- add department starts-->
    <div class="modal fade" id="departmentModal" tabindex="-1" role="dialog" aria-labelledby="departmentModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="departmentModalLabel">Add Departments</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="add_departments_ajax" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="alert alert-success" id="successdeptadded" style="display:none">
                                Department added successfully
                            </div>

                            <ul class="alert alert-danger" id="dangerdeptadded" style="font-size:12px;display:none">
                                Internal server error, we are trying to fix them soon
                            </ul> 

                            <ul class="alert alert-danger" id="deptalreadyadded" style="display:none">
                                This department already exists
                            </ul>

                            <ul class="alert alert-danger" id="dangerdeptnotadded" style="display:none"></ul>  

                            <div class="form-group">
                                <label for="title" class="col-sm-12 col-form-label" style="font-size:12px;"> Department <span style="color:red">*</span>
                                </label>
                                <div class="col-sm-12">
                                    <select class="form-control" id="department" name="department" style="font-size:12px"
                                        onchange="selectdeptcode(this.value)" required>
                                        <option value="">Please Select Department</option>
                                        @if(count($departmentdetails)>0)
                                        @foreach($departmentdetails as $departmentdetail)
                                        <option value="{{$departmentdetail->name}}">{{$departmentdetail->name}}</option>
                                        @endforeach
                                        <option value="other">Others</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="form-group" id="department_oth">
                                <label for="title" class="col-sm-12 col-form-label" style="font-size:12px;"> </label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" style="font-size:12px;" name="department_other"
                                        id="department_other" value="" placeholder="Please Specify Department">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="title" class="col-sm-12 col-form-label" style="font-size:12px;"> Department Code <span style="color:red">*</span>
                                </label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" style="font-size:12px;" name="depcode"
                                        id="depcode" value="" required placeholder="Enter Department Code">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="title" class="col-sm-12 col-form-label" style="font-size:12px;">Select HOD <span style="color:red">*</span>
                                </label>
                                <div class="col-sm-12">
                                    <select class="form-control" id="hod" name="hod" onchange="ddlselect()"
                                        style="font-size:12px" required>
                                        <option value="">Please select HOD</option>
                                        @if(count($user)>0)
                                        @foreach($user as $use)
                                        <option value="{{$use->id}}">{{$use->name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <input type="hidden" id="hodname" name="hodname" value="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="title" class="col-sm-12 col-form-label" style="font-size:12px;"> Position
                                </label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" style="font-size:12px;" name="position"
                                        id="position" value="" placeholder="Enter HOD position">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="title" class="col-sm-12 col-form-label" style="font-size:12px;"> Please Select
                                    Scope <span style="color:red">*</span> </label>
                                <div class="col-sm-12">
                                    <select class="js-select2 form-control" name="scope[]"
                                        style="font-size: 12px;width:350px;" required multiple
                                        data-placeholder="Select Scope" onchange="displayscopeoth(this.value)">
                                        @if(count($scopes)>0)
                                        @foreach($scopes as $scope)
                                        <option value="{{$scope->name}}">{{$scope->name}}</option>
                                        @endforeach
                                        <option value="other">Others</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="form-group" id="scope_oth">
                                <span class="col-sm-12 text-danger" style="font-size:10px;">Please Enter Scope separated by
                                    commas. For example: Workforce management, employee support,Technology support</span>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" style="font-size:12px;" name="scope_other[]"
                                        id="scope_other" value="" placeholder="Please Enter Scope">
                                </div>
                            </div>


                            <a class="btn btn-sm btn-alt-success" style="margin-left:15px;" id="icon" data-id="icon"
                                data-target="#icons" data-toggle="modal" title="View">
                                <span>Choose Department Icon</span>
                            </a>
                            <input type="hidden" id="ficon" name="ficon">

                        </div>


                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                style="font-size:12px">Close</button>
                            <button type="submit" class="btn btn-primary" style="font-size:12px">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
    </div>
        <!-- add department  ends-->
        <!-- department icon starts -->
    <div class="modal fade" id="icons" tabindex="-1" role="dialog" aria-labelledby="modal-block-fadein"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">Choose Icons</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="js-icon-list row items-push-2x text-center">
                            @if(count($icons)>0)
                            @for($i=0;$i<count($icons);$i++) <div class="col-sm-6 col-lg-4 col-xl-3">
                                <a href="#" class="" onclick="return selectedicon('{{$icons[$i]}}')">
                                    <p><i class="{{$icons[$i]}} fa-2x" style="color:grey"></i></p>
                                    <code style="color:grey">{{$icons[$i]}}</code>
                                </a>
                        </div>
                        @endfor
                        @endif
                    </div>
                </div>
                <div class="block-content block-content-full text-right border-top">
                    <button type="button" class="btn btn-alt-primary mr-1" data-dismiss="modal" style="font-size:10px;"
                        id="close">Close</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal"
                        style="font-size:10px;">Ok</button>
                </div>
            </div>
        </div>
    </div>
</div>
    

    <script>
        function selectedicon(icon) {
            document.getElementById('icon').innerText = icon;
            document.getElementById('ficon').value = icon;
            document.getElementById('close').click();
            return false;
        }
    </script>
    <!-- department icon ends -->


          </div>
        <!-- End Dynamic Table Full -->
   </div>
   <!-- END Page Content -->
<script>
    let CSRF_TOKEN = "";
        window.onload = function() {
            CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $(document).ready(function(){
                $('#department_oth').hide();
                $('#scope_oth').hide();
            });

    /**-------------insert department starts---------------- */
        $(document).ready(function() {
                $('#add_departments_ajax').submit(function(event) {
                    event.preventDefault();
                    var formData = $(this).serialize();
                    $.ajax({
                        type: 'POST',
                        url: '{{ route("admin.savedepartmentsajax") }}',
                        data: formData
                        , _token: CSRF_TOKEN
                        ,success: function(response) {
                            if(response.status == 200)
                            {
                                $('#dangerdeptadded').hide();
                                $('#dangerdeptnotadded').hide();
                                $('#deptalreadyadded').hide();
                                $('#successdeptadded').css('display', 'block');
                                setTimeout(function() {
                                    window.location.reload();
                                }, 1000);
                            }
                            else if(response.status == 403)
                            {
                                $('#dangerdeptadded').hide();
                                $('#dangerdeptnotadded').hide();
                                $('#deptalreadyadded').css('display', 'block');
                            }
                        },
                        error: function(err) { 
                            if(err.status === 500) {
                                $('#dangerdeptnotadded').hide();
                                $('#deptalreadyadded').hide();
                                $('#dangerdeptadded').css('display', 'block');
                            }
                            else if (err.status === 422) {
                                var error_text='';                               
                                $.each(err.responseJSON.errors, function(i) {
                                $.each(err.responseJSON.errors[i], function(key, val) {
                                    error_text += '<li style="font-size:12px;">' + val + '</li>';
                              });
                            });                               
                                $('#dangerdeptnotadded').html(error_text);
                                $('#dangerdeptadded').hide();
                                $('#deptalreadyadded').hide();
                                $('#dangerdeptnotadded').css('display', 'block');
                            } 
                        }                      
                    });
                });
      /**-------------insert department ends---------------- */

        /**-------------update division starts---------------- */
         $(document).ready(function() {
                $('#edit_departments_division_ajax').submit(function(event) {
                    event.preventDefault();
                    var formData = $(this).serialize();
                    $.ajax({
                        type: 'POST',
                        url: '{{ route("admin.updatedeptdivisionajax") }}',
                        data: formData
                        , _token: CSRF_TOKEN
                        ,success: function(response) {
                            if(response.status == 200)
                            {
                                $('#dangerdivisionupdate').hide();
                                $('#divisiondataupdtempty').hide();
                                $('#successdivisionupdate').css('display', 'block');
                                setTimeout(function() {
                                    window.location.reload();
                                }, 1000);
                            }
                            else if(response.status == 500)
                            {
                                $('#dangerdivisionupdate').css('display', 'block');
                                setTimeout(function() {
                                    window.location.reload();
                                }, 1000);
                            }
                        },
                        error: function(err) {
                            if (err.status === 422) {
                                var error_text='';                               
                                $.each(err.responseJSON.errors, function(i) {
                                $.each(err.responseJSON.errors[i], function(key, val) {
                                    error_text += '<li style="font-size:12px;">' + val + '</li>';
                              });
                            });                               
                                $('#divisiondataupdtempty').html(error_text);
                                $('#dangerdivisionupdate').hide();
                                $('#divisiondataupdtempty').css('display', 'block');
                            }            

                        }
                    });
                });
           });
    /**-------------update division ends---------------- */

    /**-------------update subdivision starts---------------- */
    $(document).ready(function() {
                $('#edit_departments_sub_division_ajax').submit(function(event) {
                    event.preventDefault();
                    var formData = $(this).serialize();
                    $.ajax({
                        type: 'POST',
                        url: '{{ route("admin.updatedeptsubdivisionajax") }}',
                        data: formData
                        , _token: CSRF_TOKEN
                        ,success: function(response) {
                            if(response.status == 200)
                            {
                                $('#dangersubdivisionupdate').hide();
                                $('#subdivisionalreadyuptadded').hide();
                                $('#subdivisionnotuptadded').hide();
                                $('#successsubdivisionupdate').css('display', 'block');
                                setTimeout(function() {
                                    window.location.reload();
                                }, 1000);
                            } 
                            else if(response.status == 500)
                            {
                                $('#dangersubdivisionupdate').css('display', 'block');
                                setTimeout(function() {
                                    window.location.reload();
                                }, 1000);
                            }
                        },
                        // error: function(xhr, status, error) {
                        //     console.error(xhr.responseText);
                        // }
                        error: function(err) {
                             if (err.status === 422) {
                                var error_text='';                               
                                $.each(err.responseJSON.errors, function(i) {
                                $.each(err.responseJSON.errors[i], function(key, val) {
                                    error_text += '<li style="font-size:12px;">' + val + '</li>';
                              });
                            });                               
                                $('#subdivisionnotuptadded').html(error_text);
                                $('#dangersubdivisionadded').hide();
                                $('#subdivisionnotuptadded').css('display', 'block');
                            }            
                        }
                    });
                });
           });
    /**-------------update subdivision ends---------------- */


     /**-------------insert subdivision starts---------------- */
     $(document).ready(function() {
                $('#add_departments_sub_division_ajax').submit(function(event) {
                    event.preventDefault();
                    var formData = $(this).serialize();
                    $.ajax({
                        type: 'POST',
                        url: '{{ route("admin.insertdeptsubdivisionajax") }}',
                        data: formData
                        , _token: CSRF_TOKEN
                        ,success: function(response) {
                            if(response.status == 200)
                            {
                                $('#subdivisionnotadded').hide();
                                $('#subdivisionalreadyadded').hide();
                                $('#dangersubdivisionadded').hide();
                                $('#successsubdivisionadded').css('display', 'block');
                                setTimeout(function() {
                                    window.location.reload();
                                }, 1000);
                            }
                            else if(response.status == 403)
                            {
                                $('#dangersubdivisionadded').hide();
                                $('#subdivisionnotadded').hide();
                                $('#subdivisionalreadyadded').css('display', 'block');
                            }
                        },                                    
                        error: function(err) { 
                            if(err.status === 500) {
                                $('#subdivisionnotadded').hide();
                                $('#subdivisionalreadyadded').hide();
                                $('#dangersubdivisionadded').css('display', 'block');
                            }
                            else if (err.status === 422) {
                                var error_text='';                               
                                $.each(err.responseJSON.errors, function(i) {
                                $.each(err.responseJSON.errors[i], function(key, val) {
                                    error_text += '<li style="font-size:12px;">' + val + '</li>';
                              });
                            });                               
                                $('#subdivisionnotadded').html(error_text);
                                $('#dangersubdivisionadded').hide();
                                $('#subdivisionalreadyadded').hide();
                                $('#subdivisionnotadded').css('display', 'block');
                            }                            
                        }
                    });
                });
           });
    /**-------------insert subdivision ends---------------- */

    /**-------------insert location starts---------------- */
    $(document).ready(function() {
                $('#add_departments_location_ajax').submit(function(event) {
                    event.preventDefault();
                    var formData = $(this).serialize();
                    $.ajax({
                        type: 'POST',
                        url: '{{ route("admin.insertdeptlocationajax") }}',
                        data: formData
                        , _token: CSRF_TOKEN
                        ,success: function(response) {
                            if(response.status == 200)
                            {
                                $('#dangerlocationadded').hide();
                                $('#locationalreadyexists').hide();
                                $('#locationnotadded').hide();
                                $('#successlocationadded').css('display', 'block');
                                setTimeout(function() {
                                    window.location.reload();
                                }, 1000);
                            }
                            else if(response.status == 403)
                            {
                                $('#dangerlocationadded').hide();
                                $('#locationnotadded').hide();
                                $('#locationalreadyexists').css('display', 'block');
                            }
                        },                                    
                        error: function(err) { 
                            if(err.status === 500) {
                                $('#locationnotadded').hide();
                                $('#locationalreadyexists').hide();
                                $('#dangerlocationadded').css('display', 'block');
                            }
                            else if (err.status === 422) {
                                var error_text='';                               
                                $.each(err.responseJSON.errors, function(i) {
                                $.each(err.responseJSON.errors[i], function(key, val) {
                                    error_text += '<li style="font-size:12px;">' + val + '</li>';
                              });
                            });                               
                                $('#locationnotadded').html(error_text);
                                $('#dangerlocationadded').hide();
                                $('#locationalreadyexists').hide();
                                $('#locationnotadded').css('display', 'block');
                            }                            
                        }
                    });
                });
           });
    /**-------------insert location ends---------------- */

     /**-------------update location starts---------------- */
     $(document).ready(function() {
                $('#edit_departments_location_ajax').submit(function(event) {
                    event.preventDefault();
                    var formData = $(this).serialize();
                    $.ajax({
                        type: 'POST',
                        url: '{{ route("admin.updatedeptlocationajax") }}',
                        data: formData
                        , _token: CSRF_TOKEN
                        ,success: function(response) {
                            if(response.status == 200)
                            {
                                $('#dangerlocationupdate').hide();
                                $('#locationalreadyupdatedexists').hide();
                                $('#locationnotupdated').hide();
                                $('#successlocationupdate').css('display', 'block');
                                setTimeout(function() {
                                    window.location.reload();
                                }, 1000);
                            } 
                            else if(response.status == 500)
                            {
                                $('#dangerlocationupdate').css('display', 'block');
                                setTimeout(function() {
                                    window.location.reload();
                                }, 1000);
                            }
                        },
                        error: function(err) {
                             if (err.status === 422) {
                                var error_text='';                               
                                $.each(err.responseJSON.errors, function(i) {
                                $.each(err.responseJSON.errors[i], function(key, val) {
                                    error_text += '<li style="font-size:12px;">' + val + '</li>';
                              });
                            });                               
                                $('#locationnotupdated').html(error_text);
                                $('#dangerlocationupdate').hide();
                                $('#locationnotupdated').css('display', 'block');
                            }            
                        }
                    });
                });
           });
    /**-------------update location ends---------------- */

    /**-----------------enable disbale departments----------------- */
    $('.js_is_active_dept').on('change', function (e) {
        e.preventDefault();
        let enable_disable_dept_msg_element = $('#enable_disable_dept_msg');
        const url = $(this).attr('data-route')
        const closeBtn = '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'
        $.ajax({
            data: {
                _token: CSRF_TOKEN
            }
            , url: url
            , type: "POST"
            , success: function(response) {
                console.log(response.success);
                if (response.success) {
                    enable_disable_dept_msg_element
                        .removeClass('alert-danger')
                        .addClass('alert-success')
                        .fadeIn()
                        .html(closeBtn + response.message);
                } else {
                    enable_disable_dept_msg_element
                        .removeClass('alert-success')
                        .addClass('alert-danger')
                        .fadeIn()
                        .html(closeBtn + response.message);
                }
                    
                setTimeout(function() {
                    enable_disable_dept_msg_element.fadeOut();
                }, 3000);

                setTimeout(function() {
                    window.location.reload();
                }, 2000);
                
            }, error: function(err) {
                let error_text = '';
                $.each(err.responseJSON.errors, function(i) {
                    $.each(err.responseJSON.errors[i], function(key, val) {
                        error_text += '<li style="font-size:12px;">' + val + '</li>';
                    });
                });
                enable_disable_dept_msg_element
                    .removeClass('alert-success')
                    .addClass('alert-danger')
                    .fadeIn()
                    .html(closeBtn + error_text);

                setTimeout(function() {
                    enable_disable_dept_msg_element.fadeOut();
                }, 3000);

                setTimeout(function() {
                    window.location.reload();
                }, 2000);
            }
        })
    })
    /**--------------------ends enable disable departments--------- */

       });
    }

 /**-------------add department starts---------------- */
     function selectdeptcode(value)
     {
        $.ajax({
                    data: {
                            "department_name_value": value,
                            _token: CSRF_TOKEN
                        }
                    , url: "{{ route('admin.displaydepartmentcodeajax')}}"
                    , type: "POST"
                    , success:function(response) {
                        console.log(response.department_code)
                        if(response.status == 200)
                         {
                            var departmentcode = response.department_code;
                            $('#depcode').val(departmentcode);
                         }
                    }
        });

        if(value=='other')
        {
            $('#department_oth').show();
            document.getElementById('department_other').required= true;
        }
        else
        {
            $('#department_oth').hide();
            document.getElementById('department_other').required= false;
        }
     }

     function displayscopeoth(value)
     {
        if(value=='other')
        {
            $('#scope_oth').show();
            document.getElementById('scope_other').required= true;
        }
        else
        {
            $('#scope_oth').hide();
            document.getElementById('scope_other').required= false;
        }
     }

    /**-------------add department ends---------------- */

/**-------------divisions,subdivisions and locations starts here ------------------ */
       
        function getdepartmentdetails(details)
        {
            var details_array = details.split(',');
            document.getElementById('showdeptname').innerHTML=details_array[1];
            var depts_id = details_array[0];
            document.getElementById('dept_locat_id').value=details_array[0];
            var userhtmldatas='';
 /**-------------divisions and subdivision list starts here ------------------ */
            $.ajax({
                        data: {
                                "depts_id_value": depts_id
                                , _token: CSRF_TOKEN
                            }          
                        , url: "{{ route('admin.selectdivisiondetails')}}"
                        , type: "POST"
                        , success:function(response) {
                           if(response.status == 200)
                            {
                                    var divisiondatalist = response.divisions;
                                    if(divisiondatalist.length>0)
                                     {                                                            
                                        for(let i=0;i<divisiondatalist.length;i++)
                                          { 
                                            const div_name = divisiondatalist[i]['name'].toLowerCase();
                                            const div_name_modified = div_name.charAt(0).toUpperCase() + div_name.slice(1);
                                            
                                            userhtmldatas+='<tr style="background-color:#f1f1f1">\
                                        <td width="80%"><span class="font-w600 font-size-sm" style="text-transform:capitalize;">'+div_name_modified+'</span><br><span class="text-info" style="font-size:12px;">'+divisiondatalist[i].owner.name+'</span></td>\
                                        <td width="20%" align="left">\
                                        <div class="btn-group m-auto d-flex justify-content-center">\
                                        <a href="#" class="btn btn-sm btn-alt-secondary" data-toggle="dropdown" data-placement="top" title="Actions" id="drop'+divisiondatalist[i]['id']+'">\
                                        <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer"></i></a>\
                                        <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownMenu'+divisiondatalist[i]['id']+'">\
                                        <a href="#" class="dropdown-item" style="font-size:10px;"  data-toggle="modal"  data-target="#editdivisionModal" onclick="geteditdivisiondetails(\''+divisiondatalist[i]['id']+'\')">\
                                        <i class="fa fa-fw fa-pencil-alt text-primary" style="font-size:12px;"></i>&nbsp;&nbsp;Edit</a>\
                                        <a href="/admin/department/deletedeptdivisions/'+divisiondatalist[i]['id']+'" class="dropdown-item" style="font-size:10px;" onclick="return confirmdivisiondelete()">\
                                        <i class="fa fa-fw fa-times text-danger" style="font-size:12px;"></i>&nbsp;&nbsp;Remove</a>\
                                        <a href="#" class="dropdown-item" style="font-size:10px;" data-toggle="modal"  data-target="#addsubdivisionModal" onclick="passdivisiondetails(\''+divisiondatalist[i]['id']+','+divisiondatalist[i]['name']+'\')">\
                                        <i class="fa fa-fw fa-plus text-primary" style="font-size:12px;"></i>&nbsp;&nbsp;Add Subdivision</a>\
                                        </div>\
                                        </div></td>\
                                        </tr>\
                                      <tr>\
                                        <td colspan="2">\
                                              <table class="table table-borderless" width="100%">';

                                              myresponse = $.ajax({
                                                    data: {
                                                        "division_id_value" : divisiondatalist[i]['id'],
                                                        _token: CSRF_TOKEN
                                                    },
                                                    url: "{{ route('admin.selectsubdivisiondetails')}}",
                                                    type: "POST",
                                                    global: false,
                                                    async: false,
                                                }).responseJSON;
                                              if (myresponse.status == 200) {
                                                var subdivisionslist = myresponse.subdivisions;
                                                if(subdivisionslist.length > 0){
                                                for (let index = 0; index < myresponse.subdivisions.length; index++) {

                                                    const sub_div_name = myresponse.subdivisions[index]['name'].toLowerCase();
                                                    const sub_div_name_modified = sub_div_name.charAt(0).toUpperCase() + sub_div_name.slice(1);

                                                    userhtmldatas+='<tr><td width="5%"><i class="si si-wallet text-secondary" style="font-size:10px;"></i></td>\
                                                   <td width="70%"><span class="text-left text-dark" style="vertical-align:bottom;font-size:12px;">'+sub_div_name_modified+'</span><br><span class="text-info" style="font-size:12px;">'+myresponse.subdivisions[index].owner.name+'</span></td>\
                                                   <td width="5%"><a href="#" data-toggle="modal"  data-target="#editsubdivisionModal" onclick="geteditsubdivisiondetails(\''+myresponse.subdivisions[index]['id']+'\')"><i class="fa fa-fw fa-pencil-alt text-primary" style="font-size:12px;"></i></a>\
                                                   </td>\
                                                   <td width="5%">\
                                                   <a href="/admin/department/deletedeptsubdivisions/'+myresponse.subdivisions[index]['id']+'" onclick="return confirmsubdivisiondelete()"><i class="fa fa-fw fa-times text-danger" style="font-size:12px;"></i></a>\
                                                   </td>';
                                        
                                                    
                                                }
                                            }else{
                                                userhtmldatas+='<tr><td colspan="4" class="text-danger" style="font-size:14px;">No subdivisions added for this department</td></tr>';
                                            }
                                              }
           
                                            userhtmldatas+='</table> </td></tr>';                        
                                          }
                                        }
                                    
                                 else {
                                            userhtmldatas='<tr><td colspan="2" class="text-danger" style="font-size:14px;">No divisions added for this department</td></tr>';
                                            
                                        }

                                        $('#getdivisionnms').html(userhtmldatas);
                           
                         }
                        }   
                       
                    }); 
            
        /**---------division and subdivision list ends----------- */


        /**---------location list starts----------- */
                    
                    $.ajax({
                        data: {
                               "depts_id_value": depts_id
                                , _token: CSRF_TOKEN
                            }          
                        , url: "{{ route('admin.selectlocationdetails')}}"
                        , type: "POST"
                        , success:function(response) {
                           if(response.status == 200)
                                 {
                                    var locationdatalist = response.locations;              
                                    var lochtmldatas='';
                                   
                                    if(locationdatalist.length>0)
                                     {                                                            
                                        for(let i=0;i<locationdatalist.length;i++)
                                          { 
                                            const locat_name = locationdatalist[i]['name'].toLowerCase();
                                            const locat_name_modified = locat_name.charAt(0).toUpperCase() + locat_name.slice(1);

                                            lochtmldatas+='<tr>\
                                                   <td width="70%"><i class="fa fa-2x fa-list-ul text-secondary text-right" style="font-size:10px;"></i>&nbsp;&nbsp;&nbsp;<span class="text-left text-dark" style="vertical-align:bottom;font-size:12px;">'+locat_name_modified+'</span></td>\
                                                   <td width="5%"><a href="#" data-toggle="modal"  data-target="#editlocationModal" onclick="geteditlocationdetails(\''+locationdatalist[i]['id']+'\')"><i class="fa fa-fw fa-pencil-alt text-primary" style="font-size:12px;"></i></a>\
                                                   </td>\
                                                   <td width="5%">\
                                                   <a href="/admin/department/deletedeptlocations/'+locationdatalist[i]['id']+'" onclick="return confirmlocationdelete()"><i class="fa fa-fw fa-times text-danger" style="font-size:12px;"></i></a>\
                                                   </td>';
                                                  
                                          }
                                      } 
                                    else
                                    {
                                        lochtmldatas+='<tr><td colspan="4" class="text-danger" style="font-size:14px;">No locations added for this department</td></tr>';
                                    }

                                     $('#getlocationnms').html(lochtmldatas); 
                                    
                                 }
                           
                         }

                    });
         /**---------location list ends----------- */

        return false;

        }

     /**-------------edit divisions data display starts------------------ */ 
      function geteditdivisiondetails(divisionid)
       {
            $.ajax({
                        data: {
                                "divisionid_value": divisionid
                                , _token: CSRF_TOKEN
                            }          
                        , url: "{{ route('admin.geteditdivisiondetails')}}"
                        , type: "POST"
                        , success:function(response) {
                           if(response.status == 200)
                            {
                                editdivisiondata = response.dividata;
                                editdeptname =  response.deptname;
                                ownerid = editdivisiondata['owner_id'];
                                document.getElementById('department_value').value=editdeptname;
                                document.getElementById('division_name_value').value=editdivisiondata['name'];
                                document.getElementById('division_main_id').value=divisionid;

                                var selectElement = $('#division_owner');
                                selectElement.empty(); // Clear existing options

                                // Iterate through the data and add options to the <select> element
                                $.each(response.users, function (index, option) {
                                    var optionElement = $('<option>', {
                                        value: option.id,
                                        text: option.name
                                    });

                                    selectElement.append(optionElement);
                                });
                           
                                var preSelectedID = ownerid; 
                                var option = selectElement.find('option[value="' + preSelectedID + '"]');
                                if (option.length > 0) {
                                    option.attr('selected', true);
                                }


                            }
                        }
                    });
         }       
       /**-------------edit divisions data display ends here ------------------ */

       /**-------------edit subdivisions data display starts------------------ */ 
      function geteditsubdivisiondetails(subdivisionid)
       {
            $.ajax({
                        data: {
                                "subdivisionid_value": subdivisionid
                                , _token: CSRF_TOKEN
                            }          
                        , url: "{{ route('admin.geteditsubdivisiondetails')}}"
                        , type: "POST"
                        , success:function(response) {
                           if(response.status == 200)
                            {
                                editsubdivisiondata = response.subdividata;
                                editdeptname =  response.deptname;
                                ownerid = editsubdivisiondata['owner_id'];
                                document.getElementById('depart_sub_div_value').value=editdeptname;
                                document.getElementById('divi_sub_name_value').value=response.division_name;
                                document.getElementById('sub_divi_name_value').value=editsubdivisiondata['name'];
                                document.getElementById('sub_division_main_id').value=subdivisionid;
                                document.getElementById('main_divi_id').value=response.division_id;
                                

                                var selectElement = $('#sub_division_owner');
                                selectElement.empty(); // Clear existing options

                                // Iterate through the data and add options to the <select> element
                                $.each(response.users, function (index, option) {
                                    var optionElement = $('<option>', {
                                        value: option.id,
                                        text: option.name
                                    });

                                    selectElement.append(optionElement);
                                });
                           
                                var preSelectedID = ownerid; 
                                var option = selectElement.find('option[value="' + preSelectedID + '"]');
                                if (option.length > 0) {
                                    option.attr('selected', true);
                                }
                            }
                        }
                    });
         }       
       /**-------------edit subdivisions data display ends here ------------------ */

      /**-------------edit location data display starts------------------ */ 
      function geteditlocationdetails(locationid)
       {
            $.ajax({
                        data: {
                                "locationid_value": locationid
                                , _token: CSRF_TOKEN
                            }          
                        , url: "{{ route('admin.geteditlocationdetails')}}"
                        , type: "POST"
                        , success:function(response) {
                           if(response.status == 200)
                            {
                                editlocationdata = response.locations;
                                editlocatdeptname =  response.deptname;                       
                                document.getElementById('depart_locat_value').value=editlocatdeptname;                            
                                document.getElementById('location_upt_value').value=editlocationdata['name'];                              
                                document.getElementById('location_upt_id').value=editlocationdata['id'];                               
                            }
                        }
                    });
         }       
       /**-------------edit location data display ends here ------------------ */


/**-------------delete confirm message starts here ------------------ */ 
   //-------department confirm message--------//   
        function confirmdeptdelete() {
            message1 = "Are you sure you want to delete this department details?";
            x = confirm(message1);
            if (x == true) {
                message2 = 'This details will be deleted permanently,Press OK to continue.';
                y = confirm(message2);
                if (y == true) {
                } else {
                    return false;
                }
            } else {
                return false;
            }
            }
    //-------division confirm message--------//   
       function confirmdivisiondelete() {
        message1 = "Are you sure you want to delete this record?";
          x = confirm(message1);
        if (x == true) {
            message2 = 'This data will be deleted permanently,Press OK to continue.';
            y = confirm(message2);
            if (y == true) {
            } else {
                return false;
            }
        } else {
            return false;
        }
        }
    //-------subdivision confirm message--------//    
        function confirmsubdivisiondelete() {
                message1 = "Are you sure you want to delete this record?";
                x = confirm(message1);
                if (x == true) {
                    message2 = 'This data will be deleted permanently,Press OK to continue.';
                    y = confirm(message2);
                    if (y == true) {
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
                }     
   //-------location confirm message--------// 
        function confirmlocationdelete() {
            message1 = "Are you sure you want to delete this record?";
            x = confirm(message1);
            if (x == true) {
                message2 = 'This data will be deleted permanently,Press OK to continue.';
                y = confirm(message2);
                if (y == true) {
                } else {
                    return false;
                }
            } else {
                return false;
            }
            }
     
/**---------delete  confirm message ends here ----------------------- */    

      /**-------add subdivisions data display starts here -------- */
        function passdivisiondetails(details)
        {
            var details_array = details.split(',');
            
            document.getElementById('divi_main_id').value=details_array[0];
            document.getElementById('divi_name_value').value=details_array[1];    
        }

     /**-------add subdivisions data display ends here ------- */
    
 /**-------------divisions,subdivisions and locations ends here ------------------ */

</script>
<script>
    function ddlselect() {
        var d = document.getElementById("hod");
        var displaytext = d.options[d.selectedIndex].text;
        // document.getElementById("try").value = displaytext;
        $('#hodname').val(displaytext);
    }

</script>
<script>
    function validation() {
        if (document.getElementById('hod').value == 0) {
            alert('Please select a HOD name');
            document.getElementById('hod').focus();
            return false;
        }
        // always return false to prevent form from submission.
    }

</script>
<script>
    let division_add_url = "{{ route('admin.departmentDivisions.add')}}";
    function saveDivision(id) {
        $.ajax({
            data: {
                "name": $('#division_name_' + id).val()
                , "owner_id": $('#owner_id_' + id).val()
                , "department_id": id
                , _token: CSRF_TOKEN
            }
            , url: division_add_url
            , type: "POST"
            , success: function(result) {
                $('#danger_division_create_' + id).addClass('d-none');
                $('#success_division_create_' + id).removeClass('d-none');
                setTimeout(function() {
                    window.location.reload();
                }, 1000);
            }
            , error: function(err) {
                $('#danger_division_create_' + id).removeClass('d-none');
                $('#success_division_create_' + id).addClass('d-none');
                let error_text = "";

                if (err.status === 500) {
                    error_text += `<li>Some server errors happened, we are trying to fix them soon.</li>`;
                } else if (err.status === 422) {
                    $.each(err.responseJSON.errors, function(i) {
                       $.each(err.responseJSON.errors[i], function(key, val) {
                          error_text += '<li>' + val + '</li>';
                       });
                    });
                }
                $("#danger_division_create_" + id).empty().append(error_text);

            }
        });
    }

</script>
@endsection
@section('js_after')
<script>
    function divisionBtn(id) {
        $('#division' + id).modal('show');
    }
    $('[data-toggle="tooltip"]').tooltip()
</script>
@endsection

