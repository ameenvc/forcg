@extends('layouts.backend')
@section('content')
<!doctype html>
<html lang="en">
   <body>

                <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center"  style="height:0px;">

                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Enterprise Risk</li>

                            <li class="breadcrumb-item" aria-current="page">
                                <a class="link-fx" href="">Edit</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">
                                <a class="link-fx" href="/user/riskmgmt">Back</a>
                            </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
                <!-- END Hero -->

                <!-- Page Content -->
    <div class="content"style="width:100%">
         <!-- Horizontal -->
        <div class="block block-rounded">

            <div class="block-content block-content-full">
                <div class="row">
            <form class="mb-5" action="{{url('/user/risks/editrequest')}}" method="post">
                {{ csrf_field() }}

			@if(count($errors) >0 )
				@foreach($errors->all() as $error)
				<div class='alert alert-warning'>
						{{ $error }}
				</div>
				@endforeach
			@endif
			@if(Session::has('msg'))
				<div class="alert alert-primary">
					<a class="close" data-dismiss="alert">×</a>
					 {!!Session::get('msg')!!}
				</div>
            @endif

            @if(count($data)>0)

            @foreach($data as $dat)
            @endforeach
            @endif


                    @if(count($data)>0)
                    <table class="table table-responsive table-bordered table-striped table-vcenter " id="dynamicTable">

                        <thead style="background-color:#FFFFFF">
                            <tr>
                                <th class="text-center" style="font-size:13px;text-transform:capitalize">Risk Area</th>
                                <th class="text-center" style="font-size:13px;text-transform:capitalize">Risk Group</th>
                                <th class="text-center" style="font-size:13px;text-transform:capitalize">Risk Name</th>
                                <th class="text-center" style="font-size:13px;text-transform:capitalize">Risk Description</th>
                                <th class="text-center" style="font-size:13px;text-transform:capitalize">Risk Drivers</th>
                                <th class="text-center" style="font-size:13px;text-transform:capitalize">Risk Source</th>
                                <th class="text-center" style="font-size:13px;text-transform:capitalize;">Risk Response Strategy</th>

                                <th class="text-center" style="font-size:13px;text-transform:capitalize">Impact</th>
                                <th class="text-center" style="font-size:13px;text-transform:capitalize">Likelihood</th>


                            </tr>
                        </thead>


                        <tbody>
                            @foreach($data as $dat1)
                            <tr>
                                {{-- <td class="text-center "style="font-size:13px;width: 200px;">{{ Form::text('docnumber[]',$dat1->riskarea,array('class'=>'form-control','readonly','style'=>'font-size: 13px')) }}</td> --}}
                                <td class="text-center font-size-sm">
                                    <input  type="text" style="font-size:13px;"  class="form-control" id="docnumber[]" name="docnumber[]" Readonly=""  value="{{$dat1->department ? $dat1->department->departmentname : '-'}}" >
                                </td>
                                {{-- <td class="text-center "style="font-size:13px;width: 200px;">{{ Form::select('riskgroup[]',array('Compliance' => 'Compliance', 'Financial' => 'Financial', 'Strategic' => 'Strategic','Operational'=>'Operational'),$dat1->riskgroup,array('class'=>'form-control','style'=>'font-size: 13px'))}}

                                </td>   --}}
                                <td class="text-center "style="font-size:13px;width:125px;">
                                    <select class="form-control" id="select_type" style="font-size: 13px;"  name = "riskgroup[]"  >
                                        @if(count($rskgrp)> 0 )
                                        @foreach($rskgrp as $value)
                                        <option value="{{$value->riskgroups}}"
                                                {{ $dat1->riskgroup == $value->riskgroups ? 'selected' : '' }}>
                                            {{ $value->riskgroups }}
                                        </option>
                                        @endforeach
                                        @endif
                                    </select>
                                </td>
                                {{-- <td class="text-center "style="font-size:13px;width: 200px;">{{ Form::text('riskname[]',$dat1->riskname,array('class'=>'form-control','style'=>'font-size: 13px')) }}

                                </td>  --}}
                                <td class="text-center font-size-sm">
                                    <input  type="text" style="font-size:13px;"  class="form-control" id="riskname[]" name="riskname[]"   value="{{$dat1->riskname}}" >
                                </td>
                               {{-- <td class="text-center "style="font-size:13px;width: 200px;">{{ Form::text('riskdec[]',$dat1->riskdec,array('class'=>'form-control','style'=>'font-size: 13px')) }}

                                </td>  --}}
                                <td class="text-center font-size-sm">
                                    <textarea  type="text" style="font-size:13px;"  class="form-control" id="riskdec[]" name="riskdec[]"   > {{$dat1->riskdec}}</textarea>
                                </td>

                                {{-- <td
                                class="text-center "style="font-size:13px;width: 200px;">{{ Form::text('riskdri[]',$dat1->riskdri,array('class'=>'form-control','style'=>'font-size: 13px')) }}

                                </td> --}}
                                <td class="text-center ">
                                    <textarea  type="text" style="font-size:13px;"  class="form-control" id="riskdri[]" name="riskdri[]"   > {{$dat1->riskdri}}</textarea>
                                </td>
                                {{-- <td class="text-center "style="font-size:13px;width: 200px;">{{ Form::select('source[]',array('Internal' => 'Internal', 'External' => 'External','Internal/External'=>'Internal/External'),$dat1->riskscr,array('class'=>'form-control','style'=>'font-size: 13px'))}}</td> --}}
                                <td class="text-center "style="font-size:13px;width:125px;">
                                    <select class="form-control" id="source" style="font-size: 13px;"  name = "source[]"  >
                                        <option value="Internal" {{ $dat1->riskscr == "Internal" ? "selected" : ""}}>Internal</option>
                                        <option value="External" {{ $dat1->riskscr == "External" ? "selected" : ""}}>External</option>
                                        <option value="Internal/External" {{ $dat1->riskscr == "Internal/External" ? "selected" : ""}}>Internal/External</option>
                                    </select>
                                </td>
                                <td class="text-center "style="font-size:13px;width:125px;">
                                        <select class="form-control" id="risk_response"  name="risk_response[]" style="font-size: 13px;" >
                                            <option value=" {{$dat1->risk_response}}" >{{$dat1->risk_response}}</option>
                                            @if(count($strategy)> 0 )
                                                @foreach($strategy as $value)
                                                @if($dat1->risk_response != $value->name)
                                                    <option value=" {{$value->name}}"> {{ $value->name }}</option><br>
                                                    @endif
                                                    @endforeach
                                            @endif
                                        </select>
                                    </td>
                                {{-- <td class="text-center "style="font-size:13px;width: 200px;">{{ Form::select('impact[]',array('High' => 'High','Moderate'=>'Moderate','Low'=>'Low'),$dat1->impa,array('class'=>'form-control','style'=>'font-size: 13px'))}}</td> --}}
                                <td class="text-center "style="font-size:13px;width:125px;">
                                    <select class="form-control" id="impact" style="font-size: 13px;"  name = "impact[]" >
                                        <option value="" selected>Choose Impact</option>
                                        <option value="High" {{ $dat1->impa == "High" ? 'selected' : '' }}>High</option>
                                        <option value="Moderate" {{ $dat1->impa == "Moderate" ? 'selected' : '' }}>Moderate</option>
                                        <option value="Low" {{ $dat1->impa == "Low" ? 'selected' : '' }}>Low</option>
                                    </select>
                                </td>
                                <td class="text-center "style="font-size:13px;width:125px;">
                                    <select class="form-control" id="likelihood" style="font-size: 13px;"  name = "likelihood[]" placeholder="Choose Likelihood">
                                        <option value="" selected>Choose Likelihood</option>
                                        <option value="High" {{ $dat1->like == "High" ? 'selected' : '' }}>High</option>
                                        <option value="Moderate" {{ $dat1->like == "Moderate" ? 'selected' : '' }}>Moderate</option>
                                        <option value="Low" {{ $dat1->like == "Low" ? 'selected' : '' }}>Low</option>
                                    </select>
                                </td>
                                {{-- <td class="text-center "style="font-size:13px;width: 200px;">{{ Form::select('likelihood[]',array('High' => 'High','Moderate'=>'Moderate','Low'=>'Low'),$dat1->like,array('class'=>'form-control','style'=>'font-size: 13px'))}}</td> --}}


                                 <div class="form-group">

                                    <input type="hidden" name="sno[]" value="{{ $dat1->Rid}}">
                                    {{-- <input type="hidden" name="revno[]" value="{{ $dat1->revnumber}}">  --}}


                                  </div>
                            </tr>

                            @endforeach
                        </tbody>

                    </table>
                    @endif


            <div >

                <button for="submittl" type="submit" onclick=" return confirm('Do you want to proceed?')" class="btn btn-primary"style="font-size:13px;margin-left:90%;">Submit</button>

                    </div>
                </form>
            </div>
        </div>
        </div>


        </body>
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
        

</body>
</html>
@endsection
