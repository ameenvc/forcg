@php
    $iconPlus = '<i class="fa fa-plus"></i>';
    $iconMinus = '<i class="fa fa-minus"></i>';
@endphp

<!-- Shared accordion component -->
<input type="hidden" id="modulepageid" value="">
@foreach(['Target Pages' => $targetPages, 'Display Pages' => $displayPages] as $sectionTitle => $sectionData)
<div class="form-group col-sm-12 mb-2">
    <label class="col-sm-12 col-form-label" style="font-size: 13px;">
        {{ $sectionTitle }} <span class="text-danger">*</span>
    </label>
    <div class="col-sm-12">
        @foreach($sectionData as $groupName => $pages)
            @php 
                $groupId = Str::slug($sectionTitle . '-' . $groupName); 
                $inputName = $sectionTitle === 'Target Pages' ? 'target_pages[]' : 'display_pages[]';
                $currentPage = $sectionTitle === 'Target Pages' ? $target??null : $display??null;
            @endphp
            <div class="rounded p-0">
                <div class="d-flex justify-content-between align-items-center" style="font-size: 13px;">
                    <span>{{ $groupName }}</span>
                    <button class="btn btn-sm btn-light bg-white p-1 toggle-group-btn" type="button" style="border:none;" data-target="#collapse-{{ $groupId }}">
                        <i class="fa fa-plus" style="color:#5e6267;font-size:13px;"id="icon-{{ $groupId }}"></i>
                    </button>
                </div>
                <div id="collapse-{{ $groupId }}" style="font-size: 13px;display: none;">
                    <ul class="list-group" style="border: none;">
                        @foreach($pages as $page)
                            <li class="list-group-item d-flex align-items-center px-1 py-0" style="border: none;">
                                @if(isset($currentPage))
                                <input type="checkbox" style="font-size: 12px;height:15px;width:15px;" name="{{ $inputName }}" value="{{ $page->id }}" class="mr-2" id="page-{{ $page->id }}" {{in_array($page->id,$currentPage)?'checked':''}}>
                                @else
                                <input type="checkbox" style="font-size: 12px;height:15px;width:15px;" name="{{ $inputName }}" value="{{ $page->id }}" class="mr-2" id="page-{{ $page->id }}">
                                @endif
                                <label for="page-{{ $page->id }}" class="mb-0 flex-grow-1" style="font-size: 13px;font-weight:normal;">
                                    {{ $page->page_name }}
                                    <button type="button" class="btn btn-sm btn-light bg-white preview-btn" data-pageid="{{$page->id}}" style="border:none;" title="View">
                                        <i class="far fa-eye-slash" style="font-size: 13px;font-weight:lighter;"></i>
                                    </button>
                                </label>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endforeach
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    $(document).ready(function() {
        var savedOrders = {};
        $('.toggle-group-btn').on('click', function () {
            const icon = $(this).find('i');
            const targetId = $(this).data('target');
            const target = $(targetId);

            target.slideToggle('fast');

            if (icon.hasClass('fa-plus')) {
                icon.removeClass('fa-plus').addClass('fa-minus');
            } else {
                icon.removeClass('fa-minus').addClass('fa-plus');
            }
        });
        $('[id^="collapse-"]').each(function () {
            const groupDiv = $(this);
            const anyChecked = groupDiv.find('input[type="checkbox"]:checked').length > 0;

            if (anyChecked) {
                groupDiv.show();
                const toggleBtn = $(`.toggle-group-btn[data-target="#${groupDiv.attr('id')}"]`);
                const icon = toggleBtn.find('i');

                icon.removeClass('fa-plus').addClass('fa-minus');
            }
        });
        $('.preview-btn').on('click',function(){
            const $btn = $(this);
            const $icon = $btn.find('i');
            const pageid = $btn.data('pageid');
            const isCurrentlyPreviewed = $icon.hasClass('fa-eye');
            const $is_edit_page = $('#is_edit_page').val();
            const $field_id = $('#id').val() || '';
            const isPageChecked = $(`#page-${pageid}`).is(':checked');
            let $fieldname,$fieldtype,$fieldplaceholder,$mandatory,$dropdown_options,$modulecalibration;
            if($is_edit_page=='false'){
                if(isPageChecked){
                    $fieldname = $('#fieldname').val();
                    $fieldtype = $('#fieldtype').val();
                    $fieldplaceholder = $('#fieldplaceholder').val();
                    $mandatory = $('#example-switch-custom1').is(':checked') ? 0 : 1;
                    $dropdown_options = $('.dropdown_option').map(function() {
                        return $(this).val();
                    }).get();
                }else{
                    $fieldname = '';
                    $fieldtype = '';
                    $fieldplaceholder = '';
                    $mandatory = '';
                    $dropdown_options = '';
                }
            }else{
                if(isPageChecked){
                    $fieldname = $('#fieldnameedit').val();
                    $fieldtype = $('#fieldtypeedit').val();
                    $fieldplaceholder = $('#fieldplaceholderedit').val();
                    $mandatory = $('#example-switch-custom1').is(':checked') ? 0 : 1;
                    $dropdown_options = $('.dropdown_option_edit').map(function() {
                        return $(this).val();
                    }).get();
                }else{
                    $fieldname = '';
                    $fieldtype = '';
                    $fieldplaceholder = '';
                    $mandatory = '';
                    $dropdown_options = '';
                }
            }
            if (isCurrentlyPreviewed) {
                $('#previewContainer').empty().html(`<div class="d-flex justify-content-center align-items-center h-100" style="min-height: 150px;">
                    <p class="text-center m-0" style="font-size: 14px;">
                        Click the '<i class="far fa-eye-slash text-center" style="font-size:10px;"></i>' icon next to pages, to view a quick preview of your created Custom Fields.
                    </p>
                </div>`);
                $icon.removeClass('fa-eye').addClass('fa-eye-slash');
                $('#modulepageid').val('');
            }else {
                $('.preview-btn i').removeClass('fa-eye').addClass('fa-eye-slash');
                $.ajax({
                    type: 'GET',
                    url: `/admin/custom_field/custom-fields-preview`,
                    data: {
                        fieldname: $fieldname,
                        fieldtype: $fieldtype,
                        fieldplaceholder: $fieldplaceholder,
                        mandatory: $mandatory,
                        dropdown_options: $dropdown_options,
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        page_id: pageid,
                        is_edit_form: $is_edit_page,
                        field_id: $field_id
                    },
                    success: function(response) {
                        $('#previewContainer').html(response.preview);
                        $icon.removeClass('fa-eye-slash').addClass('fa-eye');
                        $('html, body').animate({ scrollTop: 0 }, 600);
                        $('#modulepageid').val(pageid);
                        initSortable(response.savedOrder || []);
                    },
                    error: function(xhr) {
                        alert('Failed to load preview.');
                    }
                });
            }
        });
        $('form').on('submit', function () {
            $('#all_field_orders_input').val(JSON.stringify(savedOrders));
        });
        function initSortable(savedOrder) {
            document.querySelectorAll('.sortable-field').forEach(container => {
                let lastOrder = [];
                let hasChanged = false;
                let isDragging = false;
                let id = $('#modulepageid').val();
                new Sortable(container, {
                    animation: 150,
                    ghostClass: 'dragging',
                    handle: '.drag-handle',
                    scroll: true,
                    scrollSensitivity: 30,
                    scrollSpeed: 10,
                    bubbleScroll: true,
                    forceFallback: true,

                    onStart: function () {
                        isDragging = true;
                    },

                    onEnd: function () {
                        isDragging = false;
                        let newOrder = [];
                        $('.sortable-field').children().each(function () {
                            newOrder.push($(this).data('index'));
                        });

                        // Mark changed if order is different
                        if (JSON.stringify(newOrder) !== JSON.stringify(lastOrder)) {
                            hasChanged = true;
                        }
                    }
                });

                // Detect mouse leave after dragging is finished
                $(container).on('mouseleave', function () {
                    if (!hasChanged || isDragging) return;                    
                    // Delay to avoid false triggers during auto-scroll
                    setTimeout(() => {
                        if (isDragging || !hasChanged) return;

                        let newOrder = [];
                        
                        $('.sortable-field').children('[data-index]').each(function () {
                            newOrder.push(String($(this).data('index')));
                        });
                        
                        Swal.fire({
                            title: 'Save Changes?',
                            text: "Do you want to save the new field order?",
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, save it!',
                            cancelButtonText: 'No, discard',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                savedOrders[id] = newOrder;
                                if($('#is_edit_page').val()=='false'){
                                    Swal.fire('Note', 'Your changes will be saved after successful field creation.', 'info');
                                }else{
                                    Swal.fire('Note', 'Your changes will be saved after successful field update.', 'info');
                                }
                                isDragging = false;
                                hasChanged = false;
                            }else {
                                // If user cancels, reset the last order
                                lastOrder = [];
                                hasChanged = false;
                                isDragging = false;
                            }
                        });
                    }, 500); // 0.5-second delay to ignore false leaves during scroll
                });
            });
            var $container = $('.sortable-field');
            var $allFields = $container.children('.form-group');
            if($allFields.length==0){
                $allFields = $container.children('tr');
            }
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

        }
    });
</script>