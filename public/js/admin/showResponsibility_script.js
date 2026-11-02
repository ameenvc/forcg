let arr = [];
let error_text = null;

function validation(item, text, count, event = null) {
    if (count > 1) {
        if (document.getElementById(item) != null) {
            if (document.getElementById(item).value == 0) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Please select a user from " + text,
                });
                if (event) {
                    event.preventDefault();
                }
                document.getElementById(item).focus();
                return false;
            }
        }
    }
}

function loopFor(count, id, name) {
    for (let i = 1; i <= count; i++) {
        validation(id + i, name);
    }
}

$("form").on("submit", function (event) {
    if ($("#new_responsibility").val() === "0") {
        let dept_arr = [];
        let audit_arr = [];
        $("#dept").each(function (index, value) {
            dept_arr.push($(value).val());
        });
        if (dept_arr.includes("0")) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Please select a user from department list",
            });
            event.preventDefault();
        }

        $("#auditor").each(function (index, value) {
            audit_arr.push($(value).val());
        });
        if (audit_arr.includes("0")) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Please select a user from auditors list",
            });
            event.preventDefault();
        }
        loopFor(nearmiss_count, "nearmiss", "nearmiss", event);
        loopFor(masterdocs_count, "masterdoc", "masterdoc", event);
        loopFor(dcrs_count, "dcr", "dcr", event);
        loopFor(risks_count, "risks", "risks", event);
        loopFor(issues_count, "issues", "issues", event);
        loopFor(corrective_count, "corrective", "corrective", event);
        loopFor(opportunity_count, "opportunity", "opportunity", event);
        loopFor(hseissue_count, "hseissue", "HseIssues", event);
        validation("near", "near", near_count, event);
        validation(
            "calibration_reviewer",
            "calibration reviewer",
            calibrationreviewer_count,
            event
        );
        validation(
            "doccontroller",
            "doccontroller",
            doccontroller_count,
            event
        );
        validation("hses", "hses", hse_count, event);
        validation(
            "representative",
            "representative",
            representative_count,
            event
        );
        validation(
            "IssueReviewer",
            "IssueReviewer",
            issuereviewer_count,
            event
        );
        validation(
            "internalaudit",
            "internalaudit",
            internalaudit_count,
            event
        );
        validation(
            "archiveprivilege",
            "archiveprivilege",
            archiveprivilege_count,
            event
        );
        validation("riskreviewer", "riskreviewer", riskreviewer_count, event);
        validation(
            "escalateriskreceiver",
            "escalateriskreceiver",
            escalateriskreceiver_count,
            event
        );
        validation(
            "closurereviewer",
            "closurereviewer",
            closurereviewer_count,
            event
        );
        validation("management", "management", management_count, event);
        validation(
            "legalCoordinator",
            "legalCoordinator",
            legal_coordinator_count,
            event
        );
        validation(
            "incident_reviewer",
            "Incident Reviewer",
            incident_reviewer_count,
            event
        );
        validation(
            "incident_approver",
            "Incident Approver",
            incident_approver_count,
            event
        );
        validation("division", "Divisions", 2, event);
        validation("subdivision", "Sub Divisions", 2, event);
        validation("objective_resp", "Objective Responsibilities", 2, event);
        validation("auditee", "Auditee Responsibilities", 2, event);
        validation(
            "complaintreviewer",
            "Complaint Reviewer",
            complaint_reviewer_count,
            event
        );
        validation(
            "complaintregister",
            "Complaint Register",
            complaint_register_count,
            event
        );
        validation(
            "contextreviewer",
            "Context Reviewer",
            change_request_reviewer_count,
            event
        );
        validation(
            "contextapprover",
            "Context Approver",
            change_request_approve_count,
            event
        );
        validation(
            "complaint_responsibilities",
            "Complaint Responsibilities",
            2,
            event
        );
        validation(
            "waste_reviewer",
            "Waste Reviewers",
            waste_reviewer_count,
            event
        );
        validation(
            "waste_approver",
            "Waste Approvers",
            waste_approver_count,
            event
        );
        validation(
            "waste_disposal_sales_authorities",
            "Waste disposal sales authorities",
            waste_disposal_sales_authorithy_count,
            event
        );
        validation(
            "envireviewer",
            "Enviaspects Reviewer",
            envi_reviewer_count,
            event
        );
        validation(
            "trainingcoordinator",
            "Training Coordinators",
            trainingcoordinator_count,
            event
        );
        validation("mainreviewer", "Main reviewers", mainreviewer_count, event);
        validation(
            "mainelectrical",
            "Main electricals",
            mainelectrical_count,
            event
        );
        validation(
            "maininstrument",
            "Main instruments",
            main_instrument_count,
            event
        );
        validation(
            "company_doctor",
            "Company Doctors",
            company_doctor_count,
            event
        );
        validation(
            "supplierapprover",
            "Supplier Approver",
            supplierapprover_count,
            event
        );
        validation(
            "vendor_reviewer",
            "Vendor Reviewer",
            review_vendor_count,
            event
        );
        validation(
            "vendor_approver",
            "Vendor Approver",
            approve_vendor_count,
            event
        );
        validation(
            "elearning_add_content",
            "Elearning add content",
            elearning_add_content_count,
            event
        );
        validation(
            "envi_secondary_reviewer",
            "Enviaspects secondary reviewer",
            enviaspect_secondary_review_count,
            event
        );
        validation(
            "hse_observation_reviewer",
            "HSE Observation reviewer",
            hse_observation_reviewer_count,
            event
        );
        validation(
            "objective_reviewer",
            "Objective reviewer",
            objective_reviewer_count,
            event
        );
        validation(
            "objective_approver",
            "Objective approver",
            objective_approver_count,
            event
        );
        validation(
            "racmreviewer",
            "Racm Reviewer",
            racm_reviewer_count,
            event
        );
        validation(
            "racmapprover",
            "Racm Approver",
            racm_approver_count,
            event
        );
        validation(
            "management_reviewer",
            "Management reviewer",
            management_review_meeting_controls_count,
            event
        );
        validation(
            "ohs_secondary_review",
            "Ohsrisk owner",
            ohsrisk_secondary_review_count,
            event
        );
    }
});
$(".test").on("click", function () {
    var id = $(this).data("id");
    $(".modal-body #deptId").val(id);
    $("#dept").val(0);
    $("#hodposition").val(null);
    $("#successBtn").css("display", "none");
    $("#dangerBtn").css("display", "none");
});
$(".auditorEditBtn").on("click", function () {
    var id = $(this).data("id");
    $(".modal-body #auditorId").val(id);
    $("#auditor").val(0);
    $("#certificate").val(null);
    $("#experience").val(null);
    $("#successAuditorBtn").css("display", "none");
    $("#dangerAuditorBtn").css("display", "none");
});

function saveChanges() {
    if ($("#dept").val() == 0 || $("#hodposition").val() == "") {
        error_text = "Please fill both these fields";
        $("#dangerBtn").css("display", "block");
        $("#dangerBtn").text(error_text);
    } else {
        $.ajax({
            data: {
                dept: $("#dept").val(),
                deptId: $("#deptId").val(),
                hodposition: $("#hodposition").val(),
                _token: CSRF_TOKEN,
            },
            url: url,
            type: "POST",
            success: function (result) {
                if (!result.error) {
                    mess1 = "Are you sure?";
                    x = confirm(mess1);
                    if (x == true) {
                        $("#dangerBtn").css("display", "none");
                        $("#successBtn").css("display", "block");
                        setTimeout(function () {
                            window.location.reload();
                        }, 1000);
                    } else {
                        return false;
                    }
                } else {
                    $("#dangerBtn").css("display", "block");
                    $("#successBtn").css("display", "none");
                    $("#dangerBtn").text(result.error);
                }
            },
            error: function () {
                console.log("error");
            },
        });
    }
}

function saveAuditorChanges() {
    if (
        $("#auditor").val() == 0 ||
        $("#certificate").val() == "" ||
        $("#experience").val() == ""
    ) {
        error_text = "Please fill all these fields";
        $("#dangerAuditorBtn").css("display", "block");
        $("#dangerAuditorBtn").text(error_text);
    } else {
        $.ajax({
            data: {
                auditor: $("#auditor").val(),
                auditorId: $("#auditorId").val(),
                certificate: $("#certificate").val(),
                experience: $("#experience").val(),
                _token: CSRF_TOKEN,
            },
            url: auditor_url,
            type: "POST",
            success: function (result) {
                if (!result.error) {
                    mess1 = "Are you sure?";
                    x = confirm(mess1);
                    if (x == true) {
                        $("#dangerAuditorBtn").css("display", "none");
                        $("#successAuditorBtn").css("display", "block");
                        setTimeout(function () {
                            window.location.reload();
                        }, 1000);
                    } else {
                        return false;
                    }
                } else {
                    $("#dangerAuditorBtn").css("display", "block");
                    $("#successAuditorBtn").css("display", "none");
                    $("#dangerAuditorBtn").text(result.error);
                }
            },
            error: function () {
                console.log("error");
            },
        });
    }
}
