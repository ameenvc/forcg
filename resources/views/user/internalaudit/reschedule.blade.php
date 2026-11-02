@extends('layouts.backend')
@section('content')
        <!doctype html>
<html lang="en">

<body>

<!-- Hero -->
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center"
             style="height:0px;">

            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">Audit Schedule & Log</li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="">Reschedule</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="/user/auditschedule">Back</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <main>
    <!-- Horizontal -->
    <div class="block block-rounded">

        <div class="block-content block-content-full">
            <div class="row">

                <div class="col-lg-10">

                    <form class="mb-5" action="{{route('user.internalaudit.rescheduleRecord', $audit->id)}}" enctype="multipart/form-data"
                          method="post">
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

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="id" style="font-size: 13px;">Audit
                                id</label>
                            <div class="col-sm-12">
                                {{ Form::text('id',$audit->aud_id,array('class'=>'form-control','Readonly','style'=>'font-size: 13px')) }}
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="Auditdep" style="font-size: 13px;">Department</label>
                            <div class="col-sm-12">

                                {{ Form::text('Auditdep',$audit->departmentObj->departmentname ,array('class'=>'form-control','Readonly','style'=>'font-size: 13px')) }}
                            </div>
                        </div>
                        <div class="form-group" style="display:inline-block;width:98.5%;">
                            <label class="col-sm-12 col-form-label" for="Auditscope" style="font-size: 13px;">Scope</label>
                            <div class="col-sm-12">


                                {{ Form::textarea('Auditscope',$audit->scope,array('class'=>'form-control','rows' => 4,'Readonly','style'=>'font-size: 13px')) }}
                            </div>
                        </div>



                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="auditors" style="font-size: 13px;">Auditors</label>
                            <div class="col-sm-12">
                                {{ Form::text('Audittors',$audit->auditors,array('class'=>'form-control','Readonly','style'=>'font-size: 13px')) }}
                            </div>
                        </div>
                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="contact" style="font-size: 13px;">Auditee</label>
                            <div class="col-sm-12">

                                {{ Form::text('Auditcont',$audit->contact ,array('class'=>'form-control','Readonly','style'=>'font-size: 13px')) }}
                            </div>
                        </div>
                        <div class="form-group" style="display:inline-block;width:98.5%;">
                            <label class="col-sm-4 col-form-label" for="Auditfocus" style="font-size: 13px;">Focus</label>
                            <div class="col-sm-8">

                                {{ Form::textarea('Auditfocus',$audit->focus,array('class'=>'form-control','rows' => 4,'Readonly','style'=>'font-size: 13px')) }}
                            </div>
                        </div>
                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="date" style="font-size: 13px;">From
                                date</label>
                            <div class="col-sm-12">
                                <input type="date" style="font-size: 13px;" class="js-datepicker form-control"
                                       id="date" name="date" data-week-start="1" data-autoclose="true"
                                       data-today-highlight="true" data-date-format="dd/mm/yy"
                                       value="{{ $audit->date }}">
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="enddate" style="font-size: 13px;">To
                                date</label>
                            <div class="col-sm-12">
                                <input type="date" style="font-size: 13px;" class="js-datepicker form-control"
                                       id="date" name="enddate" data-week-start="1" data-autoclose="true"
                                       data-today-highlight="true" data-date-format="dd/mm/yy"
                                       value="{{$audit->enddate }}">
                            </div>
                        </div>


                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="time"
                                   style="font-size: 13px;">Time</label>
                            <div class="col-sm-12">
                                <input class="form-control" style="font-size: 13px;" type="time" name="time"
                                       value="{{ $audit->time }}">
                            </div>
                        </div>
                        <input type="hidden" name="id" value="{{ $audit->id}}">


                        <div>
                            <button for="submittl" type="submit" class="btn btn-primary"
                                    style="font-size:13px; margin-left:90%;" data-toggle="click-ripple"
                                    onclick="return confirm('Are you sure to submit the request?')">Reschedule
                            </button>

                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
    </main>
</div>
</body>
</html>
<div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps"
     aria-hidden="true">
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
                            <a class="block block-rounded block-link-shadow bg-body" href="/user/auditschedule">
                                <div class="block-content text-center">
                                    <i class="far fa-list-alt fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Audit Schedule
                                    </p>
                                </div>
                            </a>
                            <!-- END Products -->
                        </div>
                        <div class="col-6">
                            <!-- Sales -->
                            <a class="block block-rounded block-link-shadow bg-body mb-0"
                               href="/user/internalaudit/auditorslist">
                                <div class="block-content text-center">
                                    <i class="fas fa-address-card fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        <br> Auditors List
                                    </p>
                                </div>
                            </a>
                            <!-- END Sales -->
                        </div>
                        <div class="col-6">
                            <!-- Payments -->
                            <a class="block block-rounded block-link-shadow bg-body mb-0"
                               href="/user/internalaudit/auditlog">
                                <div class="block-content text-center">
                                    <i class="fas fa-calendar-check fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        <br>Audit Log
                                    </p>
                                </div>
                            </a>
                            <!-- END Payments -->
                        </div>
                        <div class="col-6">
                            <!-- Sales -->
                            <a class="block block-rounded block-link-shadow bg-body mb-2"
                               href="/user/internalaudit/closedaudits">
                                <div class="block-content text-center">
                                    <i class="fas fa-check-circle fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Closed Audits
                                    </p>
                                </div>
                            </a>
                            <!-- END Sales -->
                        </div>
                        <div class="col-6">
                            <!-- Sales -->
                            <a class="block block-rounded block-link-shadow bg-body mb-0"
                               href='/user/internalaudit/analysis'>
                                <div class="block-content text-center">
                                    <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Analysis
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


</body>
</html>
@endsection



