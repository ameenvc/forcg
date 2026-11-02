@extends('layouts.multiselect')
@section('content')
<style>
    .font-15{
        font-size:12px !important;
    }
    .breadcrumb>li+li:before {
        display: none !important;
    }
    .select2-results__options{
        font-size:12px !important;
    }
    .breadcrumb {
        font-size: 15px;
        padding: 0.75rem 1.25rem;
        background: #F6F7F9;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }

</style>
<!-- Page Content -->
<div class="content" style="width:100%;">
    @php
        $isAdmin = DB::table('admins')->where('email',Auth::user()->email)->first();
        $isAdminResult = $isAdmin ? true : false;
    @endphp
    <!-- Horizontal -->
    <div class="block block-rounded" style="box-shadow: 0px 0px 15px;background-color: #ffffff;">
        <nav aria-label="breadcrumb" style="display: flex; justify-content: space-between; background: #f6f7f9;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item font-w700">
                    <a href="/user/complaintmgmt" class="text-black">
                        Customer Complaint
                    </a>
                </li>
                <li class="breadcrumb-item" aria-current="page">
                    Add Customer
                </li>
            </ol>
        </nav>
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-12">
                    <form class="mb-5" action="{{url('/user/complaint/insertcustomer')}}" method="post" enctype="multipart/form-data">
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
                        @if(Session::has('err'))
                        <div class="alert alert-warning">
                            <a class="close" data-dismiss="alert">×</a>
                            {!!Session::get('err')!!}
                        </div>
                        @endif
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label class="col-sm-12 col-form-label" for="customer" style="font-size: 13px;">Customer name <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" class="form-control" id="customer" name="customer" placeholder="Enter Name" required value="{{ old('customer') }}">
                                </div>
                            </div>
                            <div class="form-group col-sm-4" id="productServiceDiv">
                                <label class="col-sm-12 col-form-label" for="product" style="font-size: 13px;">Product/Service <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select class="form-control js-select2" id="product" name="product[]" style="font-size: 13px; width: 100%;" required multiple>
                                        @if(count($product) > 0)
                                            @foreach($product as $value)
                                                <option value="{{ $value->id }}" {{ in_array($value->id, old('product', [])) ? 'selected' : '' }}>
                                                    {{ $value->product }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-12 col-form-label" for="country" style="font-size: 13px;">Country <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select class="js-select2 form-control" style="font-size: 13px;width: 100%;" id="country" name="country" data-placeholder="Choose a country" multiselect onchange="showStates()" required>
                                        <option></option>
                                        @if(count($countries)> 0 )
                                        @foreach($countries as $value)
                                        <option value="{{$value->id}}">{{ $value->country_name }}</option></br>
                                        @endforeach
                                        @endif
                                        <option value="0">Other</option>
                                    </select>
                                </div>
                                <div class="col-sm-12 mt-1 d-none" id="otherCountry">
                                    <input type="text" style="font-size: 13px;" placeholder="Enter Country Name" class="form-control" id="other_country" name="other_country" value="{{ old('other_country') }}"/>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-12 col-form-label" for="state" style="font-size: 13px;">State</label>
                                <div class="col-sm-12">
                                    <select class="js-select2 form-control" style="font-size: 13px;width: 100%;" id="state" name="state" data-placeholder="Choose a state" onchange="showOther()">
                                    </select>
                                </div>
                                <div class="col-sm-12 mt-1 d-none" id="otherState">
                                    <input type="text" style="font-size: 13px;" placeholder="Enter State Name" class="form-control" id="other_state" name="other_state" value="{{ old('other_state') }}"/>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-12 col-form-label" for="location" style="font-size: 13px;">Location</label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" placeholder="Enter Location"class="form-control" id="location" name="location" value="{{ old('location') }}" />
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-12 col-form-label" for="pin" style="font-size: 13px;">Customer Code <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" placeholder="Enter Code" class="form-control" id="pin" name="pin" value="{{ old('pin') }}" required/>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-12 col-form-label" for="email" style="font-size: 13px;">Email</label>
                                <div class="col-sm-12">
                                    <input type="email" style="font-size: 13px;" class="form-control" id="email" name="email" placeholder="Enter email" value="{{ old('email') }}"/>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-12 col-form-label" for="phone" style="font-size: 13px;">Phone</label>
                                <div class="col-sm-12">
                                    <input type="number" min="0" style="font-size: 13px;" class="form-control" id="phone" name="phone" placeholder="Enter phone" value="{{ old('phone') }}" />
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-12 col-form-label" for="customer_survey" style="font-size: 13px;">Customer email addresses to contact for survey</label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" class="form-control" id="customer_survey" name="customer_survey" value="{{ old('customer_survey') }}" />
                                </div>
                            </div>
                            <div class="col-sm-12 text-right pr-5">
                                <button type="submit" id="submit_btn" class="btn btn-primary" onclick="return confirm('Are you sure to submit the request?')" style="font-size:13px;">Add</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
    <button type="button" class="js-swal-warning d-none addservice"></button>
</div>
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
@php
    $isAdmin = DB::table('admins')->where('email',Auth::user()->email)->first();
    $isAdminResult = $isAdmin ? true : false;
@endphp
<script src="{{asset('js/sweetalert/sweetalert2.all.min.js')}}"></script>
<script>
    !function(e) {
        var n = {};

        function t(o) {
            if (n[o]) return n[o].exports;
            var r = n[o] = {
                i: o,
                l: !1,
                exports: {}
            };
            return e[o].call(r.exports, r, r.exports, t), r.l = !0, r.exports
        }
        t.m = e, t.c = n, t.d = function(e, n, o) {
            t.o(e, n) || Object.defineProperty(e, n, {
                enumerable: !0,
                get: o
            })
        }, t.r = function(e) {
            "undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(e, Symbol.toStringTag, {
                value: "Module"
            }), Object.defineProperty(e, "__esModule", {
                value: !0
            })
        }, t.t = function(e, n) {
            if (1 & n && (e = t(e)), 8 & n) return e;
            if (4 & n && "object" == typeof e && e && e.__esModule) return e;
            var o = Object.create(null);
            if (t.r(o), Object.defineProperty(o, "default", {
                    enumerable: !0,
                    value: e
                }), 2 & n && "string" != typeof e)
                for (var r in e) t.d(o, r, function(n) {
                    return e[n]
                }.bind(null, r));
            return o
        }, t.n = function(e) {
            var n = e && e.__esModule ? function() {
                return e.default
            } : function() {
                return e
            };
            return t.d(n, "a", n), n
        }, t.o = function(e, n) {
            return Object.prototype.hasOwnProperty.call(e, n)
        }, t.p = "", t(t.s = 4)
    }({
        4: function(e, n, t) {
            e.exports = t(5)
        },
        5: function(e, n) {
            function t(e, n) {
                for (var t = 0; t < n.length; t++) {
                    var o = n[t];
                    o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(e, o.key, o)
                }
            }
            var o = function() {
                function e() {
                    ! function(e, n) {
                        if (!(e instanceof n)) throw new TypeError("Cannot call a class as a function")
                    }(this, e)
                }
                var n, o, r;
                return n = e, r = [{
                    key: "sweetAlert2",
                    value: function() {
                        var e = Swal.mixin({
                            buttonsStyling: !1,
                            customClass: {
                                confirmButton: "btn btn-success m-1",
                                cancelButton: "btn btn-danger m-1",
                                input: "form-control"
                            }
                        });
                        jQuery(".addservice").on("click", (function(n) {
                            e.fire("Warning", "Only admins can add new product/service, so please contact them.", "warning")
                        }))
                    }
                }, {
                    key: "init",
                    value: function() {
                        this.sweetAlert2()
                    }
                }], (o = null) && t(n.prototype, o), r && t(n, r), e
            }();
            jQuery((function() {
                o.init()
            }))
        }
    });
</script>
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var isAdmin = "{{$isAdminResult}}";
    var clickCount2 = 0;
    function showStates() {
        let states = [];
        let country_id = $('#country').val();
        let states_text = `<option value="" selected>Choose a State</option>`;
        let states_url = '{{ route("country.states", ":id") }}';
        states_url = states_url.replace(':id', country_id);
        if(country_id!='0'){
            $('#otherCountry').addClass('d-none');
            $('#other_country').removeAttr('required');
            $('#otherState').addClass('d-none');
            $.ajax({
                url: states_url
                , type: "GET"
                , success: function(result) {
                    states = result.data;
                    for (var i = 0; i < states.length; i++) {
                        states_text += `<option value="${states[i].id}">${states[i].state_name}</option>`
                    }
                    states_text+=`<option value="0">Other</option>`;
                    $('#state').empty().append(states_text);
                }
                , error: function(result) {
                    console.log('error', result);
                }
            });
        }else{
            states_text+=`<option value="0">Other</option>`;
            $('#state').empty().append(states_text);
            $('#otherCountry').removeClass('d-none');
            $('#other_country').attr('required', 'required');
        }
    }
    function showOther(){
        let statevalue = $('#state').val();
        if(statevalue=='0'){
            $('#otherState').removeClass('d-none');
        }else{
            $('#otherState').addClass('d-none');
        }
    }
    $("#productServiceDiv").click(function(){
            var addBtn = `<hr style="margin:auto 0;border:solid 1px;"><ul style="list-style-type:none;padding:0;margin:0;cursor:pointer;"><li class="select2-results__option select2-results__option"><a id="addProductBtn" target="_blank" style="font-size:13px;" class="text-primary"><i class="fa fa-2x fa-plus" style="font-size:13px;">&nbsp;New Product/Service</i></a></li></ul>`;
            if(clickCount2==0){
                $('#select2-product-results').after(addBtn);
                clickCount2++;
            }
            $('#addProductBtn').click(function(){
                if(isAdmin){
                    window.open("/admin/viewproduct", "_blank");
                }else{
                    $('.addservice').click();
                }
            });

        });
</script>
@endsection
