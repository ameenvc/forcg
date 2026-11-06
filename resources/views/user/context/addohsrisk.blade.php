@extends('layouts.backend')
@section('content')
<!doctype html>
<html lang="en">

    <body>

    <!-- Hero -->
    <div class="bg-body-light">
                    <div class="content content-full">
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">

                            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-alt">
                                    <li class="breadcrumb-item">Health And Safety Risk</li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="">Add OHS Risk</a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="/user/ohsrisk/folder">Back</a>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>


        <!-- END Hero -->

        <!-- Page Content -->
        <div class="content" style="width:95%;">
        <!-- Horizontal -->
            <div class="block block-rounded">                       
                <div class="block-content block-content-full">
                    <div class="row">                                
                        <div class="col-lg-10">
                            <!-- Form Horizontal - Default Style -->
                            <?php $f=0; ?>               


                                <form class="mb-5" action="{{url('user/context/changelog/ohsrisk/submit')}}" method="post" style="margin-top:40px">
                                    {{csrf_field()}}
                                        @if(count($errors) >0 )
                                            @foreach($errors->all() as $error)
                                            <div class='alert alert-warning'>
                                                    {{ $error }}
                                            </div>
                                            @endforeach
                                        @endif	
                                        @if(Session::has('msg'))
                                            <div class="alert alert-primary">
                                                <a class="close" data-dismiss="alert">×</a>
                                                {!!Session::get('msg')!!}
                                            </div>
                                        @endif

                                    <!-- <div class="col-lg-10"> -->

                                          <input type="hidden" name="context_id" value="{{$context_id}}">
                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="department">Department</label>
                                            <div class="col-sm-12">
                                                <input type="hidden" class="form-control" id="department" name="department"  value="{{$cont_dept_id}}">
                                                <input type="text" class="form-control" readonly="true" value="{{$contdept}}" style="font-size: 13px;">
                                            </div>
                                        </div>
                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="type">Type of Risk</label>
                                            <div class="col-sm-12">
                                                <select class="form-control" id="type" name="type" style="width: 100%;font-size: 13px;" data-placeholder="Choose Type of Risk">
                                                    <option value="Hazard Based Risk"> Hazard Based Risk</option><br>
                                                    <option value="Other Risk"> Other Risk</option><br>
                                                </select>
                                            </div>
                                        </div>
                                        <?php date_default_timezone_set("Asia/Kuwait");  ?>
                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-12 col-form-label" for="team" style="font-size: 13px;">Time</label>
                                            <div class="col-sm-12">
                                               <input type="text" class="form-control" id="date_created" name="date_created" readonly="" value="<?php echo Carbon\Carbon::now()->toDateTimeString()?>" style="font-size: 13px;">
                                            </div>
                                        </div>
                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-12 col-form-label" for="activity" style="font-size: 13px;">Activity</label>
                                            <div class="col-sm-12">
                                            <input type="text" class="form-control" id="activity" name="activity"  placeholder="Enter activity" style="font-size: 13px;">
                                            </div>
                                        </div>

                                        <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="legal" style="font-size: 13px;">Related Legal Requirement</label>
                                                <div class="col-sm-12">
                                                <input type="text" class="form-control" id="legal" name="legal"  placeholder="Enter Related Legal Requirement" style="font-size: 13px;">
                                                </div>
                                        </div>

                                        <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="location" style="font-size: 13px;">Location</label>
                                                <div class="col-sm-12">
                                                <input type="text" class="form-control" id="location" name="location"  placeholder="Enter Location" style="font-size: 13px;">
                                                </div>
                                        </div>

                                        <div class="form-group" style="display:inline-block;width:49%;"> 
                                            <label class="col-sm-12 col-form-label" for="team" style="font-size: 13px;">Risk Assessment Team</label>
                                            <div class="col-sm-12">
                                            <input type="text" class="form-control" id="team" name="team"  placeholder="Enter Risk Assessment Team" style="font-size: 13px;">
                                            </div>
                                        </div><br>



                                    <!-- </div> -->

                            <div class="col-lg-15"> 
                                <table class="table table-responsive table-bordered table-striped table-vcenter " id="dynamicTable" style="width:120%;">
                                    <thead style="background-color:#FFFFFF">
                                    <tr>
                                        <th rowspan='2' style="text-transform:capitalize;font-size:13px;"><label>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspR/NR/EM&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label></th>
                                        <th rowspan='2' style="text-transform:capitalize;font-size:13px;"><label>&nbsp&nbspWhat&nbsp&nbspare&nbsp&nbsphazards?&nbsp&nbsp&nbsp&nbsp</label></th>
                                        <th rowspan='2' style="text-transform:capitalize;font-size:13px;"><label>How&nbsp&nbspwill&nbsp&nbspit&nbsp&nbspaffect?&nbsp&nbspWhom?</label></th>
                                        <th rowspan='2' style="text-transform:capitalize;font-size:13px;"><label>Risk control measure already in use</label></th>
                                        <th colspan='2' style="text-transform:capitalize;font-size:13px;"><label>Original Risk with present controls</label></th>
                                        <th rowspan='2' style="text-transform:capitalize;font-size:13px;"><label>Additional&nbsp&nbspControl&nbsp&nbspMeasures&nbsp&nbspNeeded&nbsp&nbsp</label></th>
                                        <th colspan='2' style="text-transform:capitalize;font-size:13px;"><label>Residual Risk with Additional Control</label></th>
                                        <th rowspan='2' style="text-transform:capitalize;font-size:13px;"><label>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspMonitoring&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label></th>
                                    </tr>
                                    <tr>
                                        <th style="font-size:13px;">S</th>
                                        <th style="font-size:13px;">L</th>
                                        <th style="font-size:13px;">S</th>
                                        <th style="font-size:13px;">L</th>
                                    </tr>   
                                    </thead>
                                    <tbody >
                                    <tr >  
                                        <td><select name="doc[0][R_NR_EM]" class="form-control" style="font-size:13px">
                                                <option value="Routine" >R-Routine</option>
                                                <option value="Non Routine">NR-Non Routine</option>
                                                <option value="Emergency">EM-Emergency</option>
                                            </select>
                                        </td>
                                        <td><textarea type="text" rows="3" name="doc[0][hazards]"  class="form-control" style="font-size:13px"></textarea></td>  
                                        <td><textarea type="text" rows="3" name="doc[0][affect]"  class="form-control" style="font-size:13px"></textarea></td> 
                                        <td><textarea type="text" rows="3" name="doc[0][control]"  class="form-control" style="font-size:13px;width:210px;"></textarea></td> 
                                        <td><select name="doc[0][OrS]" class="form-control"  style="width:56px;font-size:13px" >
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                </select>
                                        </td>

                                        <td><select name="doc[0][OrL]" class="form-control"  style="width:56px;font-size:13px" >
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                </select>
                                        </td>
                                        <td><textarea type="text" rows="3" name="doc[0][additional]"  class="form-control" style="font-size:13px"></textarea></td> 
                                        <td><select name="doc[0][RS]" class="form-control"  style="width:56px;font-size:13px" >
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                </select>
                                        </td>
                                    <td><select name="doc[0][RL]" class="form-control"  style="width:56px;font-size:13px" >
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            </select>
                                        </td>
                                        <td><input type="text" name="doc[0][monitor]"  class="form-control" style="font-size:13px"/></td> 
                                        <input type="hidden" name="postedby" value="{{Auth::user()->employeeid}}">

                                        <td><button type="button" name="add" id="add" class="btn btn-sm btn-light" data-toggle="tooltip" title="Add more">
                                                <i class="fa fa-fw fa-plus"></i>
                                            </button>
                                        </td>  

                                    </tr>

                                    </tbody>
                                </table>
                            </div>
                            <div>
                                <button type="submit" for="submit" class="btn btn-primary" style="font-size:13px;margin-left:900px;" data-toggle="click-ripple" onclick="return confirm('Are you sure to submit the request?')">Submit</button>
                                <button for="reset" type="reset" class="btn btn-primary" style="font-size:13px;margin-left:975px;margin-top:-57px;">Reset</button>
                            </div>
                        </form>                                       
                    </div>
                </div>
            </div>
        </div>
                    <!-- END Horizontal -->


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

                                       <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/ohsrisk/folder">
                                           <div class="block-content text-center">
                                               <i class="fa fa-2x fa-clipboard-list text-primary"></i>
                                               <p class="font-w600 font-size-sm mt-2 mb-3">
                                                   Register
                                               </p>
                                           </div>
                                       </a>

                                   </div>
                                   <!-- <div class="col-6">

                                        <a class="block block-rounded block-link-shadow bg-body mb-2" href="/user/ohsrisk/masterlist">
                                            <div class="block-content text-center">
                                                <i class="fa fa-2x fa-id-card-alt text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Master List
                                                </p>
                                            </div>
                                        </a>

                                    </div> -->
                                    <div class="col-6">
                                        <!-- Payments -->
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/ohsrisk/analysis">
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
        </div>
        <!-- END Page Container -->

           <!-- Apps Modal -->


    </body>
<script src="{{asset('js/oneui.core.min.js')}}"></script>
<script src="{{asset('js/oneui.app.min.js')}}"></script>
<script src="{{asset('js/plugins/select2/js/select2.full.min.js')}}"></script>
<script>jQuery(function () { One.helpers(['flatpickr', 'colorpicker', 'maxlength', 'select2', 'masked-inputs', 'rangeslider']);  });</script>

<script type="text/javascript">   
    var i = 0;
    $("#add").click(function(){
        ++i;
        $("#dynamicTable").append('<tr><td><select name="doc['+i+'][R_NR_EM]" class="form-control" style="font-size:12px" ><option value="Routine" >R-Routine</option><option value="Non Routine">NR-Non Routine</option><option value="Emergency">EM-Emergency</option></select></td><td><textarea type="text" rows="3" name="doc['+i+'][hazards]"  class="form-control" style="font-size:12px"></textarea></td><td><textarea type="text" rows="3" name="doc['+i+'][affect]"  class="form-control" style="font-size:12px"></textarea></td><td><textarea type="text" rows="3" name="doc['+i+'][control]"  class="form-control" style="font-size:12px"></textarea></td> <td><select name="doc['+i+'][OrS]" class="form-control"  style="width:56px;font-size:12px"><option value="1">1</option><option value="2">2</option><option value="3">3</option> <option value="4">4</option></select></td> <td><select name="doc['+i+'][OrL]" class="form-control"  style="width:56px;font-size:12px" ><option value="1">1</option><option value="2">2</option><option value="3">3</option> <option value="4">4</option></select></td><td><textarea type="text" rows="3" name="doc['+i+'][additional]"  class="form-control" style="font-size:12px"></textarea></td> <td><select name="doc['+i+'][RS]" class="form-control"  style="width:56px;font-size:12px" ><option value="1">1</option><option value="2">2</option><option value="3">3</option> <option value="4">4</option></select></td><td><select name="doc['+i+'][RL]" class="form-control"  style="width:56px;font-size:12px"><option value="1">1</option><option value="2">2</option><option value="3">3</option> <option value="4">4</option></select></td><td><input type="text" name="doc['+i+'][monitor]"  class="form-control" style="font-size:12px"/></td> <td><button type="button" name="add" id="add" class="btn btn-sm btn-light" data-toggle="tooltip" title="Remove activity"><i class="fa fa-fw remove-tr fa-times"></i></button></td></tr>');
        });
    $(document).on('click', '.remove-tr', function(){  
    $(this).parents('tr').remove();
    });
</script> 

</html>
@endsection
