$("#del").on('click', function () {
    var dataArr = new Array();
    if ($('input:checkbox:checked').length > 0) {
        $('input:checkbox:checked').each(function () {
            dataArr.push($(this).attr('id'));
            $.ajax({
                type: 'get',
                url: route_dardelete.replace(':id', $(this).attr('id')),
                success: function (response) {
                    window.location.href = route_register;
                },
                error: function (response) {
                    alert(errResponse);
                }
            });
        });
    } else {
        alert('No Record Selected');
    }
});
