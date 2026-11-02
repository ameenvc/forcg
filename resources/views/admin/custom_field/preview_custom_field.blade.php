@if($pageDetail->structure=='table-col-2')
    @if($pageDetail->is_target_page)
        @foreach($customFields as $customField)
            @php 
                if($customField->id==$field_id && $is_edit_form){
                    $options = $newoptions;
                    $disabled = '';
                    $fieldmandatory = $mandatory==0 ? '' : '*';
                    $selectClass = $fieldtype=='select' || $fieldtype=='multiselect' ? 'custom_field_select' : '';
                    $fieldType = $fieldtype;
                    $fieldName = $fieldname;
                    $fieldPlaceHolder = $fieldplaceholder;
                    $inputType = $fieldtype=='file_multiple' ? 'file' : $fieldtype;
                    $multiple = $fieldtype=='multiselect' ? 'multiple' : '';
                }else{
                    $options = App\DropdownOption::where('is_deleted',false)->where('custom_field_id',$customField->id)->pluck('option','id')->toArray();
                    $disabled = 'disabled';
                    $fieldName = $customField->field_name;
                    $fieldPlaceHolder = $customField->field_placeholder;
                    $fieldType = $customField->field_type;
                    $inputType = $customField->field_type=='file_multiple' ? 'file' : $customField->field_type;
                    $fieldmandatory = $customField->mandatory==0 ? '' : '*';
                    $selectClass = '';
                    $multiple = '';
                }
            @endphp
            @switch($fieldType)
                @case('text')
                @case('number')
                @case('email')
                @case('date')
                @case('datetime-local')
                @case('time')
                @case('month')
                @case('week')
                @case('password')
                @case('file')
                @case('file_multiple')
                    <tr data-index="{{array_shift($customFieldIndex)}}">
                        <td>
                            <i class="drag-handle fa fa-grip-vertical"></i>
                        </td>
                        <td class="col-sm-4">
                            <label class="col-form-label" for="{{$fieldName}}" style="font-size: 12px;">{{$fieldName}} <span class="text-danger">{{$fieldmandatory}}</span></label>
                        </td>
                        <td class="col-sm-8">
                            <input type="{{$inputType}}" placeholder="{{$fieldPlaceHolder}}" class="form-control"  style="font-size: 12px;width:100%;" {{$disabled}}>                            
                        </td>
                    </tr>
                    @break
                @case('textarea')
                    <tr data-index="{{array_shift($customFieldIndex)}}">
                        <td>
                            <i class="drag-handle fa fa-grip-vertical"></i>
                        </td>
                        <td class="col-sm-4">
                            <label class="col-form-label" for="{{$fieldName}}" style="font-size: 12px;">{{$fieldName}} <span class="text-danger">{{$fieldmandatory}}</span></label>
                        </td>
                        <td class="col-sm-8">
                            <textarea placeholder="{{$fieldPlaceHolder}}" class="form-control" style="font-size: 12px;width:100%;" {{$disabled}}></textarea>
                        </td>
                    </tr>
                    @break
                @case('select')
                @case('multiselect')
                    <tr data-index="{{array_shift($customFieldIndex)}}">
                        <td>
                            <i class="drag-handle fa fa-grip-vertical"></i>
                        </td>
                        <td class="col-sm-4">
                            <label class="col-form-label" for="{{$fieldName}}" style="font-size: 12px;">{{$fieldName}} <span class="text-danger">{{$fieldmandatory}}</span></label>
                        </td>
                        <td class="col-sm-8">
                            <select class="form-control {{$selectClass}}" style="font-size: 12px;width:100%;" {{$disabled}} data-placeholder="{{$fieldPlaceHolder??'Choose an option'}}" {{$multiple}}>
                                @if($disabled!='')
                                    <option selected>{{$fieldPlaceHolder??'Choose an option'}}</option>
                                @endif
                                <option value=""></option>
                                @foreach($options as $option)
                                    <option>{{$option}}</option>
                                @endforeach
                            </select>                            
                        </td>
                    </tr>
                    @break
                @case('checkbox')
                    <tr data-index="{{array_shift($customFieldIndex)}}">
                        <td>
                            <i class="drag-handle fa fa-grip-vertical"></i>
                        </td>
                        <td class="col-sm-4">
                            <label class="col-form-label" style="font-size: 12px;">
                                {{$fieldName}} <span class="text-danger">{{$fieldmandatory}}</span>
                            </label>
                        </td>
                        <td class="col-sm-8">
                            @if(count($options) > 0)
                                @foreach($options as $key => $option)
                                    <div class="form-check" style="display: inline-block; margin-right: 10px;">
                                        <input 
                                            class="form-check-input" 
                                            type="checkbox" 
                                            {{$disabled}}
                                        >
                                        <label class="form-check-label" style="font-size: 12px;">
                                            {{$option}}
                                        </label>
                                    </div>
                                @endforeach
                            @endif                            
                        </td>
                    </tr>
                    @break
                @case('radio')
                    <tr data-index="{{array_shift($customFieldIndex)}}">
                        <td>
                            <i class="drag-handle fa fa-grip-vertical"></i>
                        </td>
                        <td class="col-sm-4">
                            <label class="col-form-label" style="font-size: 12px;">
                                {{$fieldName}} <span class="text-danger">{{$fieldmandatory}}</span>
                            </label>
                        </td>
                        <td class="col-sm-8">
                            @if(count($options) > 0)
                                @foreach($options as $key => $option)
                                    <div class="form-check" style="display: inline-block; margin-right: 10px;">
                                        <input 
                                            class="form-check-input" 
                                            type="radio" 
                                            {{$disabled}}
                                        >
                                        <label class="form-check-label" style="font-size: 13px;">
                                            {{$option}}
                                        </label>
                                    </div>
                                @endforeach
                            @endif                            
                        </td>
                    </tr>
                    @break
            @endswitch
        @endforeach
    @else
        @foreach($customFields as $index => $customField)
            @php 
                if($customField->id==$field_id && $is_edit_form){
                    $options = $newoptions;
                    $fieldmandatory = $mandatory==0 ? '' : '*';
                    $fieldName = $fieldname;
                    $fieldPlaceHolder = $fieldplaceholder;
                }else{
                    $fieldName = $customField->field_name;
                    $fieldPlaceHolder = $customField->field_placeholder;
                    $fieldmandatory = $customField->mandatory==0 ? '' : '*';
                }
            @endphp
            <tr data-index="{{array_shift($customFieldIndex)}}">
                <td>
                    <i class="drag-handle fa fa-grip-vertical"></i>
                </td>
                <th style="width: 150px;font-size:12px;">{{ $fieldName }} <span class="text-danger">{{$fieldmandatory}}<spna></th>
                <td style="font-size:12px;">
                    {{ $fieldPlaceHolder??'xyz' }}
                </td>
            </tr>
        @endforeach
    @endif
@elseif($pageDetail->structure=='col-2')
    @foreach($customFields as $customField)
        @php 
            if($customField->id==$field_id && $is_edit_form){
                $options = $newoptions;
                $disabled = '';
                $fieldmandatory = $mandatory==0 ? '' : '*';
                $selectClass = $fieldtype=='select' || $fieldtype=='multiselect' ? 'custom_field_select' : '';
                $fieldType = $fieldtype;
                $fieldName = $fieldname;
                $fieldPlaceHolder = $fieldplaceholder;
                $inputType = $fieldtype=='file_multiple' ? 'file' : $fieldtype;
                $multiple = $fieldtype=='multiselect' ? 'multiple' : '';
            }else{
                $options = App\DropdownOption::where('is_deleted',false)->where('custom_field_id',$customField->id)->pluck('option','id')->toArray();
                $disabled = 'disabled';
                $fieldName = $customField->field_name;
                $fieldPlaceHolder = $customField->field_placeholder;
                $fieldType = $customField->field_type;
                $inputType = $customField->field_type=='file_multiple' ? 'file' : $customField->field_type;
                $fieldmandatory = $customField->mandatory==0 ? '' : '*';
                $selectClass = '';
                $multiple = '';
            }
        @endphp
        @switch($fieldType)
            @case('text')
            @case('number')
            @case('email')
            @case('date')
            @case('datetime-local')
            @case('time')
            @case('month')
            @case('week')
            @case('password')
            @case('file')
            @case('file_multiple')
                <div class="form-group col-sm-6" data-index="{{array_shift($customFieldIndex)}}">
                    <i class="drag-handle fa fa-grip-vertical"></i>
                    <label class="col-sm-9 col-form-label pl-1" for="{{$fieldName}}" style="font-size: 12px;">{{$fieldName}} <span class="text-danger">{{$fieldmandatory}}</span></label>
                    <div class="col-sm-12">
                        <input type="{{$inputType}}" placeholder="{{$fieldPlaceHolder}}" class="form-control" style="font-size: 12px;width:100%;" {{$disabled}}>
                    </div>
                </div>
                @break
            @case('textarea')
                <div class="form-group col-sm-12" data-index="{{array_shift($customFieldIndex)}}">
                    <i class="drag-handle fa fa-grip-vertical"></i>
                    <label class="col-sm-9 col-form-label pl-1" for="{{$fieldName}}" style="font-size: 12px;">{{$fieldName}} <span class="text-danger">{{$fieldmandatory}}</span></label>
                    <div class="col-sm-12">
                        <textarea placeholder="{{$fieldPlaceHolder}}" class="form-control" style="font-size: 12px;width:100%;" {{$disabled}}></textarea>
                    </div>
                </div>
                @break
            @case('select')
            @case('multiselect')
                <div class="form-group col-sm-6" data-index="{{array_shift($customFieldIndex)}}">
                    <i class="drag-handle fa fa-grip-vertical"></i>
                    <label class="col-sm-9 col-form-label pl-1" for="{{$fieldName}}" style="font-size: 12px;">{{$fieldName}} <span class="text-danger">{{$fieldmandatory}}</span></label>
                    <div class="col-sm-12">
                        <select class="form-control {{$selectClass}}" style="font-size: 12px;width:100%;" {{$disabled}} data-placeholder="{{$fieldPlaceHolder??'Choose an option'}}" {{$multiple}}>
                            @if($disabled!='')
                                <option selected>{{$fieldPlaceHolder??'Choose an option'}}</option>
                            @endif
                            <option value=""></option>
                            @foreach($options as $option)
                                <option>{{$option}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @break
            @case('checkbox')
                <div class="form-group col-sm-6" data-index="{{array_shift($customFieldIndex)}}">
                    <i class="drag-handle fa fa-grip-vertical"></i>
                    <label class="col-sm-9 col-form-label pl-1"  style="font-size: 12px;">
                        {{$fieldName}} <span class="text-danger">{{$fieldmandatory}}</span>
                    </label>
                    <div class="col-sm-12" style="font-size: 13px;">
                        @if(count($options) > 0)
                            @foreach($options as $key => $option)
                                <div class="form-check" style="display: inline-block; margin-right: 10px;">
                                    <input class="form-check-input" type="checkbox" {{$disabled}}>
                                    <label class="form-check-label">
                                        {{$option}}
                                    </label>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                @break
            @case('radio')
                <div class="form-group col-sm-6" data-index="{{array_shift($customFieldIndex)}}">
                    <i class="drag-handle fa fa-grip-vertical"></i>
                    <label class="col-sm-9 col-form-label pl-1" style="font-size: 12px;">
                        {{$fieldName}} <span class="text-danger">{{$fieldmandatory}}</span>
                    </label>
                    <div class="col-sm-12" style="font-size: 13px;">
                        @if(count($options) > 0)
                            @foreach($options as $key => $option)
                                <div class="form-check" style="display: inline-block; margin-right: 10px;">
                                    <input class="form-check-input" type="radio" {{$disabled}}>
                                    <label class="form-check-label">
                                        {{$option}}
                                    </label>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                @break
        @endswitch
    @endforeach
@elseif($pageDetail->structure=='col-3')
    @foreach($customFields as $customField)
        @php 
            if($customField->id==$field_id && $is_edit_form){
                $options = $newoptions;
                $disabled = '';
                $fieldmandatory = $mandatory==0 ? '' : '*';
                $selectClass = $fieldtype=='select' || $fieldtype=='multiselect' ? 'custom_field_select' : '';
                $fieldType = $fieldtype;
                $fieldName = $fieldname;
                $fieldPlaceHolder = $fieldplaceholder;
                $inputType = $fieldtype=='file_multiple' ? 'file' : $fieldtype;
                $multiple = $fieldtype=='multiselect' ? 'multiple' : '';
            }else{
                $options = App\DropdownOption::where('is_deleted',false)->where('custom_field_id',$customField->id)->pluck('option','id')->toArray();
                $disabled = 'disabled';
                $fieldName = $customField->field_name;
                $fieldPlaceHolder = $customField->field_placeholder;
                $fieldType = $customField->field_type;
                $inputType = $customField->field_type=='file_multiple' ? 'file' : $customField->field_type;
                $fieldmandatory = $customField->mandatory==0 ? '' : '*';
                $selectClass = '';
                $multiple = '';
            }
        @endphp
        @switch($fieldType)
            @case('text')
            @case('number')
            @case('email')
            @case('date')
            @case('datetime-local')
            @case('time')
            @case('month')
            @case('week')
            @case('password')
            @case('file')
            @case('file_multiple')
                <div class="form-group col-sm-4" data-index="{{array_shift($customFieldIndex)}}">
                    <i class="drag-handle fa fa-grip-vertical"></i>
                    <label class="col-sm-9 col-form-label pl-1" for="{{$fieldName}}" style="font-size: 12px;">{{$fieldName}} <span class="text-danger">{{$fieldmandatory}}</span></label>
                    <div class="col-sm-12">
                        <input type="{{$inputType}}" placeholder="{{$fieldPlaceHolder}}" class="form-control" style="font-size: 12px;width:100%;" {{$disabled}}>
                    </div>
                </div>
                @break
            @case('textarea')
                <div class="form-group col-sm-8" data-index="{{array_shift($customFieldIndex)}}">
                    <i class="drag-handle fa fa-grip-vertical"></i>
                    <label class="col-sm-9 col-form-label pl-1" for="{{$fieldName}}" style="font-size: 12px;">{{$fieldName}} <span class="text-danger">{{$fieldmandatory}}</span></label>
                    <div class="col-sm-12">
                        <textarea placeholder="{{$fieldPlaceHolder}}" class="form-control" style="font-size: 12px;width:100%;" {{$disabled}}></textarea>
                    </div>
                </div>
                @break
            @case('select')
            @case('multiselect')
                <div class="form-group col-sm-4" data-index="{{array_shift($customFieldIndex)}}">
                    <i class="drag-handle fa fa-grip-vertical"></i>
                    <label class="col-sm-9 col-form-label pl-1" for="{{$fieldName}}" style="font-size: 12px;">{{$fieldName}} <span class="text-danger">{{$fieldmandatory}}</span></label>
                    <div class="col-sm-12">
                         <select class="form-control {{$selectClass}}" style="font-size: 12px;width:100%;" {{$disabled}} data-placeholder="{{$fieldPlaceHolder??'Choose an option'}}" {{$multiple}}>
                            @if($disabled!='')
                                <option selected>{{$fieldPlaceHolder??'Choose an option'}}</option>
                            @endif
                            <option value=""></option>
                            @foreach($options as $option)
                                <option>{{$option}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @break
            @case('checkbox')
                <div class="form-group col-sm-4" data-index="{{array_shift($customFieldIndex)}}">
                    <i class="drag-handle fa fa-grip-vertical"></i>
                    <label class="col-sm-9 col-form-label pl-1"  style="font-size: 12px;">
                        {{$fieldName}} <span class="text-danger">{{$fieldmandatory}}</span>
                    </label>
                    <div class="col-sm-12" style="font-size: 13px;">
                        @if(count($options) > 0)
                            @foreach($options as $key => $option)
                                <div class="form-check" style="display: inline-block; margin-right: 10px;">
                                    <input class="form-check-input" type="checkbox" {{$disabled}}>
                                    <label class="form-check-label">
                                        {{$option}}
                                    </label>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                @break
            @case('radio')
                <div class="form-group col-sm-4" data-index="{{array_shift($customFieldIndex)}}">
                    <i class="drag-handle fa fa-grip-vertical"></i>
                    <label class="col-sm-9 col-form-label pl-1" style="font-size: 12px;">
                        {{$fieldName}} <span class="text-danger">{{$fieldmandatory}}</span>
                    </label>
                    <div class="col-sm-12" style="font-size: 13px;">
                        @if(count($options) > 0)
                            @foreach($options as $key => $option)
                                <div class="form-check" style="display: inline-block; margin-right: 10px;">
                                    <input class="form-check-input" type="radio" {{$disabled}}>
                                    <label class="form-check-label">
                                        {{$option}}
                                    </label>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                @break
        @endswitch
    @endforeach
@elseif($pageDetail->structure=='col-4')
    @foreach($customFields as $customField)
        @php 
            if($customField->id==$field_id && $is_edit_form){
                $options = $newoptions;
                $disabled = '';
                $fieldmandatory = $mandatory==0 ? '' : '*';
                $selectClass = $fieldtype=='select' || $fieldtype=='multiselect' ? 'custom_field_select' : '';
                $fieldType = $fieldtype;
                $fieldName = $fieldname;
                $fieldPlaceHolder = $fieldplaceholder;
                $inputType = $fieldtype=='file_multiple' ? 'file' : $fieldtype;
                $multiple = $fieldtype=='multiselect' ? 'multiple' : '';
            }else{
                $options = App\DropdownOption::where('is_deleted',false)->where('custom_field_id',$customField->id)->pluck('option','id')->toArray();
                $disabled = 'disabled';
                $fieldName = $customField->field_name;
                $fieldPlaceHolder = $customField->field_placeholder;
                $fieldType = $customField->field_type;
                $inputType = $customField->field_type=='file_multiple' ? 'file' : $customField->field_type;
                $fieldmandatory = $customField->mandatory==0 ? '' : '*';
                $selectClass = '';
                $multiple = '';
            }
        @endphp
        @switch($fieldType)
            @case('text')
            @case('number')
            @case('email')
            @case('date')
            @case('datetime-local')
            @case('time')
            @case('month')
            @case('week')
            @case('password')
            @case('file')
            @case('file_multiple')
                <div class="form-group col-sm-3 p-0" data-index="{{array_shift($customFieldIndex)}}">
                    <i class="drag-handle fa fa-grip-vertical"></i>
                    <label class="col-sm-9 col-form-label pl-1" for="{{$fieldName}}" style="font-size: 12px;">{{$fieldName}} <span class="text-danger">{{$fieldmandatory}}</span></label>
                    <div class="col-sm-12">
                        <input type="{{$inputType}}" placeholder="{{$fieldPlaceHolder}}" class="form-control" style="font-size: 12px;width:100%;" {{$disabled}}>
                    </div>
                </div>
                @break
            @case('textarea')
                <div class="form-group col-sm-6 p-0" data-index="{{array_shift($customFieldIndex)}}">
                    <i class="drag-handle fa fa-grip-vertical"></i>
                    <label class="col-sm-9 col-form-label pl-1" for="{{$fieldName}}" style="font-size: 12px;">{{$fieldName}} <span class="text-danger">{{$fieldmandatory}}</span></label>
                    <div class="col-sm-12">
                        <textarea placeholder="{{$fieldPlaceHolder}}" class="form-control" style="font-size: 12px;width:100%;" {{$disabled}}></textarea>
                    </div>
                </div>
                @break
            @case('select')
            @case('multiselect')
                <div class="form-group col-sm-3 p-0" data-index="{{array_shift($customFieldIndex)}}">
                    <i class="drag-handle fa fa-grip-vertical"></i>
                    <label class="col-sm-9 col-form-label pl-1" for="{{$fieldName}}" style="font-size: 12px;">{{$fieldName}} <span class="text-danger">{{$fieldmandatory}}</span></label>
                    <div class="col-sm-12">
                        <select class="form-control {{$selectClass}}" style="font-size: 12px;width:100%;" {{$disabled}} data-placeholder="{{$fieldPlaceHolder??'Choose an option'}}" {{$multiple}}>
                            @if($disabled!='')
                                <option selected>{{$fieldPlaceHolder??'Choose an option'}}</option>
                            @endif
                            <option value=""></option>
                            @foreach($options as $option)
                                <option>{{$option}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @break
            @case('checkbox')
                <div class="form-group col-sm-3 p-0" data-index="{{array_shift($customFieldIndex)}}">
                    <i class="drag-handle fa fa-grip-vertical"></i>
                    <label class="col-sm-9 col-form-label pl-1"  style="font-size: 12px;">
                        {{$fieldName}} <span class="text-danger">{{$fieldmandatory}}</span>
                    </label>
                    <div class="col-sm-12" style="font-size: 13px;">
                        @if(count($options) > 0)
                            @foreach($options as $key => $option)
                                <div class="form-check" style="display: inline-block; margin-right: 10px;">
                                    <input class="form-check-input" type="checkbox" {{$disabled}}>
                                    <label class="form-check-label">
                                        {{$option}}
                                    </label>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                @break
            @case('radio')
                <div class="form-group col-sm-3 p-0" data-index="{{array_shift($customFieldIndex)}}">
                    <i class="drag-handle fa fa-grip-vertical"></i>
                    <label class="col-sm-9 col-form-label pl-1" style="font-size: 12px;">
                        {{$fieldName}} <span class="text-danger">{{$fieldmandatory}}</span>
                    </label>
                    <div class="col-sm-12" style="font-size: 13px;">
                        @if(count($options) > 0)
                            @foreach($options as $key => $option)
                                <div class="form-check" style="display: inline-block; margin-right: 10px;">
                                    <input class="form-check-input" type="radio" {{$disabled}}>
                                    <label class="form-check-label">
                                        {{$option}}
                                    </label>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                @break
        @endswitch
    @endforeach
@endif
<script>
    $('.custom_field_select').select2();
</script>