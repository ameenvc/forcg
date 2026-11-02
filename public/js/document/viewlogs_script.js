let doc_remove_route = null;
$("#delete").on('click',function() {
    var dataArr = new Array();
    var group = new Array();
    var d;
    var all;
    if ($('input:checkbox:checked').length > 0) {
        $('input:checkbox:checked').each(function() {
            dataArr.push($(this).attr('id'));
            all = "" + dataArr;
            group.push(all); // push all checkbox values into an array
        });
        doc_remove_route = remove_route.replace(":id", (all));
        $.ajax({
            type: 'get'
            , url: doc_remove_route
            , success: function(response) {
                window.location.href = doc_remove_route;
            }
            , error: function(response) {
                alert(errResponse);
            }
        });

    } else {
        alert('No Record Selected');
    }
});
let doc_change_route = null;
$("#change").on('click',function() {
    var dataArr = new Array();
    var group = new Array();
    var d;
    var all;
    if ($('input:checkbox:checked').length > 0) {
        $('input:checkbox:checked').each(function() {
            dataArr.push($(this).attr('id'));
            all = "" + dataArr;
            group.push(all); // push all checkbox values into an array
        });
        doc_change_route = change_route.replace(":id", (all));
        $.ajax({
            type: 'get'
            , url: doc_change_route
            , success: function(response) {
                window.location.href = doc_change_route;
            }
            , error: function(response) {
                alert(errResponse);
            }
        });

    } else {
        alert('No Record Selected');
    }
});
let doc_delete_route = null;
$("#del").on('click',function() {
    var dataArr = new Array();
    if ($('input:checkbox:checked').length > 0) {
        $('input:checkbox:checked').each(function() {
            dataArr.push($(this).attr('id'));
            doc_delete_route = delete_route.replace(":id", $(this).attr('id'));
            $.ajax({
                type: 'get'
                , url: doc_delete_route
                , success: function(response) {
                    window.location.href = home_route;
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
let doc_edit_route = null;
    $("#edit").on('click',function() {
        var dataArr = new Array();
        var group = new Array();
        var d;
        var all;
        if ($('input:checkbox:checked').length > 0) {
            $('input:checkbox:checked').each(function() {
                dataArr.push($(this).attr('id'));
                all = "" + dataArr;
                group.push(all);
            });
            doc_edit_route = edit_route.replace(":id", (all));
            $.ajax({
                type: 'get'
                , url: doc_edit_route
                , success: function(response) {
                    window.location.href = doc_edit_route;
                }
                , error: function(response) {
                    alert(errResponse);
                }
            });

        } else {
            alert('No Record Selected');
        }
    });
