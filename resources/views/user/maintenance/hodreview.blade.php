@extends('layouts.backend')
@section('content')
<!doctype html>
<html lang="en">
  
                <!-- Hero -->
                <div class="bg-body-light">
                    <div class="content content-full">
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
                           
                            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-alt">
                                    <li class="breadcrumb-item">Maintenance Management</li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="">HoD Review</a>
                                    </li>
									<li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="/user/maintenancemgmt">Back</a>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <!-- END Hero -->

                <!-- Page Content -->
                <div class="content">
                  

                   <!-- Horizontal -->
                    <div class="block block-rounded">
                       
                        <div class="block-content block-content-full">
                            <div class="row">
                                
                                <div class="col-lg-10">
			<form class="mb-5" action="{{url('/user/maintenance/hodreview')}}" method="post">
				         		{{ csrf_field() }}
							@if(count($data)>0)
				              @foreach($data as $dat)
                            <div class="form-group row">
								<label class="col-sm-4 col-form-label"style="font-size: 13px;"  for="equid">Equipment id</label>
								<div class="col-sm-8">
                                    <input  type="text" style="font-size: 13px;" class="form-control" id="equid" name="equid"   value="{{$dat->equid}}" Readonly=''> 
                                </div>
							</div>

							<div class="form-group row">
								<label class="col-sm-4 col-form-label"style="font-size: 13px;"  for="department">Department</label>
								<div class="col-sm-8">
                                    <input  type="text" style="font-size:13px;"  class="form-control" id="department" name="department" Readonly=""  value="{{$dat->department}}" > 
                              
                                </div>
							</div>

							<div class="form-group row">
								<label class="col-sm-4 col-form-label"style="font-size: 13px;"  for="type">Equipment Type</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" id="type" style="font-size: 13px;"   name="type" value="{{$dat->type}}" Readonly="">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-4 col-form-label" style="font-size: 13px;" for="serial">Serial number</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" id="serial" style="font-size: 13px;"   name="serial" value="{{$dat->serial}}"Readonly="">
								</div>
							</div>
									
							<div class="form-group row">
								<label class="col-sm-4 col-form-label" style="font-size: 13px;" for="fault">Nature of fault</label>
								<div class="col-sm-8">
									<textarea type="text"  class="form-control"style="font-size: 13px;" id="fault" name="fault"Readonly="" >{{$dat->fault}}</textarea>
                                   </div>
							</div>
							
							
							<div class="form-group row">
								<label class="col-sm-4 col-form-label"style="font-size: 13px;"  for="severity">Severity</label>
								<div class="col-sm-8"> 
									<select class="form-control" style="font-size: 13px;"  id="severity" name = "severity" >
										<option selected="" >{{$dat->severity}}</option>
										<option value="High">High</option><br>
										<option value="Moderate">Moderate</option><br>
										<option value="Low">Low</option><br>
									</select>
								</div>
							</div>
																			
																			<div class="form-group row">
																				<label class="col-sm-4 col-form-label"style="font-size: 13px;"  for="priority">Priority</label>
																					<div class="col-sm-8"> 
																						<select class="form-control" style="font-size: 13px;"  id="priority" name = "priority" >
																						<option selected="">{{$dat->priority}}</option>
																						<option value="High">High</option><br>
																						<option value="Moderate">Moderate</option><br>
																						<option value="Low">Low</option><br>
																					</select>
																					
																					</div>
																			</div>
												
								<div class="form-group">
								<input type="hidden" name="posted" value="{{ $dat->id}}"> 
								</div>
								<div style="text-align:center;">
                                    <button type="submit" class="btn btn-primary"style="font-size: 13px;margin-left:75%;">Approve</button>
                                    <a href="{{url('user/maintenance/disapprove?val='.$id)}}"style="font-size: 13px;" class="btn btn-primary">Disapprove</a>
                                
								</div>
			
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
        <a class="block block-rounded block-link-shadow bg-body" href="/user/maintenancemgmt">
            <div class="block-content text-center" style="height:130px">
                <i class="far fa-list-alt fa-2x text-primary"></i>
                <p class="font-w600 font-size-sm mt-2 mb-3">
                Total Equipment List
                </p>
            </div>
        </a>
        <!-- END Products -->
    </div>
    <div class="col-6">
        <!-- Sales -->
        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/maintenance/pmfolder">
            <div class="block-content text-center"style="height:130px">
                <i class="fas fa-address-card fa-2x text-primary"></i>
                <p class="font-w600 font-size-sm mt-2 mb-3">
                  PM Plan and Report
                </p>
            </div>
        </a>
        <!-- END Sales -->
    </div>
    <div class="col-6">
        <!-- Payments -->
        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/maintenance/testfolder">
            <div class="block-content text-center" style="height:130px">
                <i class="fas fa-calendar-check fa-2x text-primary"></i>
                <p class="font-w600 font-size-sm mt-2 mb-3">
                   Test Plan
                </p>
            </div>
        </a>
        <!-- END Payments -->
    </div>
    <div class="col-6">
        <!-- Sales -->
        <a class="block block-rounded block-link-shadow bg-body mb-2" href="/user/maintenance/breakfolder">
            <div class="block-content text-center" style="height:130px">
                <i class="fas fa-check-circle fa-2x text-primary"></i>
                <p class="font-w600 font-size-sm mt-2 mb-3">
                   Breakdown Maintenance
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


</html>
@endsection
