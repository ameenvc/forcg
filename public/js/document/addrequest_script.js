var i = 0;
$("#newadd").on('click', function() {
    ++i;
    $("#newdynamicTable").append('<tr> <td><input type="text" name="docu[' + i + '][ndocname]" placeholder="Enter Document Name" class="form-control" style="font-size: 13px;" /></td><td><input type="text" name="docu[' + i + '][ndocnumber]" placeholder="Enter Document Number" class="form-control" style="font-size: 13px;" /></td><td><button type="button" class="btn btn-danger remove-tr">-</button></td></tr>');
});
$(document).on('click', '.remove-tr', function() {
    $(this).parents('tr').remove();
});
