$(document).ready(function() {
    // Show/hide fields based on NCR question
    $('[name="ncr_question"]').change(function() {
        if ($(this).val() === 'yes') {
            $('#no_of_occurrence_div').removeClass('d-none')
            $('#previous_ncr_reference_div').removeClass('d-none');
        } else {
            $('#no_of_occurrence').val('');
            $('#previous_ncr_reference').val(null).trigger('change');
            $('#no_of_occurrence_div').addClass('d-none')
            $('#previous_ncr_reference_div').addClass('d-none');
            $('#otherNcrReferenceDiv').addClass('d-none');
            $('#other_ncr').val('');
        }
    });
    //Fetching previous NCR reference for select2 dropdown
    $('#previous_ncr_reference').select2({
        ajax: {
            url: window.location.origin +"/user/corrective/get-previous-ncr-details",
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            cache: true,
            dataType: 'json',
            data: function (params) {
                return {
                    search: params.term 
                };
            },
            processResults: function (data) { 
                var results = []
                $.each(data.items, function (index,item) {
                    results.push({
                        id: item.id,
                        text: item.issueid ? item.issueid + ' - ' + item.title : '' + item.title
                    });
                });
                return {
                    results: results,
                    pagination: {
                        more: data.hasMore
                    }
                };
            }
        },
        placeholder: 'Choose Previous NCR Reference'
    });
    // Show/hide other NCR reference input based on dropdown selection
    $('#previous_ncr_reference').change(function() {
        if ($(this).val() === '0') {
            $('#otherNcrReferenceDiv').removeClass('d-none');
        } else {
            $('#otherNcrReferenceDiv').addClass('d-none');
            $('#other_ncr').val('');
        }
    });
    //--------------------------END-------------------------------
});