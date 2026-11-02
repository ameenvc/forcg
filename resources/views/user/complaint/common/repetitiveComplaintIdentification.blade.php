<script>
    $(document).ready(function() {
        //Fetching previous complaint reference for select2 dropdown
        var id;
        @if (isset($activeComplaintTemplate))
            id = {{$activeComplaintTemplate->id}};
        @endif
        @if (isset($data) && isset($data->complaint_template_id))
            id = {{$data->complaint_template_id}};
        @endif

        $('#previous_complaint_reference').select2({
            ajax: {
                url: window.location.origin +"/user/complaint/getPreviousComplaints?template_id="+id,
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
                            text: item.trackno ? item.trackno + ' - ' + item.title : '' + item.title
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
            placeholder: 'Select Complaint Reference'
        });

        // preload value (Blade controlled)
        @if(isset($data) && isset($data->previous_complaint_reference))
            @if ($data->previous_complaint_reference == '0')
                var option = new Option('Other', '0', true, true);
                $('#previous_complaint_reference').append(option).trigger('change');
            @elseif(isset($data->previous_complaint_ref))
                var previousId = "{{ $data->previous_complaint_ref['id'] }}";
                var previousText = "{{ $data->previous_complaint_ref['trackno'] }} - {{ $data->previous_complaint_ref['title'] }}";
                console.log('reached')
                var option = new Option(previousText, previousId, true, true);
                $('#previous_complaint_reference').append(option).trigger('change');
            @endif
        @endif

        // Show/hide fields based on repetitive complaint question
        $('[name="meta[previously_occured_complaint]"]').change(function() {
            if ($(this).val() === 'yes') {
                $('#no_of_occurrence_div').removeClass('d-none')
                $('#previous_complaint_reference_div').removeClass('d-none');
            } else {
                $('#no_of_occurrence').val('');
                $('#previous_complaint_reference').val(null).trigger('change');
                $('#no_of_occurrence_div').addClass('d-none')
                $('#previous_complaint_reference_div').addClass('d-none');
                $('#external_reference_div').addClass('d-none');
                $('#external_reference').val('');
            }
        });

        // Show/hide external reference input based on dropdown selection
        $('#previous_complaint_reference').change(function() {
            if ($(this).val() === '0') {
                $('#external_reference_div').removeClass('d-none');
            } else {
                $('#external_reference_div').addClass('d-none');
                $('#external_reference').val('');
            }
        });

        // condition when validation files
        if (old('meta.previously_occured_complaint')) {
            var oldPreviouslyOccurred = "{{ old('meta.previously_occured_complaint', isset($data) && isset($data->previously_occured_complaint) ? $data->previously_occured_complaint ?? 'no' : 'no') }}";
            var oldPreviousRef = "{{ old('previous_complaint_reference', isset($data) && isset($data->previous_complaint_reference) ? $data->previous_complaint_reference : '') }}";

            // Handle radio-based visibility
            if (oldPreviouslyOccurred === 'yes') {
                $('#no_of_occurrence_div').removeClass('d-none');
                $('#previous_complaint_reference_div').removeClass('d-none');
            } else {
                $('#no_of_occurrence_div').addClass('d-none');
                $('#previous_complaint_reference_div').addClass('d-none');
            }

            // Handle external reference visibility
            if (oldPreviousRef === '0') {
                $('#external_reference_div').removeClass('d-none');
            } else {
                $('#external_reference_div').addClass('d-none');
            }
        }

        $('#no_of_occurrence').on('paste', function (e) {
            let pasteData = (e.originalEvent || e).clipboardData.getData('text');

            // Allow empty
            if (!pasteData) return;

            // Check if it's a positive integer
            if (!/^[1-9]\d*$/.test(pasteData)) {
                e.preventDefault(); // block paste
            }
        });

        $('#no_of_occurrence').on('input', function () {
            console.log('reached')
            let value = $(this).val();

            // Allow empty
            if (value === '') return;

            // Remove anything that's not digits
            value = value.replace(/[^0-9]/g, '');

            // Remove leading zero
            value = value.replace(/^0+/, '');

            $(this).val(value);
        });
    });
</script>
