@extends('layouts.multiselect')
@section('content')
@include('shared.forms.risk-assets')




                <!-- Hero -->


                <!-- Page Content -->
                <div class="content risk-form-page risk-form-shell">
    @include('shared.forms.risk-page-header', ['formTitle' => 'Reopen risk', 'formDescription' => 'Reassess the risk and confirm why monitoring should resume.'])


                   <!-- Horizontal -->
                    <div class="block block-rounded">

                        <div class="block-content block-content-full">
                            <div class="row">
                                <div class="col-lg-10">

					<form class="risk-module-form mb-5" action="{{url('/user/risks/reopenrisk')}}" enctype="multipart/form-data"  method="post" files="true">
					{{ csrf_field() }}
					{{-- <div class="form-group">

					</div> --}}
					@if(count($errors) >0 )
						@foreach($errors->all() as $error)
						<div class='alert alert-warning'>
								{{ $error }}
						</div>
						@endforeach
					@endif

					@if(count($data)>0)
					@foreach($data as $dat)
                    {{-- <div class="col-lg-10"> --}}

								<div class="col-12 rf-section-heading rf-section-break"><h2>Risk context</h2><p>Confirm the reference information for this risk.</p></div>
<div class="form-group" style="display:inline-block;width:49%;">
							<label class="col-sm-12 col-form-label" for="Riskid"style="font-size: 13px;">Risk id</label>
							<div class="col-sm-12">
								<input type="text" class="form-control" id="Riskid" style="font-size: 13px;"  name="Riskid" value="{{$dat->grp_id}}" Readonly=""></div>
						</div>

						<div class="form-group" style="display:inline-block;width:49%;">
							<label class="col-sm-12 col-form-label"style="font-size: 13px;" for="Riskar">Risk area</label>
							<div class="col-sm-12">
							   <input type="text" class="form-control" id="Riskar"style="font-size: 13px;"   name="Riskar" value="{{$dat->department ? $dat->department->departmentname : '-'}}" Readonly="">

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
								<input type="text" class="form-control" id="riskname"  style="font-size: 13px;" name="riskname" value="{{$dat->riskname}}"Readonly="">
							</div>
						</div>
						@php
                             $riskowneremails = $dat->risk_owner ? explode(',',$dat->risk_owner): [];
                             $names = App\User::whereIn('email',$riskowneremails)->pluck('name')->toArray();
                             $names = implode(',',$names);
                        @endphp
                            <div class="form-group" style="display:inline-block;width:49%;">
								<label class="col-sm-12 col-form-label" for="Riskown"style="font-size: 13px;">Risk owner</label>
								<div class="col-sm-12">
									<input type="text" class="form-control" id="Riskown"  style="font-size: 13px;" name="Riskown" value="{{$names}}"Readonly="">

									{{-- {{ Form::text('Riskown',$dat->risk_owner,array('class'=>'form-control','style'=>'font-size: 13px','Readonly')) }} --}}
								</div>
							</div>
							<div class="form-group" style="display:inline-block;width:49%;">
								<label class="col-sm-12 col-form-label" style="font-size: 13px;"for="risk_response">Risk response strategy</label>
								<div class="col-sm-12">
									<select class="form-control js-select2" style="font-size: 13px;" id="risk_response" name = "risk_response" >
										<option selected="" disabled>Choose Strategy</option>
										@if(count($strategy)> 0 )
										@foreach($strategy as $value)
										<option value="{{$value->name}}" {{ $dat->risk_response == $value->name ? 'selected' : '' }}> {{ $value->name }}</option>
										@endforeach
										@endif
									</select>
								  </div>
							</div>
								{{-- <div class="form-group row">
									<label class="col-sm-4 col-form-label" for="riskgroup">Risk Response Strategy</label>
									<div class="col-sm-8 col-form-label">
										<div class="form-group"style="color:#666666">
										<select class="js-select2 form-control"id="risk_response"  name="risk_response" style="width: 100%;" >
											<option selected="" >{{$dat->risk_response}}</option>
											@if(count($strategy)> 0 )
												@foreach($strategy as $value)
													<option value=" {{$value->name}}"> {{ $value->name }}</option><br>
												@endforeach
											@endif
										</select>
										</div>
									</div>
								</div>    --}}
								<div class="form-group" style="display:inline-block;width:98.5%;">
									<label class="col-sm-12 col-form-label" for="mitigating_action"style="font-size: 13px;">Mitigating action</label>
									<div class="col-sm-12">
										<textarea type="text" class="form-control"style="font-size: 13px;" id="mitigating_action" name="mitigating_action" >{{$dat->mitigating_action}}</textarea>

									</div>
								</div>

								{{-- <div class="form-group row">
									<label  class="col-sm-4 col-form-label" for="riskgroup" >Risk Owner</label>
									<div class="col-sm-8 col-form-label">
										<div class="form-group"style="color:#666666">
											<select class="js-select2 form-control"id="riskowner" name = "riskowner" style="width: 100%;">
												<option selected="">
													@foreach($departments as $dep)
													@if( $dat->risk_owner == $dep->hod )
														{{ $dep->departmentname }}
													@endif
												@endforeach
											</option>
												@if(count($mn)> 0 )
													@foreach($mn as $value)
														<option value=" {{$value->hod}}"> {{ $value->departmentname }}</option></br>
													@endforeach
												@endif
											</select>
										</div>
									</div>
								</div>      --}}

								<div class="form-group" style="display:inline-block;width:49%;">
									<label class="col-sm-12 col-form-label"style="font-size: 13px;" for="impact">Impact</label>
									<div class="col-sm-12">
										<select class="form-control js-select2"style="font-size: 13px;"  id="impa" name = "impa" >
											<option value="High" {{ $dat->impa == "High" ? 'selected' : '' }}>High</option>
											<option value="Moderate" {{ $dat->impa == "Moderate" ? 'selected' : '' }}>Moderate</option>
											<option value="Low" {{ $dat->impa == "Low" ? 'selected' : '' }}>Low</option>
										</select>
									</div>
								</div>

								<div class="form-group" style="display:inline-block;width:49%;">
									<label class="col-sm-12 col-form-label"style="font-size: 13px;" for="likelihood">Likelihood</label>
									<div class="col-sm-12">
										<select class="form-control js-select2" style="font-size: 13px;" id="like" name = "like" >
											<option value="High" {{ $dat->like == "High" ? 'selected' : '' }}>High</option>
											<option value="Moderate" {{ $dat->like == "Moderate" ? 'selected' : '' }}>Moderate</option>
											<option value="Low" {{ $dat->like == "Low" ? 'selected' : '' }}>Low</option>
										</select>
										{{-- <input type="hidden" value="{{auth::user()->email}}" name="post"> --}}
								</div>
							</div>
										<div class="form-group">
											<input type="hidden" name="posted" value="{{ $dat->Rid}}">
										</div>

										<div class="form-group" style="display:inline-block;width:98.5%;">
											<label class="col-sm-12 col-form-label" for="reasontoreopen"style="font-size: 13px;">Reason</label>
												<div class="col-sm-12">
													<textarea type="text" class="form-control"style="font-size: 13px;" id="reasontoreopen" name="reasontoreopen" ></textarea>

												{{-- {{ Form::textarea('reasontoreopen','',array('class'=>'form-control','placeholder'=>'Enter Reason for Reopening','style'=>'font-size: 13px','required'=>'','rows' => 3 )) }} --}}
										</div>
									</div>



									<div class="risk-form-actions">
							<button for="submittl" type="submit" onclick=" return confirm('Are you sure to reopen?')" class="btn btn-primary"style="font-size:13px;">Reopen</button>
							<button for="reset" type="reset" class="btn btn-primary" style="font-size:13px;">Reset </button>
							</div>



                    </div>
    @endforeach
    @endif
</form>
<!-- END Form Horizontal - Default Style -->

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




@endsection
