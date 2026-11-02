<div class="row">
    @if ($complaintTemplateSectionFields['saleorder']['enable_field'])
        <div class="form-group col-sm-12 col-md-4">
            <label class="col-sm-12 col-form-label" for="Sale Order No." style="font-size: 13px;">{{$complaintTemplateSectionFields['saleorder']['field_display_label']}} @if($complaintTemplateSectionFields['saleorder']['field_is_mandatory'])<span class="text-danger">*</span> @endif</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" style="font-size: 13px;" name="saleorder" id="saleorder" placeholder="Enter {{$complaintTemplateSectionFields['saleorder']['field_display_label']}}" {{$complaintTemplateSectionFields['saleorder']['field_is_mandatory'] ? 'required' : ''}} value="{{ old('saleorder')}}">
            </div>
        </div>
    @endif
    @if ($complaintTemplateSectionFields['deliveryno']['enable_field'])
        <div class="form-group col-sm-12 col-md-4">
            <label class="col-sm-12 col-form-label" for="title" style="font-size: 13px;">{{$complaintTemplateSectionFields['deliveryno']['field_display_label']}} @if($complaintTemplateSectionFields['deliveryno']['field_is_mandatory'])<span class="text-danger">*</span> @endif</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" style="font-size: 13px;" id="deliveryno" name="deliveryno" placeholder="Enter {{$complaintTemplateSectionFields['deliveryno']['field_display_label']}}" value="{{ old('deliveryno')}}" {{$complaintTemplateSectionFields['deliveryno']['field_is_mandatory'] ? 'required' : ''}}>
            </div>
        </div>
    @endif
    @if ($complaintTemplateSectionFields['deliverydate']['enable_field'])
        <div class="form-group col-sm-12 col-md-4">
            <label class="col-sm-12 col-form-label" for="title" style="font-size: 13px;">{{$complaintTemplateSectionFields['deliverydate']['field_display_label']}} @if($complaintTemplateSectionFields['deliverydate']['field_is_mandatory'])<span class="text-danger">*</span> @endif</label>
            <div class="col-sm-12">
                <input type="date" class="form-control" style="font-size: 13px;" name="deliverydate" id="deliverydate" value="{{ old('deliverydate')}}" {{$complaintTemplateSectionFields['deliverydate']['field_is_mandatory'] ? 'required' : ''}}>
            </div>
        </div>
    @endif
</div>
<div class="row">
    <div class="form-group col-sm-12 col-md-4">
        <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="type">Type</label>
        <div class="col-sm-12">
            <select class="form-control js-select2" style="font-size: 13px;width:100%;" id="type" name="type" placeholder="Choose Type">
                <option value="" selected>Choose Type</option>
                <option value="High" <?= old('type') == "High" ? 'selected' : '' ?>>High</option>
                <option value="Moderate" <?= old('type') == "Moderate" ? 'selected' : '' ?>>Moderate</option>
                <option value="Low" <?= old('type') == "Low" ? 'selected' : '' ?>>Low</option>
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
                <option value=" {{$p->id}}" <?= old('plant') == $p->id ? 'selected' : '' ?>> {{$p->plant_name}}</option></br>
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
                    <option value=" {{$vendor->id}}" <?= old('meta[vendor_id]') == $vendor->id ? 'selected' : '' ?>> {{$vendor->vendor_name}}</option></br>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-sm-12 col-md-6">
        <label class="col-sm-12 col-form-label" for="title" style="font-size: 13px;">Complaint Title <span class="text-danger">*</span></label>
        <div class="col-sm-12">
            <input type="text" class="form-control" style="font-size: 13px;" id="title" name="title" placeholder="Enter Title" value="{{old('title')}}" required>
        </div>
    </div>
    <div class="form-group col-sm-12 col-md-12">
        <label class="col-sm-12 col-form-label" for="complaint" style="font-size: 13px;">Complaint Description <span class="text-danger">*</span></label>
        <div class="col-sm-12">
            <textarea type="text" class="form-control" style="font-size: 13px;" id="complaint" name="complaint" placeholder="Enter description">{{old('complaint')}}</textarea>
            <div id="complaint-error" class="text-danger" style="display:none;font-size:12px;">Complaint Description field is required.</div>
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-sm-12">
        <label class="col-sm-12 col-form-label" for="name" style="font-size:13px;">Is this a previously occurred Compliant?</label>
        <div class="col-sm-12">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio"
                    id="previously_occured_complaint_yes"
                    name="meta[previously_occured_complaint]"
                    value="yes"
                    {{ old('meta.previously_occured_complaint') == 'yes' ? 'checked' : '' }}>
                <label class="form-check-label" for="previously_occured_complaint_yes" style="font-size:13px;">Yes</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio"
                id="previously_occured_complaint_no"
                name="meta[previously_occured_complaint]"
                value="no"
                {{ old('meta.previously_occured_complaint', 'no') == 'no' ? 'checked' : '' }}>
                <label class="form-check-label" for="previously_occured_complaint_no" style="font-size:13px;">No</label>
            </div>
        </div>
    </div>
    <div class="form-group d-none col-sm-4" id="no_of_occurrence_div">
        <label class="col-sm-12 col-form-label" for="no_of_occurrence" style="font-size:13px;">No of Occurrence</label>
        <div class="col-sm-12">
            <input type="number" class="form-control" style="font-size: 13px;" id="no_of_occurrence" name="meta[no_of_occurrence]" placeholder="Enter Occurence" value="{{old('meta.no_of_occurrence')}}" min="1">
        </div>
    </div>
    <div class="form-group d-none col-sm-4" id="previous_complaint_reference_div">
        <label class="col-sm-12 col-form-label" for="previous_complaint_reference" style="font-size:13px;">Previous Complaint Reference</label>
        <div class="col-sm-12">
            <select class="form-control" name="meta[previous_complaint_reference]" id="previous_complaint_reference" style="font-size:13px;width:100%;">
                <option></option>
            </select>
        </div>
    </div>
    <div class="form-group d-none col-sm-4" id="external_reference_div">
        <label class="col-sm-12 col-form-label" for="external_reference" style="font-size:13px;">External Complaint Reference</label>
        <div class="col-sm-12">
            <input type="text" class="form-control" style="font-size: 13px;" id="external_reference" name="meta[external_reference]" value="{{old('meta.external_reference')}}">
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-sm-12" style="padding-left:2px;padding-right:2px;">
        <div class="table-responsive">
            <div style="overflow-x:auto; width:100%;">
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
                            <th style="font-size:11px;width:1%;"></th>
                        </tr>
                    </thead>
                    <tbody id="dynamictable">
                        @php
                            $num = 0;
                            $itemcode = old('itemcode', []);
                            $productdesc = old('productdesc', []);
                            $idno = old('idno', []);
                            $totalquant = old('totalquant', []);
                            $affectquant = old('affectquant', []);
                            $unit = old('unit', []);
                            $costimpact = old('costimpact', []);
                            $com_category = old('complaintcategory',[]);
                            $salereturnreq = old('salereturnreq',[]);
                            $reworkreq = old('reworkreq',[]);
                            $sizeofitem = old('sizeofitem',[]);
                        @endphp
                        @if(count($itemcode)<=0)
                        <tr>

                            <td><span style="font-size:11px;">{{++$num}}</span></td>
                            @if ($complaintTemplateSectionFields['itemcode']['enable_field'])
                                <td class="td-class"><input type="text" class="form-control" name="itemcode[]" id="itemcode" style="font-size:12px;" placeholder="Enter {{$complaintTemplateSectionFields['itemcode']['field_display_label']}}" {{ $complaintTemplateSectionFields['itemcode']['field_is_mandatory'] ? 'required' : '' }}></td>
                            @endif
                            @if ($complaintTemplateSectionFields['productdesc']['enable_field'])
                                <td class="td-class"><textarea class="form-control" cols="25" name="productdesc[]" id="productdesc" rows="4" style="font-size:12px;" {{ $complaintTemplateSectionFields['productdesc']['field_is_mandatory'] ? 'required' : '' }} placeholder="Enter {{$complaintTemplateSectionFields['productdesc']['field_display_label']}}"></textarea></td>
                            @endif
                            @if ($complaintTemplateSectionFields['sizeofitem']['enable_field'])
                                <td class="td-class"><input type="text" class="form-control" name="sizeofitem[]" id="sizeofitem" style="font-size:12px;" placeholder="Enter {{$complaintTemplateSectionFields['sizeofitem']['field_display_label']}}" {{ $complaintTemplateSectionFields['sizeofitem']['field_is_mandatory'] ? 'required' : '' }}></td>
                            @endif
                            @if ($complaintTemplateSectionFields['idno']['enable_field'])
                                <td class="td-class"><input type="text" class="form-control" name="idno[]" id="idno" style="font-size:12px;" placeholder="Enter {{$complaintTemplateSectionFields['idno']['field_display_label']}}" {{ $complaintTemplateSectionFields['idno']['field_is_mandatory'] ? 'required' : '' }}></td>
                            @endif
                            @if ($complaintTemplateSectionFields['totalquant']['enable_field'])
                                <td class="td-class">
                                    <input type="number" id="totalquant" class="form-control" name="totalquant[]" style="font-size:12px;" placeholder="Enter {{$complaintTemplateSectionFields['totalquant']['field_display_label']}}" {{ $complaintTemplateSectionFields['totalquant']['field_is_mandatory'] ? 'required' : '' }}>
                                </td>
                            @endif
                            @if ($complaintTemplateSectionFields['affectquant']['enable_field'])
                                <td class="td-class">
                                    <input type="number" id="affectquant" class="form-control" name="affectquant[]" style="font-size:12px;" placeholder="Enter {{$complaintTemplateSectionFields['affectquant']['field_display_label']}}" {{ $complaintTemplateSectionFields['affectquant']['field_is_mandatory'] ? 'required' : '' }}>
                                </td>
                            @endif
                            @if ($complaintTemplateSectionFields['unit']['enable_field'])
                                <td class="td-class" id="unitDiv">
                                    <select class="form-control js-select2 unitclass" name="unit[]" id="unit" style="font-size: 11px;width:100%;" data-placeholder="Select {{$complaintTemplateSectionFields['unit']['field_display_label']}}" {{ $complaintTemplateSectionFields['unit']['field_is_mandatory'] ? 'required' : '' }}>
                                        <option selected disabled>Choose {{$complaintTemplateSectionFields['unit']['field_display_label']}}</option>
                                        @if(count($units)> 0 )
                                        @foreach($units as $unit)
                                        <option value="{{$unit->id}}"> {{ $unit->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </td>
                            @endif
                            @if ($complaintTemplateSectionFields['costimpact']['enable_field'])
                                <td class="td-class">
                                    <input type="text" id="costimpact" class="form-control" name="costimpact[]" style="font-size:12px;" placeholder="Enter {{$complaintTemplateSectionFields['costimpact']['field_display_label']}}" {{ $complaintTemplateSectionFields['costimpact']['field_is_mandatory'] ? 'required' : '' }}>
                                </td>
                            @endif
                            @if ($complaintTemplateSectionFields['complaintcategory']['enable_field'])
                                <td class="td-class" id="categoryDiv">
                                    <select data-placeholder="Select {{$complaintTemplateSectionFields['complaintcategory']['field_display_label']}}" class="form-control js-select2 categoryclass" name="complaintcategory[{{$num}}][]" id="complaintcategory" style="font-size: 12px;width:100%;" {{ $complaintTemplateSectionFields['complaintcategory']['field_is_mandatory'] ? 'required' : '' }} multiple>
                                        @if(count($complaint_cat)> 0 )
                                        @foreach($complaint_cat as $cc)
                                        <option value="{{$cc->id}}"> {{ $cc->category_name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </td>
                            @endif
                            @if ($complaintTemplateSectionFields['salereturnreq']['enable_field'])
                                <td class="td-class">
                                    <input type="text" id="salereturnreq" class="form-control" name="salereturnreq[]" style="font-size:12px;" placeholder="Enter {{$complaintTemplateSectionFields['salereturnreq']['field_display_label']}}" {{ $complaintTemplateSectionFields['salereturnreq']['field_is_mandatory'] ? 'required' : '' }}>
                                </td>
                            @endif
                            @if ($complaintTemplateSectionFields['reworkreq']['enable_field'])
                                <td class="td-class">
                                    <input type="text" id="reworkreq" class="form-control" name="reworkreq[]" style="font-size:12px;" placeholder="Enter {{$complaintTemplateSectionFields['reworkreq']['field_display_label']}}" {{ $complaintTemplateSectionFields['reworkreq']['field_is_mandatory'] ? 'required' : '' }}>
                                </td>
                            @endif

                            <td class="td-class">
                                <button type="button" name="add" class="btn btn-sm btn-light" style="font-size:11px;" data-toggle="tooltip" title="Add" onclick="addRaw()">
                                    <i class="fa fa-fw fa-plus"></i>
                                </button>
                            </td>
                        </tr>
                        @else
                        @for($row=0;$row<count($itemcode);$row++)
                        <tr>

                            <td><span style="font-size:11px;">{{++$num}}</span></td>
                            @if ($complaintTemplateSectionFields['itemcode']['enable_field'])
                                <td class="td-class"><input type="text" class="form-control" name="itemcode[]" id="itemcode{{$num}}" style="font-size:12px;" placeholder="Enter {{$complaintTemplateSectionFields['itemcode']['field_display_label']}}" value="{{$itemcode[$row]}}" {{ $complaintTemplateSectionFields['itemcode']['field_is_mandatory'] ? 'required' : '' }}></td>
                            @endif
                            @if ($complaintTemplateSectionFields['productdesc']['enable_field'])
                                <td class="td-class"><textarea class="form-control" cols="25" name="productdesc[]" id="productdesc{{$num}}" rows="4" style="font-size:12px;" {{ $complaintTemplateSectionFields['productdesc']['field_is_mandatory'] ? 'required' : '' }} placeholder="Enter {{$complaintTemplateSectionFields['productdesc']['field_display_label']}}">{{$productdesc[$row]}}</textarea></td>
                            @endif
                            @if ($complaintTemplateSectionFields['sizeofitem']['enable_field'])
                                <td class="td-class"><input type="text" class="form-control" name="sizeofitem[]" id="sizeofitem{{$num}}" style="font-size:12px;" placeholder="Enter {{$complaintTemplateSectionFields['sizeofitem']['field_display_label']}}" {{ $complaintTemplateSectionFields['sizeofitem']['field_is_mandatory'] ? 'required' : '' }} value="{{$sizeofitem[$row]}}"></td>
                            @endif
                            @if ($complaintTemplateSectionFields['idno']['enable_field'])
                                <td class="td-class"><input type="text" class="form-control" value="{{$idno[$row]}}"  name="idno[]" id="idno{{$num}}" style="font-size:12px;" placeholder="Enter {{$complaintTemplateSectionFields['idno']['field_display_label']}}" {{ $complaintTemplateSectionFields['idno']['field_is_mandatory'] ? 'required' : '' }}></td>
                            @endif
                            @if ($complaintTemplateSectionFields['totalquant']['enable_field'])
                                <td class="td-class">
                                    <input type="number" id="totalquant{{$num}}" class="form-control" value="{{$totalquant[$row]}}" name="totalquant[]" style="font-size:12px;" placeholder="Enter {{$complaintTemplateSectionFields['totalquant']['field_display_label']}}" {{ $complaintTemplateSectionFields['totalquant']['field_is_mandatory'] ? 'required' : '' }}>
                                </td>
                            @endif
                            @if ($complaintTemplateSectionFields['affectquant']['enable_field'])
                                <td class="td-class">
                                    <input type="number" id="affectquant{{$num}}" class="form-control" value="{{$affectquant[$row]}}" name="affectquant[]" style="font-size:12px;" placeholder="Enter {{$complaintTemplateSectionFields['affectquant']['field_display_label']}}" {{ $complaintTemplateSectionFields['affectquant']['field_is_mandatory'] ? 'required' : '' }}>
                                </td>
                            @endif
                            @if ($complaintTemplateSectionFields['unit']['enable_field'])
                                <td class="td-class" id="unitDiv{{$num}}">
                                    <select class="form-control js-select2 unitclass" name="unit[]" id="unitid{{$num}}" style="font-size: 11px;width:100%;" data-placeholder="Select {{$complaintTemplateSectionFields['unit']['field_display_label']}}" {{ $complaintTemplateSectionFields['unit']['field_is_mandatory'] ? 'required' : '' }}>
                                        <option selected disabled>Choose {{$complaintTemplateSectionFields['unit']['field_display_label']}}</option>
                                        @if(count($units)> 0 )
                                        @foreach($units as $unit)
                                        <option value="{{$unit->id}}" {{$unit[$row]==$unit->id ? 'selected' : ''}}> {{ $unit->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </td>
                            @endif
                            @if ($complaintTemplateSectionFields['costimpact']['enable_field'])
                                <td class="td-class">
                                    <input type="text" id="costimpact{{$num}}" class="form-control" value="{{$costimpact[$row]}}" name="costimpact[]" style="font-size:12px;" placeholder="Enter {{$complaintTemplateSectionFields['costimpact']['field_display_label']}}" {{ $complaintTemplateSectionFields['costimpact']['field_is_mandatory'] ? 'required' : '' }}>
                                </td>
                            @endif
                            @if ($complaintTemplateSectionFields['complaintcategory']['enable_field'])
                                <td class="td-class" id="categoryDiv{{$num}}">
                                    <select class="form-control js-select2 categoryclass" name="complaintcategory[{{$num}}][]" id="complaintcategory{{$num}}" style="font-size: 12px;width:100%;" multiple data-title="Choose {{$complaintTemplateSectionFields['complaintcategory']['field_display_label']}}" {{ $complaintTemplateSectionFields['complaintcategory']['field_is_mandatory'] ? 'required' : '' }}>
                                        @if(count($complaint_cat)> 0 )
                                        @foreach($complaint_cat as $cc)
                                        <option value="{{$cc->id}}" <?= in_array($cc->id,$com_category[$num]) ? 'selected' : ''?>> {{ $cc->category_name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </td>
                            @endif
                            @if ($complaintTemplateSectionFields['salereturnreq']['enable_field'])
                                <td class="td-class">
                                    <input type="text" id="salereturnreq{{$num}}" value="{{$salereturnreq[$row]}}" class="form-control" name="salereturnreq[]" style="font-size:12px;" placeholder="Enter {{$complaintTemplateSectionFields['salereturnreq']['field_display_label']}}" {{ $complaintTemplateSectionFields['salereturnreq']['field_is_mandatory'] ? 'required' : '' }}>
                                </td>
                            @endif
                            @if ($complaintTemplateSectionFields['reworkreq']['enable_field'])
                                <td class="td-class">
                                    <input type="text" id="reworkreq{{$num}}" class="form-control" value="{{$reworkreq[$row]}}" name="reworkreq[]" style="font-size:12px;" placeholder="Enter {{$complaintTemplateSectionFields['reworkreq']['field_display_label']}}" {{ $complaintTemplateSectionFields['reworkreq']['field_is_mandatory'] ? 'required' : '' }}>
                                </td>
                            @endif
                            @if($num==1)
                            <td class="td-class">
                                <button type="button" name="add" class="btn btn-sm btn-light" style="font-size:11px;" data-toggle="tooltip" title="Add" onclick="addRaw()">
                                    <i class="fa fa-fw fa-plus"></i>
                                </button>
                            </td>
                            @else
                            <td class="text-center td-class"><button type="button" name="remove" style="font-size:11px;" class="btn btn-sm btn-light remove-tr" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-fw fa-times text-danger"></i></button>
                            </td>
                            @endif
                        </tr>

                        <script>
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
                            $("#unit{{$num}}").blur(function(){
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
                            $('#unit{{$num}}').change(function(){
                                if($(this).val()){
                                    $('#select2-unit-container').css("background-color","#e6e6ff");
                                }
                            });
                            $("#unitDiv{{$num}}").click(function(){
                                removeUnitRow1({{$num}});
                                addUnitRow1({{$num}});
                            });
                            $("#categoryDiv{{$num}}").click(function(){
                                removeCategoryRow1({{$num}});
                                addCategoryRow1({{$num}});
                            });
                            var isAdmin = "{{$isAdminResult}}";
                            function addCategoryRow1(count){
                                var addBtn = `<hr id="addCategoryHr1${count}" style="margin:auto 0;border:solid 1px;"><ul style="list-style-type:none;padding:0;margin:0;cursor:pointer;" id="addCategoryBtn1${count}"><li class="select2-results__option select2-results__option"><a  target="_blank" style="font-size:13px;" class="text-primary"><i class="fa fa-2x fa-plus" style="font-size:11px;">&nbsp;New Category</i></a></li></ul>`;
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
                            function addCategoryRow1(count){
                                var addBtn = `<hr id="addUnitHr1${count}" style="margin:auto 0;border:solid 1px;"><ul style="list-style-type:none;padding:0;margin:0;cursor:pointer;" id="addUnitBtn1${count}"><li class="select2-results__option select2-results__option"><a  target="_blank" style="font-size:13px;" class="text-primary"><i class="fa fa-2x fa-plus" style="font-size:11px;">&nbsp;New Unit</i></a></li></ul>`;
                                for(var x=1;x<=count;x++){
                                    $('#select2-unitid'+x+'-results').after(addBtn);
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
                        @endfor
                        @endif
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
            <textarea type="text" class="form-control" id="action" style="font-size: 13px;" name="action" placeholder="Enter Here">{{old('action')}}</textarea>
            <div id="action-error" class="text-danger" style="display:none;font-size:12px;">Recommended Action field is required.</div>
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-sm-12 col-md-4">
        <label class="col-sm-12 col-form-label" for="department" style="font-size: 13px;">Responsible Department <span class="text-danger">*</span></label>
        <div class="col-sm-12">
            <select class="form-control js-select2" id="department" name="department" style="font-size: 13px;width:100%;" required="true" onchange="chooseDivision($(this).val())">
                <option selected disabled value="">Choose Department</option>
                @if(count($departments)>0)
                @foreach($departments as $dep)
                <option value="{{$dep->id}}" <?= old('department') == $dep->id ? 'selected' : '' ?>> {{$dep->departmentname}}</option>
                @endforeach
                @endif
            </select>
        </div>
    </div>
    @php
        $divisions = App\Division::where('department_id',old('department'))->get();
        $areas = App\Area::where('division_id',old('division'))->get();
    @endphp
    <div class="form-group {{count($divisions)>0?'': 'd-none'}} col-sm-12 col-md-4" id="division">
        <label class="col-sm-12 col-form-label" style="font-size:13px;" for="division">Division</label>
        <div class="col-sm-12">
            <select class="form-control js-select2" id="division_select" name="division" style="font-size: 13px;width:100%;" placeholder="Division" onchange="chooseArea($(this).val())">
                <option value="" selected>Choose Division</option></br>
                @if(count($divisions)>0)
                @foreach($divisions as $division)
                <option value="{{$division->id}}" {{$division->id==old('division')?'selected':''}}>{{$division->name}}</option>
                @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="form-group {{count($areas)>0?'': 'd-none'}} col-sm-12 col-md-4" id="area">
        <label class="col-sm-12 col-form-label" style="font-size:13px;" for="area">Sub Division</label>
        <div class="col-sm-12" id="action_number" name="action">
            <select class="form-control js-select2" id="area_select" name="area" style="font-size: 13px;width:100%;" placeholder="Sub Division">
                <option value="" selected>Choose Sub Division</option></br>
                @if(count($areas)>0)
                @foreach($areas as $area)
                <option value="{{$area->id}}" {{$area->id==old('area')?'selected':''}}>{{$area->name}}</option>
                @endforeach
                @endif
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
                <option value="{{$use->email}}" <?= old('responsibility') == $use->email ? 'selected' : '' ?>> {{ $use->name }}</option><br>
                @endforeach
                @endif
            </select>
        </div>
    </div>

    <div class="form-group col-sm-12 col-md-4">
        <label class="col-sm-12 col-form-label" for="date" style="font-size: 13px;">Target date <span class="text-danger">{{in_array(Auth::user()->email, $issue_reviewers) ? "*" : ''}}<span></label>
        <div class="col-sm-12">
            <input type="Date" class="form-control" id="date" style="font-size: 13px;" name="date" {{in_array(Auth::user()->email, $issue_reviewers) ? "required" : ''}} value="{{ old('date')}}">
        </div>
    </div>
</div>

<!-- Below include file contains logic for Repetitive Complaint Identification -->
@include('user.complaint.common.repetitiveComplaintIdentification')

<script>
    var count = {{$num}};
    var divcounter = {{$num}}

    var itemCodeEnabled= {{$complaintTemplateSectionFields['itemcode']['enable_field'] ?? 0}}
    var itemCodeMandatory = {{ $complaintTemplateSectionFields['itemcode']['field_is_mandatory'] }}

    var productDescriptionEnabled= {{$complaintTemplateSectionFields['productdesc']['enable_field'] ?? 0}}
    var productDescriptionMandatory = {{ $complaintTemplateSectionFields['productdesc']['field_is_mandatory'] }}

    var sizeOfItemEnabled= {{$complaintTemplateSectionFields['sizeofitem']['enable_field'] ?? 0}}
    var sizeOfItemMandatory = {{ $complaintTemplateSectionFields['sizeofitem']['field_is_mandatory'] }}

    var idNoEnabled= {{$complaintTemplateSectionFields['idno']['enable_field'] ?? 0}}
    var idNoMandatory = {{ $complaintTemplateSectionFields['idno']['field_is_mandatory'] }}

    var totalDeliveryQuantityEnabled= {{$complaintTemplateSectionFields['totalquant']['enable_field'] ?? 0}}
    var totalDeliveryQuantityMandatory = {{ $complaintTemplateSectionFields['totalquant']['field_is_mandatory'] }}

    var affectedQuantityEnabled= {{$complaintTemplateSectionFields['affectquant']['enable_field'] ?? 0}}
    var affectedQuantityMandatory = {{ $complaintTemplateSectionFields['affectquant']['field_is_mandatory'] }}

    var unitEnabled= {{$complaintTemplateSectionFields['unit']['enable_field'] ?? 0}}
    var unitMandatory = {{ $complaintTemplateSectionFields['unit']['field_is_mandatory'] }}

    var costImpactEnabled= {{$complaintTemplateSectionFields['costimpact']['enable_field'] ?? 0}}
    var costImpactMandatory = {{ $complaintTemplateSectionFields['costimpact']['field_is_mandatory'] }}

    var complaintCategoryEnabled= {{$complaintTemplateSectionFields['complaintcategory']['enable_field'] ?? 0}}
    var complaintCategoryMandatory = {{ $complaintTemplateSectionFields['complaintcategory']['field_is_mandatory'] }}

    var saleReturnRequestEnabled= {{$complaintTemplateSectionFields['salereturnreq']['enable_field'] ?? 0}}
    var saleReturnRequestMandatory = {{ $complaintTemplateSectionFields['salereturnreq']['field_is_mandatory'] }}

    var reworkRequestEnabled= {{$complaintTemplateSectionFields['reworkreq']['enable_field'] ?? 0}}
    var reworkRequestMandatory = {{ $complaintTemplateSectionFields['reworkreq']['field_is_mandatory'] }}

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
            $(this).find('td:first').text(index+1);
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
        var addBtn = `<hr id="addCategoryHr${count}" style="margin:auto 0;border:solid 1px;"><ul style="list-style-type:none;padding:0;margin:0;cursor:pointer;" id="addCategoryBtn${count}"><li class="select2-results__option select2-results__option"><a  target="_blank" style="font-size:11px;" class="text-primary"><i class="fa fa-2x fa-plus" style="font-size:13px;">&nbsp;New Category</i></a></li></ul>`;
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
        var addBtn = `<hr id="addUnitHr${count}" style="margin:auto 0;border:solid 1px;"><ul style="list-style-type:none;padding:0;margin:0;cursor:pointer;" id="addUnitBtn${count}"><li class="select2-results__option select2-results__option"><a  target="_blank" style="font-size:11px;" class="text-primary"><i class="fa fa-2x fa-plus" style="font-size:13px;">&nbsp;New Unit</i></a></li></ul>`;
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

