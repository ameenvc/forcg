@extends('layouts.backend')


@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">Compliance Management</li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="">Analysis</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<!-- Page Content -->
<div class="content">
    <div class="row row-deck">
        <div class="col-sm-6 col-xl-3">
            <!-- Messages -->
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                        @if($total)
                        <a href="{{ route('compliance.register', ['type' => 'complied'])}}">
                            <dt class="font-size-h2 font-w700">{{$total}}</dt>
                        </a>
                        @else
                        <dt class="font-size-h2 font-w700">{{$total}}</dt>
                        @endif
                        <dd class="text-muted mb-0" style="font-size:14px;">Total Compiled</dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                        <i class="fa fa-inbox font-size-h3 text-primary"></i>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                        @if($NotComplied)
                        <a href="{{ route('compliance.register', ['type' => 'not-complied'])}}">
                            <dt class="font-size-h2 font-w700">{{$NotComplied}}</dt>
                        </a>
                        @else
                        <dt class="font-size-h2 font-w700">{{$NotComplied}}</dt>
                        @endif
                        <dd class="text-muted mb-0" style="font-size:14px;">Not Compiled</dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                        <i class="fa fa-chart-line font-size-h3 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                        @if($PartiallyComplied)
                        <a href="{{ route('compliance.register', ['type' => 'partially-complied'])}}">
                            <dt class="font-size-h2 font-w700">{{ $PartiallyComplied }}</dt>
                        </a>
                        @else
                        <dt class="font-size-h2 font-w700">{{ $PartiallyComplied }}</dt>
                        @endif
                        <dd class="text-muted mb-0" style="font-size:14px;">Partiallly Compiled</dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                        <i class="fa fa-chart-line font-size-h3 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                        @if($UnderReview)
                        <a href="{{ route('compliance.register', ['type' => 'under-review'])}}">
                            <dt class="font-size-h2 font-w700">{{$UnderReview}}</dt>
                        </a>
                        @else
                        <dt class="font-size-h2 font-w700">{{$UnderReview}}</dt>
                        @endif
                        <dd class="text-muted mb-0" style="font-size:14px;">Entry under review</dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                        <i class="fa fa-chart-line font-size-h3 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                        @if($AwaitingComplianceEvaluation)
                        <a href="{{ route('compliance.register', ['type' => 'awaiting-compliance-evaluation'])}}">
                            <dt class="font-size-h2 font-w700">{{$AwaitingComplianceEvaluation}}</dt>
                        </a>
                        @else
                        <dt class="font-size-h2 font-w700">{{$AwaitingComplianceEvaluation}}</dt>
                        @endif
                        <dd class="text-muted mb-0" style="font-size:14px;">Awaiting Compliance Evaluation</dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                        <i class="fa fa-chart-line font-size-h3 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row row-deck">
        <div class="col-sm-6 col-xl-3">
            <!-- Messages -->
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                        @if($totalLicensePermits)
                        <a href="{{ route('user.compliance.viewlicensepermit')}}">
                            <dt class="font-size-h2 font-w700">{{$totalLicensePermits}}</dt>
                        </a>
                        @else
                        <dt class="font-size-h2 font-w700">{{$totalLicensePermits}}</dt>
                        @endif
                        <dd class="text-muted mb-0" style="font-size:14px;">Total License/Permits</dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                        <i class="fa fa-id-card font-size-h3 text-primary"></i>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                        @if($activeLicense>0)
                        <a href="{{ route('user.compliance.viewlicensepermit', ['type' => 'Active'])}}">
                            <dt class="font-size-h2 font-w700"><span style="color:#25499c;">{{$activeLicense}}</span></dt>
                        </a>
                        @else
                        <dt class="font-size-h2 font-w700"><span style="color:#25499c;">{{$activeLicense}}</span></dt>
                        @endif
                        <dd class="text-muted mb-0" style="font-size:14px;"><span style="color: #25499c;">Active License/Permits</span></dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                        <i class="fa fa-user-check font-size-h3" style="color: #25499c;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                        @if($nearToExpiryLicense>0)
                        <a href="{{ route('user.compliance.viewlicensepermit', ['type' => 'Near to expiry'])}}">
                            <dt class="font-size-h2 font-w700"><span style="color:#af6f1e;">{{ $nearToExpiryLicense }}</span></dt>
                        </a>
                        @else
                        <dt class="font-size-h2 font-w700"><span style="color:#af6f1e;">{{ $nearToExpiryLicense }}</span></dt>
                        @endif
                        <dd class="text-muted mb-0" style="font-size:14px;"><span style="color: #af6f1e;">Near to expiry License/Permits</span></dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                        <i class="fas fa-clock font-size-h3" style="color: #af6f1e;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                        @if($expired>0)
                        <a href="{{ route('user.compliance.viewlicensepermit', ['type' => 'Expired'])}}">
                            <dt class="font-size-h2 font-w700"><span style="color: #af1e1e;">{{$expired}}</span></dt>
                        </a>
                        @else
                        <dt class="font-size-h2 font-w700"><span style="color: #af1e1e;">{{$expired}}</span></dt>
                        @endif
                        <dd class="text-muted mb-0" style="font-size:14px;"><span style="color: #af1e1e;">Expired License/Permits</span></dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                        <i class="fas fa-exclamation-triangle font-size-h3" style="color: #af1e1e;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
     <!-- Referred Members -->
     <div class="block block-rounded">
         <div class="block-header block-header-default">
             <h3 class="block-title">Legal Requirements(Responsible Users)</h3>
         </div>
         <div class="block-content">
             <div class="row row-deck">
                @foreach($responsibilities as $r)
                @php
                    $responsible = $r->responsibility;
                    $name = App\User::where('email', $responsible)->value('name');
                    $profile_pic = App\User::where('email', $responsible)->value('profile_picture');
                    $profile_pic_source = $profile_pic ? "/storage/profile/" . $profile_pic : "/media/avatars/avatar1.jpg";
                @endphp
                 <div class="col-md-4">
                     <!-- Referred User -->
                     <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                         <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                             <div>
                                 <div class="font-w600 mb-1">{{ $name }}</div>
                                 <div class="font-size-sm text-muted">{{ $r->CNT }} Requirements</div>
                             </div>
                             <div class="ml-3">
                                 <img class="img-avatar" src="{{ $profile_pic_source }}" alt="">
                             </div>
                         </div>
                     </a>
                     <!-- END Referred User -->
                 </div>
                 @endforeach
                 {{-- <div class="col-md-4">
                     <!-- Referred User -->
                     <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                         <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                             <div>
                                 <div class="font-w600 mb-1">Adam McCoy</div>
                                 <div class="font-size-sm text-muted">5 Requirements</div>
                             </div>
                             <div class="ml-3">
                                 <img class="img-avatar" src="{{ asset('media/avatars/avatar1.jpg')}}" alt="">
                             </div>
                         </div>
                     </a>
                     <!-- END Referred User -->
                 </div>
                 <div class="col-md-4">
                     <!-- Referred User -->
                     <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                         <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                             <div>
                                 <div class="font-w600 mb-1">Helen Jacobs</div>
                                 <div class="font-size-sm text-muted">3 Requirements</div>
                             </div>
                             <div class="ml-3">
                                 <img class="img-avatar" src="{{ asset('media/avatars/avatar1.jpg')}}" alt="">
                             </div>
                         </div>
                     </a>
                     <!-- END Referred User -->
                 </div> --}}
             </div>
         </div>
     </div>
     <!-- END Referred Members -->

    <div class="row">
        <div class="col-xl-8 d-flex flex-column">
            <!-- Earnings Summary -->
            <div class="block block-rounded flex-grow-1 d-flex flex-column">
                <div class="block-header block-header-default">
                    <h8>LEGAL COMPLIANCE</h8>
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

            <div class="" style="height:250px;">
                {!! $chart1->container() !!}
            </div>
            {{-- <div> --}}
            <div class="block block-rounded flex-grow-1 d-flex flex-column mt-2">
                <div class="block-header block-header-default">
                    <h8>Compliance Review</h8>
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
                        <div class="js-pie-chart pie-chart" data-percent="{{$percent}}" data-line-width="3" data-size="100" data-bar-color="rgb(46,134,193)" data-track-color="#eeeeee">
                            <span>{{$percent}}%</span>
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
                    <h8>Under Review Department Wise</h8>
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
                    <canvas class="js-chartjs-earnings" id="canvas1"></canvas>
                </div>
            </div>

        </div>
        <div class="col-xl-6 d-flex flex-column">
            <div class="block block-rounded flex-grow-1 d-flex flex-column">
                <div class="block-header block-header-default">
                    <h8>Due Compliance Evaluation</h8>
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
        <div class="col-xl-12 d-flex flex-column">
            <!-- Earnings Summary -->
            <div class="block block-rounded flex-grow-1 d-flex flex-column">
                <div class="block-header block-header-default">
                    <h8>Compliance Evaluation Review</h8>
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

    </div>
    <!-- License/permits graphs -->
    <div class="row">
        <div class="col-xl-12 d-flex flex-column">
            <!-- Earnings Summary -->
            <div class="block block-rounded flex-grow-1 d-flex flex-column">
                <div class="block-header block-header-default">
                    <h8>Total License/Permits Department Wise</h8>
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
                    <canvas class="js-chartjs-earnings" id="canvas5"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- Separate Graphs Status wise -->
    <div class="row">
        <div class="col-xl-6 d-flex flex-column">
            <div class="block block-rounded flex-grow-1 d-flex flex-column">
                <div class="block-header block-header-default">
                    <h8>Active License/Permits Department Wise</h8>
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
                    <h8>Near to expiry License/Permits Department Wise</h8>
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
                    <h8>Expired License/Permits Department Wise</h8>
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
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/compliancemgmt">
                                    <div class="block-content text-center">
                                        <i class="far fa-list-alt fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            <br />
                                            Register
                                        </p>
                                    </div>
                                </a>
                                <!-- END Products -->
                            </div>
                            <div class="col-6">
                                <!-- Sales -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/compliance/myactions">
                                    <div class="block-content text-center">
                                        <i class="fas fa-clone fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            <br>My Actions
                                        </p>
                                    </div>
                                </a>
                                <!-- END Sales -->
                            </div>
                            <div class="col-6">
                                <!-- Payments -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href='/user/compliance/companalysis'>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
    <script src="{{ asset('js/plugins/jquery/jquery.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
    {!! $chart1->script() !!}
    <script>
        var depcode = @json($depcode);
        var dept_codes_with_at_least_one_requirement = @json($dept_codes_with_at_least_one_requirement);

        var complied_collect = @json($complied_collect);
        var not_complied_collect = @json($not_complied_collect);
        var partially_complied_collect = @json($partially_complied_collect);
        var under_review_collect = @json($under_review_collect);
        var due_collect = @json($due_collect);
        let awaiting_compliance_collect = @json($awaiting_compliance_collect);

        var complied_ones = @json($complied_ones);
        var not_complied_ones = @json($not_complied_ones);
        var partially_complied_ones = @json($partially_complied_ones);
        var total_license_permits = @json($totalLicensePermits);
        var activeStatus_collect = @json($activeStatus_collect);
        var nearExpiryStatus_collect = @json($nearExpiryStatus_collect);
        var expiredStatus_collect = @json($expiredStatus_collect);
        var allLicensePermits_collect = @json($allLicensePermits_collect);
    </script>
    <script>
        var barChartData5 = {
            labels: depcode,
            datasets: [{
                label: "Total License/Permits",
                backgroundColor: "#032553",
                data: allLicensePermits_collect,
            }],
        };

        var barChartData6 = {
            labels: depcode,
            datasets: [{
                label: "Active License/Permits",
                backgroundColor: "#25499c",
                data: activeStatus_collect,
            }],
        };

        var barChartData7 = {
            labels: depcode,
            datasets: [{
                label: "Near to expiry License/Permits",
                backgroundColor: "#af6f1e",
                data: nearExpiryStatus_collect,
            }],
        };
        var barChartData8 = {
            labels: depcode,
            datasets: [{
                label: "Expired License/Permits",
                backgroundColor: "#af1e1e",
                data: expiredStatus_collect,
            }],
        };

        function drawChart1(ctx, theData) {
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: theData,
                options: {
                    elements: {
                        rectangle: {
                            borderWidth: 0,
                            borderColor: '#c1c1c1',
                            borderSkipped: 'bottom',
                        }
                    },
                    legend: {
                        position: 'bottom'
                    },
                    responsive: true,
                    title: {
                        display: true,
                        text: ''
                    }
                }
            });
        }
        var ctx5 = document.getElementById("canvas5").getContext("2d");
        drawChart1(ctx5, barChartData5);
        var ctx6 = document.getElementById("canvas6").getContext("2d");
        drawChart1(ctx6, barChartData6);
        var ctx7 = document.getElementById("canvas7").getContext("2d");
        drawChart1(ctx7, barChartData7);
        var ctx8 = document.getElementById("canvas8").getContext("2d");
        drawChart1(ctx8, barChartData8);
    </script>
    <script type="text/javascript" src="{{ asset('js/compliance/analysis_script.js')}}"></script>

    @endsection
