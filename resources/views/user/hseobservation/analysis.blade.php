@extends('layouts.backend')
@section('back')

<button type="button" style="width:34px;" class="btn btn-sm btn-dual mr-2">
    <a href="/user/hseobservation/issues"><i class="fa fa-arrow-left" style="color:black;"></i></a>
</button>

@endsection

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2 text-center text-sm-left">
            <div class="flex-sm-fill">
                <h1 class="h3 font-w700 mb-2">
                    HSE Observations Dashboard
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
                        <a href="{{ route('hseobservation.index')}}">
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
        @foreach($names as $key=>$value)
        @php 
            $count=DB::table('hseissues')->where('type',$key)->where('approvement','yes')->where('status','!=','closed') ->get('id')->count();
        @endphp
        <div class="col-sm-6 col-xl-3">
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                        @if($count>0)
                        <a href="{{ route('hseobservation.index', ['type' => $value])}}">
                            <dt class="font-size-h2 font-w700">{{$count}}</dt>
                        </a>
                        @else
                        <dt class="font-size-h2 font-w700">{{$count}}</dt>
                        @endif
                        <dd class="text-muted mb-0">{{$value}}</dd>
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
                        <a href="{{ route('hseobservation.index', ['type' => 'due'])}}">
                            <dt class="font-size-h2 font-w700">{{$nmdue}}</dt>
                        </a>
                        @else
                        <dt class="font-size-h2 font-w700">{{$nmdue}}</dt>
                        @endif
                        <dd class="text-muted mb-0">Observations Due</dd>
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
                        <a href="{{ route('hseobservation.index', ['type' => 'awaiting'])}}">
                            <dt class="font-size-h2 font-w700">{{$actions}}</dt>
                        </a>
                        @else
                        <dt class="font-size-h2 font-w700">{{$actions}}</dt>
                        @endif
                        <dd class="text-muted mb-0">Awaiting Actions</dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                        <i class="fas fa-spinner" style="font-size:24px;"></i>
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
                        <a href="{{ route('hseobservation.index', ['type' => 'proposed'])}}">
                            <dt class="font-size-h2 font-w700">{{$proposed}}</dt>
                        </a>
                        @else
                        <dt class="font-size-h2 font-w700">{{$proposed}}</dt>
                        @endif
                        <dd class="text-muted mb-0">Awaiting Reviews</dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                        <i class="fas fa-file-signature" style="font-size:24px;"></i>
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
                        <a href="{{ route('hseobservation.index', ['type' => 'closure'])}}">
                            <dt class="font-size-h2 font-w700">{{$awaitingclosure}}</dt>
                        </a>
                        @else
                        <dt class="font-size-h2 font-w700">{{$awaitingclosure}}</dt>
                        @endif
                        <dd class="text-muted mb-0">Awaiting Closure</dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                        <i class="fas fa-circle-notch" style="font-size:24px;"></i>
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
                <div class="block-content block-content-full flex-grow-1 d-flex align-items-center adjust-graph">
                    <canvas class="js-chartjs-earnings" style="height:350px;" id="canvas"></canvas>
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

                            <span class="js-sparkline" data-type="line" data-points="[716,1185,750,1365,956,890,1200,968,1158,1025,920,1190,720,1352]" data-width="100%" data-height="70px" data-chart-range-min="300" data-line-color="rgba(70,195,123, .4)" data-fill-color="rgba(70,195,123, .15)" data-spot-color="transparent" data-min-spot-color="transparent" data-max-spot-color="transparent" data-highlight-spot-color="#46C37B" data-highlight-line-color="#46C37B" data-tooltip-prefix="$"></span>

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

                            <span class="js-sparkline" data-type="line" data-points="[716,1185,750,1365,956,890,1200,968,1158,1025,920,1190,720,1352]" data-width="100%" data-height="70px" data-chart-range-min="300" data-line-color="rgba(70,195,123, .4)" data-fill-color="rgba(70,195,123, .15)" data-spot-color="transparent" data-min-spot-color="transparent" data-max-spot-color="transparent" data-highlight-spot-color="#46C37B" data-highlight-line-color="#46C37B" data-tooltip-prefix="$"></span>
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
                        <h8>Open High/Medium/Low HSE Observations Issues-Department Wise</h8>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                                <i class="si si-refresh"></i>
                            </button>
                            <button type="button" class="btn-block-option">
                                <i class="si si-settings"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content block-content-full flex-grow-1 d-flex align-items-center adjust-graph">
                        <canvas class="js-chartjs-earnings" style="height:350px;" id="canvas1"></canvas>
                    </div>
                </div>
                <!-- Earnings Summary -->
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h8>Total High/Medium/Low HSE Observations Issues-Department Wise</h8>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                                <i class="si si-refresh"></i>
                            </button>
                            <button type="button" class="btn-block-option">
                                <i class="si si-settings"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content block-content-full flex-grow-1 d-flex align-items-center adjust-graph">
                        <canvas class="js-chartjs-earnings" style="height:350px;" id="canvas5"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-xl-8 d-flex flex-column">
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
                <div class="block-content block-content-full flex-grow-1 d-flex align-items-center adjust-graph">
                    <canvas class="js-chartjs-earnings" style="height:350px;" id="canvas2"></canvas>
                </div>
            </div>

        </div>

    </div>


    <div class="row">
        <div class="col-xl-8 d-flex flex-column" style="height:500px !important;">
            <!-- Earnings Summary -->
            <div class="block block-rounded flex-grow-1 d-flex flex-column">
                <div class="block-header block-header-default">
                    <h8>Number of Actions with Implementation Progress</h8>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                            <i class="si si-refresh"></i>
                        </button>
                        <button type="button" class="btn-block-option">
                            <i class="si si-settings"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content block-content-full flex-grow-1 d-flex align-items-center adjust-graph">
                    <canvas class="js-chartjs-earnings" style="height:450px;" id="canvas3"></canvas>
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
                                <dd class="text-muted mb-0">Total Actions in 0% Progress</dd>
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
                                <dt class="font-size-h2 font-w700">{{$tencount}}</dt>
                                <dd class="text-muted mb-0">Total Actions in 10% Progress</dd>
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
                                <dt class="font-size-h2 font-w700">{{$twocount}}</dt>
                                <dd class="text-muted mb-0">Total Actions in 25% Progress</dd>
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
                    <h8>Closed Issues Per Department</h8>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                            <i class="si si-refresh"></i>
                        </button>
                        <button type="button" class="btn-block-option">
                            <i class="si si-settings"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content block-content-full flex-grow-1 d-flex align-items-center adjust-graph">
                    <canvas class="js-chartjs-earnings" style="height:400px;" id="canvas4"></canvas>
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
                                <dt class="font-size-h2 font-w700">{{$closebeforecount}}</dt>
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
                                <dt class="font-size-h2 font-w700">{{$closeaftercount}}</dt>
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
                    <div class="col-lg-8 col-xl-4">
                        <select class="form-control" id="monthlytrendyear" name="monthlytrendyear" onchange="updateChart()" style="font-size:10px;">
                            <option value="1">Select Year</option>
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
    </div>

    <div class="col-xl-6 d-flex flex-column">
        <div class="block block-rounded">
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
                        <tr>
                            <td class="font-w600 text-center" style="border: 2px solid #e1e6e9;">
                                <a href="#" style="font-size:10px">{{$dat->issueid}}</a>
                            </td>
                            <td class="font-w600 text-center" style="border: 2px solid #e1e6e9;">
                                <a href="#" style="font-size:10px">{{$dat->departmentHse?$dat->departmentHse->departmentname:$dat->department}}</a>
                            </td>
                            <td class="font-w600 text-center" style="border: 2px solid #e1e6e9;">
                                @if($dat->processstage == 'Accomplished' || $dat->processstage == 'Closed')
                                <span class="badge badge-success" style="font-size:10px">Accomplished</span>
                                @elseif($dat->processstage == 'Actions Proposed')
                                <span class="badge badge-warning" style="font-size:10px">{{$dat->processstage}}</span>
                                @elseif($dat->processstage== 'Need More Action')
                                <span class="badge badge-danger" style="font-size:10px">{{$dat->processstage}}</span>
                                @elseif($dat->processstage== 'Action Reviewed, Not OK')
                                <span class="badge badge-danger" style="font-size:10px">Action Rejected</span>
                                @elseif($dat->processstage=='Action Reviewed, OK')
                                <span class="badge badge-warning" style="font-size:10px">Action Approved</span>
                                @elseif($dat->processstage == 'Actions Completed')
                                <span class="badge badge-primary" style="font-size:10px">{{$dat->processstage}}</span>
                                @elseif($dat->processstage=='Awaiting actions')
                                <span class="badge badge-secondary">{{$dat->processstage}}</span>
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
<div class="block block-rounded">
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
                    $Dates=DB::table('hseissues')->where('department',$nmdept)->where('status','closed') ->pluck('updated_at');
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
                    $updatedat=DB::table('hseissues')->where('department',$nmdept)->where('status','closed')->where('year',$currentyear)->pluck('updated_at');
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
                        $totalissues=DB::table('hseissues')->where('department',$nmdept)->get();
                        $totalissuescount=count($totalissues);
                        $totalclosed=DB::table('hseissues')->where('department',$nmdept)->where('status','closed')->get();
                        $totalclosedcount=count($totalclosed);
                        $closebefore=DB::select("select COUNT(duedate)AS number
                                                FROM  hseissues where department = '$nmdept'
                                                and status='closed'
                                                and closed <= duedate
                                            ");


                        $closeafter=DB::select("select COUNT(duedate)AS number
                                                FROM  hseissues where department = '$nmdept'
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
                        $totaldue=DB::table('hseissues')->where('department',$nmdept)->where('status','open')->where('duedate' ,'<=', $today)->get();
                        $totalduecount=count($totaldue);
                        $totalopen=DB::table('hseissues')->where('department',$nmdept)->where('status','open')->get();
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
                    <td class="font-w600 text-center" style="border: 2px solid #e1e6e9;">
                        <a href="" style="font-size:10px">{{$departmentDB[$nmdept]??$nmdept}}</a>
                    </td>
                    <td class="font-w600 text-center" style="border: 2px solid #e1e6e9;">
                        <a href="" style="font-size:10px">{{$thisweekcount}}</a>
                    </td>
                    <td class="font-w600 text-center" style="border: 2px solid #e1e6e9;">
                        <a href="" style="font-size:10px">{{$lastweekcount}}</a>
                    </td>
                    <td class="font-w600 text-center" style="border: 2px solid #e1e6e9;">
                        <a href="" style="font-size:10px">{{$lastcount}}</a>
                    </td>
                    <td class="font-w600 text-center" style="border: 2px solid #e1e6e9;">
                        <a href="" style="font-size:10px">{{$thiscount}}</a>
                    </td>
                    <td class="font-w600 text-center" style="border: 2px solid #e1e6e9;">
                        <a href="" style="font-size:10px">{{$totalissuescount}}</a>
                    </td>
                    <td class="font-w600 text-center" style="border: 2px solid #e1e6e9;">
                        <a href="" style="font-size:10px">{{$totalclosedcount}}</a>
                    </td>
                    <td class="font-w600 text-center" style="border: 2px solid #e1e6e9;">
                        <a href="" style="font-size:10px">{{$totalbeforecount}}</a>
                    </td>
                    <td class="font-w600 text-center" style="border: 2px solid #e1e6e9;">
                        <a href="" style="font-size:10px">{{$totalaftercount}}</a>
                    </td>
                    <td class="font-w600 text-center" style="border: 2px solid #e1e6e9;">
                        <a href="" style="font-size:10px">{{$totalduecount}}</a>
                    </td>
                    <td class="font-w600 text-center" style="border: 2px solid #e1e6e9;">
                        <a href="" style="font-size:10px">{{$totalopencount}}</a>
                    </td>
                    <td class="font-w600 text-center" style="border: 2px solid #e1e6e9;">
                        <a class="px-2 py-1 rounded bg-body-dark" style="font-size:10px">{{$tcpercentage}}%</a>
                    </td>
                    <td class="font-w600 text-center" style="border: 2px solid #e1e6e9;">
                        <a class=" px-2 py-1 rounded bg-body-dark" style="font-size:10px">{{$ttpercentage}}%</a>
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
                    <h8>Options</h8>
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
                            <a class="block block-rounded block-link-shadow bg-body" href="/user/hseobservation/issues">
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
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/hseobservation/closedissue">
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
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/hseobservation/openissue">
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
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/hseobservation/analysis">
                                <div class="block-content text-center" style="height:130px">
                                    <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Analysis
                                    </p>
                                </div>
                            </a>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
    window.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            $('.adjust-graph').addClass('p-2');
        }, 1000);
    });
    var depcode = <?php echo $depcode; ?>;
    var depcodeForOpenDue = <?php echo $depcodeForOpenDue; ?>;
    var depcodeForClosedIssues = <?php echo $depcodeForClosedIssues; ?>;
    var depcodeForAwaitingIssues = <?php echo $depcodeForAwaitingIssues; ?>;
    var depcodeForTotalTypeIssues = <?php echo $depcodeForTotalTypeIssues; ?>;
    var depcodeForTypeIssues = <?php echo $depcodeForTypeIssues; ?>;
    var depcodeForImplementStatusIssues = <?php echo $depcodeForImplementStatusIssues; ?>;
    var dopen = <?php echo $dopen; ?>;
    var due = <?php echo $due; ?>;
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
    var ten = <?php echo $ten; ?>;
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


    var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

    var barChartData = {
        labels: depcodeForAwaitingIssues
        , datasets: [{
            label: 'Awaiting Actions'
            , backgroundColor: "#003366"
            , data: awaiting

        }]
    };
    var barChartData1 = {
        labels: depcodeForOpenDue
        , datasets: [{
                label: 'Open'
                , backgroundColor: "#003366"
                , data: dopen
            }
            , {
                label: 'Due'
                , backgroundColor: "#ff0000"
                , data: due

            }
        ]
    };
    var barChartData2 = {
        labels: depcodeForTypeIssues
        , datasets: [{
                label: 'High'
                , backgroundColor: "#ff6437"
                , stack: 'Stack 0'
                , data: highd
            },

            {
                label: 'Medium'
                , backgroundColor: "#fff0b9"
                , data: moderated
                , stack: 'Stack 0'
            },

            {
                label: 'Low'
                , backgroundColor: "#00cc99"
                , data: lowd
                , stack: 'Stack 0'
            }
        ]
    };
    var barChartData3 = {
        labels: depcodeForImplementStatusIssues
        , datasets: [{
                label: '0% Progress'
                , backgroundColor: "#b3d9ff"
                , stack: 'Stack 0'
                , data: zero
            },
            {
                label: '10% Progress'
                , backgroundColor: "#6ac1a4"
                , data: ten
                , stack: 'Stack 0'
            },
            {
                label: '25% Progress'
                , backgroundColor: "#0099CC"
                , data: two
                , stack: 'Stack 0'
            }
            , {
                label: '50% Progress'
                , backgroundColor: "#006080"
                , data: five
                , stack: 'Stack 0'
            }
            , {
                label: '100% Progress'
                , backgroundColor: "#013647"
                , data: hundred
                , stack: 'Stack 0'
            }
        ]
    };
    var barChartData4 = {
        labels: depcodeForClosedIssues
        , datasets: [{
                label: 'Closed Before Due'
                , backgroundColor: "#00cc99"
                , stack: 'Stack 0'
                , data: before
            },


            {
                label: 'Closed After Due'
                , backgroundColor: "#fff0b9"
                , data: after
                , stack: 'Stack 0'
            }
        ]
    };
    var barChartData5 = {
        labels: depcodeForTotalTypeIssues
        , datasets: [{
                label: 'High'
                , backgroundColor: "#ff6437"
                , stack: 'Stack 0'
                , data: hightotal
            },

            {
                label: 'Medium'
                , backgroundColor: "#fff0b9"
                , data: moderatetotal
                , stack: 'Stack 0'
            },

            {
                label: 'Low'
                , backgroundColor: "#00cc99"
                , data: lowtotal
                , stack: 'Stack 0'
            }
        ]
    };
    var barChartData6 = {
        labels: year
        , datasets: [{
            label: 'Total Issues'
            , fill: false
            , lineTension: 0
            , backgroundColor: "rgb(46,134,193)"
            , borderColor: "rgb(46,134,193)"
            , data: trend

        }]
    };
    var barChartData7 = {
        labels: months
        , datasets: [{
            label: 'Total Issues'
            , fill: false
            , lineTension: 0
            , backgroundColor: "rgb(88,214,141)"
            , borderColor: "rgb(88,214,141)"
            , data: monthtrend

        }]
    };
    var barChartData8 = {
        labels: months
        , datasets: [{
            label: 'Total Closure'
            , fill: false
            , lineTension: 0
            , backgroundColor: "rgb(46,134,193)"
            , borderColor: "rgb(46,134,193)"
            , data: closuretrend

        }]
    };
    var barChartDataLast = {
        labels: months
        , datasets: [{
            label: 'Total Issues Last Year'
            , fill: false
            , lineTension: 0
            , backgroundColor: "rgb(46,134,193)"
            , borderColor: "rgb(46,134,193)"
            , data: closuretrendlast

        }]
    };

    var dataTwo = {
        labels: months
        , datasets: [{
            label: 'Total Issues Two Years Back'
            , fill: false
            , lineTension: 0
            , backgroundColor: "rgb(46,134,193)"
            , borderColor: "rgb(46,134,193)"
            , data: closuretrendtwo

        }]
    };

    var dataThree = {
        labels: months
        , datasets: [{
            label: 'Total Issues Three Years Back'
            , fill: false
            , lineTension: 0
            , backgroundColor: "rgb(46,134,193)"
            , borderColor: "rgb(46,134,193)"
            , data: closuretrendthree

        }]
    };
    function createLineChart(canvasId, chartData, chartTitle) {
        const ctx = document.getElementById(canvasId).getContext("2d");
        return new Chart(ctx, {
            type: 'line',
            data: chartData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    },
                    title: {
                        display: true,
                        text: chartTitle
                    },
                    filler: {
                        propagate: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: { size: 10 }
                        }
                    },
                    y: {
                        grid: {
                            drawBorder: false
                        },
                        ticks: {
                            font: { size: 10 }
                        }
                    }
                },
                interaction: {
                    intersect: false
                }
            }
        });
    }

    window.myBar = createLineChart("canvas6", barChartData6, "Yearly Trend");
    var ctx1 = document.getElementById("canvas7").getContext("2d");
    var chartFilter = createLineChart("canvas7", barChartData7, "Monthly Trend");
    window.myBar = createLineChart("canvas8", barChartData8, "Monthly Trend(Closure Trend)");
    const hseDeletedDepartments = "{{$hseDeletedDepartments}}".split(',');
    const disabledDepartments = "{{$disabledDepartments}}".split(',');
    var labelColors = getColorCoding(barChartData1, hseDeletedDepartments, disabledDepartments);
    const departmentNamesKeys = "{{$departmentNamesKeys}}".split(',');
    const departmentNamesValues = "{{$departmentNamesValues}}".split(',');
    const departments = departmentNamesKeys.map((code, index) => {
        const name = departmentNamesValues[index] || '';
        let status = 'Active';

        if (hseDeletedDepartments.includes(code)) {
            status = 'Deleted';
        } else if (disabledDepartments.includes(code)) {
            status = 'Disabled';
        }
        
        return { code, name, status };
    });
    const tickColorPlugin = {
        id: 'tickColorPlugin',
        beforeDraw(chart) {
            const { ctx, scales: { x } } = chart;
            const labelColors = chart.options.plugins.tickColorPlugin.labelColors || [];

            x.ticks.forEach((tick, i) => {
                const label = tick.label;
                const xPos = x.getPixelForTick(i);
                const yPos = chart.chartArea.bottom + 5;

                ctx.save();
                ctx.translate(xPos, yPos);
                ctx.rotate(-45 * Math.PI / 180);
                ctx.font = "9px sans-serif";
                ctx.fillStyle = labelColors[i] || 'black';
                ctx.textAlign = 'right';
                ctx.textBaseline = 'middle';
                ctx.fillText(label, 0, 0);
                ctx.restore();
            });

            x.options.ticks.display = false;
        }
    };

    function getColorCoding(data,hseDeletedDepartments,disabledDepartments) {
        return data.labels.map(label => {
            if (hseDeletedDepartments.includes(label)) {
                return 'red';
            } else if (disabledDepartments.includes(label)) {
                return 'orange';
            } else {
                return '#76797f';
            }
        });
    }
    function createDepartmentChart(canvasId, chartData, chartTitle, customOptions = {}) {
        const ctx = document.getElementById(canvasId).getContext("2d");
        const labelColors = getColorCoding(chartData, hseDeletedDepartments, disabledDepartments);

        const defaultOptions = {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: { bottom: 30 }
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: { font: { size: 10 } }
                },
                title: {
                    display: true,
                    text: chartTitle,
                    font: { size: 14 }
                },
                tickColorPlugin: { labelColors },
                tooltip: {
                    filter: function (tooltipItem) {
                        return parseFloat(tooltipItem.formattedValue) !== 0;
                    },
                    callbacks: {
                        label: function (context) {
                            const deptCode = context.label;
                            const dept = departments.find(d => d.code === deptCode);
                            const value = context.formattedValue;
                            
                            if (dept) {
                                return [
                                    `${context.dataset.label}: ${value}`,
                                    `${dept.name} (${dept.status})`
                                ];
                            } else {
                                return `${context.dataset.label}: ${value}`;
                            }

                        }
                    }
                }
            },
            scales: {
                x: {
                    ticks: {
                        font: { size: 9 },
                        callback: function (value) {
                            return this.getLabelForValue(value);
                        },
                        rotation: 45
                    }
                },
                y: {
                    ticks: {
                        font: { size: 10 }
                    }
                }
            }
        };

        // Deep merge defaultOptions with customOptions
        const mergedOptions = Chart.helpers.merge({}, [defaultOptions, customOptions]);

        return new Chart(ctx, {
            type: 'bar',
            data: chartData,
            options: mergedOptions,
            plugins: [tickColorPlugin]
        });
    }
     // //dropdown filter
    function updateChart() {
        chartFilter.destroy();
        let chartData, chartTitle;
        const val = document.getElementById("monthlytrendyear").value;
        if (val == 1) {
            chartData = barChartData7;
            chartTitle = 'Monthly Trend';
        } else if (val == 2) {
            chartData = barChartDataLast;
            chartTitle = 'Monthly Trend';
        } else if (val == 3) {
            chartData = dataTwo;
            chartTitle = 'Monthly Trend';
        } else if (val == 4) {
            chartData = dataThree;
            chartTitle = 'Monthly Trend';
        }

        chartFilter = new Chart(ctx1, {
            type: 'line',
            data: chartData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    },
                    title: {
                        display: true,
                        text: chartTitle
                    },
                    filler: {
                        propagate: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: { size: 10 }
                        }
                    },
                    y: {
                        grid: {
                            drawBorder: false
                        },
                        ticks: {
                            font: { size: 10 }
                        }
                    }
                },
                interaction: {
                    intersect: false
                }
            }
        });
        chartFilter.update();
    }
    createDepartmentChart(
        "canvas",
        barChartData1,
        "Open/Due Issues-Department Wise"
    );
    createDepartmentChart(
        "canvas1",
        barChartData2,
        "Open High/Medium/Low HSE Observations Issues-Department Wise"
    );
    createDepartmentChart(
        "canvas5",
        barChartData5,
        "Total High/Medium/Low HSE Observations Issues-Department Wise"
    );
    createDepartmentChart(
        "canvas2",
        barChartData,
        "Awaiting Actions-Department Wise",
        {
            layout: {}  // Removes the bottom padding
        }
    );
    createDepartmentChart(
        "canvas3",
        barChartData3,
        "Number of Actions with Implementation Progress-Department Wise",
        {
            layout: {}  // Removes the bottom padding
        }
    );
    createDepartmentChart(
        "canvas4",
        barChartData4,
        "Closed Issues Per Department",
        {
            layout: {}  
        }
    );
</script>

@endsection
