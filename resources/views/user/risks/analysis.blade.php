@extends('layouts.backend')


@section('content')
    <style>
        .fa-arrow-circle-right:before {
            color: gray;
            opacity: .8;
        }
    </style>
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2 text-center text-sm-left">
            <div class="flex-sm-fill">
                <h1 class="h3 font-w700 mb-2">
                    Risk Analysis
                </h1>
                <h2 class="h6 font-w500 text-muted mb-0">
                    Welcome <a class="font-w600" href="javascript:void(0)">{{Auth::user()->name}}</a>, everything looks great.
                </h2>
            </div>
        </div>
    </div>
</div>

<!-- Page Content -->
<div class="content" style="padding-top:30px;width:98%;margin-left:25px;">
    <!-- All risk -->
    <div class="row row-deck">
        <div class="col-sm-6 col-xl-3">
            <!-- Messages -->
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                        <a href="{{ route('risk.index') }}" target="_blank">
                            <dt class="font-size-h2 font-w700">{{ $totalRisk }}</dt>
                        </a>
                        <dd class="mb-0 font-weight-bold text-primary">Total</dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                        <i class="fa fa-inbox font-size-h3 text-primary"></i>
                    </div>
                </div>
                <div style="background: #f8f9fa; padding: 0.5rem 1.5rem; display: flex; justify-content: space-between;">
                    <span style="font-weight: 600; opacity: .5;" class="text-primary">
                        View all risks
                    </span>
                    <span>
                        <a href="{{ route('risk.index') }}" target="_blank">
                            <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                        <a href="{{route('risk.index', [ 'rank' => 'High']) }}" target="_blank">
                            <dt class="font-size-h2 font-w700 text-primary">{{ $totalHighRisk }}</dt>
                        </a>
                        <dd class="mb-0 font-weight-bold text-primary">High</dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                        <i class="fa fa-chart-line font-size-h3 text-primary"></i>
                    </div>
                </div>
                <div style="background: #f8f9fa; padding: 0.5rem 1.5rem; display: flex; justify-content: space-between;">
                    <span style="font-weight: 600; opacity: .5;" class="text-primary">
                        View high risks
                    </span>
                    <span>
                        <a href="{{route('risk.index', [ 'rank' => 'High']) }}" target="_blank">
                            <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                        <a href="{{route('risk.index', [ 'rank' => 'Moderate']) }}" target="_blank">
                            <dt class="font-size-h2 font-w700 text-primary">{{ $totalModerateRisk }}</dt>
                        </a>
                        <dd class="mb-0 font-weight-bold text-primary">Moderate</dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                        <i class="fa fa-chart-line font-size-h3 text-primary"></i>
                    </div>
                </div>
                <div style="background: #f8f9fa; padding: 0.5rem 1.5rem; display: flex; justify-content: space-between;">
                    <span style="font-weight: 600; opacity: .5;" class="text-primary">
                        View moderate risks
                    </span>
                    <span>
                        <a href="{{route('risk.index', [ 'rank' => 'Moderate']) }}" target="_blank">
                            <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                        <a href="{{route('risk.index', [ 'rank' => 'Low']) }}" target="_blank">
                            <dt class="font-size-h2 font-w700 text-primary">{{ $totalLowRisk }}</dt>
                        </a>
                        <dd class="mb-0 font-weight-bold text-primary">Low</dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                        <i class="fa fa-chart-line font-size-h3 text-primary"></i>
                    </div>
                </div>
                <div style="background: #f8f9fa; padding: 0.5rem 1.5rem; display: flex; justify-content: space-between;">
                    <span style="font-weight: 600; opacity: .5;" class="text-primary">
                        View low risks
                    </span>
                    <span>
                        <a href="{{route('risk.index', [ 'rank' => 'Moderate']) }}" target="_blank">
                            <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="block block-rounded">
        <div class="block-content block-content-full">
            <div id="content-all">
                <div class="block-content block-content-full" style="width:100%; background-color:#ffff">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-12" style="height:400px">
                                {!! $chartDataByDepartment->container() !!}
                            </div>
                        </div>
                    </div>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
                    {!! $chartDataByDepartment->script() !!}
                    <script>
                        function autoResizeDiv(height) {
                            document.getElementById('content-all').style.height = (height) + 'px';
                        }
                        window.onresize = autoResizeDiv;
                        autoResizeDiv(window.innerHeight - 270);
                    </script>
                </div>
            </div>
        </div>
    </div>

    <!-- Strategic risk -->
    @include('user.risks._analysis_group_item', [
        'title' => 'Strategic Risk',
        'description' => 'Strategic risk refers to potential threats and uncertainties that arise from the strategic decisions and actions taken by your organization, impacting its long-term objectives and competitive positioning. It encompasses the possibility of adverse outcomes stemming from shifts in market dynamics, technological advancements, regulatory changes, or other factors.',
        'riskGroup' => 'Strategic',
        'riskColor' => '#4A6092',
        'riskBgColor' => '#F0F2F7'
    ])

    <!-- Operational risk -->
    @include('user.risks._analysis_group_item', [
        'title' => 'Operational Risk',
        'description' => 'Operational risk pertains to potential disruptions or losses resulting from inadequate internal processes, systems, people, or external events, affecting the smooth functioning of an organization. It encompasses the risk of errors, inefficiencies, and unexpected events that can impact day-to-day operations and financial performance.',
        'riskGroup' => 'Operational',
        'riskColor' => '#6F954C',
        'riskBgColor' => '#F3F6F0'
    ])

    <!-- Compliance risk -->
    @include('user.risks._analysis_group_item', [
        'title' => 'Compliance Risk',
        'description' => 'Compliance risk refers to the potential for an organization to face legal or regulatory consequences due to failure in adhering to applicable laws, regulations, or industry standards. It encompasses the risk of financial penalties, reputational damage, and operational disruptions resulting from non-compliance with established rules and requirements.',
        'riskGroup' => 'Compliance',
        'riskColor' => '#C88C60',
        'riskBgColor' => '#F9F3EF'
    ])

    <!-- Financial risk -->
    @include('user.risks._analysis_group_item', [
        'title' => 'Financial Risk',
        'description' => "Financial risk involves exposure to potential losses or adverse impacts on an organization's financial stability due to fluctuations in market conditions, interest rates, currency exchange rates, credit, or liquidity. Managing financial risk is essential for safeguarding the firm's fiscal health and ensuring its ability to meet financial obligations.",
        'riskGroup' => 'Financial',
        'riskColor' => '#D0A642',
        'riskBgColor' => '#FBF8EE'
    ])
</div>

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
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/risks/closedrisks">
                                <div class="block-content text-center" style="height:130px">
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
                                <div class="block-content text-center" style="height:130px">
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
