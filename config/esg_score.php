<?php
// Versioned internal readiness model, not a certified ESG rating or customer approval policy.
return [
    'version' => '1.0',
    'pillars' => ['E' => ['Environmental', 35], 'S' => ['Social', 35], 'G' => ['Governance', 30]],
    // label, pillar, module, source, status column, satisfactory states, weight
    'indicators' => [
        ['Environmental aspect assessment', 'E', 'enviaspects', 'environment', 'status', ['Approved', 'Reviewed'], 25],
        ['Waste disposal completion', 'E', 'wastemanagement', 'waste', 'process_stage', ['Physically Disposed', 'Sold', 'Completed', 'Closed'], 20],
        ['GHG inventory and emissions intensity', 'E', null, null, null, [], 25],
        ['Energy, renewable energy and water performance', 'E', null, null, null, [], 30],
        ['HSE observation closure', 'S', 'hse', 'hseissues', 'processstage', ['Closed', 'Closed After Due', 'Accomplished'], 20],
        ['Near-miss corrective closure', 'S', 'nearmiss', 'nearmiss', 'processstage', ['Closed', 'Closed After Due', 'Accomplished'], 15],
        ['Training completion', 'S', 'hr', 'hr_training', 'status', ['Completed'], 20],
        ['Incident investigation completion', 'S', 'incidentreport', 'incident_reports', 'investigation_status', ['Investigation Completed'], 15],
        ['Labour rights, diversity and worker welfare', 'S', null, null, null, [], 30],
        ['Legal compliance', 'G', 'compliance', 'compliance', 'comp_status', ['Complied'], 20],
        ['Audit schedule completion', 'G', 'auditmanagement', 'audit', 'status', ['Completed'], 10],
        ['Audit finding closure', 'G', 'auditmanagement', 'issues', 'processstage', ['Closed', 'Closed After Due', 'Accomplished'], 10],
        ['Corrective action closure', 'G', 'correctiveaction', 'corrective', 'processstage', ['Closed', 'Closed After Due', 'Accomplished'], 10],
        ['Risk mitigation completion', 'G', 'riskmanagement', 'mitigations', 'status', ['Closed', 'Completed'], 10],
        ['Controlled document approval', 'G', 'documentmanagement', 'governance_hub_drafts', 'status', ['Draft Approved'], 10],
        ['Customer complaint closure', 'G', 'complaintmanagement', 'complaint', 'status', ['Closed'], 10],
        ['Anti-bribery, ethics and supply-chain due diligence', 'G', null, null, null, [], 20],
    ],
];
