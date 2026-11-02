var i = 0;
$("#newadd").on('click', function() {
    ++i;
    $("#newdynamicTable").append('<tr><td><input type="text" name="docu[' + i + '][docname]" placeholder="Enter document name" class="form-control" style="font-size: 13px;"/></td><td><input type="text" name="docu[' + i + '][docnumber]" placeholder="Enter document number" class="form-control"style="font-size: 13px;" /></td><td><input type="number" name="docu[' + i + '][level]" placeholder="Enter level" class="form-control"style="font-size: 13px;" /></td><td><input type="number" name="docu[' + i + '][revnumber]" placeholder="Enter revision number" class="form-control"style="font-size: 13px;" /></td><td><input type="date" name="docu[' + i + '][revdate]"  class="form-control" style="font-size: 13px;"/></td><td><button type="button" class="btn btn-danger remove-tr">-</button></td></tr>');
});

$(document).on('click', '.remove-tr', function() {
    $(this).parents('tr').remove();
});
