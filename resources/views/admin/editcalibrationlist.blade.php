@extends('admin.datatable')

@section('content')
<!-- Page Content -->
<div class="content">
    <!-- Horizontal -->
    <div class="block block-rounded">
        <div class="block-header">
            <h3 class="block-title">Edit Calibration Details</h3>
            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="/admin/calibrationlist">Back</a>
                    </li>
                </ol>
            </nav>


        </div>
        <div class="block-content block-content-full" style="width:80%;">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <!-- Form Horizontal - Default Style -->
                    <form method="post" action="{{url('/admin/calinstupdate')}}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        
                        @if(Session::has('msg'))
                        <div class="alert alert-primary">
                            <a class="close" data-dismiss="alert">×</a>
                            {!!Session::get('msg')!!}
                        </div>
                        @endif
                        @foreach($caldetails as $cal)
                        @php
                            $dalofcal = $cal->dateofcal;
                            $date = date('Y-m-d',strtotime($dalofcal));
                            $dt = explode('-',$date);
                            $caldateval = $dalofcal == null ? null :  $dt[2]."-".$dt[1]."-".$dt[0];

                            $duedt = $cal->Duedate;
                            $ddate = date('Y-m-d',strtotime($duedt));
                            $dudat = explode('-',$ddate);
                            $dudateval = $dudat[2]."-".$dudat[1]."-".$dudat[0];

                            $status = $cal->Status;
                            $status_val = $status == null ? "Not Calibrated" : $cal->Status;
                        @endphp
                        <div class="table-responsive">
                        <table class="table table-bordered table-striped" style="margin-bottom:70px;">
                            <tr>
                                <td class="col-sm-4">
                                    <label class="col-form-label" style="font-size: 13px;" for="type">Instrument Number</label>
                                </td>
                                <td class="col-sm-8">
                                    <span style="font-size:13px;margin-left:20px;"><b>{{$cal->Instrument_no}}</b></span> 
                                </td>
                            </tr>

                        
                            <tr>
                                <td class="col-sm-4">
                                    <label class="col-form-label" style="font-size: 13px;" for="type">Description</label>
                                </td>
        
                                <td class="col-sm-8">
                                    <textarea name="desc[]" class="form-control" style="font-size: 13px;">{{$cal->Instrument_desc}}</textarea>
                                </td>
                            </tr>
                            
                            <tr>
                                <td class="col-sm-4">
                                    <label class=" col-form-label" style="font-size: 13px;" for="type">Department</label>
                                </td>
                                <td class="col-sm-8">
                                <!--<input type="text" class="form-control" style="font-size: 13px;"   name="dept[]" value="{{$cal->Department}}">-->
                                <select class="form-control" name ="dept[]" style="font-size: 13px;" required="true">    
                                    <option value=""style="font-size: 13px;">choose Department</option>
                                        @if(count($dept)> 0 )
                                            @foreach($dept as $value)
                                                <option value=" {{$value->id}}"<?php if($cal->Department == $value->id){ ?> selected="selected"<?php } ?>> {{ $value->departmentname}}</option></br>
                                            @endforeach
                                        @endif
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td class="col-sm-4">
                                    <label class=" col-form-label" style="font-size: 13px;" for="type">Holder</label>
                                </td>
                                <td class="col-sm-8">
                                <input type="text" class="form-control" style="font-size: 13px;"   name="holder[]" value="{{$cal->Holder}}">
                                </td>
                            </tr>

                            <tr>
                                <td class="col-sm-4">
                                    <label class=" col-form-label" style="font-size: 13px;" for="type">Capacity</label>
                                </td>
                                <td class="col-sm-8">
                                <input type="text" class="form-control" style="font-size: 13px;"   name="capacity[]" value="{{$cal->Capacity}}">
                                </td>
                            </tr>

                            <!-- <tr>
                                <td class="col-sm-4">
                                    <label class=" col-form-label" style="font-size: 13px;" for="type">Type</label>
                                </td>
                                <td class="col-sm-8">
                                <select class="form-control" name ="type[]" style="font-size: 13px;" required="true">    
                                    <option value=""style="font-size: 13px;">choose Type</option>
                                    <option value="External"style="font-size: 13px;" <?php //if(($cal->Type == "External") || ($cal->Type == "EXTERNAL") || ($cal->Type == "external")){ ?> selected="selected"<?php //} ?>>External</option>
                                    <option value="Internal"style="font-size: 13px;" <?php //if(($cal->Type == "Internal") || ($cal->Type == "INTERNAL") || ($cal->Type == "internal")){ ?> selected="selected"<?php //} ?>>Internal</option>         
                                </select>
                                </td>
                            </tr> -->

                            <tr>
                                <td class="col-sm-4">
                                    <label class=" col-form-label" style="font-size: 13px;" for="type">Standard Edition</label>
                                </td>
                                <td class="col-sm-8">
                                    <input type="text" class="form-control" style="font-size: 13px;" name="standard_edition[]" value="{{$cal->standard_edition}}">
                                </td>
                            </tr>

                            <tr>
                                <td class="col-sm-4">
                                    <label class=" col-form-label" style="font-size: 13px;" for="type">Acceptance Criteria</label>
                                </td>
                                <td class="col-sm-8">
                                    <input type="text" class="form-control" style="font-size: 13px;" name="acceptance_criteria[]" value="{{$cal->acceptance_criteria}}">
                                </td>
                            </tr>
                            
                            <tr>
                                <td class="col-sm-4">
                                    <label class=" col-form-label" style="font-size: 13px;" for="type">Frequency</label>
                                </td>
                                <td class="col-sm-8">
                                <!-- <input type="text" class="form-control" style="font-size: 13px;"   name="dept[]" value="{{$cal->Frequency}}"> -->
                                    <select class="form-control" name ="freq[]" style="font-size: 13px;" required="true">    
                                    <option value=""style="font-size: 13px;">choose frequency</option>
                                        @if(count($freq)> 0 )
                                            @foreach($freq as $value)
                                                <option value=" {{$value->frequency}}"<?php if(trim(strtolower($cal->Frequency)) == trim(strtolower($value->frequency))){ ?> selected="selected"<?php } ?>> {{ $value->frequency}}</option></br>
                                            @endforeach
                                        @endif
                                    </select>
                                </td>
                            </tr>
                            @if(($cal->Type == "External") || ($cal->Type == "EXTERNAL") || ($cal->Type == "external"))
                            <tr>
                                <td class="col-sm-4">
                                    <label class=" col-form-label" style="font-size: 13px;" for="type">Agency</label>
                                </td>
                                <td class="col-sm-8">
                                <input type="text" class="form-control" style="font-size: 13px;"   name="agency[]" value="{{$cal->Agency}}">
                                </td>
                            </tr>
                            @endif


                            <tr>
                                <td class="col-sm-4">
                                    <label class="col-form-label" style="font-size: 13px;" for="type">Date of Cal</label>
                                </td>
                                <td class="col-sm-8">
                                @if($cal->dateofcal=="1970-01-01")
                                <input type="text" class="form-control" style="font-size: 13px;" required  name="caldt[]" value="">
                                
                                @else
                                <input type="text" class="form-control" style="font-size: 13px;" required  name="caldt[]" value="{{$caldateval}}">
                                @endif
                                <span style="font-size:11px;color:red;">Date should be in d-m-Y Format. For example : 14-02-2021</span>
                                </td>
                            </tr>
                            <!--
                            <tr>
                                <td class="col-sm-4">
                                    <label class=" col-form-label" style="font-size: 13px;" for="type">Due Date</label>
                                </td>
                                <td class="col-sm-8">
                                <input type="text" class="form-control" style="font-size: 13px;" required  name="duedt[]" value="{{$dudateval}}">
                                </td>
                            </tr>
                            
                            <tr>
                                <td class="col-sm-4">
                                    <label class=" col-form-label" style="font-size: 13px;" for="type">Status</label>
                                </td>
                                <td class="col-sm-8">
                                <input type="text" class="form-control" style="font-size: 13px;" required  name="status[]" value="{{$status}}">
                                </td>
                            </tr>
                            -->

                            <input type="hidden" name='type[]' value="<?php echo $cal->Type; ?>">
                            <input type="hidden" name='id[]' value="<?php echo $cal->Instrument_no; ?>">

                        </table>
                        </div>
                        @endforeach
                        <table style="margin-bottom:20px;">
                            <tr>
                                <td class="text-center"><input type="submit" name="submit" class="btn btn-primary text-center" style="font-size:12px;" value="Update"></td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END Page Content -->

<script>
    function ddlselect() {
        var d = document.getElementById("dept");
        var displaytext = d.options[d.selectedIndex].text;
        document.getElementById("dept").value = displaytext;

    }

</script>
<script>
    function validation() {


        if (document.getElementById('dept').value == 0) {
            alert('Please select a department');
            document.getElementById('dept').focus();
            return false;
        }



        // always return false to prevent form from submission.
    }

</script>
@endsection
