@extends('admin.datatable')
@section('content')

<main id="main-container">

    <div class="row">
        <div class="col">
            <div class="content" style="/*margin-left:50px;margin-top:-75px*/">
                <div class="block block-rounded">
                    <div class="block block-rounded">
                        <div class="block-header">
                            <h3 class="block-title">MS 06.01 Probabilities Table</h3>
                            <ol class="breadcrumb breadcrumb-alt" style="/*margin-left:53%;*/">
                                <li class="mr-3">
                                    <button type="button" name="update" class="btn btn-primary" style="font-size:10px;" data-toggle="modal" data-target="#AddCategoryModal">Add</button>
                                    <div id="AddCategoryModal" class="modal fadebd-example-modal-lg" aria-labelledby="AddCategoryModalLabel" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-xl" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="AddCategoryModalLabel">Add Category</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form id="add_category_form" method="POST" enctype="multipart/form-data">

                                                    <div class="modal-body m-body p-4">

                                                        <div class="alert alert-success d-none w-100" id="success_msg" style="font-size:12px;">
                                                            Data added successfully
                                                        </div>

                                                        <ul class="alert alert-danger d-none w-100" id="error_msg" style="font-size:12px;">
                                                            Internal server error, we are trying to fix them soon
                                                        </ul>

                                                        <ul class="alert alert-warning d-none w-100" id="validation_error_msg" style="font-size:12px;"></ul>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                                                        <button type="submit" class="btn btn-primary" style="font-size:12px">Submit</button>
                                                    </div>

                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                </li>
                            </ol>

                        </div>
                        <div class="block-content block-content-full" style="/*width:80%;margin-left:90px*/">
                            <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                            <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                                <thead>
                                    <tr>
                                        <th class="text-left" style="width:12%;font-size:10px;text-transform:capitalize">SL No.</th>
                                        <th class="text-left" style="width:10px;font-size:10px;text-transform:capitalize">Description</th>
                                        <th class="text-left" style="width:10px;font-size:10px;text-transform:capitalize">Weightage</th>
                                        <th class="text-left" style="width:30%;font-size:10px;text-transform:capitalize">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $number = 1; ?>
                                    @foreach($enviaspect_probabilities as $p)

                                    {{csrf_field()}}

                                    <?php $id = $p->id ?>
                                    <tr>
                                        <td style="font-size:10px;" class="text-left">
                                            <span style="width:5px">{{ $number }}</span>
                                        </td>
                                        <td style="font-size:10px;width:10px" class="text-left">
                                            <span>
                                                @switch($p->description)
                                                @case('very_rare')
                                                Very rare
                                                @break
                                                @case('rare')
                                                Rare
                                                @break
                                                @case('low')
                                                Low
                                                @break
                                                @case('moderate')
                                                Moderate
                                                @break
                                                @case('high')
                                                High
                                                @break
                                                @default
                                                Very rare
                                                @endswitch
                                            </span>
                                        </td>
                                        <td style="font-size:10px;width:10px" class="text-left">
                                            <span>{{$p->weightage}}</span>
                                        </td>
                                        <td style="font-size:10px;width:10px" class="text-left">
                                            <div class="btn-group">
                                                <a href="#" class="btn btn-sm btn-alt-primary" style="font-size:10px" data-toggle="tooltip" title="Edit"><i class="fa fa-fw fa-pencil-alt"></i></a>
                                                <a href="#" class="btn btn-sm btn-alt-primary" onclick="return confirm('Are you sure?')" style="font-size:10px" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>
                                            </div>
                                        </td>
                                        <?php $number++; ?>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>

                        </div>

                    </div>
                </div>
            </div>

        </div>
        <div class="col">
            <div class="content" style="/*margin-left:50px;*/">
                <div class="block block-rounded">
                    <div class="block block-rounded">
                        <div class="block-header">
                            <h3 class="block-title">MS 06.02 Consequences Table</h3>
                            <div id="AddCategoryModal" class="modal fadebd-example-modal-lg" aria-labelledby="AddCategoryModalLabel" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-xl" role="document">
                                    <div class="modal-content">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="block-content block-content-full" style="/*width:80%;margin-left:90px*/">
                            <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                                <thead>
                                    <tr>
                                        <th class="text-left" style="width:12%;font-size:10px;text-transform:capitalize">SL No.</th>
                                        <th class="text-left" style="width:10px;font-size:10px;text-transform:capitalize">Description</th>
                                        <th class="text-left" style="width:10px;font-size:10px;text-transform:capitalize">Weightage</th>
                                        <th class="text-left" style="width:30%;font-size:10px;text-transform:capitalize">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $number = 1; ?>
                                    @foreach($enviaspect_consequences as $c)

                                    {{csrf_field()}}

                                    <?php $id = $c->id ?>
                                    <tr>
                                        <td style="font-size:10px;" class="text-left">
                                            <span style="width:5px">{{ $number }}</span>
                                        </td>
                                        <td style="font-size:10px;width:10px" class="text-left">
                                            <span>
                                                @switch($c->description)
                                                @case('negligible_risk')
                                                Negligible risk
                                                @break
                                                @case('very_low_risk')
                                                Very low risk
                                                @break
                                                @case('low_risk')
                                                Low risk
                                                @break
                                                @case('moderate_risk')
                                                Moderate risk
                                                @break
                                                @case('high_risk')
                                                High risk
                                                @break
                                                @default
                                                Negligible risk
                                                @endswitch
                                            </span>
                                        </td>
                                        <td style="font-size:10px;width:10px" class="text-left">
                                            <span>{{$c->weightage}}</span>
                                        </td>
                                        <td style="font-size:10px;width:10px" class="text-left">
                                            <div class="btn-group">
                                                <a href="#" class="btn btn-sm btn-alt-primary" style="font-size:10px" data-toggle="tooltip" title="Edit"><i class="fa fa-fw fa-pencil-alt"></i></a>
                                                <a href="#" class="btn btn-sm btn-alt-primary" onclick="return confirm('Are you sure?')" style="font-size:10px" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>
                                            </div>
                                        </td>
                                        <?php $number++; ?>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>

                        </div>



                    </div>
                </div>
            </div>

        </div>
    </div>
    {{-- <div class="content" style="width:92%;margin-left:50px;margin-top:-75px">
        <div class="block block-rounded">
            <div class="block block-rounded">
                <div class="block-header">
                    <h3 class="block-title">MS 06.01 Probabilities Table</h3>
                    <ol class="breadcrumb breadcrumb-alt" style="margin-left:53%;">
                        <li class="mr-3">
                            <button type="button" name="update" class="btn btn-primary" style="font-size:10px;" data-toggle="modal" data-target="#AddCategoryModal">Add</button>
                            <div id="AddCategoryModal" class="modal fadebd-example-modal-lg" aria-labelledby="AddCategoryModalLabel" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-xl" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="AddCategoryModalLabel">Add Category</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form id="add_category_form" method="POST" enctype="multipart/form-data">

                                            <div class="modal-body m-body p-4">

                                                <div class="alert alert-success d-none w-100" id="success_msg" style="font-size:12px;">
                                                    Data added successfully
                                                </div>

                                                <ul class="alert alert-danger d-none w-100" id="error_msg" style="font-size:12px;">
                                                    Internal server error, we are trying to fix them soon
                                                </ul>

                                                <ul class="alert alert-warning d-none w-100" id="validation_error_msg" style="font-size:12px;"></ul>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                                                <button type="submit" class="btn btn-primary" style="font-size:12px">Submit</button>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>

                        </li>
                    </ol>

                </div>
                <div class="block-content block-content-full" style="width:80%;margin-left:90px">
                    <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                        <thead>
                            <tr>
                                <th class="text-left" style="width:12%;font-size:10px;text-transform:capitalize">SL No.</th>
                                <th class="text-left" style="width:10px;font-size:10px;text-transform:capitalize">Description</th>
                                <th class="text-left" style="width:10px;font-size:10px;text-transform:capitalize">Weightage</th>
                                <th class="text-left" style="width:30%;font-size:10px;text-transform:capitalize">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $number = 1; ?>
                            @foreach($enviaspect_probabilities as $p)

                            {{csrf_field()}}

                            <?php $id = $p->id ?>
                            <tr>
                                <td style="font-size:10px;" class="text-left">
                                    <span style="width:5px">{{ $number }}</span>
                                </td>
                                <td style="font-size:10px;width:10px" class="text-left">
                                    <span>
                                        @switch($p->description)
                                        @case('very_rare')
                                        Very rare
                                        @break
                                        @case('rare')
                                        Rare
                                        @break
                                        @case('low')
                                        Low
                                        @break
                                        @case('moderate')
                                        Moderate
                                        @break
                                        @case('high')
                                        High
                                        @break
                                        @default
                                        Very rare
                                        @endswitch
                                    </span>
                                </td>
                                <td style="font-size:10px;width:10px" class="text-left">
                                    <span>{{$p->weightage}}</span>
                                </td>
                                <td style="font-size:10px;width:10px" class="text-left">
                                    <div class="btn-group">
                                        <a href="#" class="btn btn-sm btn-alt-primary" style="font-size:10px" data-toggle="tooltip" title="Edit"><i class="fa fa-fw fa-pencil-alt"></i></a>
                                        <a href="#" class="btn btn-sm btn-alt-primary" onclick="return confirm('Are you sure?')" style="font-size:10px" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>
                                    </div>
                                </td>
                                <?php $number++; ?>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>

                </div>

            </div>
        </div>
    </div> --}}
    {{-- <div class="content mt-3" style="width:92%;margin-left:50px;">
        <div class="block block-rounded">
            <div class="block block-rounded">
                <div class="block-header">
                    <h3 class="block-title">MS 06.02 Consequences Table</h3>
                    <div id="AddCategoryModal" class="modal fadebd-example-modal-lg" aria-labelledby="AddCategoryModalLabel" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-xl" role="document">
                            <div class="modal-content">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="block-content block-content-full" style="width:80%;margin-left:90px">
                    <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                        <thead>
                            <tr>
                                <th class="text-left" style="width:12%;font-size:10px;text-transform:capitalize">SL No.</th>
                                <th class="text-left" style="width:10px;font-size:10px;text-transform:capitalize">Description</th>
                                <th class="text-left" style="width:10px;font-size:10px;text-transform:capitalize">Weightage</th>
                                <th class="text-left" style="width:30%;font-size:10px;text-transform:capitalize">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $number = 1; ?>
                            @foreach($enviaspect_consequences as $c)

                            {{csrf_field()}}

                            <?php $id = $c->id ?>
                            <tr>
                                <td style="font-size:10px;" class="text-left">
                                    <span style="width:5px">{{ $number }}</span>
                                </td>
                                <td style="font-size:10px;width:10px" class="text-left">
                                    <span>
                                        @switch($c->description)
                                        @case('negligible_risk')
                                        Negligible risk
                                        @break
                                        @case('very_low_risk')
                                        Very low risk
                                        @break
                                        @case('low_risk')
                                        Low risk
                                        @break
                                        @case('moderate_risk')
                                        Moderate risk
                                        @break
                                        @case('high_risk')
                                        High risk
                                        @break
                                        @default
                                        Negligible risk
                                        @endswitch
                                    </span>
                                </td>
                                <td style="font-size:10px;width:10px" class="text-left">
                                    <span>{{$c->weightage}}</span>
                                </td>
                                <td style="font-size:10px;width:10px" class="text-left">
                                    <div class="btn-group">
                                        <a href="#" class="btn btn-sm btn-alt-primary" style="font-size:10px" data-toggle="tooltip" title="Edit"><i class="fa fa-fw fa-pencil-alt"></i></a>
                                        <a href="#" class="btn btn-sm btn-alt-primary" onclick="return confirm('Are you sure?')" style="font-size:10px" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>
                                    </div>
                                </td>
                                <?php $number++; ?>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>

                </div>



            </div>
        </div>
    </div> --}}
    <div class="content mt-3" style="width:92%;margin-left:50px;">
        <div class="block block-rounded">
            <div class="block block-rounded">
                <div class="block-header">
                    <h3 class="block-title">MS 06.03 Regulatory Requirements Table</h3>
                    <div id="AddCategoryModal" class="modal fadebd-example-modal-lg" aria-labelledby="AddCategoryModalLabel" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-xl" role="document">
                            <div class="modal-content">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="block-content block-content-full" style="width:80%;margin-left:90px">
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                        <thead>
                            <tr>
                                <th class="text-left" style="width:12%;font-size:10px;text-transform:capitalize">SL No.</th>
                                <th class="text-left" style="width:10px;font-size:10px;text-transform:capitalize">Description</th>
                                <th class="text-left" style="width:10px;font-size:10px;text-transform:capitalize">Weightage</th>
                                <th class="text-left" style="width:30%;font-size:10px;text-transform:capitalize">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $number = 1; ?>
                            @foreach($enviaspect_regulatory_requirements as $r)

                            {{csrf_field()}}

                            <?php $id = $r->id ?>
                            <tr>
                                <td style="font-size:10px;" class="text-left">
                                    <span style="width:5px">{{ $number }}</span>
                                </td>
                                <td style="font-size:10px;width:10px" class="text-left">
                                    <span>
                                        @switch($r->description)
                                        @case('no_legal_requirement')
                                        No Legal Requirement
                                        @break
                                        @case('legal_requirement')
                                        Legal Requirement
                                        @break
                                        @default
                                        No Legal Requirement
                                        @endswitch
                                    </span>
                                </td>
                                <td style="font-size:10px;width:10px" class="text-left">
                                    <span>{{$r->weightage}}</span>
                                </td>
                                <td style="font-size:10px;width:10px" class="text-left">
                                    <div class="btn-group">
                                        <a href="#" class="btn btn-sm btn-alt-primary" style="font-size:10px" data-toggle="tooltip" title="Edit"><i class="fa fa-fw fa-pencil-alt"></i></a>
                                        <a href="#" class="btn btn-sm btn-alt-primary" onclick="return confirm('Are you sure?')" style="font-size:10px" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>
                                    </div>
                                </td>
                                <?php $number++; ?>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>

                </div>



            </div>
        </div>
    </div>
    <div class="content mt-3" style="width:92%;margin-left:50px;">
        <div class="block block-rounded">
            <div class="block block-rounded">
                <div class="block-header">
                    <h3 class="block-title">MS 06.04 Quantities Table</h3>
                    <div id="AddCategoryModal" class="modal fadebd-example-modal-lg" aria-labelledby="AddCategoryModalLabel" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-xl" role="document">
                            <div class="modal-content">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="block-content block-content-full" style="width:80%;margin-left:90px">
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                        <thead>
                            <tr>
                                <th class="text-left" style="width:12%;font-size:10px;text-transform:capitalize">SL No.</th>
                                <th class="text-left" style="width:10px;font-size:10px;text-transform:capitalize">Description</th>
                                <th class="text-left" style="width:10px;font-size:10px;text-transform:capitalize">Weightage</th>
                                <th class="text-left" style="width:30%;font-size:10px;text-transform:capitalize">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $number = 1; ?>
                            @foreach($enviaspect_quantities as $q)

                            {{csrf_field()}}

                            <?php $id = $q->id ?>
                            <tr>
                                <td style="font-size:10px;" class="text-left">
                                    <span style="width:5px">{{ $number }}</span>
                                </td>
                                <td style="font-size:10px;width:10px" class="text-left">
                                    <span>
                                        @switch($q->description)
                                        @case('low')
                                        Low
                                        @break
                                        @case('moderate')
                                        Moderate
                                        @break
                                        @case('high')
                                        High
                                        @break
                                        @case('very_high')
                                        Very high
                                        @break
                                        @case('excessive')
                                        Excessive
                                        @break
                                        @default
                                        Low
                                        @endswitch

                                    </span>
                                </td>
                                <td style="font-size:10px;width:10px" class="text-left">
                                    <span>{{$q->weightage}}</span>
                                </td>
                                <td style="font-size:10px;width:10px" class="text-left">
                                    <div class="btn-group">
                                        <a href="#" class="btn btn-sm btn-alt-primary" style="font-size:10px" data-toggle="tooltip" title="Edit"><i class="fa fa-fw fa-pencil-alt"></i></a>
                                        <a href="#" class="btn btn-sm btn-alt-primary" onclick="return confirm('Are you sure?')" style="font-size:10px" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>
                                    </div>
                                </td>
                                <?php $number++; ?>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>



            </div>
        </div>
    </div>
    <div class="content mt-3" style="width:92%;margin-left:50px;">
        <div class="block block-rounded">
            <div class="block block-rounded">
                <div class="block-header">
                    <h3 class="block-title">MS 06.05 Control Measures Table</h3>
                    <div id="AddCategoryModal" class="modal fadebd-example-modal-lg" aria-labelledby="AddCategoryModalLabel" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-xl" role="document">
                            <div class="modal-content">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="block-content block-content-full" style="width:80%;margin-left:90px">
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                        <thead>
                            <tr>
                                <th class="text-left" style="width:12%;font-size:10px;text-transform:capitalize">SL No.</th>
                                <th class="text-left" style="width:10px;font-size:10px;text-transform:capitalize">Description</th>
                                <th class="text-left" style="width:10px;font-size:10px;text-transform:capitalize">Weightage</th>
                                <th class="text-left" style="width:30%;font-size:10px;text-transform:capitalize">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $number = 1; ?>
                            @foreach($enviaspect_control_measures as $c)

                            {{csrf_field()}}

                            <?php $id = $c->id ?>
                            <tr>
                                <td style="font-size:10px;" class="text-left">
                                    <span style="width:5px">{{ $number }}</span>
                                </td>
                                <td style="font-size:10px;width:10px" class="text-left">
                                    <span>
                                        @switch($c->description)
                                        @case('effectively_controlled')
                                        Effectively Controlled
                                        @break
                                        @case('ineffective_control')
                                        Ineffective Control
                                        @break
                                        @case('no_control')
                                        No Control
                                        @break
                                        @default
                                        Effectively Controlled
                                        @endswitch

                                    </span>
                                </td>
                                <td style="font-size:10px;width:10px" class="text-left">
                                    <span>{{$c->weightage}}</span>
                                </td>
                                <td style="font-size:10px;width:10px" class="text-left">
                                    <div class="btn-group">
                                        <a href="#" class="btn btn-sm btn-alt-primary" style="font-size:10px" data-toggle="tooltip" title="Edit"><i class="fa fa-fw fa-pencil-alt"></i></a>
                                        <a href="#" class="btn btn-sm btn-alt-primary" onclick="return confirm('Are you sure?')" style="font-size:10px" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>
                                    </div>
                                </td>
                                <?php $number++; ?>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>

                </div>

            </div>
        </div>
    </div>

</main>


@endsection
