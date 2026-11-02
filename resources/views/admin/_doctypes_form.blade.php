<div id="form_type" class="modal fade" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h3 class="modal-title">{{ isset($doctype) ? 'Edit Type' : 'Add Type' }}</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                        <i class="si si-close"></i>
                    </button>
                </div>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="type_name" class="col-form-label">Type Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" style="font-size: 13px" id="type_name"
                           {{ isset($doctype) && $doctype->is_default ? 'readonly' : '' }}
                           value="{{ old('type_name', isset($doctype) ? $doctype->typename : '') }}"
                           placeholder="Enter type"  required  name="type_name">
                    <div class="label-invalid col error-type_name"></div>
                    <!-- Warning message container -->
                    <div id="similarity-warning" class="alert alert-warning" style="display: none; font-size: 12px; margin-top: 10px;text-align: left">
                        <i class="fa fa-exclamation-triangle"></i>
                        <span id="warning-text"></span>
                    </div>
                    <!-- Error message container for exact matches -->
                    <div id="exact-match-error" class="alert alert-danger" style="display: none; font-size: 12px; margin-top: 10px;text-align: left">
                        <i class="fa fa-times-circle"></i>
                        <span id="error-text"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="type_code" class="col-form-label">Type Code <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" style="font-size: 13px" id="type_code"
                           value="{{ old('type_code', isset($doctype) ? $doctype->type_code : '') }}"
                           data-doctype-id="{{ isset($doctype) ? $doctype->id : '' }}"
                           placeholder="Enter code"  required  name="type_code">
                    <div class="label-invalid col error-type_code"></div>
                </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button"
                        data-name="{{ isset($doctype) ? $doctype->typename : '' }}"
                        data-code="{{ isset($doctype) ? $doctype->type_code : '' }}"
                        class="btn btn-dark btn-sm btn-reset-form {{ !isset($doctype) ? 'btn-reset-add' : 'btn-reset-edit' }}">Reset</button>
                <button type="button" class="btn btn-primary btn-sm register-doc-type"
                        data-url="{{ isset($doctype) ? route('admin.updatedoctype', ['id' => $doctype->id]) : route('admin.adddoctype') }}"
                    >Save</button>
            </div>
        </div>
    </div>
</div>