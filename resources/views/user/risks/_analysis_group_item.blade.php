<div class="row py-4">
    <div class="col-sm-12">
        <div style="background: {{ $riskBgColor }}">
            <div class="d-flex flex-column p-4">
                <h2 style="color: {{ $riskColor }}">{{ $title }}</h2>
                <p style="color: {{ $riskColor }}">{{ $description }}</p>
                <div class="row row-deck">
                    <div class="col-sm-6 col-xl-3">
                        <!-- Messages -->
                        <div class="block block-rounded d-flex flex-column">
                            <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                                <dl class="mb-0">
                                    @if(isset($totalRiskByGroupData[$riskGroup]))
                                        <a href="{{ route('risk.index', ['riskgroup' => $riskGroup]) }}" target="_blank">
                                            <dt class="font-size-h2 font-w700" style="color: {{ $riskColor }}">{{ array_sum($totalRiskByGroupData[$riskGroup]) }}</dt>
                                        </a>
                                    @else
                                        <dt class="font-size-h2 font-w700" style="color: {{ $riskColor }}">{{ 0 }}</dt>
                                    @endif
                                    <dd class="mb-0 font-weight-bold" style="color: {{ $riskColor }}">Total</dd>
                                </dl>
                                <div class="item item-rounded bg-body">
                                    <i class="fa fa-inbox font-size-h3" style="color: {{ $riskColor }}"></i>
                                </div>
                            </div>
                            <div style="background: #f8f9fa; padding: 0.5rem 1.5rem; display: flex; justify-content: space-between;">
                                <span style="font-weight: 600; opacity: .5; color: {{ $riskColor }}">
                                    View all risks
                                </span>
                                <span>
                                    <a href="{{ route('risk.index', ['riskgroup' => $riskGroup]) }}" target="_blank">
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
                                    @if(isset($totalRiskByGroupData[$riskGroup]) && isset($totalRiskByGroupData[$riskGroup]['High']))
                                        <a href="{{ route('risk.index', ['riskgroup' => $riskGroup, 'rank' => 'High']) }}" target="_blank">
                                            <dt class="font-size-h2 font-w700" style="color: {{ $riskColor }}">{{ $totalRiskByGroupData[$riskGroup]['High'] }}</dt>
                                        </a>
                                    @else
                                        <dt class="font-size-h2 font-w700" style="color: {{ $riskColor }}">{{ 0 }}</dt>
                                    @endif
                                    <dd class="mb-0 font-weight-bold" style="color: {{ $riskColor }}">High</dd>
                                </dl>
                                <div class="item item-rounded bg-body">
                                    <i class="fa fa-chart-line font-size-h3" style="color: {{ $riskColor }}"></i>
                                </div>
                            </div>
                            <div style="background: #f8f9fa; padding: 0.5rem 1.5rem; display: flex; justify-content: space-between;">
                                <span style="font-weight: 600; opacity: .5; color: {{ $riskColor }}">
                                    View high risks
                                </span>
                                <span>
                                    <a href="{{ route('risk.index', ['riskgroup' => $riskGroup, 'rank' => 'High']) }}" target="_blank">
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
                                    @if(isset($totalRiskByGroupData[$riskGroup]) && isset($totalRiskByGroupData[$riskGroup]['Moderate']))
                                        <a href="{{ route('risk.index', ['riskgroup' => $riskGroup, 'rank' => 'Moderate']) }}" target="_blank">
                                            <dt class="font-size-h2 font-w700" style="color: {{ $riskColor }}">{{ $totalRiskByGroupData[$riskGroup]['Moderate'] }}</dt>
                                        </a>
                                    @else
                                        <dt class="font-size-h2 font-w700" style="color: {{ $riskColor }}">{{ 0 }}</dt>
                                    @endif
                                    <dd class="mb-0 font-weight-bold" style="color: {{ $riskColor }}">Moderate</dd>
                                </dl>
                                <div class="item item-rounded bg-body">
                                    <i class="fa fa-chart-line font-size-h3" style="color: {{ $riskColor }}"></i>
                                </div>
                            </div>
                            <div style="background: #f8f9fa; padding: 0.5rem 1.5rem; display: flex; justify-content: space-between;">
                                <span style="font-weight: 600; opacity: .5; color: {{ $riskColor }}">
                                    View moderate risks
                                </span>
                                <span>
                                    <a href="{{ route('risk.index', ['riskgroup' => $riskGroup, 'rank' => 'Moderate']) }}" target="_blank">
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
                                    @if(isset($totalRiskByGroupData[$riskGroup]) && isset($totalRiskByGroupData[$riskGroup]['Low']))
                                        <a href="{{ route('risk.index', ['riskgroup' => $riskGroup, 'rank' => 'Low']) }}" target="_blank">
                                            <dt class="font-size-h2 font-w700" style="color: {{ $riskColor }}">{{ $totalRiskByGroupData[$riskGroup]['Low'] }}</dt>
                                        </a>
                                    @else
                                        <dt class="font-size-h2 font-w700" style="color: {{ $riskColor }}">{{ 0 }}</dt>
                                    @endif
                                    <dd class="mb-0 font-weight-bold" style="color: {{ $riskColor }}">Low</dd>
                                </dl>
                                <div class="item item-rounded bg-body">
                                    <i class="fa fa-chart-line font-size-h3" style="color: {{ $riskColor }}"></i>
                                </div>
                            </div>
                            <div style="background: #f8f9fa; padding: 0.5rem 1.5rem; display: flex; justify-content: space-between;">
                                <span style="font-weight: 600; opacity: .5; color: {{ $riskColor }}">
                                    View low risks
                                </span>
                                <span>
                                    <a href="{{ route('risk.index', ['riskgroup' => $riskGroup, 'rank' => 'Low']) }}" target="_blank">
                                        <i class="fa fa-arrow-circle-right"></i>
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-8">
        <div class="block block-rounded">
            <div class="block-content block-content-full">
                <div id="content-{{ $riskGroup }}">
                    <div class="block-content block-content-full" style="width:100%; background-color:#ffff">
                        @if(isset($chartData[\App\Charts\RiskAnalysis::RISK_GROUP[$riskGroup]]))
                            <div class="container">
                                <div class="row">
                                    <div class="col-sm-12" style="height:400px">
                                        {!! $chartData[\App\Charts\RiskAnalysis::RISK_GROUP[$riskGroup]]->container() !!}
                                    </div>
                                </div>
                            </div>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
                            {!! $chartData[\App\Charts\RiskAnalysis::RISK_GROUP[$riskGroup]]->script() !!}
                            <script>
                                function autoResizeDiv(height) {
                                    document.getElementById('content-{{ $riskGroup }}').style.height = (height) + 'px';
                                }
                                window.onresize = autoResizeDiv;
                                autoResizeDiv(window.innerHeight - 275);
                            </script>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>