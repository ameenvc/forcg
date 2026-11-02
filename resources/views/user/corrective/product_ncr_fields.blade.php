@if($mode=='blank')
    @foreach($productNcrFields as $field)
        <div class="form-group productNcrFieldClass {{$field->field_type=='textarea' ? 'col-sm-12' : 'col-sm-6'}}">
            <label class="col-sm-12 col-form-label" for="{{$field->display_label}}" style="font-size: 13px;">{{$field->display_label}} <span class="text-danger">{{$field->is_mandatory==0?'':'*'}}</span></label>
            <div class="col-sm-12">
                @if($field->field_type=='text' || $field->field_type=='number' || $field->field_type=='date')
                    <input type="{{$field->field_type}}" name="{{$field->field_name}}" id="{{$field->field_name}}" placeholder="Enter {{$field->display_label}}" class="form-control" {{$field->is_mandatory==0?'':'required'}} style="font-size: 13px;width:100%;">
                @elseif($field->field_type=='textarea')
                    <textarea name="{{$field->field_name}}" id="{{$field->field_name}}" placeholder="Enter {{$field->display_label}}" class="form-control" {{$field->is_mandatory==0?'':'required'}} style="font-size: 13px;width:100%;"></textarea>
                @elseif($field->field_type=='dropdown')
                    <select name="{{$field->field_name}}" id="{{$field->field_name}}" class="form-control dropdown-ncr-field" data-placeholder="Select {{$field->display_label}}" {{$field->is_mandatory==0?'':'required'}} style="font-size: 13px;width:100%;">
                        <option></option>
                        @foreach($vendors as $vendor)
                            @if($vendor->is_deleted==1)
                                @continue
                            @endif
                            <option value="{{$vendor->id}}">{{$vendor->vendor_name}}</option>
                        @endforeach
                    </select>
                @endif
            </div>
        </div>
    @endforeach
@elseif($mode=='blank-col-sm-6')
    @foreach($productNcrFields as $field)
        <div class="form-group productNcrFieldClass {{$field->field_type=='textarea' ? 'col-sm-12' : 'col-sm-6'}}">
            <label class="col-sm-12 col-form-label" for="{{$field->display_label}}" style="font-size: 13px;">{{$field->display_label}} <span class="text-danger">{{$field->is_mandatory==0?'':'*'}}</span></label>
            <div class="col-sm-12">
                @if($field->field_type=='text' || $field->field_type=='number' || $field->field_type=='date')
                    <input type="{{$field->field_type}}" name="{{$field->field_name}}" id="{{$field->field_name}}" placeholder="Enter {{$field->display_label}}" class="form-control" {{$field->is_mandatory==0?'':'required'}} style="font-size: 13px;width:100%;">
                @elseif($field->field_type=='textarea')
                    <textarea name="{{$field->field_name}}" id="{{$field->field_name}}" placeholder="Enter {{$field->display_label}}" class="form-control" {{$field->is_mandatory==0?'':'required'}} style="font-size: 13px;width:100%;"></textarea>
                @elseif($field->field_type=='dropdown')
                    <select name="{{$field->field_name}}" id="{{$field->field_name}}" class="form-control dropdown-ncr-field" data-placeholder="Select {{$field->display_label}}" {{$field->is_mandatory==0?'':'required'}} style="font-size: 13px;width:100%;">
                        <option></option>
                        @foreach($vendors as $vendor)
                            @if($vendor->is_deleted==1 && $field->value != $vendor->id)
                                @continue
                            @endif
                            <option value="{{$vendor->id}}">{{$vendor->vendor_name}}</option>
                        @endforeach
                    </select>
                @endif
            </div>
        </div>
    @endforeach
@elseif($mode=='edit')
    @foreach($ncrValues as $field)
        <div class="form-group productNcrFieldClass {{$fieldTypeArray[$field->field_id]=='textarea' ? 'col-sm-12' : 'col-sm-6'}}">
            <label class="col-sm-12 col-form-label" style="font-size:13px;"> {{ $field->display_label }} @if($mandatoryProductFieldArray[$field->field_id])<span class="text-danger">*</span>@endif </label>
            <div class="col-sm-12">
                @if($fieldTypeArray[$field->field_id]=='text' || $fieldTypeArray[$field->field_id]=='number' || $fieldTypeArray[$field->field_id]=='date')
                    <input type="{{ $fieldTypeArray[$field->field_id] }}"  name="{{ $field->field_name }}"
                        id="{{ $field->field_name }}" value="{{ $field->value }}" placeholder="Enter {{$field->display_label }}"
                        class="form-control" style="font-size:13px;width:100%;" {{ $mandatoryProductFieldArray[$field->field_id] ? 'required' : '' }}
                    >
                @elseif($fieldTypeArray[$field->field_id]=='textarea')
                    <textarea name="{{$field->field_name}}" id="{{$field->field_name}}" placeholder="Enter {{$field->display_label}}" class="form-control" {{ $mandatoryProductFieldArray[$field->field_id] ? 'required' : '' }} style="font-size: 13px;width:100%;">{{ $field->value }}</textarea>
                @elseif($fieldTypeArray[$field->field_id]=='dropdown')
                    <select name="{{$field->field_name}}" id="{{$field->field_name}}" class="form-control dropdown-ncr-field" data-placeholder="Select {{$field->display_label}}" {{ $mandatoryProductFieldArray[$field->field_id] ? 'required' : '' }} style="font-size: 13px;width:100%;">
                        <option></option>
                        @foreach($vendors as $vendor)
                            @if($vendor->is_deleted==1 && $field->value != $vendor->id)
                                @continue
                            @endif
                            <option value="{{$vendor->id}}" {{ $field->value == $vendor->id ? 'selected' : '' }}>{{$vendor->vendor_name}}</option>
                        @endforeach
                    </select>
                @endif
            </div>
        </div>
    @endforeach
@elseif($mode=='col-sm-6')
    @foreach($ncrValues as $field)
        <div class="form-group productNcrFieldClass {{$fieldTypeArray[$field->field_id]=='textarea' ? 'col-sm-12' : 'col-sm-6'}}">
            <label class="col-sm-12 col-form-label" style="font-size:13px;"> {{ $field->display_label }} @if($mandatoryProductFieldArray[$field->field_id])<span class="text-danger">*</span>@endif </label>
            <div class="col-sm-12">
                @if($fieldTypeArray[$field->field_id]=='text' || $fieldTypeArray[$field->field_id]=='number' || $fieldTypeArray[$field->field_id]=='date')
                    <input type="{{ $fieldTypeArray[$field->field_id] }}"  name="{{ $field->field_name }}"
                        id="{{ $field->field_name }}" value="{{ $field->value }}" placeholder="Enter {{$field->display_label }}"
                        class="form-control" style="font-size:13px;width:100%;" {{ $mandatoryProductFieldArray[$field->field_id] ? 'required' : '' }}
                    >
                @elseif($fieldTypeArray[$field->field_id]=='textarea')
                    <textarea name="{{$field->field_name}}" id="{{$field->field_name}}" placeholder="Enter {{$field->display_label}}" class="form-control" {{ $mandatoryProductFieldArray[$field->field_id] ? 'required' : '' }} style="font-size: 13px;width:100%;">{{ $field->value }}</textarea>
                @elseif($fieldTypeArray[$field->field_id]=='dropdown')
                    <select name="{{$field->field_name}}" id="{{$field->field_name}}" class="form-control dropdown-ncr-field" data-placeholder="Select {{$field->display_label}}" {{ $mandatoryProductFieldArray[$field->field_id] ? 'required' : '' }} style="font-size: 13px;width:100%;">
                        <option></option>
                        @foreach($vendors as $vendor)
                            @if($vendor->is_deleted==1 && $field->value != $vendor->id)
                                @continue
                            @endif
                            <option value="{{$vendor->id}}" {{ $field->value == $vendor->id ? 'selected' : '' }}>{{$vendor->vendor_name}}</option>
                        @endforeach
                    </select>
                @endif
            </div>
        </div>
    @endforeach
@elseif($mode=='readonly')
    @foreach($ncrValues as $field)
        <div class="form-group productNcrFieldClass {{$fieldTypeArray[$field->field_id]=='textarea' ? 'col-sm-12' : 'col-sm-6'}}">
            <label class="col-sm-12 col-form-label" style="font-size:13px;"> {{ $field->display_label }} @if($mandatoryProductFieldArray[$field->field_id])<span class="text-danger">*</span>@endif </label>
            <div class="col-sm-12">
                @if($fieldTypeArray[$field->field_id]=='text' || $fieldTypeArray[$field->field_id]=='number' || $fieldTypeArray[$field->field_id]=='date')
                    <input type="{{ $fieldTypeArray[$field->field_id] }}" value="{{ $field->value }}" 
                        class="form-control" style="font-size:13px;width:100%;"  {{ $fieldTypeArray[$field->field_id]=='date' ? 'disabled' : 'readonly' }}
                    >
                @elseif($fieldTypeArray[$field->field_id]=='textarea')
                    <textarea class="form-control"  style="font-size: 13px;width:100%;" readonly>{{ $field->value }}</textarea>
                @elseif($fieldTypeArray[$field->field_id]=='dropdown')
                    @php
                        $vendorName = $vendors->where('id', $field->value)->first()->vendor_name ?? '';
                    @endphp
                    <input type="text" value="{{ $vendorName }}" class="form-control" style="font-size:13px;width:100%;" readonly>
                @endif
            </div>
        </div>
    @endforeach
@elseif($mode=='view-detail')
    @foreach($ncrValues as $field)
        <tr>
            <th>{{ $field->display_label }}</th>
            @if($fieldTypeArray[$field->field_id]=='dropdown')
                @php
                    $vendorName = $vendors->where('id', $field->value)->first()->vendor_name ?? '';
                @endphp
                <td>{{ $vendorName }}</td>
            @else
                <td >{{ $field->value }}</td>
            @endif
        </tr>
    @endforeach
@elseif($mode=='new-detail-page')
    <link rel="stylesheet" href="{{ versionedCache('css/user/corrective/ncrfieldstyle.css') }}">
    {{-- Only render if there are populated fields --}}
    @if($ncrValues->where('value', '!=', null)->isNotEmpty())
    <div class="mt-4 category-details-block mx-5">
        {{-- Header --}}
        <div class="section-header">
            <img class="header-icon"
                src="{{ asset('images/icons/category-section.png') }}"
                alt="category section icon">
            <label style="font-size:13px;">Category Details</label>
        </div>
        @php 
            $filledCount = $ncrValues->whereNotNull('value')->where('value', '!=', '')->count();
        @endphp
        {{-- Cards --}}
        <div class="card-grid">
            @foreach($ncrValues as $field)
                @if($field->value)
                    @php
                        $isTextarea = ($fieldTypeArray[$field->field_id] ?? '') === 'textarea';
                    @endphp
                    <div class="detail-card {{$filledCount>3 ? 'detail-card' : 'detail-card-auto'}} {{ $isTextarea ? 'is-textarea' : '' }}">

                        {{-- Label --}}
                        <span class="card-label">{{ $field->display_label }}</span>

                        {{-- Value --}}
                        <div class="card-value">
                            <img class="field-icon"
                                src="{{ asset('images/icons/' . $field->field_name . '.png') }}"
                                alt="{{ $field->field_name }} icon">

                            @if(($fieldTypeArray[$field->field_id] ?? '') === 'dropdown')
                                <span>{{ $vendors->where('id', $field->value)->first()->vendor_name ?? '—' }}</span>
                            @elseif(($fieldTypeArray[$field->field_id] ?? '') === 'date')
                                <span>{{ date('d-m-Y', strtotime($field->value)) }}</span>
                            @else
                                <span>{{ $field->value }}</span>
                            @endif
                        </div>

                    </div>
                @endif
            @endforeach
        </div>

    </div>
    @endif
@elseif($mode=='pdf-detail-page')
    <style>
        .category-block{
            background: #F6F7F9;
            border-radius: 10px 10px 10px 10px;
            display: block;
            padding-bottom: 10px;
            width: 100%;
            margin-top: 5px;
            margin-bottom: 10px;
            padding-top: 10px;
            height: auto;
        }
        .category-block .category-title{
            margin-left: 20px;
            margin-bottom: 20px;
        }
        .category-block .category-title img {
            display: inline-block;
            vertical-align: middle;
            margin-right: 5px;
            width: 16px;
            height: 16px;
            padding: 5px;
            background: #2529453D;
            border-radius: 5px 5px 5px 5px
        }
        .category-block .category-title span{
            display: inline-block;
            vertical-align: middle;
            font-weight: 600;
        }
        .category-block .category-content-block {
            margin-top: 20px;
            display: block;
            width: 100%;
            margin-left: 20px;
        }
        .category-block .category-content-block .category-item-block {
            background: white;
            margin-right: 10px;
            padding: 12px 22px 12px 12px;
            border-radius: 10px;
            display: inline-block;
            width: 180px;        /* cap the card width */
            vertical-align: top;    /* keeps cards aligned when text wraps */
        }
        .category-block .category-content-block .category-item-block label {
            font-weight: 600;
            font-size: 10px;
            color: #6b7280;
        }
        .category-block .category-content-block .category-item-block div {
            margin-top: 10px;
        }
        .category-block .category-content-block .category-item-block div img {
            display: inline-block;
            vertical-align: middle;
            margin-right: 5px;
            width: 20px;
            height: 20px;
            border-radius: 5px 5px 5px 5px
        }
        .category-block .category-content-block .category-item-block div span {
            display: inline-block;
            vertical-align: middle;
            font-weight: 600;
            font-size: 10px;
            text-align: justify;
            white-space: normal;
        }
        .category-block .category-content-block .category-item-block-textarea {
            background: white;
            margin-right: 10px;
            padding: 12px;
            border-radius: 10px;
            display: block;
            width: 95%;
            box-sizing: border-box;
            overflow: hidden;
        }

        .category-block .category-content-block .category-item-block-textarea label {
            font-weight: 600;
            font-size: 11px;
            color: #6b7280;
            display: block;
        }

        .category-block .category-content-block .category-item-block-textarea div {
            margin-top: 10px;
        }

        .category-block .category-content-block .category-item-block-textarea div img {
            display: inline-block;
            vertical-align: top;
            margin-right: 5px;
            width: 20px;
            height: 20px;
            border-radius: 5px;
        }

        .category-block .category-content-block .category-item-block-textarea div span {
            display: inline-block;
            vertical-align: top;
            font-weight: 600;
            font-size: 10px;
            word-break: break-word;
            overflow-wrap: break-word;
            white-space: normal;
            width: 90%;
        }
    </style>
    @php
        $rows = collect();
        $regularItems = collect();

        foreach ($ncrValues as $field) {
            if (!$field->value) continue;

            $isTextarea = ($fieldTypeArray[$field->field_id] ?? '') === 'textarea';
            $isDropdown = ($fieldTypeArray[$field->field_id] ?? '') === 'dropdown';
            $isDate = ($fieldTypeArray[$field->field_id] ?? '') === 'date';
            $value = $isDropdown
                ? ($vendors->where('id', $field->value)->first()->vendor_name ?? '—')
                : $field->value;
            $value = $isDate ? date('d-m-Y', strtotime($field->value)) : $value;
            if ($isTextarea) {
                if ($regularItems->isNotEmpty()) {
                    $rows->push(['type' => 'regular', 'items' => $regularItems->chunk(3)]);
                    $regularItems = collect();
                }
                $rows->push([
                    'type'      => 'textarea',
                    'label'     => $field->display_label,
                    'value'     => $value,
                    'field_name'=> $field->field_name,
                ]);
            } else {
                $regularItems->push([
                    'label'      => $field->display_label,
                    'value'      => $value,
                    'field_name' => $field->field_name,
                ]);
            }
        }

        if ($regularItems->isNotEmpty()) {
            $rows->push(['type' => 'regular', 'items' => $regularItems->chunk(3)]);
        }
    @endphp
    <div class="category-block">
        <div class="category-title">
            <img src="{{ public_path('images/icons/category-section.png') }}">
            <span>Category Details</span>
        </div>

        @foreach ($rows as $row)
            @if ($row['type'] === 'regular')
                @foreach ($row['items'] as $chunk)
                    <div class="category-content-block">
                        @foreach ($chunk as $item)
                            <div class="category-item-block">
                                <label>{{ $item['label'] }}</label>
                                <div>
                                    <img src="{{ public_path('images/icons/' . $item['field_name'] . '.png') }}">
                                    <span>{{ $item['value'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            @elseif ($row['type'] === 'textarea')
                <div class="category-content-block">
                    <div class="category-item-block-textarea">
                        <label>{{ $row['label'] }}</label>
                        <div>
                            <img src="{{ public_path('images/icons/' . $row['field_name'] . '.png') }}">
                            <span>{{ $row['value'] }}</span>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@endif