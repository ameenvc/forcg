<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wastemaster extends Model
{
    protected $fillable = [
        'department', 'responsibility',
        'waste_name', 'waste_code', 'waste_type', 'monthly_expected_qty',
        'yearly_expected_qty', 'unit', 'unit_other', 'agency',
        'agency_license', 'posted_by'
    ];
    public function disposalMethod()
    {
        return $this->hasOne('App\Wastedisposalmethod', 'id', 'disposal_method');
    }
}
