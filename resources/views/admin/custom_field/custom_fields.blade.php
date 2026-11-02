@if($structure=='table')
    @if(isset(($data)) && $data->count() > 0)
        @foreach($customFields as $customField)
            @php 
                $fieldName = Str::slug($customField->field_name, '_');
                $value = $data->{$fieldName} ?? null;
                if(in_array($customField->field_type, ['checkbox', 'multiselect'])) {
                    $value = is_array($value) ? $value : json_decode($value, true);
                }
                $options = App\DropdownOption::where('is_deleted',false)->where('custom_field_id',$customField->id)->pluck('option','id')->toArray();
            @endphp
            @switch($customField->field_type)
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
                    <tr data-index="{{array_shift($fieldIndex)}}">
                        <td class="col-sm-4">
                            <label class="col-form-label" for="{{$customField->field_name}}" style="font-size: 13px;">{{$customField->field_name}} <span class="text-danger">{{$customField->mandatory==0?'':'*'}}</span></label>
                        </td>
                        <td class="col-sm-8">
                            <input type="{{$customField->field_type=='file_multiple' ? 'file':$customField->field_type}}" name="{{$customField->field_type=='file_multiple' ? $fieldName.'[]':$fieldName}}" id="{{$fieldName}}" placeholder="{{$customField->field_placeholder}}" class="form-control" {{$customField->mandatory==0?'':'required'}} style="font-size: 13px;width:100%;" value="{{$value}}" {{$customField->field_type=='file_multiple' ? 'multiple':''}}>
                        </td>
                    </tr>
                    @break
                @case('textarea')
                    <tr data-index="{{array_shift($fieldIndex)}}">
                        <td class="col-sm-4">
                            <label class="col-form-label" for="{{$customField->field_name}}" style="font-size: 13px;">{{$customField->field_name}} <span class="text-danger">{{$customField->mandatory==0?'':'*'}}</span></label>
                        </td>
                        <td class="col-sm-8">
                            <textarea name="{{$fieldName}}" id="{{$fieldName}}" placeholder="{{$customField->field_placeholder}}" class="form-control" {{$customField->mandatory==0?'':'required'}} style="font-size: 13px;width:100%;">{{$value}}</textarea>                            
                        </td>
                    </tr>
                    @break
                @case('select')
                    <tr data-index="{{array_shift($fieldIndex)}}">
                        <td class="col-sm-4">
                            <label class="col-form-label" for="{{$customField->field_name}}" style="font-size: 13px;">{{$customField->field_name}} <span class="text-danger">{{$customField->mandatory==0?'':'*'}}</span></label>
                        </td>
                        <td class="col-sm-8">
                            <select class="form-control js-select2 custom_field_select" name="{{$fieldName}}" id="{{$fieldName}}" {{$customField->mandatory==0?'':'required'}} style="font-size: 13px;width:100%;" data-placeholder="{{$customField->field_placeholder?? 'Choose an option'}}">
                                @if($customField->field_placeholder==null)
                                <option value="" selected disabled>Choose a option</option>
                                @endif
                                @if($customField->field_placeholder)
                                <option value="" selected disabled>{{$customField->field_placeholder}}</option>
                                @endif
                                @if(count($options)>0)
                                @foreach($options as $key => $option)
                                    <option value="{{$key}}" {{$value==$key ? 'selected' : ''}}>{{$option}}</option>
                                @endforeach
                                @endif
                            </select>                            
                        </td>
                    </tr>
                    @break
                @case('multiselect')
                    <tr data-index="{{array_shift($fieldIndex)}}">
                        <td class="col-sm-4">
                            <label class="col-form-label" for="{{$customField->field_name}}" style="font-size: 13px;">{{$customField->field_name}} <span class="text-danger">{{$customField->mandatory==0?'':'*'}}</span></label>
                        </td>
                        <td class="col-sm-8">
                            <select class="form-control js-select2 custom_field_select" name="{{$fieldName.'[]'}}" id="{{$fieldName}}" {{$customField->mandatory==0?'':'required'}} multiple style="font-size: 13px;width:100%;" data-placeholder="{{$customField->field_placeholder?? 'Choose an option'}}">
                                @if(count($options)>0)
                                @foreach($options as $key => $option)
                                    <option value="{{$key}}" {{in_array($key, $value??[]) ? 'selected' : ''}}>{{$option}}</option>
                                @endforeach
                                @endif
                            </select>                            
                        </td>
                    </tr>
                    @break
                @case('checkbox')
                    <tr data-index="{{array_shift($fieldIndex)}}">
                        <td class="col-sm-4">
                            <label class="col-form-label" style="font-size: 13px;">
                                {{$customField->field_name}} <span class="text-danger">{{$customField->mandatory==0?'':'*'}}</span>
                            </label>
                        </td>
                        <td class="col-sm-8">
                            @if(count($options) > 0)
                                @foreach($options as $key => $option)
                                    @php
                                        $isChecked = is_array($value) ? in_array($key, $value??[]) : ($value == $key);
                                    @endphp
                                    <div class="form-check" style="display: inline-block; margin-right: 10px;">
                                        <input 
                                            class="form-check-input" 
                                            type="checkbox" 
                                            name="{{$fieldName}}[]" 
                                            id="{{$fieldName}}_{{Str::slug($option, '_')}}" 
                                            value="{{$key}}" 
                                            {{$isChecked ? 'checked' : ''}}
                                            {{$customField->mandatory==0?'':'required'}}
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
                @case('radio')
                    <tr data-index="{{array_shift($fieldIndex)}}">
                        <td class="col-sm-4">
                            <label class="col-form-label" style="font-size: 13px;">
                                {{$customField->field_name}} <span class="text-danger">{{$customField->mandatory==0?'':'*'}}</span>
                            </label>
                        </td>
                        <td class="col-sm-8">
                            @if(count($options) > 0)
                                @foreach($options as $key => $option)
                                    @php
                                        $isChecked = $value == $key;
                                    @endphp
                                    <div class="form-check" style="display: inline-block; margin-right: 10px;">
                                        <input 
                                            class="form-check-input" 
                                            type="radio" 
                                            name="{{$fieldName}}" 
                                            id="{{$fieldName}}_{{Str::slug($option, '_')}}" 
                                            value="{{$key}}" 
                                            {{$isChecked ? 'checked' : ''}}
                                            {{$customField->mandatory==0?'':'required'}}
                                        >
                                        <label class="form-check-label" style="font-size: 13px;" for="{{$fieldName}}_{{Str::slug($option, '_')}}">
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
        @foreach($customFields as $customField)
            @php 
                $fieldName = Str::slug($customField->field_name, '_');
                $options = App\DropdownOption::where('is_deleted',false)->where('custom_field_id',$customField->id)->pluck('option','id')->toArray();
            @endphp
            @switch($customField->field_type)
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
                    <tr data-index="{{array_shift($fieldIndex)}}">
                        <td class="col-sm-4">
                            <label class="col-form-label" for="{{$customField->field_name}}" style="font-size: 13px;">{{$customField->field_name}} <span class="text-danger">{{$customField->mandatory==0?'':'*'}}</span></label>
                        </td>
                        <td class="col-sm-8">
                            <input type="{{$customField->field_type=='file_multiple' ? 'file':$customField->field_type}}" name="{{$customField->field_type=='file_multiple' ? $fieldName.'[]':$fieldName}}" id="{{$fieldName}}" placeholder="{{$customField->field_placeholder}}" class="form-control" {{$customField->mandatory==0?'':'required'}} style="font-size: 13px;width:100%;" value="{{old($fieldName)}}" {{$customField->field_type=='file_multiple' ? 'multiple':''}}>                            
                        </td>
                    </tr>
                    @break
                @case('textarea')
                    <tr data-index="{{array_shift($fieldIndex)}}">
                        <td class="col-sm-4">
                            <label class="col-form-label" for="{{$customField->field_name}}" style="font-size: 13px;">{{$customField->field_name}} <span class="text-danger">{{$customField->mandatory==0?'':'*'}}</span></label>
                        </td>
                        <td class="col-sm-8">
                            <textarea name="{{$fieldName}}" id="{{$fieldName}}" placeholder="{{$customField->field_placeholder}}" class="form-control" {{$customField->mandatory==0?'':'required'}} style="font-size: 13px;width:100%;">{{old($fieldName)}}</textarea>
                        </td>
                    </tr>
                    @break
                @case('select')
                @case('multiselect')
                    <tr data-index="{{array_shift($fieldIndex)}}">
                        <td class="col-sm-4">
                            <label class="col-form-label" for="{{$customField->field_name}}" style="font-size: 13px;">{{$customField->field_name}} <span class="text-danger">{{$customField->mandatory==0?'':'*'}}</span></label>
                        </td>
                        <td class="col-sm-8">
                            <select class="form-control js-select2 custom_field_select" name="{{$customField->field_type=='multiselect'?$fieldName.'[]':$fieldName}}" id="{{$fieldName}}" {{$customField->mandatory==0?'':'required'}} {{$customField->field_type=='multiselect'?'multiple':''}} style="font-size: 13px;width:100%;" data-placeholder="{{$customField->field_placeholder?? 'Choose an option'}}">
                                @if($customField->field_placeholder==null)
                                <option value="" {{$customField->field_type != 'multiselect' ? 'selected':''}} disabled>Choose a option</option>
                                @endif
                                @if($customField->field_placeholder && $customField->field_type!='multiselect')
                                <option value="" selected disabled>{{$customField->field_placeholder}}</option>
                                @endif
                                
                                @if(count($options)>0)
                                @foreach($options as $key => $option)
                                    <option value="{{$key}}" {{in_array($option, old($fieldName, [])) ? 'selected' : ''}}>{{$option}}</option>
                                @endforeach
                                @endif
                            </select>                            
                        </td>
                    </tr>
                    @break
                @case('checkbox')
                    <tr data-index="{{array_shift($fieldIndex)}}">
                        <td class="col-sm-4">
                            <label class="col-form-label" style="font-size: 13px;">
                                {{$customField->field_name}} <span class="text-danger">{{$customField->mandatory==0?'':'*'}}</span>
                            </label>
                        </td>
                        <td class="col-sm-8">
                            @if(count($options) > 0)
                                @foreach($options as $key => $option)
                                    @php
                                        $isChecked = is_array(old($fieldName)) ? in_array($key, old($fieldName, [])) : (old($fieldName) == $key);
                                    @endphp
                                    <div class="form-check" style="display: inline-block; margin-right: 10px;">
                                        <input 
                                            class="form-check-input" 
                                            type="checkbox" 
                                            name="{{$fieldName}}[]" 
                                            id="{{$fieldName}}_{{Str::slug($option, '_')}}" 
                                            value="{{$key}}" 
                                            {{$isChecked ? 'checked' : ''}}
                                            {{$customField->mandatory==0?'':'required'}}
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
                @case('radio')
                    <tr data-index="{{array_shift($fieldIndex)}}">
                        <td class="col-sm-4">
                            <label class="col-form-label" style="font-size: 13px;">
                                {{$customField->field_name}} <span class="text-danger">{{$customField->mandatory==0?'':'*'}}</span>
                            </label>
                        </td>
                        <td class="col-sm-8">
                            @if(count($options) > 0)
                                @foreach($options as $key => $option)
                                    @php
                                        $isChecked = old($fieldName) == $key;
                                    @endphp
                                    <div class="form-check" style="display: inline-block; margin-right: 10px;">
                                        <input 
                                            class="form-check-input" 
                                            type="radio" 
                                            name="{{$fieldName}}" 
                                            id="{{$fieldName}}_{{Str::slug($option, '_')}}" 
                                            value="{{$key}}" 
                                            {{$isChecked ? 'checked' : ''}}
                                            {{$customField->mandatory==0?'':'required'}}
                                        >
                                        <label class="form-check-label" style="font-size: 13px;" for="{{$fieldName}}_{{Str::slug($option, '_')}}">
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
    @endif
@else
    @if(isset(($data)) && $data->count() > 0)
        @foreach($customFields as $customField)
            @php 
                $fieldName = Str::slug($customField->field_name, '_');
                $value = $data->{$fieldName} ?? null;
                if(in_array($customField->field_type, ['checkbox', 'multiselect'])) {
                    $value = is_array($value) ? $value : json_decode($value, true);
                }
                $options = App\DropdownOption::where('is_deleted',false)->where('custom_field_id',$customField->id)->pluck('option','id')->toArray();
            @endphp
            @switch($customField->field_type)
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
                    <div class="form-group col-sm-6" data-index="{{array_shift($fieldIndex)}}">
                        <label class="col-sm-12 col-form-label" for="{{$customField->field_name}}" style="font-size: 13px;">{{$customField->field_name}} <span class="text-danger">{{$customField->mandatory==0?'':'*'}}</span></label>
                        <div class="col-sm-12">
                            <input type="{{$customField->field_type=='file_multiple' ? 'file':$customField->field_type}}" name="{{$customField->field_type=='file_multiple' ? $fieldName.'[]':$fieldName}}" id="{{$fieldName}}" placeholder="{{$customField->field_placeholder}}" class="form-control" {{$customField->mandatory==0?'':'required'}} style="font-size: 13px;width:100%;" value="{{$value}}" {{$customField->field_type=='file_multiple' ? 'multiple':''}}>
                        </div>
                    </div>
                    @break
                @case('textarea')
                    <div class="form-group col-sm-12" data-index="{{array_shift($fieldIndex)}}">
                        <label class="col-sm-12 col-form-label" for="{{$customField->field_name}}" style="font-size: 13px;">{{$customField->field_name}} <span class="text-danger">{{$customField->mandatory==0?'':'*'}}</span></label>
                        <div class="col-sm-12">
                            <textarea name="{{$fieldName}}" id="{{$fieldName}}" placeholder="{{$customField->field_placeholder}}" class="form-control" {{$customField->mandatory==0?'':'required'}} style="font-size: 13px;width:100%;">{{$value}}</textarea>
                        </div>
                    </div>
                    @break
                @case('select')
                    <div class="form-group col-sm-6">
                        <label class="col-sm-12 col-form-label" for="{{$customField->field_name}}" style="font-size: 13px;">{{$customField->field_name}} <span class="text-danger">{{$customField->mandatory==0?'':'*'}}</span></label>
                        <div class="col-sm-12">
                            <select class="form-control js-select2 custom_field_select" name="{{$fieldName}}" id="{{$fieldName}}" {{$customField->mandatory==0?'':'required'}} style="font-size: 13px;width:100%;" data-placeholder="{{$customField->field_placeholder?? 'Choose an option'}}">
                                @if($customField->field_placeholder==null)
                                <option value="" selected disabled>Choose a option</option>
                                @endif
                                @if($customField->field_placeholder)
                                <option value="" selected disabled>{{$customField->field_placeholder}}</option>
                                @endif
                                @if(count($options)>0)
                                @foreach($options as $key => $option)
                                    <option value="{{$key}}" {{$key==$value ? 'selected' : ''}}>{{$option}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    @break
                @case('multiselect')
                    <div class="form-group col-sm-6" data-index="{{array_shift($fieldIndex)}}">
                        <label class="col-sm-12 col-form-label" for="{{$customField->field_name}}" style="font-size: 13px;">{{$customField->field_name}} <span class="text-danger">{{$customField->mandatory==0?'':'*'}}</span></label>
                        <div class="col-sm-12">
                            <select class="form-control js-select2 custom_field_select" name="{{$fieldName.'[]'}}" id="{{$fieldName}}" {{$customField->mandatory==0?'':'required'}} multiple style="font-size: 13px;width:100%;" data-placeholder="{{$customField->field_placeholder?? 'Choose an option'}}">
                                @if(count($options)>0)
                                @foreach($options as $key => $option)
                                    <option value="{{$key}}" {{in_array($key, $value??[]) ? 'selected' : ''}}>{{$option}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    @break
                @case('checkbox')
                    <div class="form-group col-sm-6" data-index="{{array_shift($fieldIndex)}}">
                        <label class="col-sm-12 col-form-label" style="font-size: 13px;">
                            {{$customField->field_name}} <span class="text-danger">{{$customField->mandatory==0?'':'*'}}</span>
                        </label>
                        <div class="col-sm-12" style="font-size: 13px;">
                            @if(count($options) > 0)
                                @foreach($options as $key => $option)
                                    @php
                                        $isChecked = is_array($value) ? in_array($key, $value??[]) : ($value == $key);
                                    @endphp
                                    <div class="form-check" style="display: inline-block; margin-right: 10px;">
                                        <input 
                                            class="form-check-input" 
                                            type="checkbox" 
                                            name="{{$fieldName}}[]" 
                                            id="{{$fieldName}}_{{Str::slug($option, '_')}}" 
                                            value="{{$key}}" 
                                            {{$isChecked ? 'checked' : ''}}
                                            {{$customField->mandatory==0?'':'required'}}
                                        >
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
                    <div class="form-group col-sm-6" data-index="{{array_shift($fieldIndex)}}">
                        <label class="col-sm-12 col-form-label" style="font-size: 13px;">
                            {{$customField->field_name}} <span class="text-danger">{{$customField->mandatory==0?'':'*'}}</span>
                        </label>
                        <div class="col-sm-12" style="font-size: 13px;">
                            @if(count($options) > 0)
                                @foreach($options as $key =>$option)
                                    @php
                                        $isChecked = $value == $key;
                                    @endphp
                                    <div class="form-check" style="display: inline-block; margin-right: 10px;">
                                        <input 
                                            class="form-check-input" 
                                            type="radio" 
                                            name="{{$fieldName}}" 
                                            id="{{$fieldName}}_{{Str::slug($option, '_')}}" 
                                            value="{{$key}}" 
                                            {{$isChecked ? 'checked' : ''}}
                                            {{$customField->mandatory==0?'':'required'}}
                                        >
                                        <label class="form-check-label" for="{{$fieldName}}_{{Str::slug($option, '_')}}">
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
    @else
        @foreach($customFields as $customField)
            @php 
                $fieldName = Str::slug($customField->field_name, '_');
                $options = App\DropdownOption::where('is_deleted',false)->where('custom_field_id',$customField->id)->pluck('option','id')->toArray();
            @endphp
            @switch($customField->field_type)
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
                    <div class="form-group col-sm-6" data-index="{{array_shift($fieldIndex)}}">
                        <label class="col-sm-12 col-form-label" for="{{$customField->field_name}}" style="font-size: 13px;">{{$customField->field_name}} <span class="text-danger">{{$customField->mandatory==0?'':'*'}}</span></label>
                        <div class="col-sm-12">
                            <input type="{{$customField->field_type=='file_multiple' ? 'file':$customField->field_type}}" name="{{$customField->field_type=='file_multiple' ? $fieldName.'[]':$fieldName}}" id="{{$fieldName}}" placeholder="{{$customField->field_placeholder}}" class="form-control" {{$customField->mandatory==0?'':'required'}} style="font-size: 13px;width:100%;" value="{{old($fieldName)}}" {{$customField->field_type=='file_multiple' ? 'multiple':''}}>
                        </div>
                    </div>
                    @break
                @case('textarea')
                    <div class="form-group col-sm-12" data-index="{{array_shift($fieldIndex)}}">
                        <label class="col-sm-12 col-form-label" for="{{$customField->field_name}}" style="font-size: 13px;">{{$customField->field_name}} <span class="text-danger">{{$customField->mandatory==0?'':'*'}}</span></label>
                        <div class="col-sm-12">
                            <textarea name="{{$fieldName}}" id="{{$fieldName}}" placeholder="{{$customField->field_placeholder}}" class="form-control" {{$customField->mandatory==0?'':'required'}} style="font-size: 13px;width:100%;">{{old($fieldName)}}</textarea>
                        </div>
                    </div>
                    @break
                @case('select')
                @case('multiselect')
                    <div class="form-group col-sm-6" data-index="{{array_shift($fieldIndex)}}">
                        <label class="col-sm-12 col-form-label" for="{{$customField->field_name}}" style="font-size: 13px;">{{$customField->field_name}} <span class="text-danger">{{$customField->mandatory==0?'':'*'}}</span></label>
                        <div class="col-sm-12">
                            <select class="form-control js-select2 custom_field_select" name="{{$customField->field_type=='multiselect'?$fieldName.'[]':$fieldName}}" id="{{$fieldName}}" {{$customField->mandatory==0?'':'required'}} {{$customField->field_type=='multiselect'?'multiple':''}} style="font-size: 13px;width:100%;" data-placeholder="{{$customField->field_placeholder?? 'Choose an option'}}">
                                @if($customField->field_placeholder==null)
                                <option value="" {{$customField->field_type != 'multiselect' ? 'selected':''}} disabled>Choose a option</option>
                                @endif
                                @if($customField->field_placeholder && $customField->field_type!='multiselect')
                                <option value="" selected disabled>{{$customField->field_placeholder}}</option>
                                @endif
                                
                                @if(count($options)>0)
                                @foreach($options as $key => $option)
                                    <option value="{{$key}}" {{is_array(old($fieldName)) ? (in_array($key, old($fieldName, [])) ? 'selected' : '') : ($key==old($fieldName)?'selected':'')}}>{{$option}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    @break
                @case('checkbox')
                    <div class="form-group col-sm-6" data-index="{{array_shift($fieldIndex)}}">
                        <label class="col-sm-12 col-form-label" style="font-size: 13px;">
                            {{$customField->field_name}} <span class="text-danger">{{$customField->mandatory==0?'':'*'}}</span>
                        </label>
                        <div class="col-sm-12" style="font-size: 13px;">
                            @if(count($options) > 0)
                                @foreach($options as $key => $option)
                                    @php
                                        $isChecked = is_array(old($fieldName)) ? in_array($key, old($fieldName, [])) : (old($fieldName) == $key);
                                    @endphp
                                    <div class="form-check" style="display: inline-block; margin-right: 10px;">
                                        <input 
                                            class="form-check-input" 
                                            type="checkbox" 
                                            name="{{$fieldName}}[]" 
                                            id="{{$fieldName}}_{{Str::slug($option, '_')}}" 
                                            value="{{$key}}" 
                                            {{$isChecked ? 'checked' : ''}}
                                            {{$customField->mandatory==0?'':'required'}}
                                        >
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
                    <div class="form-group col-sm-6" data-index="{{array_shift($fieldIndex)}}">
                        <label class="col-sm-12 col-form-label" style="font-size: 13px;">
                            {{$customField->field_name}} <span class="text-danger">{{$customField->mandatory==0?'':'*'}}</span>
                        </label>
                        <div class="col-sm-12" style="font-size: 13px;">
                            @if(count($options) > 0)
                                @foreach($options as $key => $option)
                                    @php
                                        $isChecked = old($fieldName) == $key;
                                    @endphp
                                    <div class="form-check" style="display: inline-block; margin-right: 10px;">
                                        <input 
                                            class="form-check-input" 
                                            type="radio" 
                                            name="{{$fieldName}}" 
                                            id="{{$fieldName}}_{{Str::slug($option, '_')}}" 
                                            value="{{$key}}" 
                                            {{$isChecked ? 'checked' : ''}}
                                            {{$customField->mandatory==0?'':'required'}}
                                        >
                                        <label class="form-check-label" for="{{$fieldName}}_{{Str::slug($option, '_')}}">
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
@endif
@if(!(request()->is('user/calibration/editins')))
    <script>
        $('.custom_field_select').select2();
    </script>
@endif