var mini = true;

function toggleSidebar() {
    if (mini) {
        document.getElementById("mySidebar").style.width = "250px";
        document.getElementById("main").style.marginLeft = "250px";
        this.mini = false;
    } else {
        document.getElementById("mySidebar").style.width = "85px";
        document.getElementById("main").style.marginLeft = "85px";
        this.mini = true;
    }
}
$(function () {
    $('body').on('change', '.headingselect', function () {
        if ($(this).val()) {
            $(this).find('option:selected').html(
                $(this).val() + "&nbsp;&nbsp;&#9207;"
            )
            $(this).find('option:not(:selected)').each(function () {
                $(this).html($(this).text().replace("⏷", ""));
            })
        }
    })
    $('body').on('shown.bs.modal', '.new-mitigating-popup', function() {
        $(this).find('.js-select2').each(function() {
            const limitSelection = $(this).attr('data-limit-selection')
            let dropdownParent = $(document.body);
            if ($(this).parents('.modal.new-mitigating-popup:first').length !== 0) {
                dropdownParent = $(this).parents('.modal.new-mitigating-popup:first');
            }
            $(this).select2({
                dropdownParent: dropdownParent,
                width: '100%',
                tags: true,
                multiple: true,
                maximumSelectionLength: limitSelection ? limitSelection : 0
            });
        });
    });
    // $('#link_other_risk').select2({
    //     dropdownParent: $(".new-mitigating-popup .modal-content .modal-body .link-other-risk-dropdown"),
    //     width: '100%',
    // });
})
// /*** start: user/nearmiss/issues.blade.php  ***/
// $("#del").on("click", function () {
//     var dataArr = new Array();
//     if ($("input:checkbox:checked").length > 0) {
//         $("input:checkbox:checked").each(function () {
//             dataArr.push($(this).attr("id"));
//             $.ajax({
//                 type: "get",
//                 url: route_nearmiss_delete + "?val=" + $(this).attr("id"),
//                 success: function (response) {
//                     window.location.href = route_nearmiss_index;
//                 },
//                 error: function (response) {
//                     alert(errResponse);
//                 }
//             });
//         });
//     } else {
//         alert("No Record Selected");
//     }
// });
// $("#edit").on("click", function () {
//     var dataArr = new Array();
//     var group = new Array();
//     var d;
//     var all;
//     if ($("input:checkbox:checked").length > 0) {
//         $("input:checkbox:checked").each(function () {
//             dataArr.push($(this).attr("id"));
//             all = "" + dataArr;
//             group.push(all); // push all checkbox values into an array
//         });
//         $.ajax({
//             type: "get",
//             url: route_nearmiss_edit + "?val=" + all,
//             success: function (response) {
//                 window.location.href = route_nearmiss_edit + "?val=" + all;
//             },
//             error: function (response) {
//                 alert(errResponse);
//             }
//         });
//     } else {
//         alert("No Record Selected");
//     }
// });
// $(".viewDetails").on("click", function () {
//     var id = $(this).data("id");
//     $.ajax({
//         url: "/show/" + id + "/" + "nearmiss",
//         type: "get",
//         data: {
//             id: id
//         },
//         dataType: "json",
//         success: function (response) {
//             response.data.responsibleUser ?
//                 $("#responsible" + id).attr(
//                     "src",
//                     "/storage/profile/" +
//                     response.data.responsibleUser.profile_picture
//                 ) :
//                 $("#responsible").attr("src", "");
//             response.data.postedByUser ?
//                 $("#postedbyUser" + id).attr(
//                     "src",
//                     "/storage/profile/" +
//                     response.data.postedByUser.profile_picture
//                 ) :
//                 $("#postedbyUser").attr("src", "");
//         }
//     });
// });
// /*** end: user/nearmiss/addissue.blade.php  ***/
