@extends('layouts.multiselect')
@section('content')

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">

            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">Incident Report</li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="">Closure Review</a>
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
    @if(Session::has('msg'))
    <div class="alert alert-primary">
        <a class="close" data-dismiss="alert">×</a>
        {!!Session::get('msg')!!}
    </div>
    @endif
    <!-- Horizontal -->
    <div class="block block-rounded" style="box-shadow: 0px 0px 15px;background-color: #ffffff;">
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-9">
                    <!-- Form Horizontal - Default Style -->
                    <?php $f=0; ?>
                    <form class="mb-5" action="{{route('incident.confirmclosure')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="rid" style="font-size: 13px;">Report ID</label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" class="form-control" id="rid" name="rid" value="{{$incident->report_no}}" readonly>
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="addedby" style="font-size: 13px;">Reported By</label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" class="form-control" id="addedby" name="addedby" value="{{$incident->addedby}}" readonly>
                                </div>
                            </div>
                        </div>
                        @php
                        $today=Carbon\Carbon::now()->toDateTimeString();
                        $today=Carbon\Carbon::parse($today);
                        @endphp
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="addeddate" style="font-size: 13px;">Reporting Date</label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" class="form-control" id="addeddate" name="addeddate" value="{{$incident->addeddate}}" readonly>
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">Department</label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" class="form-control" name="department" value="{{$reportedby_depname}}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                              @if($incident->division)
                              <div class="form-group col-sm-6">
                                  <label class="col-sm-12 col-form-label" style="font-size:13px;" for="division">Division</label>
                                  <div class="col-sm-12">
                                      <div class="form-group" style="color:#666666">
                                          <input type="text" class="form-control" style="font-size: 13px;" value="{{$incident->division->name}}" readonly>
                                      </div>
                                  </div>
                              </div>
                              @endif
                              @if($incident->subdiv)
                              <div class="form-group col-sm-6">
                                  <label class="col-sm-12 col-form-label" style="font-size:13px;" for="department">Subdivision</label>
                                  <div class="col-sm-12">
                                      <div class="form-group" style="color:#666666">
                                          <input type="text" class="form-control" style="font-size: 13px;" value="{{$incident->subdiv->name}}" readonly>
                                      </div>
                                  </div>
                              </div>
                              @endif
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" for="area" style="font-size: 13px;">Area <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <textarea type="text" required style="font-size: 13px;" class="form-control" id="area" name="area" readonly>{{$incident->area}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="priority" style="font-size: 13px;">Incident Type <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" value="{{$incident->incident_type}}" required class="form-control" id="type" name="type" readonly>
                                </div>
                            </div>
                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $('#sel_priority').select2({});
                                });

                            </script>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;">Any Person Injured / Suffered</label>
                                <div class="col-sm-12">
                                    @if($incident->any_person_injured=='yes')
                                    <input style="font-size: 13px;" type="text" value="yes" id="person_injured" name="person_injured" value="Yes" class="form-control" readonly>
                                    @elseif($incident->any_person_injured=='no')
                                    <input style="font-size: 13px;" type="text" value="no" id="person_injured1" name="person_injured" value="No" class="form-control" readonly />
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @if($incident->any_person_injured=='yes')
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="person" style="font-size: 13px;">Name of the injured / suffered person</label>
                                @if($incident->injuredperson=='other')
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" readonly class="form-control" id="injempname_oth" name="injempname_oth" value="{{$incident->injured_person_oth_name}}">
                                </div>
                                <br />
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" readonly class="form-control" id="injempid_oth" name="injempid_oth" value="{{$incident->injured_person_oth_empid}}">
                                </div>
                                <br />
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" readonly class="form-control" id="injposition_oth" name="injposition_oth" value="{{$incident->injured_person_oth_position}}">
                                </div>
                                <br />
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" readonly class="form-control" id="injdept_oth" name="injdept_oth" value="{{$injured_deptname}}">
                                </div>
                                @else
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" readonly class="form-control" id="injper" name="injper" value="{{$incident->injuredperson}}">
                                </div>
                                <br />
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" readonly class="form-control" id="injempid" name="injempid" value="{{$incident->empid}}">
                                </div>
                                <br />
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" readonly class="form-control" id="injposition" name="injposition" value="{{$incident->injured_person_position}}">
                                </div>
                                <br />
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" readonly class="form-control" id="injdept" name="injdept" value="{{$injured_deptname}}">
                                </div>
                                @endif
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="hospital" style="font-size: 13px;">Name of Hospital Injured / Suffered Person attended/ Admitted <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" value="{{$incident->hospital}}" required class="form-control" id="hospital" name="hospital" readonly>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="riskdec" style="font-size: 13px;">Date and Time of Incident</label>
                                <div class="col-sm-12">
                                    <input type="text" class="js-datepicker form-control" id="date" name="incidentdate" data-week-start="1" data-autoclose="true" value="{{$incident->incidentdate}}" data-date-format="dd/mm/yy" placeholder="dd/mm/yy" style="font-size: 13px;" readonly>
                                </div>
                                <div class="col-sm-12">
                                    <input type="time" class="form-control" name="time" style="font-size: 13px;" placeholder="Time" value="{{$incident->time}}" readonly>
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="place" style="font-size: 13px;">Place of the Incident</label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" class="form-control" id="place" name="place" readonly value="{{$incident->incidentplace}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="employer" style="font-size: 13px;">Employer of the injured / suffered person (state if labour supply) <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" required style="font-size: 13px;" class="form-control" id="employer" name="employer" readonly value="{{$incident->emplyoer}}"></textarea>
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="trade" style="font-size: 13px;">Trade or occupation <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" required style="font-size: 13px;" class="form-control" id="trade" name="trade" readonly value="{{$incident->trade_or_occupation}}"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="desc" style="font-size: 13px;">Description <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <textarea type="text" required style="font-size: 13px;" class="form-control" id="desc" name="desc" readonly>{{$incident->incidentdesc}}</textarea>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="action" style="font-size: 13px;">Action Taken <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <textarea type="text" required style="font-size: 13px;" class="form-control" id="action" name="action" readonly>{{$incident->actions}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="local" style="font-size: 13px;">Reported under local regulation</label>
                                <div class="col-md-12">
                                    <input type="text" style="font-size: 13px;" class="form-control" id="local_regulation" name="local_regulation" readonly value="{{$incident->local_regulation}}">
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">Evaluation of control measures <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" required style="font-size: 13px;" class="form-control" id="eval" name="eval" readonly value="{{$incident->eval}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">Public reaction <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" required style="font-size: 13px;" class="form-control" id="pubrec" name="pubrec" readonly value="{{$incident->pubrec}}">
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">Probability of occurrence <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" required style="font-size: 13px;" class="form-control" id="probablity" name="probablity" readonly value="{{$incident->probablity}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">Severity in terms of financial loss <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" required style="font-size: 13px;" class="form-control" id="finloss" name="finloss" readonly value="{{$incident->finloss}}">
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">Effect on community <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" required style="font-size: 13px;" class="form-control" id="efcom" name="efcom" readonly value="{{$incident->efcom}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">Severity in terms of human trauma <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" required style="font-size: 13px;" class="form-control" id="trauma" name="trauma" readonly value="{{$incident->humtur}}">
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">Potential For Loss <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" required style="font-size: 13px;" class="form-control" id="potloss" name="potloss" readonly value="{{$incident->potloss}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">Severity of potential loss <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" required style="font-size: 13px;" class="form-control" id="sevpotloss" name="sevpotloss" readonly value="{{$incident->sevpotloss}}">
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">Probability of reoccurrence <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" required style="font-size: 13px;" class="form-control" id="porbocc" name="porbocc" readonly value="{{$incident->porbocc}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">Is this incident requires further investigation <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" required style="font-size: 13px;" class="form-control" id="further_investigate" name="further_investigate" readonly value="{{ucfirst($incident->further_investigate)}}">
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="main_incident_id" id ="main_incident_id" value="{{$incident->id}}"/>
                        <div class="row">
                        <div class="form-group col-sm-12 text-right">
                            <button type="submit" class="btn btn-success" style="font-size:13px;">Accept</button>

                            <button type="button" class="btn btn-danger" style="font-size:13px;" onclick="getincidentdetails('{{$incident->id}}')" data-toggle="modal" data-id="" data-target="#addinvestigatorModal">
                                Reject
                            </button>
                        </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- add investigator starts-->
<div class="modal fade" id="addinvestigatorModal" tabindex="-1" role="dialog" aria-labelledby="addinvestigatorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addinvestigatorModalLabel">Add Investigators</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <form id="add_investigation_ajax" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="alert alert-success" id="successinvestigatoradded" style="display:none">
                    Investigators added successfully 
                </div>

                <div class="alert alert-danger" id="dangerinvestigatoradded" style="display:none"></div>
                <ul class="alert alert-danger" id="investigatornotadded" style="display:none"></ul>
                    
                <div class="form-group">
                    <label for="teamleader" class="col-form-label" style="font-size:12px;">Team Leader<span class="text-danger">*</span></label>
                        @if(count($users)>0)
                        <select class="form-control" name="leader" id="leader" style="font-size: 13px;" required>
                            <option value="">Select Team Leader</option>
                            @foreach($users as $user)
                            <option value="{{$user->email}}">{{$user->name}}</option>s
                            @endforeach
                        </select>
                        @endif
                </div>
                <div class="form-group">
                    <label for="teammembers" class="col-form-label" style="font-size:12px;">Team Members<span class="text-danger">*</span></label>             
                        @if(count($users)>0)
                        <select class="js-select2 form-control col-sm-8" name="members[]" id="members" required style="font-size:13px;width:100%;" multiple data-placeholder="Select Team members">
                            @foreach($users as $user)
                            <option value="{{$user->email}}">{{$user->name}}</option>
                            @endforeach
                        </select>
                        @endif                     
                </div>                
                <div class="form-group">
                    <label for="targetdate" class="col-form-label" style="font-size:12px;">Target date to complete investigation<span class="text-danger">*</span></label>
                    <input type="date" class="js-datepicker form-control col-sm-4" id="targetdate" name="targetdate" data-week-start="1" 
                        data-autoclose="true" value="{{$incident->incidentdate}}" data-date-format="dd/mm/yy" 
                        placeholder="dd/mm/yy" style="font-size: 13px;" required>
                </div>
            <input type="hidden" name="incident_id" id ="incident_id" value=""/>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                <button type="submit" class="btn btn-primary" style="font-size:12px">Submit</button>
            </div>
        </form>
        </div>
    </div>
</div>

<!-- add investigator ends-->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>  
    let CSRF_TOKEN = "";
    window.onload = function() {
        CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    }

    function getincidentdetails(id){
        $('#incident_id').val(id);
    }
    /**-------------add investigators starts---------------- */
   $(document).ready(function() {
                $('#add_investigation_ajax').submit(function(event) {
                    event.preventDefault();
                    var formData = $(this).serialize();
                    $.ajax({
                        type: 'POST',
                        url: '{{ route("incident.addinvestigatorsajax") }}',
                        data: formData
                        , _token: CSRF_TOKEN
                        ,success: function(response) {
                            console.log(response.status);
                            if(response.status == 200)
                            {
                                $('#investigatornotadded').hide();
                                $('#dangerinvestigatoradded').hide();
                                $('#successinvestigatoradded').css('display', 'block');
                                setTimeout(function() {
                                    window.location.href = "{{route('incidentregister')}}"
                                }, 1000);
                            }
                        },                                    
                        error: function(err) { 
                            if(err.status === 500) {
                                $('#investigatornotadded').hide();
                                $('#dangerinvestigatoradded').css('display', 'block');
                            }
                            else if (err.status === 422) {
                                var error_text='';                               
                                $.each(err.responseJSON.errors, function(i) {
                                $.each(err.responseJSON.errors[i], function(key, val) {
                                    error_text += '<li style="font-size:12px;">' + val + '</li>';
                              });
                            });                               
                                $('#investigatornotadded').html(error_text);
                                $('#dangerinvestigatoradded').hide();
                                $('#investigatornotadded').css('display', 'block');
                            }                            
                        }
                        // error: function(xhr, status, error) {
                        //     console.error(xhr.responseText);
                        // }
                    });
                });
           });

   /**-------------add investigators ends---------------- */ 
</script> 

<!-- Apps Modal -->
<!-- Opens from the modal toggle button in the header -->
<div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
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
                            <a class="block block-rounded block-link-shadow bg-body" href="/user/incidents/register">
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
                            <a class="block block-rounded block-link-shadow bg-body" href="/user/incidents/closed/incidents">
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

                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/incident/correactions">
                                <div class="block-content text-center">
                                    <i class="fas fa-clone fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        My Open Corrective Actions
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
<!-- END Page Container -->
    @endsection
