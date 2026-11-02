@extends('layouts.multiselect')
@section('content')
{{-- <!doctype html>
<html lang="en"> --}}


<body>
    <main id="main-container" style="padding-top:0px;">

        <div class="bg-body-light" style="">
            <div class="content content-full">
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">

                    <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-alt">
                            <li class="breadcrumb-item">Vendor Management</li>
                            <li class="breadcrumb-item" aria-current="page">
                                <a class="link-fx" href="">Edit Vendor</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">
                                <a class="link-fx" href="/user/vendormgmt">Back</a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>


        <!-- Page Content -->
        <div class="content" style="width:110%">

            <!-- Dynamic Table Full -->
            <div class="block block-rounded">
                <div class="block-content block-content-full">
                    <div class="scrollable" style="height:450px;">
                        <form class="mb-5" action="{{url('/user/vendor/editven')}}" method="post">
                            {{ csrf_field() }}

                            @if(count($errors) >0 )
                            @foreach($errors->all() as $error)
                            <div class='alert alert-warning'>
                                {{ $error }}
                            </div>
                            @endforeach
                            @endif
                            @if(Session::has('msg'))
                            <div class="alert alert-primary">
                                <a class="close" data-dismiss="alert">×</a>
                                {!!Session::get('msg')!!}
                            </div>
                            @endif



                            @if(count($data)>0)
                            <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->

                            <table class="table table-bordered table-striped table-vcenter " id="dynamicTable">
                                <thead style="background-color:#FFFFFF">
                                    <tr>
                                        <th class="text-center" style="font-size:10px;width: 50px;text-transform:capitalize">Vendor Name</th>
                                        <th class="text-center" style="font-size:10px;width: 50px;text-transform:capitalize">Basis Of Addition</th>
                                        <th class="text-center" style="font-size:10px;width: 50px;text-transform:capitalize">Year Of Establishment</th>
                                        <th class="text-center" style="font-size:10px;width: 80px;text-transform:capitalize">Vendor Type</th>
                                        <th class="text-center" style="font-size:10px;width: 70px;text-transform:capitalize">Frequency Of Evaluation</th>
                                        <th class="text-center" style="font-size:10px;width: 50px;text-transform:capitalize">Product/Service</th>
                                        <th class="text-center" style="font-size:10px;width: 50px;text-transform:capitalize">Country</th>
                                        <th class="text-center" style="font-size:10px;width: 100px;text-transform:capitalize">Email</th>
                                        <th class="text-center" style="font-size:10px;width: 50px;text-transform:capitalize">Contact</th>
                                        <th class="text-center" style="font-size:10px;width: 50px;text-transform:capitalize">Iso Standard</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($data as $dat1)
                                    <tr>
                                        <td class="text-center" style="font-size: 10px;">
                                            <input type="text" style="font-size: 10px;" style="width: 100px;" class="form-control" name="vname[]" value="{{$dat1->vendor_name}}">
                                        </td>

                                        <td class="text-center" style="font-size: 10px;">
                                            <input type="text" style="font-size: 10px;" style="width: 100px;" class="form-control" name="group[]" readonly="" value="{{$dat1->vendor_group}}">
                                        </td>
                                        <td class="text-center" style="font-size: 10px;">
                                            <select class="form-control js-select2" style="font-size:10px;width:100%" name="year[]" data-placeholder="Choose year">
                                                <option selected value="0" style="font-size:13px;">Choose year</option>
                                                <?php $y = date("Y");
                                                for ($year= $y; $year >= config('constants.vendor.start_year'); $year--): ?>
                                                <option value="<?=$year;?>" <?=$year == $dat1->Yoe ? 'selected' : '' ?>><?=$year;?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </td>
                                        <td class="text-center" style="font-size: 10px;">
                                            <select class="form-control" style="font-size:10px;width:100%" name="type[]" data-placeholder="Choose type">
                                                <option value="non_critical" {{ $dat1->vendor_type == 'non_critical' ? 'selected' : '' }}>Non Critical</option>
                                                <option value="semi_critical" {{ $dat1->vendor_type == 'semi_critical' ? 'selected' : '' }}>Semi Critical</option>
                                                <option value="critical" {{ $dat1->vendor_type == 'critical' ? 'selected' : '' }}>Critical</option>
                                            </select>
                                        </td>

                                        <td class="text-center" style="font-size: 10px;">
                                            <select class="form-control" id="eval[]" style="font-size: 10px;" name="eval[]">
                                                <option value="quarterly" {{ $dat1->eval == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                                <option style="font-size: 13px;" value="half_year" {{ $dat1->eval == 'half_year' ? 'selected' : '' }}>Half year</option>
                                                <option style="font-size: 13px;" value="one_year" {{ $dat1->eval == 'one_year' ? 'selected' : '' }}>One year</option>
                                                <option style="font-size: 13px;" value="one_half_yeaR" {{ $dat1->eval == 'one_half_year' ? 'selected' : '' }}>1.5 year</option>
                                                <option style="font-size: 13px;" value="two_year" {{ $dat1->eval == 'two_year' ? 'selected' : '' }}>Two year</option>
                                                <option style="font-size: 13px;" value="three_year" {{ $dat1->eval == 'three_year' ? 'selected' : '' }}>Three year</option>
                                                <option style="font-size: 13px;" value="four_year" {{ $dat1->eval == 'four_year' ? 'selected' : '' }}>Four year</option>
                                                <option style="font-size: 13px;" value="five_year" {{ $dat1->eval == 'five_year' ? 'selected' : '' }}>Five year</option>
                                            </select>

                                        </td>
                                        <td class="text-center" style="font-size: 10px;">
                                            <textarea style="font-size: 10px;" type="text" style="width: 100px;" class="form-control" id="scope[]" name="scope[]">{{$dat1->scope}}</textarea>
                                        </td>
                                        <td class="text-center" style="font-size: 10px;">
                                            {{-- <input type="text" style="font-size: 10px;" type="text" style="width: 100px;" class="form-control" id="add[]" name="add[]" value="{{$dat1->address}}"> --}}

                                            {{-- <select class="form-control js-select2" style="font-size: 13px;" id="add[]" name="add[]" style="width: 100%;" data-placeholder="Choose a country">
                                                @if(count($countries)> 0 )
                                                @foreach($countries as $value)
                                                <option value="{{$value->id}}" {{ $value->id == $dat1->country_id ? 'selected' : '' }}>{{ $value->country_name }}</option></br>
                                            @endforeach
                                            @endif
                                            </select> --}}
                                            <select class="form-control js-select2" style="font-size:10px;width:100%" id="add{{ $dat1->id }}" name="add[]" data-placeholder="Choose country" mulitselect>
                                                @if(count($countries)> 0)
                                                @foreach($countries as $value)
                                                <option value="{{$value->id}}" {{$value->id == $dat1->country_id ? 'selected' : '' }}>{{ $value->country_name }} </option>
                                                @endforeach
                                                @endif
                                            </select>

                                        </td>
                                        <td class="text-center" style="font-size: 10px;">
                                            <input style="font-size: 10px;" type="text" style="width: 400px;" class="form-control" id="email[]" name="email[]" value="{{$dat1->email}}">
                                        </td>
                                        <td class="text-center" style="font-size: 10px;">
                                            <input style="font-size: 10px;" type="text" style="width: 400px;" class="form-control" id="contact[]" name="contact[]" value="{{$dat1->contact}}">
                                        </td>
                                        <td class="text-center" style="font-size: 10px;">
                                            <select class="js-select2 form-control" style="width:100%" name="iso{{ $dat1->id }}[]" id="iso{{ $dat1->id }}" multiple>
                                                @foreach($standards as $s)
                                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                                                @endforeach
                                            </select>

                                        </td>
                                        <div class="form-group">
                                            <input style="font-size: 10px;" type="hidden" name="sno[]" value="{{ $dat1->id}}">
                                        </div>
                                    </tr>

                                    @endforeach
                                </tbody>

                            </table>
                            @endif
                            <div class="col-lg-10">
                                <br>
                            </div>

                            <div>

                                <button for="submittl" type="submit" onclick=" return confirm('Do you want to proceed?')" class="btn btn-primary" style="font-size:13px;margin-left:92%;">Submit</button>

                            </div>
                    </div>
                </div>
            </div>
        </div>

        </form>


    </main>
    <!-- Apps Modal -->
    <!-- Opens from the modal toggle button in the header -->
    <div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">Options</h3>
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
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/vendormgmt">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="far fa-list-alt fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Register
                                        </p>
                                    </div>
                                </a>
                                <!-- END Products -->
                            </div>
                            <div class="col-6">
                                <!-- Products -->
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/vendor/report">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Vendor report
                                        </p>
                                    </div>
                                </a>
                                <!-- END Products -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Apps Modal -->
</body>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>

{{-- </html> --}}
@endsection
@section('js')
<script type="text/javascript">
    $(document).ready(function() {
        $('#sel_type').select2({});
    });
    //sel_bias

    let data = @json($data);
    let standards = [];
    for (var i = 0; i < data.length; i++) {
        standards[i] = [];
        for (var j = 0; j < data[i].standards.length; j++) {
            standards[i][j] = data[i].standards[j].id;
        }
        $('#iso' + data[i].id).select2().val(standards[i]).trigger("change");
    }

</script>


@endsection
