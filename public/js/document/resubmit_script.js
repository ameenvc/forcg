$("#no_method").on('change', function() {
    if ($("#no_method option:selected").val() == 2) {

        $('#dynamicTable').hide();
    } else {

        $('#dynamicTable').show();
    }
});
