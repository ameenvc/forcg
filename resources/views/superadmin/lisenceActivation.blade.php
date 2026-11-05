<!doctype html>
<html lang="en">
<head>

<style>
.form-group{
   display:flex;
   flex-direction: row;
   justify-content:left;
   align-items: center;

}
.select2-results__options,.select2{
font-size:13px ;
}


</style>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

        <title>Setilas</title>

        <meta name="description" content="Setilas">
        <meta name="author" content="Setilas">
        <meta name="robots" content="noindex, nofollow">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">


        <!-- Fonts and Styles -->
        <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>


        <link rel="stylesheet" id="css-main" href="{{asset('css/oneui.min.css')}}">
        <link rel="stylesheet" href="{{asset('js/plugins/select2/css/select2.min.css')}}">
        <link rel="stylesheet" id="css-main" href="{{asset('css/oneui.min.css')}}">


        <link rel="stylesheet" id="css-main" href="{{ mix('/css/oneui.css') }}">

        <script>window.Laravel = {!! json_encode(['csrfToken' => csrf_token(),]) !!};</script>



    @include('shared.typography.assets')
</head>
    <body>
            <!-- Main Container -->
            <main id="main-container">
      <!-- Hero -->

      <div class="bg-body-light">
                    <div class="content content-full">
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">


                            <h3 style="margin-left:40%">Client Activation<h3>


                        </div>
                    </div>
                </div>



            @if($name=="")
 <!-- Page Content -->
 <div class="content" style="width:60%">
                   <!-- Horizontal -->
                    <div class="block block-rounded">
                        <div class="block-content block-content-full">
                            <div class="row">
                                <div class="col-lg-10">
                                    <!-- Form Horizontal - Default Style -->

                    <form class="form-horizontal" method="POST" action="{{route('generate')}}" enctype="multipart/form-data">
                        {{ csrf_field()}}

                        <div class="form-group">
                            <label for="company_name" class="col-md-4 control-label">Set Company Name<span class="text-danger">*</span> </label>

                            <div class="col-md-6">
                                <input id="company_name" type="text" class="form-control" name="company_name" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">Set Client Name<span class="text-danger">*</span> </label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="new-password" class="col-md-4 control-label">Client Email-ID<span class="text-danger">*</span> </label>

                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control" name="email" required>

                            </div>
                        </div>
                        <div class="form-group">
                            <label for="new-password-confirm" class="col-md-4 control-label">Activation Code<span class="text-danger">*</span> </label>
                            <div class="col-md-6">
                                <input id="code" type="text" class="form-control" name="code">
                            </div>

                            <button type="submit" class="btn btn-primary">
                                    Get Code
                                </button>
                        </div>

                        <div class="form-group">
                            <label for="new-password-confirm" class="col-md-4 control-label">Set Client Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="new-password_confirmation" >
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="logo" class="col-md-4 control-label">Select App type</label>

                            <div class="col-md-3">
                                <input id="quickms"  style="margin-right:4px;" type="radio" value="quickms"   name="app"><label>Quick MS</label>
                            </div>
                            <div class="col-md-3">
                                <input id="quickcg" style="margin-right:4px;" type="radio"   value="quickcg"   name="app"><label>Quick CG</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="appname" class="col-md-4 control-label">Enter App Name to Display </label>

                            <div class="col-md-6">
                                <input id="appname" type="text" class="form-control" name="appname" >
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="modules" class="col-md-4 control-label">Select Modules</label>
                            <div class="col-md-6">
                                <button id="modules" type="text" class="form-control btn btn-dark" data-toggle="modal" data-id="module" data-target="#module" onclick="return false">Click to Select Modules</button>
                            </div>
                        </div>
                        <div id="module"  class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h2 class="modal-title"><b>Select Modules</b></h2>
                                                    </div>
                                                    <table class="table table-hover">
                <tr>

                  <th ><b>Modules</b></th>
                  <th style="float:right;margin-right:60px;"><b>Select</b></th>

                </tr>
                <tr>
                  <td><h4><span class="label label-success">Objectives</span></h4></td>
                  <td><div class="form-group">
                    <div class="icheckbox_flat-green">
                      <input style="float:right;margin-right:60px;" type="checkbox" id="obj" name="obj" value="0" style="float:right;" >
                    </div>
                  </div></td>


                </tr>
                <tr>
                    <td><h4><span class="label label-success">Audit Management</span></h4></td>
                  <td><div class="form-group">
                    <div class="icheckbox_flat-green">
                          <input style="float:right;margin-right:60px;" onclick="check1()" type="checkbox" id="audit" name="audit" value="0" style="float:right;">
                    </div>
                  </div></td>
                </tr>
                <tr id="tr1">
                    <td ><h4><span class="label label-success">Audit Scheduling and Logs</span></h4></td>
                  <td><div class="form-group" style="">
                    <div class="icheckbox_flat-green" >
                          <input style="float:right;margin-right:60px;" type="checkbox" id="auditschedule" name="auditschedule" value="0" style="float:right;">
                    </div>
                  </div></td>
                </tr>
                <tr>
                    <td><h4><span class="label label-success">Compliances</span></h4></td>
                  <td><div class="form-group">
                    <div class="icheckbox_flat-green">
                        <input style="float:right;margin-right:60px;" type="checkbox" id="comp" name="comp" value="0" style="float:right;">
                    </div>
                  </div></td>
                </tr>
                <tr>
                    <td><h4><span class="label label-success">Enterprise Risk</span></h4></td>
                    <td><div class="form-group">
                      <div class="icheckbox_flat-green">
                          <input style="float:right;margin-right:60px;" type="checkbox" id="rm" name="rm" value="0" style="float:right;">
                      </div>
                    </div></td>
                </tr>

                <tr>
                    <td><h4><span class="label label-success">Opportunity</span></h4></td>
                    <td><div class="form-group">
                      <div class="icheckbox_flat-green">
                          <input style="float:right;margin-right:60px;" type="checkbox" id="op" name="op" value="0" style="float:right;">
                      </div>
                    </div></td>
                </tr>
 <tr>
                  <td><h4><span class="label label-success">NCR & Corrective Actions</span></h4></td>
                  <td><div class="form-group">
                    <div class="icheckbox_flat-green">
                   <input style="float:right;margin-right:60px;" type="checkbox" id="as" name="as" value="0" style="float:right;" >
                    </div>
                  </div></td>


                </tr>
 <tr>
                  <td><h4><span class="label label-success">Environmental Aspects</span></h4></td>
                  <td><div class="form-group">
                    <div class="icheckbox_flat-green">
                     <input style="float:right;margin-right:60px;" type="checkbox" name="ea" id="ea" value="0" style="float:right;">
                    </div>
                  </div></td>
                </tr>

                <tr>
                   <td><h4><span class="label label-success">OHS Risk</span></h4></td>
                  <td><div class="form-group">
                    <div class="icheckbox_flat-green">
                   <input style="float:right;margin-right:60px;" type="checkbox" name="ohs" value="0" id="ohs" style="float:right;">
                    </div>
                  </div></td>


                </tr>
                <tr>
                    <td><h4><span class="label label-success">Document Management</span></h4></td>
                    <td><div class="form-group">
                      <div class="icheckbox_flat-green">
                     <input style="float:right;margin-right:60px;" type="checkbox" name="da" id="da" value="0" style="float:right;">
                      </div>
                    </div></td>
                </tr>
                <tr>
                    <td><h4><span class="label label-success">Customer Complaint</span></h4></td>
                    <td><div class="form-group">
                      <div class="icheckbox_flat-green">
                     <input style="float:right;margin-right:60px;" type="checkbox" name="cmpl" id="cmpl" value="0" style="float:right;">
                      </div>
                    </div></td>
                </tr>
                <tr>
                    <td><h4><span class="label label-success">Context Management</span></h4></td>
                    <td><div class="form-group">
                      <div class="icheckbox_flat-green">
                     <input style="float:right;margin-right:60px;" type="checkbox" name="cntx" id="cntx" value="0" style="float:right;">
                      </div>
                    </div></td>
                </tr>
                <tr>

                    <td><h4><span class="label label-success">Vendor Management</span></h4></td>
                    <td><div class="form-group">
                      <div class="icheckbox_flat-green">
                     <input style="float:right;margin-right:60px;" type="checkbox" name="vendor" id="vendor" value="0" style="float:right;">
                      </div>
                    </div></td>

                </tr>
                <tr>

                    <td><h4><span class="label label-success">Incident Report</span></h4></td>
                    <td><div class="form-group">
                      <div class="icheckbox_flat-green">
                     <input style="float:right;margin-right:60px;" type="checkbox" name="incident" id="incident" value="0" style="float:right;">
                      </div>
                    </div></td>
                  </tr><tr>
                    <td><h4><span class="label label-success">Hse Observation</span></h4></td>
                    <td><div class="form-group">
                      <div class="icheckbox_flat-green">
                     <input style="float:right;margin-right:60px;" type="checkbox" name="hse" id="hse" value="0" style="float:right;">
                      </div>
                    </div></td>

                </tr>

                <tr>

                    <td><h4><span class="label label-success">HR</span></h4></td>
                    <td><div class="form-group">
                      <div class="icheckbox_flat-green">
                     <input style="float:right;margin-right:60px;" type="checkbox" name="hr" id="hr" value="0" style="float:right;">
                      </div>
                    </div></td>

                </tr>
                <tr>

                    <td><h4><span class="label label-success">Maintenance</span></h4></td>
                    <td><div class="form-group">
                      <div class="icheckbox_flat-green">
                     <input style="float:right;margin-right:60px;" type="checkbox" name="maintainance" id="maintainance" value="0" style="float:right;">
                      </div>
                    </div></td>

                </tr>
                <tr>

                    <td><h4><span class="label label-success">Calibration</span></h4></td>
                    <td><div class="form-group">
                      <div class="icheckbox_flat-green">
                     <input style="float:right;margin-right:60px;" type="checkbox" name="calibration" id="calibration" value="0" style="float:right;">
                      </div>
                    </div></td>

                </tr>
                <tr>

                    <td><h4><span class="label label-success">Occupational Health</span></h4></td>
                    <td><div class="form-group">
                      <div class="icheckbox_flat-green">
                     <input style="float:right;margin-right:60px;" type="checkbox" name="occhealth" id="occhealth" value="0" style="float:right;">
                      </div>
                    </div></td>

                </tr>
                <tr>

                    <td><h4><span class="label label-success">Waste Management</span></h4></td>
                    <td><div class="form-group">
                      <div class="icheckbox_flat-green">
                     <input style="float:right;margin-right:60px;" type="checkbox" name="wastemanagement" id="wastemanagement" value="0" style="float:right;">
                      </div>
                    </div></td>

                </tr>
              </table>
                      <div class="modal-footer">
                      <input type="reset" class="btn btn-primary" name="submit" value="Reset" style="float:right;">
                      <button type="button" class="btn btn-dark" onclick='return countmodules()' data-dismiss="modal">Set</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                        <div class="form-group">
                            <label for="compsize" class="col-md-4 control-label">Set Company Size</label>

                            <div class="col-md-6">
                                <input id="compsize" type="text" class="form-control" name="compsize" >
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="logo" class="col-md-4 control-label">Select Logo</label>

                            <div class="col-md-6">
                                <input id="logo" type="file"  name="logo">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4 ml-10">
                                <button type="submit" class="btn btn-primary" disabled>
                                   Activate
                                </button>
                            </div>
                        </div>
                    </form>
                    </div>
                    </div>
                </div>
            </div>
            </div>
@else
<div class="content" style="width:60%">
                   <!-- Horizontal -->
                    <div class="block block-rounded">
                        <div class="block-content block-content-full">
                            <div class="row">
                                <div class="col-lg-10">
                                    <!-- Form Horizontal - Default Style -->
                                   @if(Session::has('msg'))
                                        <div class="alert alert-danger">
                                            <a class="close" data-dismiss="alert">×</a>
                                            {!!Session::get('msg')!!}
                                        </div>
                                    @endif
                                    @if(Session::has('mailed'))
                                        <div class="alert alert-primary">
                                            <a class="close" data-dismiss="alert">×</a>
                                           {{Session::get('mailed')}}
                                        </div>
                                    @endif

                    <form class="form-horizontal" method="POST" action="{{route('activationComplete')}}" enctype="multipart/form-data">
                        {{ csrf_field()}}

                        <div class="form-group">
                            <label for="company_name" class="col-md-4 control-label">Company Name<span class="text-danger">*</span> </label>

                            <div class="col-md-6">
                                <input id="company_name1" type="text" class="form-control" name="company_name1" disabled value="{{$company_name}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">Client Name</label>

                            <div class="col-md-6">
                                <input id="name1" type="text" class="form-control" name="name1" value="{{$name}}" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="new-password" class="col-md-4 control-label">Client Username/Email</label>

                            <div class="col-md-6">
                                <input id="email1" type="text" class="form-control" name="email1" value="{{$email}}" disabled>

                            </div>
                        </div>

                        <div class="form-group"  >
                            <label for="new-password-confirm" class="col-md-4 control-label">Activation Code <span class="text-danger">*</span> </label>
                            <div class="col-md-6">
                                <input id="code" type="password" class="form-control" name="code" required>
                            </div>

                                <button type="submit" class="btn btn-primary col-md-4"  disabled>
                                    Get Code
                                </button>
                        </div>



                        <div class="form-group">
                            <label for="new-password-confirm" class="col-md-4 control-label">Set Client Password<span class="text-danger">*</span> </label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="logo" class="col-md-4 control-label">Select App type</label>

                            <div class="col-md-3">
                                <input id="quickms"  style="margin-right:4px;" type="radio" value="quickms"   name="app"><label>Quick MS</label>
                            </div>
                            <div class="col-md-3">
                                <input id="quickcg" style="margin-right:4px;" type="radio"   value="quickcg"   name="app"><label>Quick CG</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="appname" class="col-md-4 control-label">Enter App Name to Display <span class="text-danger">*</span> </label>

                            <div class="col-md-6">
                                <input id="appname" type="text" class="form-control" name="appname" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="modules" class="col-md-4 control-label">Select Modules</label>
                            <div class="col-md-6">
                                <button id="modules" type="text" class="form-control btn btn-dark" data-toggle="modal" data-id="module" data-target="#module" onclick="return false">Click to Select Modules</button>
                            </div>
                        </div>
                        <div id="module"  class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h2 class="modal-title"><b>Select Modules</b></h2>
                                                    </div>
                                                    <table class="table table-hover">
                <tr>

                  <th ><b>Modules</b></th>
                  <th style="float:right;margin-right:60px;"><b>Select</b></th>

                </tr>
                <tr>
                  <td><h4><span class="label label-success">Objectives</span></h4></td>
                  <td><div class="form-group">
                    <div class="icheckbox_flat-green">
                      <input style="float:right;margin-right:60px;" type="checkbox" id="obj" name="obj" value="0" style="float:right;" >
                    </div>
                  </div></td>


                </tr>
                <tr>
                    <td><h4><span class="label label-success">Audit Management</span></h4></td>
                  <td><div class="form-group">
                    <div class="icheckbox_flat-green">
                          <input style="float:right;margin-right:60px;" onclick="check1()" type="checkbox" id="audit" name="audit" value="0" style="float:right;">
                    </div>
                  </div></td>
                </tr>
                <tr id="tr1">
                    <td ><h4><span class="label label-success">Audit Scheduling and Logs</span></h4></td>
                  <td><div class="form-group" style="">
                    <div class="icheckbox_flat-green" >
                          <input style="float:right;margin-right:60px;" type="checkbox" id="auditschedule" name="auditschedule" value="0" style="float:right;">
                    </div>
                  </div></td>
                </tr>
                <tr>
                    <td><h4><span class="label label-success">Compliances</span></h4></td>
                  <td><div class="form-group">
                    <div class="icheckbox_flat-green">
                        <input style="float:right;margin-right:60px;" type="checkbox" id="comp" name="comp" value="0" style="float:right;">
                    </div>
                  </div></td>
                </tr>
                <tr>
                    <td><h4><span class="label label-success">Enterprise Risk</span></h4></td>
                    <td><div class="form-group">
                      <div class="icheckbox_flat-green">
                          <input style="float:right;margin-right:60px;" type="checkbox" id="rm" name="rm" value="0" style="float:right;">
                      </div>
                    </div></td>
                </tr>

                <tr>
                    <td><h4><span class="label label-success">Opportunity</span></h4></td>
                    <td><div class="form-group">
                      <div class="icheckbox_flat-green">
                          <input style="float:right;margin-right:60px;" type="checkbox" id="op" name="op" value="0" style="float:right;">
                      </div>
                    </div></td>
                </tr>
 <tr>
                  <td><h4><span class="label label-success">NCR & Corrective Actions</span></h4></td>
                  <td><div class="form-group">
                    <div class="icheckbox_flat-green">
                   <input style="float:right;margin-right:60px;" type="checkbox" id="as" name="as" value="0" style="float:right;" >
                    </div>
                  </div></td>


                </tr>
 <tr>
                  <td><h4><span class="label label-success">Environmental Aspects</span></h4></td>
                  <td><div class="form-group">
                    <div class="icheckbox_flat-green">
                     <input style="float:right;margin-right:60px;" type="checkbox" name="ea" id="ea" value="0" style="float:right;">
                    </div>
                  </div></td>
                </tr>

                <tr>
                   <td><h4><span class="label label-success">OHS Risk</span></h4></td>
                  <td><div class="form-group">
                    <div class="icheckbox_flat-green">
                   <input style="float:right;margin-right:60px;" type="checkbox" name="ohs" value="0" id="ohs" style="float:right;">
                    </div>
                  </div></td>


                </tr>
                <tr>
                    <td><h4><span class="label label-success">Document Management</span></h4></td>
                    <td><div class="form-group">
                      <div class="icheckbox_flat-green">
                     <input style="float:right;margin-right:60px;" type="checkbox" name="da" id="da" value="0" style="float:right;">
                      </div>
                    </div></td>
                </tr>
                <tr>
                    <td><h4><span class="label label-success">Customer Complaint</span></h4></td>
                    <td><div class="form-group">
                      <div class="icheckbox_flat-green">
                     <input style="float:right;margin-right:60px;" type="checkbox" name="cmpl" id="cmpl" value="0" style="float:right;">
                      </div>
                    </div></td>
                </tr>
                <tr>
                    <td><h4><span class="label label-success">Context Management</span></h4></td>
                    <td><div class="form-group">
                      <div class="icheckbox_flat-green">
                     <input style="float:right;margin-right:60px;" type="checkbox" name="cntx" id="cntx" value="0" style="float:right;">
                      </div>
                    </div></td>
                </tr>
                <tr>

                    <td><h4><span class="label label-success">Vendor Management</span></h4></td>
                    <td><div class="form-group">
                      <div class="icheckbox_flat-green">
                     <input style="float:right;margin-right:60px;" type="checkbox" name="vendor" id="vendor" value="0" style="float:right;">
                      </div>
                    </div></td>

                </tr>
                <tr>

                <td><h4><span class="label label-success">Incident Report</span></h4></td>
                <td><div class="form-group">
                  <div class="icheckbox_flat-green">
                 <input style="float:right;margin-right:60px;" type="checkbox" name="incident" id="incident" value="0" style="float:right;">
                  </div>
                </div></td></tr><tr>
                <td><h4><span class="label label-success">Hse Observation</span></h4></td>
                <td><div class="form-group">
                  <div class="icheckbox_flat-green">
                 <input style="float:right;margin-right:60px;" type="checkbox" name="hse" id="hse" value="0" style="float:right;">
                  </div>
                </div></td>

            </tr>
            <tr>

                <td><h4><span class="label label-success">HR</span></h4></td>
                <td><div class="form-group">
                  <div class="icheckbox_flat-green">
                 <input style="float:right;margin-right:60px;" type="checkbox" name="hr" id="hr" value="0" style="float:right;">
                  </div>
                </div></td>

            </tr>
            <tr>

                <td><h4><span class="label label-success">Maintenance</span></h4></td>
                <td><div class="form-group">
                  <div class="icheckbox_flat-green">
                 <input style="float:right;margin-right:60px;" type="checkbox" name="maintainance" id="maintainance" value="0" style="float:right;">
                  </div>
                </div></td>

            </tr>
            <tr>

                <td><h4><span class="label label-success">Calibration</span></h4></td>
                <td><div class="form-group">
                  <div class="icheckbox_flat-green">
                 <input style="float:right;margin-right:60px;" type="checkbox" name="calibration" id="calibration" value="0" style="float:right;">
                  </div>
                </div></td>

            </tr>
            <tr>

                <td><h4><span class="label label-success">Occupational Health</span></h4></td>
                <td><div class="form-group">
                  <div class="icheckbox_flat-green">
                 <input style="float:right;margin-right:60px;" type="checkbox" name="occhealth" id="occhealth" value="0" style="float:right;">
                  </div>
                </div></td>

            </tr>
            <tr>

                <td><h4><span class="label label-success">Waste Management</span></h4></td>
                <td><div class="form-group">
                  <div class="icheckbox_flat-green">
                 <input style="float:right;margin-right:60px;" type="checkbox" name="wastemanagement" id="wastemanagement" value="0" style="float:right;">
                  </div>
                </div></td>

            </tr>

              </table>
                      <div class="modal-footer">
                      <input type="reset" class="btn btn-primary" name="submit" value="Reset" style="float:right;">
                      <button type="button" class="btn btn-dark" onclick='return countmodules()' data-dismiss="modal">Set</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                        <div class="form-group">
                            <label for="compsize" class="col-md-4 control-label">Set Company Size<span class="text-danger">*</span> </label>

                            <div class="col-md-6">
                                <input id="compsize" type="text" class="form-control" name="compsize" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="logo" class="col-md-4 control-label">Select Logo</label>

                            <div class="col-md-6">
                                <input id="logo" type="file"  name="logo" >
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4 ml-10">
                                <button type="submit" class="btn btn-primary">
                                   Activate
                                </button>
                            </div>
                        </div>
                    </form>


                        </div>
                    </div>
                </div>
            </div>
            </div>

@endif
</main>     <!-- END Main Container -->

            <!-- Footer -->
            {{--<footer id="page-footer" class="bg-body-light">
                <div class="content py-3">
                    <div class="row font-size-sm">
                        <div class="col-sm-6 order-sm-2 py-1 text-center text-sm-right">
                            Crafted with <i class="fa fa-heart text-danger"></i> by <a class="font-w600" href="https://1.envato.market/ydb" target="_blank">pixelcave</a>
                        </div>
                        <div class="col-sm-6 order-sm-1 py-1 text-center text-sm-left">
                            <a class="font-w600" href="https://1.envato.market/AVD6j" target="_blank">OneUI</a> &copy; <span data-toggle="year-copy"></span>
                        </div>
                    </div>
                </div>
            </footer>--}}
            <!-- END Footer -->

            <!-- Apps Modal -->
                    <!-- END Page Container -->

        <script src="{{asset('js/oneui.core.min.js')}}"></script>
        <script src="{{asset('js/oneui.app.min.js')}}"></script>

        <script src="{{asset('js/plugins/select2/js/select2.full.min.js')}}"></script>
        <script>jQuery(function () {One.helpers(['flatpickr', 'colorpicker', 'maxlength', 'select2', 'masked-inputs', 'rangeslider']);  });</script>


</body>
</html>


<script type="text/javascript">
function countmodules(){
    count=0;
    if(document.getElementById('obj').checked)
    count=count+1;
    if(document.getElementById('audit').checked)
    count=count+1;
    if(document.getElementById('auditschedule').checked){
    if(document.getElementById('audit').checked)
       count=count+1;
    }
    if(document.getElementById('comp').checked)
    count=count+1;
    if(document.getElementById('rm').checked)
    count=count+1;
    if(document.getElementById('op').checked)
    count=count+1;
    if(document.getElementById('as').checked)
    count=count+1;
    if(document.getElementById('ea').checked)
    count=count+1;
    if(document.getElementById('ohs').checked)
    count=count+1;
    if(document.getElementById('da').checked)
    count=count+1;
    if(document.getElementById('cmpl').checked)
    count=count+1;
    if(document.getElementById('cntx').checked)
    count=count+1;
    if(document.getElementById('vendor').checked)
    count=count+1;
    if(document.getElementById('incident').checked)
    count=count+1;
    if(document.getElementById('hse').checked)
    count=count+1;
    if(document.getElementById('hr').checked)
    count=count+1;
    if(document.getElementById('maintainance').checked)
    count=count+1;
    if(document.getElementById('calibration').checked)
    count=count+1;
    if(document.getElementById('occhealth').checked)
    count=count+1;
    if(document.getElementById('wastemanagement').checked)
    count=count+1;
    document.getElementById('modules').innerHTML=count+" Modules Selected";
    return false;
}
window.onload=function(){
 check();
};
function check(){

  if(!document.getElementById('audit').checked){
      document.getElementById('auditschedule').disabled=true;
      document.getElementById('tr1').style.opacity="0.3";
      document.getElementById('tr1').style.backgroundColor="transparent";
      }

  }
function check1(){
    if(document.getElementById('audit').checked)
    {
      document.getElementById('auditschedule').disabled=false;
      document.getElementById('tr1').style.opacity="";
      document.getElementById('tr1').style.backgroundColor="";
    }
    else if(!document.getElementById('audit').checked)
   {
      document.getElementById('auditschedule').disabled=true;
      document.getElementById('tr1').style.opacity="0.3";
      document.getElementById('tr1').style.backgroundColor="transparent";
   }
  }
</script>
