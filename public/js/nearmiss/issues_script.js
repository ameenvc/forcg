$("#del").on("click", function() {
    var dataArr = new Array();
    if ($("input:checkbox:checked").length > 0) {
        $("input:checkbox:checked").each(function() {
            dataArr.push($(this).attr("id"));
            $.ajax({
                type: "get",
                url: "/user/nearmiss/delete/" + $(this).attr("id"),
                success: function(response) {
                    window.location.href = route_nearmiss_index;
                },
                error: function(response) {
                    alert(errResponse);
                }
            });
        });
    } else {
        alert("No Record Selected");
    }
});
$("#edit").on("click", function() {
    var dataArr = new Array();
    var group = new Array();
    var d;
    var all;
    if ($("input:checkbox:checked").length > 0) {
        $("input:checkbox:checked").each(function() {
            dataArr.push($(this).attr("id"));
            all = "" + dataArr;
            group.push(all); // push all checkbox values into an array
        });
        $.ajax({
            type: "get",
            url: "/user/nearmiss/editreq/" + all,
            success: function(response) {
                window.location.href = "/user/nearmiss/editreq/" + all;
            },
            error: function(response) {
                alert(errResponse);
            }
        });
    } else {
        alert("No Record Selected");
    }
});
$(".viewDetails").on("click", function() {
    var id = $(this).data("id");
    $.ajax({
        url: "/show/" + id + "/" + "nearmiss",
        type: "get",
        data: {
            id: id
        },
        dataType: "json",
        success: function(response) {
            response.data.responsibleUser
                ? $("#responsible" + id).attr(
                      "src",
                      "/storage/profile/" +
                          response.data.responsibleUser.profile_picture
                  )
                : $("#responsible").attr("src", "");
            response.data.postedByUser
                ? $("#postedbyUser" + id).attr(
                      "src",
                      "/storage/profile/" +
                          response.data.postedByUser.profile_picture
                  )
                : $("#postedbyUser").attr("src", "");
        }
    });
});
