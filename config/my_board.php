<?php

/* Personal work sources. Identity types are explicit; being a record's author is not an assignment. */
$modules = [
    'riskmanagement' => ['Enterprise risk', 'risk.index', 'fa-shield-alt', 'Governance'],
    'correctiveaction' => ['NCR & corrective actions', 'corrective.viewlist', 'fa-tools', 'Quality'],
    'auditmanagement' => ['Audit management', 'action.viewlist', 'fa-clipboard-check', 'Governance'],
    'compliance' => ['Compliance', 'compliance.register', 'fa-balance-scale', 'Governance'],
    'documentmanagement' => ['Document management', 'doc.governancehub', 'fa-folder-open', 'Governance'],
    'managementreview' => ['Management review', 'management.meetings', 'fa-users', 'Governance'],
    'objectives' => ['Objectives', 'user.objectives.folders', 'fa-bullseye', 'Governance'],
    'opportunity' => ['Opportunities', 'opportunity.oppindex', 'fa-lightbulb', 'Governance'],
    'ohsrisk' => ['Health & safety risks', 'ohsrisk.folder', 'fa-hard-hat', 'Safety'],
    'enviaspects' => ['Environmental aspects', 'enviaspect.folder', 'fa-leaf', 'Environment'],
    'contextmanagement' => ['Context management', 'user.context.index', 'fa-project-diagram', 'Governance'],
    'customer_satisfaction' => ['Customer satisfaction', 'customer.register', 'fa-smile', 'Quality'],
    'elearning' => ['Learning', 'elearning.courses', 'fa-graduation-cap', 'People'],
    'complaintmanagement' => ['Customer complaints', 'user.compliancemgmt', 'fa-comment-dots', 'Quality'],
    'vendormanagement' => ['Vendor management', 'vendor.index', 'fa-store', 'Operations'],
    'incidentreport' => ['Incident reporting', 'incidentregister', 'fa-exclamation-triangle', 'Safety'],
    'hr' => ['Human resources', 'hr.register', 'fa-user-friends', 'People'],
    'maintainance' => ['Maintenance', 'maintenance.total', 'fa-wrench', 'Operations'],
    'hse' => ['HSE observations', 'hseobservation.index', 'fa-eye', 'Safety'],
    'emergencyresponse' => ['Emergency response', 'emergency.register', 'fa-fire-extinguisher', 'Safety'],
    'calibration' => ['Calibration', 'calibration.index', 'fa-sliders-h', 'Operations'],
    'occhealth' => ['Occupational health', 'employeehealthrecords', 'fa-heartbeat', 'People'],
    'wastemanagement' => ['Waste management', 'waste.register', 'fa-recycle', 'Environment'],
    'nearmiss' => ['Near miss', 'nearmiss.index', 'fa-exclamation-circle', 'Safety'],
];
$role = function ($field, $identity = 'email', $kind = 'action', $pending = '*', $extra = []) {
    return array_merge(compact('field', 'identity', 'kind', 'pending'), $extra);
};
$source = function ($module, $table, $title, $reference, $stage, $roles, $extra = []) {
    return array_merge(['module' => $module, 'table' => $table, 'id' => 'id', 'title' => $title, 'reference' => $reference,
        'stage' => $stage, 'roles' => $roles, 'due' => ['due_date', 'duedate', 'target_date', 'tardate'],
        'updated' => ['updated_at', 'updatedat', 'created_at', 'createdat'], 'completion' => ['completion_date', 'completed_at', 'closed_at'],
        'complete' => ['closed', 'completed', 'accomplished', 'achieved'], 'watch' => [], 'events' => [], 'score' => true], $extra);
};
$reviewStages = ['Under review', 'Review Pending', 'Entry Under Review', 'Review Requested'];
$approvalStages = ['Reviewed', 'Awaiting Approval', 'Approval Pending', 'Pending Approval'];
$actionStages = ['Awaiting actions', 'RC Updated- Correction Awaiting', 'Correction Proposed- RC Awaiting',
    'Action Reviewed, OK', 'Action Reviewed, Not OK', 'Action Approved', 'Action Rejected', 'Approved', 'Need More Action', 'Actions Proposed- Review Awaiting'];
$workflow = function ($module, $table, $reviewerTable, $extra = []) use ($source, $role, $actionStages) {
    return $source($module, $table, ['title', 'observation'], ['issueid'], 'processstage', [
        $role('responsibility', 'email', 'action', $actionStages, ['due_pairs' => ['owners' => 'responsibility', 'dates' => 'target_date']]),
        $role('correction_responsibility', 'email', 'action', $actionStages),
        $role('team_lead_id', 'id', 'action', ['Awaiting actions', 'Correction Proposed- RC Awaiting'], ['title' => 'Investigate issue', 'due' => ['investigation_date']]),
        $role(null, 'email', 'review', ['Entry Under Review', 'Under Review', 'Actions Proposed', 'Actions Proposed- Review Awaiting', 'Actions Completed'], strpos($reviewerTable, 'gate:') === 0 ? ['gate' => substr($reviewerTable, 5), 'due' => []] : ['directory' => $reviewerTable, 'due' => []]),
    ], array_merge(['due' => ['correction_target_date', 'duedate', 'targetdate'], 'watch' => ['postedby' => 'employeeid'],
        'complete' => ['Accomplished', 'Closed', 'closed', 'Closed After Due'], 'completion' => ['completion_date', 'closedate'],
        'events' => [['field' => 'entry_reviewed_by', 'identity' => 'id', 'kind' => 'review', 'date' => 'entry_reviewed_by_date'],
            ['field' => 'action_reviewed_by', 'identity' => 'employeeid', 'kind' => 'review', 'date' => 'action_reviewed_at']]], $extra));
};
return [
    'modules' => $modules,
    'sources' => [
        'risks' => $source('riskmanagement', 'risks', ['riskname'], ['grp_id'], 'action_status', [
            $role('riskreviewer_id', 'id', 'review', ['Under review', 'Action under review', 'Completed'], ['score' => true, 'due' => []]),
            $role('risk_owner', 'email', 'action', ['Awaiting Periodic Review'], ['title' => 'Complete periodic risk review', 'due' => ['next_review_date']]),
            $role('postedby', 'employeeid', 'action', ['Rejected'], ['title' => 'Revise and resubmit risk']),
        ], ['id' => 'Rid', 'score' => false, 'due' => ['next_review_date', 'action_target'], 'watch' => ['postedby' => 'employeeid', 'risk_owner' => 'email'], 'path' => '/user/riskmgmt/{id}/detail', 'events' => [['field' => 'reviewed_by', 'identity' => 'employeeid', 'kind' => 'review', 'date' => 'review_date', 'score' => true]]]),
        'mitigations' => $source('riskmanagement', 'mitigations', ['action_name'], ['action_id'], 'status', [
            $role('owner', 'id'), $role(null, 'id', 'review', '*', ['directory' => 'riskreviewers', 'directory_identity' => 'email', 'when_progress' => 100]),
        ], ['progress' => 'progress', 'completion' => ['reviewed_date'], 'complete' => ['Closed'], 'complete_progress' => false,
            'path' => '/user/riskmgmt/mitigations/show/{id}', 'watch' => ['created_by' => 'id'],
            'events' => [['field' => 'reviewed_by', 'identity' => 'id', 'kind' => 'review', 'date' => 'reviewed_date']]]),
        'criteria' => $source('riskmanagement', 'risk_racms', ['risk_factor_title'], [], 'status', [
            $role('approver_id', 'id', 'approval', ['Reviewed', 'Awaiting Approval']),
        ], ['score' => true, 'watch' => ['posted_by' => 'id'], 'complete' => ['Approved'], 'events' => [['field' => 'approved_by', 'identity' => 'id', 'kind' => 'approval', 'date' => 'approved_date', 'score' => true]]]),
        'corrective' => $workflow('correctiveaction', 'corrective', 'issuereviewers', ['investigators' => 'corrective_investigators', 'investigator_fk' => 'corrective_id', 'path' => '/user/corrective/corrissues/{id}/detail']),
        'audit_findings' => $workflow('auditmanagement', 'issues', 'issuereviewers'),
        'hse_observations' => $workflow('hse', 'hseissues', 'gate:hse-observation-reviewer'),
        'near_misses' => $workflow('nearmiss', 'nearmiss', 'nears', ['due' => ['targetdate', 'duedate']]),
        'complaints' => $workflow('complaintmanagement', 'complaint', 'complaintreviewers', ['stage' => 'status', 'watch' => ['addedby' => 'employeeid']]),
        'opportunities' => $source('opportunity', 'opportunity', ['oppname'], ['Oid'], 'status', [
            $role('responsibility', 'email', 'action', ['Approved', 'Not Completed', 'In progress']),
            $role('opportunityapprover_id', 'id', 'review', ['Review Pending']),
            $role('closurereviewer_id', 'id', 'approval', ['Completed']),
        ], ['watch' => ['postedby' => 'employeeid'], 'due' => ['tardate'], 'completion' => ['closed_at']]),
        'objectives' => $source('objectives', 'objectives', ['objectives', 'description'], [], 'status', [
            $role('responsibility', 'email', 'action', ['Approved', 'Not Achieved']),
            $role('reviewers', 'id', 'review', $reviewStages), $role('approver', 'id', 'approval', $approvalStages),
        ], ['id' => 'Oid', 'score' => false, 'due' => ['todate', 'tardate'], 'path' => '/user/objective/{id}/detail', 'watch' => ['postedby' => 'employeeid', 'new_postedby' => 'id']]),
        'compliance' => $source('compliance', 'compliance', ['requirement', 'regulation'], [], 'pro_status', [
            $role('person_control'), $role('person_monitor'), $role('responsibility'),
            $role('reviewer_id', 'id', 'review', $reviewStages), $role('approver_id', 'id', 'approval', $approvalStages),
        ], ['id' => 'Cid', 'score' => false, 'watch' => ['postedby' => 'employeeid']]),
        'document_drafts' => $source('documentmanagement', 'governance_hub_drafts', ['document_name'], ['document_number'], 'status', [
            $role('reviewer', 'email', 'review', $reviewStages), $role('approvers', 'email', 'approval', ['Draft Reviewed']),
            $role('drafted_by', 'email', 'action', ['Rejected', 'Review Rejected', 'Approval Rejected'], ['credit_completion' => false]),
        ], ['watch' => ['drafted_by' => 'email'], 'complete' => ['Draft Approved'], 'events' => [
            ['field' => 'approved_by_user', 'identity' => 'id', 'kind' => 'approval', 'date' => 'approved_date']]]),
        'training' => $source('hr', 'hr_training', ['training_title'], [], 'status', [
            $role('employee_id', 'employee_record', 'action', ['Scheduled', 'Pending', 'Not completed', 'Not Completed']),
        ], ['watch' => ['posted_id' => 'employeeid'], 'complete' => ['Completed'], 'completion' => ['completed_at']]),
        'learning' => $source('elearning', 'course_participants', ['course_name'], ['course_id'], 'status', [$role('email', 'email', 'action', '*', ['title' => 'Continue assigned learning'])],
            ['progress' => 'progress_percentage', 'complete_progress' => true, 'completion' => ['completed_at'], 'course' => true]),
        'maintenance' => $source('maintainance', 'maintenance', ['desc'], ['equid'], 'pmstatus', [$role('pmby', 'unique_name', 'action', ['Time for PM'])],
            ['score' => false, 'due' => ['schedule'], 'watch' => []]),
        'maintenance_tests' => $source('maintainance', 'maintenance', ['desc'], ['equid'], 'teststatus', [$role('testby', 'unique_name', 'action', ['Time for Test'])],
            ['score' => false, 'due' => ['testschedule'], 'watch' => []]),
        'calibration' => $source('calibration', 'calibration_instruments', ['Instrument_desc'], ['Instrument_no'], 'Status', [
            $role('Holder', 'unique_name', 'action', ['Expired', 'Near to expiry', 'Not Calibrated', 'Under Calibration']),
        ], ['score' => false, 'due' => ['Duedate'], 'watch' => []]),
        'emergency_drills' => $source('emergencyresponse', 'emergency_response_details', ['emergency_situation_name'], [], 'status', [
            $role('drill_team_id', 'employee_record', 'action', '*', ['title' => 'Prepare emergency drill']),
        ], ['score' => false, 'due' => ['drill_date_next', 'duedate'], 'watch' => ['added_by' => 'id']]),
        'incidents' => $source('incidentreport', 'incident_reports', ['incidentdesc'], ['report_no'], 'investigation_status', [
            $role('responsiblity', 'email', 'action', ['Under Investigation', 'Need Improvement', 'Investigation Completed']),
        ], ['score' => false, 'due' => ['currective_targdate'], 'watch' => ['adder_email' => 'email']]),
        'incident_entry_reviews' => $source('incidentreport', 'incident_reports', ['incidentdesc'], ['report_no'], 'reviewed', [
            $role('reviewer_email', 'email', 'review', ['Under review', 'Under Secondary Review']),
        ], ['score' => false, 'watch' => [], 'events' => [['field' => 'reviewed_by', 'identity' => 'email', 'kind' => 'review', 'date' => 'reviewed_at', 'score' => true]]]),
        'incident_approvals' => $source('incidentreport', 'incident_reports', ['incidentdesc'], ['report_no'], 'reviewed', [
            $role('approver_email', 'email', 'approval', ['Reviewed']),
        ], ['score' => false, 'watch' => []]),
        'audit_schedules' => $source('auditmanagement', 'audit', ['department_audit_title'], ['aud_id'], 'status', [
            $role('email', 'email', 'action', '*', ['title' => 'Prepare or complete assigned audit']),
        ], ['score' => false, 'due' => ['enddate', 'date'], 'complete' => ['Completed', 'Permanently closed', 'Cancelled', 'Archived'], 'watch' => []]),
        'waste' => $source('wastemanagement', 'waste', ['wastedesc', 'itemname'], ['request_no'], 'process_stage', [
            $role('responsibility', 'unique_name', 'action', ['Approved', 'Disposal Requested', 'Sales Requested']), $role('reviewer', 'email', 'review', ['HOD Approved', 'Disposal Requested', 'Sales Requested']),
            $role('approver', 'email', 'approval', $approvalStages),
        ], ['complete' => ['Physically Disposed', 'Sold', 'Completed', 'Closed'], 'completion' => ['disposed_completed_date'], 'watch' => ['posted_by' => 'email']]),
        'vendor_evaluations' => $source('vendormanagement', 'vendor', ['vendor_name'], [], 'status', [
            $role('end_user_id', 'id', 'review', ['Review Pending', 'Under Review', 'Evaluation Requested']),
            $role('system_compliance_user_id', 'id', 'review', ['Review Pending', 'Under Review', 'Evaluation Requested']),
            $role('supply_chain_representative_user_id', 'id', 'review', ['Review Pending', 'Under Review', 'Evaluation Requested']),
        ], ['watch' => ['added_by' => 'employeeid'], 'due' => ['due_date'], 'score' => false]),
        'meetings' => $source('managementreview', 'management_review_schedules', ['agenda_points'], [], 'status', [
            $role('participants', 'id', 'action', ['Scheduled', 'Pending', 'Open']),
            $role('management_participants', 'id', 'action', ['Scheduled', 'Pending', 'Open']),
        ], ['score' => false, 'due' => ['date'], 'complete' => ['Completed', 'Cancelled'], 'watch' => ['added_by' => 'id']]),
        'environment_reviews' => $source('enviaspects', 'environment', ['activity', 'location'], [], 'status', [
            $role(null, 'email', 'review', ['Review Pending'], ['directory' => 'envireviewers']),
        ], ['id' => 'Eid', 'score' => false, 'watch' => ['useremail' => 'email']]),
        'environmental_aspects' => $source('enviaspects', 'enviaspects', ['enviaspects'], ['Eid'], 'status', [
            $role('reviewer_id', 'id', 'review', $reviewStages), $role('approver_id', 'id', 'approval', $approvalStages),
        ], ['score' => false, 'watch' => ['posted_by' => 'employeeid']]),
        'safety_risks' => $source('ohsrisk', 'ohsrisk', ['hazards'], ['riskid'], 'risk_status', [$role('risk_owner'), $role('monitor')], ['score' => false, 'watch' => []]),
        'context_changes' => $source('contextmanagement', 'context_crs', ['title'], ['crid'], 'status', [
            $role('rev_authority', 'id', 'review', $reviewStages), $role('appr_authority', 'id', 'approval', $approvalStages), $role('responsibility'),
        ], ['score' => false, 'watch' => ['postedby' => 'employeeid']]),
    ],
];
