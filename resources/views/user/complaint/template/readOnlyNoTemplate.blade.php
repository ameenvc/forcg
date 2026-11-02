<div class="row p-0">
    <fieldset class="col-sm-12 row p-0 ml-4" style="border:2px solid #dfe4e8;max-width:95%;">
        <legend style="font-size:15px;width:90px;text-weight:bold;" class="bg-primary text-white p-1">Customer</legend>
        <div class="form-group col-sm-12 col-md-4 px-0" id="customerDiv">
            <label class="col-sm-12 col-form-label" for="Customer Name" style="font-size: 13px;">Customer Name <span class="text-danger">*</span></label>
            <div class="col-sm-12">
                <input type="text" class="form-control" style="font-size: 13px;" value="{{ $customer}}" readonly>

            </div>
        </div>
        <div class="form-group col-sm-12 col-md-4 px-0">
            <label class="col-sm-12 col-form-label" for="Customer Phone" style="font-size: 13px;">Customer Phone</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" style="font-size: 13px;" id="customerphone" name="customerphone" value="{{$data->customer_phone}}" placeholder="Enter Phone Number" <?= $data->customer_phone ? 'readonly' : ''?>>
            </div>
        </div>
        <div class="form-group col-sm-12 col-md-4 px-0">
            <label class="col-sm-12 col-form-label" for="Customer Email" style="font-size: 13px;">Customer Email <span class="text-danger">*</span></label>
            <div class="col-sm-12">
                <input type="email" class="form-control" style="font-size: 13px;" id="customeremail" value="{{$data->customer_email}}" readonly>
            </div>
        </div>
        <div class="form-group col-sm-12 col-md-4 px-0">
            <label class="col-sm-12 col-form-label" for="Customer Address" style="font-size: 13px;">Customer Address</label>
            <div class="col-sm-12">
                <textarea class="form-control" style="font-size: 13px;" id="customeraddress" name="customeraddress" placeholder="Enter Address" <?= $data->customer_address ? 'readonly' : ''?>>{{$data->customer_address}}</textarea>
            </div>
        </div>
        <div class="form-group col-sm-12 col-md-4 px-0">
            <label class="col-sm-12 col-form-label" for="product" style="font-size: 13px;">Product/Service <span class="text-danger">*</span></label>
            <div class="col-sm-12">
                <textarea type="text" class="form-control" style="font-size: 13px;" readonly>{{implode(',',$product)}}</textarea>
            </div>
        </div>
    </fieldset>
</div>
<div class="row">
    <div class="form-group col-sm-12 col-md-4">
        <label class="col-sm-12 col-form-label" for="Sale Order No." style="font-size: 13px;">Sale Order No. <span class="text-danger">*</span></label>
        <div class="col-sm-12">
            <input type="text" class="form-control" style="font-size: 13px;" placeholder="Enter Sale Order No." required="" value="{{ $data->sale_order_no }}" readonly>
        </div>
    </div>
    <div class="form-group col-sm-12 col-md-4">
        <label class="col-sm-12 col-form-label" for="title" style="font-size: 13px;">Delivery No. <span class="text-danger">*</span></label>
        <div class="col-sm-12">
            <input type="text" class="form-control" style="font-size: 13px;" placeholder="Enter Delivery No." value="{{$data->delivery_no}}" required="" readonly>
        </div>
    </div>
    <div class="form-group col-sm-12 col-md-4">
        <label class="col-sm-12 col-form-label" for="title" style="font-size: 13px;">Delivery Date <span class="text-danger">*</span></label>
        <div class="col-sm-12">
            <input type="date" class="form-control" style="font-size: 13px;" required="" value="{{ $data->delivery_date}}" readonly>
        </div>
    </div>
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
        <div class="form-group col-sm-12 col-md-4" id="vendorDiv">
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
            <textarea type="text" class="form-control" style="font-size: 13px;" placeholder="Enter description" required="" readonly>{{$data->complaint}}</textarea>
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
                <input type="readonly" disabled class="form-control" style="font-size: 13px;" id="no_of_occurrence" name="meta[no_of_occurrence]" placeholder="Enter Occurence" value="{{ $data->previous_complaint_ref ? $data->previous_complaint_ref['trackno'].' - '.$data->previous_complaint_ref['title'] : 'other'}}">
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
                            <th class="text-center" style="font-size:11px;width:8%;">Item Code <span class="text-danger">*</span></th>
                            <th class="text-center" style="font-size:11px;width:15%;">Product Description <span class="text-danger">*</span></th>
                            <th class="text-center" style="font-size:11px;width:7%;">Size of the Item <span class="text-danger">*</span></th>
                            <th class="text-center" style="font-size:11px;width:7%;">ID No. <span class="text-danger">*</span></th>
                            <th class="text-center" style="font-size:11px;width:7%;">Total Delivery Quantity <span class="text-danger">*</span></th>
                            <th class="text-center" style="font-size:11px;width:7%;">Affected Quantity <span class="text-danger">*</span></th>
                            <th class="text-center" style="font-size:11px;width:6%;">Unit <span class="text-danger">*</span></th>
                            <th class="text-center" style="font-size:11px;width:9%;">Cost Impact <span class="text-danger">*</span></th>
                            <th class="text-center" style="font-size:11px;width:13%;">Complaint Category <span class="text-danger">*</span></th>
                            <th class="text-center" style="font-size:11px;width:8%;">Sale Return Request</th>
                            <th class="text-center" style="font-size:11px;width:8%;">Rework Request</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $num = 0; ?>
                        @foreach($product_details as $pd)
                        <tr>
                            <input type="hidden" name="product_desc_id[]" class="form-control" value="{{$pd->id}}">
                            <td><span style="font-size:11px;">{{++$num}}</span></td>
                            <td class="td-class"><input type="text" class="form-control" style="font-size:12px;" placeholder="Enter Item Code" value="{{$pd->item_code}}" required readonly></td>

                            <td class="td-class"><textarea class="form-control" cols="25" rows="4" style="font-size:12px;" required placeholder="Enter Product Description" readonly>{{$pd->product_description}}</textarea></td>
                            <td class="td-class"><input type="text" class="form-control" name="sizeofitem[]" id="sizeofitem{{$num}}" style="font-size:12px;" placeholder="Enter Size of the Item" required value="{{$pd->size_of_item}}" <?= $pd->size_of_item ? 'readonly' : ''?>></td>
                            <td class="td-class"><input type="text" class="form-control" style="font-size:12px;" value="{{$pd->idno}}" readonly></td>

                            <td class="td-class">
                                <input type="text" class="form-control" style="font-size:12px;" value="{{$pd->total_delivery_quantity}}" readonly>
                            </td>
                            <td class="td-class">
                                <input type="text" class="form-control" style="font-size:12px;" value="{{$pd->affected_quantity}}" readonly>
                            </td>
                            @php
                                $oldunitforoldrecords = App\ComplaintUnit::where('is_deleted',false)->where('id',$pd->unit)->first();
                            @endphp
                            <td class="td-class">
                                <input type="text" class="form-control" style="font-size:13px;" value="{{$oldunitforoldrecords ? $oldunitforoldrecords->name : $pd->unit}}" readonly>
                            </td>
                            <td class="td-class">
                                <input type="text" class="form-control" style="font-size:12px;" value="{{$pd->cost_impact}}" readonly>
                            </td>
                            <td class="td-class" id="categoryDiv{{$num}}">
                                @if($pd->complaint_category && $data->plant)
                                    @php $cat_list = DB::table('complaint_categories')->whereIn('id',explode(',',$pd->complaint_category))->pluck('category_name')->toArray(); @endphp
                                    <textarea class="form-control" cols="25" rows="4" style="font-size:12px;" name="complaintcategory" readonly>{{implode(',',$cat_list)}}</textarea>
                                @else
                                <select class="form-control js-select2 categoryclass" name="complaintcategory[{{$num}}][]" id="complaintcategory{{$num}}" style="font-size: 12px;width:100%;" required="true" multiple>
                                    @if(count($complaint_cat)> 0 )
                                    @foreach($complaint_cat as $cc)
                                    <option value="{{$cc->id}}" <?= in_array($cc->id,explode(',',$pd->complaint_category)) ? 'selected' : ''?>> {{ $cc->category_name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                @endif

                            </td>
                            <td class="td-class">
                                <input type="text" id="salereturnreq{{$num}}" value="{{$pd->sale_return_request}}" class="form-control" name="salereturnreq[]" style="font-size:12px;" placeholder="Enter Sale Return Request" <?= $pd->sale_return_request ? 'readonly' : ''?>>
                            </td>
                            <td class="td-class">
                                <input type="text" id="reworkreq{{$num}}" class="form-control" name="reworkreq[]" value="{{$pd->rework_request}}" style="font-size:12px;" placeholder="Enter Rework Request" <?= $pd->rework_request ? 'readonly' : ''?>>
                            </td>

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
            <textarea type="text" class="form-control" style="font-size: 13px;" placeholder="Enter Here" required readonly>{{$data->action}}</textarea>
        </div>
    </div>
</div>
