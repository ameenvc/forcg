@if($pageDetail->structure=='table-col-2')
    @if($pageDetail->is_target_page)
        @switch($fieldtype)
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
                <tr data-index="{{$totalCount+1}}">
                    <td>
                        <i class="drag-handle fa fa-grip-vertical"></i>
                    </td>
                    <td class="col-sm-4">
                        <label class="col-form-label" for="{{$fieldname}}" style="font-size: 12px;">{{$fieldname}} <span class="text-danger">{{$mandatory==0?'':'*'}}</span></label>
                    </td>
                    <td class="col-sm-8">
                        <input type="{{$fieldtype=='file_multiple' ? 'file':$fieldtype}}" placeholder="{{$fieldplaceholder}}" class="form-control"  style="font-size: 12px;width:100%;">                            
                    </td>
                </tr>
                @break
            @case('textarea')
                <tr data-index="{{$totalCount+1}}">
                    <td>
                        <i class="drag-handle fa fa-grip-vertical"></i>
                    </td>
                    <td class="col-sm-4">
                        <label class="col-form-label" for="{{$fieldname}}" style="font-size: 12px;">{{$fieldname}} <span class="text-danger">{{$mandatory==0?'':'*'}}</span></label>
                    </td>
                    <td class="col-sm-8">
                        <textarea placeholder="{{$fieldplaceholder}}" class="form-control" style="font-size: 12px;width:100%;"></textarea>
                    </td>
                </tr>
                @break
            @case('select')
            @case('multiselect')
                <tr data-index="{{$totalCount+1}}">
                    <td>
                        <i class="drag-handle fa fa-grip-vertical"></i>
                    </td>
                    <td class="col-sm-4">
                        <label class="col-form-label" for="{{$fieldname}}" style="font-size: 12px;">{{$fieldname}} <span class="text-danger">{{$mandatory==0?'':'*'}}</span></label>
                    </td>
                    <td class="col-sm-8">
                        <select class="form-control js-select2 custom_field_select" {{$fieldtype=='multiselect'?'multiple':''}} style="font-size: 13px;width:100%;" data-placeholder="{{$fieldplaceholder??'Choose an option'}}">
                            @if($fieldtype!='multiselect')
                            <option value=""></option>
                            @endif
                            
                            @if(count($options)>0)
                            @foreach($options as $option)
                                <option>{{$option}}</option>
                            @endforeach
                            @endif
                        </select>                            
                    </td>
                </tr>
                @break
            @case('checkbox')
                <tr data-index="{{$totalCount+1}}">
                    <td>
                        <i class="drag-handle fa fa-grip-vertical"></i>
                    </td>
                    <td class="col-sm-4">
                        <label class="col-form-label" style="font-size: 12px;">
                            {{$fieldname}} <span class="text-danger">{{$mandatory==0?'':'*'}}</span>
                        </label>
                    </td>
                    <td class="col-sm-8">
                        @if(count($options) > 0)
                            @foreach($options as $key => $option)
                                <div class="form-check" style="display: inline-block; margin-right: 10px;">
                                    <input 
                                        class="form-check-input" 
                                        type="checkbox" 
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
                <tr data-index="{{$totalCount+1}}">
                    <td>
                        <i class="drag-handle fa fa-grip-vertical"></i>
                    </td>
                    <td class="col-sm-4">
                        <label class="col-form-label" style="font-size: 12px;">
                            {{$fieldname}} <span class="text-danger">{{$mandatory==0?'':'*'}}</span>
                        </label>
                    </td>
                    <td class="col-sm-8">
                        @if(count($options) > 0)
                            @foreach($options as $key => $option)
                                <div class="form-check" style="display: inline-block; margin-right: 10px;">
                                    <input 
                                        class="form-check-input" 
                                        type="radio" 
                                    >
                                    <label class="form-check-label" style="font-size: 13px;" for="{{$fieldname}}_{{Str::slug($option, '_')}}">
                                        {{$option}}
                                    </label>
                                </div>
                            @endforeach
                        @endif                            
                    </td>
                </tr>
                @break
        @endswitch
    @else
        @if($fieldname)
        <tr data-index="{{$totalCount+1}}">
            <td>
                <i class="drag-handle fa fa-grip-vertical"></i>
            </td>
            <th style="width: 150px;font-size:12px;">{{ $fieldname }}</th>
            <td style="font-size:12px;">
                {{$fieldplaceholder ?? 'xyz'}}
            </td>
        </tr>
        @endif
    @endif
@elseif($pageDetail->structure=='col-2')
    @switch($fieldtype)
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
            <div class="form-group col-sm-6" data-index="{{$totalCount+1}}">
                <i class="drag-handle fa fa-grip-vertical"></i>
                <label class="col-sm-9 col-form-label pl-1" for="{{$fieldname}}" style="font-size: 13px;">{{$fieldname}} <span class="text-danger">{{$mandatory==0?'':'*'}}</span></label>
                <div class="col-sm-12">
                    <input {{$pageDetail->is_target_page?'':'disabled'}} type="{{$fieldtype=='file_multiple' ? 'file':$fieldtype}}" placeholder="{{$fieldplaceholder}}" class="form-control" style="font-size: 12px;width:100%;" {{$fieldtype=='file_multiple' ? 'multiple':''}}>
                </div>
            </div>
            @break
        @case('textarea')
            <div class="form-group col-sm-12" data-index="{{$totalCount+1}}">
                <i class="drag-handle fa fa-grip-vertical"></i>
                <label class="col-sm-9 col-form-label pl-1" for="{{$fieldname}}" style="font-size: 13px;">{{$fieldname}} <span class="text-danger">{{$mandatory==0?'':'*'}}</span></label>
                <div class="col-sm-12">
                    <textarea placeholder="{{$fieldplaceholder}}" class="form-control" style="font-size: 12px;width:100%;" {{$pageDetail->is_target_page?'':'disabled'}}></textarea>
                </div>
            </div>
            @break
        @case('select')
        @case('multiselect')
            <div class="form-group col-sm-6" data-index="{{$totalCount+1}}">
                <i class="drag-handle fa fa-grip-vertical"></i>
                <label class="col-sm-9 col-form-label pl-1" for="{{$fieldname}}" style="font-size: 13px;">{{$fieldname}} <span class="text-danger">{{$mandatory==0?'':'*'}}</span></label>
                <div class="col-sm-12">
                    <select class="form-control js-select2 custom_field_select" {{$fieldtype=='multiselect'?'multiple':''}} style="font-size: 13px;width:100%;" data-placeholder="{{$fieldplaceholder?? 'Choose an option'}}" {{$pageDetail->is_target_page?'':'disabled'}}>
                        @if($fieldtype!='multiselect')
                        <option value=""></option>
                        @endif
                        
                        @if(count($options)>0)
                        @foreach($options as $option)
                            <option>{{$option}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>
            @break
        @case('checkbox')
            <div class="form-group col-sm-6" data-index="{{$totalCount+1}}">
                <i class="drag-handle fa fa-grip-vertical"></i>
                <label class="col-sm-9 col-form-label pl-1" style="font-size: 13px;">
                    {{$fieldname}} <span class="text-danger">{{$mandatory==0?'':'*'}}</span>
                </label>
                <div class="col-sm-12" style="font-size: 13px;">
                    @if(count($options) > 0)
                        @foreach($options as $option)
                            <div class="form-check" style="display: inline-block; margin-right: 10px;">
                                <input class="form-check-input" type="checkbox" {{$pageDetail->is_target_page?'':'disabled'}}>
                                <label class="form-check-label">{{$option}}</label>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            @break
        @case('radio')
            <div class="form-group col-sm-6" data-index="{{$totalCount+1}}">
                <i class="drag-handle fa fa-grip-vertical"></i>
                <label class="col-sm-9 col-form-label pl-1" style="font-size: 13px;">
                    {{$fieldname}} <span class="text-danger">{{$mandatory==0?'':'*'}}</span>
                </label>
                <div class="col-sm-12" style="font-size: 13px;">
                    @if(count($options) > 0)
                        @foreach($options as $option)
                            <div class="form-check" style="display: inline-block; margin-right: 10px;">
                                <input name="{{$fieldname}}" class="form-check-input" type="radio" {{$pageDetail->is_target_page?'':'disabled'}}>
                                <label class="form-check-label" for="{{$fieldname}}_{{Str::slug($option, '_')}}">
                                    {{$option}}
                                </label>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            @break
    @endswitch
@elseif($pageDetail->structure=='col-3')
    @switch($fieldtype)
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
            <div class="form-group col-sm-4" data-index="{{$totalCount+1}}">
                <i class="drag-handle fa fa-grip-vertical"></i>
                <label class="col-sm-9 col-form-label pl-1" for="{{$fieldname}}" style="font-size: 13px;">{{$fieldname}} <span class="text-danger">{{$mandatory==0?'':'*'}}</span></label>
                <div class="col-sm-12">
                    <input type="{{$fieldtype=='file_multiple' ? 'file':$fieldtype}}" placeholder="{{$fieldplaceholder}}" class="form-control" style="font-size: 12px;width:100%;" {{$fieldtype=='file_multiple' ? 'multiple':''}} {{$pageDetail->is_target_page?'':'disabled'}}>
                </div>
            </div>
            @break
        @case('textarea')
            <div class="form-group col-sm-8" data-index="{{$totalCount+1}}">
                <i class="drag-handle fa fa-grip-vertical"></i>
                <label class="col-sm-9 col-form-label pl-1" for="{{$fieldname}}" style="font-size: 13px;">{{$fieldname}} <span class="text-danger">{{$mandatory==0?'':'*'}}</span></label>
                <div class="col-sm-12">
                    <textarea placeholder="{{$fieldplaceholder}}" class="form-control" style="font-size: 12px;width:100%;" {{$pageDetail->is_target_page?'':'disabled'}}></textarea>
                </div>
            </div>
            @break
        @case('select')
        @case('multiselect')
            <div class="form-group col-sm-4" data-index="{{$totalCount+1}}">
                <i class="drag-handle fa fa-grip-vertical"></i>
                <label class="col-sm-9 col-form-label pl-1" for="{{$fieldname}}" style="font-size: 13px;">{{$fieldname}} <span class="text-danger">{{$mandatory==0?'':'*'}}</span></label>
                <div class="col-sm-12">
                    <select class="form-control js-select2 custom_field_select" {{$fieldtype=='multiselect'?'multiple':''}} style="font-size: 13px;width:100%;" data-placeholder="{{$fieldplaceholder?? 'Choose an option'}}" {{$pageDetail->is_target_page?'':'disabled'}}>
                        @if($fieldtype!='multiselect')
                        <option value=""></option>
                        @endif
                        
                        @if(count($options)>0)
                        @foreach($options as $option)
                            <option>{{$option}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>
            @break
        @case('checkbox')
            <div class="form-group col-sm-4" data-index="{{$totalCount+1}}">
                <i class="drag-handle fa fa-grip-vertical"></i>
                <label class="col-sm-9 col-form-label pl-1" style="font-size: 13px;">
                    {{$fieldname}} <span class="text-danger">{{$mandatory==0?'':'*'}}</span>
                </label>
                <div class="col-sm-12" style="font-size: 13px;">
                    @if(count($options) > 0)
                        @foreach($options as $option)
                            <div class="form-check" style="display: inline-block; margin-right: 10px;">
                                <input class="form-check-input" type="checkbox" {{$pageDetail->is_target_page?'':'disabled'}}>
                                <label class="form-check-label">{{$option}}</label>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            @break
        @case('radio')
            <div class="form-group col-sm-4" data-index="{{$totalCount+1}}">
                <i class="drag-handle fa fa-grip-vertical"></i>
                <label class="col-sm-9 col-form-label pl-1" style="font-size: 13px;">
                    {{$fieldname}} <span class="text-danger">{{$mandatory==0?'':'*'}}</span>
                </label>
                <div class="col-sm-12" style="font-size: 13px;">
                    @if(count($options) > 0)
                        @foreach($options as $option)
                            <div class="form-check" style="display: inline-block; margin-right: 10px;">
                                <input name="{{$fieldname}}" class="form-check-input" type="radio" {{$pageDetail->is_target_page?'':'disabled'}}>
                                <label class="form-check-label" for="{{$fieldname}}_{{Str::slug($option, '_')}}">
                                    {{$option}}
                                </label>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            @break
    @endswitch
@elseif($pageDetail->structure=='col-4')
    @switch($fieldtype)
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
            <div class="form-group col-sm-3" data-index="{{$totalCount+1}}">
                <i class="drag-handle fa fa-grip-vertical"></i>
                <label class="col-sm-9 col-form-label pl-1" for="{{$fieldname}}" style="font-size: 13px;">{{$fieldname}} <span class="text-danger">{{$mandatory==0?'':'*'}}</span></label>
                <div class="col-sm-12">
                    <input type="{{$fieldtype=='file_multiple' ? 'file':$fieldtype}}" placeholder="{{$fieldplaceholder}}" class="form-control" style="font-size: 12px;width:100%;" {{$fieldtype=='file_multiple' ? 'multiple':''}} {{$pageDetail->is_target_page?'':'disabled'}}>
                </div>
            </div>
            @break
        @case('textarea')
            <div class="form-group col-sm-6" data-index="{{$totalCount+1}}">
                <i class="drag-handle fa fa-grip-vertical"></i>
                <label class="col-sm-9 col-form-label pl-1" for="{{$fieldname}}" style="font-size: 13px;">{{$fieldname}} <span class="text-danger">{{$mandatory==0?'':'*'}}</span></label>
                <div class="col-sm-12">
                    <textarea placeholder="{{$fieldplaceholder}}" class="form-control" style="font-size: 12px;width:100%;" {{$pageDetail->is_target_page?'':'disabled'}}></textarea>
                </div>
            </div>
            @break
        @case('select')
        @case('multiselect')
            <div class="form-group col-sm-3" data-index="{{$totalCount+1}}">
                <i class="drag-handle fa fa-grip-vertical"></i>
                <label class="col-sm-9 col-form-label pl-1" for="{{$fieldname}}" style="font-size: 13px;">{{$fieldname}} <span class="text-danger">{{$mandatory==0?'':'*'}}</span></label>
                <div class="col-sm-12">
                    <select class="form-control js-select2 custom_field_select" {{$fieldtype=='multiselect'?'multiple':''}} style="font-size: 13px;width:100%;" data-placeholder="{{$fieldplaceholder?? 'Choose an option'}}" {{$pageDetail->is_target_page?'':'disabled'}}>
                        @if($fieldtype!='multiselect')
                        <option value=""></option>
                        @endif
                        @if(count($options)>0)
                        @foreach($options as $option)
                            <option>{{$option}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>
            @break
        @case('checkbox')
            <div class="form-group col-sm-3" data-index="{{$totalCount+1}}">
                <i class="drag-handle fa fa-grip-vertical"></i>
                <label class="col-sm-9 col-form-label pl-1" style="font-size: 13px;">
                    {{$fieldname}} <span class="text-danger">{{$mandatory==0?'':'*'}}</span>
                </label>
                <div class="col-sm-12" style="font-size: 13px;">
                    @if(count($options) > 0)
                        @foreach($options as $option)
                            <div class="form-check" style="display: inline-block; margin-right: 10px;">
                                <input class="form-check-input" type="checkbox" {{$pageDetail->is_target_page?'':'disabled'}}>
                                <label class="form-check-label">{{$option}}</label>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            @break
        @case('radio')
            <div class="form-group col-sm-3" data-index="{{$totalCount+1}}">
                <i class="drag-handle fa fa-grip-vertical"></i>
                <label class="col-sm-9 col-form-label pl-1" style="font-size: 13px;">
                    {{$fieldname}} <span class="text-danger">{{$mandatory==0?'':'*'}}</span>
                </label>
                <div class="col-sm-12" style="font-size: 13px;">
                    @if(count($options) > 0)
                        @foreach($options as $option)
                            <div class="form-check" style="display: inline-block; margin-right: 10px;">
                                <input name="{{$fieldname}}" class="form-check-input" type="radio" {{$pageDetail->is_target_page?'':'disabled'}}>
                                <label class="form-check-label" for="{{$fieldname}}_{{Str::slug($option, '_')}}">
                                    {{$option}}
                                </label>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            @break
    @endswitch
@endif
<script>
    $('.custom_field_select').select2();
</script>