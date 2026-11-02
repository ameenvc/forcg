<div class="row p-0">
    <fieldset class="col-sm-12 row p-0 ml-4" style="border:2px solid #dfe4e8;max-width:95%;">
        <legend style="font-size:15px;width:90px;text-weight:bold;" class="bg-primary text-white p-1">Customer</legend>
        <div class="form-group col-sm-12 col-md-4 px-0" id="customerDiv">
            <label class="col-sm-12 col-form-label" for="Customer Name" style="font-size: 13px;">Customer Name <span class="text-danger">*</span></label>
            <div class="col-sm-12">
                <select class="form-control" name="customer" id="customer" style="font-size: 13px;width:100%;" required="true">
                    <option value="" selected disabled>Choose Customer</option>
                    @if(count($customer)>0)
                    @foreach($customer as $dep)
                    <option value="{{$dep->id}}" {{$data->customer==$dep->id ? 'selected' : ''}}>{{$dep->customer}}</option></br>
                    @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="form-group col-sm-12 col-md-4 px-0">
            <label class="col-sm-12 col-form-label" for="Customer Phone" style="font-size: 13px;">Customer Phone</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" style="font-size: 13px;" id="customerphone" name="customerphone" value="{{$data->customer_phone}}" placeholder="Enter Phone Number">
            </div>
        </div>
        <div class="form-group col-sm-12 col-md-4 px-0">
            <label class="col-sm-12 col-form-label" for="Customer Email" style="font-size: 13px;">Customer Email <span class="text-danger">*</span></label>
            <div class="col-sm-12">
                <input type="email" class="form-control" style="font-size: 13px;" id="customeremail" name="customeremail" value="{{$data->customer_email}}" placeholder="Enter Email" required>
            </div>
        </div>
        <div class="form-group col-sm-12 col-md-4 px-0">
            <label class="col-sm-12 col-form-label" for="Customer Address" style="font-size: 13px;">Customer Address</label>
            <div class="col-sm-12">
                <textarea class="form-control" style="font-size: 13px;" id="customeraddress" name="customeraddress" placeholder="Enter Address">{{$data->customer_address}}</textarea>
            </div>
        </div>
        <div class="form-group col-sm-12 col-md-4 px-0" id="productServiceDiv">
            <label class="col-sm-12 col-form-label" for="product" style="font-size: 13px;">Product/Service <span class="text-danger">*</span></label>
            <div class="col-sm-12">
                <select class="form-control js-select2" id="product" name="product[]" style="font-size: 13px; width: 100%;" required multiple>
                    @if(count($product) > 0)
                        @foreach($product as $value)
                            <option value="{{ $value->id }}" {{ in_array($value->id,explode(',',$data->product)) ? 'selected' : '' }}>
                                {{ $value->product }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
    </fieldset>
</div>
<div class="row">
    <div class="form-group col-sm-12 col-md-4">
        <label class="col-sm-12 col-form-label" for="Sale Order No." style="font-size: 13px;">Sale Order No. <span class="text-danger">*</span></label>
        <div class="col-sm-12">
            <input type="text" class="form-control" style="font-size: 13px;" name="saleorder" id="saleorder" placeholder="Enter Sale Order No." required="" value="{{ $data->sale_order_no }}">
        </div>
    </div>
    <div class="form-group col-sm-12 col-md-4">
        <label class="col-sm-12 col-form-label" for="title" style="font-size: 13px;">Delivery No. <span class="text-danger">*</span></label>
        <div class="col-sm-12">
            <input type="text" class="form-control" style="font-size: 13px;" id="deliveryno" name="deliveryno" placeholder="Enter Delivery No." value="{{$data->delivery_no}}" required="">
        </div>
    </div>
    <div class="form-group col-sm-12 col-md-4">
        <label class="col-sm-12 col-form-label" for="title" style="font-size: 13px;">Delivery Date <span class="text-danger">*</span></label>
        <div class="col-sm-12">
            <input type="date" class="form-control" style="font-size: 13px;" name="deliverydate" id="deliverydate" required="" value="{{ $data->delivery_date}}">
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-sm-12 col-md-4">
        <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="type">Type</label>
        <div class="col-sm-12">
            <select class="form-control js-select2" style="font-size: 13px;width:100%;" id="type" name="type" placeholder="Choose Type">
                <option value="" selected>Choose Type</option>
                <option value="High" <?= $data->type == "High" ? 'selected' : '' ?>>High</option>
                <option value="Moderate" <?= $data->type == "Moderate" ? 'selected' : '' ?>>Moderate</option>
                <option value="Low" <?= $data->type == "Low" ? 'selected' : '' ?>>Low</option>
            </select>
        </div>
    </div>
    <div class="form-group col-sm-12 col-md-4" id="plantDiv">
        <label class="col-sm-12 col-form-label" for="Plant" style="font-size: 13px;">Plant <span class="text-danger">*</span></label>
        <div class="col-sm-12" >
            <select class="form-control js-select2" name="plant" id="plant" style="font-size: 13px;width:100%;" required>
                <option value="" selected>Choose Plant</option>
                @if(count($plants)>0)
                @foreach($plants as $p)
                <option value="{{$p->id}}" <?= $data->plant == $p->id ? 'selected' : '' ?>> {{$p->plant_name}}</option></br>
                @endforeach
                @endif

            </select>
        </div>
    </div>
    <div class="form-group col-sm-12 col-md-4" id="vendorDiv">
        <label class="col-sm-12 col-form-label" for="vendor_id" style="font-size: 13px;">Vendor</label>
        <div class="col-sm-12" >
            <select class="form-control js-select2" name="meta[vendor_id]" id="vendor_id" style="font-size: 13px;width:100%;">
                <option value="" selected>Select Vendor</option>
                @foreach($vendors as $vendor)
                    <option value="{{ $vendor->id }}"
                        {{ isset($data->vendor_id) && $data->vendor_id == $vendor->id ? 'selected' : '' }}>
                        {{ $vendor->vendor_name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-sm-12 col-md-6">
        <label class="col-sm-12 col-form-label" for="title" style="font-size: 13px;">Complaint Title <span class="text-danger">*</span></label>
        <div class="col-sm-12">
            <input type="text" class="form-control" style="font-size: 13px;" id="title" name="title" placeholder="Enter Title" value="{{$data->title}}" required>
        </div>
    </div>
    <div class="form-group col-sm-12 col-md-12">
        <label class="col-sm-12 col-form-label" for="complaint" style="font-size: 13px;">Complaint Description <span class="text-danger">*</span></label>
        <div class="col-sm-12">
            <textarea type="text" class="form-control" style="font-size: 13px;" id="complaint" name="complaint" placeholder="Enter description">{{$data->complaint}}</textarea>
            <div id="complaint-error" class="text-danger" style="display:none;font-size:12px;">Complaint Description field is required.</div>
        </div>
    </div>
</div>
<div class="row">
    <?php $num = 0; ?>
    <div class="form-group col-sm-12" >
        <div class="table-responsive">
            <div  style="overflow-x:auto; width:100%;">
                <table class="table table-bordered table-striped table-vcenter" >
                    <thead class="bg-white">
                        <tr>
                            <th class="text-center" style="font-size:11px;width:1%;">S #</th>
                            <th class="text-center" style="font-size:11px;width:8%;">Item Code <span class="text-danger">*</span></th>
                            <th class="text-center" style="font-size:11px;width:15%;">Product Description <span class="text-danger">*</span></th>
                            <th class="text-center" style="font-size:11px;width:7%;">Size of the Item <span class="text-danger">*</span></th>
                            <th class="text-center" style="font-size:11px;width:7%;">ID No. <span class="text-danger">*</span></th>
                            <th class="text-center" style="font-size:11px;width:7%;">Total Delivery Quantity <span class="text-danger">*</span></th>
                            <th class="text-center" style="font-size:11px;width:7%;">Affected Quantity <span class="text-danger">*</span></th>
                            <th class="text-center" style="font-size:11px;width:6%;">Unit <span class="text-danger">*</span></th>
                            <th class="text-center" style="font-size:11px;width:9%;">Cost Impact <span class="text-danger">*</span></th>
                            <th class="text-center" style="font-size:11px;width:9%;">Complaint Category <span class="text-danger">*</span></th>
                            <th class="text-center" style="font-size:11px;width:9%;">Sale Return Request</th>
                            <th class="text-center" style="font-size:11px;width:9%;">Rework Request</th>
                            <th style="font-size:11px;width:1%;"></th>
                        </tr>
                    </thead>
                    <tbody id="dynamictable">
                        @foreach($product_details as $pd)
                        <tr>
                            <input type="hidden" name="product_desc_id[]" class="form-control" value="{{$pd->id}}">
                            <td><span style="font-size:11px;">{{++$num}}</span></td>
                            <td class="td-class"><input type="text" class="form-control" name="itemcode[]" id="itemcode{{$num}}" style="font-size:12px;" placeholder="Enter Item Code" value="{{$pd->item_code}}" required></td>

                            <td class="td-class"><textarea class="form-control" cols="25" name="productdesc[]" id="productdesc{{$num}}" rows="4" style="font-size:12px;" required placeholder="Enter Product Description">{{$pd->product_description}}</textarea></td>
                            <td class="td-class"><input type="text" class="form-control" name="sizeofitem[]" id="sizeofitem{{$num}}" style="font-size:12px;" placeholder="Enter Size of the Item" required value="{{$pd->size_of_item}}"></td>
                            <td class="td-class"><input type="text" class="form-control"  name="idno[]" id="idno{{$num}}" style="font-size:12px;" placeholder="Enter ID No." value="{{$pd->idno}}" required></td>

                            <td class="td-class">
                                <input type="text" id="totalquant{{$num}}" class="form-control" name="totalquant[]" style="font-size:12px;" placeholder="Enter Total Delivery Quantity" value="{{$pd->total_delivery_quantity}}" required>
                            </td>
                            <td class="td-class">
                                <input type="text" id="affectquant{{$num}}" class="form-control" name="affectquant[]" style="font-size:12px;" placeholder="Enter Affected Quantity" value="{{$pd->affected_quantity}}" required>
                            </td>
                            @php
                                $oldunitforoldrecords = App\ComplaintUnit::where('is_deleted',false)->where('id',$pd->unit)->first();
                            @endphp
                            <td class="td-class" id="unitDiv{{$num}}">
                                <select class="form-control js-select2 unitclass" name="unit[]" id="unit{{$num}}" style="font-size: 11px;width:100%;" required="true" data-placeholder="Select Unit">
                                    <option selected disabled>Choose Unit</option>
                                    @if(!$oldunitforoldrecords)
                                    <option value="{{$pd->unit}}" selected>{{$pd->unit}}</option>
                                    @endif
                                    @if(count($units)> 0 )
                                    @foreach($units as $unit)
                                    <option value="{{$unit->id}}" {{$unit->id == $pd->unit ? 'selected' : ''}}> {{ $unit->name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </td>
                            <td class="td-class">
                                <input type="text" id="costimpact{{$num}}" class="form-control" name="costimpact[]" style="font-size:12px;" placeholder="Enter Cost Impact" required value="{{$pd->cost_impact}}">
                            </td>
                            <td class="td-class" id="categoryDiv{{$num}}">

                                <select class="form-control js-select2 categoryclass" name="complaintcategory[{{$num}}][]" id="complaintcategory{{$num}}" style="font-size: 12px;width:100%;" required="true" multiple>
                                    @if(count($complaint_cat)> 0 )
                                        @foreach($complaint_cat as $cc)
                                            <option value="{{$cc->id}}" <?= in_array($cc->id,explode(',',$pd->complaint_category)) ? 'selected' : ''?>> {{ $cc->category_name }}</option>
                                        @endforeach
                                    @endif
                                    @php
                                        $complaintIds = $complaint_cat->pluck('id');
                                        $selectedComplaintIds = explode(',', $pd->complaint_category);
                                        $missingComplaintIds = collect($selectedComplaintIds)->diff($complaintIds);
                                    @endphp

                                    {{-- Missing categories only --}}
                                    @foreach($all_complaint_cat->whereIn('id', $missingComplaintIds) as $cc)
                                        <option value="{{$cc->id}}" selected>
                                            {{ $cc->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="td-class">
                                <input type="text" id="salereturnreq{{$num}}" value="{{$pd->sale_return_request}}" class="form-control" name="salereturnreq[]" style="font-size:12px;" placeholder="Enter Sale Return Request">
                            </td>
                            <td class="td-class">
                                <input type="text" id="reworkreq{{$num}}" class="form-control" name="reworkreq[]" value="{{$pd->rework_request}}" style="font-size:12px;" placeholder="Enter Rework Request">
                            </td>
                            @if($num==1)
                            <td class="td-class">
                                <button type="button" name="add" class="btn btn-sm btn-light" style="font-size:12px;" data-toggle="tooltip" title="Add" onclick="addRaw()">
                                    <i class="fa fa-fw fa-plus"></i>
                                </button>
                            </td>
                            @else
                            <td class="text-center td-class"><button type="button" name="remove" style="font-size:12px;" class="btn btn-sm btn-light remove-tr" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-fw fa-times text-danger"></i></button>
                            </td>
                            @endif
                        </tr>
                        <style>
                            #select2-unit{{$num}}-container{
                                background-color: #e6e6ff;
                            }
                        </style>
                        <script>
                            $("#itemcode{{$num}}").css("background-color","#e8f0fe");
                            $("#productdesc{{$num}}").css("background-color","#e8f0fe");
                            $("#idno{{$num}}").css("background-color","#e8f0fe");
                            $("#totalquant{{$num}}").css("background-color","#e8f0fe");
                            $("#affectquant{{$num}}").css("background-color","#e8f0fe");
                            $("#costimpact{{$num}}").css("background-color","#e8f0fe");
                            if("{{$pd->sale_return_request}}"){
                                $("#salereturnreq{{$num}}").css("background-color","#e8f0fe");
                            }
                            if("{{$pd->rework_request}}"){
                                $("#reworkreq{{$num}}").css("background-color","#e8f0fe");
                            }
                            if("{{$pd->size_of_item}}"){
                                $("#sizeofitem{{$num}}").css("background-color","#e8f0fe");
                            }
                            $("#itemcode{{$num}}").blur(function(){
                                $(this).css("background-color","#e8f0fe");
                            }).focus(function(){
                                $(this).css("background-color","white");
                            });
                            $("#productdesc{{$num}}").blur(function(){
                                $(this).css("background-color","#e8f0fe");
                            }).focus(function(){
                                $(this).css("background-color","white");
                            });
                            $("#idno{{$num}}").blur(function(){
                                $(this).css("background-color","#e8f0fe");
                            }).focus(function(){
                                $(this).css("background-color","white");
                            });
                            $("#totalquant{{$num}}").blur(function(){
                                $(this).css("background-color","#e8f0fe");
                            }).focus(function(){
                                $(this).css("background-color","white");
                            });
                            $("#affectquant{{$num}}").blur(function(){
                                $(this).css("background-color","#e8f0fe");
                            }).focus(function(){
                                $(this).css("background-color","white");
                            });

                            $("#costimpact{{$num}}").blur(function(){
                                $(this).css("background-color","#e8f0fe");
                            }).focus(function(){
                                $(this).css("background-color","white");
                            });
                            $("#salereturnreq{{$num}}").blur(function(){
                                $(this).css("background-color","#e8f0fe");
                            }).focus(function(){
                                $(this).css("background-color","white");
                            });
                            $("#reworkreq{{$num}}").blur(function(){
                                $(this).css("background-color","#e8f0fe");
                            }).focus(function(){
                                $(this).css("background-color","white");
                            });
                            $("#sizeofitem{{$num}}").blur(function(){
                                $(this).css("background-color","#e8f0fe");
                            }).focus(function(){
                                $(this).css("background-color","white");
                            });

                            $("#categoryDiv{{$num}}").click(function(){
                                removeCategoryRow1({{$num}});
                                addCategoryRow1({{$num}});
                            });
                            function addCategoryRow1(count){
                                var isAdmin = "{{$isAdminResult}}";
                                var addBtn = `<hr id="addCategoryHr1${count}" style="margin:auto 0;border:solid 1px;"><ul style="list-style-type:none;padding:0;margin:0;cursor:pointer;" id="addCategoryBtn1${count}"><li class="select2-results__option select2-results__option"><a  target="_blank" style="font-size:13px;" class="text-primary"><i class="fa fa-2x fa-plus" style="font-size:13px;">&nbsp;New Category</i></a></li></ul>`;
                                for(var x=1;x<=count;x++){
                                    $('#select2-complaintcategory'+x+'-results').after(addBtn);
                                    $('#addCategoryBtn1'+x).click(function(){
                                        if(isAdmin){
                                            window.open("{{ route('admin.complaint.category') }}", "_blank");
                                        }else{
                                            $('.addcategory').click();
                                        }
                                    });

                                }
                            }
                            function removeCategoryRow1(count){
                                for(var x=1;x<=count;x++){
                                    $('#addCategoryHr1'+x).remove();
                                    $('#addCategoryBtn1'+x).remove();
                                }
                            }
                            $("#unitDiv{{$num}}").click(function(){
                                removeUnitRow1({{$num}});
                                addUnitRow1({{$num}});
                            });
                            function addUnitRow1(count){
                                var isAdmin = "{{$isAdminResult}}";
                                var addBtn = `<hr id="addUnitHr1${count}" style="margin:auto 0;border:solid 1px;"><ul style="list-style-type:none;padding:0;margin:0;cursor:pointer;" id="addUnitBtn1${count}"><li class="select2-results__option select2-results__option"><a  target="_blank" style="font-size:9px;" class="text-primary"><i class="fa fa-2x fa-plus" style="font-size:13px;">&nbsp;New Unit</i></a></li></ul>`;
                                for(var x=1;x<=count;x++){
                                    $('#select2-unit'+x+'-results').after(addBtn);
                                    $('#addUnitBtn1'+x).click(function(){
                                        if(isAdmin){
                                            window.open("{{ route('admin.complaint.category') }}", "_blank");
                                        }else{
                                            $('.addunit').click();
                                        }
                                    });

                                }
                            }
                            function removeUnitRow1(count){
                                for(var x=1;x<=count;x++){
                                    $('#addUnitHr1'+x).remove();
                                    $('#addUnitBtn1'+x).remove();
                                }
                            }
                        </script>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
        <div class="form-group col-sm-12">
        <label class="col-sm-12 col-form-label" for="action" style="font-size: 13px;">Recommended Action <span class="text-danger">*</span></label>
        <div class="col-sm-12">
            <textarea type="text" class="form-control" id="action" style="font-size: 13px;" name="action" placeholder="Enter Here">{{$data->action}}</textarea>
            <div id="action-error" class="text-danger" style="display:none;font-size:12px;">Recommended Action field is required.</div>
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-sm-12 col-md-4">
        <label class="col-sm-12 col-form-label" for="department" style="font-size: 13px;">Responsible Department <span class="text-danger">*</span></label>
        <div class="col-sm-12">
            <select class="form-control js-select2" id="department" name="department" style="font-size: 13px;width:100%;" required="true" onchange="chooseDivision($(this).val())">
                <option selected disabled>Choose Department</option>
                @if(count($departments)>0)
                @foreach($departments as $dep)
                <option value="{{$dep->id}}" <?= $data->dept == $dep->id ? 'selected' : '' ?>> {{$dep->departmentname}}</option>
                @endforeach
                @endif
            </select>
        </div>
    </div>
    @php
        $divisions = App\Division::where('department_id',$data->dept)->get();
        $areas = App\Area::where('division_id',$data->division_id)->get();
    @endphp
    <div class="form-group col-sm-12 col-md-4 {{count($divisions)>0 ?'':'d-none'}}" id="division">
        <label class="col-sm-12 col-form-label" style="font-size:13px;" for="division">Division</label>
        <div class="col-sm-12">
            <select class="form-control js-select2" id="division_select" name="division" style="font-size: 13px;width:100%;" placeholder="Division" onchange="chooseArea($(this).val())">
                <option selected value=''>Choose Division</option>
                @foreach($divisions as $division)
                <option value="{{$division->id}}" {{$division->id==$data->division_id ?'selected':''}}>{{$division->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group col-sm-12 col-md-4 {{count($areas)>0 ?'':'d-none'}}" id="area">
        <label class="col-sm-12 col-form-label" style="font-size:13px;" for="area">Sub Division</label>
        <div class="col-sm-12" id="action_number" name="action">
            <select class="form-control js-select2" id="area_select" name="area" style="font-size: 13px;width:100%;" placeholder="Sub Division">
                <option selected value=''>Choose Sub Division</option>
                @foreach($areas as $area)
                <option value="{{$area->id}}" {{$area->id==$data->area_id ?'selected':''}}>{{$area->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group col-sm-12 col-md-4">
        <label class="col-sm-12 col-form-label" for="responsibility" style="font-size: 13px;">Responsibility <span class="text-danger">*</span></label>
        <div class="col-sm-12">
            <select class="js-select2 form-select w-100" id="responsibility" name="responsibility" style="font-size: 13px;width:100%;" required="">
                <option value="">Select Responsible Person</option>
                @if(count($user)> 0 )
                @foreach($user as $use)
                <option value="{{$use->email}}" <?= $data->responsibility == $use->email ? 'selected' : '' ?>> {{ $use->name }}</option><br>
                @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="form-group col-sm-12 col-md-4">
        <label class="col-sm-12 col-form-label" for="date" style="font-size: 13px;">Target date <span class="text-danger">{{in_array(Auth::user()->email, $issue_reviewers) ? "*" : ''}}<span></label>
        <div class="col-sm-12">
            <input type="Date" class="form-control" id="date" style="font-size: 13px;" name="date" {{in_array(Auth::user()->email, $issue_reviewers) ? "required" : ''}} value="{{ $data->tardate}}">
        </div>
    </div>
</div>

<script>
    var count = {{$num}};
    var divcounter = {{$num}}

    var itemCodeEnabled= {{$complaintTemplateSectionFields['itemcode']['enable_field'] ?? 0}}
    var itemCodeMandatory = {{ $complaintTemplateSectionFields['itemcode']['field_is_mandatory'] ?? 0 }}

    var productDescriptionEnabled= {{$complaintTemplateSectionFields['productdesc']['enable_field'] ?? 0}}
    var productDescriptionMandatory = {{ $complaintTemplateSectionFields['productdesc']['field_is_mandatory'] ?? 0 }}

    var sizeOfItemEnabled= {{$complaintTemplateSectionFields['sizeofitem']['enable_field'] ?? 0}}
    var sizeOfItemMandatory = {{ $complaintTemplateSectionFields['sizeofitem']['field_is_mandatory'] ?? 0 }}

    var idNoEnabled= {{$complaintTemplateSectionFields['idno']['enable_field'] ?? 0}}
    var idNoMandatory = {{ $complaintTemplateSectionFields['idno']['field_is_mandatory'] ?? 0 }}

    var totalDeliveryQuantityEnabled= {{$complaintTemplateSectionFields['totalquant']['enable_field'] ?? 0}}
    var totalDeliveryQuantityMandatory = {{ $complaintTemplateSectionFields['totalquant']['field_is_mandatory'] ?? 0 }}

    var affectedQuantityEnabled= {{$complaintTemplateSectionFields['affectquant']['enable_field'] ?? 0}}
    var affectedQuantityMandatory = {{ $complaintTemplateSectionFields['affectquant']['field_is_mandatory'] ?? 0 }}

    var unitEnabled= {{$complaintTemplateSectionFields['unit']['enable_field'] ?? 0}}
    var unitMandatory = {{ $complaintTemplateSectionFields['unit']['field_is_mandatory'] ?? 0 }}

    var costImpactEnabled= {{$complaintTemplateSectionFields['costimpact']['enable_field'] ?? 0}}
    var costImpactMandatory = {{ $complaintTemplateSectionFields['costimpact']['field_is_mandatory'] ?? 0 }}

    var complaintCategoryEnabled= {{$complaintTemplateSectionFields['complaintcategory']['enable_field'] ?? 0}}
    var complaintCategoryMandatory = {{ $complaintTemplateSectionFields['complaintcategory']['field_is_mandatory'] ?? 0 }}

    var saleReturnRequestEnabled= {{$complaintTemplateSectionFields['salereturnreq']['enable_field'] ?? 0}}
    var saleReturnRequestMandatory = {{ $complaintTemplateSectionFields['salereturnreq']['field_is_mandatory'] ?? 0 }}

    var reworkRequestEnabled= {{$complaintTemplateSectionFields['reworkreq']['enable_field'] ?? 0}}
    var reworkRequestMandatory = {{ $complaintTemplateSectionFields['reworkreq']['field_is_mandatory'] ?? 0 }}

    function addRaw() {
        count++;
        let newrow= `<tr>
            <td><span style="font-size:12px;">${count}</span></td>
            ${itemCodeEnabled ?`<td class="td-class"><input type="text" id='itemcodeid${count}' style="font-size:12px;" class="form-control" name="itemcode[]" placeholder="Enter Item Code" ${itemCodeMandatory ? 'required':''}></td>`: ''}
            ${productDescriptionEnabled ? `<td class="td-class"><textarea class="form-control" cols="25" id='productdescid${count}' name="productdesc[]" rows="4" style="font-size:12px;" placeholder="Enter Product Description" ${productDescriptionMandatory ? 'required' : ''}></textarea></td>` : ''}
            ${sizeOfItemEnabled ? `<td class="td-class"><input type="text" class="form-control" name="sizeofitem[]" id="sizeofitem${count}" style="font-size:12px;" placeholder="Enter Size of the Item" ${sizeOfItemMandatory ? 'required' : ''}></td>`: ''}
            ${idNoEnabled ? `<td class="td-class"><input type="text" class="form-control" style="font-size:12px;" id='idnoid${count}' name="idno[]" placeholder="Enter ID No." ${idNoMandatory ? 'required' : ''} ></td>`: ''}
            ${totalDeliveryQuantityEnabled ? `
            <td class="td-class">
                <input type="number" class="form-control" id='totalquantid${count}' name="totalquant[]" style="font-size:12px;" placeholder="Enter Total Delivery Quantity" ${totalDeliveryQuantityMandatory ? 'required' : ''}>
            </td>` : ''}
            ${affectedQuantityEnabled ? `
            <td class="td-class">
                <input type="number" class="form-control" id='affectquantid${count}' name="affectquant[]" style="font-size:12px;" placeholder="Enter Affected Quantity"  ${affectedQuantityMandatory ? 'required' : ''}>
            </td>`: '' }
            ${unitEnabled ? `
            <td class="td-class" id="unit1Div${count}">
                <select class='form-control unit_select unitclass unitclasscount' name='unit[]' id='unitid${count}' style="font-size: 11px;width:100%;" ${unitMandatory ? 'required' : ''} data-placeholder="Select Unit">
                    <option value="" selected disabled>Choose Unit</option>
                    @if(count($units)> 0 )
                    @foreach($units as $unit)
                    <option value='{{$unit->id}}'> {{ $unit->name }}</option>
                    @endforeach
                    @endif
                </select>
            </td>`: ''}
            ${costImpactEnabled ? `
            <td class="td-class">
                <input type="text" class="form-control" id='costimpactid${count}' name="costimpact[]" style="font-size:12px;" placeholder="Enter Cost Impact" ${costImpactMandatory ? 'required' : ''}>
            </td>`: ''}
            ${complaintCategoryEnabled ? `
            <td class="td-class" id="category1Div${count}" data-has-button="false">

                <select class="form-control category_select categoryclass" name="complaintcategory[${count}][]" id="complaintcategory${count}" style="font-size: 12px;width:100%;" ${complaintCategoryMandatory ? 'required': ''} multiple data-title="Choose Category">
                    ${generateCategoryOptions()}
                </select>
            </td>`: ''}
            ${saleReturnRequestEnabled ? `
            <td class="td-class">
                <input type="text" id="salereturnreq${count}" class="form-control" name="salereturnreq[]" style="font-size:12px;" placeholder="Enter Sale Return Request" ${saleReturnRequestMandatory ? 'required' : ''}>
            </td>`: ''}
            ${ reworkRequestEnabled ? `
                <td class="td-class">
                    <input type="text" id="reworkreq${count}" class="form-control" name="reworkreq[]" style="font-size:12px;" placeholder="Enter Rework Request" ${reworkRequestMandatory ? 'required' : ''}>
                </td>` : ''}
            <td class="text-center td-class"><button type="button" name="remove" style="font-size:11px;" class="btn btn-sm btn-light remove-tr" data-toggle="tooltip" title="Remove">
                <i class="fa fa-fw fa-times text-danger"></i></button>
            </td>
        </tr>`;
        $("#dynamictable").append(newrow);
        $('.category_select').select2();
        $('.unit_select').select2();
        setBlurFocusNewRow('#itemcodeid'+count);
        setBlurFocusNewRow('#productdescid'+count);
        setBlurFocusNewRow('#idnoid'+count);
        setBlurFocusNewRow('#totalquantid'+count);
        setBlurFocusNewRow('#affectquantid'+count);
        setBlurFocusNewRow('#costimpactid'+count);
        setBlurFocusNewRow('#salereturnreq'+count);
        setBlurFocusNewRow('#reworkreq'+count);
        setBlurFocusNewRow('#sizeofitem'+count);
        $("#category1Div"+count).click(function(){
            removeCategoryRow(count);
            addCategoryRow(count);
        });
        $("#unit1Div"+count).click(function(){
            removeUnitRow(count);
            addUnitRow(count);
        });
        updateCount();
        setBackgroundColorUnit();
    }
    $('#dynamictable').on('click', '.remove-tr', function () {
        $(this).closest('tr').remove();
        updateCount();
        setBackgroundColorUnit();
    });
    function updateCount() {
        $('#dynamictable tr').each(function(index) {
            $(this).find('td:first').text(index + 1);
        });
    }
    function setBlurFocusNewRow(selector) {
        $(selector).blur(function(){
            $(this).css("background-color","#e8f0fe");
        }).focus(function(){
            $(this).css("background-color","white");
        });
    }
    function generateCategoryOptions() {
        let options = '';
        globalCategories.forEach(category => {
            options += `<option value="${category.id}">${category.category_name}</option>`;
        });
        return options;
    }
    function addCategoryRow(count){
        var addBtn = `<hr id="addCategoryHr${count}" style="margin:auto 0;border:solid 1px;"><ul style="list-style-type:none;padding:0;margin:0;cursor:pointer;" id="addCategoryBtn${count}"><li class="select2-results__option select2-results__option"><a  target="_blank" style="font-size:13px;" class="text-primary"><i class="fa fa-2x fa-plus" style="font-size:13px;">&nbsp;New Category</i></a></li></ul>`;
        for(var x={{$num}};x<=count;x++){
            $('#select2-complaintcategory'+x+'-results').after(addBtn);

            $('#addCategoryBtn'+x).click(function(){
                if(isAdmin){
                    window.open("{{ route('admin.complaint.category') }}", "_blank");
                }else{
                    $('.addcategory').click();
                }
            });
        }
    }
    function removeCategoryRow(count){

        for(var x={{$num}};x<=count;x++){
            $('#addCategoryHr'+x).remove();
            $('#addCategoryBtn'+x).remove();
        }
    }
    function addUnitRow(count){
        var addBtn = `<hr id="addUnitHr${count}" style="margin:auto 0;border:solid 1px;"><ul style="list-style-type:none;padding:0;margin:0;cursor:pointer;" id="addUnitBtn${count}"><li class="select2-results__option select2-results__option"><a  target="_blank" style="font-size:13px;" class="text-primary"><i class="fa fa-2x fa-plus" style="font-size:13px;">&nbsp;New Unit</i></a></li></ul>`;
        for(var x={{$num}};x<=count;x++){
            $('#select2-unitid'+x+'-results').after(addBtn);

            $('#addUnitBtn'+x).click(function(){
                if(isAdmin){
                    window.open("{{ route('admin.complaint.category') }}", "_blank");
                }else{
                    $('.addunit').click();
                }
            });
        }
    }
    function removeUnitRow(count){

        for(var x={{$num}};x<=count;x++){
            $('#addUnitHr'+x).remove();
            $('#addUnitBtn'+x).remove();
        }
    }
    function setBackgroundColorUnit(){
        var unitDivs = document.getElementsByClassName('unitclasscount');
        for(var u=0;u<unitDivs.length;u++){
            var unitId = unitDivs[u].getAttribute('id');
            if (unitDivs[u].value) {
                $(`#select2-${unitId}-container`).css("background-color", "#e6e6ff");
            }
            $(`#${unitId}`).change(function() {
                var ele = document.getElementById(unitId);
                if (ele.value) {
                    $(`#select2-${unitId}-container`).css("background-color", "#e6e6ff");
                }
            });
        }
    }
</script>
