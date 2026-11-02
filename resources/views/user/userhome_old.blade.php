@extends('layouts.backend')
@section('content')
<!doctype html>
<html lang="en">

            <!-- Main Container -->
            <main id="main-container">


                  <!-- Page Content -->
                  <div class="content">
                    <!-- Square Themed Blocks -->
                    <!-- <h2 class="content-heading">Themed Square</h2> -->
                    <div class="row">
                    @if($model->auditmanagement==1)
                        <div class="col-md-6 col-xl-3">
                            <a class="block block-rounded block-themed" href="{{ url('/user/actionsystem/issues') }}"  style="width:225px;height:235px">
                                <div class="block-header">
                                    <h3 class="block-title">Audit Management</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option">
                                        <i class="far fa-2x fa-edit"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <p>Internal & External Audits, Management Review</p>
                                </div>
                           </a>
                        </div>
                    @endif
                    @if($model->riskmanagement==1)
                        <div class="col-md-6 col-xl-3">
                            <a class="block block-rounded block-themed" href="{{url('/user/risks/risk')}}" style="width:225px;height:235px">
                                <div class="block-header bg-primary-light">
                                    <h3 class="block-title">Enterprise Risk</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option">
                                             <i class="fa fa-2x fa-exclamation-triangle"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <p>Risk Identification, Assessment, Mitigation, Analysis-Dashboard</p>
                                </div>
                             </a>
                        </div>
                    @endif
                    @if($model->compliance==1)
                        <div class="col-md-6 col-xl-3">
                            <a class="block block-rounded block-themed"  href="{{ url('/user/compliance/register') }}" style="width:225px;height:235px">
                                <div class="block-header bg-primary-dark">
                                    <h3 class="block-title">Compliance Management</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option">
                                           <i class="fa fa-2x fa-gavel"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <p>Identification of Legal Requirements, Compliance Evaluation</p>
                                </div>
                             </a>
                        </div>
                    @endif
                    @if($model->documentmanagement==1)
                        <div class="col-md-6 col-xl-3">
                            <a class="block block-rounded block-themed" href="{{url('/user/doc/view')}}" style="width:225px;height:235px">
                                <div class="block-header bg-primary-darker">
                                    <h3 class="block-title">Document Management</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option">
                                                 <i class="fa fa-2x fa-file-upload"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <p>Master List of Documents, Document Change Request, External Documents</p>
                                </div>
                            </a>
                        </div>
                    @endif
                    @if($model->objectives==1)
                        <div class="col-md-6 col-xl-3">
                            <a class="block block-rounded block-themed" href="{{ url('/user/objectives/listofobjectives') }}" style="width:225px;height:235px">
                                <div class="block-header bg-success">
                                    <h3 class="block-title" style="height:56px">Objectives</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option">
                                              <i class="fa fa-2x fa-bullseye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <p>Objectives,Targets, Objective Performance Monitoring</p>
                                </div>
                           </a>
                        </div>
                    @endif
                    @if($model->opportunity==1)
                        <div class="col-md-6 col-xl-3">
                            <a class="block block-rounded block-themed" href="{{ url('/user/opportunity/register') }}" style="width:225px;height:235px">
                                <div class="block-header bg-info">
                                    <h3 class="block-title" style="height:56px">Opportunity</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option">
                                           <i class="fa fa-2x fa-briefcase"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <p>Opportunity Identification, Action Plan</p>
                                </div>
                          </a>
                        </div>
                    @endif
                    @if($model->correctiveaction==1)
                        <div class="col-md-6 col-xl-3">
                            <a class="block block-rounded block-themed" href="{{ url('/user/corrective/corrissues') }}" style="width:225px;height:235px">
                                <div class="block-header bg-warning">
                                    <h3 class="block-title">Corrective Action</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option">
                                           <i class="fa fa-2x fa-exclamation-circle"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <p>Corrective Actions register,Open/closed issues,Analysis</p>
                                </div>
                           </a>
                        </div>
                    @endif
                    @if($model->ohsrisk==1)
                        <div class="col-md-6 col-xl-3">
                            <a class="block block-rounded block-themed" href="{{ url('/user/ohsrisk/list') }}" style="width:225px;height:235px">
                                <div class="block-header bg-danger">
                                    <h3 class="block-title">Health and safety risk</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option">
                                            <i class="fa fa-2x fa-clinic-medical"></i>
                                        <!-- <i class="fa fa-2x fa-hand-holding-medical"></i> -->
                                        </button>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <p>Occupational Health & Safety Risk identification,Assessment,<br>Controls</p>
                                </div>
                           </a>
                        </div>
                    @endif
                    @if($model->enviaspects==1)
                        <div class="col-md-6 col-xl-3">
                             <a class="block block-rounded block-themed" href="{{ url('/user/enviaspects/listofaspects') }}" style="width:225px;height:235px">
                                <div class="block-header bg-amethyst">
                                    <h3 class="block-title">Environmental Aspects</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option">
                                              <i class="fa fa-2x fa-cannabis"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <p>Aspect and Impact Identifcation, Aspect Assessment, Controls</p>
                                </div>
                            </a>
                        </div>
                    @endif
                    @if($model->contextmanagement==1)
                        <div class="col-md-6 col-xl-3">
                            <a class="block block-rounded block-themed" href="{{ url('/user/context/issuereg') }}" style="width:225px;height:235px">
                                <div class="block-header bg-muted">
                                    <h3 class="block-title">Context Management</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option">
                                            <i class="si si-settings fa-2x"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <p>Issue Register,Interested Party Register, Change Log</p>
                                </div>
                             </a>
                        </div>
                    @endif
                    @if($model->complaintmanagement==1)
                        <div class="col-md-6 col-xl-3">
                            <a class="block block-rounded block-themed" href="{{ url('/user/complaint/register') }}" style="width:225px;height:235px">
                                <div class="block-header bg-gray-darker">
                                    <h3 class="block-title">Customer Complaint</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option">
                                             <i class="fa fa-2x fa-question"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <p>Customer list,My complaints,closed complaints</p>
                                </div>
                           </a>
                        </div>
                    @endif
                    @if($model->vendormanagement==1)
                        <div class="col-md-6 col-xl-3">
                            <a class="block block-rounded block-themed" href="{{ url('/user/vendor/register') }}" style="width:225px;height:235px">
                                <div class="block-header bg-smooth">
                                    <h3 class="block-title">Vendor Management</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option">
                                              <i class="fa fa-2x fa-people-carry"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <p>Vendor register ,Vendor evaluation </p>
                                </div>
                           </a>
                        </div>
                    @endif
                    @if($model->incidentreport==1)
                        <div class="col-md-6 col-xl-3">
                            <a class="block block-rounded block-themed" href="{{ url('/user/incidents/register') }}" style="width:225px;height:235px">
                                <div class="block-header bg-blue-darker">
                                    <h3 class="block-title">Incident Report</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option">
                                             <i class="fa fa-2x fa-car-crash"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <p> </p>
                                </div>
                           </a>
                        </div>
                    @endif
                    </div>
                    <!-- END Square Themed Blocks -->


                </div>
                <!-- END Page Content -->
            </main>
            <!-- END Main Container -->

            <!-- Footer -->

            <!-- Apps Modal -->

</html>
@endsection
