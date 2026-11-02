@if ($complaintTemplateSectionFields['customer']['enable_field'] || $complaintTemplateSectionFields['customerphone']['enable_field'] || $complaintTemplateSectionFields['customeremail']['enable_field'] || $complaintTemplateSectionFields['customeraddress']['enable_field'] || $complaintTemplateSectionFields['product']['enable_field'] )
    <div class="row p-0">
        <fieldset class="col-sm-12 row p-0 ml-4" style="border:2px solid #dfe4e8;max-width:95%;">
            <legend style="font-size:15px;width:90px;text-weight:bold;" class="bg-primary text-white p-1">Customer</legend>
            @if ($complaintTemplateSectionFields['customer']['enable_field'])
                <div class="form-group col-sm-12 col-md-4 px-0" id="customerDiv">
                    <label class="col-sm-12 col-form-label" for="Customer Name" style="font-size: 13px;">{{$complaintTemplateSectionFields['customer']['field_display_label']}} @if($complaintTemplateSectionFields['customer']['field_is_mandatory'])<span class="text-danger">*</span> @endif</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" style="font-size: 13px;" value="{{ $customer}}" readonly>
                    </div>
                </div>
            @endif

            @if ($complaintTemplateSectionFields['customerphone']['enable_field'])
                <div class="form-group col-sm-12 col-md-4 px-0">
                    <label class="col-sm-12 col-form-label" for="Customer Phone" style="font-size: 13px;">{{$complaintTemplateSectionFields['customerphone']['field_display_label']}} @if($complaintTemplateSectionFields['customerphone']['field_is_mandatory'])<span class="text-danger">*</span> @endif</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" style="font-size: 13px;" id="customerphone" name="customerphone" value="{{$data->customer_phone}}" placeholder="Enter Phone Number" <?= $data->customer_phone ? 'readonly' : ''?>>
                    </div>
                </div>
            @endif
            @if ($complaintTemplateSectionFields['customeremail']['enable_field'])
                <div class="form-group col-sm-12 col-md-4 px-0">
                    <label class="col-sm-12 col-form-label" for="Customer Email" style="font-size: 13px;">{{$complaintTemplateSectionFields['customeremail']['field_display_label']}}@if($complaintTemplateSectionFields['customeremail']['field_is_mandatory'])<span class="text-danger">*</span> @endif</label>
                    <div class="col-sm-12">
                        <input type="email" class="form-control" style="font-size: 13px;" id="customeremail" value="{{$data->customer_email}}" readonly>
                    </div>
                </div>
            @endif
            @if ($complaintTemplateSectionFields['customeraddress']['enable_field'])
                <div class="form-group col-sm-12 col-md-4 px-0">
                    <label class="col-sm-12 col-form-label" for="Customer Address" style="font-size: 13px;">{{$complaintTemplateSectionFields['customeraddress']['field_display_label']}}@if($complaintTemplateSectionFields['customeraddress']['field_is_mandatory'])<span class="text-danger">*</span> @endif</label>
                    <div class="col-sm-12">
                        <textarea class="form-control" style="font-size: 13px;" id="customeraddress" name="customeraddress" placeholder="Enter Address" <?= $data->customer_address ? 'readonly' : ''?>>{{$data->customer_address}}</textarea>
                    </div>
                </div>
            @endif
            @if ($complaintTemplateSectionFields['product']['enable_field'])
                <div class="form-group col-sm-12 col-md-4 px-0" id="productServiceDiv">
                    <label class="col-sm-12 col-form-label" for="product" style="font-size: 13px;">{{$complaintTemplateSectionFields['product']['field_display_label']}} @if($complaintTemplateSectionFields['product']['field_is_mandatory'])<span class="text-danger">*</span> @endif</label>
                    <div class="col-sm-12">
                        <textarea type="text" class="form-control" style="font-size: 13px;" readonly>{{implode(',',$product)}}</textarea>
                    </div>
                </div>
            @endif
        </fieldset>
    </div>
@endif
<div class="row">
    @if ($complaintTemplateSectionFields['saleorder']['enable_field'])
        <div class="form-group col-sm-12 col-md-4">
            <label class="col-sm-12 col-form-label" for="Sale Order No." style="font-size: 13px;">{{$complaintTemplateSectionFields['saleorder']['field_display_label']}} @if($complaintTemplateSectionFields['saleorder']['field_is_mandatory'])<span class="text-danger">*</span> @endif</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" style="font-size: 13px;" placeholder="Enter Sale Order No." required="" value="{{ $data->sale_order_no }}" readonly>
            </div>
        </div>
    @endif
    @if ($complaintTemplateSectionFields['deliveryno']['enable_field'])
        <div class="form-group col-sm-12 col-md-4">
            <label class="col-sm-12 col-form-label" for="title" style="font-size: 13px;">{{$complaintTemplateSectionFields['deliveryno']['field_display_label']}} @if($complaintTemplateSectionFields['deliveryno']['field_is_mandatory'])<span class="text-danger">*</span> @endif</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" style="font-size: 13px;" placeholder="Enter Delivery No." value="{{$data->delivery_no}}" required="" readonly>
            </div>
        </div>
    @endif
    @if ($complaintTemplateSectionFields['deliverydate']['enable_field'])
        <div class="form-group col-sm-12 col-md-4">
            <label class="col-sm-12 col-form-label" for="title" style="font-size: 13px;">{{$complaintTemplateSectionFields['deliverydate']['field_display_label']}} @if($complaintTemplateSectionFields['deliverydate']['field_is_mandatory'])<span class="text-danger">*</span> @endif</label>
            <div class="col-sm-12">
                <input type="date" class="form-control" style="font-size: 13px;" required="" value="{{ $data->delivery_date}}" readonly>
            </div>
        </div>
    @endif
</div>
<div class="row">
    <div class="form-group col-sm-12 col-md-4">
        <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="type">Type</label>

        <div class="col-sm-12">
            @if($data->type)
                <input type="text" class="form-control" name="type" style="font-size: 13px;" value="{{$data->type}}" readonly>
            @else
            <select class="form-control js-select2" style="font-size: 13px;width:100%;" id="type" name="type" placeholder="Choose Type">
                <option value="" selected>Choose Type</option>
                <option value="High" <?= $data->type == "High" ? 'selected' : '' ?>>High</option>
                <option value="Moderate" <?= $data->type == "Moderate" ? 'selected' : '' ?>>Moderate</option>
                <option value="Low" <?= $data->type == "Low" ? 'selected' : '' ?>>Low</option>
            </select>
            @endif
        </div>
    </div>
    @if ($data->plant)
        <div class="form-group col-sm-12 col-md-4" id="plantDiv">
            <label class="col-sm-12 col-form-label" for="Plant" style="font-size: 13px;">Plant <span class="text-danger">*</span></label>
            <div class="col-sm-12" >
                @if($data->plant)
                    <input type="text" class="form-control" name="oldplant" style="font-size: 13px;" value="{{DB::table('complaint_plants')->where('id',$data->plant)->value('plant_name')}}" readonly>
                @else
                <select class="form-control js-select2" name="plant" id="plant" style="font-size: 13px;width:100%;" required>
                    <option value="" selected disabled>Choose Plant</option>
                    @if(count($plants)>0)
                    @foreach($plants as $p)
                    <option value="{{$p->id}}" <?= $data->plant == $p->id ? 'selected' : '' ?>> {{$p->plant_name}}</option></br>
                    @endforeach
                    @endif

                </select>
                @endif
            </div>
        </div>
    @endif
    @if (isset($data->vendor_id))
        <div class="form-group col-sm-4" id="vendorDiv">
            <label class="col-sm-12 col-form-label" for="vendor" style="font-size: 13px;">Vendor</label>
            <div class="col-sm-12" >
                <input type="text" class="form-control" name="oldplant" style="font-size: 13px;" value="{{DB::table('vendor')->where('id',$data->vendor_id)->value('vendor_name')}}" readonly>
            </div>
        </div>
    @endif
</div>
<div class="row">
    <div class="form-group col-sm-12 col-md-6">
        <label class="col-sm-12 col-form-label" for="title" style="font-size: 13px;">Complaint Title <span class="text-danger">*</span></label>
        <div class="col-sm-12">
            <input type="text" class="form-control" style="font-size: 13px;" placeholder="Enter Title" value="{{$data->title}}" required readonly>
        </div>
    </div>
    <div class="form-group col-sm-12 col-md-12">
        <label class="col-sm-12 col-form-label" for="complaint" style="font-size: 13px;">Complaint Description <span class="text-danger">*</span></label>
        <div class="col-sm-12">
            <textarea type="text" id=complaint class="form-control" style="font-size: 13px;" placeholder="Enter description" required="" readonly>{{$data->complaint}}</textarea>
        </div>
    </div>

</div>
@if (isset($data->previously_occured_complaint))
    <div class="row">
        <div class="form-group col-sm-12">
            <label class="col-sm-12 col-form-label" for="name" style="font-size:13px;">Is this a previously occurred Compliant?</label>
            <div class="col-sm-12">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" disabled type="radio" id="previously_occured_complaint_yes" name="meta[previously_occured_complaint]" value="yes" {{ isset($data->previously_occured_complaint) && $data->previously_occured_complaint == 'yes' ? 'checked' : '' }}>
                    <label class="form-check-label" for="previously_occured_complaint_yes" style="font-size:13px;">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" disabled type="radio" id="previously_occured_complaint_no" name="meta[previously_occured_complaint]" value="no" {{ isset($data->previously_occured_complaint) && $data->previously_occured_complaint == 'no' ? 'checked' : ''}}>
                    <label class="form-check-label" for="previously_occured_complaint_no" style="font-size:13px;">No</label>
                </div>
            </div>
        </div>
        <div class="form-group {{ isset($data->previously_occured_complaint) && $data->previously_occured_complaint == 'no' ? 'd-none' : '' }} col-sm-4" id="no_of_occurrence_div">
            <label class="col-sm-12 col-form-label" for="no_of_occurrence" style="font-size:13px;">No of Occurrence</label>
            <div class="col-sm-12">
                <input type="readonly" disabled class="form-control" style="font-size: 13px;" id="no_of_occurrence" name="meta[no_of_occurrence]" placeholder="Enter Occurence" value="{{ $data->no_of_occurrence ?? ''}}">
            </div>
        </div>
        <div class="form-group {{ isset($data->previously_occured_complaint) && $data->previously_occured_complaint == 'no' ? 'd-none' : '' }} col-sm-4" id="previous_complaint_reference_div">
            <label class="col-sm-12 col-form-label" for="previous_complaint_reference" style="font-size:13px;">Previous Complaint Reference</label>
            <div class="col-sm-12">
                <input type="readonly" disabled class="form-control" style="font-size: 13px;" id="previous_complaint_reference" name="meta[previous_complaint_reference]" placeholder="Enter Occurence"
                    value="{{ isset($data->previous_complaint_reference) ?
                        $data->previous_complaint_reference != '0' ?
                            ($data->previous_complaint_ref ? $data->previous_complaint_ref['trackno'].' - '.$data->previous_complaint_ref['title'] : 'This complaint was deleted' )
                            : 'other'
                        : ''}}">
            </div>
        </div>
        <div class="form-group {{ isset($data->previous_complaint_reference) && $data->previous_complaint_reference == '0' ? '' : 'd-none' }} col-sm-4" id="external_reference_div">
            <label class="col-sm-12 col-form-label" for="external_reference" style="font-size:13px;">External Complaint Reference</label>
            <div class="col-sm-12">
                <input type="readonly" disabled class="form-control" style="font-size: 13px;" id="external_reference" name="meta[external_reference]" value="{{ $data->external_reference ?? ''}}">
            </div>
        </div>
    </div>
@endif

<div class="row">
    <div class="form-group col-sm-12" >
        <div class="table-responsive">
            <div  style="overflow-x:auto; width:100%;">
                <table class="table table-bordered table-striped table-vcenter" >
                    <thead class="bg-white">
                        <tr>
                            <th class="text-center" style="font-size:11px;width:1%;">S #</th>
                            @if ($complaintTemplateSectionFields['itemcode']['enable_field'])
                            <th class="text-center" style="font-size:11px;width:8%;">{{$complaintTemplateSectionFields['itemcode']['field_display_label']}} @if($complaintTemplateSectionFields['itemcode']['field_is_mandatory'])<span class="text-danger">*</span>@endif </th>
                            @endif
                            @if ($complaintTemplateSectionFields['productdesc']['enable_field'])
                                <th class="text-center" style="font-size:11px;width:15%;">{{$complaintTemplateSectionFields['productdesc']['field_display_label']}} @if($complaintTemplateSectionFields['productdesc']['field_is_mandatory'])<span class="text-danger">*</span>@endif</th>
                            @endif
                            @if ($complaintTemplateSectionFields['sizeofitem']['enable_field'])
                                <th class="text-center" style="font-size:11px;width:7%;">{{$complaintTemplateSectionFields['sizeofitem']['field_display_label']}} @if($complaintTemplateSectionFields['sizeofitem']['field_is_mandatory'])<span class="text-danger">*</span>@endif</th>
                            @endif
                            @if ($complaintTemplateSectionFields['idno']['enable_field'])
                                <th class="text-center" style="font-size:11px;width:7%;">{{$complaintTemplateSectionFields['idno']['field_display_label']}} @if($complaintTemplateSectionFields['idno']['field_is_mandatory'])<span class="text-danger">*</span>@endif</th>
                            @endif
                            @if ($complaintTemplateSectionFields['totalquant']['enable_field'])
                                <th class="text-center" style="font-size:11px;width:7%;">{{$complaintTemplateSectionFields['totalquant']['field_display_label']}} @if($complaintTemplateSectionFields['totalquant']['field_is_mandatory'])<span class="text-danger">*</span>@endif</th>
                            @endif
                            @if ($complaintTemplateSectionFields['affectquant']['enable_field'])
                                <th class="text-center" style="font-size:11px;width:7%;">{{$complaintTemplateSectionFields['affectquant']['field_display_label']}} @if($complaintTemplateSectionFields['affectquant']['field_is_mandatory'])<span class="text-danger">*</span>@endif</th>
                            @endif
                            @if ($complaintTemplateSectionFields['unit']['enable_field'])
                                <th class="text-center" style="font-size:11px;width:6%;">{{$complaintTemplateSectionFields['unit']['field_display_label']}} @if($complaintTemplateSectionFields['unit']['field_is_mandatory'])<span class="text-danger">*</span>@endif</th>
                            @endif
                            @if ($complaintTemplateSectionFields['costimpact']['enable_field'])
                                <th class="text-center" style="font-size:11px;width:9%;">{{$complaintTemplateSectionFields['costimpact']['field_display_label']}} @if($complaintTemplateSectionFields['costimpact']['field_is_mandatory'])<span class="text-danger">*</span>@endif</th>
                            @endif
                            @if ($complaintTemplateSectionFields['complaintcategory']['enable_field'])
                                <th class="text-center" style="font-size:11px;width:9%;">{{$complaintTemplateSectionFields['complaintcategory']['field_display_label']}} @if($complaintTemplateSectionFields['complaintcategory']['field_is_mandatory'])<span class="text-danger">*</span>@endif</th>
                            @endif
                            @if ($complaintTemplateSectionFields['salereturnreq']['enable_field'])
                                <th class="text-center" style="font-size:11px;width:9%;">{{$complaintTemplateSectionFields['salereturnreq']['field_display_label']}} @if($complaintTemplateSectionFields['salereturnreq']['field_is_mandatory'])<span class="text-danger">*</span>@endif</th>
                            @endif
                            @if ($complaintTemplateSectionFields['reworkreq']['enable_field'])
                            <th class="text-center" style="font-size:11px;width:9%;">{{$complaintTemplateSectionFields['reworkreq']['field_display_label']}} @if($complaintTemplateSectionFields['reworkreq']['field_is_mandatory'])<span class="text-danger">*</span>@endif</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        <?php $num = 0; ?>
                        @foreach($product_details as $pd)
                        <tr>
                            <input type="hidden" name="product_desc_id[]" class="form-control" value="{{$pd->id}}">
                            <td><span style="font-size:11px;">{{++$num}}</span></td>
                            @if ($complaintTemplateSectionFields['itemcode']['enable_field'])
                                <td class="td-class"><input type="text" class="form-control" name="itemcode[]" id="itemcode" style="font-size:12px;" placeholder="Enter Item Code" {{ $complaintTemplateSectionFields['itemcode']['field_is_mandatory'] ? 'required' : '' }} value="{{$pd->item_code}}" readonly></td>
                            @endif
                            @if ($complaintTemplateSectionFields['productdesc']['enable_field'])
                                <td class="td-class"><textarea class="form-control" cols="25" name="productdesc[]" id="productdesc" rows="4" style="font-size:12px;" {{ $complaintTemplateSectionFields['productdesc']['field_is_mandatory'] ? 'required' : '' }} placeholder="Enter Product Description" readonly>{{$pd->product_description}}</textarea></td>
                            @endif
                            @if ($complaintTemplateSectionFields['sizeofitem']['enable_field'])
                                <td class="td-class"><input type="text" class="form-control" name="sizeofitem[]" id="sizeofitem" style="font-size:12px;" placeholder="Enter Size of the Item" {{ $complaintTemplateSectionFields['sizeofitem']['field_is_mandatory'] ? 'required' : '' }} value="{{$pd->size_of_item}}" readonly></td>
                            @endif
                            @if ($complaintTemplateSectionFields['idno']['enable_field'])
                                <td class="td-class"><input type="text" class="form-control" name="idno[]" id="idno" style="font-size:12px;" placeholder="Enter ID No." {{ $complaintTemplateSectionFields['idno']['field_is_mandatory'] ? 'required' : '' }} value="{{$pd->idno}}" readonly></td>
                            @endif
                            @if ($complaintTemplateSectionFields['totalquant']['enable_field'])
                                <td class="td-class">
                                    <input type="text" id="totalquant" class="form-control" name="totalquant[]" style="font-size:12px;" placeholder="Enter Total Delivery Quantity" {{ $complaintTemplateSectionFields['totalquant']['field_is_mandatory'] ? 'required' : '' }} value="{{$pd->total_delivery_quantity}}" readonly>
                                </td>
                            @endif
                            @if ($complaintTemplateSectionFields['affectquant']['enable_field'])
                                <td class="td-class">
                                    <input type="text" id="affectquant" class="form-control" name="affectquant[]" style="font-size:12px;" placeholder="Enter Affected Quantity" {{ $complaintTemplateSectionFields['affectquant']['field_is_mandatory'] ? 'required' : '' }} value="{{$pd->total_affected_quantity}}" readonly>
                                </td>
                            @endif
                            @if ($complaintTemplateSectionFields['unit']['enable_field'])
                            @php
                                $oldunitforoldrecords = App\ComplaintUnit::where('is_deleted',false)->where('id',$pd->unit)->first();
                            @endphp
                            <td class="td-class">
                                <input type="text" class="form-control" style="font-size:13px;" value="{{$oldunitforoldrecords ? $oldunitforoldrecords->name : $pd->unit}}" readonly>
                            </td>
                            @endif
                            @if ($complaintTemplateSectionFields['costimpact']['enable_field'])
                                <td class="td-class">
                                    <input type="text" class="form-control" style="font-size:12px;" value="{{$pd->cost_impact}}" readonly>
                                </td>
                            @endif
                            @if ($complaintTemplateSectionFields['complaintcategory']['enable_field'])
                                <td class="td-class" id="categoryDiv{{$num}}">
                                    @if($pd->complaint_category && $data->plant)
                                        @php $cat_list = DB::table('complaint_categories')->whereIn('id',explode(',',$pd->complaint_category))->pluck('category_name')->toArray(); @endphp
                                        <textarea class="form-control" cols="25" rows="4" style="font-size:12px;" name="complaintcategory" readonly>{{implode(',',$cat_list)}}</textarea>
                                    @else
                                    <select class="form-control js-select2 categoryclass" name="complaintcategory[{{$num}}][]" id="complaintcategory{{$num}}" style="font-size: 12px;width:100%;"  multiple>
                                        @if(count($complaint_cat)> 0 )
                                        @foreach($complaint_cat as $cc)
                                        <option value="{{$cc->id}}" <?= in_array($cc->id,explode(',',$pd->complaint_category)) ? 'selected' : ''?>> {{ $cc->category_name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    @endif

                                </td>
                            @endif
                            @if ($complaintTemplateSectionFields['salereturnreq']['enable_field'])
                                <td class="td-class">
                                    <input type="text" id="salereturnreq{{$num}}" value="{{$pd->sale_return_request}}" class="form-control" name="salereturnreq[]" style="font-size:12px;" placeholder="Enter Sale Return Request" <?= $pd->sale_return_request ? 'readonly' : ''?>>
                                </td>
                            @endif
                            @if ($complaintTemplateSectionFields['reworkreq']['enable_field'])
                                <td class="td-class">
                                    <input type="text" id="reworkreq{{$num}}" class="form-control" name="reworkreq[]" value="{{$pd->rework_request}}" style="font-size:12px;" placeholder="Enter Rework Request" <?= $pd->rework_request ? 'readonly' : ''?>>
                                </td>
                            @endif
                        </tr>

                        <script>
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
            <textarea type="text" id="action" class="form-control" style="font-size: 13px;" placeholder="Enter Here" required readonly>{{$data->action}}</textarea>
        </div>
    </div>
</div>
