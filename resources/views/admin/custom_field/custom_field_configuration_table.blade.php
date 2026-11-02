<table class="table table-bordered table-striped table-vcenter js-dataTable-full js-table-checkable" id="custom-field-table">
    <thead >
        <tr>
            <th class="text-center" style="width: 2%;background-color:#fff;position: sticky; top: -10px;z-index:5;" id="firstColumn" data-orderable="false">
                <div class="custom-control custom-checkbox d-inline-block">
                    <input type="checkbox" class="custom-control-input" id="check-all" name="check-all">
                    <label class="custom-control-label" for="check-all"></label>
                </div>
            </th>
            <th class="text-center" style="position: sticky; top: -10px;z-index:5;width: 2%;background-color:#fff;font-size:10px;font-weight:bold !important;cursor:pointer; text-transform:capitalize;" draggable="false" data-orderable="false">
                Sl No.
            </th>
            <th id="modulenameheader" class="text-center" style="position: sticky; top: -10px;z-index:5;width: 10%;background-color:#fff;font-size:10px;font-weight:bold !important;cursor:pointer; text-transform:capitalize;" draggable="false" data-orderable="false">
                Module Name
            </th>
            <th class="text-center" draggable="false" style="position: sticky; top: -10px;z-index:5;width: 20%;background-color:#fff;font-size:10px;font-weight:bold !important;cursor:pointer; text-transform:capitalize;" data-orderable="false">
                Field Name
            </th>
            <th id="typeheader" class="text-center" draggable="false" style="position: sticky; top: -10px;z-index:5;width: 10%;background-color:#fff;font-size:10px;font-weight:bold !important;cursor:pointer; text-transform:capitalize;" data-orderable="false">
                Type
            </th>
            <th class="text-center" draggable="false" style="position: sticky; top: -10px;z-index:5;width: 8%;background-color:#fff;font-size:10px;font-weight:bold !important;cursor:pointer; text-transform:capitalize;" data-orderable="false">
                Mandatory (Yes/No)
            </th>
            <th class="text-center" draggable="false" style="position: sticky; top: -10px;z-index:5;width: 20%;background-color:#fff;font-size:10px;font-weight:bold !important;cursor:pointer; text-transform:capitalize;" data-orderable="false">
                Target Pages
            </th>
            <th class="text-center" draggable="false" style="position: sticky; top: -10px;z-index:5;width: 20%;background-color:#fff;font-size:10px;font-weight:bold !important;cursor:pointer; text-transform:capitalize;" data-orderable="false">
                Display Pages
            </th>
            <th class="text-center" draggable="false" style="position: sticky; top: -10px;z-index:5;width: 8%;background-color:#fff;font-size:10px;font-weight:bold !important;cursor:pointer; text-transform:capitalize;" data-orderable="false">
                Actions
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach($custom_fields as $field)
        @php 
            $target_pages = json_decode($field->target_pages);
            $display_pages = json_decode($field->display_pages);
            $targets = App\ModuleBladePageDetail::whereIn('id', $target_pages)->pluck('page_name')->toArray();
            $displays = App\ModuleBladePageDetail::whereIn('id', $display_pages)->pluck('page_name')->toArray();
        @endphp
        <tr @if($field->deleted) style="color: red; text-decoration: line-through; background: #fff2f2;" @endif>
            <td class="text-center" style="width:1%;">
                <div class="custom-control custom-checkbox d-inline-block">
                    <input type="checkbox" class="custom-control-input" id="id{{$field->id}}" name="id[]" @if($field->deleted) disabled @endif>
                    <label class="custom-control-label" for="id{{$field->id}}"></label>
                </div>
            </td>
            <td class="text-center px-1" style="font-size:10px;">{{$count++}}</td>
            <td class="text-center px-1" style="font-size:10px;">
                {{$modules[$field->module_name]}}
            <span style="display: none;">e_{{$modules[$field->module_name]}}_f</span>
            </td>
            <td class="text-center px-1" style="font-size:10px;word-wrap: break-word;">
                {{$field->field_name}}
            </td>
            <td class="text-center px-1" style="font-size:10px;">
                {{$fieldtypes[$field->field_type]}}
            <span style="display: none;">e_{{$fieldtypes[$field->field_type]}}_f</span>
            </td>
            <td class="text-center px-1" style="font-size:10px;">
                {{$field->mandatory?'Yes':'No'}}
            </td>
            <td class="text-center px-1" style="font-size:10px; position: relative;">
            <div class="hover-container" style="display: inline-block; position: relative;">
                {{ count($targets) }} selected

                @if(count($targets) > 0)
                <div class="hover-dropdown">
                @foreach($targets as $target)
                    <div class="dropdown-item text-left">{{ ucfirst($target) }}</div>
                @endforeach
                </div>
                @endif
            </div>
            </td>
            <td class="text-center px-1" style="font-size:10px; position: relative;">
            <div class="hover-container" style="display: inline-block; position: relative;">
                {{ count($displays) }} selected

                @if(count($displays) > 0)
                <div class="hover-dropdown">
                @foreach($displays as $display)
                    <div class="dropdown-item text-left">{{ ucfirst($display) }}</div>
                @endforeach
                </div>
                @endif
            </div>
            </td>
            <td>
            <div class="btn-group m-auto d-flex justify-content-center">
                <a href="#" class="btn btn-sm btn-alt-secondary edit-field @if($field->deleted) disabled pointer-events-none @endif" data-id={{$field->id}} data-toggle="tooltip" data-placement="top" title="Edit"
                @if($field->deleted) tabindex="-1" aria-disabled="true" @endif>
                <i class="fa fa-pencil-alt" style="font-size:13px;cursor:pointer"></i>
                </a>
                <a data-id="{{$field->id}}" class="btn btn-sm btn-alt-secondary delete-custom-field @if($field->deleted) disabled pointer-events-none @endif" data-hasdata="{{$field->hasData}}" data-toggle="tooltip" data-placement="top" title="Delete"
                @if($field->deleted) tabindex="-1" aria-disabled="true" @endif>
                <i class="far fa-trash-alt" style="font-size:13px;cursor:pointer"></i>
                </a>
            </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>