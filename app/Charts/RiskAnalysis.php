<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\Chartjs\Chart;

class RiskAnalysis extends Chart
{
    const RISK_GROUP = [
        'Strategic' => 'Strategic',
        'Operational' => 'Operational',
        'Compliance' => 'Compliance',
        'Financial' => 'Financial',
    ];

    /**
     * Initializes the chart.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
}
