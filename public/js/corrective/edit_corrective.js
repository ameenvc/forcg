
$(document).ready(function() {
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
                var results = [];
                $.each(data.items, function (index, item) {
                    results.push({
                        id: item.id,
                        text: item.issueid 
                            ? item.issueid + ' - ' + item.title 
                            : item.title
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
    // ================= EDIT MODE PRELOAD =================
    if (previous_ncr_reference && previous_ncr_reference !== '') {

        if (previous_ncr_reference == 0) {

            var option = new Option('Other', '0', true, true);
            $('#previous_ncr_reference')
                .append(option)
                .trigger('change');

        } else {

            $.ajax({
                url: window.location.origin + "/user/corrective/get-previous-ncr-details",
                type: "GET",
                data: { id: previous_ncr_reference },
                success: function (response) {

                    if (response && response.item) {

                        var text = response.item.issueid
                            ? response.item.issueid + ' - ' + response.item.title
                            : response.item.title;

                        var option = new Option(text, response.item.id, true, true);

                        $('#previous_ncr_reference')
                            .append(option)
                            .trigger('change');
                    }
                }
            });
        }
    }
    // Show/hide fields based on NCR question
    function toggleNcrFields() {
        if ($('[name="ncr_question"]:checked').val() === 'yes') {
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
    }
    $(document).on('change', '[name="ncr_question"]', toggleNcrFields);
    toggleNcrFields();
    
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