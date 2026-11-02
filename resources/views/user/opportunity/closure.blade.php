@extends('layouts.multiselect')
@section('content')
<style>
    .breadcrumb>li+li:before {
        display: none !important;
    }
    .select2-results__options{
        font-size:12px !important;
    }
    .breadcrumb {
        font-size: 15px;
        padding: 0.75rem 1.25rem;
        background: #F6F7F9;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }
</style>
<!-- Page Content -->
<div class="content">
	<!-- Horizontal -->
	<div class="block block-rounded">
		<nav aria-label="breadcrumb" style="display: flex; justify-content: space-between; background: #f6f7f9;">
			<ol class="breadcrumb">
				<li class="breadcrumb-item font-w700">
					<a href="{{ route('opportunity.oppindex') }}" class="text-black">
						Opportunity
					</a>
				</li>
				<li class="breadcrumb-item" aria-current="page">
					Closure Review
				</li>
			</ol>
		</nav>
		<div class="block-content block-content-full">
			<div class="row">
				<div class="col-lg-12">
					<form id="closureform" class="mb-5" action="{{ route('opportunity.closreviewopp_post')}}" method="post" onsubmit="return validateChips()">
						{{ csrf_field() }}
						@if(count($data)>0)
						@foreach($data as $dat)
							<div class="row">
							<div class="form-group col-sm-4">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="reportedby">Reported By</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" style="font-size: 13px;" id="reportedby" name="reportedby" value="{{App\User::where('employeeid',$dat->postedby)->value('name')}}" readonly>
                                </div>
                            </div>
							<div class="form-group col-sm-4">
								<label class="col-sm-12 col-form-label"style="font-size:13px;" for="id">Opportunity ID</label>
								<div class="col-sm-12">
									<input type="text" class="form-control"style="font-size: 13px;" id="id"   name="id" value="{{$dat->dep_id}}" Readonly="">
								</div>
							</div>


							<div class="form-group col-sm-4">
								<label class="col-sm-12 col-form-label"style="font-size:13px;" for="oppname">Opportunity name</label>
								<div class="col-sm-12">
									<input type="text" class="form-control"style="font-size: 13px;" id="oppname" name="oppname" value="{{$dat->oppname}}"Readonly="">
								</div>
							</div>
							<div class="form-group col-sm-12">
								<label class="col-sm-12 col-form-label" style="font-size:13px;" for="oppdes">Opportunity description</label>
								<div class="col-sm-12">
									<textarea type="text" class="form-control" style="font-size: 13px;" readonly>{{$dat->oppdes}}</textarea>
								</div>
							</div>
							<div class="form-group col-sm-6">
								<label class="col-sm-12 col-form-label"style="font-size:13px;" for="ohs">Is this OHS related?</label>
								<div class="col-sm-12">
									<input type="text" class="form-control"style="font-size: 13px;" id="ohs"   name="ohs" value="{{$dat->ohs}}"Readonly="">
								</div>
							</div>
							<div class="form-group col-sm-6">
								<label class="col-sm-12 col-form-label"style="font-size:13px;" for="type">Type</label>
								<div class="col-sm-12">
									<input type="text" class="form-control"style="font-size: 13px;" id="type"   name="type" value="{{$type}}"Readonly="">
								</div>
							</div>
							<?php
								$dept = DB::table('departments')->where('id',$dat->department)->value('departmentname');
							?>
							<div class="form-group col-sm-4">
								<label class="col-sm-12 col-form-label"style="font-size:13px;" for="department">Responsible Department</label>
								<div class="col-sm-12">
									<input type="text" class="form-control"style="font-size: 13px;" id="department"   name="department" value="{{$dept}}" Readonly="">
								</div>
							</div>
							@if($dat->division)
							<div class="form-group col-sm-4">
								<label class="col-sm-12 col-form-label" style="font-size:13px;" for="division">Division</label>
								<div class="col-sm-12">
										<input type="text" class="form-control" style="font-size: 13px;" value="{{$dat->division->name}}" readonly>
								</div>
							</div>
							@endif
							@if($dat->area)
							<div class="form-group col-sm-4">
								<label class="col-sm-12 col-form-label" style="font-size:13px;" for="department">Sub Division</label>
								<div class="col-sm-12">
										<input type="text" class="form-control" style="font-size: 13px;" value="{{$dat->area->name}}" readonly>
								</div>
							</div>
							@endif
						</div>
						<div class="row">
							<div class="form-group col-sm-6">
								<label class="col-sm-12 col-form-label"style="font-size:13px;" for="resp">Responsible Person</label>
								<div class="col-sm-12">
									<input type="hidden" id="resp" name="resp" value="{{$dat->responsibility}}">
									<input type="text" class="form-control"style="font-size: 13px;" value="{{$dat->byresponsibility ? $dat->byresponsibility->name : '' }}"Readonly="">
								</div>
							</div>
							<div class="form-group col-sm-6">
								<label class="col-sm-12 col-form-label"style="font-size:13px;" for="date">Target Date</label>
								<div class="col-sm-12">
									<input type="text" class="form-control"style="font-size: 13px;" id="date"name="date" value="{{$dat->tardate}}"Readonly="">
								</div>
							</div>
							
						
							<div class="form-group col-sm-12" >
								<label class="col-sm-12 col-form-label"style="font-size:13px;" for="oppdes">Action</label>
								<div class="col-sm-12">
									<textarea type="text" class="form-control"style="font-size: 13px;" id="action" name="action" Readonly="">{{$dat->action}}</textarea>
								</div>
							</div>
							<div class="form-group col-sm-6">
								<label class="col-sm-12 col-form-label" style="font-size:13px;" for="likelihood">Likelihood<span class="text-danger">*</span></label>
								<div class="col-sm-12">
									<input type="hidden" name="likelihood" id="likelihood" value="">
									@if(count($hml)> 0 )
									@foreach($hml as $value)
									@php 
										$btn_class = '';
										$clicked_class = '';
										if($value->name == 'High'){
											$btn_class = 'btn-outline-danger';
										}elseif($value->name == 'Moderate'){
											$btn_class = 'btn-outline-warning';
										}elseif($value->name == 'Low'){
											$btn_class = 'btn-outline-success';
										}
										$clicked_class = $dat->likelihood == $value->name ? 'clicked' : '';
									@endphp
									<input type="button" class="btn btn-sm {{$btn_class}} {{$clicked_class}} likelihoodbtn" id="{{Str::slug($value->name,'_')}}" style="margin-right:2px;margin-bottom:10px;font-size:12px;" value="{{$value->name}}">
									@endforeach
									@endif
									<div id="likelihood-error" class="text-danger" style="display:none;font-size:12px;">Please select a likelihood.</div>
								</div>
							</div>

							<div class="form-group col-sm-6">
								<label class="col-sm-12 col-form-label" style="font-size:13px;" for="benefit">Benefit<span class="text-danger">*</span></label>
								<div class="col-sm-12">
									<input type="hidden" name="benefit" id="benefit" value="">
									@if(count($hml)> 0 )
									@foreach($hml as $value)
									@php 
										$btn_class = '';
										$clicked_class = '';
										if($value->name == 'High'){
											$btn_class = 'btn-outline-danger';
										}elseif($value->name == 'Moderate'){
											$btn_class = 'btn-outline-warning';
										}elseif($value->name == 'Low'){
											$btn_class = 'btn-outline-success';
										}
										$clicked_class = $dat->benefit == $value->name ? 'clickedbenefit' : '';
									@endphp
									<input type="button" class="btn btn-sm {{$btn_class}} {{$clicked_class}} benefitbtn" id="{{Str::slug($value->name.'b','_')}}" style="margin-right:2px;margin-bottom:10px;font-size:12px;" value="{{$value->name}}">
									@endforeach
									@endif
									<div id="benefit-error" class="text-danger" style="display:none;font-size:12px;">Please select a benefit.</div>
								</div>
							</div>
							
							<?php
								$docs=explode(',',$dat->file);
							?>
							<div class="form-group col-sm-6">
								<label class="col-sm-4 col-form-label" for="download"  style="font-size:13px;">Attached file</label>
								<div class="col-sm-12">
									<button type="button" class="btn btn-primary btn-small" style="font-size:12px;width: 155px;" data-toggle="modal" data-id="" data-target="#viewModal1"><strong>Attachments</strong> </button>
									<div id="viewModal1" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
									<div class="modal-dialog modal-lg">
											<div class="modal-content">
												<div class="modal-header">
													<h3 class="modal-title">Attachments</h3>
												</div>
												<table class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
													<div class="row justify-content-center">
														<thead>
															<tr class="table-secondary">
																<th>S.No</th>
																<th>Attachments</th>
																<th></th>
															</tr>
														</thead>
														<?php $sno=0; ?>

														@if( count($docs) > 0 )
															@foreach($docs as $value1)

															@if( $value1!="NULL" && $value1!="")
															<tbody>
																<tr>
																	<td style="font-size:10px; ;">{{ $sno=$sno+1}}</td>
																	<td style="font-size:15px;width: 100%;">{{ $value1}}</td>
																	<td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success"  href="{{asset("storage/opportunity/".$value1)}}" download="{{$value1}}" >Download</a></td>
																</tr>
															</tbody>
															@else
															<div class="row justify-content-center">
																<h2>No Files</h2>
															</div>
														@endif
															@endforeach
														@else
															<div class="row justify-content-center">
																<h2>No Files</h2>
															</div>
														@endif
													</div>
												</table>


											<div class="modal-footer">
												<button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
											</div>
										</div>
									</div>
									</div>
								</div>
							</div>
							<div class="form-group col-sm-6">
								<label class="col-sm-12 col-form-label"style="font-size:13px;" for="ndareason">Remarks</label>
								<div class="col-sm-12">
									<textarea type="text" class="form-control"style="font-size: 13px;" id="ndareason" name="ndareason" >{{$dat->ndareason}}</textarea>
								</div>
							</div>
							<input type="hidden" name="posted" value="{{ $dat->id}}">
							<div class="col-sm-12 text-right pr-4">
								<button type="submit" class="btn btn-outline-danger"style="font-size: 13px;" onclick="return confirm('Are you sure?')">Need More Action</button>
								<button type="button" style="font-size: 13px;" class="btn btn-primary" onclick="changeFormActionAndSubmit('{{ route('opportunity.closeopp', ['id' => $id]) }}')">Close Opportunity</button>
							</div>
							</div>
						@endforeach
						@endif
					</form>
				</div>
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
							<a class="block block-rounded block-link-shadow bg-body" href="{{ route('opportunity.oppindex') }}">
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
							<a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/opportunity/accomplished">
								<div class="block-content text-center">
									<i class="fas fa-check-circle fa-2x text-primary"></i>
									<p class="font-w600 font-size-sm mt-2 mb-3">
										Accomplished
									</p>
								</div>
							</a>
							<!-- END Sales -->
						</div>

						<div class="col-6">
							<!-- Payments -->
							<a class="block block-rounded block-link-shadow bg-body mb-0" href='/user/opportunity/analysis'>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
	function changeFormActionAndSubmit(newAction) {
		const form = document.getElementById('closureform');
		form.action = newAction;
		if(confirm('Are you sure?')) {
			form.submit();
		}else{
			form.action = "{{ route('opportunity.closreviewopp_post')}}";
		}
	}
    $('.likelihoodbtn').click(function(){
        var dval = $(this).val();
        $('#likelihood').val(dval);
        $('#likelihood-error').hide();
        if(dval=="High"){
            $('#high').addClass('btn-alt-danger').css('background-color','#f4cccc').css('border','none');
            $('#moderate').removeClass('btn-alt-warning').css('background-color','').css('border','');
            $('#low').removeClass('btn-alt-success').css('background-color','').css('border','');
        }else if(dval=="Moderate"){
            $('#moderate').addClass('btn-alt-warning').css('background-color','#fce5cd').css('border','none');
            $('#high').removeClass('btn-alt-danger').css('background-color','').css('border','');
            $('#low').removeClass('btn-alt-success').css('background-color','').css('border','');
        }else if(dval=="Low"){
            $('#low').addClass('btn-alt-success').css('background-color','#b6d7a8').css('border','none');
            $('#high').removeClass('btn-alt-danger').css('background-color','').css('border','');
            $('#moderate').removeClass('btn-alt-warning').css('background-color','').css('border','');
        }
    });
    $('.benefitbtn').click(function(){
        var dval = $(this).val();
        $('#benefit').val(dval);
        $('#benefit-error').hide();
        if(dval=="High"){
            $('#highb').addClass('btn-alt-danger').css('background-color','#f4cccc').css('border','none');
            $('#moderateb').removeClass('btn-alt-warning').css('background-color','').css('border','');
            $('#lowb').removeClass('btn-alt-success').css('background-color','').css('border','');
        }else if(dval=="Moderate"){
            $('#moderateb').addClass('btn-alt-warning').css('background-color','#fce5cd').css('border','none');
            $('#highb').removeClass('btn-alt-danger').css('background-color','').css('border','');
            $('#lowb').removeClass('btn-alt-success').css('background-color','').css('border','');
        }else if(dval=="Low"){
            $('#lowb').addClass('btn-alt-success').css('background-color','#b6d7a8').css('border','none');
            $('#highb').removeClass('btn-alt-danger').css('background-color','').css('border','');
            $('#moderateb').removeClass('btn-alt-warning').css('background-color','').css('border','');
        }
    });
    $('.clicked').first().trigger('click');
    $('.clickedbenefit').first().trigger('click');
    function validateChips() {
        if (!$('#likelihood').val()) {
            $('#likelihood-error').show();
            return false;
        }
        if (!$('#benefit').val()) {
            $('#benefit-error').show();
            return false;
        }
        return true;
    }
</script>
@endsection
