@extends('layouts.backend')
@section('content')
<!doctype html>
<html lang="en">


<body>

    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">

                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">HR Management</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Add Training</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="/user/hrtmgmt">Back</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">


        <!-- Horizontal -->
        <div class="block block-rounded">

            <div class="block-content block-content-full">
                <div class="row">

                    <div class="col-lg-10">
                        <!-- Form Horizontal - Default Style -->
                        <form name="form" class="mb-5" action="{{url('/user/hr/trainingsubmit')}}" method="POST">
                            {{csrf_field()}}

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

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="title" style="font-size:13px;">Training Title <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" class="form-control" id="title" name="title" value="{{ old('title') }}" placeholder="Enter Training Title" required>
                                </div>
                            </div>
                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="in_out" style="font-size:13px;">Inhouse / Outside <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select class="form-control" name="in_out" id="in_out" style="font-size: 13px;">
                                        <option value="0" selected="" disabled>Choose</option>
                                        <option value="Inhouse" {{ old('in_out') === 'Inhouse' ? 'selected' : ''}}>Inhouse</option>
                                        <option value="Outside" {{ old('in_out') === 'Outside' ? 'selected' : ''}}>Outside</option>
                                    </select>

                                </div>
                            </div>
                            <div class="form-group" style="display:inline-block;width:98.5%;">
                                <label class="col-sm-12 col-form-label" for="type" style="font-size:13px;">Training Type</label>
                                <div class="col-sm-12">
                                    <div class="form-check">
                                        <label class="form-check-label" id="lblappr1" style="font-size:13px;">
                                            <input class="form-check-input" type="checkbox" name="type[]" id="type" value="Induction" {{ old('type') && in_array('Induction', old('type')) ? 'checked' : '' }}><span></span>Induction&nbsp;&nbsp;&nbsp;&nbsp;
                                        </label><br>
                                        <label class="form-check-label" id="lblappr2" style="font-size:13px;">
                                            <input class="form-check-input" type="checkbox" name="type[]" id="type" value="On Job" {{ old('type') && in_array('On Job', old('type')) ? 'checked' : '' }}><span></span>On Job&nbsp;&nbsp;&nbsp;&nbsp;
                                        </label><br>
                                        <label class="form-check-label" id="lblappr3" style="font-size:13px;">
                                            <input class="form-check-input" type="checkbox" name="type[]" id="type" value="Awareness" {{ old('type') && in_array('Awareness', old('type')) ? 'checked' : '' }}><span></span>Awareness&nbsp;&nbsp;&nbsp;&nbsp;
                                        </label><br>
                                        <label class="form-check-label" id="lblappr4" style="font-size:13px;">
                                            <input class="form-check-input" type="checkbox" name="type[]" id="type" value="Certificate" {{ old('type') && in_array('Certificate', old('type')) ? 'checked' : '' }}><span></span>Certificate&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </label><br>
                                        <label class="form-check-label" id="lblappr5" style="font-size:13px;">
                                            <input class="form-check-input" type="checkbox" name="type[]" id="type" value="License" {{ old('type') && in_array('License', old('type')) ? 'checked' : '' }}><span></span>License&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </label>
                                        <br> <label class="form-check-label" id="lblappr6" style="font-size:13px;">
                                            <input class="form-check-input" type="checkbox" name="type[]" id="type" value="Competitive" {{ old('type') && in_array('Competitive', old('type')) ? 'checked' : '' }}><span></span>Competitive
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="agency" style="font-size:13px;top:0;" id="lblagency">Training Agency</label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" class="form-control" id="agency" name="agency" placeholder="Enter Training Agency" value="{{ old('agency')}}">
                                </div>
                            </div>
                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="provider" style="font-size:13px;">Name Of Trainer <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" class="form-control" id="provider" name="provider" placeholder="Enter Name of Trainer" required value="{{ old('provider')}}">
                                </div>
                            </div>
                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="dur" style="font-size:13px;">Training Duration(In Hours)<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" class="form-control" id="dur" name="dur" placeholder="Enter Training Duration" required value="{{ old('dur') }}">
                                </div>
                            </div>
                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="periods" style="font-size:13px;">Date Of Training</label>
                                <div class="col-sm-12">
                                    <input type="date" class="js-datepicker form-control" id="periods" name="periods" value="{{ old('periods')}}" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy" style="font-size: 13px;">
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:98.5%;">
                                <label class="col-sm-12 col-form-label" for="benefits" style="font-size:13px;">Benefits Of Training <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <textarea rows="9" type="text" style="font-size: 13px;" class="form-control" id="benefits" name="benefits" placeholder="Enter Training Benefits" required>{{ old('benefits')}}</textarea>

                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="empname" style="font-size:13px;">Employee Details <span class="text-danger">*</span> </label>
                                <div class="col-sm-12">
                                    <table style="border:none;" id="dynamicTable" cellpadding="5">
                                        <tr>
                                            <td>
                                                <select class="form-control" onchange="sel(this.value,'{{$emp}}','{{$dep}}')" id="empnumber[]" name="number[]" required="true" style="font-size: 13px;width:230px;">
                                                    <option value="">Choose Employee Number</option>
                                                    @if(count($emp)>0)
                                                    @foreach($emp as $empd)
                                                    <option value="{{$empd->emp_number}}">{{$empd->emp_number}} - {{$empd->emp_name}}</option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" style="font-size: 13px;width:230px;" class="form-control" id="name[0]" name="empname[]" placeholder="Employee Name" readonly>
                                            </td>
                                            <td>
                                                <input type="text" style="font-size:13px;width:230px;" class="form-control" id="position[0]" name="pos[]" required placeholder="Employee Position" readonly>
                                            </td>
                                            <td>
                                                <input type="text" style="font-size:13px;width:230px;" class="form-control" id="department[0]" name="department[]" required placeholder="Employee Department" readonly>
                                            </td>

                                            <td><button type="button" style="font-size:12px;" name="add" id="add" class="btn btn-sm btn-light" data-toggle="tooltip" title="Add">
                                                    <i class="fa fa-fw fa-plus"></i>
                                                </button>
                                            </td>

                                        </tr>

                                    </table>
                                </div>
                            </div>



                            <div class="form-group row">
                                <div class="col-sm-8 ml-auto">
                                    <button type="submit" class="btn btn-primary" style="font-size:13px;margin-left:449px;" onclick="return confirm('Are you sure to submit the request?')">Submit</button>
                                </div>
                            </div>
                        </form>
                        <!-- END Form Horizontal - Default Style -->

                    </div>
                </div>
            </div>
        </div>
        <!-- END Horizontal -->



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
                                    <a class="block block-rounded block-link-shadow bg-body" href="/user/hr/register">
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="far fa-list-alt fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Job Description
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Products -->
                                </div>
                                <div class="col-6">
                                    <!-- Products -->
                                    <a class="block block-rounded block-link-shadow bg-body" href="/user/hr/trainingregister">
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Training Schedule
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Products -->
                                </div>

                                <div class="col-6">
                                    <a class="block block-rounded block-link-shadow bg-body" href="/user/employeelist">
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-clone fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Employee List
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
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        let in_out = "{{ old('in_out')}}";
        if (in_out != "Outside") {
            $('#lblagency').hide();
            $('#agency').hide();
        }

    });
    $("#in_out").change(function() {
        if ($("#in_out option:selected").val() == "Outside") {
            $('#lblagency').show();
            $('#agency').show();
        } else {
            $('#lblagency').hide();
            $('#agency').hide();
        }
    });

</script>

<script type="text/javascript">
    var i = 0;
    $("#add").click(function() {
        ++i;
        $("#dynamicTable").append('<tr><td><select class="form-control" onchange="sel(this.value,\'{{$emp}}\',\'{{$dep}}\',' + i + ')" id="empnumber[]" name = "number[]" required="true" style="font-size: 13px;width:230px;"><option value="">Choose Employee Number</option>)@foreach($emp as $empd)<option  value="{{$empd->emp_number}}">{{$empd->emp_number}} - {{$empd->emp_name}}</option>@endforeach</select></td><td><input type="text"style="font-size: 13px;width:230px;" class="form-control" id="name[' + i + ']" name="empname[]" placeholder="Employee Name" readonly></td><td><input type="text"style="font-size: 13px;width:230px;" class="form-control" name="pos[]" id="position[' + i + ']" placeholder="Enter Position" readonly></td><td><input type="text"style="font-size:13px;width:230px;" class="form-control" id="department[' + i + ']" name="department[]" required placeholder="Employee Department" readonly></td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<td><button type="button" name="add" id="add" class="btn btn-sm btn-light" data-toggle="tooltip" title="Remove Employee Name"> <i class="fa fa-fw remove-tr fa-times"></i></button></td></tr>');

    });
    $(document).on('click', '.remove-tr', function() {
        $(this).parents('tr').remove();
    });

</script>

<script>
    function sel(value, details, deprts, i = 0) {
        datas = JSON.parse(details);
        depmts = JSON.parse(deprts);
        for (var key in datas) {
            if (datas[key].emp_number === value) {
                document.getElementById('name[' + i + ']').value = datas[key].emp_name;
                var a = datas[key].emp_department;
                deprtname = '-';
                for (let m = 0; m < depmts.length; m++) {
                    if (depmts[m].id == a) {
                        deprtname = depmts[m].departmentname;
                        break;
                    }
                }
                document.getElementById('department[' + i + ']').value = deprtname;
                if (datas[key].emp_position == '') {
                    document.getElementById('position[' + i + ']').value = '-';
                } else {
                    document.getElementById('position[' + i + ']').value = datas[key].emp_position;
                }
            }
        }
    }

</script>


</html>
@endsection
