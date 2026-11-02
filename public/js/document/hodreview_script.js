// $("#no_method").on('change', function () {
//     if ($("#no_method option:selected").val() == 2) {
//         $('#dynamicTable').hide();
//     } else {
//         $('#dynamicTable').show();
//     }
// });
var i = 0;
$("#add").on('click', function () {
    ++i;
    $("#dynamicTable").append(
        '<tr><td><input type="number" name="doc[' +
            i +
            '][edocname]" placeholder="Enter New Document Number" class="form-control"style="font-size: 13px;"/></td><td><input type="text" class="form-control" name="doc[' +
            i +
            '][edocnumber]" placeholder="Enter New Document Name" class="form-control"style="font-size: 13px;"/></td><td><input type="number" name="doc[' +
            i +
            '][erevnumber]"  placeholder="Enter New Revision Number" class="form-control" style="font-size: 13px;"/></td><td><input type="date" name="doc[' +
            i +
            '][erevdate]" placeholder="Enter New Revision Date" class="form-control" style="font-size: 13px;"/></td><td> <button type="button" class="btn btn-danger remove-tr">-</button></ ></tr > '
    );
});
$(document).on('click', '.remove-tr', function () {
    $(this).parents('tr').remove();
});

// var i = 0;
// $("#newadd").on("click", function () {
//     console.log('goh');
//     ++i;
//     $("#newdynamicTable").append(
//         '<tr><<td><input type="number" class="form-control" name="doc[' +
//             i +
//             '][ndocnumber]" placeholder="Enter New Document Number" lass="form-control"style="font-size: 13px;" /></td><td><input type="date" name="doc[' +
//             i +
//         '][nrevdate]"  class="form-control" style="font-size: 13px;" /></td><td><button type="button" class="btn btn-danger remove-tr">-</button></></tr> '
//     );
// });
// $(document).on("click", ".remove-tr", function () {
//     $(this).parents("tr").remove();
// });
