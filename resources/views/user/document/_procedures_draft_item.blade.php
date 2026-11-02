<table class="table table-bordered parent-table" width="100%">
    <tbody>
    @php $i = 0; $n = 1; @endphp
    @foreach ($documentContents as $documentContent)
        @php
            // Get processed contents from controller (already has base64 images extracted)
            if (isset($relatedDocumentIds) && isset($relatedDocumentIds[$i]) && isset($processedContents[$relatedDocumentIds[$i]])) {
                $contents = $processedContents[$relatedDocumentIds[$i]];
            } else {
                $contents = [];
            }
        @endphp
        <tr class="table-active tr-draggable {{ isset($mode) && $mode == "dragdrop" ? 'draggable-row' : '' }}"
                data-content-id="{{ $relatedDocumentIds[$i] }}"
                data-document-id="{{ $documentContent->document_id }}"
                data-sub-content-id="" draggable="{{ isset($mode) && $mode == "dragdrop" ? 'true' : 'false' }}" 
                style="position: relative;">
            @if(isset($mode) && $mode == "dragdrop")
                <span class="drag-handle" title="Drag to reorder">⋮⋮</span>
            @endif
            <td class="text-center" style="width:50px;border-right: 1px solid #D3D3D3;">
                <span style="color: #3366CC;font-size:13px;" class="font-w600"><?php printf("%.1f", $n); ?></span>
            </td>
            <td class="text-left" style="width:230px;border-right: 1px solid #D3D3D3;">
                <span  class="font-w600 text-uppercase">Responsibility</span>
            </td>
            <td class="text-left">
                <span  class="font-w600">{{ucfirst($documentContent->title)}}</span>
            </td>

            <td class="text-right" style="width:50px;">
                <a href="#" class="font-w600 edit-content-btn" style="font-size:10px;cursor:pointer;" data-id="{{$i}}" title="Edit content"><i class="fa fa-2x fa-edit" style="color:grey;"></i></a>
            </td>
            <td class="text-right" style="width:50px;">
                <a href="#" class="font-w600" style="font-size:10px;cursor:pointer;" title="Delete" data-title=<?= $relatedDocumentIds[$i] ?>><i class="fas fa-2x fa-trash" style="color:red;"></i></a>
            </td>
        </tr>

        @php $m = 1; @endphp
        @foreach($contents as $key => $con)
            <tr class="content-item tr-draggable {{ isset($mode) && $mode == "dragdrop" ? 'draggable-row hovered' : '' }}"
                data-content-id="{{ $con['content_id'] }}" data-sub-content-id="{{ $con['id'] }}"
                draggable="{{ isset($mode) && $mode == "dragdrop" ? 'true' : 'false' }}" 
                style="position: relative;">
                @if(isset($mode) && $mode == "dragdrop")
                    <span class="drag-handle" title="Drag to reorder">⋮⋮</span>
                @endif
                <td class="text-center">
                    <span style="font-size:13px;">@php echo $n . '.' . $m; $m++; @endphp</span>
                </td>
                <td class="text-left">
                    @if(isset($con['responsibility']))
                        <span style="word-wrap:break-word;font-size:13px;"><b>{{ucfirst($con['responsibility'])}}</b></span>
                    @else
                        <span>-</span>
                    @endif
                </td>
                <td class="text-left" colspan="3">
                    <div class="rich_text_container" data-content-id="{{ $con['id'] }}">
                        @if(isset($con['processed_html']))
                            <span style="font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                                {!! $con['processed_html'] !!}
                            </span>
                        @else
                            <span>-</span>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
        <?php $i++; $n++;?>
    @endforeach
    </tbody>
</table>
<div class="edit-area">
</div>
<script>
    $('body').on('click', '.btn_remove', function() {
        const parentElement = $(this).closest('.dynamic-added');
        const responsibility = parentElement.find('.res_list').val();
        const richText = parentElement.find('.rich_list').val();
        if(responsibility !== '' || richText !== '') {
            if (confirm('Are you sure you want to remove this content?')) {
                parentElement.remove();
            }
        } else {
            parentElement.remove();
        }
    });
</script>
<script>
    window.pendingUpdates = [];
    window.hadPendingUpdates = false;
    let saveTimeout = null;
    let isSaving = false;
    
    function showSavingIndicator() {
    }
    
    function hideSavingIndicator() {
    }
    
    function savePendingUpdates() {
        if (window.pendingUpdates.length === 0 || isSaving) {
            return;
        }
        
        isSaving = true;
        showSavingIndicator();
        
        const updateToSave = window.pendingUpdates[window.pendingUpdates.length - 1];
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        $.ajax({
            url: "{{ route('doc.update.content.position', ['id' => $id]) }}",
            method: 'POST',
            data: updateToSave,
            success: function(response) {
                if(response.success) {
                    window.pendingUpdates = [];
                }
                isSaving = false;
                hideSavingIndicator();
            },
            error: function(xhr, status, error) {
                isSaving = false;
                hideSavingIndicator();
            }
        });
    }
    
    function queuePositionUpdate(updateData) {
        window.pendingUpdates.push(updateData);
        window.hadPendingUpdates = true;
        
        if (saveTimeout) {
            clearTimeout(saveTimeout);
        }
        
        saveTimeout = setTimeout(function() {
            savePendingUpdates();
        }, 2000);
    }
    
    window.forceSavePendingUpdates = function() {
        if (saveTimeout) {
            clearTimeout(saveTimeout);
        }
        savePendingUpdates();
    };
    
    // Renumber all rows after drag-drop
    function renumberAllRows() {
        const $table = $('.parent-table');
        const $rows = $table.find('tbody tr.tr-draggable');
        
        let parentNumber = 1;
        let currentParentId = null;
        let childNumber = 1;
        
        $rows.each(function() {
            const $row = $(this);
            const contentId = $row.attr('data-content-id');
            const subContentId = $row.attr('data-sub-content-id');
            const isParent = !subContentId || subContentId === '' || subContentId === contentId;
            
            if (isParent) {
                // This is a parent row
                const numberText = parentNumber.toFixed(1);
                $row.find('td:first span').text(numberText);
                
                currentParentId = contentId;
                parentNumber++;
                childNumber = 1;
            } else {
                // This is a child row
                const numberText = (parentNumber - 1) + '.' + childNumber;
                $row.find('td:first span').text(numberText);
                
                childNumber++;
            }
        });
        
        console.log('✅ Renumbered all rows');
    }
    
    function dragdrop() {
        let startIndex;
        let startId;
        let draggedTh;
        let dataContentId;
        let dataSubContentId;
        const $table = $('.parent-table');
        if ($table.length === 0) {
            console.error('Table not found: .parent-table');
            return;
        }

        const draggableElements = $table.find('tbody tr[draggable="true"].draggable-row');
        draggableElements.off('dragstart dragover dragend dragleave drop');
        
        draggableElements.on('dragstart', function(e) {
            draggedTh = this;
            startIndex = $(this).index();
            dataContentId = $(this).attr('data-content-id');
            dataSubContentId = $(this).attr('data-sub-content-id');
            $(this).addClass('dragging');
        }).on('dragover', function(e) {
            e.preventDefault();
            e.originalEvent.dataTransfer.dropEffect = 'move';
            const mouseY = e.originalEvent.clientY;
            const screenHeight = window.innerHeight;
            const scrollStep = 5;
            const edgeThreshold = 100;

            if (mouseY < edgeThreshold) {
                window.scrollBy(0, -scrollStep);
            }
            else if (screenHeight - mouseY < edgeThreshold) {
                window.scrollBy(0, scrollStep);
            }
        }).on('dragend', function(e) {
            $(this).removeClass('dragging');
        }).on("dragover", function (event) {
            event.preventDefault();
            $(this).addClass("drag-over");
        }).on("dragleave", function () {
            $(this).removeClass("drag-over");
        }).on('drop', function(e) {
            e.preventDefault();
            const targetContentId = $(this).attr('data-content-id');
            const targetSubContentId = $(this).attr('data-sub-content-id');
            $(this).removeClass("drag-over");
            
            if (draggedTh !== this) {
                const $draggedRow = $(draggedTh);
                const $targetRow = $(e.target).closest('tr.tr-draggable');
                
                // Check if we're dragging a parent row (no sub-content-id)
                const isDraggingParent = !dataSubContentId || dataSubContentId === '';
                
                if (isDraggingParent) {
                    // Find all child rows that belong to this parent
                    const $childRows = $table.find('tbody tr[data-content-id="' + dataContentId + '"][data-sub-content-id]').filter(function() {
                        return $(this).attr('data-sub-content-id') !== '';
                    });
                    
                    // Move parent and all its children together
                    if ($draggedRow.index() < $targetRow.index()) {
                        $draggedRow.insertAfter($targetRow);
                        // Insert children after parent in order
                        let $lastInserted = $draggedRow;
                        $childRows.each(function() {
                            $(this).insertAfter($lastInserted);
                            $lastInserted = $(this);
                        });
                    } else {
                        $draggedRow.insertBefore($targetRow);
                        // Insert children after parent in order
                        let $lastInserted = $draggedRow;
                        $childRows.each(function() {
                            $(this).insertAfter($lastInserted);
                            $lastInserted = $(this);
                        });
                    }
                } else {
                    // Moving a child row only
                    if ($draggedRow.index() < $targetRow.index()) {
                        $draggedRow.insertAfter($targetRow);
                    } else {
                        $draggedRow.insertBefore($targetRow);
                    }
                }
                
                const updateData = {
                    'old_content_id': dataContentId,
                    'old_sub_content_id': dataSubContentId,
                    'new_content_id': targetContentId,
                    'new_sub_content_id': targetSubContentId,
                };
                queuePositionUpdate(updateData);
                
                // Renumber all rows immediately after visual move
                renumberAllRows();
                
                $(draggedTh).removeClass('dragging');
                
                startIndex = 0;
                startId = 0;
                draggedTh = null;
                dataContentId = 0;
                dataSubContentId = 0;
            }
        }).on('dragend', function() {
            $(this).removeClass('dragging');
        });
    };
</script>
<script>
    // Single initialization script with AJAX lazy loading
    $(document).ready(function() {
        var globalRowCounter = 0;
        
        $('.edit-content-btn').on('click', function(e) {
            e.preventDefault();
            
            var $btn = $(this);
            var index = $btn.data('id');
            var $row = $btn.closest('tr');
            var contentId = $row.data('content-id');
            var documentId = $row.data('document-id');
            
            $('#modal_loading_' + index).modal('show');
            
            $.ajax({
                url: '{{ route("doc.load_edit_modal") }}',
                method: 'POST',
                data: {
                    index: index,
                    content_id: contentId,
                    document_id: documentId,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        var $loadingModal = $('#modal_loading_' + index);
                        $loadingModal.modal('hide');
                        $loadingModal.on('hidden.bs.modal', function() {
                            $(this).remove();
                            $('.modal-backdrop').remove();
                            $('body').removeClass('modal-open');
                        });
                        setTimeout(function() {
                            $('#' + response.modalId).remove();
                            $('.edit-area').append(response.html);
                            $('#' + response.modalId).modal('show');
                        }, 300);
                    } else {
                        var $loadingModal = $('#modal_loading_' + index);
                        $loadingModal.modal('hide');
                        $loadingModal.on('hidden.bs.modal', function() {
                            $(this).remove();
                            $('.modal-backdrop').remove();
                        });
                        alert('Failed to load modal: ' + (response.message || 'Unknown error'));
                    }
                },
                error: function(xhr, status, error) {
                    var $loadingModal = $('#modal_loading_' + index);
                    $loadingModal.modal('hide');
                    $loadingModal.on('hidden.bs.modal', function() {
                        $(this).remove();
                        $('.modal-backdrop').remove();
                    });
                    alert('Error loading modal: ' + error);
                }
            });
        });
        
        // Handle modal show event - load content via AJAX
        $(document).on('show.bs.modal', '.edit-content-modal', function(e) {
            var $modal = $(this);
            var contentLoaded = $modal.attr('data-content-loaded');

            if (contentLoaded === 'false') {
                var contentId = $modal.data('content-id');
                var modalIndex = $modal.data('modal-index');
                var $loadingIndicator = $modal.find('.loading-indicator');
                var $contentContainer = $modal.find('.modal-content-container');
                var $dynamicField = $modal.find('#dynamic_field' + modalIndex);
                
                $loadingIndicator.show();
                $contentContainer.hide();
                
                $.ajax({
                    url: '{{ route("doc.get_content_for_edit") }}',
                    method: 'POST',
                    data: {
                        content_id: contentId,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success && response.contents) {
                            try {
                                var $addMoreRow = $dynamicField.find('tr').last();
                                
                                var initPromises = [];
                                
                                response.contents.forEach(function(con, index) {
                                    var isFirst = (index === 0);
                                    var escapedResponsibility = $('<div>').text(con.responsibility || '').html();
                                    
                                    var rowHtml = '<tr class="dynamic-added">' +
                                        '<input type="hidden" class="form-control col-sm-12" name="text_id[]" value="' + (con.id || '') + '" />' +
                                        '<td>' +
                                        '<label for="responsibility" class="col-sm-12 col-form-label px-0 pt-0">Responsibility(Position)</label>' +
                                        '<input type="text" class="form-control res_list col-sm-12" name="responsibility[]" value="' + escapedResponsibility + '"/>' +
                                        '</td>' +
                                        '<td>' +
                                        '<textarea class="form-control rich_list col-sm-12 needs-editor" id="rich_text_' + modalIndex + '_' + index + '" name="rich_text[]"></textarea>' +
                                        '</td>' +
                                        '<td>' +
                                        (!isFirst ? '<button type="button" class="btn btn-danger btn_remove" data-toggle="tooltip" title="remove">X</button>' : '') +
                                        '</td>' +
                                        '</tr>';
                                    $addMoreRow.before(rowHtml);
                                    $('#rich_text_' + modalIndex + '_' + index).val(con.rich_text || '');
                                });
                                var currentIndex = 0;
                                function initNextEditor() {
                                    if (currentIndex < response.contents.length) {
                                        var editorId = '#rich_text_' + modalIndex + '_' + currentIndex;
                                        
                                        $(editorId).summernote({
                                            height: 200,
                                            dialogsInBody: true,
                                            callbacks: {
                                                onInit: function() {
                                                    currentIndex++;
                                                    setTimeout(initNextEditor, 50);
                                                }
                                            }
                                        });
                                    } else {
                                        finishLoading();
                                    }
                                }
                                
                                function finishLoading() {
                                    console.log('All rows processed successfully');
                                    var formId = $modal.find('.dynamic-form').attr('id');
                                    if (!window.originalFormData) window.originalFormData = {};
                                    if (!window.changedRecords) window.changedRecords = {};
                                    
                                    window.originalFormData[formId] = JSON.parse(JSON.stringify(response.contents));
                                    window.changedRecords[formId] = new Set();
                                    $modal.attr('data-content-loaded', 'true');
                                    $loadingIndicator.hide();
                                    $contentContainer.show();
                                }
                                initNextEditor();
                            } catch (error) {
                                alert('Error processing content: ' + error.message);
                                $modal.modal('hide');
                            }
                        } else {
                            alert('Failed to load content: Invalid response');
                            $modal.modal('hide');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error loading content: ' + error);
                        $modal.modal('hide');
                    }
                });
            } else {
                $modal.find('.loading-indicator').hide();
                $modal.find('.modal-content-container').show();
            }
        });
        
        // Handle "Add more" button clicks using event delegation
        $(document).on('click', '.btn-add-row', function() {
            globalRowCounter++;
            var targetBodyId = $(this).data('target-body');
            var uniqueId = Date.now() + '_' + globalRowCounter;
            
            var newField = '<tr id="row' + uniqueId + '" class="dynamic-added">' +
                '<input type="hidden" class="form-control col-sm-12" name="text_id[]" value="null" />' +
                '<td>' +
                '<label for="responsibility" class="col-sm-12 col-form-label px-0 pt-0">Responsibility(Position)</label>' +
                '<input type="text" class="form-control res_list" name="responsibility[]">' +
                '</td>' +
                '<td>' +
                '<textarea id="rich_text' + uniqueId + '" name="rich_text[]" class="form-control rich_list needs-editor"></textarea>' +
                '</td>' +
                '<td>' +
                '<button type="button" name="remove" class="btn btn-danger btn_remove" data-toggle="tooltip" title="remove">X</button>' +
                '</td>' +
                '</tr>';
            var $targetBody = $('#' + targetBodyId);
            var $addMoreRow = $targetBody.find('tr').last();
            $addMoreRow.before(newField);
            $('#rich_text' + uniqueId).summernote({
                height: 200,
                dialogsInBody: true
            });
        });
        
        $(document).on('input change', '.res_list', function() {
            var $row = $(this).closest('.dynamic-added');
            var $form = $(this).closest('.dynamic-form');
            var formId = $form.attr('id');
            var rowIndex = $form.find('.dynamic-added').index($row);
            
            if (window.changedRecords && window.changedRecords[formId]) {
                window.changedRecords[formId].add(rowIndex);
                $row.addClass('row-modified').css('background-color', '#fff3cd');
            }
        });
        
        $(document).on('summernote.change', '.rich_list', function() {
            var $row = $(this).closest('.dynamic-added');
            var $form = $(this).closest('.dynamic-form');
            var formId = $form.attr('id');
            var rowIndex = $form.find('.dynamic-added').index($row);
            
            if (window.changedRecords && window.changedRecords[formId]) {
                window.changedRecords[formId].add(rowIndex);
                $row.addClass('row-modified').css('background-color', '#fff3cd');
            }
        });
        
        
        $(document).on('click', '.btn-save', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var modalIndex = $(this).data('modal-index');
            var contentId = $(this).data('content-id');
            saveFrom(modalIndex, contentId);
            
            return false;
        });

    function base64ToBlob(base64, mimeType) {
        var byteString = atob(base64.split(',')[1]);
        var ab = new ArrayBuffer(byteString.length);
        var ia = new Uint8Array(ab);
        for (var i = 0; i < byteString.length; i++) {
            ia[i] = byteString.charCodeAt(i);
        }
        return new Blob([ab], { type: mimeType });
    }
    
    async function compressImage(base64Src, maxWidth = 1200, quality = 0.8) {
        return new Promise((resolve, reject) => {
            var img = new Image();
            img.onload = function() {
                var canvas = document.createElement('canvas');
                var ctx = canvas.getContext('2d');
                
                var width = img.width;
                var height = img.height;
                
                if (width > maxWidth) {
                    height = (height * maxWidth) / width;
                    width = maxWidth;
                }
                
                canvas.width = width;
                canvas.height = height;
                
                ctx.drawImage(img, 0, 0, width, height);
                
                canvas.toBlob(function(blob) {
                    resolve(blob);
                }, 'image/jpeg', quality);
            };
            img.onerror = reject;
            img.src = base64Src;
        });
    }
    
    async function processBase64ImagesInHtml(html) {
        var tempDiv = $('<div>').html(html);
        var images = tempDiv.find('img[src^="data:image"]');
        
        for (var i = 0; i < images.length; i++) {
            var $img = $(images[i]);
            var base64Src = $img.attr('src');
            
            try {
                var blob = await compressImage(base64Src);
                
                var formData = new FormData();
                formData.append('image', blob, 'image_' + Date.now() + '.jpg');
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                
                var response = await $.ajax({
                    url: '{{ route("doc.process_base64_image") }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false
                });
                
                if (response.success && response.url) {
                    console.log('Image', i+1, 'uploaded successfully, replacing with URL');
                    $img.attr('src', response.url);
                } else {
                    console.warn('Failed to upload image', i+1);
                }
            } catch (error) {
                console.error('Error processing image', i+1, ':', error);
            }
        }
        
        return tempDiv.html();
    }
    async function saveFrom(modalIndex, contentId) {
        var $form = $('#form_id_' + contentId);
        var formId = $form.attr('id');
        var $submitBtn = $form.find('.btn-save');
        var originalBtnText = $submitBtn.html();
        $submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Processing images...');
        
        var changedData = [];
        if (window.changedRecords && window.changedRecords[formId]) {
            for (let rowIndex of window.changedRecords[formId]) {
                var $row = $form.find('.dynamic-added').eq(rowIndex);
                var textId = $row.find('input[name="text_id[]"]').val();
                var responsibility = $row.find('.res_list').val();
                var $richTextArea = $row.find('.rich_list');
                var richText = $richTextArea.summernote('code');
                
                richText = await processBase64ImagesInHtml(richText);
                
                changedData.push({
                    text_id: textId,
                    responsibility: responsibility,
                    rich_text: richText
                });
            }
        }
        
        $submitBtn.html('<span class="spinner-border spinner-border-sm"></span> Saving...');
        var formData = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            content_id: contentId,
            document_id: $form.find('input[name="document_id"]').val(),
            title: $form.find('input[name="title"]').val(),
            changed_records: JSON.stringify(changedData),
            has_changes: changedData.length > 0 ? '1' : '0'
        };
        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                $('#successBtn' + modalIndex).show().delay(3000).fadeOut();
                
                window.changedRecords[formId] = new Set();
                $form.find('.row-modified').removeClass('row-modified').css('background-color', '');
                
                setTimeout(function() {
                    location.reload();
                }, 2000);
            },
            error: function(xhr) {
                var $dangerBtn = $('#dangerBtn' + modalIndex);
                $dangerBtn.empty();
                
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    $.each(xhr.responseJSON.errors, function(key, value) {
                        $dangerBtn.append('<li>' + value[0] + '</li>');
                    });
                } else {
                    $dangerBtn.append('<li>Error: ' + (xhr.responseJSON?.message || 'Failed to save changes') + '</li>');
                }
                
                $dangerBtn.show().delay(5000).fadeOut();
            },
            complete: function() {
                $submitBtn.prop('disabled', false).html(originalBtnText);
            }
        });
        
        return false;
    }
    });
    
</script>