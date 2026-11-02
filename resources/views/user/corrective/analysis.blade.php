@extends('layouts.backend')
@section('back')

<button type="button" style="width:34px;" class="btn btn-sm btn-dual mr-2">
    <a href="/user/corrective/corrissues"><i class="fa fa-arrow-left" style="color:black;"></i></a>
</button>

@endsection
@section('css_after')
<style>

.status-red {
color: #af1e1e;
background-color: #f8d4d4;
border-color: #fbeaea;
}

.status-red-light {
color: #e27e7e;
background-color: #f8d4d4;
border-color: #fbeaea;
}

.status-warning {
color: #af6f1e;
background-color: #fbdece;
border-color: #fbf4ea;
}

.status-gray {
color: #737373;
background-color: #e6e6e6;
border-color: #fbf4ea;
}

.status-blue {
color: #25499c;
background-color: #d9e2f6;
border-color: #d9e2f6;
}

.status-success {
color: #176044;
background-color: #e0edcf;
border-color: #bff0dd;
}

.status-success-light {
color: #488a71;
background-color: #e0edcf;
border-color: #bff0dd;
}


.status {
display: inline-block;
padding: 7px 5px 5px 7px;
font-size: 75%;
font-weight: 600;
line-height: 1;
text-align: center;
white-space: nowrap;
vertical-align: baseline;
border-radius: 0.25rem;
transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

</style>
@endsection

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2 text-center text-sm-left">
            <div class="flex-sm-fill">
                <h1 class="h3 font-w700 mb-2">
                  NCR & Corrective Actions Dashboard
                </h1>
                <h2 class="h6 font-w500 text-muted mb-0">
                    Welcome <a class="font-w600" href="javascript:void(0)">{{Auth::user()->name}}</a>, everything looks great.
                </h2>
            </div>
            <div class="mt-3 mt-sm-0 ml-sm-3">
                <button type="button" class="btn btn-sm btn-alt-primary">
                    <i class="fa fa-cog"></i>
                </button>
                <div class="dropdown d-inline-block">
                    <button type="button" class="btn btn-sm btn-alt-primary" id="dropdown-analytics-overview" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-fw fa-calendar-alt"></i>
                        Last 30 days
                        <i class="fa fa-fw fa-angle-down"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right font-size-sm" aria-labelledby="dropdown-analytics-overview">
                        <a class="dropdown-item font-w500" href="javascript:void(0)">This Week</a>
                        <a class="dropdown-item font-w500" href="javascript:void(0)">Previous Week</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item font-w500" href="javascript:void(0)">This Month</a>
                        <a class="dropdown-item font-w500" href="javascript:void(0)">Previous Month</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="row row-deck">
        <div class="col-sm-6 col-xl-3">
            <!-- Messages -->
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                        @if($opencount)
                        <a href="{{ route('corrective.viewlist')}}">
                            <dt class="font-size-h2 font-w700">{{$opencount}}</dt>
                        </a>
                        @else
                        <dt class="font-size-h2 font-w700">{{$opencount}}</dt>
                        @endif
                        <dd class="text-muted mb-0">Total Open</dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                        <i class="fa fa-inbox font-size-h3 text-primary"></i>
                    </div>
                </div>

            </div>
           </div>
        @foreach($types as $t)
        <?php $alltype=DB::table('corrective')->where('type',$t->order)->where('approvement','yes')->where('status','!=','closed') ->get();
        $count=count($alltype);?>
        <div class="col-sm-6 col-xl-3">
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                        @if($count)
                        <a href="{{ route('corrective.viewlist', ['type' => $t->name])}}">
                            <dt class="font-size-h2 font-w700">{{$count}}</dt>
                        </a>
                        @else
                        <dt class="font-size-h2 font-w700">{{$count}}</dt>
                        @endif
                        <dd class="text-muted mb-0">{{$t->name}}</dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                    <i class="fa fa-chart-line font-size-h3 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
<div class="content">
    <div class="row row-deck">
        <div class="col-sm-6 col-xl-3">
            <!-- Messages -->
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                        @if($nmdue)
                        <a href="{{ route('corrective.viewlist', ['type' => 'due'])}}">
                            <dt class="font-size-h2 font-w700">{{$nmdue}}</dt>
                        </a>
                        @else
                        <dt class="font-size-h2 font-w700">{{$nmdue}}</dt>
                        @endif
                        <dd class="text-muted mb-0">Due Corrective </dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                        <i class="fas fa-user-clock" style="color:red;font-size:24px;"></i>
                    </div>
                </div>

            </div>
           </div>

           <div class="col-sm-6 col-xl-3">
            <!-- Messages -->
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                        @if($actions)
                        <a href="{{ route('corrective.viewlist', ['type' => 'awaiting'])}}">
                            <dt class="font-size-h2 font-w700">{{$actions}}</dt>
                        </a>
                        @else
                        <dt class="font-size-h2 font-w700">{{$actions}}</dt>
                        @endif
                        <dd class="text-muted mb-0">Awaiting Actions</dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                        <i class="fas fa-spinner"style="font-size:24px;"></i>
                    </div>
                </div>

            </div>
           </div>

           <div class="col-sm-6 col-xl-3">
            <!-- Messages -->
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                        @if($proposed)
                        <a href="{{ route('corrective.viewlist', ['type' => 'proposed'])}}">
                            <dt class="font-size-h2 font-w700">{{$proposed}}</dt>
                        </a>
                        @else
                        <dt class="font-size-h2 font-w700">{{$proposed}}</dt>
                        @endif
                        <dd class="text-muted mb-0">Awaiting Reviews</dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                        <i class="fas fa-file-signature"style="font-size:24px;"></i>
                    </div>
                </div>

            </div>
           </div>
           <div class="col-sm-6 col-xl-3">
            <!-- Messages -->
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                        @if($awaitingclosure)
                        <a href="{{ route('corrective.viewlist', ['type' => 'closure'])}}">
                            <dt class="font-size-h2 font-w700">{{$awaitingclosure}}</dt>
                        </a>
                        @else
                        <dt class="font-size-h2 font-w700">{{$awaitingclosure}}</dt>
                        @endif
                        <dd class="text-muted mb-0">Awaiting Closure</dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                        <i class="fas fa-circle-notch"style="font-size:24px;"></i>
                    </div>
                </div>

            </div>
           </div>
</div>

<div class="row">
    <div class="col-xl-8 d-flex flex-column">
        <!-- Earnings Summary -->
        <div class="block block-rounded flex-grow-1 d-flex flex-column">
            <div class="block-header block-header-default">
                <h8>Open/Due Issues-Department Wise</h8>
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
               <canvas class="js-chartjs-earnings" id="canvas"></canvas>
            </div>
        </div>

    </div>
    <div class="col-xl-4 d-flex flex-column">
        <div class="row row-deck flex-grow-1">
            <div class="col-md-6 col-xl-12">
                <div class="block block-rounded d-flex flex-column">
                    <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between">
                        <dl class="mb-0">
                            <dt class="font-size-h2 font-w700">$</dt>
                            <dd class="text-muted mb-0">Closure(Two weeks overview)</dd>
                        </dl>
                        <div>
                            <div class="d-inline-block px-2 py-1 rounded-lg font-size-sm font-w600 text-success bg-success-light">
                                <i class="fa fa-caret-up mr-1"></i>
                                4.2%
                            </div>
                        </div>
                    </div>
                    <div class="block-content p-1 text-center overflow-hidden">

                        <span class="js-sparkline" data-type="line"
                              data-points="[716,1185,750,1365,956,890,1200,968,1158,1025,920,1190,720,1352]"
                              data-width="100%"
                              data-height="70px"
                              data-chart-range-min="300"
                              data-line-color="rgba(70,195,123, .4)"
                              data-fill-color="rgba(70,195,123, .15)"
                              data-spot-color="transparent"
                              data-min-spot-color="transparent"
                              data-max-spot-color="transparent"
                              data-highlight-spot-color="#46C37B"
                              data-highlight-line-color="#46C37B"
                              data-tooltip-prefix="$"></span>

                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-12">
                <div class="block block-rounded d-flex flex-column">
                    <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between">
                        <dl class="mb-0">
                            <dt class="font-size-h2 font-w700">$</dt>
                            <dd class="text-muted mb-0">Due (Two weeks overview)</dd>
                        </dl>
                        <div>
                            <div class="d-inline-block px-2 py-1 rounded-lg font-size-sm font-w600 text-success bg-success-light">
                                <i class="fa fa-caret-up mr-1"></i>
                                4.2%
                            </div>
                        </div>
                    </div>
                    <div class="block-content p-1 text-center oveflow-hidden">

                        <span class="js-sparkline" data-type="line"
                              data-points="[716,1185,750,1365,956,890,1200,968,1158,1025,920,1190,720,1352]"
                              data-width="100%"
                              data-height="70px"
                              data-chart-range-min="300"
                              data-line-color="rgba(70,195,123, .4)"
                              data-fill-color="rgba(70,195,123, .15)"
                              data-spot-color="transparent"
                              data-min-spot-color="transparent"
                              data-max-spot-color="transparent"
                              data-highlight-spot-color="#46C37B"
                              data-highlight-line-color="#46C37B"
                              data-tooltip-prefix="$"></span>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
<div class="row">

    <div class="col-xl-8 d-flex flex-column">
        <div class="js-slider" data-dots="true">
            <!-- Earnings Summary -->
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h8>Open {{ $implode_names }} Corrective Issues-Department Wise</h8>
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
                <canvas class="js-chartjs-earnings" id="canvas1"></canvas>
                </div>
            </div>
                <!-- Earnings Summary -->
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h8>Total {{ $implode_names }} Corrective Issues-Department Wise</h8>
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
                <canvas class="js-chartjs-earnings" id="canvas5"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-xl-8 d-flex flex-column">
        <!-- Earnings Summary -->
        <div class="block block-rounded flex-grow-1 d-flex flex-column">
            <div class="block-header block-header-default">
                <h8>Awaiting Actions-Department Wise</h8>
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
               <canvas class="js-chartjs-earnings" id="canvas2"></canvas>
            </div>
        </div>

    </div>

    </div>


<div class="row">
    <div class="col-xl-8 d-flex flex-column">
        <!-- Earnings Summary -->
        <div class="block block-rounded flex-grow-1 d-flex flex-column">
            <div class="block-header block-header-default">
                <h3 class="block-title">Number of Actions with Implementation Progress</h3>
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
               <canvas class="js-chartjs-earnings" id="canvas3"></canvas>
            </div>
        </div>

    </div>
    <div class="col-xl-4 d-flex flex-column">
        <div class="row row-deck flex-grow-1">
            <div class="col-md-6 col-xl-12">
                <div class="block block-rounded d-flex flex-column">
                    <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between">
                        <dl class="mb-0">
                            <dt class="font-size-h2 font-w700">{{$zerocount}}</dt>
                            <dd class="text-muted mb-0">Total Actions in Zero Progress</dd>
                        </dl>
                        <div class="item item-rounded bg-body">
                            <i class="fa fa-inbox font-size-h3 text-primary"></i>
                        </div>

                    </div>

                </div>
            </div>
            <div class="col-md-6 col-xl-12">
                <div class="block block-rounded d-flex flex-column">
                    <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between">
                        <dl class="mb-0">
                            <dt class="font-size-h2 font-w700">{{$fivecount}}</dt>
                            <dd class="text-muted mb-0">Total Actions in 50% Progress</dd>
                        </dl>
                        <div class="item item-rounded bg-body">
                            <i class="fa fa-inbox font-size-h3 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-12">
                <div class="block block-rounded d-flex flex-column">
                    <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between">
                        <dl class="mb-0">
                            <dt class="font-size-h2 font-w700">{{$hundredcount}}</dt>
                            <dd class="text-muted mb-0">Total Actions in 100% Progress</dd>
                        </dl>
                        <div class="item item-rounded bg-body">
                            <i class="fa fa-inbox font-size-h3 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>

    </div>
</div>
</div>




<div class="row">
    <div class="col-xl-8 d-flex flex-column">
        <!-- Earnings Summary -->
        <div class="block block-rounded flex-grow-1 d-flex flex-column">
            <div class="block-header block-header-default">
                <h3 class="block-title">Closed Issues Per Department</h3>
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
               <canvas class="js-chartjs-earnings" id="canvas4"></canvas>
            </div>
        </div>

    </div>
    <div class="col-xl-4 d-flex flex-column">
        <div class="row row-deck flex-grow-1">
            <div class="col-md-6 col-xl-12">
                <div class="block block-rounded d-flex flex-column">
                    <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between">
                        <dl class="mb-0">
                            <dt class="font-size-h2 font-w700">{{$closecount}}</dt>
                            <dd class="text-muted mb-0">Total Closed</dd>
                        </dl>
                        <div class="item item-rounded bg-body">
                            <i class="fa fa-inbox font-size-h3 text-primary"></i>
                        </div>

                    </div>

                </div>
            </div>
            <div class="col-md-6 col-xl-12">
                <div class="block block-rounded d-flex flex-column">
                    <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between">
                        <dl class="mb-0">
                            <dt class="font-size-h2 font-w700">{{$beforecount}}</dt>
                            <dd class="text-muted mb-0">Closed before due</dd>
                        </dl>
                        <div class="item item-rounded bg-body">
                            <i class="fa fa-inbox font-size-h3 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-12">
                <div class="block block-rounded d-flex flex-column">
                    <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between">
                        <dl class="mb-0">
                            <dt class="font-size-h2 font-w700">{{$aftercount}}</dt>
                            <dd class="text-muted mb-0">Closed after due</dd>
                        </dl>
                        <div class="item item-rounded bg-body">
                            <i class="fa fa-inbox font-size-h3 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>

    </div>
</div>
</div>
<div class="row">
    <div class="col-xl-6 d-flex flex-column">
        <div class="block block-rounded flex-grow-1 d-flex flex-column">
            <div class="block-header block-header-default">
                <h8>Yearly Trend(Total Issues)</h8>
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
               <canvas class="js-chartjs-earnings" id="canvas6"></canvas>
            </div>
        </div>

    </div>
    <div class="col-xl-6 d-flex flex-column">
    <div class="block block-rounded flex-grow-1 d-flex flex-column">
        <div class="block-header block-header-default">
            <h8>Monthly Trend (Total Issues)</h8>
            <div class="col-lg-8 col-xl-4" >
                <!-- <div class="form-group"> -->
                    <!-- <label for="monthlytrendyear" style="font-weight:normal;font-size:10px;">Which year to show?</label><br> -->
                    <select class="form-control" id="monthlytrendyear" name="monthlytrendyear" onchange="updateChart()" style="font-size:10px;">
                        <option value="">Select Year</option>
                        <option value="1">Current year</option>
                        <option value="2">Last year</option>
                        <option value="3">Two years back</option>
                        <option value="4">Three years back</option>
                    </select>
                <!-- </div> -->
            </div>
            <!-- <button class="btn btn-success" onclick="updateChart()">Update</button> -->
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

           <canvas class="js-chartjs-earnings" id="canvas7"></canvas>
        </div>
    </div>

</div>
</div>


<div class="row">
    <div class="col-xl-6 d-flex flex-column">
        <div class="block block-rounded flex-grow-1 d-flex flex-column">
            <div class="block-header block-header-default">
                <h8>Monthly Trend (Total Closure)</h8>
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
               <canvas class="js-chartjs-earnings" id="canvas8"></canvas>
            </div>
        </div>

    </div>
    <div class="col-xl-6 d-flex flex-column">
        <div class="block block-rounded" >
            <div class="block-header block-header-default">
                <h8>Latest Actions</h8>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                        <i class="si si-refresh"></i>
                    </button>
                </div>
            </div>
            <div class="block-content">
                <table class="table table-borderless table-striped table-vcenter font-size-sm">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Issue ID</th>
                            <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Department</th>
                            <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Process Stage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $dat)
                        <?php
                        $dept = DB::table('departments')->where('id',$dat->department)->value('departmentname'); ?>
                      <tr>
                            <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                                <a href=""style="font-size:10px" >{{$dat->issueid}}</a>
                            </td>
                            <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                                <a href=""style="font-size:10px" >{{$dept}}</a>
                            </td>
                            <td class="font-w600 text-center" style="border: 2px solid #e1e6e9;">
                                @if($dat->processstage == 'Accomplished')
                                <span class="status status-success"style="font-size:10px">{{$dat->processstage}}</span>
                                @elseif($dat->processstage == 'Correction Proposed- RC Awaiting' || $dat->processstage == 'Actions Proposed- Review Awaiting' || $dat->processstage == 'RC Updated- Correction Awaiting')

                                <span class="status status-blue"style="font-size:10px">{{$dat->processstage}}</span>
                                @elseif($dat->processstage== 'Need More Action')
                                <span class="status status-red"style="font-size:10px">{{$dat->processstage}}</span>
                                @elseif($dat->processstage == 'Action Reviewed, Not OK')
                                <span class="status status-red" style="font-size:10px">Action Rejected</span>
                                @elseif($dat->processstage=='Action Reviewed, OK')
                                <span class="status status-warning"style="font-size:10px">Action Approved</span>
                                {{-- @elseif($dat->processstage=='Approved')
                                <span class="status status-success"style="font-size:10px">Approved</span> --}}
                                 @elseif($dat->processstage == 'Actions Completed')
                                <span class="status status-blue"style="font-size:10px">{{$dat->processstage}}</span>
                                @endif
                            </td>

                        </tr>

                       @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="block block-rounded" >

    <div class="block-content">
        <table class="table table-borderless table-striped table-vcenter font-size-sm">
            <thead class="thead-light">
                <tr>
                    <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Department</th>
                   <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Closure(This week)</th>
                    <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Closure(Last week)</th>
                    <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Closure(Last Month)</th>
                    <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Closure(This Month)</th>
                    <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Total Issues</th>
                    <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Total Closed</th>
                    <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Total Closed Before Due</th>
                    <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Total Closed After Due</th>
                    <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Total Due</th>
                    <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Total Open</th>
                    <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Total Closure %</th>
                    <th class="text-center" style="font-size:10px;width: 50px;border: 2px solid #e1e6e9;position:sticky;top:-1;text-transform:capitalize">Total Timely Closure %</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($nmdepts as $nmdept)
                <?php
                $dept = DB::table('departments')->where('id',$nmdept)->value('departmentname');
                $Dates=DB::table('corrective')->where('department',$nmdept)->where('status','closed') ->pluck('updated_at');
                    $current_week = strtotime("today");
                    $start_week = strtotime("last sunday midnight",$current_week);
                    $end_week = strtotime("next saturday",$current_week);
                    $start = date("Y-m-d",$start_week);
                    $end = date("Y-m-d",$end_week);

                    $thisweekcount=0;
                    foreach ($Dates as $D) {
                        $t = date('Y-m-d',strtotime($D));
                        if($t >= $start && $t <= $end) {
                            $thisweekcount++;
                        }
                    }
                    $previous_week = strtotime("-1 week +1 day");
                    $pstart_week = strtotime("last sunday midnight",$previous_week);
                    $pend_week = strtotime("next saturday",$pstart_week);
                    $pstart_week = date("Y-m-d",$pstart_week);
                    $pend_week = date("Y-m-d",$pend_week);
                    $lastweekcount=0;
                    foreach ($Dates as $D) {
                        $t = date('Y-m-d',strtotime($D));
                        if($t >= $pstart_week && $t <= $pend_week) {
                            $lastweekcount++;
                        }
                    }
                    $currentmonth =date('M');
                    $prev_month = date('M', strtotime("last month"));
                    $currentyear=date('Y');
                    $updatedat=DB::table('corrective')->where('department',$nmdept)->where('status','closed')->where('year',$currentyear)->pluck('updated_at');
                    $mm=[];
                    $thiscount=0;
                    foreach($updatedat as $key){
                        $mm=date('M',strtotime($key));
                        if($currentmonth==$mm){
                                $thiscount++;
                            }
                        }
                    $lastcount=0;
                    foreach($updatedat as $key){
                        $mm=date('M',strtotime($key));
                        if($prev_month==$mm){
                                $lastcount++;
                            }
                        }
                        $totalissues=DB::table('corrective')->where('department',$nmdept)->get();
                        $totalissuescount=count($totalissues);
                        $totalclosed=DB::table('corrective')->where('department',$nmdept)->where('status','closed')->get();
                        $totalclosedcount=count($totalclosed);
                        $closebefore=DB::select("select COUNT(duedate)AS number
                                                FROM  corrective where department = '$nmdept'
                                                and status='closed'
                                                and closed <= duedate
                                            ");


                        $closeafter=DB::select("select COUNT(duedate)AS number
                                                FROM  corrective where department = '$nmdept'
                                                and status='closed'
                                                and closed > duedate
                                        ");
                        $totalbeforecount=[];
                        foreach($closebefore as $key){ array_push($totalbeforecount,$key->number); }
                        $totalbeforecount=implode(',',$totalbeforecount);
                        $totalaftercount=[];
                        foreach($closeafter as $key){ array_push($totalaftercount,$key->number); }
                        $totalaftercount=implode(',',$totalaftercount);
                        $today=date('Y-m-d');
                        $totaldue=DB::table('corrective')->where('department',$nmdept)->where('status','open')->where('duedate' ,'<=', $today)->get();
                        $totalduecount=count($totaldue);
                        $totalopen=DB::table('corrective')->where('department',$nmdept)->where('status','open')->get();
                        $totalopencount=count($totalopen);
                        if($totalissuescount != 0)
                        {
                        $tcpercentage=round(($totalclosedcount/$totalissuescount)*100);
                        // $tcpercentage== round($tcpercentage, 1);
                        }
                        else
                        {
                            $tcpercentage=0;
                        }
                        if($totalclosedcount != 0)
                        {
                        $ttpercentage=round(($totalbeforecount/$totalclosedcount)*100);
                        }
                        else
                        {
                            $ttpercentage=0;
                        }
                    ?>
             <tr>
                <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                    <a href=""style="font-size:10px" >{{$dept}}</a>
                </td>
                <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                    <a href=""style="font-size:10px" >{{$thisweekcount}}</a>
                </td>
                <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                    <a href=""style="font-size:10px" >{{$lastweekcount}}</a>
                </td>
                <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                    <a href=""style="font-size:10px" >{{$lastcount}}</a>
                </td>
                <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                    <a href=""style="font-size:10px" >{{$thiscount}}</a>
                </td>
                <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                    <a href=""style="font-size:10px" >{{$totalissuescount}}</a>
                </td>
                <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                    <a href=""style="font-size:10px" >{{$totalclosedcount}}</a>
                </td>
                <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                    <a href=""style="font-size:10px" >{{$totalbeforecount}}</a>
                </td>
                <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                    <a href=""style="font-size:10px" >{{$totalaftercount}}</a>
                </td>
                <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                    <a href=""style="font-size:10px" >{{$totalduecount}}</a>
                </td>
                <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                    <a href=""style="font-size:10px" >{{$totalopencount}}</a>
                </td>
                <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                    <a class="px-2 py-1 rounded bg-body-dark" style="font-size:10px" >{{$tcpercentage}}%</a>
                </td>
                <td class="font-w600 text-center"style="border: 2px solid #e1e6e9;">
                    <a class=" px-2 py-1 rounded bg-body-dark" style="font-size:10px" >{{$ttpercentage}}%</a>
                </td>
            </tr>

               @endforeach
            </tbody>
        </table>
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
                                <!-- Products -->
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/corrective/corrissues">
                                    <div class="block-content text-center" style="height:130px">
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
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/corrective/closedissue">
                                    <div class="block-content text-center" style="height:130px">
                                    <i class="fas fa-check-square fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                           Closed Issues
                                        </p>
                                    </div>
                                </a>
                                <!-- END Sales -->
                            </div>
                            <div class="col-6">
                                <!-- Sales -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/corrective/openissue">
                                    <div class="block-content text-center" style="height:130px">
                                    <i class="fas fa-clone fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            My Open Issues
                                        </p>
                                    </div>
                                </a>
                                <!-- END Sales -->
                            </div>
                                    <div class="col-6">
                                        <!-- Sales -->
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/corrective/analysis">
                                            <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-clone fa-2x text-primary"></i>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
    <script>
        var process = <?php echo $process; ?>;
        var depcode = <?php echo $depcode; ?>;
        var dopen = <?php echo $dopen; ?>;
        var due = <?php echo $due; ?>;
        var dclose = <?php echo $dclose; ?>;
        var awaiting = <?php echo $awaiting; ?>;
        var trend = <?php echo $trend; ?>;
        var year = <?php echo $year; ?>;
        var highd = <?php echo $highd; ?>;
        var moderated = <?php echo $moderated; ?>;
        var lowd = <?php echo $lowd; ?>;
        var hightotal = <?php echo $hightotal; ?>;
        var moderatetotal = <?php echo $moderatetotal; ?>;
        var lowtotal = <?php echo $lowtotal; ?>;
        var zero = <?php echo $zero; ?>;
        var two = <?php echo $two; ?>;
        var five = <?php echo $five; ?>;
        var hundred = <?php echo $hundred; ?>;
        var before = <?php echo $before; ?>;
        var after = <?php echo $after; ?>;
        var monthtrend = <?php echo $monthtrend; ?>;
        var closuretrend = <?php echo $closuretrend; ?>;
        var closuretrendlast = <?php echo $closuretrendlast; ?>;
        var closuretrendtwo = <?php echo $closuretrendtwo; ?>;
        var closuretrendthree = <?php echo $closuretrendthree; ?>;
        var months = ["Jan","Feb","Mar","Apr","May","Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        let implode_names = '{{ $implode_names }}';

        var barChartData= {
            labels: depcode,
            datasets: [{
                label: 'Awaiting Actions',
                backgroundColor: "#003366",
                data: awaiting

            }]
        };
        var barChartData1 = {
        labels: depcode,
        datasets: [{
            label: 'Open',
            backgroundColor: "#003366",
            data: dopen
        },
        {
            label: 'Due',
            backgroundColor: "#ff0000",
            data: due

        }]
    };
    var barChartData2 = {
            labels: depcode,
            datasets: [{
                label: '{{ $types[0]->name }}',
                backgroundColor: '{{ $types[0]->background_color }}',
                stack: 'Stack 0',
                data: highd
            },

            {
                label: '{{ $types[1]->name }}',
                backgroundColor: "{{ $types[1]->background_color }}",
                data: moderated,
                stack: 'Stack 0'
            },

            {
                label: '{{ $types[2]->name }}',
                backgroundColor: "{{ $types[2]->background_color }}",
                data: lowd,
                stack: 'Stack 0'
            }]
        };
        var barChartData3 = {
            labels: depcode,
            datasets: [{
                label: 'Zero Progress',
                backgroundColor: "#b3d9ff",
                stack: 'Stack 0',
                data: zero
            },

            {
                label: '25% Progress',
                backgroundColor: "#0099CC",
                data: two,
                stack: 'Stack 0'
            },
            {
                label: '50% Progress',
                backgroundColor: "#006080",
                data: five,
                stack: 'Stack 0'
            },
            {
                label: '100% Progress',
                backgroundColor: "#013647",
                data: hundred,
                stack: 'Stack 0'
            }]
        };
        var barChartData4 = {
            labels: depcode,
            datasets: [{
                label: 'Closed Before Due',
                backgroundColor: "#00cc99",
                stack: 'Stack 0',
                data: before
            },


            {
                label: 'Closed After Due',
                backgroundColor: "#fff0b9",
                data: after,
                stack: 'Stack 0'
            }]
        };
        var barChartData5 = {
            labels: depcode,
            datasets: [{
                label: '{{ $types[0]->name }}',
                backgroundColor: '{{ $types[0]->background_color }}',
                stack: 'Stack 0',
                data: hightotal
            },

            {
                label: '{{ $types[1]->name }}',
                backgroundColor: '{{ $types[1]->background_color }}',
                data: moderatetotal,
                stack: 'Stack 0'
            },

            {
                label: '{{ $types[2]->name }}',
                backgroundColor: '{{ $types[2]->background_color }}',
                data: lowtotal,
                stack: 'Stack 0'
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
                data: trend

            }]
        };
        var barChartData7= {
            labels: months ,
            datasets: [{
                label: 'Total Issues',
                fill: false,
                lineTension: 0,
                backgroundColor: "rgb(88,214,141)",
                borderColor: "rgb(88,214,141)",
                data: monthtrend

            }]
        };
        var barChartData8= {
            labels: months ,
            datasets: [{
                label: 'Total Closure',
                fill: false,
                lineTension: 0,
                backgroundColor: "rgb(46,134,193)",
                borderColor: "rgb(46,134,193)",
                data: closuretrend

            }]
        };
        var barChartDataLast={
            labels: months ,
            datasets: [{
                label: 'Total Issues Last Year',
                fill: false,
                lineTension: 0,
                backgroundColor: "rgb(46,134,193)",
                borderColor: "rgb(46,134,193)",
                data: closuretrendlast

            }]
        };

        var dataTwo = {
            labels: months ,
            datasets: [{
                label: 'Total Issues Two Years Back',
                fill: false,
                lineTension: 0,
                backgroundColor: "rgb(46,134,193)",
                borderColor: "rgb(46,134,193)",
                data: closuretrendtwo

            }]
        };
        var dataThree = {
            labels: months ,
            datasets: [{
                label: 'Total Issues Three Years Back',
                fill: false,
                lineTension: 0,
                backgroundColor: "rgb(46,134,193)",
                borderColor: "rgb(46,134,193)",
                data: closuretrendthree

            }]
        };
        window.onload = function() {
            var ctx= document.getElementById("canvas").getContext("2d");
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
        };
        var ctx = document.getElementById("canvas1").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: barChartData2,
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
                        text: 'Open' + implode_names + 'Near Miss Issues-Department Wise'
                    }
                }
            });
            var ctx = document.getElementById("canvas5").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: barChartData5,
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
                                // yAxes: [
                                //         {
                                //             ticks: {
                                //             userCallback(label, index, labels) {
                                //                if (Math.floor(label) === label) {
                                //                     return label;
                                //                 }
                                //             },
                                //             }
                                //         }
                                //         ],
                            },
                            legend: { position: 'bottom' },
                    title: {
                        display: true,
                        text: 'Total' + implode_names + 'Near Miss Issues-Department Wise'
                    }
                }
            });
            var ctx= document.getElementById("canvas2").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: barChartData,
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
                        text: 'Awaiting Actions-Department Wise'
                    }
                }
            });



            var ctx = document.getElementById("canvas3").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: barChartData3,
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
                        text: 'Number of Actions with Implementation Progress'
                    }
                }
            });
            var ctx = document.getElementById("canvas4").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: barChartData4,
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
                        text: 'Closed Issues Per Department'
                    }
                }
            });
            var ctx= document.getElementById("canvas6").getContext("2d");
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
            var ctx1= document.getElementById("canvas7").getContext("2d");
            var chartFilter = new Chart(ctx1, {
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
            var ctx= document.getElementById("canvas8").getContext("2d");
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
                            text: 'Monthly Trend(Closure Trend)'
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
            //dropdown filter
            function updateChart() {
                chartFilter.destroy();
             //current year
             if(document.getElementById("monthlytrendyear").value==1){
                chartFilter = new Chart(ctx1, {
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

                   //last year
             }else if(document.getElementById("monthlytrendyear").value==2){
                chartFilter = new Chart(ctx1, {
                    type: 'line',
                data: barChartDataLast,
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
            //two years back
               }else if(document.getElementById("monthlytrendyear").value==3){
                chartFilter = new Chart(ctx1, {
                    type: 'line',
                data: dataTwo,
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
            //3 years back
               }else if(document.getElementById("monthlytrendyear").value==4){
                chartFilter = new Chart(ctx1, {
                    type: 'line',
                data: dataThree,
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
               }
               chart.update();
            };
    </script>

    @endsection
