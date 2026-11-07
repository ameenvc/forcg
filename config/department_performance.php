<?php
return [
    'version'=>'1.0', 'days'=>90, 'minimum_records'=>5, 'minimum_coverage'=>60,
    'metrics'=>[
        'on_time'=>['label'=>'On-time action completion','weight'=>35],
        'backlog'=>['label'=>'Overdue action control','weight'=>25],
        'audits'=>['label'=>'Scheduled audits completed','weight'=>20],
        'objectives'=>['label'=>'Recorded objectives achieved','weight'=>20],
    ],
    // Use the record-owning department ID, never names, record authors or employee headcounts.
    'actions'=>[
        ['riskmanagement','mitigations','department_id','status','due_date',['reviewed_date'],'priority_level',['Closed']],
        ['correctiveaction','corrective','department','processstage','duedate',['completion_date'],'type',['Closed','Closed After Due','Accomplished']],
        ['auditmanagement','issues','department','processstage','duedate',['closed','completion_date'],'type',['Closed','Closed After Due','Accomplished']],
        ['hse','hseissues','department','processstage','duedate',['closed','completion_date'],'type',['Closed','Closed After Due','Accomplished']],
        ['nearmiss','nearmiss','department','processstage','targetdate',['closed','completion_date'],'type',['Closed','Closed After Due','Accomplished']],
    ],
    'obligations'=>[
        ['audits','auditmanagement','audit','department','status','enddate',['Completed']],
        ['objectives','objectives','objectives','department','status','todate',['Achieved']],
    ],
];
