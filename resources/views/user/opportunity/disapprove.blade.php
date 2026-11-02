@extends('layouts.multiselect')
@section('content')
<!doctype html>
<html lang="en">
    
                <!-- Hero -->
                <div class="bg-body-light">
                    <div class="content content-full">
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
                            
                            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-alt">
                                    <li class="breadcrumb-item">Opportunity</li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="">Disapproving</a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="{{ route('opportunity.oppindex') }}">Back</a>
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
                                
                                <div class="col-lg-8">
<form action="{{route('opportunity.disapproveopp_post')}}" method="post" >
    {{ csrf_field() }}
    <div class="container">
              <input type="hidden" name="id" value="{{$id}}">
              <div class="form-group" style="display:inline-block;width:98.5%">
												<label class="col-sm-12 col-form-label" for="benefit"style="font-size:13px;">Reason For Disapproving</label>
												<div class="col-sm-12"> 
                                                <textarea rows="10"  class="form-control" name="reason" required "></textarea>
											</div>
									</div>
            <div class="form-group" style="display:inline-block;width:98.5%;">
												<label class="col-sm-12 col-form-label" for="benefit"style="font-size:13px;">Additional email notification</label>
												<div class="col-sm-12"> 
                                                    <select class="js-select2 form-control" id="example-select2-multiple" style="font-size:13px;" name="additional[]"  multiple >
														    @foreach($user as $u)
																<option value=" {{$u->email}}"> {{ $u->name }}</option></br>
															@endforeach
														
													</select>
											</div>
									</div>
                                    <div style="text-align:center;">
                <button type="submit" class="btn btn-primary"style="font-size:13px;margin-left:84%;">Submit</button>
               
            </div>  
            </div>
        </form>
                
    </div>
</div>
</div>
</div>

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
                                       <a class="block block-rounded block-link-shadow bg-body" href="{{ route('opportunity.oppindex') }}">
                                            <div class="block-content text-center">
                                                <i class="far fa-list-alt fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Register
                                                </p>
                                            </div>
                                        </a>
                                       </div>
                                       <div class="col-6">
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/{{ route('opportunity.oppaccomplished') }}">
                                            <div class="block-content text-center">
                                                <i class="fas fa-check-circle fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Accomplished
                                                </p>
                                            </div>
                                        </a>
                                         </div>
                                                                      
                                    <div class="col-6">
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href='{{ route('opportunity.oppanalysis') }}'>
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

</body>

</html>
@endsection
