@extends('superadmin.datatable')
@section('content')


<!-- END Hero -->
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">

            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">Enable/Disable Module</li>

                    <li class="breadcrumb-item" aria-current="page">
                        <a id="a1" onclick="" class="link-fx" href="/superadmin/home">Back</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<form action="{{ route('superadmin.disenaconfirm')}}" method="post">
    @csrf
    <ul class="list-group push" style="margin-left:80px;margin-right:80px;margin-bottom:20px;margin-top:30px;">
        <li class="list-group-item d-flex justify-content-between align-items-center" style="font-size: 13px;">
            Objectives
            <div class="col-2">
                <div class="custom-control custom-switch custom-control-primary custom-control-lg mb-2">
                    @if($module->objectives>0)
                    <input type="checkbox" class="custom-control-input" id="obj" name="obj" checked style="font-size: 13px;">
                    <label class="custom-control-label" for="obj" style="font-size: 13px;"></label>
                    @else
                    <input type="checkbox" class="custom-control-input" id="obj" name="obj" style="font-size: 13px;">
                    <label class="custom-control-label" for="obj" style="font-size: 13px;"></label>
                    @endif
                </div>
            </div>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center" style="font-size: 13px;">
            Audit Management
            <div class="col-2">
                <div class="custom-control custom-switch custom-control-primary custom-control-lg mb-2">
                    @if($module->auditmanagement>0)
                    <input type="checkbox" onclick="check1()" class="custom-control-input" id="audit" name="audit" checked style="font-size: 13px;">
                    <label class="custom-control-label" for="audit" style="font-size: 13px;"></label>
                    @else
                    <input type="checkbox" onclick="check1()" class="custom-control-input" id="audit" name="audit" style="font-size: 13px;">
                    <label class="custom-control-label" for="audit" style="font-size: 13px;"></label>
                    @endif
                </div>
            </div>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center" style="font-size: 13px;">
            Audit Scheduling and Logs
            <div class="col-2">
                <div class="custom-control custom-switch custom-control-primary custom-control-lg mb-2">
                    @if($module->auditschedulelogs>0)
                    <input type="checkbox" class="custom-control-input" id="auditschedule" name="auditschedule" checked style="font-size: 13px;">
                    <label class="custom-control-label" for="auditschedule" style="font-size: 13px;"></label>
                    @else
                    <input type="checkbox" class="custom-control-input" id="auditschedule" style="font-size: 13px;" name="auditschedule">
                    <label class="custom-control-label" for="auditschedule" style="font-size: 13px;"></label>
                    @endif
                </div>
            </div>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center" style="font-size: 13px;">
            Compliances
            <div class="col-2">
                <div class="custom-control custom-switch custom-control-primary custom-control-lg mb-2">
                    @if($module->compliance>0)
                    <input type="checkbox" class="custom-control-input" id="comp" name="comp" checked style="font-size: 13px;">
                    <label class="custom-control-label" for="comp" style="font-size: 13px;"></label>
                    @else
                    <input type="checkbox" class="custom-control-input" id="comp" name="comp" style="font-size: 13px;">
                    <label class="custom-control-label" for="comp" style="font-size: 13px;"></label>
                    @endif
                </div>
            </div>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center" style="font-size: 13px;">
            Customer Satisfaction
            <div class="col-2">
                <div class="custom-control custom-switch custom-control-primary custom-control-lg mb-2">
                    @if($module->customer_satisfaction >0)
                    <input type="checkbox" class="custom-control-input" id="survey" name="survey" checked style="font-size: 13px;">
                    <label class="custom-control-label" for="survey" style="font-size: 13px;"></label>
                    @else
                    <input type="checkbox" class="custom-control-input" id="survey" name="survey" style="font-size: 13px;">
                    <label class="custom-control-label" for="survey" style="font-size: 13px;"></label>
                    @endif
                </div>
            </div>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center" style="font-size: 13px;">
            e-Learning
            <div class="col-2">
                <div class="custom-control custom-switch custom-control-primary custom-control-lg mb-2">
                    @if($module->elearning >0)
                    <input type="checkbox" class="custom-control-input" id="elearning" name="elearning" checked style="font-size: 13px;">
                    <label class="custom-control-label" for="elearning" style="font-size: 13px;"></label>
                    @else
                    <input type="checkbox" class="custom-control-input" id="elearning" name="elearning" style="font-size: 13px;">
                    <label class="custom-control-label" for="elearning" style="font-size: 13px;"></label>
                    @endif
                </div>
            </div>
        </li>


        <li class="list-group-item d-flex justify-content-between align-items-center" style="font-size: 13px;">
            Enterprise Risk
            <div class="col-2">
                <div class="custom-control custom-switch custom-control-primary custom-control-lg mb-2">
                    @if($module->riskmanagement>0)
                    <input type="checkbox" class="custom-control-input" id="rm" name="rm" checked style="font-size: 13px;">
                    <label class="custom-control-label" for="rm" style="font-size: 13px;"></label>
                    @else
                    <input type="checkbox" class="custom-control-input" id="rm" name="rm" style="font-size: 13px;">
                    <label class="custom-control-label" for="rm" style="font-size: 13px;"></label>
                    @endif

                </div>
            </div>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center" style="font-size: 13px;">
            Opportunity
            <div class="col-2">
                <div class="custom-control custom-switch custom-control-primary custom-control-lg mb-2">
                    @if($module->opportunity>0)
                    <input type="checkbox" class="custom-control-input" id="op" name="op" checked style="font-size: 13px;">
                    <label class="custom-control-label" for="op" style="font-size: 13px;"></label>
                    @else
                    <input type="checkbox" class="custom-control-input" id="op" name="op" style="font-size: 13px;">
                    <label class="custom-control-label" for="op" style="font-size: 13px;"></label>
                    @endif
                </div>
            </div>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center" style="font-size: 13px;">
            NCR & Corrective Actions
            <div class="col-2">
                <div class="custom-control custom-switch custom-control-primary custom-control-lg mb-2">
                    @if($module->correctiveaction>0)
                    <input type="checkbox" class="custom-control-input" id="as" name="as" checked style="font-size: 13px;">
                    <label class="custom-control-label" for="as" style="font-size: 13px;"></label>
                    @else
                    <input type="checkbox" class="custom-control-input" id="as" name="as" style="font-size: 13px;">
                    <label class="custom-control-label" for="as" style="font-size: 13px;"></label>
                    @endif
                </div>
            </div>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center" style="font-size: 13px;">
            Management Review
            <div class="col-2">
                <div class="custom-control custom-switch custom-control-primary custom-control-lg mb-2">
                    @if($module->managementreview>0)
                    <input type="checkbox" class="custom-control-input" id="management_review" name="management_review" checked style="font-size: 13px;">
                    <label class="custom-control-label" for="management_review" style="font-size: 13px;"></label>
                    @else
                    <input type="checkbox" class="custom-control-input" id="management_review" name="management_review" style="font-size: 13px;">
                    <label class="custom-control-label" for="management_review" style="font-size: 13px;"></label>
                    @endif
                </div>
            </div>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center" style="font-size: 13px;">
            Emergency Response
            <div class="col-2">
                <div class="custom-control custom-switch custom-control-primary custom-control-lg mb-2">
                    @if($module->emergencyresponse>0)
                    <input type="checkbox" class="custom-control-input" id="emergency_response" name="emergency_response" checked style="font-size: 13px;">
                    <label class="custom-control-label" for="emergency_response" style="font-size: 13px;"></label>
                    @else
                    <input type="checkbox" class="custom-control-input" id="emergency_response" name="emergency_response" style="font-size: 13px;">
                    <label class="custom-control-label" for="emergency_response" style="font-size: 13px;"></label>
                    @endif
                </div>
            </div>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center" style="font-size: 13px;">
            Environmental Aspects
            <div class="col-2">
                <div class="custom-control custom-switch custom-control-primary custom-control-lg mb-2">
                    @if($module->enviaspects>0)
                    <input type="checkbox" class="custom-control-input" id="ea" name="ea" checked style="font-size: 13px;">
                    <label class="custom-control-label" for="ea" style="font-size: 13px;"></label>
                    @else
                    <input type="checkbox" class="custom-control-input" id="ea" name="ea" style="font-size: 13px;">
                    <label class="custom-control-label" for="ea" style="font-size: 13px;"></label>
                    @endif
                </div>
            </div>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center" style="font-size: 13px;">
            OHS Risk
            <div class="col-2">
                <div class="custom-control custom-switch custom-control-primary custom-control-lg mb-2">
                    @if($module->ohsrisk>0)
                    <input type="checkbox" class="custom-control-input" id="ohs" name="ohs" checked style="font-size: 13px;">
                    <label class="custom-control-label" for="ohs" style="font-size: 13px;"></label>
                    @else
                    <input type="checkbox" class="custom-control-input" id="ohs" name="ohs" style="font-size: 13px;">
                    <label class="custom-control-label" for="ohs" style="font-size: 13px;"></label>
                    @endif
                </div>
            </div>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center" style="font-size: 13px;">
            Document Management
            <div class="col-2">
                <div class="custom-control custom-switch custom-control-primary custom-control-lg mb-2">
                    @if($module->documentmanagement>0)
                    <input type="checkbox" class="custom-control-input" id="da" name="da" checked style="font-size: 13px;">
                    <label class="custom-control-label" for="da" style="font-size: 13px;"></label>
                    @else
                    <input type="checkbox" class="custom-control-input" id="da" name="da" style="font-size: 13px;">
                    <label class="custom-control-label" for="da" style="font-size: 13px;"></label>
                    @endif
                </div>
            </div>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center" style="font-size: 13px;">
            Customer Complaint
            <div class="col-2">
                <div class="custom-control custom-switch custom-control-primary custom-control-lg mb-2">
                    @if($module->complaintmanagement>0)
                    <input type="checkbox" class="custom-control-input" id="cmpl" name="cmpl" checked style="font-size: 13px;">
                    <label class="custom-control-label" for="cmpl" style="font-size: 13px;"></label>
                    @else
                    <input type="checkbox" class="custom-control-input" id="cmpl" name="cmpl" style="font-size: 13px;">
                    <label class="custom-control-label" for="cmpl" style="font-size: 13px;"></label>
                    @endif
                </div>
            </div>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center" style="font-size: 13px;">
            Context Management
            <div class="col-2">
                <div class="custom-control custom-switch custom-control-primary custom-control-lg mb-2">
                    @if($module->contextmanagement>0)
                    <input type="checkbox" class="custom-control-input" id="cntx" name="cntx" checked style="font-size: 13px;">
                    <label class="custom-control-label" for="cntx" style="font-size: 13px;"></label>
                    @else
                    <input type="checkbox" class="custom-control-input" id="cntx" name="cntx" style="font-size: 13px;">
                    <label class="custom-control-label" for="cntx" style="font-size: 13px;"></label>
                    @endif
                </div>
            </div>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center" style="font-size: 13px;">
            Vendor Management
            <div class="col-2">
                <div class="custom-control custom-switch custom-control-primary custom-control-lg mb-2">
                    @if($module->vendormanagement>0)
                    <input type="checkbox" class="custom-control-input" id="vendor" name="vendor" checked style="font-size: 13px;">
                    <label class="custom-control-label" for="vendor" style="font-size: 13px;"></label>
                    @else
                    <input type="checkbox" class="custom-control-input" id="vendor" name="vendor" style="font-size: 13px;">
                    <label class="custom-control-label" for="vendor" style="font-size: 13px;"></label>
                    @endif
                </div>
            </div>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center" style="font-size: 13px;">
            Incident Report
            <div class="col-2">
                <div class="custom-control custom-switch custom-control-primary custom-control-lg mb-2">
                    @if($module->incidentreport>0)
                    <input type="checkbox" class="custom-control-input" id="incident" name="incident" checked style="font-size: 13px;">
                    <label class="custom-control-label" for="incident" style="font-size: 13px;"></label>
                    @else
                    <input type="checkbox" class="custom-control-input hello" id="incident" name="incident" style="font-size: 13px;">
                    <label class="custom-control-label" for="incident" style="font-size: 13px;"></label>
                    @endif
                </div>
            </div>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center" style="font-size: 13px;">
            Hse Observation
            <div class="col-2">
                <div class="custom-control custom-switch custom-control-primary custom-control-lg mb-2">
                    @if($module->hse>0)
                    <input type="checkbox" class="custom-control-input" id="hse" name="hse" checked style="font-size: 13px;">
                    <label class="custom-control-label" for="hse" style="font-size: 13px;"></label>
                    @else
                    <input type="checkbox" class="custom-control-input hello" id="hse" name="hse" style="font-size: 13px;">
                    <label class="custom-control-label" for="hse" style="font-size: 13px;"></label>
                    @endif
                </div>
            </div>
        </li>

        <li class="list-group-item d-flex justify-content-between align-items-center" style="font-size: 13px;">
            HR
            <div class="col-2">
                <div class="custom-control custom-switch custom-control-primary custom-control-lg mb-2">
                    @if($module->hr>0)
                    <input type="checkbox" class="custom-control-input" id="hr" name="hr" checked style="font-size: 13px;">
                    <label class="custom-control-label" for="hr" style="font-size: 13px;"></label>
                    @else
                    <input type="checkbox" class="custom-control-input hello" id="hr" name="hr" style="font-size: 13px;">
                    <label class="custom-control-label" for="hr" style="font-size: 13px;"></label>
                    @endif
                </div>
            </div>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center" style="font-size: 13px;">
            Maintenance
            <div class="col-2">
                <div class="custom-control custom-switch custom-control-primary custom-control-lg mb-2">
                    @if($module->maintainance>0)
                    <input type="checkbox" class="custom-control-input" id="maintainance" name="maintainance" checked style="font-size: 13px;">
                    <label class="custom-control-label" for="maintainance" style="font-size: 13px;"></label>
                    @else
                    <input type="checkbox" class="custom-control-input hello" id="maintainance" name="maintainance" style="font-size: 13px;">
                    <label class="custom-control-label" for="maintainance" style="font-size: 13px;"></label>
                    @endif
                </div>
            </div>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center" style="font-size: 13px;">
            Calibration
            <div class="col-2">
                <div class="custom-control custom-switch custom-control-primary custom-control-lg mb-2">
                    @if($module->calibration>0)
                    <input type="checkbox" class="custom-control-input" id="calibration" name="calibration" checked style="font-size: 13px;">
                    <label class="custom-control-label" for="calibration" style="font-size: 13px;"></label>
                    @else
                    <input type="checkbox" class="custom-control-input hello" id="calibration" name="calibration" style="font-size: 13px;">
                    <label class="custom-control-label" for="calibration" style="font-size: 13px;"></label>
                    @endif
                </div>
            </div>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center" style="font-size: 13px;">
            Occupational Health
            <div class="col-2">
                <div class="custom-control custom-switch custom-control-primary custom-control-lg mb-2">
                    @if($module->occhealth>0)
                    <input type="checkbox" class="custom-control-input" id="occhealth" name="occhealth" checked style="font-size: 13px;">
                    <label class="custom-control-label" for="occhealth" style="font-size: 13px;"></label>
                    @else
                    <input type="checkbox" class="custom-control-input hello" id="occhealth" name="occhealth" style="font-size: 13px;">
                    <label class="custom-control-label" for="occhealth" style="font-size: 13px;"></label>
                    @endif
                </div>
            </div>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center" style="font-size: 13px;">
            Waste Management
            <div class="col-2">
                <div class="custom-control custom-switch custom-control-primary custom-control-lg mb-2">
                    @if($module->wastemanagement>0)
                    <input type="checkbox" class="custom-control-input" id="wastemanagement" name="wastemanagement" checked style="font-size: 13px;">
                    <label class="custom-control-label" for="wastemanagement" style="font-size: 13px;"></label>
                    @else
                    <input type="checkbox" class="custom-control-input hello" id="wastemanagement" name="wastemanagement" style="font-size: 13px;">
                    <label class="custom-control-label" for="wastemanagement" style="font-size: 13px;"></label>
                    @endif
                </div>
            </div>
        </li>

        <li class="list-group-item d-flex justify-content-between align-items-center" style="font-size: 13px;">
            Near Miss
            <div class="col-2">
                <div class="custom-control custom-switch custom-control-primary custom-control-lg mb-2">
                    @if($module->nearmiss>0)
                    <input type="checkbox" class="custom-control-input" id="nearmiss" name="nearmiss" checked style="font-size: 13px;">
                    <label class="custom-control-label" for="nearmiss" style="font-size: 13px;"></label>
                    @else
                    <input type="checkbox" class="custom-control-input hello" id="nearmiss" name="nearmiss" style="font-size: 13px;">
                    <label class="custom-control-label" for="nearmiss" style="font-size: 13px;"></label>
                    @endif
                </div>
            </div>
        </li>
        <li class="list-group-item  justify-content-between align-items-center">
            <div>
                <button style="margin-left:50%;" class="btn btn-primary" type="submit" style="font-size: 13px;">Update</button>
            </div>
        </li>
    </ul>
</form>
<!-- END Services -->



<script type="text/javascript">
    window.onload = function() {
        check();
    };

    function check() {

        if (!document.getElementById('audit').checked) {
            document.getElementById('auditschedule').disabled = true;
            //   document.getElementById('tr1').style.opacity="0.3";
            //   document.getElementById('tr1').style.backgroundColor="transparent";
        }

    }

    function check1() {
        if (document.getElementById('audit').checked) {
            document.getElementById('auditschedule').disabled = false;
            //   document.getElementById('tr1').style.opacity="";
            //   document.getElementById('tr1').style.backgroundColor="";
        } else if (!document.getElementById('audit').checked) {
            document.getElementById('auditschedule').disabled = true;
            //   document.getElementById('tr1').style.opacity="0.3";
            //   document.getElementById('tr1').style.backgroundColor="transparent";
        }
    }

</script>

@endsection
