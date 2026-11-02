@extends('admin.nav')
@section('css_after')
<style>
    .display-true {
        display: table-row !important;
    }

</style>
@endsection
@section('content')

<main id="main-container" style="margin-top:-10px;margin-left:22px;margin-right:22px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 style="font-size:15px" class="mb-0">Enable / Disable Modules</h3>
        <a href="{{ url('/admin/viewUsers') }}" class="btn btn-sm btn-secondary">
            <i class="fa fa-arrow-left"></i> Back
        </a>
    </div>
    <div class="block">
        <div class="row justify-content-center" style="margin-top:-10px;">

            <div style="margin-left:40px;margin-top:50px;width:100%;margin-right:40px;">
                @php
                $i = 0;
                $ids = [];
                $mails = [];
                @endphp
                @foreach($users as $u)
                @php
                $ids[$i] = $u->id;
                $i++;
                @endphp
                @endforeach
                @php
                $ids = implode(',', $ids);
                @endphp
                <div class="card-header">
                    <h5 style="font-size:15px" class="text-primary">{{$names}}</h5>
                </div>
                <div class="responsive">
                    <div>
                        <form action="{{ route('admin.submit_enable_disable_module') }}" method="post">
                            {{csrf_field()}}
                            <input type="hidden" name="ids" value="{{ $ids }}">
                            <table class="table table-bordered table-striped table-vcenter" style="margin-top:-20px;">
                                <tr style="background-color:#deebf7">
                                    <th style="font-size:12px;background-color:#deebf7">Module</th>
                                    <th style="font-size:12px;background-color:#deebf7">Description</th>
                                    <th style="font-size:12px;background-color:#deebf7">Status</th>
                                </tr>
                                <tr style="background-color:#ededed">
                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:All Modules</span></td>
                                </tr>
                                <tr style="background-color:white">
                                    <td><span class="label label-success" style="font-size:12px">All pages</span></td>
                                    <td><span class="label label-success" style="font-size:11px">Activate this button to enable all modules simultaneously. Deactivate it to manage individual module access.</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="all" id="all" class="custom-control-input" value="all" class="custom-control-input" style="position: fixed;left: 600px" {{ $enable_all || (count(explode(',', $ids))) > 1  || $enable_all_condition ? 'checked' : ''}}>

                                            <label class="custom-control-label" for="all"></label>
                                        </div>
                                    </td>
                                </tr>
                                @if(count(explode(',', $ids)) === 1)
                                 <tr style="background-color:#ededed" class="sub-tr d-none">
                                     <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">General</span></td>
                                 </tr>
                                 <tr style="background-color:white" class="sub-tr d-none">
                                     <td><span class="label label-success" style="font-size:12px">Main Dashboard</span></td>
                                     <td><span class="label label-success" style="font-size:11px">Enable/Disable dashboard</span></td>
                                     <td>
                                         <div class="custom-control custom-switch custom-control-success mb-1">
                                             <input type="checkbox" name="dashboard" id="dashboard" class="custom-control-input" value="dashboard" class="custom-control-input" style="position: fixed;left: 600px" {{ $enable_dashboard || $enable_all ? 'checked' : ''}}>
                                             <label class="custom-control-label" for="dashboard"></label>
                                         </div>
                                     </td>
                                 </tr>

                                <tr style="background-color:#ededed" class="sub-tr d-none">
                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:Customer Complaint</span></td>
                                </tr>

                                <tr style="background-color:white" class="sub-tr d-none">


                                    <td><span class="label label-success" style="font-size:12px">All pages</span></td>
                                    <td><span class="label label-success" style="font-size:11px">All pages in the module can be visible</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="complaint_all" id="example-sw-custom-success1" class="custom-control-input" value="complaint_all" class="custom-control-input" style="position: fixed;left: 600px" {{ $complaint_all_pages || $enable_all ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success1"></label>
                                        </div>
                                    </td>
                                </tr>

                                <tr style="background-color:#ededed" class="sub-tr d-none">

                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:Compliance</span></td>

                                </tr>
                                <tr style="background-color:white" class="sub-tr d-none">


                                    <td><span class="label label-success" style="font-size:12px">All pages</span></td>
                                    <td><span class="label label-success" style="font-size:11px">All pages in the module can be visible</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="compliance_all" value="compliance_all" id="example-sw-custom-success2" class="custom-control-input" style="position: fixed;left: 600px" {{ $compliance_all_pages || $enable_all ? 'checked' : ''}}>

                                            <label class="custom-control-label" for="example-sw-custom-success2"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="sub-tr d-none">


                                    <td><span class="label label-success" style="font-size:12px">Analysis</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To adjust the view of analysis page</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="compliance_analysis" value="compliance_analysis" id="example-sw-custom-success3" class="custom-control-input" style="position: fixed;left: 600px" {{ $compliance_analysis_page || $enable_all ? 'checked' : ''}}>

                                            <label class="custom-control-label" for="example-sw-custom-success3"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:#ededed" class="sub-tr d-none">


                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:Context</span></td>

                                </tr>
                                <tr style="background-color:white" class="sub-tr d-none">


                                    <td><span class="label label-success" style="font-size:12px">All pages</span></td>
                                    <td><span class="label label-success" style="font-size:11px">All pages in the module can be visible</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="context_all" value="context_all" id="example-sw-custom-success4" class="custom-control-input" style="position: fixed;left: 600px" {{ $context_all_pages || $enable_all ? 'checked' : ''}}>

                                            <label class="custom-control-label" for="example-sw-custom-success4"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:#ededed" class="sub-tr d-none">


                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:Customer Satisfaction</span></td>

                                </tr>
                                <tr style="background-color:white" class="sub-tr d-none">


                                    <td><span class="label label-success" style="font-size:12px">All pages</span></td>
                                    <td><span class="label label-success" style="font-size:11px">All pages in the module can be visible</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="customer_all" value="customer_all" id="example-sw-custom-success5" class="custom-control-input" style="position: fixed;left: 600px" {{ $customer_all_pages || $enable_all ? 'checked' : ''}}>

                                            <label class="custom-control-label" for="example-sw-custom-success5"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="sub-tr d-none">


                                    <td><span class="label label-success" style="font-size:12px">Analysis</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To adjust the view of analysis page</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="customer_analysis" value="customer_analysis" id="example-sw-custom-success6" class="custom-control-input" style="position: fixed;left: 600px" {{ $customer_analysis_page || $enable_all ? 'checked' : ''}}>

                                            <label class="custom-control-label" for="example-sw-custom-success6"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:#ededed" class="sub-tr d-none">


                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:Emergency Response</span></td>
                                </tr>
                                <tr style="background-color:white" class="sub-tr d-none">


                                    <td><span class="label label-success" style="font-size:12px">All pages</span></td>
                                    <td><span class="label label-success" style="font-size:11px">All pages in the module can be visible</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="emergency_all" value="emergency_all" id="example-sw-custom-success7" class="custom-control-input" style="position: fixed;left: 600px" {{ $emergency_all_pages || $enable_all ? 'checked' : ''}}>

                                            <label class="custom-control-label" for="example-sw-custom-success7"></label>
                                        </div>
                                    </td>
                                </tr>

                                <tr style="background-color:#ededed" class="sub-tr d-none">


                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:Enterprise Risk</span></td>
                                </tr>
                                <tr style="background-color:white" class="sub-tr d-none">


                                    <td><span class="label label-success" style="font-size:12px">All pages</span></td>
                                    <td><span class="label label-success" style="font-size:11px">All pages in the module can be visible</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="risk_all" value="risk_all" id="example-sw-custom-success8" class="custom-control-input" style="position: fixed;left: 600px" {{ $risk_all_pages || $enable_all ? 'checked' : ''}}>

                                            <label class="custom-control-label" for="example-sw-custom-success8"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="sub-tr d-none">


                                    <td><span class="label label-success" style="font-size:12px">Analysis</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To adjust the view of analysis page</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="risk_analysis" value="risk_analysis" id="example-sw-custom-success9" class="custom-control-input" style="position: fixed;left: 600px" {{ $risk_analysis_page || $enable_all ? 'checked' : ''}}>

                                            <label class="custom-control-label" for="example-sw-custom-success9"></label>
                                        </div>
                                    </td>
                                </tr>

                                <tr style="background-color:#ededed" class="sub-tr d-none">


                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:Objectives</span></td>

                                </tr>
                                <tr style="background-color:white" class="sub-tr d-none">


                                    <td><span class="label label-success" style="font-size:12px">All pages</span></td>
                                    <td><span class="label label-success" style="font-size:11px">All pages in the module can be visible</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="objective_all" value="objective_all" id="example-sw-custom-success10" class="custom-control-input" style="position: fixed;left: 600px" {{ $objective_all_pages || $enable_all ? 'checked' : ''}}>

                                            <label class="custom-control-label" for="example-sw-custom-success10"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:#ededed" class="sub-tr d-none">


                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:Environmental Aspects</span></td>

                                </tr>
                                <tr style="background-color:white" class="sub-tr d-none">


                                    <td><span class="label label-success" style="font-size:12px">All pages</span></td>
                                    <td><span class="label label-success" style="font-size:11px">All pages in the module can be visible</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="envi_all" value="envi_all" id="example-sw-custom-success11" class="custom-control-input" style="position: fixed;left: 600px" {{ $envi_all_pages || $enable_all ? 'checked' : ''}}>

                                            <label class="custom-control-label" for="example-sw-custom-success11"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="sub-tr d-none">


                                    <td><span class="label label-success" style="font-size:12px">Analysis</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To adjust the view of analysis page</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="envi_analysis" value="envi_analysis" id="example-sw-custom-success12" class="custom-control-input" style="position: fixed;left: 600px" {{ $envi_analysis_page || $enable_all ? 'checked' : ''}}>

                                            <label class="custom-control-label" for="example-sw-custom-success12"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:#ededed" class="sub-tr d-none">


                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:Opportunity</span></td>
                                </tr>
                                <tr style="background-color:white" class="sub-tr d-none">



                                    <td><span class="label label-success" style="font-size:12px">All pages</span></td>
                                    <td><span class="label label-success" style="font-size:11px">All pages in the module can be visible</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="opportunity_all" value="opportunity_all" id="example-sw-custom-success13" class="custom-control-input" style="position: fixed;left: 600px" {{ $opportunity_all_pages || $enable_all ? 'checked' : ''}}>

                                            <label class="custom-control-label" for="example-sw-custom-success13"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="sub-tr d-none">



                                    <td><span class="label label-success" style="font-size:12px">Analysis</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To adjust the view of analysis page</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="opportunity_analysis" value="opportunity_analysis" id="example-sw-custom-success14" class="custom-control-input" style="position: fixed;left: 600px" {{ $opportunity_analysis_page || $enable_all ? 'checked' : ''}}>

                                            <label class="custom-control-label" for="example-sw-custom-success14"></label>
                                        </div>
                                    </td>
                                </tr>

                                <tr style="background-color:#ededed" class="sub-tr d-none">


                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:HSE Observations</span></td>

                                </tr>
                                <tr style="background-color:white" class="sub-tr d-none">


                                    <td><span class="label label-success" style="font-size:12px">All pages</span></td>
                                    <td><span class="label label-success" style="font-size:11px">All pages in the module can be visible</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="hse_all" value="hse_all" id="example-sw-custom-success15" class="custom-control-input" style="position: fixed;left: 600px" {{ $hse_all_pages || $enable_all ? 'checked' : ''}}>

                                            <label class="custom-control-label" for="example-sw-custom-success15"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="sub-tr d-none">


                                    <td><span class="label label-success" style="font-size:12px">Analysis</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To adjust the view of analysis page</span></td>

                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="hse_analysis" value="hse_analysis" id="example-sw-custom-success16" class="custom-control-input" style="position: fixed;left: 600px" {{ $hse_analysis_page || $enable_all ? 'checked' : ''}}>

                                            <label class="custom-control-label" for="example-sw-custom-success16"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:#ededed" class="sub-tr d-none">


                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:NCR & Corrective Actions</span></td>
                                </tr>
                                <tr style="background-color:white" class="sub-tr d-none">


                                    <td><span class="label label-success" style="font-size:12px">All pages</span></td>
                                    <td><span class="label label-success" style="font-size:11px">All pages in the module can be visible</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="corrective_all" value="corrective_all" id="example-sw-custom-success17" class="custom-control-input" style="position: fixed;left: 600px" {{ $corrective_all_pages || $enable_all ? 'checked' : ''}}>

                                            <label class="custom-control-label" for="example-sw-custom-success17"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="sub-tr d-none">


                                    <td><span class="label label-success" style="font-size:12px">Analysis</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To adjust the view of analysis page</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="corrective_analysis" value="corrective_analysis" id="example-sw-custom-success18" class="custom-control-input" style="position: fixed;left: 600px" {{ $corrective_analysis_page || $enable_all ? 'checked' : ''}}>

                                            <label class="custom-control-label" for="example-sw-custom-success18"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:#ededed" class="sub-tr d-none">


                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:Health And Safety Risk</span></td>
                                </tr>
                                <tr style="background-color:white" class="sub-tr d-none">


                                    <td><span class="label label-success" style="font-size:12px">All pages</span></td>
                                    <td><span class="label label-success" style="font-size:11px">All pages in the module can be visible</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="hsr_all" value="hsr_all" id="example-sw-custom-success19" class="custom-control-input" style="position: fixed;left: 600px" {{ $hsr_all_pages || $enable_all ? 'checked' : ''}}>

                                            <label class="custom-control-label" for="example-sw-custom-success19"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:#ededed" class="sub-tr d-none">


                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:Document Management</span></td>
                                </tr>
                                <tr style="background-color:white" class="sub-tr d-none">


                                    <td><span class="label label-success" style="font-size:12px">All pages</span></td>
                                    <td><span class="label label-success" style="font-size:11px">All pages in the module can be visible</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="doc_all" value="doc_all" id="example-sw-custom-success20" class="custom-control-input" style="position: fixed;left: 600px" {{ $document_all_pages || $enable_all ? 'checked' : ''}}>

                                            <label class="custom-control-label" for="example-sw-custom-success20"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="sub-tr d-none">


                                    <td><span class="label label-success" style="font-size:12px">Analysis</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To adjust the view of analysis page</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="doc_analysis" value="doc_analysis" id="example-sw-custom-success21" class="custom-control-input" style="position: fixed;left: 600px" {{ $document_analysis_page || $enable_all ? 'checked' : ''}}>

                                            <label class="custom-control-label" for="example-sw-custom-success21"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:#ededed" class="sub-tr d-none">


                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:Audit Management</span></td>
                                </tr>
                                <tr style="background-color:white" class="sub-tr d-none">


                                    <td><span class="label label-success" style="font-size:12px">All pages</span></td>
                                    <td><span class="label label-success" style="font-size:11px">All pages in the module can be visible</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="audit_all" value="audit_all" id="example-sw-custom-success22" class="custom-control-input" style="position: fixed;left: 600px" {{ $audit_all_pages || $enable_all ? 'checked' : ''}}>

                                            <label class="custom-control-label" for="example-sw-custom-success22"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="sub-tr d-none">


                                    <td><span class="label label-success" style="font-size:12px">Analysis</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To adjust the view of analysis page</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="audit_analysis" value="audit_analysis" id="example-sw-custom-success23" class="custom-control-input" style="position: fixed;left: 600px" {{ $audit_analysis_page || $enable_all ? 'checked' : ''}}>

                                            <label class="custom-control-label" for="example-sw-custom-success23"></label>
                                        </div>
                                    </td>
                                </tr>

                                <tr style="background-color:#ededed" class="sub-tr d-none">


                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:Management Review</span></td>
                                </tr>
                                <tr style="background-color:white" class="sub-tr d-none">


                                    <td><span class="label label-success" style="font-size:12px">All pages</span></td>
                                    <td><span class="label label-success" style="font-size:11px">All pages in the module can be visible</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="management_all" value="management_all" id="example-sw-custom-success24" class="custom-control-input" style="position: fixed;left: 600px" {{ $management_all_pages || $enable_all ? 'checked' : ''}}>

                                            <label class="custom-control-label" for="example-sw-custom-success24"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="sub-tr d-none">


                                    <td><span class="label label-success" style="font-size:12px">Analysis</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To adjust the view of analysis page</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="management_analysis" value="management_analysis" id="example-sw-custom-success25" class="custom-control-input" style="position: fixed;left: 600px" {{ $management_analysis_page || $enable_all ? 'checked' : ''}}>

                                            <label class="custom-control-label" for="example-sw-custom-success25"></label>
                                        </div>
                                    </td>
                                </tr>

                                <tr style="background-color:#ededed" class="sub-tr d-none">


                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:Incident Report</span></td>
                                </tr>
                                <tr style="background-color:white" class="sub-tr d-none">


                                    <td><span class="label label-success" style="font-size:12px">All pages</span></td>
                                    <td><span class="label label-success" style="font-size:11px">All pages in the module can be visible</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="incident_all" value="incident_all" id="example-sw-custom-success26" class="custom-control-input" style="position: fixed;left: 600px" {{ $incident_all_pages || $enable_all ? 'checked' : ''}}>

                                            <label class="custom-control-label" for="example-sw-custom-success26"></label>
                                        </div>
                                    </td>
                                </tr>

                                <tr style="background-color:#ededed" class="sub-tr d-none">


                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:HR</span></td>
                                </tr>
                                <tr style="background-color:white" class="sub-tr d-none">


                                    <td><span class="label label-success" style="font-size:12px">All pages</span></td>
                                    <td><span class="label label-success" style="font-size:11px">All pages in the module can be visible</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="hr_all" value="hr_all" id="example-sw-custom-success27" class="custom-control-input" style="position: fixed;left: 600px" {{ $hr_all_pages || $enable_all ? 'checked' : ''}}>

                                            <label class="custom-control-label" for="example-sw-custom-success27"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:#ededed" class="sub-tr d-none">


                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:Vendor</span></td>

                                </tr>
                                <tr style="background-color:white" class="sub-tr d-none">


                                    <td><span class="label label-success" style="font-size:12px">All pages</span></td>
                                    <td><span class="label label-success" style="font-size:11px">All pages in the module can be visible</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="vendor_all" value="vendor_all" value="trainingcoordinator" id="example-sw-custom-success28" class="custom-control-input" style="position: fixed;left: 600px" {{ $vendor_all_pages || $enable_all ? 'checked' : ''}}>

                                            <label class="custom-control-label" for="example-sw-custom-success28"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="sub-tr d-none">


                                    <td><span class="label label-success" style="font-size:12px">Analysis</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To adjust the view of analysis page</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="vendor_analysis" value="vendor_analysis" id="example-sw-custom-success29" class="custom-control-input" style="position: fixed;left: 600px" {{ $vendor_analysis_page || $enable_all ? 'checked' : ''}}>

                                            <label class="custom-control-label" for="example-sw-custom-success29"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:#ededed" class="sub-tr d-none">


                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:Waste Management</span></td>

                                </tr>
                                <tr style="background-color:white" class="sub-tr d-none">


                                    <td><span class="label label-success" style="font-size:12px">All pages</span></td>
                                    <td><span class="label label-success" style="font-size:11px">All pages in the module can be visible</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="waste_all" value="waste_all" id="example-sw-custom-success30" class="custom-control-input" style="position: fixed;left: 600px" {{ $waste_all_pages || $enable_all ? 'checked' : ''}}>

                                            <label class="custom-control-label" for="example-sw-custom-success30"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="sub-tr d-none">


                                    <td><span class="label label-success" style="font-size:12px">Analysis</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To adjust the view of analysis page</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="waste_analysis" value="waste_analysis" id="example-sw-custom-success31" class="custom-control-input" style="position: fixed;left: 600px" {{ $waste_analysis_page || $enable_all ? 'checked' : ''}}>

                                            <label class="custom-control-label" for="example-sw-custom-success31"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:#ededed" class="sub-tr d-none">


                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:Calibration</span></td>

                                </tr>
                                <tr style="background-color:white" class="sub-tr d-none">


                                    <td><span class="label label-success" style="font-size:12px">All pages</span></td>
                                    <td><span class="label label-success" style="font-size:11px">All pages in the module can be visible</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="calibration_all" value="calibration_all" id="example-sw-custom-success32" class="custom-control-input" style="position: fixed; left: 600px" {{ $calibration_all_pages || $enable_all ? 'checked' : ''}}>

                                            <label class="custom-control-label" for="example-sw-custom-success32"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="sub-tr d-none">


                                    <td><span class="label label-success" style="font-size:12px">Analysis</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To adjust the view of analysis page</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="calibration_analysis" value="calibration_analysis" id="example-sw-custom-success33" class="custom-control-input" style="position: fixed; left: 600px" {{ $calibration_analysis_page || $enable_all ? 'checked' : ''}}>

                                            <label class="custom-control-label" for="example-sw-custom-success33"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:#ededed" class="sub-tr d-none">


                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:Near Miss</span></td>

                                </tr>
                                <tr style="background-color:white" class="sub-tr d-none">


                                    <td><span class="label label-success" style="font-size:12px">All pages</span></td>
                                    <td><span class="label label-success" style="font-size:11px">All pages in the module can be visible</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="nearmiss_all" value="nearmiss_all" id="example-sw-custom-success34" class="custom-control-input" style="position: absolute;" {{ $nearmiss_all_pages || $enable_all ? 'checked' : ''}}>

                                            <label class="custom-control-label" for="example-sw-custom-success34"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="sub-tr d-none">


                                    <td><span class="label label-success" style="font-size:12px">Analysis</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To adjust the view of analysis page</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="nearmiss_analysis" value="nearmiss_analysis" id="example-sw-custom-success35" class="custom-control-input" style="position: absolute;" {{ $nearmiss_analysis_page || $enable_all ? 'checked' : ''}}>

                                            <label class="custom-control-label" for="example-sw-custom-success35"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:#ededed" class="sub-tr d-none">


                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:Occupational Health</span></td>

                                </tr>
                                <tr style="background-color:white" class="sub-tr d-none">


                                    <td><span class="label label-success" style="font-size:12px">All pages</span></td>
                                    <td><span class="label label-success" style="font-size:11px">All pages in the module can be visible</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="occu_all" value="occu_all" id="example-sw-custom-success36" class="custom-control-input" style="position: fixed;left: 600px" {{ $occhealth_all_pages || $enable_all ? 'checked' : ''}}>

                                            <label class="custom-control-label" for="example-sw-custom-success36"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="sub-tr d-none">


                                    <td><span class="label label-success" style="font-size:12px">Analysis</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To adjust the view of analysis page</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="occu_analysis" value="occu_analysis" id="example-sw-custom-success37" class="custom-control-input" style="position: fixed;left: 600px" {{ $occhealth_all_pages || $enable_all ? 'checked' : '' }}>

                                            <label class="custom-control-label" for="example-sw-custom-success37"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:#ededed" class="sub-tr d-none">


                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:Maintenance</span></td>

                                </tr>
                                <tr style="background-color:white" class="sub-tr d-none">


                                    <td><span class="label label-success" style="font-size:12px">All pages</span></td>
                                    <td><span class="label label-success" style="font-size:11px">All pages in the module can be visible</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="maintenance_all" value="maintenance_all" id="example-sw-custom-success38" class="custom-control-input" style="position: fixed;left: 600px" {{ $maintenance_all_pages || $enable_all ? 'checked' : '' }}>

                                            <label class="custom-control-label" for="example-sw-custom-success38"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:#ededed" class="sub-tr d-none">


                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:e-Learning</span></td>
                                </tr>
                                <tr style="background-color:white" class="sub-tr d-none">


                                    <td><span class="label label-success" style="font-size:12px">All pages</span></td>
                                    <td><span class="label label-success" style="font-size:11px">All pages in the module can be visible</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="elearning_all" value="elearning_all" id="example-sw-custom-success39" class="custom-control-input" style="position: fixed;left: 600px" {{ $elearning_all_pages || $enable_all ? 'checked' : '' }}>

                                            <label class="custom-control-label" for="example-sw-custom-success39"></label>
                                        </div>
                                    </td>
                                </tr>
                                @endif

                            </table>
                            <div class="row justify-content-center">
                                <table>
                                    <tr style="background-color:white" class="text-center">
                                        <td>
                                            <div class="form-group">
                                                <input type="reset" style="font-size:12px" class="btn btn-primary" name="submit" value="Reset">
                                                <input type="submit" style="font-size:12px" class="btn btn-primary" name="submit" value="Update">
                                            </div>
                                        </td>
                                        {{-- <td>
                                            <div class="form-group">
                                                <input type="submit" style="font-size:12px" class="btn btn-primary" name="submit" value="Update">
                                            </div>
                                        </td> --}}
                                    </tr>

                                </table>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

</main>

@endsection
@section('js_after')
<script>
    function init() {
        if (!$('#all').is(':checked')) {
            $('.sub-tr').removeClass('d-none');
            $('.sub-tr').addClass('display-true');
        } else {
            $('.sub-tr').removeClass('display-true');
            $('.sub-tr').addClass('d-none');
        }
    }
    init();
    $('#all').on('click', function() {
        init();
    })

</script>
@endsection
