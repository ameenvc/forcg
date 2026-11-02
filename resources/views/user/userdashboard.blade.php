@extends('layouts.backend')
@section('content')
<!doctype html>
<html lang="en">
<body>
    {{-- {{ $start = Carbon::now()->getTimestampMs(); }} --}}
    <!-- Hero -->
<div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2 text-center text-sm-left">
                <div class="flex-sm-fill">
                    <h1 class="h3 font-w700 mb-2">
                        Main Dashboard
                    </h1>
                    <h2 class="h6 font-w500 text-muted mb-0">
                        Welcome <a class="font-w600" href="javascript:void(0)">{{Auth::user()->name}}</a>, this is main dashboard.
                    </h2>
                </div>
            </div>
        </div>
</div>

<div class="content" >
    <div class="row">
        <div class="col-md-12">
            <div class="js-slider slick-nav-hover"   data-autoplay="true"  data-arrows="true">
            @if(($model->auditmanagement==1)||($model->auditschedulelogs==1))
                <div class="block block-rounded">

                    <div class="block-header block-header-default">
                        <h8>Internal Audit</h8>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                                <i class="si si-refresh"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                    <div class="js-icon-list row items-push-2x text-center">
                        <div class="col-6 col-xl-2 py-3">
                            <i class="fa fa-inbox fa-2x text-success"></i>
                            <div class="font-size-h7 font-w600 mt-3">{{$opencount}}</div>
                            <div class="text-muted">Total Open Isuses</div>
                        </div>
                        <div class="col-6 col-xl-3 py-3">
                            <i class="fas fa-user-clock fa-2x"></i>
                            <div class="font-size-h7 font-w600 mt-3">{{$nmdue}}</div>
                            <div class="text-muted">Total Due Actions</div>
                        </div>
                        <div class="col-6 col-xl-2 py-3">
                            <i class="fas fa-spinner fa-2x"></i>
                            <div class="font-size-h7 font-w600 mt-3">{{$actions}}</div>
                            <div class="text-muted">Awaiting Actions</div>
                        </div>
                        <div class="col-6 col-xl-3 py-3">
                            <i class="fas fa-file-signature fa-2x"></i>
                            <div class="font-size-h7 font-w600 mt-3">{{$proposed}}</div>
                            <div class="text-muted">Awaiting Reviews</div>
                        </div>
                        <div class="col-6 col-xl-2 py-3">
                            <i class="fas fa-circle-notch fa-2x"></i>
                            <div class="font-size-h7 font-w600 mt-3">{{$awaitingclosure}}</div>
                            <div class="text-muted">Awaiting Closure</div>
                        </div>
                     </div>
                     </div>

                </div>
                @endif
                @if($model->correctiveaction==1)
                <div class="block block-rounded">

                    <div class="block-header block-header-default">
                        <h8>NCR & Corrective Actions</h8>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                                <i class="si si-refresh"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                    <div class="js-icon-list row items-push-2x text-center">
                        <div class="col-6 col-xl-2 py-3">
                            <i class="fa fa-inbox fa-2x text-success"></i>
                            <div class="font-size-h7 font-w600 mt-3">{{$opencountcor}}</div>
                            <div class="text-muted">Total Open Isuses</div>
                        </div>
                        <div class="col-6 col-xl-3 py-3">
                            <i class="fas fa-user-clock fa-2x"></i>
                            <div class="font-size-h7 font-w600 mt-3">{{$nmduecor}}</div>
                            <div class="text-muted">Total Due Actions</div>
                        </div>
                        <div class="col-6 col-xl-2 py-3">
                            <i class="fas fa-spinner fa-2x"></i>
                            <div class="font-size-h7 font-w600 mt-3">{{$actionscor}}</div>
                            <div class="text-muted">Awaiting Actions</div>
                        </div>
                        <div class="col-6 col-xl-3 py-3">
                            <i class="fas fa-file-signature fa-2x"></i>
                            <div class="font-size-h7 font-w600 mt-3">{{$proposedcor}}</div>
                            <div class="text-muted">Awaiting Reviews</div>
                        </div>
                        <div class="col-6 col-xl-2 py-3">
                            <i class="fas fa-circle-notch fa-2x"></i>
                            <div class="font-size-h7 font-w600 mt-3">{{$awaitingclosurecor}}</div>
                            <div class="text-muted">Awaiting Closure</div>
                        </div>
                     </div>
                     </div>

                </div>
                @endif
                @if($model->hse==1)
                <div class="block block-rounded">

                    <div class="block-header block-header-default">
                        <h8>HSE Observations</h8>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                                <i class="si si-refresh"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                    <div class="js-icon-list row items-push-2x text-center">
                        <div class="col-6 col-xl-2 py-3">
                            <i class="fa fa-inbox fa-2x text-success"></i>
                            <div class="font-size-h7 font-w600 mt-3">{{$opencounthse}}</div>
                            <div class="text-muted">Total Open Isuses</div>
                        </div>
                        <div class="col-6 col-xl-3 py-3">
                            <i class="fas fa-user-clock fa-2x"></i>
                            <div class="font-size-h7 font-w600 mt-3">{{$nmduehse}}</div>
                            <div class="text-muted">Total Due Actions</div>
                        </div>
                        <div class="col-6 col-xl-2 py-3">
                            <i class="fas fa-spinner fa-2x"></i>
                            <div class="font-size-h7 font-w600 mt-3">{{$actionshse}}</div>
                            <div class="text-muted">Awaiting Actions</div>
                        </div>
                        <div class="col-6 col-xl-3 py-3">
                            <i class="fas fa-file-signature fa-2x"></i>
                            <div class="font-size-h7 font-w600 mt-3">{{$proposedhse}}</div>
                            <div class="text-muted">Awaiting Reviews</div>
                        </div>
                        <div class="col-6 col-xl-2 py-3">
                            <i class="fas fa-circle-notch fa-2x"></i>
                            <div class="font-size-h7 font-w600 mt-3">{{$awaitingclosurehse}}</div>
                            <div class="text-muted">Awaiting Closure</div>
                        </div>
                     </div>
                     </div>

                </div>
               @endif
               @if($model->nearmiss==1)
                <div class="block block-rounded">

                    <div class="block-header block-header-default">
                        <h8>Near Miss</h8>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                                <i class="si si-refresh"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                    <div class="js-icon-list row items-push-2x text-center">
                        <div class="col-6 col-xl-2 py-3">
                            <i class="fa fa-inbox fa-2x text-success"></i>
                            <div class="font-size-h7 font-w600 mt-3">{{$opencountnearmiss}}</div>
                            <div class="text-muted">Total Open Isuses</div>
                        </div>
                        <div class="col-6 col-xl-3 py-3">
                            <i class="fas fa-user-clock fa-2x"></i>
                            <div class="font-size-h7 font-w600 mt-3">{{$nmduenearmiss}}</div>
                            <div class="text-muted">Total Due Actions</div>
                        </div>
                        <div class="col-6 col-xl-2 py-3">
                            <i class="fas fa-spinner fa-2x"></i>
                            <div class="font-size-h7 font-w600 mt-3">{{$actionsnearmiss}}</div>
                            <div class="text-muted">Awaiting Actions</div>
                        </div>
                        <div class="col-6 col-xl-3 py-3">
                            <i class="fas fa-file-signature fa-2x"></i>
                            <div class="font-size-h7 font-w600 mt-3">{{$proposednearmiss}}</div>
                            <div class="text-muted">Awaiting Reviews</div>
                        </div>
                        <div class="col-6 col-xl-2 py-3">
                            <i class="fas fa-circle-notch fa-2x"></i>
                            <div class="font-size-h7 font-w600 mt-3">{{$awaitingclosurenearmiss}}</div>
                            <div class="text-muted">Awaiting Closure</div>
                        </div>
                     </div>
                     </div>

                </div>
               @endif

            </div>
        </div>
    </div>
	<div class="row">

        <div class="col-xl-8 col-lg-8 col-md-8 d-flex flex-column">
            <div class="js-slider slick-nav-hover"   data-autoplay="true"  data-arrows="true">
            @if(($model->auditmanagement==1)||($model->auditschedulelogs==1))
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h8>Internal Audit - Open/Due Issues-Department Wise</h8>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                                <i class="si si-refresh"></i>
                            </button>
                            <button type="button" class="btn-block-option">
                                <i class="si si-settings"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content block-content-full align-items-center">
                    <canvas class="js-chartjs-earnings" id="myChart1"></canvas>
                    </div>
                </div>
                @endif
                @if($model->correctiveaction==1)
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h8>NCR & Corrective Actions - Open/Due Issues-Department Wise</h8>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                                <i class="si si-refresh"></i>
                            </button>
                            <button type="button" class="btn-block-option">
                                <i class="si si-settings"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content block-content-full align-items-center">
                    <canvas class="js-chartjs-earnings" id="myChart2"></canvas>
                    </div>
                </div>
                @endif
                @if($model->hse==1)
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h8>HSE Observations - Open/Due Issues-Department Wise</h8>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                                <i class="si si-refresh"></i>
                            </button>
                            <button type="button" class="btn-block-option">
                                <i class="si si-settings"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content block-content-full align-items-center">
                    <canvas class="js-chartjs-earnings" id="myChart3"></canvas>
                    </div>
                </div>
                @endif
                @if($model->nearmiss==1)
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h8>Near Miss - Open/Due Issues-Department Wise</h8>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                                <i class="si si-refresh"></i>
                            </button>
                            <button type="button" class="btn-block-option">
                                <i class="si si-settings"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content block-content-full align-items-center">
                    <canvas class="js-chartjs-earnings" id="myChart4"></canvas>
                    </div>
                </div>
                @endif
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 d-flex flex-column">
             <div class="block block-rounded">
                 <div class="block-header block-header-default">
                     <h3 class="block-title">
                         <i class="fa fa-briefcase text-muted mr-1"></i>
                         Higher Score Departments
                     </h3>
                     <div class="block-options">
                         <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                             <i class="si si-refresh"></i>
                         </button>
                     </div>
                 </div>
                 <div class="block-content">
                    @foreach($largest_overallscore as $key => $item)
                     @php
                         $icon = App\Departments::where('departmentname', $key)->value('icon');
                     @endphp
                     <div class="media d-flex align-items-center push">
                         <div class="mr-3">
                             <a class="item item-rounded bg-info" href="javascript:void(0)">
                                 <i class="{{$icon}} fa-2x text-white-75"></i>
                             </a>
                         </div>
                         <div class="media-body">
                             <div class="font-w600">{{ ucfirst($key) }}</div>
                             <div class="font-size-sm text-primary">Overall Score: {{ $item . '%'}}</div>
                         </div>
                     </div>
                     @endforeach

                     <div class="text-center push">
                         <a href="#Department" type="button" class="btn btn-sm btn-light">View More..</a>
                     </div>
                 </div>
             </div>

    </div>
    </div>
    <div class="row">
        <div class="col-xl-6 d-flex flex-column">
            <div class="js-slider slick-nav-hover"   data-autoplay="true"  data-arrows="true">
            @if(($model->auditmanagement==1)||($model->auditschedulelogs==1))
            <div class="block block-rounded flex-grow-1 d-flex flex-column">
                <div class="block-header block-header-default">
                    <h8>Internal audit - Yearly Trend(Total Issues)</h8>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                            <i class="si si-refresh"></i>
                        </button>
                        <button type="button" class="btn-block-option">
                            <i class="si si-settings"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content block-content-full flex-grow-1 d-flex align-items-center">
                   <canvas class="js-chartjs-earnings" id="myChart5"></canvas>
                </div>
            </div>
            @endif
            @if($model->correctiveaction==1)
            <div class="block block-rounded flex-grow-1 d-flex flex-column">
                <div class="block-header block-header-default">
                    <h8 >Corrective - Yearly Trend(Total Issues)</h8>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                            <i class="si si-refresh"></i>
                        </button>
                        <button type="button" class="btn-block-option">
                            <i class="si si-settings"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content block-content-full flex-grow-1 d-flex align-items-center">
                   <canvas class="js-chartjs-earnings" id="myChart6"></canvas>
                </div>
            </div>
            @endif
            @if($model->hse==1)
            <div class="block block-rounded flex-grow-1 d-flex flex-column">
                <div class="block-header block-header-default">
                    <h8>HSE Observations - Yearly Trend(Total Issues)</h8>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                            <i class="si si-refresh"></i>
                        </button>
                        <button type="button" class="btn-block-option">
                            <i class="si si-settings"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content block-content-full flex-grow-1 d-flex align-items-center">
                   <canvas class="js-chartjs-earnings" id="myChart7"></canvas>
                </div>
            </div>
            @endif
            @if($model->nearmiss==1)
            <div class="block block-rounded flex-grow-1 d-flex flex-column">
                <div class="block-header block-header-default">
                    <h8>Near Miss - Yearly Trend(Total Issues)</h8>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                            <i class="si si-refresh"></i>
                        </button>
                        <button type="button" class="btn-block-option">
                            <i class="si si-settings"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content block-content-full flex-grow-1 d-flex align-items-center">
                   <canvas class="js-chartjs-earnings" id="myChart8"></canvas>
                </div>
            </div>
            @endif

        </div>
    </div>
    <div class="col-xl-6 d-flex flex-column">
        <div class="js-slider slick-nav-hover"   data-autoplay="true"  data-arrows="true">
        @if(($model->auditmanagement==1)||($model->auditschedulelogs==1))
            <div class="block block-rounded flex-grow-1 d-flex flex-column">
                <div class="block-header block-header-default">
                    <h8>Internal audit - Monthly Trend (Total Issues)</h8>
                    <div class="block-options">
                            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                                <i class="si si-refresh"></i>
                            </button>
                            <button type="button" class="btn-block-option">
                                <i class="si si-settings"></i>
                            </button>
                    </div>
                </div>
                <div class="block-content block-content-full flex-grow-1 d-flex align-items-center">

                    <canvas class="js-chartjs-earnings" id="myChart9"></canvas>
                </div>
            </div>
            @endif
            @if($model->correctiveaction==1)
            <div class="block block-rounded flex-grow-1 d-flex flex-column">
                <div class="block-header block-header-default">
                    <h8>Corrective - Monthly Trend (Total Issues)</h8>
                    <div class="block-options">
                            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                                <i class="si si-refresh"></i>
                            </button>
                            <button type="button" class="btn-block-option">
                                <i class="si si-settings"></i>
                            </button>
                    </div>
                </div>
                <div class="block-content block-content-full flex-grow-1 d-flex align-items-center">

                    <canvas class="js-chartjs-earnings" id="myChart10"></canvas>
                </div>
            </div>
            @endif
            @if($model->hse==1)
            <div class="block block-rounded flex-grow-1 d-flex flex-column">
                <div class="block-header block-header-default">
                    <h8>HSE observations - Monthly Trend (Total Issues)</h8>
                    <div class="block-options">
                            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                                <i class="si si-refresh"></i>
                            </button>
                            <button type="button" class="btn-block-option">
                                <i class="si si-settings"></i>
                            </button>
                    </div>
                </div>
                <div class="block-content block-content-full flex-grow-1 d-flex align-items-center">

                    <canvas class="js-chartjs-earnings" id="myChart11"></canvas>
                </div>
            </div>
            @endif
            @if($model->nearmiss==1)
            <div class="block block-rounded flex-grow-1 d-flex flex-column">
                <div class="block-header block-header-default">
                    <h8>Near miss - Monthly Trend (Total Issues)</h8>
                    <div class="block-options">
                            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                                <i class="si si-refresh"></i>
                            </button>
                            <button type="button" class="btn-block-option">
                                <i class="si si-settings"></i>
                            </button>
                    </div>
                </div>
                <div class="block-content block-content-full flex-grow-1 d-flex align-items-center">

                    <canvas class="js-chartjs-earnings" id="myChart12"></canvas>
                </div>
            </div>
           @endif

        </div>
    </div>

</div>
<div class="row">

    <div class="col-xl-8 d-flex flex-column">
        <div class="js-slider slick-nav-hover"   data-autoplay="true"  data-arrows="true">
         @if($model->riskmanagement==1)
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h8>Enterprise Risk - High/Moderate/Low Risks-Department Wise</h8>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                            <i class="si si-refresh"></i>
                        </button>
                        <button type="button" class="btn-block-option">
                            <i class="si si-settings"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content block-content-full align-items-center">
                <canvas class="js-chartjs-earnings" id="myChart13"></canvas>
                </div>
            </div>
           @endif
           @if($model->opportunity==1)
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h8>Opportunity - High/Moderate/Low opportunities-Department Wise</h8>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                            <i class="si si-refresh"></i>
                        </button>
                        <button type="button" class="btn-block-option">
                            <i class="si si-settings"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content block-content-full align-items-center">
                <canvas class="js-chartjs-earnings" id="myChart14"></canvas>
                </div>
            </div>
          @endif
        </div>
    </div>

</div>

<div class="row">
    <div class="col-xl-8 d-flex flex-column">
       @if($model->compliance==1)
        <div class="block block-rounded flex-grow-1 d-flex flex-column">
            <div class="block-header block-header-default">
                <h8>Legal Compliance</h8>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                        <i class="si si-refresh"></i>
                    </button>
                    <button type="button" class="btn-block-option">
                        <i class="si si-settings"></i>
                    </button>
                </div>
            </div>
            <div class="block-content block-content-full flex-grow-1 d-flex align-items-center">
               <canvas class="js-chartjs-earnings" id="myChart15"></canvas>
            </div>
        </div>
       @endif
    </div>
</div>
@if($model->documentmanagement==1)
<div class="row">
    <div class="col-xl-7 d-flex flex-column">

      <div class="block block-rounded" >
          <div class="block-header block-header-default">
            <h8>Document Management</h8>
              <div class="block-options">
                  <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                      <i class="si si-refresh"></i>
                  </button>
              </div>
          </div>
          <div class="block-content">
              <table class="table table-borderless table-striped  table-vcenter font-size-sm">
                  <thead class="thead-light">
                      <tr>
                          <th class="text-center" style="font-size:10px;width: 10px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Slno</th>
                          <th class="text-center" style="font-size:10px;width: 80px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Type</th>
                          <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Total</th>
                      </tr>
                  </thead>
                  <tbody>
                    <?php $no=0;?>
                      @foreach($doctypes as $doc)

                      <?php
                       $docs=DB::table('masterdocs')->where('type',$doc)->get();
                       $doccount=count($docs);
                        ?>

                    <tr>
                          <td class="font-w600 text-center" style="border: 2px solid #e1e6e9;">
                            <a href=""style="font-size:10px" >{{ $no=$no+1}} </a>
                          </td>
                          <td class="font-w600 text-center" style="border: 2px solid #e1e6e9;">
                              <a href=""style="font-size:10px" >{{$doc}}</a>
                          </td>
                          <td class="font-w600 text-center" style="border: 2px solid #e1e6e9;">
                              <a href=""style="font-size:10px" >{{$doccount}}</a>
                          </td>

                      </tr>

                     @endforeach
                  </tbody>
              </table>
          </div>
      </div>
      </div>

      <div class="col-xl-5 d-flex flex-column">
        <!-- Earnings Summary -->
        <div class="block block-rounded flex-grow-1 d-flex flex-column">
            <div class="block-header block-header-default">
                <h8>Document Management(YTD)</h8>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                        <i class="si si-refresh"></i>
                    </button>
                    <button type="button" class="btn-block-option">
                        <i class="si si-settings"></i>
                    </button>
                </div>
            </div>
            <div class="block-content block-content-full flex-grow-1 d-flex align-items-center">
               <canvas class="js-chartjs-earnings" id="myChart22"></canvas>
            </div>
        </div>

    </div>
    </div>
    @endif
@if($model->ohsrisk==1)
<div class="row">
    <div class="col-xl-4 d-flex flex-column">
        <!-- Earnings Summary -->
        <div class="block block-rounded flex-grow-1 d-flex flex-column">
            <div class="block-header block-header-default">
                <h8>Total Original Risks</h8>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                        <i class="si si-refresh"></i>
                    </button>
                    <button type="button" class="btn-block-option">
                        <i class="si si-settings"></i>
                    </button>
                </div>
            </div>
            <div class="block-content block-content-full flex-grow-1 d-flex align-items-center">
               <canvas class="js-chartjs-earnings" id="myChart16"></canvas>
            </div>
        </div>

    </div>

    <div class="col-xl-4 d-flex flex-column">
        <!-- Earnings Summary -->
        <div class="block block-rounded flex-grow-1 d-flex flex-column">
            <div class="block-header block-header-default">
                <h8>Total Residual Risks</h8>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                        <i class="si si-refresh"></i>
                    </button>
                    <button type="button" class="btn-block-option">
                        <i class="si si-settings"></i>
                    </button>
                </div>
            </div>
            <div class="block-content block-content-full flex-grow-1 d-flex align-items-center">
               <canvas class="js-chartjs-earnings" id="myChart17"></canvas>
            </div>
        </div>

    </div>
    <div class="col-xl-4 d-flex flex-column">
    <div class="block block-rounded flex-grow-1 d-flex flex-column">
        <div class="block-header block-header-default">
            <h8>Risk Review</h8>
            <div class="block-options">
                <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                    <i class="si si-refresh"></i>
                </button>
                <button type="button" class="btn-block-option">
                    <i class="si si-settings"></i>
                </button>
            </div>
        </div>
        <div class="block-content block-content-full flex-grow-1 d-flex align-items-center">
        <div class="col-6 col-xl-10 ">
                 <div class="js-pie-chart pie-chart" data-percent="{{$ORpercentage}}" data-line-width="3" data-size="100" data-bar-color="rgb(46,134,193)" data-track-color="#eeeeee" >
                <span>{{$ORpercentage}}%</span>
            </div>
        </div>
    </div>
</div>
    <div class="block block-rounded flex-grow-1 d-flex flex-column">
        <div class="block-header block-header-default">
            <h8>Residual Risk</h8>
            <div class="block-options">
                <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                    <i class="si si-refresh"></i>
                </button>
                <button type="button" class="btn-block-option">
                    <i class="si si-settings"></i>
                </button>
            </div>
        </div>
        <div class="block-content block-content-full flex-grow-1 d-flex align-items-center">
            <div class="col-6 col-xl-12">
            <canvas class="js-chartjs-donut" id="myChart18"></canvas>
         </div>
        </div>


    </div>
</div>


</div>



<div class="row">
    <div class="col-xl-4 d-flex flex-column">
        <!-- Earnings Summary -->
        <div class="block block-rounded flex-grow-1 d-flex flex-column">
            <div class="block-header block-header-default">
                <h8>Total Original Risks</h8>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                        <i class="si si-refresh"></i>
                    </button>
                    <button type="button" class="btn-block-option">
                        <i class="si si-settings"></i>
                    </button>
                </div>
            </div>
            <div class="block-content block-content-full flex-grow-1 d-flex align-items-center">
               <canvas class="js-chartjs-earnings" id="myChart19"></canvas>
            </div>
        </div>

    </div>

    <div class="col-xl-4 d-flex flex-column">
        <!-- Earnings Summary -->
        <div class="block block-rounded flex-grow-1 d-flex flex-column">
            <div class="block-header block-header-default">
                <h8>Total Environmental Risks</h8>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                        <i class="si si-refresh"></i>
                    </button>
                    <button type="button" class="btn-block-option">
                        <i class="si si-settings"></i>
                    </button>
                </div>
            </div>
            <div class="block-content block-content-full flex-grow-1 d-flex align-items-center">
               <canvas class="js-chartjs-earnings" id="myChart20"></canvas>
            </div>
        </div>

    </div>
    @endif
    @if($model->enviaspects==1)
    <div class="col-xl-4 d-flex flex-column">
    <div class="block block-rounded flex-grow-1 d-flex flex-column">
        <div class="block-header block-header-default">
                <h8>Aspect Review</h8>
                <div class="block-options">
                <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                    <i class="si si-refresh"></i>
                </button>
                <button type="button" class="btn-block-option">
                    <i class="si si-settings"></i>
                </button>
            </div>
        </div>
        <div class="block-content block-content-full flex-grow-1 d-flex align-items-center">
        <div class="col-6 col-xl-10 ">
            <div class="js-pie-chart pie-chart" data-percent="{{$envipercentage}}" data-line-width="3" data-size="100" data-bar-color="rgb(46,134,193)" data-track-color="#eeeeee" >
                <span>{{$envipercentage}}%</span>
                </div>
        </div>
    </div>
</div>
    <div class="block block-rounded flex-grow-1 d-flex flex-column">
        <div class="block-header block-header-default">
            <h8>Environmental Aspect</h8>
            <div class="block-options">
                <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                    <i class="si si-refresh"></i>
                </button>
                <button type="button" class="btn-block-option">
                    <i class="si si-settings"></i>
                </button>
            </div>
        </div>
        <div class="block-content block-content-full flex-grow-1 d-flex align-items-center">
            <div class="col-6 col-xl-12">
            <canvas class="js-chartjs-donut" id="myChart21"></canvas>
            </div>
        </div>


    </div>
</div>


</div>
@endif
<div class="block block-rounded" >
    <div class="col-lg-5 col-xl-3"style="margin-left:75%;margin-bottom:-40px" >
        <select class="form-control" id="changetable" name="changetable" onchange="changetable()" style="font-size:10px;">
                <option value="">Select Table</option>
                <option value="1">Department Performance Rank</option>
                <option value="2">Objectives</option>
                <option value="3">Health and Safety Risks</option>
                <option value="4">Environmental Aspects</option>
            </select>

    </div>
    <div class="block-content" id="Department">
            <h8>Department Performance Rank</h8>
            {{-- <div class="scrollable" style="height:300px;overflow-x:hidden;"> --}}
            <table class="table table-borderless table-striped table-sm table-vcenter font-size-sm">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Department</th>
                        <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Audit</th>
                        <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Corrective</th>
                        <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Compliance</th>
                        <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">HSE Observation</th>
                        <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Near Miss</th>
                        <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Over All Score</th>


                    </tr>
                </thead>
                <tbody>

                    @foreach($departments as $dep)

                  <tr>
                        <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                            <a href=""style="font-size:10px" >{{$dep->departmentname}}</a>
                        </td>
                        <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                            <a href=""style="font-size:10px" >{{$dep->audmark}}</a>
                        </td>
                        <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                            <a href=""style="font-size:10px" >{{$dep->corrmark}}</a>
                        </td>
                        <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                            <a href=""style="font-size:10px" >{{$dep->compmark}}</a>
                        </td>
                        <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                            <a href=""style="font-size:10px" >{{$dep->hsemark}}</a>
                        </td>
                        <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                            <a href=""style="font-size:10px" >{{$dep->nearmark}}</a>
                        </td>
                        <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                            <a class=" px-2 py-1 rounded bg-body-dark" href=""style="font-size:10px" >{{$dep->totalmarkpercentage}}%</a>
                        </td>


                    </tr>

                  @endforeach

                </tbody>
            </table>
          </div>
    <div class="block-content"id="Objectives">
        <h8>Objectives</h8>
        {{-- <div class="scrollable" style="height:300px;overflow-x:hidden;"> --}}
        <table class="table table-borderless table-striped table-sm table-vcenter font-size-sm">
            <thead class="thead-light">
                <tr>
                    <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Slno</th>
                   <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Department</th>
                    <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Total Objectives</th>
                    <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Approved Objectives</th>
                    <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Unapproved Objectives</th>
                    <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;background-color:red;text-transform:capitalize">Due Performance Report Update</th>
                    <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Achieved Objectives(Last Year)</th>
                    <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Unachieved Objectives(Last Year)</th>
                </tr>
            </thead>
            <tbody>
                <?php $no=0;?>

                @foreach($departments as $dep)
              <tr>
                    <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                        <a href=""style="font-size:10px" >{{$no=$no+1}}</a>
                    </td>
                    <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                        <a href=""style="font-size:10px" >{{$dep->departmentname}}</a>
                    </td>
                    <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                        <a href=""style="font-size:10px" >{{$dep->totalobjcount}}</a>
                    </td>
                    <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                        <a href=""style="font-size:10px" >{{$dep->appobjcount}}</a>
                    </td>
                    <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                        <a href=""style="font-size:10px" >{{$dep->unappobjcount}}</a>
                    </td>
                    <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                        <a href=""style="font-size:10px" >{{$dep->dueobjcount}}</a>
                    </td>
                    <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                        <a href=""style="font-size:10px" >{{$dep->achobjcount}}</a>
                    </td>
                    <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                        <a href=""style="font-size:10px" >{{$dep->nachobjcount}}</a>
                    </td>


                </tr>

               @endforeach

            </tbody>
        </table>
    </div>
{{-- </div> --}}
    <div class="block-content"id="Health">
        <h8>Health and Safety Risks</h8>
        {{-- <div class="scrollable" style="height:300px;overflow-x:hidden;" > --}}
        <table class="table table-borderless table-striped table-sm table-vcenter font-size-sm">
            <thead class="thead-light">
                <tr>
                    <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Slno</th>
                   <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Department</th>
                    <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Total Active Risks</th>
                    <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Original Risks<br>High</th>
                    <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Original Risks<br>Medium</th>
                    <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Residual Risks<br>High</th>
                    <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Residual Risks<br>Medium</th>
                    <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Review Pending</th>
                </tr>
            </thead>
            <tbody>
                <?php $no=0;?>
                @foreach ($departments as $dep)
              <tr>
                    <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                        <a href=""style="font-size:10px" >{{$no=$no+1}}</a>
                    </td>
                    <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                        <a href=""style="font-size:10px" >{{$dep->departmentname}}</a>
                    </td>
                    <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                        <a href=""style="font-size:10px" >{{$dep->activeohscount}}</a>
                    </td>
                    <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                        <a href=""style="font-size:10px" >{{$dep->orhigh}}</a>
                    </td>
                    <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                        <a href=""style="font-size:10px" >{{$dep->ormed}}</a>
                    </td>
                    <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                        <a href=""style="font-size:10px" >{{$dep->rhigh}}</a>
                    </td>
                    <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                        <a href=""style="font-size:10px" >{{$dep->rmed}}</a>
                    </td>
                    <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                        <a href=""style="font-size:10px" >{{$dep->ohspendingcount}}</a>
                    </td>


                </tr>

               @endforeach

            </tbody>
        </table>
    </div>
{{-- </div> --}}
    <div class="block-content"id="Environmental">
        <h8>Environmental Aspects</h8>
        {{-- <div class="scrollable" style="height:300px;overflow-x:hidden;" > --}}
        <table class="table table-borderless table-striped table-sm table-vcenter font-size-sm">
            <thead class="thead-light">
                <tr>
                    <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Slno</th>
                   <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Department</th>
                    <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Total Active Aspects</th>
                    <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Active Aspects(High)</th>
                    <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Active Aspects(Medium)</th>
                    <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Air Pollution</th>
                    <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Resource Depletion</th>
                    <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Water Pollution</th>
                    <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Noise Pollution</th>
                    <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Land Contamination</th>
                    <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Ozone Depletion</th>
                    <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Review Pending</th>


                </tr>
            </thead>
            <tbody>
                <?php $no=0;?>
                @foreach ($departments as $dep)
                <?php
                $dep_id = $dep->id;
                //$totalenvi=DB::table('enviaspects')->where('department',$dep_id)->where('status','Reviewed')->get();
                $dep->totalenvicount= DB::table('enviaspects')->where('department',$dep_id)->where('status','Reviewed')->count();
                //$totalenvihigh= DB::table('enviaspects')->where('department',$dep_id)->where('status','Reviewed')->where('HS','HS')->count();
                $dep->totalenvihighcount= DB::table('enviaspects')->where('department',$dep_id)->where('status','Reviewed')->where('HS','HS')->count();
                //$totalenvimed= DB::table('enviaspects')->where('department',$dep_id)->where('status','Reviewed')->where('HS','MS')->get();
                $dep->totalenvimedcount=DB::table('enviaspects')->where('department',$dep_id)->where('status','Reviewed')->where('HS','MS')->count();
                //$totalair=DB::table('enviaspects')->where('department',$dep_id)->where('status','Reviewed')->where('potential','Air Pollution')->get();
                $dep->totalaircount=DB::table('enviaspects')->where('department',$dep_id)->where('status','Reviewed')->where('potential','Air Pollution')->count();
                //$totalres=DB::table('enviaspects')->where('department',$dep_id)->where('status','Reviewed')->where('potential','Water Pollution')->get();
                $dep->totalrescount=DB::table('enviaspects')->where('department',$dep_id)->where('status','Reviewed')->where('potential','Water Pollution')->count();
                //$totalwater=DB::table('enviaspects')->where('department',$dep_id)->where('status','Reviewed')->where('potential','Resource Depletion')->get();
                $dep->totalwatercount=DB::table('enviaspects')->where('department',$dep_id)->where('status','Reviewed')->where('potential','Resource Depletion')->count();
                //$totalnoise=DB::table('enviaspects')->where('department',$dep_id)->where('status','Reviewed')->where('potential','Noise and Vibrational Effect')->get();
                $dep->totalnoisecount= DB::table('enviaspects')->where('department',$dep_id)->where('status','Reviewed')->where('potential','Noise and Vibrational Effect')->count();
                //$totalland=DB::table('enviaspects')->where('department',$dep_id)->where('status','Reviewed')->where('potential','Land Contamination')->get();
                $dep->totallandcount= DB::table('enviaspects')->where('department',$dep_id)->where('status','Reviewed')->where('potential','Land Contamination')->count();
                //$totalozone=DB::table('enviaspects')->where('department',$dep_id)->where('status','Reviewed')->where('potential','Ozone Depletion')->get();
                $dep->totalozonecount= DB::table('enviaspects')->where('department',$dep_id)->where('status','Reviewed')->where('potential','Ozone Depletion')->count();
                //$totalenvipending=DB::table('enviaspects')->where('department',$dep_id)->where('status','Review Pending')->get();
                $dep->totalenvipendingcount= DB::table('enviaspects')->where('department',$dep_id)->where('status','Review Pending')->count();
               ?>
              <tr>
                    <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                        <a href=""style="font-size:10px" >{{ $no=$no+1}}</a>
                    </td>
                    <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                        <a href=""style="font-size:10px" >{{$dep->departmentname}}</a>
                    </td>
                    <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                        <a href=""style="font-size:10px" >{{$dep->totalenvicount}}</a>
                    </td>
                    <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                        <a href=""style="font-size:10px" >{{$dep->totalenvihighcount}}</a>
                    </td>
                    <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                        <a href=""style="font-size:10px" >{{$dep->totalenvimedcount}}</a>
                    </td>
                    <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                        <a href=""style="font-size:10px" >{{$dep->totalaircount}}</a>
                    </td>
                    <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                        <a href=""style="font-size:10px" >{{$dep->totalrescount}}</a>
                    </td>
                    <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                        <a href=""style="font-size:10px" >{{$dep->totalwatercount}}</a>
                    </td>
                    <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                        <a href=""style="font-size:10px" >{{$dep->totalnoisecount}}</a>
                    </td>
                    <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                        <a href=""style="font-size:10px" >{{$dep->totallandcount}}</a>
                    </td>
                    <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                        <a href=""style="font-size:10px" >{{$dep->totalozonecount}}</a>
                    </td>
                    <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                        <a href=""style="font-size:10px" >{{$dep->totalenvipendingcount}}</a>
                    </td>

                </tr>

               @endforeach
            </tbody>
        </table></div>
    {{-- </div> --}}
{{-- {{ dd(Carbon::now()->getTimestampMs() - $start )}} --}}
</div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

<script>
    var process = <?php echo $process; ?>;
    var depcode = <?php echo $depcode; ?>;
    var Adopen = <?php echo $Adopen; ?>;
    var Adue = <?php echo $Adue; ?>;
    var Cdopen = <?php echo $Cdopen; ?>;
    var Cdue = <?php echo $Cdue; ?>;
    var Hdopen = <?php echo $Hdopen; ?>;
    var Hdue = <?php echo $Hdue; ?>;
    var Ndopen = <?php echo $Ndopen; ?>;
    var Ndue = <?php echo $Ndue; ?>;
    var year = <?php echo $year; ?>;
    var Atrend = <?php echo $Atrend; ?>;
    var Ctrend = <?php echo $Ctrend; ?>;
    var Htrend = <?php echo $Htrend; ?>;
    var Ntrend = <?php echo $Ntrend; ?>;
    var months = ["Jan","Feb","Mar","Apr","May","Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    var Amonthtrend = <?php echo $Amonthtrend; ?>;
    var Cmonthtrend = <?php echo $Cmonthtrend; ?>;
    var Hmonthtrend = <?php echo $Hmonthtrend; ?>;
    var Nmonthtrend = <?php echo $Nmonthtrend; ?>;
    var highrisk = <?php echo $highrisk; ?>;
    var moderaterisk = <?php echo $moderaterisk; ?>;
    var lowrisk = <?php echo $lowrisk; ?>;
    var highopp = <?php echo $highopp; ?>;
    var moderateopp = <?php echo $moderateopp; ?>;
    var lowopp = <?php echo $lowopp; ?>;
    var compliedn = <?php echo $compliedn; ?>;
    var complied = <?php echo $complied; ?>;
    var compp = <?php echo $compp; ?>;
    var reviewu = <?php echo $reviewu; ?>;
    var OHohs = <?php echo $OHohs; ?>;
    var OMohs = <?php echo $OMohs; ?>;
    var OLohs = <?php echo $OLohs; ?>;
    var RHohs = <?php echo $RHohs; ?>;
    var RMohs = <?php echo $RMohs; ?>;
    var RLohs = <?php echo $RLohs; ?>;
    var hml = ["High","Medium","Low"];
    var highenvi= <?php echo $highenvi; ?>;
    var mediumenvi= <?php echo $mediumenvi; ?>;
    var lowenvi= <?php echo $lowenvi; ?>;

    var air= <?php echo $air; ?>;
    var water= <?php echo $water; ?>;
    var noise= <?php echo $noise; ?>;
    var resource= <?php echo $resource; ?>;
    var ozone= <?php echo $ozone; ?>;
    var land= <?php echo $land; ?>;
    var ar= <?php echo $ar; ?>;
    var cr= <?php echo $cr; ?>;
    var rr= <?php echo $rr; ?>;
    var barChartData1 = {
        labels: depcode,
        datasets: [{
            label: 'Open',
            backgroundColor: "#003366",
            data: Adopen
        },
        {
            label: 'Due',
            backgroundColor: "#ff0000",
            data: Adue

        }]
    };
    var barChartData2 = {
        labels: depcode,
        datasets: [{
            label: 'Open',
            backgroundColor: "#003366",
            data: Cdopen
        },
        {
            label: 'Due',
            backgroundColor: "#ff0000",
            data: Cdue

        }]
    };
    var barChartData3 = {
        labels: depcode,
        datasets: [{
            label: 'Open',
            backgroundColor: "#003366",
            data: Hdopen
        },
        {
            label: 'Due',
            backgroundColor: "#ff0000",
            data: Hdue

        }]
    };
    var barChartData4 = {
        labels: depcode,
        datasets: [{
            label: 'Open',
            backgroundColor: "#003366",
            data: Ndopen
        },
        {
            label: 'Due',
            backgroundColor: "#ff0000",
            data: Ndue

        }]
    };
    var barChartData5= {
            labels: year,
            datasets: [{
                label: 'Total Issues',
                fill: false,
                lineTension: 0,
                backgroundColor: "rgb(46,134,193)",
                borderColor: "rgb(46,134,193)",
                data: Atrend

            }]
        };
        var barChartData6= {
            labels: year,
            datasets: [{
                label: 'Total Issues',
                fill: false,
                lineTension: 0,
                backgroundColor: "rgb(46,134,193)",
                borderColor: "rgb(46,134,193)",
                data: Ctrend

            }]
        };
        var barChartData7= {
            labels: year,
            datasets: [{
                label: 'Total Issues',
                fill: false,
                lineTension: 0,
                backgroundColor: "rgb(46,134,193)",
                borderColor: "rgb(46,134,193)",
                data: Htrend

            }]
        };
        var barChartData8= {
            labels: year,
            datasets: [{
                label: 'Total Issues',
                fill: false,
                lineTension: 0,
                backgroundColor: "rgb(46,134,193)",
                borderColor: "rgb(46,134,193)",
                data: Ntrend

            }]
        };
        var barChartData9= {
            labels: months ,
            datasets: [{
                label: 'Total Issues',
                fill: false,
                lineTension: 0,
                backgroundColor: "rgb(88,214,141)",
                borderColor: "rgb(88,214,141)",
                data: Amonthtrend

            }]
        };
        var barChartData10= {
            labels: months ,
            datasets: [{
                label: 'Total Issues',
                fill: false,
                lineTension: 0,
                backgroundColor: "rgb(88,214,141)",
                borderColor: "rgb(88,214,141)",
                data: Cmonthtrend

            }]
        };
        var barChartData11= {
            labels: months ,
            datasets: [{
                label: 'Total Issues',
                fill: false,
                lineTension: 0,
                backgroundColor: "rgb(88,214,141)",
                borderColor: "rgb(88,214,141)",
                data: Hmonthtrend

            }]
        };
        var barChartData12= {
            labels: months ,
            datasets: [{
                label: 'Total Issues',
                fill: false,
                lineTension: 0,
                backgroundColor: "rgb(88,214,141)",
                borderColor: "rgb(88,214,141)",
                data: Nmonthtrend

            }]
        };
        var barChartData13 = {
            labels: depcode,
            datasets: [{
                label: 'High',
                backgroundColor: "#ff6437",
                stack: 'Stack 0',
                data: highrisk
            },

            {
                label: 'Moderate',
                backgroundColor: "#fff0b9",
                data: moderaterisk,
                stack: 'Stack 0'
            },

            {
                label: 'Low',
                backgroundColor: "#00cc99",
                data: lowrisk,
                stack: 'Stack 0'
            }]
        };
        var barChartData14 = {
            labels: depcode,
            datasets: [{
                label: 'High',
                backgroundColor: "#ff6437",
                stack: 'Stack 0',
                data: highopp
            },

            {
                label: 'Moderate',
                backgroundColor: "#fff0b9",
                data: moderateopp,
                stack: 'Stack 0'
            },

            {
                label: 'Low',
                backgroundColor: "#00cc99",
                data: lowopp,
                stack: 'Stack 0'
            }]
        };

        var barChartData15 = {
            labels: depcode,
            datasets: [{
                label: 'Under Review',
                backgroundColor: "#003366",
                stack: 'Stack 0',
                data: reviewu
            },

            {
                label: 'Partially Complied',
                backgroundColor: "rgb(46,134,193)",
                data: compp,
                stack: 'Stack 0'
            },

            {
                label: 'Not Complied',
                backgroundColor: "#ff6437",
                data: compliedn,
                stack: 'Stack 0'
            },
             {
             label: 'Complied',
             backgroundColor: "#58D68D",
             data: complied,
             stack: 'Stack 0'
             }

        ]
        };
        var barChartData16= {
            datasets: [
                    {
                    label: 'High',
                    backgroundColor: "#ff6437",
                    data: OHohs
                    },
                    {
                    label: 'Medium',
                    backgroundColor: "#fff0b9",
                    data: OMohs
                    },
                    {
                    label: 'Low',
                    backgroundColor: "#00cc99",
                    data: OLohs
                    },
            ]
        };
        var barChartData17= {
            datasets: [
                    {
                    label: 'High',
                    backgroundColor: "#ff6437",
                    data: RHohs
                    },
                    {
                    label: 'Medium',
                    backgroundColor: "#fff0b9",
                    data: RMohs
                    },
                    {
                    label: 'Low',
                    backgroundColor: "#00cc99",
                    data: RLohs
                    },
            ]
        };
        var barChartData18= {
            labels: ['High','Medium','Low'],
            datasets: [{

                    backgroundColor: ["#ff6437","#fff0b9","#00cc99"],
                    data: [{{$RHohs}},{{$RMohs}},{{$RLohs}}]
                    }]
        };
        var barChartData19= {
            datasets: [
                    {
                    label: 'High',
                    backgroundColor: "#ff6437",
                    data: highenvi
                    },
                    {
                    label: 'Medium',
                    backgroundColor: "#fff0b9",
                    data: mediumenvi
                    },
                    {
                    label: 'Low',
                    backgroundColor: "#00cc99",
                    data: lowenvi
                    }
]
        };
        var barChartData20= {
            datasets: [
                    {
                    label: 'Air',
                    backgroundColor: "rgb(46,134,193)",
                    data: air
                    },
                    {
                    label: 'Water',
                    backgroundColor: "rgb(46,134,193)",
                    data: water
                    },
                    {
                    label: 'Noise',
                    backgroundColor: "rgb(46,134,193)",
                    data: noise
                    },
                    {
                    label: 'Resource',
                    backgroundColor: "rgb(46,134,193)",
                    data: resource
                    },
                    {
                    label: 'Ozone',
                    backgroundColor: "rgb(46,134,193)",
                    data: ozone
                    },
                    {
                    label: 'Land',
                    backgroundColor: "rgb(46,134,193)",
                    data: land
                    },
            ]
        };
        var barChartData21= {
            labels: ['High','Medium','Low'],
            datasets: [{

                    backgroundColor: ["#ff6437","#fff0b9","#00cc99"],
                    data: [{{$highenvi}},{{$mediumenvi}},{{$lowenvi}}]
                    }]
        };

        var barChartData22 = {
            datasets: [{
                label: 'Add Request',
                backgroundColor: "rgb(46,134,193)",

                data: ar
            },

            {
                label: 'Change Request',
                backgroundColor: "rgb(46,134,193)",
                data: cr

            },

            {
                label: 'Removal Request',
                backgroundColor: "rgb(46,134,193)",
                data: rr

            }]
        };
        window.onload = function() {
            <?php
             if(($model->auditmanagement==1)||($model->auditschedulelogs==1))
             {
            ?>
            var ctx= document.getElementById("myChart1").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: barChartData1,
                options: {
                    elements: {
                        rectangle: {
                            borderWidth: 0,
                            borderColor: '#c1c1c1',
                            borderSkipped: 'bottom',
                        }
                    },
                    legend: { position: 'bottom' },
                    responsive: true,
                    title: {
                        display: true,
                        text: 'Open/Due Issues-Department Wise'
                    }
                }
            });
            <?php
            }
            ?>
            <?php
             if($model->correctiveaction==1)
             {
            ?>
            var ctx= document.getElementById("myChart2").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: barChartData2,
                options: {
                    elements: {
                        rectangle: {
                            borderWidth: 0,
                            borderColor: '#c1c1c1',
                            borderSkipped: 'bottom',
                        }
                    },
                    legend: { position: 'bottom' },
                    responsive: true,
                    title: {
                        display: true,
                        text: 'Open/Due Issues-Department Wise'
                    }
                }
            });
            <?php
             }
            ?>
            <?php
             if($model->hse==1)
             {
            ?>
            var ctx= document.getElementById("myChart3").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: barChartData3,
                options: {
                    elements: {
                        rectangle: {
                            borderWidth: 0,
                            borderColor: '#c1c1c1',
                            borderSkipped: 'bottom',
                        }
                    },
                    legend: { position: 'bottom' },
                    responsive: true,
                    title: {
                        display: true,
                        text: 'Open/Due Issues-Department Wise'
                    }
                }
            });
            <?php
             }
            ?>
            <?php
             if($model->nearmiss==1)
             {
            ?>
            var ctx= document.getElementById("myChart4").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: barChartData4,
                options: {
                    elements: {
                        rectangle: {
                            borderWidth: 0,
                            borderColor: '#c1c1c1',
                            borderSkipped: 'bottom',
                        }
                    },
                    legend: { position: 'bottom' },
                    responsive: true,
                    title: {
                        display: true,
                        text: 'Open/Due Issues-Department Wise'
                    }
                }
            });
          <?php
             }
          ?>
          <?php
           if(($model->auditmanagement==1)||($model->auditschedulelogs==1))
           {
          ?>
            var ctx= document.getElementById("myChart5").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'line',
                data: barChartData5,
                options: {
                    legend: {
                            display: true,
                            position: 'bottom'
                            },
                    plugins: {
                        filler: {
                            propagate: false,
                        },
                        title: {
                            display: true,
                            text: 'Yearly Trend'
                        }
                        },
                        scales: {
                            xAxes : [ {
                                    gridLines : {
                                        display : false
                                    },
                                } ],
                            yAxes: [{

                                    gridLines: {
                                        drawBorder: false
                                        },
                                }],
                            },
                        interaction: {
                        intersect: false,
                        }
                    },
            });
          <?php
           }
          ?>
          <?php
           if($model->correctiveaction==1)
           {
          ?>
            var ctx= document.getElementById("myChart6").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'line',
                data: barChartData6,
                options: {
                    legend: {
                            display: true,
                            position: 'bottom'
                            },
                    plugins: {
                        filler: {
                            propagate: false,
                        },
                        title: {
                            display: true,
                            text: 'Yearly Trend'
                        }
                        },
                        scales: {
                            xAxes : [ {
                                    gridLines : {
                                        display : false
                                    },
                                } ],
                            yAxes: [{

                                    gridLines: {
                                        drawBorder: false
                                        },
                                }],
                            },
                        interaction: {
                        intersect: false,
                        }
                    },
            });
            <?php
            }
            ?>
            <?php
             if($model->hse==1)
             {
            ?>
            var ctx= document.getElementById("myChart7").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'line',
                data: barChartData7,
                options: {
                    legend: {
                            display: true,
                            position: 'bottom'
                            },
                    plugins: {
                        filler: {
                            propagate: false,
                        },
                        title: {
                            display: true,
                            text: 'Yearly Trend'
                        }
                        },
                        scales: {
                            xAxes : [ {
                                    gridLines : {
                                        display : false
                                    },
                                } ],
                            yAxes: [{

                                    gridLines: {
                                        drawBorder: false
                                        },
                                }],
                            },
                        interaction: {
                        intersect: false,
                        }
                    },
            });
         <?php
          }
         ?>
         <?php
          if($model->nearmiss==1)
          {
         ?>
            var ctx= document.getElementById("myChart8").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'line',
                data: barChartData8,
                options: {
                    legend: {
                            display: true,
                            position: 'bottom'
                            },
                    plugins: {
                        filler: {
                            propagate: false,
                        },
                        title: {
                            display: true,
                            text: 'Yearly Trend'
                        }
                        },
                        scales: {
                            xAxes : [ {
                                    gridLines : {
                                        display : false
                                    },
                                } ],
                            yAxes: [{

                                    gridLines: {
                                        drawBorder: false
                                        },
                                }],
                            },
                        interaction: {
                        intersect: false,
                        }
                    },
            });
          <?php
           }
          ?>
          <?php
           if(($model->auditmanagement==1)||($model->auditschedulelogs==1))
           {
          ?>
            var ctx1= document.getElementById("myChart9").getContext("2d");
            window.myBar = new Chart(ctx1, {
                type: 'line',
                data: barChartData9,
                options: {
                    legend: {
                            display: true,
                            position: 'bottom'
                            },
                    plugins: {
                        filler: {
                            propagate: false,
                        },
                        title: {
                            display: true,
                            text: 'Monthly Trend'
                        }
                        },
                        scales: {
                            xAxes : [ {
                                    gridLines : {
                                        display : false
                                    },
                                } ],
                            yAxes: [{

                                    gridLines: {
                                        drawBorder: false
                                        },
                                }],
                            },
                        interaction: {
                        intersect: false,
                        }
                    },
            });
          <?php
           }
           ?>
           <?php
           if($model->correctiveaction==1)
           {
           ?>
            var ctx1= document.getElementById("myChart10").getContext("2d");
            window.myBar = new Chart(ctx1, {
                type: 'line',
                data: barChartData10,
                options: {
                    legend: {
                            display: true,
                            position: 'bottom'
                            },
                    plugins: {
                        filler: {
                            propagate: false,
                        },
                        title: {
                            display: true,
                            text: 'Monthly Trend'
                        }
                        },
                        scales: {
                            xAxes : [ {
                                    gridLines : {
                                        display : false
                                    },
                                } ],
                            yAxes: [{

                                    gridLines: {
                                        drawBorder: false
                                        },
                                }],
                            },
                        interaction: {
                        intersect: false,
                        }
                    },
            });
        <?php
           }
        ?>
        <?php
         if($model->hse==1)
         {
        ?>
            var ctx1= document.getElementById("myChart11").getContext("2d");
            window.myBar = new Chart(ctx1, {
                type: 'line',
                data: barChartData11,
                options: {
                    legend: {
                            display: true,
                            position: 'bottom'
                            },
                    plugins: {
                        filler: {
                            propagate: false,
                        },
                        title: {
                            display: true,
                            text: 'Monthly Trend'
                        }
                        },
                        scales: {
                            xAxes : [ {
                                    gridLines : {
                                        display : false
                                    },
                                } ],
                            yAxes: [{

                                    gridLines: {
                                        drawBorder: false
                                        },
                                }],
                            },
                        interaction: {
                        intersect: false,
                        }
                    },
            });
        <?php
         }
        ?>
        <?php
         if($model->nearmiss==1)
         {
        ?>
            var ctx1= document.getElementById("myChart12").getContext("2d");
            window.myBar = new Chart(ctx1, {
                type: 'line',
                data: barChartData12,
                options: {
                    legend: {
                            display: true,
                            position: 'bottom'
                            },
                    plugins: {
                        filler: {
                            propagate: false,
                        },
                        title: {
                            display: true,
                            text: 'Monthly Trend'
                        }
                        },
                        scales: {
                            xAxes : [ {
                                    gridLines : {
                                        display : false
                                    },
                                } ],
                            yAxes: [{

                                    gridLines: {
                                        drawBorder: false
                                        },
                                }],
                            },
                        interaction: {
                        intersect: false,
                        }
                    },
            });
         <?php
         }
         ?>
         <?php
          if($model->riskmanagement==1)
          {
         ?>
            var ctx = document.getElementById("myChart13").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: barChartData13,
                options: {
                    plugins: {
                            title: {
                                    display: true,
                                    text: 'Chart.js Bar Chart - Stacked'
                                },
                              },
                    responsive: true,
                    interaction: {
                        intersect: false,
                        },
                    scales: {
                              x: {
                                    stacked: true,
                                },
                                y: {
                                    stacked: true
                                }
                            },
                            legend: { position: 'bottom' },
                    title: {
                        display: true,
                        text: 'High/Moderate/Low Risks-Department Wise'
                    }
                }
            });
         <?php
          }
         ?>
         <?php
          if($model->opportunity==1)
          {
         ?>
            var ctx = document.getElementById("myChart14").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: barChartData14,
                options: {
                    plugins: {
                            title: {
                                    display: true,
                                    text: 'Chart.js Bar Chart - Stacked'
                                },
                              },
                    responsive: true,
                    interaction: {
                        intersect: false,
                        },
                    scales: {
                              x: {
                                    stacked: true,
                                },
                                y: {
                                    stacked: true
                                }
                            },
                            legend: { position: 'bottom' },
                    title: {
                        display: true,
                        text: 'High/Moderate/Low Opportunities-Department Wise'
                    }
                }
            });
         <?php
          }
         ?>
         <?php
          if($model->compliance==1)
          {
         ?>
            var ctx = document.getElementById("myChart15").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: barChartData15,
                options: {
                    plugins: {
                            title: {
                                    display: true,
                                    text: 'Chart.js Bar Chart - Stacked'
                                },
                              },
                    responsive: true,
                    interaction: {
                        intersect: false,
                        },
                    scales: {
                              x: {
                                    stacked: true,
                                },
                                y: {
                                    stacked: true
                                }
                            },
                            legend: { position: 'bottom' },

                }
            });
         <?php
          }
          ?>
          <?php
          if($model->ohsrisk==1)
          {
          ?>
            var ctx= document.getElementById("myChart16").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: barChartData16,
                options: {
                    elements: {
                        rectangle: {
                            borderWidth: 0,
                            borderColor: '#c1c1c1',
                            borderSkipped: 'bottom',
                        }
                    },
                    scales: {
                        xAxes: [{
                            barPercentage: 0.4
                        }],
                    yAxes: [{

                        ticks: {
                        beginAtZero: true,
                        stepSize: 10,

                        },

                    }]
                    },
                    legend: {
                        display: false
                    },
                    responsive: true,

                }
            });

            var ctx= document.getElementById("myChart17").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: barChartData17,
                options: {
                    elements: {
                        rectangle: {
                            borderWidth: 0,
                            borderColor: '#c1c1c1',
                            borderSkipped: 'bottom',
                        }
                    },
                    scales: {
                        xAxes: [{
                            barPercentage: 0.4
                        }],
                    yAxes: [{

                        ticks: {
                        beginAtZero: true,
                        stepSize: 10,
                        },

                    }]
                    },
                    legend: {
                        display: false
                    },
                    responsive: true,

                }
            });

            var ctx= document.getElementById("myChart18").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'doughnut',
                data: barChartData18,
                options: {
                    legend: { position: 'bottom' },
                    responsive: true,
                    },
            });

            var ctx= document.getElementById("myChart19").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: barChartData19,
                options: {
                    elements: {
                        rectangle: {
                            borderWidth: 0,
                            borderColor: '#c1c1c1',
                            borderSkipped: 'bottom',
                        }
                    },
                    scales: {
                        xAxes: [{
                            barPercentage: 0.4
                        }],
                    yAxes: [{

                        ticks: {
                        beginAtZero: true,
                        stepSize: 10,
                        },

                    }]
                    },
                    legend: {
                        display: false
                    },
                    responsive: true,

                }
            });

            var ctx= document.getElementById("myChart20").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: barChartData20,
                options: {
                    elements: {
                        rectangle: {
                            borderWidth: 0,
                            borderColor: '#c1c1c1',
                            borderSkipped: 'bottom',
                        }
                    },
                    scales: {
                        xAxes: [{
                            barPercentage: 0.6
                        }],
                    yAxes: [{

                        ticks: {
                        beginAtZero: true,
                        stepSize: 10,
                        },

                    }]
                    },
                    legend: {
                        display: false
                    },
                    responsive: true,

                }
            });
         <?php
          }
          ?>
          <?php
           if($model->enviaspects==1)
            {
          ?>
            var ctx= document.getElementById("myChart21").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'doughnut',
                data: barChartData21,
                options: {
                    legend: { position: 'bottom' },
                    responsive: true,
                    },
            });
         <?php
            }
         ?>
         <?php
          if($model->documentmanagement==1)
          {
         ?>
            var ctx= document.getElementById("myChart22").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: barChartData22,
                options: {
                    elements: {
                        rectangle: {
                            borderWidth: 0,
                            borderColor: '#c1c1c1',
                            borderSkipped: 'bottom',
                        }
                    },
                    scales: {
                        xAxes: [{
                            barPercentage: 0.4
                        }],
                    yAxes: [{

                        ticks: {
                        beginAtZero: true,
                        stepSize: 10,
                        },

                    }]
                    },
                    legend: {
                        display: false
                    },
                    responsive: true,

                }
            });
          <?php
          }
          ?>
        };




</script>



</body>

</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript">
                $(document).ready(function() {
                    $('#Department').show();
                    $('#Objectives').hide();
                    $('#Health').hide();
                    $('#Environmental').hide();


                });
                $("#changetable").change(function() {
                    console.log($("#changetable option:selected").val());
                        if ($("#changetable option:selected").val() == 1) {
                            $('#Department').show();
                            $('#Objectives').hide();
                            $('#Health').hide();
                            $('#Environmental').hide();


                            }
                            if ($("#changetable option:selected").val() == 2) {
                            $('#Department').hide();
                            $('#Objectives').show();
                            $('#Health').hide();
                            $('#Environmental').hide();


                            }
                            if ($("#changetable option:selected").val() == 3) {
                            $('#Department').hide();
                            $('#Objectives').hide();
                            $('#Health').show();
                            $('#Environmental').hide();


                            }
                            if ($("#changetable option:selected").val() == 4) {
                            $('#Department').hide();
                            $('#Objectives').hide();
                            $('#Health').hide();
                            $('#Environmental').show();


                            }
                                      });
</script>
@endsection
