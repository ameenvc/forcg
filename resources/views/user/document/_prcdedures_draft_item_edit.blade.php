<div class="modal fade edit-content-modal" id="edit_content_{{$i}}" 
        data-content-id="{{ $relatedDocumentIds[$i] }}"
        data-document-id="{{ $documentContent->document_id }}"
        data-modal-index="{{$i}}"
        data-content-loaded="false"
        tabindex="-1" role="dialog" aria-labelledby="edit_contentLabel{{$i}}" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit_contentLabel{{$i}}">Edit Content</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="alert alert-success" id="successBtn{{$i}}" style="display:none;font-size:12px;">Content edited successfully!</div>
            <ul class="alert alert-danger" id="dangerBtn{{$i}}" style="display:none;font-size:12px;"></ul>
            <div class="modal-body">
                <div class="loading-indicator text-center" style="padding: 40px;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-2">Loading content...</p>
                </div>
                <div class="modal-content-container" style="display:none;">
                    <form class="mb-5 dynamic-form" id="form_id_{{ $relatedDocumentIds[$i] }}" method="post" action="{{ route('doc.update_draft_content_text') }}" enctype="multipart/form-data" name="form_id_{{ $relatedDocumentIds[$i] }}">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-borderless dynamic_field">
                                <tbody id="dynamic_field{{$i}}">
                                <tr>
                                    <input type="hidden" class="form-control col-sm-12" name="content_id" id="content_id_{{ $relatedDocumentIds[$i] }}" value="{{ $relatedDocumentIds[$i] }}" />
                                    <input type="hidden" class="form-control" name="document_id" id="document_id_{{$i}}" value="{{ $documentContent->document_id }}"/>
                                    <td colspan="3">
                                        <label for="document_title" class="col-sm-12 col-form-label px-0">Section Title<span class="text-danger">*</span></label>
                                        <div class="col-sm-6 px-0">
                                            <input type="text" class="form-control" name="title" id="title_{{$i}}" value="{{ $documentContent->title }}">
                                        </div>
                                    </td>
                                </tr>
                                <!-- Content rows will be loaded here via AJAX -->
                                <tr>
                                    <td colspan="3" class="text-right">
                                        <button type="button" name="add" id="newadd_{{$i}}" class="btn btn-sm btn-light btn-add-row" data-target-body="dynamic_field{{$i}}" data-toggle="tooltip" title="Add more">
                                            <i class="fa fa-fw fa-plus"></i>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="modal-footer">
                                <input type="button" class="btn btn-danger" data-dismiss="modal" style="font-size:12px" value="Cancel"/>
                                <button type="button" class="btn btn-primary btn-save" style="font-size:12px" 
                                    data-modal-index="{{$i}}" 
                                    data-content-id="{{ $relatedDocumentIds[$i] }}">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>