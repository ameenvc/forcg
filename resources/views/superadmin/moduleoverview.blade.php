@extends('superadmin.datatable')

@section('content')
 <!-- Main Container -->

                <!-- END Hero -->
                <div class="bg-body-light">
                    <div class="content content-full">
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">

                            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-alt">
                                <li class="breadcrumb-item">Module Overview</li>
                                    <li class="breadcrumb-item" aria-current="page">
                                    <a id="a1" onclick="" class="link-fx" href="/superadmin/home">Back</a>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <!-- END Hero -->

<hr>
 <?php
    $count=0;
    if(count($users)>0){
      foreach($users as $user){
        if(Cache::has('user-is-online-'.$user->email))
           $count=$count+1;
      }
    }
    if(count($admins)>0){
      foreach($admins as $admin){
        if(Cache::has('user-is-online-'.$admin->email))
        $count=$count+1;
      }
    }
?>
@if(count($modpageload)>0)
<?php
  $total=$modpageload[0]->correctiveaction+$modpageload[0]->riskmanagement+$modpageload[0]->objectives+$modpageload[0]->opportunity+$modpageload[0]->compliance+$modpageload[0]->enviaspects+$modpageload[0]->auditmanagement+$modpageload[0]->ohsrisk+$modpageload[0]->documentmanagement+$modpageload[0]->contextmanagement+$modpageload[0]->complaintmanagement+$modpageload[0]->vendormanagement+$modpageload[0]->incidentreport+$modpageload[0]->hse+$modpageload[0]->hr+$modpageload[0]->maintainance+$modpageload[0]->calibration+$modpageload[0]->occhealth+$modpageload[0]->wastemanagement;
  $correctiveaction=($modpageload[0]->correctiveaction/$total)*100;
  $riskmanagement=($modpageload[0]->riskmanagement/$total)*100;
  $objectives=($modpageload[0]->objectives/$total)*100;
  $opportunity=($modpageload[0]->opportunity/$total)*100;
  $compliance=($modpageload[0]->compliance/$total)*100;
  $enviaspects=($modpageload[0]->enviaspects/$total)*100;
  $auditmanagement=($modpageload[0]->auditmanagement/$total)*100;
  $ohsrisk=($modpageload[0]->ohsrisk/$total)*100;
  $documentmanagement=($modpageload[0]->documentmanagement/$total)*100;
  $contextmanagement=($modpageload[0]->contextmanagement/$total)*100;
  $complaintmanagement=($modpageload[0]->complaintmanagement/$total)*100;
  $vendormanagement=($modpageload[0]->vendormanagement/$total)*100;
  $incidentreport=($modpageload[0]->incidentreport/$total)*100;
  $hse=($modpageload[0]->hse/$total)*100;
  $maintainance=($modpageload[0]->maintainance/$total)*100;
  $hr=($modpageload[0]->hr/$total)*100;
  $calibration=($modpageload[0]->calibration/$total)*100;
  $occhealth=($modpageload[0]->occhealth/$total)*100;
  $wastemanagement=($modpageload[0]->wastemanagement/$total)*100;
?>

                <!-- Page Content -->
                <!-- Appear ([data-toggle="appear"] is initialized in Helpers.coreAppear()) -->
                <!-- For more info and examples you can check out https://github.com/bas2k/jquery.appear -->
                <div class="content"  style="width:92%;margin-left:50px;margin-top:-30px">
                    <!-- END Appear -->
                    <div class="row">
                        <div class="col-xl-12 d-flex flex-column my-0 mb-n4">
                            <!-- Earnings Summary -->
                            <div class="block block-rounded flex-grow-1 d-flex flex-column">
                                <div class="block-header block-header-default">
                                    <h3 class="block-title">Module Utilization and Page Loads Analysis</h3>
                                    <div class="block-options">

                                        <a type="button"  id="btn1" onclick=" return showChart(this,'btn2','bar','pie')" class="btn-block-option btn-alt-dark" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo" >
                                            BAR
                                        </a>
                                        <a type="button" id="btn2" onclick="return showChart(this,'btn1','pie','bar')"  class="btn-block-option btn-alt-dark" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo" >
                                            PIE
                                        </a>
                                    </div>
                                </div>
                                <div class="block-content block-content-full flex-grow-1 d-flex align-items-center"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                               <script type="text/javascript">
                                  window.onload=function(){
                                      document.getElementById('btn1').style.backgroundColor="grey";
                                      document.getElementById('btn2').style.backgroundColor="white";
                                      document.getElementById('btn1').style.color="white";
                                  }
                                  function showChart(btn1,btn2,chart1,chart2) {
                                        btn1.style.backgroundColor="grey";
                                        btn1.style.color="white";
                                        document.getElementById(btn2).style.backgroundColor="white";
                                        document.getElementById(btn2).style.color="grey";
                                        document.getElementById(chart1).style.display="";
                                        document.getElementById(chart2).style.display="none";
                                        return false;
                                  }

                               </script>
                                <div class="container">
                                                <div class="row">
                                                    <div class="col-sm-12"  style="height:250px" id="bar">
                                                        {!! $chart1->container() !!}
                                                    </div>
                                                    <div class="col-sm-12"  style="height:250px;display:none;" id="pie">
                                                        {!! $chart2->container() !!}
                                                    </div>
                                                </div>
                                </div>
                                <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
                                {!! $chart1->script() !!}
                                {!! $chart2->script() !!}

                                </div>
                                <div class="block-content bg-body-light">
                                    <div class="row items-push text-center w-100">
                                        <div class="col-sm-6">
                                            <dl class="mb-0">
                                                <dt class="font-size-h3 font-w700">
                                                    <i class="fa fa-arrow-up font-size-lg text-success"></i> {{count((array)$modpageload[0])-1}}
                                                </dt>
                                                <dd class="text-muted mb-0">Total Modules</dd>
                                            </dl>
                                        </div>
                                        <div class="col-sm-6">
                                            <dl class="mb-0">
                                                <dt class="font-size-h3 font-w700">
                                                    <i class="fa fa-arrow-up font-size-lg text-success"></i> {{$total}}
                                                </dt>
                                                <dd class="text-muted mb-0">Total Page Loads</dd>
                                            </dl>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- END Earnings Summary -->
                        </div>
                        </div>

                        <div class="row">
                        <div class="col-xl-12 d-flex flex-column my-4 mb-n4">
                            <!-- Earnings Summary -->
                            <div class="block block-rounded flex-grow-1 d-flex flex-column">
                                <div class="block-header block-header-default">
                                    <h3 class="block-title">User Registration and Logged In Analysis</h3>
                                    <div class="block-options">
                                    <select class="form-control" onchange="alert(this.value)"  name="year" style="font-size:12px;width:155px">
                                    <option  selected >Check User Logins</option>
                                    <option value="{{implode(',',$janperson)}}">January</option>
                                    <option value="{{implode(',',$febperson)}}">February</option>
                                    <option value="{{implode(',',$marperson)}}">March</option>
                                    <option value="{{implode(',',$aprperson)}}">April</option>
                                    <option value="{{implode(',',$mayperson)}}">May</option>
                                    <option value="{{implode(',',$junperson)}}">June</option>
                                    <option value="{{implode(',',$julperson)}}">July</option>
                                    <option value="{{implode(',',$augperson)}}">August</option>
                                    <option value="{{implode(',',$sepperson)}}">September</option>
                                    <option value="{{implode(',',$octperson)}}">October</option>
                                    <option value="{{implode(',',$novperson)}}">November</option>
                                    <option value="{{implode(',',$decperson)}}">December</option>
                                    </select>
                                    </div>
                                    <div class="block-options">
                                    <table>
                                                                        <tr>
                                                                            <form action="{{url('/superadmin/moduleoverviewfilter')}}" method="post" >
                                                                                {{csrf_field()}}
                                                                                <div class="col-md-4">
                                                                                    <td>

                                                                                        <select class="form-control"  name="year" style="font-size:12px;width:155px">
                                                                                            <option value="" selected>Select Year</option>

                                                                                            <?php for ($year=2019; $year <= 2050; $year++): ?>
                                                                                                              <option value="<?=$year;?>"><?=$year;?></option>
                                                                                                    <?php endfor; ?>
                                                                                        </select>
                                                                                    </td>
                                                                                </div>


                                                                                <td>
                                                                                    <div class="form-group" style="padding-top:8px;margin-top:16px;margin-left:2px;font-size:12px;">
                                                                                        <input type="submit" value="Go" class="btn btn-dark" style="font-size:12px;">
                                                                                    </div>

                                                                                </td>

                                                                                </td>

                                                                                </form>
                                                                        </tr>
                                                                    </table>
                                                                    </div>
                                </div>
                                <div class="block-content block-content-full flex-grow-1 d-flex align-items-center"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>

                                <div class="container">
                                                <div class="row">
                                                    <div class="col-sm-12"  style="height:250px" id="line">
                                                        {!! $chart3->container() !!}
                                                    </div>
                                                </div>
                                </div>
                                {!! $chart3->script() !!}


                                </div>
                                <div class="block-content bg-body-light">
                                    <div class="row items-push text-center w-100">
                                        <div class="col-sm-4">
                                            <dl class="mb-0">
                                                <dt class="font-size-h3 font-w700">
                                                    <i class="fa fa-arrow-up font-size-lg text-success"></i> {{$compsize}}
                                                </dt>
                                                <dd class="text-muted mb-0">Company Size</dd>
                                            </dl>
                                        </div>
                                        <div class="col-sm-4">
                                            <dl class="mb-0">
                                                <dt class="font-size-h3 font-w700">
                                                    <i class="fa fa-arrow-up font-size-lg text-success"></i> {{$totuser}}
                                                </dt>
                                                <dd class="text-muted mb-0">Total Registered Users</dd>
                                            </dl>
                                        </div>
                                        <div class="col-sm-4">
                                            <dl class="mb-0">
                                                <dt class="font-size-h3 font-w700">
                                                    <i class="fa fa-arrow-up font-size-lg text-success"></i> {{$totappuser}}
                                                </dt>
                                                <dd class="text-muted mb-0">Total Approved Users</dd>
                                            </dl>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- END Earnings Summary -->
                        </div>
                        </div>

                    <!-- Animation Classes -->
                    <h2 class="content-heading">Analysis Dashboard<small> You can see the module utilization percentage</small></h2>
                    <div class="row">
                        <div class="col-sm-3">
                        <a href="#">
                            <div class="block block-rounded invisible" data-toggle="appear" data-class="animated bounceIn">
                                <div class="block-content block-content-full">
                                    <div class="py-2 text-center">
                                        <div class="item item-1x item-rounded text-white mx-auto" style="background-color:rgba(255,99,132,1.0)">
                                            <i class="fa-2x fa fa-running"></i>
                                        </div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Currective Action</div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Utilized   <span style="font-size:13px;">{{round($correctiveaction,2)}} %</span></div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Page Loads <span style="font-size:13px;">{{$modpageload[0]->correctiveaction}} Times</span></div>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        <div class="col-sm-3">
                        <a href="#">
                            <div class="block block-rounded invisible" data-toggle="appear" data-class="animated flipInX">
                                <div class="block-content block-content-full">
                                    <div class="py-2 text-center">
                                        <div class="item item-1x item-rounded  text-white mx-auto" style="background-color:rgba(22,160,133,1.0)">
                                            <i class="fa-2x fa fa-exclamation-triangle"></i>
                                        </div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Enterprise Risk</div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Utilized   <span style="font-size:13px;">{{round($riskmanagement,2)}} %</span></div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Page Loads <span style="font-size:13px;">{{$modpageload[0]->riskmanagement}} Times</span></div>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>

                        <div class="col-sm-3">
                        <a href="#">
                            <div class="block block-rounded invisible" data-toggle="appear" data-class="animated flash">
                                <div class="block-content block-content-full">
                                    <div class="py-2 text-center">
                                        <div class="item item-1x item-rounded  text-white mx-auto" style="background-color:rgba(255,205,86,1.0)">
                                            <i class="fa-2x fa-2x fa fa-bullseye"></i>
                                        </div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0  text-muted" style="font-size:13px;">Objectives</div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted"  style="font-size:13px;">Utilized   <span style="font-size:13px;">{{round($objectives,2)}} %</span></div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0  text-muted" style="font-size:13px;">Page Loads <span style="font-size:13px;">{{$modpageload[0]->objectives}} Times</span></div>
                                    </div>
                                </div>

</a>                            </div>
                        </div>

                    <div class="col-sm-3">
                    <a href="#">
                            <div class="block block-rounded invisible" data-toggle="appear" data-class="animated flipInX">
                                <div class="block-content block-content-full">
                                    <div class="py-2 text-center">
                                        <div class="item item-1x item-rounded  text-white mx-auto" style="background-color:rgba(51,105,232,1.0)">
                                            <i class="fa-2x fa-2x fa fa-briefcase"></i>
                                        </div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Opportunity</div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Utilized   <span style="font-size:13px;">{{round($opportunity,2)}} %</span></div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Page Loads <span style="font-size:13px;">{{$modpageload[0]->opportunity}} Times</span></div>
                                    </div>
                                </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- END Animation Classes -->
                    <!-- Animation Classes -->
                    <div class="row">
                        <div class="col-sm-3">
                        <a href="#">
                            <div class="block block-rounded invisible" data-toggle="appear" data-class="animated bounceIn">
                                <div class="block-content block-content-full">
                                    <div class="py-2 text-center">
                                        <div class="item item-1x item-rounded text-white mx-auto" style="background-color:rgba(244,67,54,1.0)">
                                            <i class=" fa-2x fa fa-gavel"></i>
                                        </div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Compliance</div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Utilized   <span style="font-size:13px;">{{round($compliance,2)}} %</span></div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Page Loads <span style="font-size:13px;">{{$modpageload[0]->compliance}} Times</span></div>
                                    </div>
                                </div>

</a>                            </div>
                        </div>
                        <div class="col-sm-3">
                        <a href="#">
                            <div class="block block-rounded invisible" data-toggle="appear" data-class="animated flipInX">
                                <div class="block-content block-content-full">
                                    <div class="py-2 text-center">
                                       <div class="item item-1x item-rounded  text-white mx-auto" style="background-color:rgba(255,99,132,1.0)">
                                            <i class=" fa-2x fab fa-canadian-maple-leaf"></i>
                                        </div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Environment Aspects</div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Utilized   <span style="font-size:13px;">{{round($enviaspects,2)}} %</span></div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Page Loads <span style="font-size:13px;">{{$modpageload[0]->enviaspects}} Times</span></div>
                                    </div>
                                </div>

</a>                            </div>
                        </div>
                        <div class="col-sm-3">
                        <a href="#">
                            <div class="block block-rounded invisible" data-toggle="appear" data-class="animated flash">
                                <div class="block-content block-content-full">
                                    <div class="py-2 text-center">
                                        <div class="item item-1x item-rounded  text-white mx-auto" style="background-color:rgba(34,198,246,1.0)">
                                            <i class="fa-2x fa fa-chalkboard-teacher"></i>
                                        </div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Audit Management</div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Utilized   <span style="font-size:13px;">{{round($auditmanagement,2)}} %</span></div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Page Loads <span style="font-size:13px;">{{$modpageload[0]->auditmanagement}} Times</span></div>
                                    </div>
                                </div>

</a>                            </div>
                        </div>

                    <div class="col-sm-3">
                    <a href="#">
                            <div class="block block-rounded invisible" data-toggle="appear" data-class="animated flipInX">
                                <div class="block-content block-content-full">
                                    <div class="py-2 text-center">
                                        <div class="item item-1x item-rounded  text-white mx-auto" style="background-color:rgba(153,102,255,1.0)">
                                            <i class="fa-2x fab fa-medrt"></i>
                                        </div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">OHS Risk</div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Utilized   <span style="font-size:13px;">{{round($ohsrisk,2)}} %</span></div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Page Loads <span style="font-size:13px;">{{$modpageload[0]->ohsrisk}} Times</span></div>
                                    </div>
                                </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- END Animation Classes -->
<!-- Animation Classes -->
                    <div class="row">
                        <div class="col-sm-3">
                        <a href="#">
                            <div class="block block-rounded invisible" data-toggle="appear" data-class="animated bounceIn">
                                <div class="block-content block-content-full">
                                    <div class="py-2 text-center">
                                        <div class="item item-1x item-rounded  text-white mx-auto" style="background-color:rgba(255,159,64,1.0)">
                                            <i class="fa-2x fa fa-file-upload"></i>
                                        </div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Document Management</div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Utilized   <span style="font-size:13px;">{{round($documentmanagement,2)}} %</span></div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Page Loads <span style="font-size:13px;">{{$modpageload[0]->documentmanagement}} Times</span></div>
                                    </div>
                                </div>

</a>                            </div>
                        </div>
                        <div class="col-sm-3">
                        <a href="#">
                            <div class="block block-rounded invisible" data-toggle="appear" data-class="animated flipInX">
                                <div class="block-content block-content-full">
                                    <div class="py-2 text-center">
                                        <div class="item item-1x item-rounded  text-white mx-auto" style="background-color:rgba(233,159,642,1.0)">
                                            <i class="fa-2x fab fa-contao"></i>
                                        </div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Context Management</div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Utilized   <span style="font-size:13px;">{{round($contextmanagement,2)}} %</span></div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Page Loads <span style="font-size:13px;">{{$modpageload[0]->contextmanagement}} Times</span></div>
                                    </div>
                                </div>

</a>                            </div>
                        </div>
                        <div class="col-sm-3">
                        <a href="#">
                            <div class="block block-rounded invisible" data-toggle="appear" data-class="animated flash">
                                <div class="block-content block-content-full">
                                    <div class="py-2 text-center">
                                        <div class="item item-1x item-rounded  text-white mx-auto" style="background-color:rgba(233,30,99,1.0)">
                                            <i class="fa fa-2x fa-question"></i>
                                        </div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Customer Complaint</div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Utilized   <span style="font-size:13px;">{{round($complaintmanagement,2)}} %</span></div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Page Loads <span style="font-size:13px;">{{$modpageload[0]->complaintmanagement}} Times</span></div>
                                    </div>
                                </div>

</a>                            </div>
                        </div>

                    <div class="col-sm-3">
                    <a href="#">
                            <div class="block block-rounded invisible" data-toggle="appear" data-class="animated flipInX">
                                <div class="block-content block-content-full">
                                    <div class="py-2 text-center">
                                        <div class="item item-1x item-rounded  text-white mx-auto" style="background-color:rgba(233,99,205,1.0)">
                                            <i class="si fa-2x fa-2x si-rocket"></i>
                                        </div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Vendor Management</div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Utilized   <span style="font-size:13px;">{{round($vendormanagement,2)}} %</span></div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Page Loads <span style="font-size:13px;">{{$modpageload[0]->vendormanagement}} Times</span></div>
                                    </div>
                                </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- END Animation Classes -->
<!-- Animation Classes -->
                    <div class="row">
                        <div class="col-sm-3">
                        <a href="#">
                            <div class="block block-rounded invisible" data-toggle="appear" data-class="animated bounceIn">
                                <div class="block-content block-content-full">
                                    <div class="py-2 text-center">
                                        <div class="item item-1x item-rounded  text-white mx-auto" style="background-color:rgba(205,220,57,1.0)">
                                            <i class=" fa-2x fas fa-car-crash"></i>
                                        </div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Incident Report</div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Utilized   <span style="font-size:13px;">{{round($incidentreport,2)}} %</span></div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Page Loads <span style="font-size:13px;">{{$modpageload[0]->incidentreport}} Times</span></div>
                                    </div>
                                </div>
    </a>
    </div>
                        </div>

                    </div>

                    <div class="col-sm-3">
                        <a href="#">
                            <div class="block block-rounded invisible" data-toggle="appear" data-class="animated bounceIn">
                                <div class="block-content block-content-full">
                                    <div class="py-2 text-center">
                                        <div class="item item-1x item-rounded  text-white mx-auto" style="background-color:rgba(205,220,57,1.0)">
                                            <i class=" fa-2x fas fa-car-crash"></i>
                                        </div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">HR</div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Utilized   <span style="font-size:13px;">{{round($hr,2)}} %</span></div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Page Loads <span style="font-size:13px;">{{$modpageload[0]->hr}} Times</span></div>
                                    </div>
                                </div>
    </a>
    </div>
                        </div>

                    </div>

                    <div class="col-sm-3">
                        <a href="#">
                            <div class="block block-rounded invisible" data-toggle="appear" data-class="animated bounceIn">
                                <div class="block-content block-content-full">
                                    <div class="py-2 text-center">
                                        <div class="item item-1x item-rounded  text-white mx-auto" style="background-color:rgba(205,220,57,1.0)">
                                            <i class=" fa-2x fas fa-car-crash"></i>
                                        </div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Maintainance</div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Utilized   <span style="font-size:13px;">{{round($maintainance,2)}} %</span></div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Page Loads <span style="font-size:13px;">{{$modpageload[0]->maintainance}} Times</span></div>
                                    </div>
                                </div>
    </a>
    </div>
                        </div>

                    </div>

                    <div class="col-sm-3">
                        <a href="#">
                            <div class="block block-rounded invisible" data-toggle="appear" data-class="animated bounceIn">
                                <div class="block-content block-content-full">
                                    <div class="py-2 text-center">
                                        <div class="item item-1x item-rounded  text-white mx-auto" style="background-color:rgba(205,220,57,1.0)">
                                            <i class=" fa-2x fas fa-car-crash"></i>
                                        </div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Hse Observation</div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Utilized   <span style="font-size:13px;">{{round($hse,2)}} %</span></div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Page Loads <span style="font-size:13px;">{{$modpageload[0]->hse}} Times</span></div>
                                    </div>
                                </div>
    </a>
    </div>
                        </div>

                    </div>
                    <!-- END Animation Classes -->
                </div>

                <div class="row">
                        <div class="col-sm-3">
                        <a href="#">
                            <div class="block block-rounded invisible" data-toggle="appear" data-class="animated bounceIn">
                                <div class="block-content block-content-full">
                                    <div class="py-2 text-center">
                                        <div class="item item-1x item-rounded  text-white mx-auto" style="background-color:rgba(205,220,57,1.0)">
                                            <i class=" fa-2x fas fa-car-crash"></i>
                                        </div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Calibration</div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Utilized   <span style="font-size:13px;">{{round($calibration,2)}} %</span></div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Page Loads <span style="font-size:13px;">{{$modpageload[0]->calibration}} Times</span></div>
                                    </div>
                                </div>
    </a>
    </div>
                        </div>

                    </div>

                    <div class="col-sm-3">
                        <a href="#">
                            <div class="block block-rounded invisible" data-toggle="appear" data-class="animated bounceIn">
                                <div class="block-content block-content-full">
                                    <div class="py-2 text-center">
                                        <div class="item item-1x item-rounded  text-white mx-auto" style="background-color:rgba(205,220,57,1.0)">
                                            <i class=" fa-2x fas fa-car-crash"></i>
                                        </div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Occupational Health</div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Utilized   <span style="font-size:13px;">{{round($occhealth,2)}} %</span></div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Page Loads <span style="font-size:13px;">{{$modpageload[0]->occhealth}} Times</span></div>
                                    </div>
                                </div>
    </a>
    </div>
                        </div>

                    </div>

                    <div class="col-sm-3">
                        <a href="#">
                            <div class="block block-rounded invisible" data-toggle="appear" data-class="animated bounceIn">
                                <div class="block-content block-content-full">
                                    <div class="py-2 text-center">
                                        <div class="item item-1x item-rounded  text-white mx-auto" style="background-color:rgba(205,220,57,1.0)">
                                            <i class=" fa-2x fas fa-car-crash"></i>
                                        </div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Waste Management</div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Utilized   <span style="font-size:13px;">{{round($wastemanagement,2)}} %</span></div>
                                        <div class="font-size-h6 font-w600 pt-3 mb-0 text-muted" style="font-size:13px;">Page Loads <span style="font-size:13px;">{{$modpageload[0]->wastemanagement}} Times</span></div>
                                    </div>
                                </div>
    </a>
    </div>
                        </div>

                    </div>


                    <!-- END Animation Classes -->
                </div>
                <!-- END Page Content -->
@else
No Data

@endif

            <!-- END Main Container -->

@endsection
