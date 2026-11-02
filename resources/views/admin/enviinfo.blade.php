@extends('admin.datatable')
@section('css_before')
<link rel="stylesheet" href="{{asset('css/oneui.min-5.8.css')}}">
<style>
    .breadcrumb>li+li:before {
        display: none !important;
    }

    .breadcrumb {
        font-size: 15px;
        padding: 0.75rem 1.25rem !important;
        background: #F6F7F9 !important;
        border-bottom-right-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
    }

    .breadcrumb li:first-child {
        font-size: 1rem;
        text-transform: uppercase;
    }

    .breadcrumb li:last-child {
        font-size: 1rem;
        color: gray;
    }

</style>
@endsection
@section('content')

<div class="content">

    <div class="row">
        @if(Session::has('message'))
        <div class="alert alert-primary">
            <a class="close" data-dismiss="alert">×</a>
            {!!Session::get('message')!!}
        </div>
        @endif

        <div class="col-lg-12">
            <div class="block block-rounded row g-0" style="margin-bottom: 0 !important;">
                <nav aria-label="breadcrumb" style="width: 100%">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item font-w700"></li>
                        <li class="breadcrumb-item"></li>
                        <li class="breadcrumb-item ml-auto">
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

                                                <div class="form-group">
                                                    <label for="category" class="col-sm-12 col-form-label text-black" style="font-size:12px;"> Category <span style="color:red">*</span>
                                                    </label>
                                                    <div class="col-sm-12">
                                                        <select class="form-control" id="category" name="category" style="font-size:12px" required>
                                                            <option value="0" disabled selected>Please Select Category</option>
                                                            <option value="probabilities">Probabilities</option>
                                                            <option value="consequences">Consequences</option>
                                                            <option value="regulatory">Regulatory Requirements</option>
                                                            <option value="quantities">Quantities</option>
                                                            <option value="control_measures">Control Measures</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="description" class="col-sm-12 col-form-label text-black" style="font-size:12px;"> Description</label>
                                                    <div class="col-sm-12">
                                                        <input type="text" class="form-control" style="font-size:12px;" name="description" id="description" placeholder="Description">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="weightage" class="col-sm-12 col-form-label text-black" style="font-size:12px;"> Weightage</label>
                                                    <div class="col-sm-12">
                                                        <input type="text" class="form-control" style="font-size:12px;" name="weightage" id="weightage" placeholder="Weightage">
                                                    </div>
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

                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="block block-rounded row g-0">
                <ul class="nav nav-tabs nav-tabs-block flex-md-column col-md-2" id="myTab" role="tablist" style="padding-right:0 !important;">
                    <li class="nav-item d-md-flex flex-md-column">
                        <button class="nav-link text-md-start active" id="btabs-vertical-probabilities-tab" data-toggle="tab" data-target="#btabs-vertical-probabilities" role="tab" aria-controls="btabs-vertical-probabilities" aria-selected="true">
                            <i class="fab fa-fw fa-product-hunt opacity-50 me-1 d-none d-sm-inline-block"></i> Probabilities
                        </button>
                    </li>
                    <li class="nav-item d-md-flex flex-md-column">
                        <button class="nav-link text-md-start" id="btabs-vertical-consequences-tab" data-toggle="tab" data-target="#btabs-vertical-consequences" role="tab" aria-controls="btabs-vertical-consequences" aria-selected="false">
                            <i class="fab fa-fw fa-contao opacity-50 me-1 d-none d-sm-inline-block"></i> Consequences
                        </button>
                    </li>
                    <li class="nav-item d-md-flex flex-md-column">
                        <button class="nav-link text-md-start" id="btabs-vertical-regulatory-tab" data-toggle="tab" data-target="#btabs-vertical-regulatory" role="tab" aria-controls="btabs-vertical-regulatory" aria-selected="false">
                            <i class="fas fa-fw fa-registered opacity-50 me-1 d-none d-sm-inline-block"></i> Regulatory
                        </button>
                    </li>
                    <li class="nav-item d-md-flex flex-md-column">
                        <button class="nav-link text-md-start" id="btabs-vertical-quantities-tab" data-toggle="tab" data-target="#btabs-vertical-quantities" role="tab" aria-controls="btabs-vertical-quantities" aria-selected="false">
                            <i class="fab fa-fw fa-quora opacity-50 me-1 d-none d-sm-inline-block"></i> Quantities
                        </button>
                    </li>
                    <li class="nav-item d-md-flex flex-md-column">
                        <button class="nav-link text-md-start" id="btabs-vertical-measures-tab" data-toggle="tab" data-target="#btabs-vertical-measures" role="tab" aria-controls="btabs-vertical-measures" aria-selected="false">
                            <i class="fas fa-fw fa-copyright opacity-50 me-1 d-none d-sm-inline-block"></i> Control Measures
                        </button>
                    </li>
                </ul>
                <div class="tab-content col-md-10">
                    <div class="block-content tab-pane active" id="btabs-vertical-probabilities" role="tabpanel" aria-labelledby="btabs-vertical-probabilities-tab" tabindex="0">
                        <h4 class="fw-semibold">MS 06.01 Probabilities Table</h4>
                        <p class="fs-sm">
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
                                                {{ $p->description }}
                                                @endswitch
                                            </span>
                                        </td>
                                        <td style="font-size:10px;width:10px" class="text-left">
                                            <span>{{$p->weightage}}</span>
                                        </td>
                                        <td style="font-size:10px;width:10px" class="text-left">
                                            <div class="btn-group">
                                                <a href="#" class="btn btn-sm btn-alt-primary" style="font-size:10px" data-toggle="modal" data-target="#editProbabilityModal{{ $id }}"><i class="fa fa-fw fa-pencil-alt"></i></a>
                                                <div id="editProbabilityModal{{ $id }}" class="modal fadebd-example-modal-lg" aria-labelledby="editProbabilityModalLabel{{ $id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                    <div class="modal-dialog modal-xl" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editProbabilityModalLabel{{ $id }}">Edit Probability</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body m-body p-4">

                                                                <div class="alert alert-success d-none w-100" id="successprob{{ $id}}" style="font-size:12px;">
                                                                    Data edited successfully
                                                                </div>

                                                                <ul class="alert alert-danger d-none w-100" id="errorprob{{ $id }}" style="font-size:12px;">
                                                                    Internal server error, we are trying to fix them soon
                                                                </ul>

                                                                <ul class="alert alert-warning d-none w-100" id="validationprob{{ $id }}" style="font-size:12px;"></ul>

                                                                <div class="form-group">
                                                                    <label for="description" class="col-sm-12 col-form-label" style="font-size:12px;"> Description</label>
                                                                    <div class="col-sm-12">
                                                                        <input type="text" class="form-control" style="font-size:12px;" id="descriptionprob{{ $id }}" value="{{ showDescriptions($p->description) }}">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="weightage" class="col-sm-12 col-form-label" style="font-size:12px;"> Weightage</label>
                                                                    <div class="col-sm-12">
                                                                        <input type="text" class="form-control" style="font-size:12px;" id="weightageprob{{ $id }}" value="{{ $p->weightage }}">
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                                                                <button type="button" class="btn btn-primary" style="font-size:12px" onclick="editProb('{{ $id }}')">Submit</button>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                                <a href="{{ route('probabilites.remove', ['id' => $id]) }}" class="btn btn-sm btn-alt-primary" onclick="return confirm('Are you sure?')" style="font-size:10px" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>
                                            </div>
                                        </td>
                                        <?php $number++; ?>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>

                        </p>
                    </div>
                    <div class="block-content tab-pane" id="btabs-vertical-consequences" role="tabpanel" aria-labelledby="btabs-vertical-consequences-tab" tabindex="0">
                        <h4 class="fw-semibold">MS 06.02 Consequences Table</h4>
                        <p class="fs-sm">
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
                                                {{ $c->description }}
                                                @endswitch
                                            </span>
                                        </td>
                                        <td style="font-size:10px;width:10px" class="text-left">
                                            <span>{{$c->weightage}}</span>
                                        </td>
                                        <td style="font-size:10px;width:10px" class="text-left">
                                            <div class="btn-group">
                                                <a href="#" class="btn btn-sm btn-alt-primary" style="font-size:10px" data-toggle="modal" data-target="#editConsequenceModal{{ $id }}"><i class="fa fa-fw fa-pencil-alt"></i></a>
                                                <div id="editConsequenceModal{{ $id }}" class="modal fadebd-example-modal-lg" aria-labelledby="editConsequenceModalLabel{{ $id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                    <div class="modal-dialog modal-xl" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editConsequenceModalLabel{{ $id }}">Edit Consequence</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body m-body p-4">

                                                                <div class="alert alert-success d-none w-100" id="successcons{{ $id}}" style="font-size:12px;">
                                                                    Data edited successfully
                                                                </div>

                                                                <ul class="alert alert-danger d-none w-100" id="errorcons{{ $id }}" style="font-size:12px;">
                                                                    Internal server error, we are trying to fix them soon
                                                                </ul>

                                                                <ul class="alert alert-warning d-none w-100" id="validationcons{{ $id }}" style="font-size:12px;"></ul>

                                                                <div class="form-group">
                                                                    <label for="description" class="col-sm-12 col-form-label" style="font-size:12px;"> Description</label>
                                                                    <div class="col-sm-12">
                                                                        <input type="text" class="form-control" style="font-size:12px;" name="descriptionprob" id="descriptioncons{{ $id }}" value="{{ showDescriptions($c->description) }}">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="weightage" class="col-sm-12 col-form-label" style="font-size:12px;"> Weightage</label>
                                                                    <div class="col-sm-12">
                                                                        <input type="text" class="form-control" style="font-size:12px;" name="weightageprob" id="weightagecons{{ $id }}" value="{{ $c->weightage }}">
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                                                                <button type="button" class="btn btn-primary" style="font-size:12px" onclick="editCons('{{ $id }}')">Submit</button>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                                <a href="{{ route('consequences.remove', ['id' => $id]) }}" class="btn btn-sm btn-alt-primary" onclick="return confirm('Are you sure?')" style="font-size:10px" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>
                                            </div>
                                        </td>
                                        <?php $number++; ?>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>

                        </p>
                    </div>
                    <div class="block-content tab-pane" id="btabs-vertical-regulatory" role="tabpanel" aria-labelledby="btabs-vertical-regulatory-tab" tabindex="0">
                        <h4 class="fw-semibold">MS 06.03 Regulatory Requirements Table</h4>
                        <p class="fs-sm">
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
                                                {{ $r->description }}
                                                @endswitch
                                            </span>
                                        </td>
                                        <td style="font-size:10px;width:10px" class="text-left">
                                            <span>{{$r->weightage}}</span>
                                        </td>
                                        <td style="font-size:10px;width:10px" class="text-left">
                                            <div class="btn-group">
                                                <a href="#" class="btn btn-sm btn-alt-primary" style="font-size:10px" data-toggle="modal" data-target="#editRegulatoryModal{{ $id }}"><i class="fa fa-fw fa-pencil-alt"></i></a>
                                                <div id="editRegulatoryModal{{ $id }}" class="modal fadebd-example-modal-lg" aria-labelledby="editRegulatoryModalLabel{{ $id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                    <div class="modal-dialog modal-xl" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editRegulatoryModalLabel{{ $id }}">Edit Regulatory Requirement</h5>

                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body m-body p-4">

                                                                <div class="alert alert-success d-none w-100" id="successreg{{ $id}}" style="font-size:12px;">
                                                                    Data edited successfully
                                                                </div>

                                                                <ul class="alert alert-danger d-none w-100" id="errorreg{{ $id }}" style="font-size:12px;">
                                                                    Internal server error, we are trying to fix them soon
                                                                </ul>

                                                                <ul class="alert alert-warning d-none w-100" id="validationreg{{ $id }}" style="font-size:12px;"></ul>

                                                                <div class="form-group">
                                                                    <label for="description" class="col-sm-12 col-form-label" style="font-size:12px;"> Description</label>
                                                                    <div class="col-sm-12">
                                                                        <input type="text" class="form-control" style="font-size:12px;" name="descriptionreg" id="descriptionreg{{ $id }}" value="{{ showDescriptions($r->description) }}">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="weightage" class="col-sm-12 col-form-label" style="font-size:12px;"> Weightage</label>
                                                                    <div class="col-sm-12">
                                                                        <input type="text" class="form-control" style="font-size:12px;" name="weightagereg" id="weightagereg{{ $id }}" value="{{ $r->weightage }}">
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                                                                <button type="button" class="btn btn-primary" style="font-size:12px" onclick="editReg('{{ $id }}')">Submit</button>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                                <a href="{{ route('regulatory.remove', ['id' => $id]) }}" class="btn btn-sm btn-alt-primary" onclick="return confirm('Are you sure?')" style="font-size:10px" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>
                                            </div>
                                        </td>
                                        <?php $number++; ?>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>

                        </p>
                    </div>
                    <div class="block-content tab-pane" id="btabs-vertical-quantities" role="tabpanel" aria-labelledby="btabs-vertical-quantities-tab" tabindex="0">
                        <h4 class="fw-semibold">MS 06.04 Quantities Table</h4>
                        <p class="fs-sm">
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
                                                {{ $q->description }}
                                                @endswitch

                                            </span>
                                        </td>
                                        <td style="font-size:10px;width:10px" class="text-left">
                                            <span>{{$q->weightage}}</span>
                                        </td>
                                        <td style="font-size:10px;width:10px" class="text-left">
                                            <div class="btn-group">
                                                <a href="#" class="btn btn-sm btn-alt-primary" style="font-size:10px" data-toggle="modal" data-target="#editQuantityModal{{ $id }}"><i class="fa fa-fw fa-pencil-alt"></i></a>
                                                <div id="editQuantityModal{{ $id }}" class="modal fadebd-example-modal-lg" aria-labelledby="editQuantityModalLabel{{ $id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                    <div class="modal-dialog modal-xl" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editQuantityModalLabel{{ $id }}">Edit Quantity</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body m-body p-4">

                                                                <div class="alert alert-success d-none w-100" id="successquan{{ $id}}" style="font-size:12px;">
                                                                    Data edited successfully
                                                                </div>

                                                                <ul class="alert alert-danger d-none w-100" id="errorquan{{ $id }}" style="font-size:12px;">
                                                                    Internal server error, we are trying to fix them soon
                                                                </ul>

                                                                <ul class="alert alert-warning d-none w-100" id="validationquan{{ $id }}" style="font-size:12px;"></ul>

                                                                <div class="form-group">
                                                                    <label for="description" class="col-sm-12 col-form-label" style="font-size:12px;"> Description</label>
                                                                    <div class="col-sm-12">
                                                                        <input type="text" class="form-control" style="font-size:12px;" name="descriptionquan" id="descriptionquan{{ $id }}" value="{{ showDescriptions($q->description) }}">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="weightage" class="col-sm-12 col-form-label" style="font-size:12px;"> Weightage</label>
                                                                    <div class="col-sm-12">
                                                                        <input type="text" class="form-control" style="font-size:12px;" name="weightagereg" id="weightagequan{{ $id }}" value="{{ $q->weightage }}">
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                                                                <button type="button" class="btn btn-primary" style="font-size:12px" onclick="editQuan('{{ $id }}')">Submit</button>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                                <a href="{{ route('quantity.remove', ['id' => $id]) }}" class="btn btn-sm btn-alt-primary" onclick="return confirm('Are you sure?')" style="font-size:10px" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>
                                            </div>
                                        </td>
                                        <?php $number++; ?>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>

                        </p>
                    </div>
                    <div class="block-content tab-pane" id="btabs-vertical-measures" role="tabpanel" aria-labelledby="btabs-vertical-measures-tab" tabindex="0">
                        <h4 class="fw-semibold">MS 06.05 Control Measures Table</h4>
                        <p class="fs-sm">
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
                                                {{ $c->description }}
                                                @endswitch

                                            </span>
                                        </td>
                                        <td style="font-size:10px;width:10px" class="text-left">
                                            <span>{{$c->weightage}}</span>
                                        </td>
                                        <td style="font-size:10px;width:10px" class="text-left">
                                            <div class="btn-group">
                                                <a href="#" class="btn btn-sm btn-alt-primary" style="font-size:10px" data-toggle="modal" data-target="#editControlModal{{ $id }}"><i class="fa fa-fw fa-pencil-alt"></i></a>
                                                <div id="editControlModal{{ $id }}" class="modal fadebd-example-modal-lg" aria-labelledby="editControlModalLabel{{ $id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                    <div class="modal-dialog modal-xl" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editControlModalLabel{{ $id }}">Edit Control Measure</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body m-body p-4">

                                                                <div class="alert alert-success d-none w-100" id="successcontrol{{ $id}}" style="font-size:12px;">
                                                                    Data edited successfully
                                                                </div>

                                                                <ul class="alert alert-danger d-none w-100" id="errorcontrol{{ $id }}" style="font-size:12px;">
                                                                    Internal server error, we are trying to fix them soon
                                                                </ul>

                                                                <ul class="alert alert-warning d-none w-100" id="validationcontrol{{ $id }}" style="font-size:12px;"></ul>

                                                                <div class="form-group">
                                                                    <label for="description" class="col-sm-12 col-form-label" style="font-size:12px;"> Description</label>
                                                                    <div class="col-sm-12">
                                                                        <input type="text" class="form-control" style="font-size:12px;" name="descriptioncontrol" id="descriptioncontrol{{ $id }}" value="{{ showDescriptions($c->description) }}">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="weightage" class="col-sm-12 col-form-label" style="font-size:12px;"> Weightage</label>
                                                                    <div class="col-sm-12">
                                                                        <input type="text" class="form-control" style="font-size:12px;" name="weightagecontrol" id="weightagecontrol{{ $id }}" value="{{ $c->weightage }}">
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                                                                <button type="button" class="btn btn-primary" style="font-size:12px" onclick="editControl('{{ $id }}')">Submit</button>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                                <a href="{{ route('control_measures.remove', ['id' => $id]) }}" class="btn btn-sm btn-alt-primary" onclick="return confirm('Are you sure?')" style="font-size:10px" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>
                                            </div>
                                        </td>
                                        <?php $number++; ?>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>

                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('js_after')
<script src="{{ asset('js/oneui.min-5.8.js') }}"></script>
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    let add_category_url = "{{ route('admin.enviaspect.add_category') }}";
    let edit_env_url = "{{ route('probabilites.edit') }}";

    $("ul.nav-tabs > li > button").on("click", function(e) {
        var id = $(e.target).attr("data-target").substr(1);
        window.location.hash = id;
    });

    // on load of the page: switch to the currently selected tab
    var hash = window.location.hash;
    $('#myTab button[data-target="' + hash + '"]').tab('show');
    //$('#myTab button[data-target="' + "#{{ old('tab') }}" + '"]').tab('show');
    function editItem(id, the_category, postfix) {
        let success_selector = $('#success' + postfix + id);
        let error_selector = $('#error' + postfix + id);
        let validation_error_selector = $('#validation' + postfix + id);

        $.ajax({
            data: {
                id: id
                , category: the_category
                , description: $('#description' + postfix + id).val()
                , weightage: $('#weightage' + postfix + id).val()
                , _token: CSRF_TOKEN
            , }
            , url: edit_env_url
            , type: "POST"
            , success: function() {
                success_selector.removeClass('d-none');
                success_selector.addClass('d-block')

                error_selector.removeClass('d-block');
                error_selector.addClass('d-none')

                validation_error_selector.removeClass('d-block');
                validation_error_selector.addClass('d-none');

                setTimeout(function() {
                    window.location.reload();
                    $('#myTab button[data-target="' + hash + '"]').tab('show');
                }, 2000);

                //$('#myTab button[data-target="' + hash + '"]').tab('show');

            }
            , error: function(err) {
                if (err.status === 500) {
                    error_selector.removeClass('d-none');
                    error_selector.addClass('d-block');

                    validation_error_selector.removeClass('d-block')
                    validation_error_selector.addClass('d-none');

                    success_selector.removeClass('d-block');
                    success_selector.addClass('d-none');
                } else if (err.status === 422) {
                    success_selector.removeClass('d-block');
                    success_selector.addClass('d-none');

                    error_selector.removeClass('d-block');
                    error_selector.addClass('d-none');

                    validation_error_selector.addClass('d-block')
                    validation_error_selector.removeClass('d-none');

                    var error_text = '';
                    $.each(err.responseJSON.errors, function(i) {
                        $.each(err.responseJSON.errors[i], function(key, val) {
                            error_text += '<li style="font-size:12px;">' + val + '</li>';
                        });
                    });
                    validation_error_selector.html(error_text);
                }

            }
        , });

    }

    function editProb(id) {
        editItem(id, 'probabilities', 'prob');
    }

    function editCons(id) {
        editItem(id, 'consequences', 'cons');
    }

    function editReg(id) {
        editItem(id, 'regulatory', 'reg');
    }

    function editQuan(id) {
        editItem(id, 'quantities', 'quan');
    }

    function editControl(id) {
        editItem(id, 'control_measures', 'control');
    }



    $('#add_category_form').on('submit', function(e) {
        e.preventDefault();
        let success_selector = $('#success_msg');
        let error_selector = $('#error_msg');
        let validation_error_selector = $('#validation_error_msg');

        $.ajax({
            data: {
                category: $('#category').val()
                , description: $('#description').val()
                , weightage: $('#weightage').val()
                , _token: CSRF_TOKEN
            , }
            , url: add_category_url
            , type: "POST"
            , success: function() {
                success_selector.removeClass('d-none');
                success_selector.addClass('d-block')

                error_selector.removeClass('d-block');
                error_selector.addClass('d-none')

                validation_error_selector.removeClass('d-block');
                validation_error_selector.addClass('d-none');

                setTimeout(function() {
                    window.location.reload();
                }, 2000);

            }
            , error: function(err) {
                if (err.status === 500) {
                    error_selector.removeClass('d-none');
                    error_selector.addClass('d-block');

                    validation_error_selector.removeClass('d-block')
                    validation_error_selector.addClass('d-none');

                    success_selector.removeClass('d-block');
                    success_selector.addClass('d-none');
                } else if (err.status === 422) {
                    success_selector.removeClass('d-block');
                    success_selector.addClass('d-none');

                    error_selector.removeClass('d-block');
                    error_selector.addClass('d-none');

                    validation_error_selector.addClass('d-block')
                    validation_error_selector.removeClass('d-none');

                    var error_text = '';
                    $.each(err.responseJSON.errors, function(i) {
                        $.each(err.responseJSON.errors[i], function(key, val) {
                            error_text += '<li style="font-size:12px;">' + val + '</li>';
                        });
                    });
                    validation_error_selector.html(error_text);
                }

            }
        , });

    });

</script>
@endsection
