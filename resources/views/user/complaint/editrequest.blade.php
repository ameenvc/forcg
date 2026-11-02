@extends('pages.datatables')
@section('content')
<style>
    .border-3 {
        border-width: 3px !important;
    }

    .font-15{
        font-size:12px !important;
    }
    .breadcrumb>li+li:before {
        display: none !important;
    }
    .select2-results__options{
        font-size:11px !important;
    }
    .select2-container{
        font-size:11px !important;
    }
    .select2-selection__choice{
        font-size:11px !important;
    }
    .breadcrumb {
        font-size: 15px;
        padding: 0.75rem 1.25rem;
        background: #F6F7F9;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }
    .scrollable {
        /* height: 40%; */
        /* overflow-x: scroll; */
        /* overflow-y: scroll; */
    }
    .titleheading {
        font-size: 10px !important;
        text-transform: capitalize !important;
        position: sticky !important;
        position: relative !important;
        font-weight:bold !important;
        background-color:#fff;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice{
        background-color: #5179d6 !important;
        border: none !important;
        border-radius: .25rem !important;
        margin: .25rem .25rem .25rem 0 !important;
        height: 1.375rem !important;
        line-height: 1.375rem !important;
        color: #fff !important;
        font-weight: 600 !important;
    }
</style>
<link rel="stylesheet" id="css-main" href="{{asset('css/oneui.min.css')}}">
<link rel="stylesheet" href="{{asset('js/plugins/select2/css/select2.min.css')}}">
    <!-- Page Content -->
<div class="content" style="width:100%;padding-left:10px;padding-right:10px;">
        <!-- Horizontal -->
    <div class="block block-rounded" style="box-shadow: 0px 0px 15px;background-color: #ffffff;">
        <nav aria-label="breadcrumb" style="display: flex; justify-content: space-between; background: #f6f7f9;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item font-w700" style="margin-left:10px;">
                    <a href="/user/complaintmgmt" class="text-black">
                        Customer Complaint
                    </a>
                </li>
                <li class="breadcrumb-item" aria-current="page">
                    Edit Customer
                </li>
            </ol>
        </nav>
        <div class="block-content block-content-full" style="width:100%;">
            <form class="mb-5" action="{{url('/user/complaint/editrequest')}}" method="post">
                <div class="row">
                    <div class="col-lg-12 px-1">
                        {{ csrf_field() }}
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
                        @if(count($data)>0)
                        <div class="table-responsive" style="max-height: 500px;">
                            <table class="table table-bordered table-striped table-vcenter" style="width:120%;">

                                <thead style="position: sticky; top: -5px;z-index:5;">
                                    <tr>
                                        <th class="text-center titleheading" style="width:11%;">Customer Name <span class="text-danger">*</span></th>
                                        <th class="text-center titleheading" style="width:10%;">Customer Code <span class="text-danger">*</span></th>
                                        <th class="text-center titleheading" style="width:12%;">Product/Service <span class="text-danger">*</span></th>
                                        <th class="text-center titleheading" style="width:16%;">Country <span class="text-danger">*</span></th>
                                        <th class="text-center titleheading" style="width:14%;">State</th>
                                        <th class="text-center titleheading" style="width:9%;">Location</th>
                                        <th class="text-center titleheading" style="width:9%;">Email</th>
                                        <th class="text-center titleheading" style="width:9%;">Phone</th>
                                        <th class="text-center titleheading" style="width:10%;">Contact Details for Survey</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 0; @endphp
                                    @foreach($data as $dat1)
                                    <tr>


                                        <td class="text-center font-size-sm px-1">
                                            <input  type="text" style="font-size:11px;"  class="form-control" name="customer[]"  value="{{$dat1->customer}}">
                                        </td>
                                        <td class="text-center font-size-sm px-1">
                                            <input  type="text" style="font-size:11px;"  class="form-control" name="pin[]"  value="{{$dat1->pin}}">
                                        </td>
                                        <td class="text-center px-1"style="font-size:11px;">
                                            <select class="form-control js-select2 product2"  name="product[{{$no}}][]" style="font-size: 11px;width:100%;" multiple>
                                                @php
                                                    $products = $dat1->product ? explode(',',$dat1->product) : [];
                                                @endphp
                                                @if(count($p)> 0 )
                                                    @foreach($p as $value)
                                                        <option value="{{$value->id}}" {{in_array($value->id,$products) ? 'selected' : ''}}>{{ $value->product }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </td>
                                        <td class="text-center px-1"style="font-size:11px;">
                                            <select class="form-control js-select2"  name="country[]" id="country_id{{$no}}" style="font-size: 11px;width:100%;" aria-placeholder="Choose Country" onchange="showStates('{{$no}}')">

                                                @if(count($countries)> 0)
                                                    @foreach($countries as $country)
                                                        <option value="{{$country->id}}" {{$country->id == $dat1->country_id ? 'selected' : ''}}>{{ $country->country_name }}</option>
                                                    @endforeach
                                                @endif
                                                <option value="0" {{$dat1->country_id=="0" ? 'selected' : ''}}>Other</option>
                                            </select>
                                            <div class="mt-1 {{$dat1->country_id=="0" ?'':'d-none'}}" id="otherCountry{{$no}}">
                                                <input type="text" style="font-size: 11px;" placeholder="Enter Country Name" class="form-control" id="other_country{{$no}}" name="other_country[]" value="{{$dat1->other_country}}"/>
                                            </div>
                                        </td>
                                        <td class="text-center px-1" style="font-size:11px;">
                                            @php
                                                $states = App\State::where('country_id',$dat1->country_id)->get();
                                            @endphp
                                            <select class="form-control js-select2"  name="state[]" style="font-size: 11px;width:100%;" aria-placeholder="Choose State" id="state_id{{$no}}" onchange="showOther('{{$no}}')">
                                                <option value="">Choose State</option>
                                                @if(count($states)>0)
                                                    @foreach($states as $state)
                                                    <option value="{{$state->id}}" {{$state->id==$dat1->state_id ? 'selected' : ''}}>{{$state->state_name}}</option>
                                                    @endforeach
                                                @endif
                                                <option value="0" {{$dat1->country_id=="0" || $dat1->other_state ?'selected' : ''}}>Other</option>
                                            </select>
                                            <div class="mt-1 {{$dat1->other_state ? '' : 'd-none'}}" id="otherState{{$no}}">
                                                <input type="text" style="font-size: 11px;" placeholder="Enter State Name" class="form-control" id="other_state{{$no}}" name="other_state[]" value="{{ $dat1->other_state }}"/>
                                            </div>
                                        </td>
                                        <td class="text-center px-1 font-size-sm">
                                            <input type="text" style="font-size:11px;"  class="form-control" name="location[]" value="{{$dat1->location}}">
                                        </td>

                                        <td class="text-center px-1">
                                            <input type="email" style="font-size:11px;"  class="form-control" name="email[]" value="{{$dat1->email}}">
                                        </td>
                                        <td class="text-center px-1">
                                            <input type="text" style="font-size:11px;"  class="form-control" name="phone[]" value="{{$dat1->phone}}">
                                        </td>
                                        <td class="text-center px-1">
                                            <textarea style="font-size:11px;"  class="form-control" name="survey_emails[]">{{$dat1->customer_survey_emails}}</textarea>
                                        </td>
                                        <input type="hidden" name="sno[]" value="{{ $dat1->id}}">
                                    </tr>
                                    @php $no = $no+1; @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                        <br>
                        <div class="text-right">
                            <button for="submittl" type="submit" onclick=" return confirm('Do you want to proceed?')" class="btn btn-primary"style="font-size:13px">Submit</button>
                        </div>
                    </div>
                </div>
            </form>

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
                        {{-- <p>

                            <div class="col-6">
                                <!-- CRM -->
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/complaint/add">
                                    <div class="block-content text-center">
                                        <i class="si si-speedometer fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Add Complaint
                                        </p>
                                    </div>
                                </a>
                                <!-- END CRM -->
                            </div> --}}

                        <div class="col-6">
                            <!-- Products -->

                            <a class="block block-rounded block-link-shadow bg-body" href="/user/complaint/addcustomer">
                                <div class="block-content text-center">
                                    <i class="fas fa-plus-square fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Add Customers
                                    </p>
                                </div>
                            </a>
                            <!-- END Products -->
                        </div>
                        <div class="col-6">
                            <!-- Sales -->

                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/complaintmgmt">
                                <div class="block-content text-center">
                                    <i class="far fa-list-alt fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        <br>Register
                                    </p>
                                </div>
                            </a>
                            <!-- END Sales -->
                        </div>
                        <div class="col-6">

                            <!-- Payments -->
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/complaint/list">
                                <div class="block-content text-center">
                                    <i class="fas fa-address-card fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Customer List
                                    </p>
                                </div>
                            </a>
                            <!-- END Payments -->
                        </div>
                        <div class="col-6">

                            <!-- Payments -->
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/complaint/my">
                                <div class="block-content text-center">
                                    <i class="fas fa-clone fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        My Complaints
                                    </p>
                                </div>
                            </a>
                            <!-- END Payments -->
                        </div>
                        <div class="col-6">
                            <br>
                            <!-- Payments -->
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/complaint/closedc" style="margin-top:-16px">
                                <div class="block-content text-center" style="height:130px">
                                    <i class="fas fa-check-circle fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        <br>Closed
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
<script src="{{asset('js/oneui.core.min.js')}}"></script>
<script src="{{asset('js/oneui.app.min.js')}}"></script>
<script src="{{asset('js/plugins/select2/js/select2.full.min.js')}}"></script>

<script>
    jQuery(function() {
        One.helpers(['select2']);
    });

</script>
<script>
    function showStates(num) {
        let states = [];
        let country_id = $("#country_id"+num).val();
        let states_text = `<option value="" selected>Choose a State</option>`;
        let states_url = '{{ route("country.states", ":id") }}';
        states_url = states_url.replace(':id', country_id);
        if(country_id!='0'){
            $('#otherCountry'+num).addClass('d-none');
            $('#other_country'+num).removeAttr('required');
            $('#otherState'+num).addClass('d-none');
            $.ajax({
                url: states_url
                , type: "GET"
                , success: function(result) {
                    states = result.data;
                    for (var i = 0; i < states.length; i++) {
                        states_text += `<option value="${states[i].id}">${states[i].state_name}</option>`
                    }
                    states_text+=`<option value="0">Other</option>`;
                    $('#state_id'+num).empty().append(states_text);
                }
                , error: function(result) {
                    console.log('error', result);
                }
            });
        }else{
            states_text+=`<option value="0">Other</option>`;
            $('#state_id'+num).empty().append(states_text);
            $('#otherCountry'+num).removeClass('d-none');
            $('#other_country'+num).attr('required', 'required');
        }
    }
    function showOther(num){
        let statevalue = $('#state_id'+num).val();
        if(statevalue=='0'){
            $('#otherState'+num).removeClass('d-none');
        }else{
            $('#otherState'+num).addClass('d-none');
        }
    }
    $(document).ready(function() {
        $('table').DataTable({
            "pageLength": -1,
            "paging": false,
            "lengthChange": false,
            "info": false
        });
        $('#DataTables_Table_0_filter').hide();
    });
</script>
@endsection
