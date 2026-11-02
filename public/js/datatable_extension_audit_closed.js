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
 * Function to create the tree structure
 *
 * @param dates
 * @return {{}}
 */
function createTree(dates) {
    const tree = {};

    // Build the tree structure
    dates.forEach(date => {
        if (date) {
            const [year, month, day] = date.split('-');

            if (!tree[year]) {
                tree[year] = {};
            }
            if (!tree[year][month]) {
                tree[year][month] = [];
            }
            tree[year][month].push(day);
            tree[year][month].sort()
        }
    });

    return tree;
}

/**
 * Function to build the HTML for the tree view
 *
 * @param tree
 * @param containerName
 * @return {string}
 */
function buildTreeHtml(tree, containerName, columnName) {
    let html = '';
    html += `<div class="${containerName}">
            <div class="filter-container">
                <div class="filter-header">${columnName} <span>⏷</span></div>
                <div class="filter-content">`;
    for (const year in tree) {
        html += `<div class="date-filter year" data-filter="${year}">
                    <span class="expand-icon expand">+</span> 
                    <input type="checkbox" class="cke-filter" data-name="year" value="${year}"> 
                    ${year}
                </div>`;
        html += `<div class="nested year">`;
        const monthHasBeenSorted = Object.keys(tree[year]).sort()
        monthHasBeenSorted.forEach((month) => {
            html += `<div class="date-filter month" data-filter="${month}-${year}">
                        <input type="checkbox" class="cke-filter"
                            data-name="month" data-year="${year}"
                            value="${month}-${year}"> ${month}
                      </div>`;
            html += `<div class="nested">`;
            html += `</div>`;
        })
        html += `</div>`;
    }
    html += `</div></div></div>`;

    return html;
}

/**
 *  Filter on header functional
 *
 * @param tableSelector
 * @param columnKey
 * @param filterData
 * @param columnName
 * @param columnPosition
 * @param option
 *
 * @return void
 */
function enableHeaderFilter(tableSelector, columnKey, filterData = '', columnName = '', columnPosition = 0, option = {}) {
    $(document).ready(function() {
        // Ensure that the specified table exists
        const $table = $(tableSelector);
        if ($table.length === 0) {
            console.error('Table not found:', tableSelector);
            return;
        }

        const filterPostfix = 'filter_postfix' in option ? option['filter_postfix'] : '';
        const isDateFilter = 'is_date_filter' in option ? option['is_date_filter'] : false;

        $table.one("preInit.dt", function () {
            let $tableD = $table.DataTable();
            const $columnId = columnName.replace(/ /g, '')
            let $optionData = filterData.split(',');
            let $optionText = "";
            for (let i = 0; i < $optionData.length; i++) {
                if ($optionData[i]) {
                    $optionText += `<option value="${$optionData[i]}">${$optionData[i]}</option>`;
                }
            }
            let $heading = '';
            if (isDateFilter) {
                const dates = filterData.split(",")
                const tree = createTree(dates);
                $heading = buildTreeHtml(tree, columnKey, columnName);
            } else {
                $heading = $(`<select class="headingselect" style="width:100px;border:none;" id="${$columnId}_dropdown">
                <option value="">${columnName}&nbsp;&nbsp;&#9207;</option>` +
                    $optionText +
                    `</select>`);
            }

            $table.find('thead td').eq(columnPosition).html($heading);
            if (isDateFilter) {
                const dateFilterElement = $('.' + columnKey);
                dateFilterElement.find('.filter-header').click(function() {
                    $(this).next('.filter-content').slideToggle();
                    // $(this).find('span').text($(this).find('span').text() === '▼' ? '▲' : '▼'); // Toggle arrow
                });
                dateFilterElement.find('.expand').click(function(e) {
                    e.stopPropagation();
                    const dateFilter = $(this).closest('.date-filter')
                    if (dateFilter.next('.nested').length) {
                        dateFilter.next('.nested').slideToggle();
                        $(this).text($(this).text() === '-' ? '+' : '-');
                    }
                });
                dateFilterElement.find(".cke-filter").change(function() {
                    const eleName = $(this).attr('data-name')
                    const value = $(this).val();
                    let searchDataList = []
                    if(this.checked) {
                        if (eleName === "year") {
                            dateFilterElement.find(".cke-filter[data-year=" + value + "]")
                                .prop('checked', true);
                        }
                        else if (eleName === "month") {
                            dateFilterElement.find(".cke-filter[data-month=" + value + "]")
                                .prop('checked', true);
                        }
                    } else {
                        if (eleName === "year") {
                            dateFilterElement.find(".cke-filter[data-year=" + value + "]")
                                .prop('checked', false);
                        } else if (eleName === "month") {
                            dateFilterElement.find(".cke-filter[data-month=" + value + "]")
                                .prop('checked', false);
                        }
                    }

                    const yearHasBeenSelected = $(this).attr('data-year');
                    const monthHasBeenSelected = $(this).attr('data-month');

                    let isSelectAllMonth = true;
                    dateFilterElement.find(".cke-filter[data-month=" + monthHasBeenSelected + "]").each(function (index, item) {
                        if(!$(item).is(":checked")) {
                            isSelectAllMonth = false;
                            return false;
                        }
                    })
                    if (isSelectAllMonth) {
                        dateFilterElement.find(".cke-filter[data-name=month][value="+ monthHasBeenSelected + "]")
                            .prop('checked', true);
                    } else {
                        dateFilterElement.find(".cke-filter[data-name=month][value="+ monthHasBeenSelected + "]")
                            .prop('checked', false);
                    }
                    let isSelectAllYear = false;
                    dateFilterElement.find(".cke-filter[data-year=" + yearHasBeenSelected + "]").each(function (index, item) {
                        if($(item).is(":checked")) {
                            isSelectAllYear = true;
                            return false;
                        }
                    })
                    if (isSelectAllYear) {
                        dateFilterElement.find(".cke-filter[data-name=year][value="+ yearHasBeenSelected + "]")
                            .prop('checked', true);
                    } else {
                        dateFilterElement.find(".cke-filter[data-name=year][value="+ yearHasBeenSelected + "]")
                            .prop('checked', false);
                    }

                    dateFilterElement.find(".cke-filter[data-name=month]").each(function (index, item) {
                        if($(item).is(":checked")) {
                            searchDataList.push($(item).val())
                        }
                    })
                    const searchKeyWord = searchDataList.join('|');
                    $tableD.columns(columnPosition).search(filterPostfix ? searchKeyWord + filterPostfix : searchKeyWord, true, false).draw()
                    $(this).closest('.filter-content').slideToggle();
                });
            } else {
                $("#" + $columnId + "_dropdown").on('change', function () {
                    if ($(this).val()) {
                        $tableD.columns(columnPosition).search(filterPostfix ? $(this).val() + filterPostfix : $(this).val()).draw();
                        $(this).addClass('text-primary');
                        $("#" + $columnId + "_dropdown option").css('color', 'black')
                    } else {
                        location.reload();
                    }
                });
            }
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
            $table.find('thead td').eq(columnIndex).mouseover(function(event) {
                let dropdown = $('#dropdownMenucol');
                let position = $(this).offset();
                let headerHeight = $(this).outerHeight();
                let headerWidth = $(this).outerWidth();
                dropdown.css({
                    top: position.top - dropdown.outerHeight() + 10,
                    left: position.left + headerWidth - dropdown.outerWidth() + 10,
                    display: 'block'
                });
                dropdown.find('button').attr('data-column-index', columnIndex);
                event.stopPropagation();
            });
            $table.find('thead td').eq(columnIndex).mouseleave(function(e) {
                const dataColumnIndex = $(e.relatedTarget).attr('data-column-index');
                if (dataColumnIndex != columnIndex) {
                    $('#dropdownMenucol').hide();
                }
            });
            $('#dropdownMenucol').mouseleave(function(e) {
                const dataColumnIndex = $(e.relatedTarget).attr('data-column-index');
                if (dataColumnIndex != columnIndex) {
                    $('#dropdownMenucol').hide();
                }
            });
        } else {
            console.error('Column "' + columnName + '" not found.');
        }

    });
}