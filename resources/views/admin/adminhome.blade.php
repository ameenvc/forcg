@extends('admin.nav')

@section('content')
<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        .circle-chart {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: white;
            /* Set the background color to white */
        }
    </style>
</head>

<body>
    <main id="main-container" style="width: 100%;margin-top:-75px;">
        <!-- Hero -->
        <div class="bg-image overflow-hidden" style="background-image: url('{{asset('media/photos/photo3@2x.jpg')}}')">
            <div class="bg-primary-dark-op">
                <div class="content content-full">
                    <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                        <div class="flex-grow-1">
                            <h1 class="fw-bold mb-1 text-white">Dashboard</h1>
                            <h3 class="fs-base lh-base fw-medium text-muted mb-0">
                                Welcome Administrator
                            </h3>
                        </div>
                        <div class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-alt">
                                <li class="breadcrumb-item">
                                    <a class="btn btn-primary px-4 py-2" href="{{ url('/user/dashboard') }}" data-toggle="click-ripple">User Access</a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Hero -->
        <div class="content" style="width: 100%;">
            <div class="row">
                <div class="col-lg-3">
                    <div class="row  flex-lg-column gap-3 justify-content-around mb-md-5">
                        <!-- start -->
                        <div class="col-lg-12 col-sm-6 ">
                            <!-- Pending Orders -->
                            <div class="block block-rounded d-flex flex-column h-100 ">
                                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center p-2">
                                    <dl class="mb-0">
                                        <dt class="fs-4 fw-bold">{{$totalUsersCount}}</dt>
                                        <dd class="fs-sm fw-medium fs-sm fw-medium text-dark font-weight-bold mb-0">
                                            Total Users
                                        </dd>
                                    </dl>
                                    <div class="item item-rounded-lg bg-body-light" style="width:auto;height:auto; padding: 10px;border-radius:50%;">
                                        <i class="far fa-gem fs-5 text-primary"></i>
                                    </div>
                                </div>
                                <div class="bg-body-light rounded-bottom">
                                    <a class="block-content block-content-full flex-grow-1 d-flex align-items-center justify-content-between p-2" href="{{ url('/admin/viewUsers') }}">
                                        <span style="font-size: 13px;">View all users</span>
                                        <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                                    </a>
                                </div>
                            </div>
                            <!-- END Pending Orders -->
                        </div>
                        <!-- end --><!-- start -->
                        <div class="col-lg-12 col-sm-6 mt-1">
                            <!-- Pending Orders -->
                            <div class="block block-rounded d-flex flex-column h-100 ">
                                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center p-2">
                                    <dl class="mb-0">
                                        <dt class="fs-4 fw-bold">{{$pendingAprroval}}</dt>
                                        <dd class="fs-sm fw-medium fs-sm fw-medium text-dark font-weight-bold mb-0">
                                            Pending Approval
                                        </dd>
                                    </dl>
                                    <div class="item item-rounded-lg bg-body-light" style="width:auto;height:auto; padding: 10px;border-radius:50%;">
                                        <i class="far fa-gem fs-5 text-primary"></i>
                                    </div>
                                </div>
                                <div class="bg-body-light rounded-bottom">
                                    <a class="block-content block-content-full flex-grow-1 d-flex align-items-center justify-content-between p-2" href="{{ url('/user/approval') }}">
                                        <span style="font-size: 13px;">View all pending</span>
                                        <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                                    </a>
                                </div>
                            </div>
                            <!-- END Pending Orders -->
                        </div>
                        <!-- end --><!-- start -->
                        <div class="col-lg-12 col-sm-6 mt-1">
                            <!-- Pending Orders -->
                            <div class="block block-rounded d-flex flex-column h-100 ">
                                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center p-2">
                                    <dl class="mb-0">
                                        <dt class="fs-4 fw-bold">{{$totalDepartments}}</dt>
                                        <dd class="fs-sm fw-medium fs-sm fw-medium text-dark font-weight-bold mb-0">
                                            Total Departments
                                        </dd>
                                    </dl>
                                    <div class="item item-rounded-lg bg-body-light" style="width:auto;height:auto; padding: 10px;border-radius:50%;">
                                        <i class="far fa-gem fs-5 text-primary"></i>
                                    </div>
                                </div>
                                <div class="bg-body-light rounded-bottom">
                                    <a class="block-content block-content-full flex-grow-1 d-flex align-items-center justify-content-between p-2" href="{{ url('/admin/viewDepartments') }}">
                                        <span style="font-size: 13px;">View total departments</span>
                                        <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                                    </a>
                                </div>
                            </div>
                            <!-- END Pending Orders -->
                        </div>
                        <!-- end --><!-- start -->
                        <div class="col-lg-12 col-sm-6 mt-1">
                            <!-- Pending Orders -->
                            <div class="block block-rounded d-flex flex-column h-100 ">
                                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center p-2">
                                    <dl class="mb-0">
                                        <dt class="fs-4 fw-bold">{{$totalEmployee}}</dt>
                                        <dd class="fs-sm fw-medium fs-sm fw-medium text-dark font-weight-bold mb-0">
                                            Total Employees
                                        </dd>
                                    </dl>
                                    <div class="item item-rounded-lg bg-body-light" style="width:auto;height:auto; padding: 10px;border-radius:50%;">
                                        <i class="far fa-gem fs-5 text-primary"></i>
                                    </div>
                                </div>
                                <div class="bg-body-light rounded-bottom">
                                    <a class="block-content block-content-full flex-grow-1 d-flex align-items-center justify-content-between p-2" href="{{ url('/admin/employeelist') }}">
                                        <span style="font-size: 13px;">View all employees</span>
                                        <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                                    </a>
                                </div>
                            </div>
                            <!-- END Pending Orders -->
                        </div>
                        <!-- end --><!-- start -->
                        <div class="col-lg-12 col-sm-6 mt-1">
                            <!-- Pending Orders -->
                            <div class="block block-rounded d-flex flex-column h-100">
                                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center p-2">
                                    <dl class="mb-0">
                                        <dt class="fs-4 fw-bold">{{$enabledModules}}</dt>
                                        <dd class="fs-sm fw-medium fs-sm fw-medium text-dark font-weight-bold mb-0">
                                            Enable Module
                                        </dd>
                                    </dl>
                                    <div class="item item-rounded-lg bg-body-light" style="width:auto;height:auto; padding: 10px;border-radius:50%;">
                                        <i class="far fa-gem fs-5 text-primary"></i>
                                    </div>
                                </div>
                                <div class="bg-body-light rounded-bottom">
                                    <a class="block-content block-content-full flex-grow-1 d-flex align-items-center justify-content-between p-2" href="{{ url('/user/home') }}">
                                        <span style="font-size: 13px;">View enable modules</span>
                                        <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                                    </a>
                                </div>
                            </div>
                            <!-- END Pending Orders -->
                        </div>
                        <!-- end -->
                    </div>
                </div>
                <div class="col-lg-9">
                    <!-- Bars Chart -->

                    <div class="row">
                        <div class="block-content block-content-full flex-grow-1 d-flex align-items-center">
                            <div class="chartjs-size-monitor">
                                <div class="chartjs-size-monitor-expand">
                                    <div class=""></div>
                                </div>
                                <div class="chartjs-size-monitor-shrink">
                                    <div class=""></div>
                                </div>
                            </div>
                            <canvas class="js-chartjs-earnings chartjs-render-monitor" id="myChart" style="display: block; height: 382px; width: 765px;" width="1147" height="573"></canvas>
                        </div>
                    </div>
                    <!-- END Bars Chart -->
                    <div class="row">
                        <div class="col-xl-2 col-sm-4 col-md-3">
                            <div class="js-pie-chart pie-chart circle-chart" data-percent="{{$regularUserPer}}" data-line-width="3" data-size="100" data-bar-color="#fadb7d" data-track-color="#eeeeee">
                                <span>{{$regularUserPer}}%</span>
                            </div>
                            <div style="font-size: 13px;" class="ml-2">Regular user</div>
                        </div>
                        <div class="col-xl-2 col-sm-4 col-md-3">
                            <div class="js-pie-chart pie-chart circle-chart" data-percent="{{$neverVisitedUsersPer}}" data-line-width="3" data-size="100" data-bar-color="#fadb7d" data-track-color="#eeeeee">
                                <span>{{$neverVisitedUsersPer}}%</span>
                            </div>
                            <div style="font-size: 13px;">Never Visited user</div>
                        </div>
                        <div class="col-xl-2 col-sm-4 col-md-3">
                            <div class="js-pie-chart pie-chart circle-chart" data-percent="{{$onlineUsersPercentage}}" data-line-width="3" data-size="100" data-bar-color="#fadb7d" data-track-color="#eeeeee">
                                <span>{{$onlineUsersPercentage}}%</span>
                            </div>
                            <div style="font-size: 13px;" class="ml-3">Online user</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Main Container -->
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
        <script>
            var process = <?php echo $process; ?>;
            var depcode = <?php echo $depcode; ?>;
            var users = <?php echo $users; ?>;
            var employees = <?php echo $employees; ?>;

            var barChartData = {
                labels: depcode,
                datasets: [{
                        label: 'Users',
                        backgroundColor: "#3d8adc",
                        data: users
                    },
                    {
                        label: '# of employees',
                        backgroundColor: "#686464",
                        data: employees,
                    }
                ]
            };
            var ctx = document.getElementById("myChart").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: barChartData,
                options: {
                    legend: {
                        position: 'top',
                    },
                    responsive: true,
                    scales: {
                        xAxes: [{
                            gridLines: {
                                display: false
                            },
                        }],
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
        </script>

    </main>
    <!-- END Main Container -->
</body>

</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

@endsection