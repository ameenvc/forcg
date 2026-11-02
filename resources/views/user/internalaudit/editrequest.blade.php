@extends('layouts.multiselect')
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
                        <li class="breadcrumb-item">Audit Management</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Edit Audit</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="/user/auditschedule">Back</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->


    <div class="content">


        <!-- Horizontal -->
        <div class="block block-rounded">

            <div class="block-content block-content-full">
                <div class="row">

                    <div class="col-lg-12">
                        <form class="mb-5" action="{{url('/user/internalaudit/editrequest')}}" method="post">
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
                            <table class="table table-responsive table-bordered table-striped table-vcenter" id="dynamicTable">
                                {{-- <table class="table table-responsive table-bordered table-striped table-vcenter" id="dynamicTable"> --}}
                                <thead style="background-color:#FFFFFF">
                                    <tr>
                                        <th class="text-center px-3" style="font-size:13px;width: 50px;text-transform:capitalize">&nbsp&nbspAudit&nbspID&nbsp&nbsp</th>
                                        <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">&nbsp&nbsp&nbspDepartment&nbsp&nbsp&nbsp</th>
                                        <th class="text-center px-6" style="font-size:13px;text-transform:capitalize">&nbspScope&nbspof&nbspAudit&nbsp</th>
                                        <th class="text-center px-6" style="font-size:13px;text-transform:capitalize">Auditors</th>
                                        <th class="text-center px-6" style="font-size:13px;text-transform:capitalize">Auditee</th>
                                        <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">&nbsp&nbspQuarter&nbsp&nbsp</th>
                                        <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">From Date</th>
                                        <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">To Date</th>
                                        <th class="text-center" style="font-size:13px;text-transform:capitalize">Time</th>
                                        <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">Budgeted Hours</th>
                                        <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">&nbspFocus/Standard&nbspRequirement/Chapter/Clause&nbsp</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($data as $dat1)
                                    @php
                                    $dept = DB::table('departments')->where('id',$dat1->department)->value('departmentname');
                                    $selected_auditors = explode(',', $dat1->auditors);
                                    $standard_array = explode(',', $dat1->scope);
                                    $contact_array = explode(',', $dat1->contact);
                                    @endphp
                                    <tr>
                                        <td class="text-center font-size-sm">
                                            <input type="text" style="font-size:13px;" class="form-control" id="auditid{{ $dat1->id }}" name="auditid[]" Readonly="" value="{{$dat1->aud_id}}">
                                        </td>

                                        <td class="text-center font-size-sm">
                                            <input type="text" style="font-size:13px;" class="form-control" id="department{{ $dat1->id }}" name="department[]" Readonly="" value="{{$dept}}">
                                        </td>
                                        <td class="text-center font-size-sm">
                                            <select class="js-select2 form-control" style="font-size:13px;" required="true" id="scope{{ $dat1->id }}" name="scope{{ $dat1->id}}[]" placeholder="Choose Scope" multiple>
                                                @if(count($standards)> 0 )
                                                @foreach($standards as $value)
                                                <option value="{{$value->name }}" {{ in_array($value->name, $standard_array)? 'selected':'' }}> {{ $value->name }}</option><br>
                                                @endforeach
                                                @endif
                                            </select>

                                        </td>
                                        <td class="text-center font-size-sm">
                                            <select class="js-select2 form-control" style="font-size:13px;" required="true" id="auditors{{ $dat1->id }}" name="auditors{{ $dat1->id}}[]" placeholder="Choose Auditors" multiple>
                                                @if(count($auditors)> 0 )
                                                @foreach($auditors as $value)
                                                <option value="{{$value->auditorsname}}" {{ in_array($value->auditorsname, $selected_auditors)? 'selected':'' }}> {{ $value->auditorsname }}</option><br>
                                                @endforeach
                                                @endif
                                            </select>
                                        </td>
                                        <td class="text-center font-size-sm">
                                            <select class="js-select2 form-control" style="font-size:13px;" required="true" id="auditee{{ $dat1->id }}" name="auditee{{ $dat1->id}}[]" placeholder="Choose Auditee" multiple>
                                                @if(count($users)> 0 )
                                                @foreach($users as $value)
                                                <option value="{{$value->name}}" {{ in_array($value->name, $contact_array)? 'selected':'' }}> {{ $value->name }}</option><br>
                                                @endforeach
                                                @endif
                                            </select>
                                        </td>

                                        <td class="text-center " style="font-size:13px;width:100px;">
                                            <select class="form-control" id="select_type{{ $dat1->id }}" style="font-size: 13px;" name="quarter[]">
                                                <option style="font-size: 13px;" selected="">{{$dat1->quarter}}</option>
                                                <option style="font-size: 13px;" value=" {{'Q1'}}"> Q1</option><br>
                                                <option style="font-size: 13px;" value=" {{'Q2'}}">Q2</option><br>
                                                <option style="font-size: 13px;" value=" {{'Q3'}}"> Q3</option><br>
                                                <option style="font-size: 13px;" value=" {{'Q4'}}">Q4</option><br>
                                            </select>
                                        </td>
                                        <td class="text-center font-size-sm">
                                            <input type="date" style="font-size:13px;" class="form-control" id="date{{ $dat1->id }}" name="date[]" value="{{$dat1->date}}">
                                        </td>
                                        <td class="text-center font-size-sm">
                                            <input type="date" style="font-size:13px;" class="form-control" id="enddate{{ $dat1->id }}" name="enddate[]" value="{{$dat1->enddate}}">
                                        </td>
                                        <td class="text-center font-size-sm">
                                            <input type="time" style="font-size:13px;" class="form-control" id="time{{ $dat1->id }}" name="time[]" value="{{$dat1->time}}">
                                        </td>
                                        <td class="text-center font-size-sm">
                                            <input type="text" style="font-size:13px;" class="form-control" id="budget{{ $dat1->id }}" name="budget[]" value="{{$dat1->budgeted}}">
                                        </td>

                                        <td class="text-center font-size-sm">
                                            <textarea type="text" style="font-size:13px;" class="form-control" id="focus{{ $dat1->id }}" name="focus[]" rows="5"> {{$dat1->focus}}</textarea>
                                        </td>


                                        <div class="form-group">

                                            <input type="hidden" name="sno[]" value="{{ $dat1->id}}">

                                        </div>
                                    </tr>

                                    @endforeach
                                </tbody>

                            </table>
                            @endif

                            <div>
                                <button for="submittl" type="submit" onclick=" return confirm('Do you want to proceed?')" class="btn btn-primary" style="font-size:13px;margin-left:90%;">Submit</button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
        </main>
</body>
</html>
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
                            <a class="block block-rounded block-link-shadow bg-body" href="/user/auditschedule">
                                <div class="block-content text-center">
                                    <i class="far fa-list-alt fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Audit Schedule
                                    </p>
                                </div>
                            </a>
                            <!-- END Products -->
                        </div>
                        <div class="col-6">
                            <!-- Sales -->
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/internalaudit/auditorslist">
                                <div class="block-content text-center">
                                    <i class="fas fa-address-card fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        <br> Auditors List
                                    </p>
                                </div>
                            </a>
                            <!-- END Sales -->
                        </div>
                        <div class="col-6">
                            <!-- Payments -->
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/internalaudit/auditlog">
                                <div class="block-content text-center">
                                    <i class="fas fa-calendar-check fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        <br>Audit Log
                                    </p>
                                </div>
                            </a>
                            <!-- END Payments -->
                        </div>
                        <div class="col-6">
                            <!-- Sales -->
                            <a class="block block-rounded block-link-shadow bg-body mb-2" href="/user/internalaudit/closedaudits">
                                <div class="block-content text-center">
                                    <i class="fas fa-check-circle fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Closed Audits
                                    </p>
                                </div>
                            </a>
                            <!-- END Sales -->
                        </div>
                        <div class="col-6">
                            <!-- Sales -->
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href='/user/internalaudit/analysis'>
                                <div class="block-content text-center">
                                    <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Analysis
                                    </p>
                                </div>
                            </a>
                            <!-- END Sales -->
                        </div>
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
