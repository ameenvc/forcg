@if($pagename=='register')
<!-- external add attachments starts-->
<div class="modal fade" id="addattachmentsModal" tabindex="-1" role="dialog" aria-labelledby="addattachmentsModalLabel" aria-hidden="true">
    <div class="modal-dialog {{$CustomFields?'modal-lg':''}}" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addattachmentsModalLabel">Add Attachments</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="add_attachments_ajax" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-success" id="addattachmentsuccess" style="display:none">
                        Attachments added successfully
                    </div>

                    <ul class="alert alert-danger" id="dangeraddattachment" style="display:none">
                        Internal server error
                    </ul>

                    <ul class="alert alert-danger" id="attachmentnotupdated" style="display:none"></ul>
                    <div class="row sortable-field">
                        <div class="form-group {{$CustomFields?'col-sm-6':'col-sm-12'}}" data-index="{{array_shift($externalFieldIndex)}}">
                            <label class="col-form-label" style="font-size:12px;"> Attach files(avoid '#' and '&' in file names)</label>

                            <input type="file" style="font-size: 13px;" class="form-control" name="externalfiles[]" id="externalfiles" multiple required>
                        </div>    
                        {!!$CustomFields!!}
                    </div>                              
                </div>
                <input type="hidden" name="instrument_main_id" id="instrument_main_id" value="{{$id}}"/>
                <input type="hidden" name="page_name" id="instrument_main_id" value="register"/>             
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                    <button type="submit" class="btn btn-primary" style="font-size:12px">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- external add attachments ends-->
@elseif($pagename=='undercalibration')
<!-- external add attachments starts-->
<div class="modal fade" id="addattachmentsModal" tabindex="-1" role="dialog" aria-labelledby="addattachmentsModalLabel" aria-hidden="true">
    <div class="modal-dialog {{$CustomFields?'modal-lg':''}}" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addattachmentsModalLabel">Add Attachments</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="add_attachments_ajax" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-success" id="addattachmentsuccess" style="display:none">
                        Attachments added successfully
                    </div>

                    <ul class="alert alert-danger" id="dangeraddattachment" style="display:none">
                        Internal server error
                    </ul>

                    <ul class="alert alert-danger" id="attachmentnotupdated" style="display:none"></ul>
                    <div class="row sortable-field">
                        <div class="form-group {{$CustomFields?'col-sm-6':'col-sm-12'}}" data-index="{{array_shift($externalFieldIndex)}}">
                            <label class="col-form-label" style="font-size:12px;"> Attach files(avoid '#' and '&' in file names)</label>

                            <input type="file" style="font-size: 13px;" class="form-control" name="externalfiles[]" id="externalfiles" multiple required>
                        </div> 
                        {!!$CustomFields!!}
                    </div>                                 
                </div>
                <input type="hidden" name="instrument_main_id" id="instrument_main_id" value="{{$id}}"> 
                <input type="hidden" name="page_name" id="instrument_main_id" value="undercalibration"/>             
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                    <button type="submit" class="btn btn-primary" style="font-size:12px">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- external add attachments ends-->
@endif
<script>
    $(document).ready(function () {
        var savedOrder = @json($defaultFieldIndex); 
        var $container = $('.sortable-field');
        var $allFields = $container.children('.form-group');
        // Map of index to element
        var fieldMap = {};
        $allFields.each(function () {
            var index = $(this).data('index');
            if (index !== undefined) {
                fieldMap[index] = $(this);
            }
        });
        // Remove all fields from container
        $allFields.detach();
        // Append elements in saved order
        if (Array.isArray(savedOrder)) {
            savedOrder.forEach(function (index) {
                if (fieldMap[index]) {
                    $container.append(fieldMap[index]);
                    delete fieldMap[index];
                }
            });
        }
        // Append remaining fields (not in savedOrder)
        $.each(fieldMap, function (index, $el) {
            $container.append($el);
        });
    });
</script>