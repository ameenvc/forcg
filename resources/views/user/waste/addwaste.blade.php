@extends('layouts.multiselect')
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
                        <li class="breadcrumb-item">Waste Management</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Transfer Waste Request</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="/user/wastemgmt">Back</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">


        <!-- Horizontal -->
        <div class="block block-rounded">

            <div class="block-content block-content-full">
                <div class="row">

                    <div class="col-lg-10">
                        <!-- Form Horizontal - Default Style -->
                        <form name="form" class="mb-5" action="{{url('/user/waste/insertwasterequest')}}" method="POST" onsubmit="return validation()">
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
                            @if(Session::has('qtymsg'))
                            <div class="alert alert-danger">
                                <a class="close" data-dismiss="alert">×</a>
                                {!!Session::get('qtymsg')!!}
                            </div>
                            @endif

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="date" style="font-size:13px;">Date Of Request</label>
                                <div class="col-sm-12">
                                    <input type="date" style="font-size:13px;width:350px;" class="js-datepicker form-control" id="date" name="date" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy" value="<?php echo date("Y-m-d");?>" readonly>
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="request number" style="font-size:13px;">Transfer Request Number</label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size:13px;width:350px;" class="form-control" id="request_num" name="request_num"  value="{{$reportnum}}" readonly>
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="requestedby" style="font-size:13px;">Requested by</label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size:13px;width:350px;" class="form-control" id="requestedby" name="requestedby"  value="{{$requestedby}}" readonly>
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="dept" style="font-size:13px;">Department</label>
                                <div class="col-sm-12">
                                    {{-- <input type="text" style="font-size:13px;width:350px;" class="form-control" id="dept" name="dept"  value="{{$departments}}"> --}}
                                    <select class="form-control" name="dept" id="dept" style="font-size:13px;width:350px;" required>
                                        {{-- @if(count($rev)>0)
                                        @foreach($rev as $r)
                                        <option value="{{$r->email}}">{{$r->name}}</option>
                                        @endforeach
                                        @endif --}}
                                        @foreach($departments as $dep)
                                           <option value="{{$dep->id}}" {{ $dep->id == $wdept ? 'selected' : ''}}>{{$dep->departmentname}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="resp" style="font-size:13px;">Responsibility</label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size:13px;width:350px;" class="form-control" id="resp" name="resp"  value="{{$waste->responsibility}}" readonly>
                                </div>
                            </div> --}}

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="itemcode" style="font-size:13px;">Item code</label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;width:350px;" class="form-control" id="itemcode" name="itemcode" value="{{$waste->waste_code}}" readonly>
                                </div>
                            </div>
                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="itemname" style="font-size:13px;">Item Name</label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;width:350px;" class="form-control" id="itemname" name="itemname" value="{{$waste->waste_name}}" readonly>
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="type" style="font-size:13px;">Type of Scrap</label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size:13px;width:350px;" class="form-control" id="type" name="type"  value="{{$waste->waste_type}}" readonly>
                                </div>
                            </div>

                           <!-- <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="desc" style="font-size:13px;">Waste Description</label>
                                <div class="col-sm-12">
                                    <textarea type="text" class="form-control" id="desc" name="desc" style="font-size:13px;" required placeholfer="Description"></textarea>
                                </div>
                            </div>-->

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="qty" style="font-size:13px;">Quantity</label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;width:350px;" class="form-control" id="qty" name="qty" value="{{ old('qty') }}" required>
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="unit" style="font-size:13px;">Unit</label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;width:350px;" class="form-control" id="unit" name="unit" value="{{$waste->unit}}" readonly>
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="use" style="font-size:13px;">Are there Useable parts in the euipment(if yes to be removed and reused)?</label>
                                <div class="col-sm-12">
                                    <textarea type="text" class="form-control" id="use" name="use" required style="font-size:13px;">{{ old('use') }}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-12 col-form-label" for="rev" style="font-size:13px;">Reviewer</label>
                                <div class="col-sm-12">
                                    <table style="border:none;" id="dynamicTable">
                                        <tr>
                                            <td>
                                                <select class="js-select2 form-control" name="rev[]" id="rev" style="font-size: 13px;width:350px;" multiple required>
                                                    @if(count($rev)>0)
                                                    @foreach($rev as $r)
                                                    <option value="{{$r->email}}">{{$r->name}}</option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </td>
                                          <!--  <td><button type="button" style="font-size:12px;" name="add" id="add" class="btn btn-sm btn-light" data-toggle="tooltip" title="Add">
                                                    <i class="fa fa-fw fa-plus"></i>
                                                </button>
                                            </td>-->

                                        </tr>

                                    </table>

                                </div>
                            </div>
                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="use" style="font-size:13px;">Approver
                                    <i class="fas fa-info-circle fa-1x text-danger" data-toggle="modal" data-target="#viewModal"></i>
                                    <div id="viewModal" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                @if(count($targets)>0)
                                                <table class="table">
                                                    <tr>
                                                        <th>itemname</th>
                                                        <th>qty</th>
                                                        <th>approver</th>
                                                    </tr>
                                                    @foreach($targets as $t)
                                                    <tr>
                                                    <td>{{ $t->items}}</td>
                                                    <td>{{ $t->qty }}</td>
                                                    <td>{{$t->approver ? $t->approver->name : '-'}}</td>
                                                    </tr>
                                                    @endforeach
                                                </table>
                                                {{-- @foreach($targets as $c)
                                                <div style="margin-left:50px;margin-top:10px;">
                                                    <h5>{{$c->approver ? $c->approver->name : ''}}</h5>
                                                </div>
                                                @endforeach --}}
                                                @else
                                                <h3 align="center"><span style="color:red;">No info</span></h3>
                                                @endif
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                            <div class="col-sm-12" style="display:inline-block;width:49%;">
                                    <select class="form-control" name="appr" id="appr" style="font-size: 13px; width:350px;" required placeholder="Choose Approver">
                                        <option value="" selected="" disabled>Choose Approver</option>
                                        @foreach($appr as $app)
                                        <option value="{{$app->email}}">{{$app->name}}</option>
                                        @endforeach
                                    </select>
                            </div>

                           <input type="hidden" name="wasteid" value="{{$waste->id}}">
                            <div class="form-group row">
                                <div class="col-sm-8 ml-auto">
                                    <button type="submit" class="btn btn-primary" style="font-size:13px;margin-left:700px;margin-top:10px;">Submit</button>
                                </div>
                            </div>
                        </form>
                        <!-- END Form Horizontal - Default Style -->

                    </div>
                </div>
            </div>
        </div>
        <!-- END Horizontal -->

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
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/waste/register">
                                    <div class="block-content text-center">
                                        <i class="far fa-list-alt fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Waste Management
                                        </p>
                                    </div>
                                </a>
                                <!-- END Products -->
                            </div>
                            <div class="col-6">
                                <!-- Products -->
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/waste/register">
                                    <div class="block-content text-center">
                                        <i class="far fa-list-alt fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Waste / Scrap List
                                        </p>
                                    </div>
                                </a>
                                <!-- END Products -->
                            </div>
                            <div class="col-6">

                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/waste/transferrequest">
                                    <div class="block-content text-center">
                                        <i class="fas fa-clone fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Transfer Request
                                        </p>
                                    </div>
                                </a>

                            </div>

                                    <div class="col-6">
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/waste/analysis">
                                            <div class="block-content text-center">
                                                <i class="si si-wallet fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Analysis
                                                </p>
                                            </div>
                                        </a>
                                    </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Apps Modal -->

</body>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<!--<script src="{{ asset('js/jquery.min.js')}}"></script>-->
<script type="text/javascript">
window.onload = function(){
  // Your code here
  $(document).ready(function() {
        $('#other').hide();
    });
     $("#unit").change(function() {
        if ($("#unit option:selected").val() == "Other") {

            $('#other').show();
        } else {

            $('#other').hide();
        }
    });
     var i = 0;
    $("#add").click(function() {
        ++i;
        $("#dynamicTable").append('<tr> <td><br><select class="form-control" name="rev[]" id="rev"  style="font-size: 13px;width:350px;" ><option value="" selected="" disabled>Select </option>   @foreach($rev as $r)<option value="{{$r->email}}">{{$r->name}}</option> @endforeach</select></td><td><br> <i class="fa fa-fw remove-tr fa-times"></i></button></td></tr>');
    });
    $(document).on('click', '.remove-tr', function() {
        $(this).parents('tr').remove();
    });
};



</script>
<!-- script for plus button of reviewer -->
<script>
    function validation() {
        if (document.getElementById('rev').value == 0) {
            alert('Please select Reviewer');
            document.getElementById('rev').focus();
            return false;
        }
    }

</script>

<script>
window.onload = display1();
function display1()
{
    document.getElementById('internaltype').style.display="none";
    document.getElementById('externaltype').style.display="none";

}
function display2()
{
        if(document.getElementById('internal_transfer').checked)
        {
          document.getElementById('internaltype').style.display="";
          document.getElementById('externaltype').style.display="none";
          document.getElementById('loc_dept').required= true;
          document.getElementById('ext_flow').required= false;

        }
        else if(document.getElementById('external_transfer').checked)
        {
            document.getElementById('externaltype').style.display="";
          document.getElementById('internaltype').style.display="none";
          document.getElementById('loc_dept').required= false;
          document.getElementById('ext_flow').required= true;
        }
 }

</script>

</html>
@endsection
