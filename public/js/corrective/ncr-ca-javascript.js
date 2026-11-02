function toggleTab(tabName) {
    localStorage.setItem('activeTab', tabName);
    $('#dropdownMenu').scrollTop(0);
    if(tabName==='#tab-1'){
        $('#tabselected').val('a2');
        $('#tvalue1').val('tvalue2');
        $('.open_corrective_export').removeClass('d-none');
        $('.my_corrective_export').addClass('d-none');
        $('.closed_corrective_export').addClass('d-none');
    }else if(tabName==='#tab-2'){
        $('#tabselected').val('a3');
        $('#tvalue1').val('tvalue3');
        $('.open_corrective_export').addClass('d-none');
        $('.my_corrective_export').removeClass('d-none');
        $('.closed_corrective_export').addClass('d-none');
    }else if(tabName==='#tab-3'){
        $('#tabselected').val('a4');
        $('#tvalue1').val('tvalue4');
        $('.open_corrective_export').addClass('d-none');
        $('.my_corrective_export').addClass('d-none');
        $('.closed_corrective_export').removeClass('d-none');
    }
}
let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
function theAlert(id) {
    let url = window.location.origin + `/correctiveActionAttachment/${id}`;
    Swal.fire({
        title: 'Select files'
        , html: '<label>Action Files: </label><input type="file"  class="swal2-file" accept="image/*,.pdf,.doc,.docs,.xlsx,.xls,.csv" multiple>' +
            '<label>Issue Files: </label><input type="file"  class="swal2-file" accept="image/*,.pdf,.doc,.docs,.xlsx,.xls,.csv" multiple>'
        , showCancelButton: true
        , confirmButtonText: 'Upload'
        , onBeforeOpen: () => {
            $(".swal2-file").change(function() {
                var reader = new FileReader();
                reader.readAsDataURL(this.files);
            });
        }
    }).then((file) => {
        if (file.value) {
            var formData = new FormData();
            var files = $('.swal2-file')[0].files;
            var issueFiles = $('.swal2-file')[1].files;
            $.each(files, function(i, x) {
                formData.append('fileToUpload[]', x);
            });
            $.each(issueFiles, function(i, x) {
                formData.append('fileToUpload1[]', x);
            });
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN
                }
                , method: 'post'
                , url: url
                , data: formData
                , processData: false
                , contentType: false
                , success: function(resp) {
                    Swal.fire({
                        title: 'Uploaded'
                        , text: 'Your files have been uploaded'
                        , icon: 'success'
                    })
                }
                , error: function() {
                    Swal.fire({
                        title: 'Oops'
                        , text: 'Something went wrong!'
                        , icon: 'error'
                    })
                }
            })
        }
    });
}
function autoResizeDiv(height) {
    document.getElementById('content').style.height = (height) + 'px';
}
window.onresize = autoResizeDiv;
autoResizeDiv(window.innerHeight - 100);
$('body').on('click', '.view-moc-detail', function () {
    const id = $(this).attr('data-route');
    $.ajax({
        url : window.location.origin+`/user/context/changelog/${id}/show`,
        type: 'GET',
        success: function (data) {
            if (data.success) {
                $('#view-detail').html(data.view)
                const modalEle = $(`#viewModal${data.id}`);
                modalEle.modal('show');
            } else {
                alert('Data is not found')
            }
        },
        error: function () {
            alert('Handle error');
        }
    })
});
$('body').on('click', '.btn-view-detail', function () {
    const id = $(this).attr('data-route');
    $.ajax({
        url : window.location.protocol + "//" + window.location.host+`/user/corrective/corrissues/${id}/show`,
        type: 'GET',
        success: function (data) {
            if (data.success) {
                $('.corrective-area').html(data.view)
                const modalEle = $(`#viewModal${data.id}`);
                modalEle.modal('show');
            } else {
                alert('Data is not found')
            }
        },
        error: function () {
            alert('Handle error');
        }
    })
});
$(".viewDetails").on('click', function() {
    var id = $(this).attr('data-route');
    $.ajax({
        url: window.location.protocol + "//" + window.location.host+"/show/" + id + "/" + "corrective"
        , type: 'get'
        , data: {
            id: id
        }
        , dataType: "json"
        , success: function(response) {
            response.data.responsibleUser ? $('#responsible' + id).attr('src', '/storage/profile/' + response.data.responsibleUser.profile_picture) : $('#responsible').attr('src', '');
            response.data.postedByUser ? $('#postedbyUser' + id).attr('src', '/storage/profile/' + response.data.postedByUser.profile_picture) : $('#postedbyUser').attr('src', '');
        }
    });
});
$("#del").click(function() {
    var dataArr = new Array();
    if ($('input:checkbox:checked').length > 0) {
        $('input:checkbox:checked').each(function() {
            dataArr.push($(this).attr('id'));
            $.ajax({
                type: 'get'
                , url: window.location.origin+'/user/corrective/delete'+ "?val=" + $(this).attr('id')
                , success: function(response) {
                    window.location.href = window.location.origin+'/user/corrective/corrissues';
                }
                , error: function(response) {
                    alert(errResponse);
                }
            });
        });

    } else {
        alert('No Record Selected');
    }
});

function ifChecked(id) {
    if ($('#foreseeable_yes_' + id).is(":checked")) {
        $('#prevent_block_' + id).removeClass('d-none');
        $('#prevent_block_' + id).addClass('d-inline-block');
    } else if ($('#foreseeable_no_' + id).is(":checked")) {
        $('#prevent_block_' + id).addClass('d-none');
        $('#prevent_block_' + id).removeClass('d-inline-block');
    }

}
        
function in_array(needle, haystack) {
    var length = haystack.length;
    for (var i = 0; i < length; i++) {
        if (haystack[i] == needle)
            return true;
    }
    return false;
}


function displayDetails(id) {
    let factors_details_selector = $('#factors_details' + id);
    if (in_array('others', $('#factors' + id).val())) {
        factors_details_selector.removeClass('d-none');
        factors_details_selector.addClass('d-inline-block');
    } else {
        factors_details_selector.removeClass('d-inline-block');
        factors_details_selector.addClass('d-none');
    }
}

function displayInfluenceDetails(id) {
    let influence_details_selector = $('#influence_details' + id);
    if (in_array('others', $('#influence' + id).val())) {
        influence_details_selector.removeClass('d-none');
        influence_details_selector.addClass('d-inline-block');
    } else {
        influence_details_selector.removeClass('d-inline-block');
        influence_details_selector.addClass('d-none');
    }

}

$('body').on('click', '.js-change-investigation-team', function () {
    const url = $(this).attr('data-url')
    $.ajax({
        url,
        type: 'GET',
        success: function (data) {
            if (data.success) {
                $('.corrective-area').html(data.view)
                const modalEle = $(`#changeInvestigationModal${data.id}`);
                modalEle.modal('show');
                modalEle.find('.js-select2').each(function() {
                    const limitSelection = $(this).attr('data-limit-selection')
                    const isMultiple = $(this).attr('data-multiple')
                    $(this).select2({
                        width: '100%',
                        tags: true,
                        multiple: isMultiple !== "false",
                        maximumSelectionLength: limitSelection ? limitSelection : 0
                    });
                });
            } else {
                alert('Data is not found')
            }
        },
        error: function () {
            alert('Handle error');
        }
    })
});
$(document).ready(function () {
    let activeTab = localStorage.getItem('activeTab');
    if(activeTab){
        // Activate tab
        $('.nav-link[href="' + activeTab + '"]').tab('show');
        // Call your function to handle UI changes
        toggleTab(activeTab);
    } else {
        toggleTab('#tab-1');
    }
});

function showInvestigationModal(id, url) {
    $.ajax({
        url,
        type: 'GET',
        success: function (data) {
            if (data.success) {
                $('.corrective-area').html(data.view)
                const modalEle = $(`#investigationModal${data.id}`);
                modalEle.modal('show');
                let factors_array = $('#factors' + id).val();
                let influence_array = $("#influence" + id).val();
                let factors_details_selector = $('#factors_details' + id);
                let influence_details_selector = $('#influence_details' + id);

                if (in_array('others', factors_array)) {
                    factors_details_selector.removeClass('d-none').addClass('d-inline-block');
                } else {
                    factors_details_selector.removeClass('d-inline-block').addClass('d-none');
                }

                if (in_array('others', influence_array)) {
                    influence_details_selector.removeClass('d-none').addClass('d-inline-block');
                } else {
                    influence_details_selector.removeClass('d-inline-block').addClass('d-none');
                }

                if ($('#foreseeable_yes_' + id).is(":checked")) {
                    $('#prevent_block_' + id).removeClass('d-none');
                    $('#prevent_block_' + id).addClass('d-inline-block');
                } else if ($('#foreseeable_no_' + id).is(":checked")) {
                    $('#prevent_block_' + id).addClass('d-none');
                    $('#prevent_block_' + id).removeClass('d-inline-block');
                }
                modalEle.find('.js-select2').each(function() {
                    const limitSelection = $(this).attr('data-limit-selection')
                    $(this).select2({
                        width: '100%',
                        tags: true,
                        multiple: true,
                        maximumSelectionLength: limitSelection ? limitSelection : 0
                    });
                });
            } else {
                alert('Data is not found')
            }
        },
        error: function () {
            alert('Handle error');
        }
    })
}
function removehidecolumn(id, url) {
    $('#remove_column_id').val(id);
    $('#remove_column_url').val(url);
}
//-----remove datatable column  starts----//
$(document).ready(function() {
    $('#remove_column_ajax').submit(function(event) {
        event.preventDefault();
        var formData = $(this).serialize();
        var url = $('#remove_column_url').val();
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            url: url,
            data: formData
            ,success: function(response) {
                if(response.status == 200){
                    window.location.reload();
                }
            }
            ,error: function(err) {
                if(err.status === 500) {
                    $('#columnremovaldanger').css('display', 'block');
                }else if (err.status === 422) {
                    var error_text='';
                    $.each(err.responseJSON.errors, function(i) {
                        $.each(err.responseJSON.errors[i], function(key, val) {
                            error_text += '<li style="font-size:12px;">' + val + '</li>';
                        });
                    });
                    $('#columnremovaldangernotremoved').html(error_text);
                    $('#columnremovaldanger').hide();
                }
            }
        });
    });
});
//---------ends remove datatable column  ----//
$(document).ready(function () {

    function applyCategoryFilter(selectedValue) {

        if (selectedValue === 'product') {
            $('.product-ncr-section').removeClass('d-none');
            $('.process-ncr-section').addClass('d-none');
            $('.vendor-ncr-section').addClass('d-none');
            $('.productCategoryHeader').show(); 
            $('.processCategoryHeader').hide(); 
            $('.vendorCategoryHeader').hide();
            $('#clearFilterBtn').removeClass('d-none');
        } else if (selectedValue === 'process') {
            $('.product-ncr-section').addClass('d-none');
            $('.process-ncr-section').removeClass('d-none');
            $('.vendor-ncr-section').addClass('d-none');
            $('.productCategoryHeader').hide(); 
            $('.processCategoryHeader').show(); 
            $('.vendorCategoryHeader').hide();
            $('#clearFilterBtn').removeClass('d-none');
        } else if (selectedValue === 'vendor') {
            $('.product-ncr-section').addClass('d-none');
            $('.process-ncr-section').addClass('d-none');
            $('.vendor-ncr-section').removeClass('d-none');

            $('.productCategoryHeader').hide(); 
            $('.processCategoryHeader').hide(); 
            $('.vendorCategoryHeader').show();
            $('#clearFilterBtn').removeClass('d-none');
        } else {
            $('#clearFilterBtn').addClass('d-none');
            $('.productCategoryHeader').hide(); 
            $('.processCategoryHeader').hide(); 
            $('.vendorCategoryHeader').hide();
            $('.product-ncr-section').addClass('d-none');
            $('.process-ncr-section').addClass('d-none');
            $('.vendor-ncr-section').addClass('d-none');
        }
    }
    // On page load
    let selectedValue = $('.filter-radio:checked').val();
    applyCategoryFilter(selectedValue);
    // On radio change
    $('.filter-radio').on('change', function () {
        let selectedValue = $(this).val();

        applyCategoryFilter(selectedValue);
        $.ajax({
            url: window.location.origin + '/user/corrective/category-table/last-selected-category',
            type: 'GET',
            data: {
                filter_value: selectedValue,
                _token: CSRF_TOKEN
            },
            success: function (response) {
                if (response.status === 200) {
                    console.log('Updated');
                } else {
                    alert('Failed to filter data');
                }
            },
            error: function () {
                alert('An error occurred while filtering data');
            }
        });
        
    });
});

$('.export-link').on('click', function (e) {
    e.preventDefault();
    let baseUrl = $(this).attr('href');
    let typeForExport = $('#typeForExport').val();
    const tabMap = { 'a2': 'open', 'a3': 'my_open', 'a4': 'closed' };
    const tab    = tabMap[document.getElementById('tabselected').value] || 'open';
    baseUrl += `/${tab}`;
    let ids = [];
    if (tab === 'open') {
        ids = getVisibleIds('#tabledata-open',2);
    } else if (tab === 'my_open') {
        ids = getVisibleIds('#tabledata-myopen');
    } else if (tab === 'closed') {
        ids = getVisibleIds('#tabledata-closed');
    }
    let finalUrl = baseUrl+'?ids='+encodeURIComponent(JSON.stringify(ids));
    window.location.href = finalUrl;
});
function getVisibleIds(tableSelector,columnIndex=1) {
    let table = $(tableSelector).DataTable();
    let ids = [];
    table.rows({ search: 'applied' }).every(function () {
        let rowData = this.data();
        ids.push(rowData[columnIndex]);
    });
    return ids;
}