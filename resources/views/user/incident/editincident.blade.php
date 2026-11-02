@extends('layouts.backend')

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center"
            style="height:0px;">

            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">Incident Report</li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="">Edit</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="/user/incidents/register">Back</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<!-- Page Content -->
<div class="content" style="">
    <!-- Horizontal -->
    <div class="block block-rounded">
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Form Horizontal - Default Style -->
                    <?php $f=0; ?>
                    <form class="mb-5" action="{{url('/user/incident/edited')}}" method="post">
                        {{ csrf_field() }}

                        @if(Session::has('editmsg'))
                        <div class="alert alert-success">
                            <a class="close" data-dismiss="alert">×</a>
                            {!!Session::get('editmsg')!!}
                        </div>
                        @endif


                        @if(count($data)>0)

                        <table class="table table-responsive table-bordered table-striped table-vcenter "
                            id="dynamicTable">

                            <thead style="background-color:#FFFFFF">
                                <tr>
                                    <th class="text-center"
                                        style="font-size:13px;width: 50px;text-transform:capitalize">Report ID</th>
                                    <th class="text-center"
                                        style="font-size:13px;width: 50px;text-transform:capitalize">Department</th>
                                    <th class="text-center"
                                        style="font-size:13px;width: 50px;text-transform:capitalize">Area</th>
                                    <th class="text-center"
                                        style="font-size:13px;width: 50px;text-transform:capitalize">Name of the
                                        injured<br />/suffered person</th>
                                    <th class="text-center"
                                        style="font-size:13px;width: 50px;text-transform:capitalize">Employee ID of
                                        injured<br />/suffered person</th>
                                    <th class="text-center"
                                        style="font-size:13px;width: 50px;text-transform:capitalize">Name of Hospital
                                    </th>
                                    <th class="text-center"
                                        style="font-size:13px;width: 50px;text-transform:capitalize">Date of Incident
                                    </th>
                                    <th class="text-center"
                                        style="font-size:13px;width: 50px;text-transform:capitalize">Time of Incident
                                    </th>
                                    <th class="text-center"
                                        style="font-size:13px;width: 50px;text-transform:capitalize">Place of the
                                        Incident</th>
                                    <th class="text-center"
                                        style="font-size:13px;width: 50px;text-transform:capitalize">Employer of the
                                        injured <br /> suffered person<br /> (state if labour supply)</th>
                                    <th class="text-center"
                                        style="font-size:13px;width: 50px;text-transform:capitalize">Trade or occupation
                                    </th>
                                    <th class="text-center"
                                        style="font-size:13px;width: 50px;text-transform:capitalize">Description</th>
                                    <th class="text-center"
                                        style="font-size:13px;width: 50px;text-transform:capitalize">Action Taken</th>
                                    <th class="text-center"
                                        style="font-size:13px;width: 50px;text-transform:capitalize">Reportable under
                                        Local Regulations?</th>
                                </tr>
                            </thead>


                            <tbody>
                                <?php $i=0;?>
                                @foreach($data as $incident)
                                <tr>
                                    <td>
                                        <input type="text" style="font-size: 13px;" value="{{$incident->report_no}}"
                                            readonly class="form-control" id="rid" name="rid[]">
                                    </td>
                                    <?php 
                                             $incdate = DateTime::createFromFormat('d/m/Y', $incident->incidentdate);
                                             $incdate=$incdate->format('Y-m-d');
                                            ?>
                                    <td>
                                        <select class="form-control" name="department[]" style="font-size: 13px;width:100%;" id="">
                                            @foreach($departments as $dept)
                                            @if($incident->department==$dept)
                                            <option value="{{$incident->department}}" selected>{{$incident->department}}
                                            </option>
                                            @else
                                            <option value="{{$dept}}">{{$dept}}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" style="font-size: 13px;" value="{{$incident->area}}" required
                                            class="form-control" id="area" name="area[]">
                                    </td>
                                    <td>
                                        <input type="text" style="font-size: 13px;" value="{{$incident->injuredperson}}"
                                            required class="form-control" id="person" name="person[]">
                                    </td>
                                    <td>
                                        <input type="text" style="font-size: 13px;" value="{{$incident->empid}}"
                                            required class="form-control" id="empid" name="empid[]">
                                    </td>
                                    <td>
                                        <input type="text" style="font-size: 13px;" value="{{$incident->hospital}}"
                                            required class="form-control" id="hospital" name="hospital[]">
                                    </td>
                                    <td>
                                        <input type="date" class="js-datepicker form-control" id="date"
                                            name="incidentdate[]" data-week-start="1" data-autoclose="true"
                                            value="{{$incdate}}" data-date-format="dd/mm/yy" placeholder="dd/mm/yy"
                                            style="font-size: 13px;">
                                    </td>
                                    <td>
                                        <input type="time" class="form-control" name="time[]" style="font-size: 13px;"
                                            placeholder="Time" value="{{$incident->time}}">
                                    </td>
                                    <td>
                                        <input type="text" style="font-size: 13px;" class="form-control" id="place"
                                            name="place[]" value="{{$incident->incidentplace}}">
                                    </td>
                                    <td>
                                        <input type="text" required style="font-size: 13px;" class="form-control"
                                            id="employer" name="employer[]" value="{{$incident->emplyoer}}"></textarea>
                                    </td>
                                    <td>
                                        <input type="text" required style="font-size: 13px;" class="form-control"
                                            id="trade" name="trade[]"
                                            value="{{$incident->trade_or_occupation}}"></textarea>
                                    </td>
                                    <td>
                                        <textarea type="text" required style="font-size: 13px;" class="form-control"
                                            id="desc" name="desc[]">{{$incident->incidentdesc}}</textarea>
                                    </td>
                                    <td>
                                        <textarea type="text" required style="font-size: 13px;" class="form-control"
                                            id="action" name="action[]">{{$incident->actions}}</textarea>
                                    </td>
                                    <td>
                                        <input type="text" style="font-size: 13px;" class="form-control" id="localreg"
                                            name="localreg[]" value="{{$incident->local_regulation}}">
                                    </td>

                                </tr>
                                <?php $i+=1 ?>
                                @endforeach
                            </tbody>

                        </table>
                        @endif
                        <div class="col-lg-10">




                            <br>
                        </div>

                        <div style="text-align:center;">
                            <button type="submit" for="submit" class="btn btn-primary"
                                style="font-size:13px; float:right;margin-right:0%;" data-toggle="click-ripple"
                                onclick="return confirm('Do you want to proceed ? ')">Submit</button>
                            <button for="reset" type="reset" class="btn btn-secondary"
                                style="font-size:13px;float:right;margin-right:1%; ">Reset</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- <footer id="page-footer" class="bg-body-light">
                <div class="content py-3">
                    <div class="row font-size-sm">
                        <div class="col-sm-6 order-sm-2 py-1 text-center text-sm-right">
                           </div>
                        <div class="col-sm-6 order-sm-1 py-1 text-center text-sm-left">
                            <a style="margin-left:35%;" class="font-w600" href="" target="_blank">SETILAS QuickQM 2.1.1</a> &copy; <span data-toggle="year-copy"></span>
                        </div>
                    </div>
                </div>
            </footer> --}}
    <!-- Apps Modal -->
    <!-- Opens from the modal toggle button in the header -->
    <!-- Opens from the modal toggle button in the header -->
    <!-- Opens from the modal toggle button in the header -->
    <div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps"
        aria-hidden="true">
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
                                <!-- Products -->
                                <a class="block block-rounded block-link-shadow bg-body"
                                    href="/user/incidents/register">
                                    <div class="block-content text-center">
                                        <i class="far fa-list-alt fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Incident Register
                                        </p>
                                    </div>
                                </a>
                                <!-- END Products -->
                            </div>
                            <div class="col-6">
                                <!-- Products -->
                                <a class="block block-rounded block-link-shadow bg-body"
                                    href="/user/incidents/closed/incidents">
                                    <div class="block-content text-center">
                                        <i class="far fa-list-alt fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Closed Incident
                                        </p>
                                    </div>
                                </a>
                                <!-- END Products -->
                            </div>
                            <div class="col-6">

                                <a class="block block-rounded block-link-shadow bg-body mb-0"
                                    href="/user/incident/correactions">
                                    <div class="block-content text-center">
                                        <i class="fas fa-clone fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            My Open Corrective Actions
                                        </p>
                                    </div>
                                </a>

                            </div>
                            <!--<div class="col-6">
                                        
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/risks/closedrisks">
                                            <div class="block-content text-center">
                                                <i class="si si-plane fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Closed Risks
                                                </p>
                                            </div>
                                        </a>
                                       
                                    </div>
                                    <div class="col-6">
                                       
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/risks/analysis">
                                            <div class="block-content text-center">
                                                <i class="si si-wallet fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Analysis
                                                </p>
                                            </div>
                                        </a>
                                   
                                    </div>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Apps Modal -->
    @endsection