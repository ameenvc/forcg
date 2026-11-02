/**
 * --------------------------------
 * Datatable extension:
 *  - Drag & drop header of table
 *  - Header filter
 * --------------------------------
 */

/**
 *
 * Drag and drop functional
 *
 * @param tableSelector
 * @param ajaxUrl
 * @param callback
 *
 * @return void
 */
function enableDragDrop(tableSelector, ajaxUrl = null, callback = null) {
    $(document).ready(function() {
        let startIndex;
        let startId;
        let draggedTh;

        // Ensure that the specified table exists
        const $table = $(tableSelector);
        if ($table.length === 0) {
            console.error('Table not found:', tableSelector);
            return;
        }

        // Initialize drag events for the headers
        $table.find('thead td').attr('draggable', true).on('dragstart', function(e) {
            draggedTh = this;
            startIndex = $(this).index();
            startId = $(this).attr('data-column-id');
            $(this).addClass('dragging');
            e.originalEvent.dataTransfer.effectAllowed = 'move';
            e.originalEvent.dataTransfer.setData('text/plain', startIndex); // Set data for reference
        });

        $table.find('thead td').on('dragover', function(e) {
            e.preventDefault();
            e.originalEvent.dataTransfer.dropEffect = 'move';
        });

        function switchColumns(colIndex1, colIndex2) {
            // Swap column headers
            const $headers = $table.find('thead td');
            const $header1 = $headers.eq(colIndex1);
            const $header2 = $headers.eq(colIndex2);

            // Create temporary placeholders for swapping
            const $tempHeader = $('<td>').html($header1.html());
            $header1.html($header2.html());
            $header2.html($tempHeader.html());

            // Swap columns in table body
            $table.find('tbody tr').each(function() {
                const $cells = $(this).find('td');
                const $cell1 = $cells.eq(colIndex1);
                const $cell2 = $cells.eq(colIndex2);

                // Create temporary placeholders for swapping
                const $tempCell = $('<td>').html($cell1.html());
                $cell1.html($cell2.html());
                $cell2.html($tempCell.html());
            });
        }

        $table.find('thead td').on('drop', function(e) {
            e.preventDefault();
            const targetId = $(this).attr('data-column-id');
            if (draggedTh !== this) {
                let targetIndex = $(this).index();

                if (startId && targetId) {
                    switchColumns(startIndex, targetIndex);

                    if (ajaxUrl) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: ajaxUrl,
                            method: 'POST',
                            data: {
                                startIndex: startId,
                                targetIndex: targetId
                            },
                            success: function(response) {
                                // Call the callback function with the response from the server
                                if (typeof callback === 'function') {
                                    callback(response);
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('AJAX request failed:', status, error);
                            }
                        });
                    }

                    $(draggedTh).removeClass('dragging');
                }
            }
        });

        $table.find('thead td').on('dragend', function() {
            $(this).removeClass('dragging');
        });
    });
}

/**
 *  Filter on header functional
 *
 * @param tableSelector
 * @param filterData
 * @param columnName
 * @param columnPosition
 * @param filterPostfix
 *
 * @return void
 */
function enableHeaderFilter(tableSelector, filterData = '', columnName = '', columnPosition = 0, filterPostfix = '',strict_ness = false) {
    $(document).ready(function() {
        // Ensure that the specified table exists
        const $table = $(tableSelector);
        if ($table.length === 0) {
            console.error('Table not found:', tableSelector);
            return;
        }

        $table.one("preInit.dt", function () {
            let $tableD = $table.DataTable();
            const $columnId = columnName.replace(/ /g, '');
            let $optionData = filterData.split(',');
            let $optionText = "";
            for (let i = 0; i < $optionData.length; i++) {
                if ($optionData[i]) {
                    $optionText += `<option value="${$optionData[i]}">${$optionData[i]}</option>`;
                }
            }
            const $heading = $(`<select class="headingselect text-center" style="-webkit-appearance: none;min-width:fit-content;border:none;background-color:#fff;font-weight:600 !important;" id="${$columnId}_dropdown">
                <option value="">${columnName}&nbsp;&nbsp;&#9207;</option>` +
                $optionText +
                `</select>`);
            $table.find('thead td').eq(columnPosition).html($heading);
            $("#" + $columnId + "_dropdown").on('change', function () {
                let firstOption = $('#'+$columnId+'_dropdown option:first');
                if ($(this).val()) {
                    if(strict_ness){
                        $tableD.columns(columnPosition).search('^'+$(this).val()+'$', true, false).draw();
                    }else{
                        $tableD.columns(columnPosition).search(filterPostfix ? $(this).val() + filterPostfix : $(this).val()).draw();
                    }
                    $(this).addClass('text-primary');
                    $("#" + $columnId + "_dropdown option").css('color', 'black')
                } else {
                    $tableD.column(columnPosition).search('').draw();
                    $(this).removeClass('text-primary');
                    $('#'+$columnId+'_dropdown').val('');
                }
                firstOption.html(`${columnName}&nbsp;&nbsp;&#9207;`);
            });
        });
    });
}

/**
 * Get the index of column
 *
 * @param tableSelector
 * @param headerName
 *
 * @return {number}
 */
function getColumnIndexByHeaderName(tableSelector, headerName) {
    // Ensure that the specified table exists
    const $table = $(tableSelector);
    if ($table.length === 0) {
        console.error('Table not found:', tableSelector);
        return -1;
    }

    let index = -1;
    $table.find('thead td').each(function (i) {
        if ($(this).text().trim() === headerName.trim()) {
            index = i;
            return false;
        }
    });

    return index;
}

/**
 *  Remove column on header functional
 *
 * @param tableSelector
 * @param columnName
 * @param columnIndex
 *
 * @return {number}
 */
function enableRemoveColumn(tableSelector, columnName = '', columnIndex = -1) {
    $(document).ready(function() {
        // Ensure that the specified table exists
        const $table = $(tableSelector);
        if ($table.length === 0) {
            console.error('Table not found:', tableSelector);
            return -1;
        }
        if (columnIndex !== -1) {
            $table.find('thead td').eq(columnIndex).addClass('header-arrow');
            $table.find('thead td').eq(columnIndex).click(function(event) {
                if (!$(event.target).hasClass('headingselect')) {
                    let dropdown = $('#dropdownMenucol');
                    let position = $(this).offset();
                    let headerHeight = $(this).outerHeight();
                    let headerWidth = $(this).outerWidth();
                    let columnCenter = position.left + (headerWidth / 2);
                    dropdown.css({
                        top: position.top - dropdown.outerHeight() + 10,
                        left: columnCenter - (dropdown.outerWidth() / 2), // Center the dropdown
                        display: 'block'
                    });
                    
                    $(document).on('click', function(e) {
                        if (!$(e.target).closest(tableSelector + ' thead td, #dropdownMenucol').length) {
                            dropdown.hide();
                        }
                    });
                    event.stopPropagation();
                }
            });
        } else {
            console.error('Column "' + columnName + '" not foundhj.');
        }

    });
}