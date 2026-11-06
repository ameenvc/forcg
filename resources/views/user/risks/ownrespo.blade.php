@extends('layouts.backend')
@section('content')
@include('shared.forms.risk-assets')






                <!-- Page Content -->
                <div class="content risk-form-page risk-form-shell risk-module-page">
    @include('shared.forms.risk-page-header', ['formTitle' => 'Owner response', 'formDescription' => 'Review the risk and record the response from the accountable owner.'])


                   <!-- Horizontal -->
                    <div class="block block-rounded">

                        <div class="block-content block-content-full">
                            <div class="row">

                                <div class="col-lg-10">


				<form class="risk-module-form mb-5" action="{{url('/user/risks/ownrespo')}}" enctype="multipart/form-data"  method="post" files="true">
         		{{ csrf_field() }}

					@if(count($data)>0)
					@foreach($data as $dat)

					{{-- <div class="col-lg-10"> --}}
						<div class="col-12 rf-section-heading rf-section-break"><h2>Risk context</h2><p>Confirm the reference information for this risk.</p></div>
<div class="form-group" style="display:inline-block;width:49%;">
					<label class="col-sm-12 col-form-label"style="font-size: 13px;" for="Riskid">Risk id</label>
					<div class="col-sm-12">
						<input type="text" class="form-control"style="font-size: 13px;" id="Riskid"   name="Riskid" value="{{$dat->grp_id}}" Readonly="">
					</div>
				</div>

				<div class="form-group" style="display:inline-block;width:49%;">
					<label class="col-sm-12 col-form-label"style="font-size: 13px;" for="Riskar">Risk area</label>
					<div class="col-sm-12">
						<input type="text" class="form-control" id="Riskar" style="font-size: 13px;"  name="Riskar" value="{{$dat->department ? $dat->department->departmentname : '-'}}" Readonly="">
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
<div class="form-group" style="display:inline-block;width:98.5%;">
					<label class="col-sm-12 col-form-label" for="riskdec"style="font-size: 13px;">Risk description</label>
					<div class="col-sm-12">
						<textarea type="text" class="form-control"style="font-size: 13px;" id="riskdec" name="riskdec" >{{$dat->riskdec}}</textarea>
					</div>
				</div>

				<div class="form-group" style="display:inline-block;width:98.5%;">
					<label class="col-sm-12 col-form-label"style="font-size: 13px;" for="riskdri">Risk drivers</label>
					<div class="col-sm-12">
						<textarea type="text" class="form-control"style="font-size: 13px;" id="riskdri" name="riskdri" >{{$dat->riskdri}}</textarea>
                                        </div>
				</div>
				<div class="col-12 rf-section-heading rf-section-break"><h2>Assessment & responsibility</h2><p>Confirm the response, assessment, and responsible people.</p></div>
<div class="form-group" style="display:inline-block;width:98.5%;">
					<label class="col-sm-12 col-form-label"style="font-size: 13px;" for="riskscr">Risk source</label>
					<div class="col-sm-12">

						<select class="form-control"style="font-size: 13px;"  id="riskscr" name = "riskscr">
							<option selected="">{{$dat->riskscr}}</option>
						   <option value="Internal">Internal</option><br>
							<option value="External">External</option><br>
							<option value="Internal/External">Internal/External</option><br>
						</select>
					   </div>
				</div>
				<div class="form-group" style="display:inline-block;width:98.5%;">
					<label class="col-sm-12 col-form-label"style="font-size: 13px;" for="mitigating_action">Mitigating action</label>
					<div class="col-sm-12">
						<textarea type="text" class="form-control"style="font-size: 13px;" id="mitigating_action" name="mitigating_action" >{{$dat->mitigating_action}}</textarea>
                                        {{-- {{ Form::textarea('mitigating_action',$dat->mitigating_action,array('class'=>'form-control','style'=>'font-size: 13px','rows' => 3,'placeholder'=>'Enter Mitigating Action' )) }} --}}
					</div>
				</div>
									<div class="form-group" style="display:inline-block;width:98.5%;">
										<label class="col-sm-12 col-form-label"style="font-size: 13px;" for="remarks">Remarks</label>
														<div class="col-sm-12">
															{{-- <input type="text"style="font-size: 13px;" class="form-control" id="remarks"   name="remarks" value="{{$dat->remarks}}"> --}}
															<textarea type="text" class="form-control"style="font-size: 13px;" id="remarks" name="remarks" >{{$dat->remarks}}</textarea>

										{{-- {{ Form::textarea('remarks',$dat->remarks,array('class'=>'form-control','style'=>'font-size: 13px','placeholder'=>'Enter  Remarks Here......','required'=>'true','rows' =>3 )) }} --}}
									</div>
								</div>

								<div class="form-group">
								<input type="hidden" name="posted" value="{{ $dat->Rid}}">
                                </div>
								<div class="risk-form-actions">
										<button type="submit" class="btn btn-primary"style="font-size: 13px;margin-left:87%;">Update</button>

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
