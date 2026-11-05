<?php

// Curated examples for demonstration only. These are not model-generated assessments.
$make = function ($name, $reason, $description, $drivers, $controls, $questions) {
    return ['risk_name' => $name, 'reason' => $reason, 'risk_description' => $description,
        'risk_drivers' => $drivers, 'proposed_controls' => $controls, 'questions' => $questions];
};

return [
    [
        'keywords' => ['geopolit', 'sanction', 'political', 'conflict', 'trade restriction'],
        'risks' => [
            $make('Supply disruption from geopolitical conflict',
                'Relevant when critical suppliers or transport routes depend on regions exposed to conflict.',
                'Geopolitical conflict or border restrictions could disrupt critical imports and transport routes, delaying delivery of materials and potentially interrupting operations.',
                ['Concentration of suppliers in exposed regions', 'Dependency on cross-border transport routes', 'Limited alternative sources for critical materials'],
                ['Assess geographic exposure of critical suppliers and routes', 'Qualify alternative suppliers in different regions', 'Define contingency stock and alternative logistics arrangements'],
                ['Which countries, suppliers, and routes are involved?', 'How long could operations continue if deliveries stopped?']),
            $make('Trade sanctions restricting access to key markets',
                'Relevant when imports, exports, counterparties, or payment routes may be affected by sanctions.',
                'New or expanded trade sanctions could restrict transactions with suppliers or customers, preventing fulfillment of contracts and exposing the organization to compliance and financial consequences.',
                ['Changing sanctions regimes', 'Dependency on affected markets or counterparties', 'Limited visibility of intermediary and ownership relationships'],
                ['Screen relevant counterparties and ownership relationships', 'Review sanctions exposure with qualified compliance advisers', 'Prepare alternative sourcing and market arrangements'],
                ['Which jurisdictions and counterparties are relevant?', 'What screening and escalation controls already exist?']),
            $make('Margin erosion from geopolitical energy-price volatility',
                'Relevant to energy-intensive operations or contracts exposed to fuel and energy price changes.',
                'Geopolitical instability could cause sharp increases in energy or fuel prices, raising operating costs and reducing margins where cost increases cannot be passed on to customers.',
                ['Dependency on externally sourced energy', 'Exposure to variable energy prices', 'Limited flexibility in pricing or production arrangements'],
                ['Assess exposure under energy-price stress scenarios', 'Evaluate supply diversification and suitable contractual protections', 'Identify feasible energy-efficiency and contingency measures'],
                ['What energy sources and pricing arrangements are used?', 'Which cost increases can be passed through to customers?']),
        ],
    ],
    [
        'keywords' => ['supplier', 'supply chain', 'spare part', 'sourcing'],
        'risks' => [
            $make('Production interruption from single-supplier dependency',
                'Relevant when a critical input has no qualified alternative supplier.',
                'Failure or unavailability of a sole supplier could prevent delivery of critical inputs, causing production interruptions, delayed customer orders, and additional procurement costs.',
                ['Single-source dependency for critical inputs', 'Long supplier qualification or replenishment lead times', 'Insufficient contingency inventory'],
                ['Qualify an alternative supplier', 'Review safety stock against replacement lead time', 'Monitor supplier continuity and delivery performance'],
                ['Which inputs have no qualified alternative?', 'What inventory coverage and replacement lead times apply?']),
            $make('Customer delivery delays from unreliable supplier lead times',
                'Relevant when late inbound deliveries affect your ability to fulfill orders.',
                'Unreliable supplier delivery times could cause material shortages and scheduling changes, delaying fulfillment of customer commitments and increasing expediting costs.',
                ['Variable replenishment lead times', 'Limited visibility of supplier capacity', 'Inadequate planning buffers'],
                ['Monitor on-time delivery and lead-time variance', 'Agree escalation arrangements with critical suppliers', 'Review planning buffers and alternative sourcing options'],
                ['Which suppliers have missed delivery commitments?', 'What is the effect on customer delivery dates?']),
            $make('Product quality failures from inconsistent supplied materials',
                'Relevant when variation in purchased inputs may affect finished-product quality.',
                'Materials that do not consistently meet specifications could lead to product defects, rework, scrap, or customer complaints if incoming checks and process controls do not detect the variation.',
                ['Variation in supplier process capability', 'Unclear or outdated purchase specifications', 'Gaps in incoming verification'],
                ['Review specifications and acceptance criteria', 'Evaluate incoming verification based on material criticality', 'Agree corrective actions and monitoring with suppliers'],
                ['Which material characteristics are critical?', 'What inspection evidence and supplier performance data are available?']),
        ],
    ],
    [
        'keywords' => ['cyber', 'ransomware', 'data breach', 'phishing'],
        'risks' => [
            $make('Operational disruption from ransomware',
                'Relevant when business operations depend on digital systems and recoverable data.',
                'A ransomware incident could make critical systems or data unavailable, interrupting business operations and increasing recovery costs if preventive and recovery controls are ineffective.',
                ['Exposure to malicious messages and compromised credentials', 'Unaddressed vulnerabilities', 'Dependency on shared systems and backup recovery'],
                ['Review multi-factor authentication and privileged access', 'Verify isolated backup restoration', 'Exercise incident response and recovery procedures'],
                ['Which systems are critical to operations?', 'When was a representative backup restoration last verified?']),
            $make('Confidential data exposure through unauthorized access',
                'Relevant when personal, financial, or commercially sensitive data is stored or processed.',
                'Unauthorized access to sensitive information could expose confidential data, leading to contractual, regulatory, or reputational consequences depending on the information and jurisdictions involved.',
                ['Excessive access permissions', 'Compromised or shared credentials', 'Limited monitoring of sensitive-data access'],
                ['Review access according to job responsibilities', 'Strengthen authentication and credential handling', 'Monitor and investigate unusual access patterns'],
                ['Which information needs protection?', 'What access reviews and monitoring already exist?']),
            $make('Financial loss from phishing-enabled payment fraud',
                'Relevant when email or messaging is used to request payments or supplier bank-detail changes.',
                'Fraudulent messages or compromised accounts could cause staff to authorize payments or change bank details incorrectly, resulting in financial loss and recovery effort.',
                ['Reliance on message-based payment instructions', 'Insufficient independent verification', 'Compromised staff or supplier accounts'],
                ['Independently verify changes to payment details', 'Apply appropriate approval separation', 'Train staff using relevant fraud scenarios'],
                ['How are bank-detail changes verified?', 'Which payment approvals and transaction checks are already in place?']),
        ],
    ],
];
