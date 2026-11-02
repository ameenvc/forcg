@extends('admin.datatable')
@section('content')
    <!-- Page Content -->
    <div class="content">
        <!-- Dynamic Table Full -->
        @if(Session::has('message'))
        <div class="alert alert-primary">
            <a class="close" data-dismiss="alert">×</a>
            {!!Session::get('message')!!}
        </div>
        @endif
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">MS.07.01 Purpose Table</h3>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page">
                            Context Management
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="block-content block-content-full" style="width:84%;">
                <div class="table-responsive">
                <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                    <thead>
                        <tr>
                            <th class="text-left" style="width: 1%;font-size:10px;text-transform:capitalize">SL No.</th>
                            <th class="d-sm-table-cell text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Name</th>
                            <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $number = 1; ?>
                        @foreach($purposes as $c)
                        {{csrf_field()}}
                        <?php $id = $c->id ; $name=$c->name ?>
                        <tr>

                            <td style="font-size:10px" class="text-left">
                                <span>{{ $number }}</span>
                            </td>


                            <?php $number++; ?>
                            <td style="font-size:10px" class="text-left">
                                <span>{{$c->name}}</span>
                            </td>

                            <td class="text-left">
                                <div class="btn-group">
                                    <a href="{{url('/admin/editPurpose?val='.$id)}}" class="btn btn-sm btn-alt-primary" style="font-size: 10px;" data-toggle="tooltip" title="Edit"><i class="fa fa-fw fa-pencil-alt"></i>
                                    </a>
                                    <a href="{{url('/admin/removePurpose?val='.$id)}}" class="btn btn-sm btn-alt-primary" onclick="return confirm('Are you sure?')" style="font-size: 10px;" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>
                                </div>

                            </td>
                        </tr>
                        @endforeach

                    </tbody>

                </table>
                </div>
                <br><br>
                <div class="row">
                    @if(Session::has('msg'))
                    <div class="alert alert-primary">
                        <a class="close" data-dismiss="alert">×</a>
                        {!!Session::get('msg')!!}
                    </div>
                    @endif
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6 text-right">
                        <form method="post" action="{{url('/admin/addPurpose')}}" onsubmit="return validation()">
                            {{csrf_field()}}
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" style="font-size: 13px" placeholder="Enter Purpose" name="name" id="name" required>
                                    <div class="input-group-append">
                                        <input type="submit" name="update" class="btn btn-primary" style="font-size:12px" value="Add">
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
           
        </div>
        <!-- for types  -->
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">MS.07.02 Change category Table</h3>

                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page">
                            Context Management
                        </li>
                    </ol>
                </nav>

            </div>
            <div class="block-content block-content-full" style="width:84%;">
                <div class="table-responsive">
                <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                    <thead>
                        <tr>
                            <th class="text-left" style="width: 1%;font-size:10px;text-transform:capitalize">SL No.</th>
                            <th class="d-sm-table-cell text-center" style="width: 5%;font-size:10px;text-transform:capitalize">Category</th>
                            <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $number = 1; ?>
                        @foreach($cats as $c)

                        {{csrf_field()}}

                        <?php $id = $c->id ;$name=$c->category?>
                        <tr>

                            <td style="font-size:10px" class="text-left">
                                <span>{{ $number }}</span>
                            </td>


                            <td style="font-size:10px" class="text-left">
                                <span>{{$c->category}}</span>
                            </td>

                            <td style="font-size:10px" class="text-left">
                                <?php $number++; ?>
                                <div class="btn-group">
                                    <a href="{{url('/admin/editcat?val='.$id.'&name='.$name)}}" class="btn btn-sm btn-alt-primary" style="font-size: 10px;" data-toggle="tooltip" title="Edit"><i class="fa fa-fw fa-pencil-alt"></i></a>
                                    <a href="{{url('/admin/removecat?val='.$id)}}" class="btn btn-sm btn-alt-primary" onclick="return confirm('Are you sure?')" style="font-size: 10px;" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
                </div>
                <br><br>
                <div class="row">
                    @if(Session::has('msg'))
                    <div class="alert alert-primary">
                        <a class="close" data-dismiss="alert">×</a>
                        {!!Session::get('msg')!!}
                    </div>
                    @endif
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6 text-right">
                        <form method="post" action="{{url('/admin/addcat')}}" onsubmit="return validation()">
                            {{csrf_field()}}
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" style="font-size: 13px" placeholder="Enter change category" id="example-text-input" required name="name">
                                    <div class="input-group-append">
                                        <input type="submit" name="update" class="btn btn-primary" style="font-size:12px" value="Add">
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
            
        </div>
        <!-- for types  -->
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">MS.07.03 CR Approver Table</h3>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page">
                            Context Management
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="block-content block-content-full" style="width:84%;">
                <div class="table-responsive">
                <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                    <thead>
                        <tr>
                            <th class="text-left" style="width: 2%;font-size:10px;text-transform:capitalize">SL No.</th>
                            <th class="d-sm-table-cell text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Name</th>
                            <th class="d-sm-table-cell text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Email</th>
                            <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $number = 1; ?>
                        @foreach($changereqapp as $c)

                        {{csrf_field()}}

                        <?php 
                            $id = $c->id ; $name=$c->name;
                            if(!in_array($c->email,$usersEmails)){
                                continue;
                            }
                        ?>
                        <tr>

                            <td style="font-size:10px" class="text-left">
                                <span>{{ $number }}</span>
                            </td>


                            <td style="font-size:10px" class="text-left">
                                <span>{{$c->name}}</span>
                            </td>
                            <td style="font-size:10px" class="text-left">
                                <span>{{$c->email}}</span>
                            </td>
                            <td style="font-size:10px" class="text-left">
                                <?php $number++; ?>
                                <div class="btn-group">
                                    <a href="{{url('/admin/removeRequestApprover?val='.$id)}}" style="font-size: 10px;" class="btn btn-sm btn-alt-primary" onclick="return confirm('Are you sure?')" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
                </div>
                <br><br>
                <div class="row">
                    @if(Session::has('msg'))
                    <div class="alert alert-primary">
                        <a class="close" data-dismiss="alert">×</a>
                        {!!Session::get('msg')!!}
                    </div>
                    @endif
                    <div class="col-sm-4"></div>
                    <div class="col-sm-8 text-right">
                        <form method="post" action="{{url('/admin/addRequestApprover')}}" onsubmit="return validation()">
                            {{csrf_field()}}
                                <div class="input-group mb-3">
                                    <select class="form-control" name="user" required="" id="user" onchange="ddlselect()" style="font-size: 13px;" name="example-select">
                                        <option value="0">Please select</option>
                                        @if(count($users)>0)
                                        @foreach($users as $use)
                                        <option required value="{{$use->email}}" id="name" name="name">{{$use->name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <input type="text" readonly class="form-control" style="font-size: 13px;" placeholder="Email" required name="emailappr" id="emailappr">
                                    <input type="hidden" id="try" name="try">
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
                <h3 class="block-title">MS.07.04 CR Reviewer Table</h3>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page">
                            Context Management
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="block-content block-content-full" style="width:84%;">
                <div class="table-responsive">
                <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                    <thead>
                        <tr>
                            <th class="text-left" style="width: 2%;font-size:10px;text-transform:capitalize">SL No.</th>
                            <th class="d-sm-table-cell text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Name</th>
                            <th class="d-sm-table-cell text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Email</th>
                            <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $number = 1; ?>
                        @foreach($changereqreview as $c)
                            
                            {{csrf_field()}}
                            <?php 
                                $id = $c->id ; $name=$c->name;
                                if(!in_array($c->email,$usersEmails)){
                                    continue;
                                }
                            ?>
                            <tr>
                                <td style="font-size:10px" class="text-left">
                                    <span>{{ $number }}</span>
                                </td>
                                <td style="font-size:10px" class="text-left">
                                    <span>{{$c->name}}</span>
                                </td>
                                <td style="font-size:10px" class="text-left">
                                    <span>{{$c->email}}</span>
                                </td>
                                <td style="font-size:10px" class="text-left">
                                    <?php $number++; ?>
                                    <div class="btn-group">
                                        <a href="{{url('/admin/removeRequestReviewer?val='.$id)}}" style="font-size: 10px;" class="btn btn-sm btn-alt-primary" onclick="return confirm('Are you sure?')" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
                </div>
                <br><br>
                <div class="row">
                    @if(Session::has('msg'))
                    <div class="alert alert-primary">
                        <a class="close" data-dismiss="alert">×</a>
                        {!!Session::get('msg')!!}
                    </div>
                    @endif
                    <div class="col-sm-4"></div>
                    <div class="col-sm-8 text-right">
                        <form method="post" action="{{url('/admin/addRequestReviewer')}}" onsubmit="return validation()">
                            {{csrf_field()}}
                                <div class="input-group mb-3">
                                    <select class="form-control" name="user" required="" id="users" onchange="ddlselect1()" style="font-size: 13px;" name="example-select">
                                        <option value="0">Please select</option>
                                        @if(count($users)>0)
                                        @foreach($users as $use)
                                        <option required value="{{$use->email}}" id="name" name="name">{{$use->name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <input type="text" readonly style="font-size: 13px;" class="form-control" placeholder="Email" required name="emailreview" id="emailreview"><br><br>
                                    <input type="hidden" id="try1" name="try1">
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
                <h3 class="block-title">MS.07.05 Context Severities Table</h3>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page">
                            Context Management
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="block-content block-content-full" style="width:84%;">
                <div class="table-responsive">
                <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                    <thead>
                        <tr>
                            <th class="text-left" style="width: 1%;font-size:10px;text-transform:capitalize">SL No.</th>
                            <th class="d-sm-table-cell text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Name</th>
                            <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $number = 1; ?>
                        @foreach($context_crs_severities as $c)
                        {{csrf_field()}}
                        <tr>
                            <td style="font-size:10px" class="text-left">
                                <span>{{ $number }}</span>
                            </td>
                            <?php $number++; ?>
                            <td style="font-size:10px" class="text-left">
                                <span>{{$c->name}}</span>
                            </td>
                            <td class="text-left">
                                <div class="btn-group">
                                    <a href="{{ route('admin.editSeverity', ['id' => $c->id])}}" class="btn btn-sm btn-alt-primary" style="font-size: 10px;" data-toggle="tooltip" title="Edit"><i class="fa fa-fw fa-pencil-alt"></i></a>
                                    <a href="{{ route('admin.removeContextSeverity', ['id' => $c->id])}}" class="btn btn-sm btn-alt-primary" onclick="return confirm('Are you sure?')" style="font-size: 10px;" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
                </div>
                <br><br>
                <div class="row">
                    @if(Session::has('msg'))
                    <div class="alert alert-primary">
                        <a class="close" data-dismiss="alert">×</a>
                        {!!Session::get('msg')!!}
                    </div>
                    @endif
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6 text-right">
                        <form method="post" action="{{ route('admin.addSeverity')}}" onsubmit="return validation()">
                            {{csrf_field()}}
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" style="font-size: 13px" placeholder="Enter Severity" name="severity_name" id="severity_name" required>
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
<script>
    function ddlselect() {
        var d = document.getElementById("user");
        var displaytext = d.options[d.selectedIndex].value;
        var displaytext1 = d.options[d.selectedIndex].text;
        document.getElementById("emailappr").value = displaytext;
        document.getElementById("try").value = displaytext1;
    }

    function ddlselect1() {
        var d = document.getElementById("users");
        var displaytext = d.options[d.selectedIndex].value;
        var displaytext1 = d.options[d.selectedIndex].text;
        document.getElementById("emailreview").value = displaytext;
        document.getElementById("try1").value = displaytext1;
    }

</script>
@endsection
