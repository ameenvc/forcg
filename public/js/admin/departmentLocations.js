let id = 0;
function loadDataOfTable(url, table, tbody_content) {
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": CSRF_TOKEN,
            'accept': 'application/json'
        },
        method: "get",
        url: url,
        success: function(resp) {
            records = resp.data;
            let number = 1;

            if (records.length) {
                for (var i = 0; i < records.length; i++) {
                    id = records[i].id;
                    tbody_content += `<tr>
        <td style="font-size:10px;" class="text-left">
            <span style="width:5px"> ${number} </span>
        </td>
        <td style="font-size:10px;width:10px" class="text-left">
            <span>${records[i].name}</span>
        </td>
        <td style="font-size:10px;width:10px" class="text-left">
            <div class="btn-group">

                <button type="button" class="btn btn-sm btn-alt-primary" style="font-size:10px" data-toggle="tooltip" title="Edit" onclick="editBtn('${id}')"><strong><i class="fa fa-fw fa-pencil-alt"></i></strong></button>
                <div id="viewModal${id}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h2 class="modal-title">Edit Location</h2>
                            </div>
                            <div class="alert alert-success d-none ml-3 mr-3" id="success_edit_location${id}">Location edited successfully</div>
                            <ul class="alert alert-danger d-none ml-3 mr-3" id="danger_edit_location${id}"></ul>
                                <div class="col-lg-12">
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" style="font-size: 13px;" value="${records[i].name}" name="name" id="editLocation${id}">
                                            <input type="hidden" class="form-control" style="font-size: 13px;" value="${records[i].locationable_id}" name="locationable_id" id="editLocationId${id}">
                                            <input type="hidden" class="form-control" style="font-size: 13px;" value="${records[i].locationable_type}" name="locationable_type" id="editLocationType${id}">
                                        </div>
                                    </div>
                                </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" onClick="editLocation(${id})">Save</button>
                                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-alt-primary" style="font-size:10px" data-toggle="tooltip" title="Delete" onclick="deleteLocation(${id}, '/admin/locations/delete/${id}')"><strong><i class="fa fa-fw fa-times"></i></strong></button>

        </td>
        </tr>`;

                    number++;
                }
            } else {
                tbody_content = "";
            }

            $(table + " > tbody")
                .empty()
                .append(tbody_content);
        },
        error: function(err) {
            console.log(err);
        }
    });
}

function init(url, id, table_id) {
    let the_url = url;
    the_url = the_url.replace(":id", id);
    loadDataOfTable(the_url, table_id, "");
}

function filterRecord(url, id, table_id, tbody_content) {
    let filter_url = url;
    filter_url = filter_url.replace(":id", id);
    loadDataOfTable(filter_url, table_id, tbody_content);
}
init(location_department_url, $("#department").val(), "#departments_table");
init(location_division_url, $("#division").val(), "#divisions_table");
init(location_area_url, $("#area").val(), "#areas_table");

function filterDepartment(id) {
    filterRecord(location_department_url, id, "#departments_table", "");
}

function filterLocation(id) {
    filterRecord(location_division_url, id, "#divisions_table", "");
}

function filterArea(id) {
    filterRecord(location_area_url, id, "#areas_table", "");
}

function addLocation() {
    $("#addLocation").modal("show");
}

function editBtn(id) {
    $("#viewModal" + id).modal("show");
}

function displayDepartmentSelect() {
    $("#division_radio_select").addClass("d-none");
    $("#area_radio_select").addClass("d-none");
    $("#department_radio_select").removeClass("d-none");
}

function displayDivisionSelect() {
    $("#division_radio_select").removeClass("d-none");
    $("#area_radio_select").addClass("d-none");
    $("#department_radio_select").addClass("d-none");
}

function displayAreaSelect() {
    $("#division_radio_select").addClass("d-none");
    $("#area_radio_select").removeClass("d-none");
    $("#department_radio_select").addClass("d-none");
}
$(".form-check-input").on("click", function () {
    if ($(this).val() == "department") {
        $("#division_location_select").val("");
        $("#area_location_select").val("");
    } else if ($(this).val() == "division") {
        $("#department_location_select").val("");
        $("#area_location_select").val("");
    } else if ($(this).val() == "area") {
        $("#department_location_select").val("");
        $("#division_location_select").val("");
    }
});

function saveLocation() {
    $.ajax({
        data: {
            name: $("#name").val(),
            radio_select: $(".form-check-input:checked").val(),
            department: $("#department_location_select").val(),
            division: $("#division_location_select").val(),
            area: $("#area_location_select").val(),
            _token: CSRF_TOKEN,
        },
        url: location_add_url,
        headers: {
            'accept': 'application/json'
        },
        type: "POST",
        success: function () {
            console.log('success');
            $("#danger_location").addClass("d-none");
            $("#success_location").removeClass("d-none");
            setTimeout(function () {
                window.location.reload();
            }, 1000);
        },
        error: function (result) {
            console.log('error');
            $("#danger_location").removeClass("d-none");
            $("#success_location").addClass("d-none");
            let error_text = "";
            if (result.responseJSON.errors) {
                $.each(result.responseJSON.errors, function (i) {
                    $.each(result.responseJSON.errors[i], function (key, val) {
                        error_text += "<li>" + val + "</li>";
                    });
                });
            } else {
                error_text += "<li> Some errors happened In Server </li>";
            }

            $("#danger_location").empty().append(error_text);
        },
    });
}

function editLocation(id) {
    location_edit_url = location_edit_url.replace(":id", id);
    $.ajax({
        data: {
            name: $("#editLocation" + id).val(),
            locationable_id: $("#editLocationId" + id).val(),
            locationable_type: $("#editLocationType" + id).val(),
            _token: CSRF_TOKEN
        },
        url: location_edit_url,
        headers: {
            'accept': 'application/json'
        },
        type: "POST",
        success: function () {
            console.log("success edit");
            $("#danger_edit_location" + id).addClass("d-none");
            $("#success_edit_location" + id).removeClass("d-none");
            setTimeout(function() {
                window.location.reload();
            }, 1000);
        },
        error: function (result) {
            console.log("error edit");
            $("#danger_edit_location" + id).removeClass("d-none");
            $("#success_edit_location" + id).addClass("d-none");
            let error_text = "";
            if (result.responseJSON.errors) {
                $.each(result.responseJSON.errors, function(i) {
                    $.each(result.responseJSON.errors[i], function(key, val) {
                        error_text += "<li>" + val + "</li>";
                    });
                });
            } else {
                error_text += "<li> Some errors happened In Server </li>";
            }

            $("#danger_edit_location" + id)
                .empty()
                .append(error_text);
        }
    });
}

function deleteLocation(id, delete_url) {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                data: {
                    id: id,
                    _token: CSRF_TOKEN,
                },
                url: delete_url,
                type: "GET",
                success: function (result) {
                    Swal.fire({
                        title: "Deleted!",
                        text: "Location has been deleted.",
                        icon: "success",
                    }).then((res) => {
                        if (res.isConfirmed) window.location.reload();
                    });
                },
                error: function (result) {
                    console.log(result);
                },
            });
        }
    });
}
