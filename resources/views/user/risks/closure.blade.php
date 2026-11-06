@extends('layouts.multiselect')
@section('content')
@include('shared.forms.risk-assets')






                <!-- Page Content -->
                <div class="content risk-form-page risk-form-shell risk-module-page">
    @include('shared.forms.risk-page-header', ['formTitle' => 'Risk closure review', 'formDescription' => 'Review the final assessment and identify whether further action is required.'])


                   <!-- Horizontal -->
                    <div class="block block-rounded">

                        <div class="block-content block-content-full">
                            <div class="row">

                                <div class="col-lg-10">
			<form class="risk-module-form mb-5" action="{{url('/user/risks/closure')}}" method="post">
				 {{ csrf_field() }}

				@if(count($data)>0)
              		@foreach($data as $dat)

                      {{-- <div class="col-lg-10"> --}}
                        <div class="col-12 rf-section-heading rf-section-break"><h2>Risk context</h2><p>Confirm the reference information for this risk.</p></div>
<div class="form-group" style="display:inline-block;width:49%;">
                    <label class="col-sm-12 col-form-label"style="font-size: 13px;" for="Riskid">Risk Id</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control"style="font-size: 13px;" id="Riskid"   name="Riskid" value="{{$dat->grp_id}}" Readonly="">
                    </div>
                </div>

                {{-- <div class="form-group" style="display:inline-block;width:49%;">
                    <label class="col-sm-12 col-form-label"style="font-size: 13px;" for="Riskar">Risk area</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" style="font-size: 13px;"id="Riskar"   name="Riskar" value="{{$dat->department ? $dat->department->departmentname : '-'}}" Readonly="">
                    </div>
                </div> --}}
                <div class="form-group" style="display:inline-block;width:98.5%;">
                    <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="Riskar">Risk area</label>
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="Riskar" style="font-size: 13px;" name="Riskar" value="{{$dat->department ? $dat->department->departmentname : '-'}}" Readonly="">
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="division" style="font-size: 13px;" value="{{$dat->division ? $dat->division->name : '-'}}" Readonly="">
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="area" style="font-size: 13px;" value="{{$dat->area ? $dat->area->name : '-'}}" Readonly="">
                            </div>
                        </div>
                    </div>

                </div>


                <div class="form-group" style="display:inline-block;width:49%;">
                    <label class="col-sm-12 col-form-label"style="font-size: 13px;" for="Riskgp">Risk group</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="Riskgp"  style="font-size: 13px;" name="Riskgp" value="{{$dat->riskgroup}}" Readonly="">
                    </div>
                </div>
                <div class="form-group" style="display:inline-block;width:49%;">
                    <label class="col-sm-12 col-form-label"style="font-size: 13px;" for="riskname">Risk name</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="riskname" style="font-size: 13px;"  name="riskname" value="{{$dat->riskname}}"Readonly="">
                    </div>
                </div>

                <div class="col-12 rf-section-heading rf-section-break"><h2>Risk details</h2><p>Review the risk statement, drivers, and existing controls.</p></div>
<div class="form-group" style="display:inline-block;width:49%;">
                    <label class="col-sm-12 col-form-label"style="font-size: 13px;" for="riskdec">Risk description</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="riskdec" style="font-size: 13px;"  name="riskdec" value="{{$dat->riskdec}}"Readonly="" >
                    </div>
                </div>
                <div class="form-group" style="display:inline-block;width:49%;">
                    <label class="col-sm-12 col-form-label" style="font-size: 13px;"for="riskdri">Risk drivers</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="riskdri"  style="font-size: 13px;" name="riskdri" value="{{$dat->riskdri}}"Readonly="">
                    </div>
                </div>
                <div class="col-12 rf-section-heading rf-section-break"><h2>Assessment & responsibility</h2><p>Confirm the response, assessment, and responsible people.</p></div>
<div class="form-group" style="display:inline-block;width:49%;">
                    <label class="col-sm-12 col-form-label"style="font-size: 13px;" for="riskscr">Risk source</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="riskscr" style="font-size: 13px;"  name="riskscr" value="{{$dat->riskscr}}"Readonly="">

                    </div>
                </div>
                <div class="form-group" style="display:inline-block;width:49%;">
                    <label class="col-sm-12 col-form-label"style="font-size: 13px;" for="Riskrs">Risk response strategy</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="riskscr"style="font-size: 13px;"   name="riskscr" value="{{$dat->risk_response}}"Readonly="">
                    </div>
                </div>
                <div class="form-group" style="display:inline-block;width:49%;">
                    <label class="col-sm-12 col-form-label"style="font-size: 13px;" for="mitigating_action">Mitigating action</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="mitigating_action" style="font-size: 13px;"  name="mitigating_action" value="{{$dat->mitigating_action}}"Readonly="">
                        {{-- {{ Form::textarea('mitigating_action',$dat->mitigating_action,array('class'=>'form-control','style'=>'font-size: 13px','rows' => 3,'placeholder'=>'Enter Mitigating Action' )) }} --}}
                    </div>
                </div>
                @php
                        $riskowneremails = $dat->risk_owner ? explode(',',$dat->risk_owner): [];
                        $names = App\User::whereIn('email',$riskowneremails)->pluck('name')->toArray();
                        $names = implode(',',$names);
                @endphp
                <div class="form-group" style="display:inline-block;width:49%;">
                    <label class="col-sm-12 col-form-label"style="font-size: 13px;" for="Riskown">Risk owner</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control"style="font-size: 13px;" id="riskscr"   name="riskscr" value="{{$names}}"Readonly="">
                        {{-- {{ Form::text('Riskown',$dat->risk_owner,array('class'=>'form-control','style'=>'font-size: 13px','Readonly')) }} --}}
                    </div>
                </div>
                <div class="form-group" style="display:inline-block;width:49%;">
                    <label class="col-sm-12 col-form-label"style="font-size: 13px;" for="impa">Impact</label>
                    <div class="col-sm-12">
                        <select class="form-control js-select2" style="font-size: 13px;" id="impa" name = "impa" >
                            <option value="High" {{ $dat->impa == "High" ? 'selected' : '' }}>High</option>
                            <option value="Moderate" {{ $dat->impa == "Moderate" ? 'selected' : '' }}>Moderate</option>
                            <option value="Low" {{ $dat->impa == "Low" ? 'selected' : '' }}>Low</option>
                        </select>
                    </div>
                </div>

                                                                <div class="form-group" style="display:inline-block;width:49%;">
                                                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;"for="like">Likelihood</label>
                                                                    <div class="col-sm-12">
                                                                        <select class="form-control js-select2" style="font-size: 13px;" id="like" name = "like" >
                                                                            <option value="High" {{ $dat->like == "High" ? 'selected' : '' }}>High</option>
                                                                            <option value="Moderate" {{ $dat->like == "Moderate" ? 'selected' : '' }}>Moderate</option>
                                                                            <option value="Low" {{ $dat->like == "Low" ? 'selected' : '' }}>Low</option>
                                                                        </select>
                                                                        <input type="hidden" value="{{auth::user()->email}}" name="post">
                                                                        <input type="hidden" value="{{auth::user()->email}}" name="review">
                                                                    </div>
                                                                </div>

			<div class="form-group">
								<input type="hidden" name="Rid" value="{{ $dat->Rid}}">
								</div>




                                <div class="risk-form-actions">
        <button type="submit" class="btn btn-primary"style="font-size: 13px;margin-left:69.5%;">Need More Action</button>
        <a href="{{ url('/user/risks/closed?val='.$Rid)}}"style="font-size: 13px;" class="btn btn-primary">Close Risk</a>


</div>


        </div>
    </div>
@endforeach
@endif
</form>
</div>
</div>
</div>
</div>
<!-- END Horizontal -->



<!-- Apps Modal -->
<!-- Opens from the modal toggle button in the header -->
<div class="risk-module-form modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
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
     {{-- <div class="col-6">
                                        <!-- CRM -->
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/risks/addrisk">
                                            <div class="block-content text-center">
                                                <i class="fas fa-plus-circle fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Add Risk
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END CRM -->
                                    </div> --}}
                                    <div class="col-6">
                                        <!-- Products -->
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/riskmgmt">
                                            <div class="block-content text-center">
                                                <i class="far fa-list-alt fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Register
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Products -->
                                    </div>
                                    <div class="col-6">
                                        <!-- Sales -->
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/risks/closedrisks">
                                            <div class="block-content text-center">
                                                <i class="fas fa-check-circle fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Closed Risks
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Sales -->
                                    </div>
                                    <div class="col-6">
                                        <!-- Payments -->
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/risks/analysis">
                                            <div class="block-content text-center">
                                                <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Analysis
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Payments -->
                                    </div>
</div>
</div>
</div>
</div>
</div>
</div>
<!-- END Apps Modal -->




@endsection
