@extends('pages.datatables')
@section('content')
<!doctype html>
<html lang="en">


<body>
    <main id="main-container" style="padding-top:0px;">

        <div class="bg-body-light" style="">
            <div class="content content-full">
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">

                    <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-alt">
                            <li class="breadcrumb-item">Objectives</li>
                            <li class="breadcrumb-item" aria-current="page">
                                <a class="link-fx" href="">Edit Objectives</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">
                                <a class="link-fx" href="/user/objectives/listofobjectives/{{Session::get('objdept')}}/{{$type}}">Back</a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>


        <!-- Page Content -->
        <div class="content" style="width:100%">

            <!-- Dynamic Table Full -->
            <div class="block block-rounded">
                <div class="block-content block-content-full">
                    <div class="scrollable" style="height:450px;">
                        <form class="mb-5" action="{{url('/user/objectives/editrequest_obj/'. $type)}}" method="post">
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

                            @foreach($data as $dat)
                            @endforeach
                            @endif


                            @if(count($data)>0)

                            <table class="table table-bordered table-striped table-vcenter " id="dynamicTable">
                                <thead style="background-color:#FFFFFF">
                                    <tr>
                                        <th class="text-center" style="font-size:13px;width: 150px;text-transform:capitalize">Type</th>
                                        <th class="text-center" style="font-size:13px;width: 600px;text-transform:capitalize">Name</th>
                                        <th class="text-center" style="font-size:13px;width: 150px;text-transform:capitalize">Department</th>
                                        <th class="text-center" style="font-size:13px;width: 250px;text-transform:capitalize">Target</th>
                                        <th class="text-center" style="font-size:13px;width: 400px;text-transform:capitalize">Unit</th>
                                        <th class="text-center" style="font-size:13px;width: 300px;text-transform:capitalize">Span</th>
                                        <th class="text-center" style="font-size:13px;width: 400px;text-transform:capitalize">Methodology</th>
                                        <th class="text-center" style="font-size:13px;width: 400px;text-transform:capitalize">Resources</th>
                                        <th class="text-center" style="font-size:13px;width: 120px;text-transform:capitalize">Frequency</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $dat1)
                                    <tr>
                                        <td class="text-center " style="font-size:13px;">
                                            <select class="form-control" id="objective_type" style="font-size: 13px;width: 150px;" name="type[]">
                                                <option value="0" selected>Choose Type</option><br>
                                                @if(count($objective_types)> 0 )
                                                @foreach($objective_types as $item)
                                                <option style="font-size: 13px;" value="{{$item->id}}" {{ $item->id == $dat1->objective_type_id ? 'selected' : ''}}>{{ $item->name }}</option><br>
                                                @endforeach
                                                @endif
                                            </select>
                                        </td>

                                        <td class="text-center" style="font-size: 13px;">
                                            <input type="text" style="font-size: 13px;" style="width: 600px;" class="form-control" id="name[]" name="name[]" value="{{$dat1->objectives}}">
                                        </td>
                                        <td class="text-center " style="font-size:13px;">
                                            <select class="form-control" id="select_type" style="font-size: 13px;width: 150px;" name="department[]">
                                                <option value="{{$dat1->dep->id}}">{{ $dat1->dep ? $dat1->dep->departmentname : '-' }}</option><br>
                                                @if(count($department)> 0 )
                                                @foreach($department as $d)
                                                @if($d->id != $dat1->dep->id)
                                                <option style="font-size: 13px;" value="{{$d->id}}">{{ $d->departmentname }}</option><br>
                                                @endif
                                                @endforeach
                                                @endif
                                            </select>
                                        </td>
                                        <td class="text-center" style="font-size: 13px;">
                                            <input style="font-size: 13px;" type="number" step="any" style="width: 120px;" class="form-control" id="target[]" name="target[]" value="{{$dat1->otarget}}">
                                        </td>
                                        <td class="text-center" style="font-size: 13px;">
                                            <input style="font-size: 13px;" type="text" style="width: 400px;" class="form-control" id="unit[]" name="unit[]" value="{{$dat1->unit}}">
                                        </td>
                                        <td class="text-center" style="font-size: 13px;width:300px">
                                            <select class="form-control" id="span" style="font-size: 13px;width:100px" name="span[]">
                                                <option value="{{$dat1->span}}"> {{ $dat1->span}}</option><br>

                                                <option style="font-size: 13px;" value=" {{'Short'}}"> Short</option><br>
                                                <option style="font-size: 13px;" value=" {{'Medium'}}">Medium</option><br>
                                                <option style="font-size: 13px;" value=" {{'Long'}}">Long</option><br>
                                            </select>
                                        </td>
                                        <td class="text-center" style="font-size: 13px;">
                                            <textarea style="font-size: 13px;" type="text" style="width: 100px;" class="form-control" id="methodology[]" name="methodology[]">{{$dat1->methodology}}</textarea>
                                        </td>
                                        <td class="text-center" style="font-size: 13px;">
                                            <textarea style="font-size: 13px;" type="text" style="width: 100px;" class="form-control" id="resources[]" name="resources[]">{{$dat1->resources}}</textarea>
                                        </td>
                                        <td class="text-center" style="font-size: 13px;">
                                            <select class="form-control" id="frequency" style="font-size: 13px;width:120px" name="frequency[]">
                                                <option value="{{$dat1->frequency}}"> {{ $dat1->frequency}}</option><br>

                                                <option style="font-size: 13px;" value=" {{'Quarterly'}}"> Quarterly</option><br>
                                                <option style="font-size: 13px;" value=" {{'Half Yearly'}}">Half Yearly</option><br>
                                                <option style="font-size: 13px;" value=" {{'Yearly'}}">Yearly</option><br>
                                            </select>
                                        </td>
                                        <div class="form-group">
                                            <input style="font-size: 13px;" type="hidden" name="sno[]" value="{{ $dat1->Oid}}">
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

                                <button for="submittl" type="submit" onclick=" return confirm('Do you want to proceed?')" class="btn btn-primary" style="font-size:13px;margin-left:91%;">Submit</button>

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
                        <h3 class="block-title">OPTIONS</h3>
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
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/objectives/listofobjectives/{{Session::get('objdept')}}/{{Session::get('year')}}">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="far fa-list-alt fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            <br> Register
                                        </p>
                                    </div>
                                </a>
                                <!-- END Products -->
                            </div>
                            <div class="col-6">
                                <!-- Sales -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/objectives/achieved">
                                    <div class="block-content text-center" style="height:130px">

                                        <i class="fas fa-check-circle fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            <br> Achieved<br>
                                        </p>
                                    </div>
                                </a>

                            </div>
                            <div class="col-6">
                                <!-- Sales -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/objectives/notachieved">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="fas fa-times-circle fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Not Achieved
                                        </p>
                                    </div>
                                </a>

                            </div>


                            <div class="col-6">
                                <!-- Sales -->
                                <br>
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/objectives/objreport" style="margin-top:-18px">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Objective report
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
    <!-- END Apps Modal -->
</body>

<script type="text/javascript">
    $(document).ready(function() {
        $('#sel_type').select2({});
    });

</script>
</html>
@endsection
