{{-- @extends('admin.datatable') --}}
@extends('layouts.backend')
@section('content')

<main id="main-container">
    <!-- Services -->
    <!-- Hero -->

    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content" style="width:88%;margin-left:70px;margin-top:-75px">
        <!-- Horizontal -->
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">Edit Waste Details</h3>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb" style="margin-left:70px">
                    <ol class="breadcrumb breadcrumb-alt">

                        <li class="breadcrumb-item" aria-current="page">

                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="/user/wastemgmt">Back</a>
                        </li>
                    </ol>
                </nav>


            </div>
            <div class="block-content block-content-full" style="width:90%;margin-left:100px">
                <div class="row">
                    <div class="col-md-10">
                        <!-- Form Horizontal - Default Style -->
                        <form method="post" action="{{url('/user/waste/updatewaste')}}" enctype="multipart/form-data">
		                  {{ csrf_field() }}

                            @if(Session::has('msg'))
                            <div class="alert alert-primary">
                                <a class="close" data-dismiss="alert">×</a>
                                {!!Session::get('msg')!!}
                            </div>
                            @endif
                            <?php
                              $id = 0;
                            ?>
                            @foreach($data as $val)

                           <table class="table table-bordered table-striped" style="margin-bottom:70px;">

                           <tr>
                                 <td class="col-sm-4">
                                     <label class=" col-form-label" style="font-size: 13px;" for="type">Department</label>
                                   </td>
                                   <td class="col-sm-8">

                                   <select class="form-control" name ="dept[]" style="font-size: 13px;" required="true">
                                        <option value=""style="font-size: 13px;">choose Department</option>
                                            @if(count($dept)> 0 )
                                                @foreach($dept as $value)
                                                    <option value=" {{$value->id}}"<?php if($val->department == $value->id){ ?> selected="selected"<?php } ?>> {{ $value->departmentname}}</option></br>
                                                @endforeach
                                            @endif
                                       </select>
                                   </td>
                               </tr>

                               <tr>
                                 <td class="col-sm-4">
                                     <label class=" col-form-label" style="font-size: 13px;" for="type">Responsilbility</label>
                                   </td>
                                   <td class="col-sm-8">
                                   <select class="form-control" name="responsibility[]" id="responsibility[]" style="font-size: 13px;" required="true">
                                    <option value="">Choose Responsibility</option>
                                        @foreach($users as $user)
                                        <option value="{{$user->name}}"<?php if($val->responsibility == $user->name){ ?> selected="selected"<?php } ?>>{{$user->name}}</option>
                                        @endforeach
                                    </select>
                                   </td>
                               </tr>


                               <tr>
                                 <td class="col-sm-4">
                                     <label class=" col-form-label" style="font-size: 13px;" for="type">Waste Name</label>
                                   </td>
                                   <td class="col-sm-8">
                                   <input type="text" class="form-control" style="font-size: 13px;"   name="wname[]" value="{{$val->waste_name}}">
                                   </td>
                               </tr>

                               <tr>
                                <td class="col-sm-4">
                                    <label class=" col-form-label" style="font-size: 13px;" for="wcode">Waste Code</label>
                                  </td>
                                  <td class="col-sm-8">
                                  <input type="text" class="form-control" style="font-size: 13px;"   name="wcode[]" value="{{$val->waste_code}}">
                                  </td>
                              </tr>
                              <tr>
                                <td class="col-sm-4">
                                    <label class=" col-form-label" style="font-size: 13px;" for="wdisposalmethod">Disposal Method</label>
                                  </td>
                                  <td class="col-sm-8">
                                    <select class="form-control" name="wdisposalmethod[]" id="type[]" style="font-size: 13px;" required="true">
                                        <option value="" selected="" disabled>Choose Waste Disposal method </option>
                                        @foreach($wastedisposalmethods as $w)
                                        <option value="{{$w->id}}"<?php if($val->disposal_method == $w->id){ ?> selected="selected"<?php } ?>>{{$w->value}}</option>
                                        @endforeach
                                    </select>
                                  {{-- <input type="text" class="form-control" style="font-size: 13px;"   name="wdisposalmethod[]" value="{{$val->disposal_method}}"> --}}
                                  </td>
                              </tr>
                               <tr>
                                 <td class="col-sm-4">
                                     <label class=" col-form-label" style="font-size: 13px;" for="type">Type of Scrap</label>
                                   </td>
                                   <td class="col-sm-8">
                                     <select class="form-control" name="type[]" id="type[]" style="font-size: 13px;" required="true">
                                        <option value="" selected="" disabled>Choose Waste Type </option>
                                        @foreach($wastetype as $w)
                                        <option value="{{$w->name}}"<?php if($val->waste_type == $w->name){ ?> selected="selected"<?php } ?>>{{$w->name}}</option>
                                        @endforeach
                                    </select>
                                   </td>
                               </tr>


                               <tr>
                                 <td class="col-sm-4">
                                     <label class=" col-form-label" style="font-size: 13px;" for="type">Monthly Expected quantity</label>
                                   </td>
                                   <td class="col-sm-8">
                                   <input type="text" class="form-control" style="font-size: 13px;"   name="mqty[]" value="{{$val->monthly_expected_qty}}">
                                   </td>
                               </tr>

                               <tr>
                                 <td class="col-sm-4">
                                     <label class=" col-form-label" style="font-size: 13px;" for="type">Yearly Expected quantity</label>
                                   </td>
                                   <td class="col-sm-8">
                                   <input type="text" class="form-control" style="font-size: 13px;"   name="yqty[]" value="{{$val->yearly_expected_qty}}">
                                   </td>
                               </tr>


                               <tr>
                                 <td class="col-sm-4">
                                     <label class=" col-form-label" style="font-size: 13px;" for="type">Units</label>
                                   </td>
                                   <td class="col-sm-8">
                                     <select class="form-control" name="units[]" id="units[{{$id}}]" style="font-size: 13px;" required="true" onchange="SelectCheck(this);">
                                        <option value="" selected="" disabled>Choose Waste Units </option>
                                        @foreach($wasteunits as $unit)
                                        <option value="{{$unit->name}}"<?php if($val->unit == $unit->name){ ?> selected="selected"<?php } ?>>{{$unit->name}}</option>
                                        @endforeach
                                        <option id="unitoth[]" value="Other"<?php if($val->unit == "Other"){ ?> selected="selected"<?php } ?>>Other</option>
                                    </select>
                                   </td>
                               </tr>

                               @if($val->unit=="Other")
                               <tr id="unit_other[{{$id}}]">
                                 <td class="col-sm-4" >
                                     <label class=" col-form-label" style="font-size: 13px;" for="unit_other"></label>
                                   </td>
                                   <td class="col-sm-8">
                                   <input type="text" class="form-control" style="font-size: 13px;" name="units_other[]" id="units_other[]" value="{{$val->unit_other}}">
                                   </td>
                               </tr>
                               @elseif($val->unit!="Other")
                                 <tr id="unit_other[{{$id}}]" style="display:none;">
                                    <td class="col-sm-4">
                                        <label class=" col-form-label" style="font-size: 13px;" for="unit_other"></label>
                                    </td>
                                    <td class="col-sm-8">
                                    <input type="text" class="form-control" style="font-size: 13px;" name="units_other[]" id="units_other[]" value="">
                                    </td>
                                  </tr>
                               @endif

                               <tr>
                                 <td class="col-sm-4">
                                     <label class=" col-form-label" style="font-size: 13px;" for="type">Agency</label>
                                   </td>
                                   <td class="col-sm-8">
                                   <input type="text" class="form-control" style="font-size: 13px;"   name="agency[]" value="{{$val->agency}}">
                                   </td>
                               </tr>


                               <input type="hidden" name='id[]' value="<?php echo $val->id; ?>">

                           </table>
                           <?php $id= $id+1; ?>
                         @endforeach
                         <table style="margin-bottom:20px;">
                             <tr>
                                <td><input type="submit" name="submit" class="btn btn-primary text-center" style="font-size:12px;margin-left:550px;" value="Update"></td>
                              </tr>
                         </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Page Content -->
</main>
<!-- END Main Container -->

<script>

function SelectCheck(nameSelect)
{
    var id = nameSelect.id.substr(nameSelect.id.search('\\[')+1).replace(']','');
    if(nameSelect.value == "Other")
    {
       document.getElementById(`unit_other[${id}]`).style.display="";
    }
    else
    {
        document.getElementById(`unit_other[${id}]`).style.display="none";
    }

}

</script>



@endsection
