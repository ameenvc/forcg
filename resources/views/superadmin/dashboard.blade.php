@extends('superadmin.datatable')

@section('content')
<main id="main-container">
                <!-- Page Content -->
                <div class="content" style="margin-top:-60px;">
                    <!-- Square Themed Blocks -->
                    <div class="row">
                        <div class="col-md-8 col-xl-4">
                        <a href="{{route('superadmin.edmodules')}}">
                            <div class="block block-rounded block-themed">
                                <div class="block-header">
                                    <h3 class="block-title" style="font-size: 13px;">Enable/Disable<br/> Modules</h3>
                                    <div class="block-options">
                                            <i class="fa fa-toggle-on" style="color:black"></i>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <p style="visibility:hidden;">Block’s content..</p>
                                </div>
                            </div>
                            </a>
                        </div>
                        <div class="col-md-8 col-xl-4">
                        <a href="{{route('superadmin.compsize')}}">
                            <div class="block block-rounded block-themed">
                                <div class="block-header bg-primary-light">
                                    <h3 class="block-title" style="font-size: 13px;">Manage<br/> Company Size</h3>
                                    <div class="block-options">
                                            <i class="fa fa-balance-scale" style="color:black"></i>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <p style="visibility:hidden;">Block’s content..</p>
                                </div>
                            </div>
                            </a>
                        </div>
                        <div class="col-md-8 col-xl-4">
                        <a href="{{route('superadmin.logs')}}">
                            <div class="block block-rounded block-themed">
                                <div class="block-header bg-primary-dark">
                                    <h3 class="block-title" style="font-size: 13px;">Admin/Users<br/> Logs</h3>
                                    <div class="block-options">
                                            <i class="fa fa-clipboard-list" style="color:white"></i>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <p style="visibility:hidden;">Block’s content..</p>
                                </div>
                            </div>
                            </a>
                        </div>
                        <div class="col-md-8 col-xl-4">
                        <a href="{{route('superadmin.activity')}}" >
                            <div class="block block-rounded block-themed">
                                <div class="block-header bg-primary-darker">
                                    <h3 class="block-title" style="font-size: 13px;">Admin/Users<br/>Activities</h3>
                                    <div class="block-options">
                                            <i class="fa fa-hammer"></i>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <p style="visibility:hidden;">Block’s content..</p>
                                </div>
                            </div>
                            </a>
                        </div>
                        <div class="col-md-8 col-xl-4">
                        <a href="{{route('superadmin.contlogins')}}" >
                            <div class="block block-rounded block-themed">
                                <div class="block-header bg-success">
                                    <h3 class="block-title" style="font-size: 13px;">Control<br/>Device Logins</h3>
                                    <div class="block-options">
                                            <i class="fa fa-network-wired" style="color:black"></i>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <p style="visibility:hidden;">Block’s content..</p>
                                </div>
                            </div>
                            </a>
                        </div>
                        <div class="col-md-8 col-xl-4">
                        <a  href="{{route('superadmin.modoverview')}}" >
                            <div class="block block-rounded block-themed">
                                <div class="block-header bg-info">
                                    <h3 class="block-title" style="font-size: 13px;">Analysis<br/>Dashboard</h3>
                                    <div class="block-options">
                                            <i class="fa fa-chart-line" style="color:black"></i>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <p style="visibility:hidden;">Block’s content..</p>
                                </div>
                            </div>
                            </a>
                        </div>
                        <div class="col-md-8 col-xl-4">
                        <a href="{{route('superadmin.databackup')}}" >
                            <div class="block block-rounded block-themed">
                                <div class="block-header bg-warning">
                                    <h3 class="block-title" style="font-size: 13px;">Backup/<br/>Restores Data</h3>
                                    <div class="block-options">
                                            <i class="fa fa-window-restore" style="color:black"></i>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <p style="visibility:hidden;">Block’s content..</p>
                                </div>
                            </div>
                            </a>
                        </div>
                        <div class="col-md-8 col-xl-4">
                        <a href="{{route('superadmin.errorLogs')}}" >
                            <div class="block block-rounded block-themed">
                                <div class="block-header bg-danger">
                                    <h3 class="block-title" style="font-size: 13px;">Error<br/> Logs</h3>
                                    <div class="block-options">
                                            <i class="fa fa-exclamation-triangle" style="color:black"></i>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <p style="visibility:hidden;">Block’s content..</p>
                                </div>
                            </div>
                            </a>
                        </div>
                        <div class="col-md-8 col-xl-4">
                        <a href="{{route('superadmin.getoldemailsetting')}}" >
                            <div class="block block-rounded block-themed">
                                <div class="block-header bg-gray">
                                    <h3 class="block-title" style="font-size: 13px;">Email<br/> Settings</h3>
                                    <div class="block-options">
                                            <i class="fa fa-envelope" style="color:black"></i>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <p style="visibility:hidden;">Block’s content..</p>
                                </div>
                            </div>
                            </a>
                        </div>
                        <div class="col-md-8 col-xl-4">
                        <a href="{{route('superadmin.initreset')}}" >
                            <div class="block block-rounded block-themed">
                                <div class="block-header bg-muted">
                                    <h3 class="block-title" style="font-size: 13px;">Master<br/> Reset</h3>
                                    <div class="block-options">
                                            <i class="fas fa-undo"></i>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <p style="visibility:hidden;">Block’s content..</p>
                                </div>
                            </div>
                            </a>
                        </div>
                        
                    <!-- END Rounded Themed Blocks -->
                </div>
                <!-- END Page Content -->
            </main>
            <!-- END Main Container -->

@endsection