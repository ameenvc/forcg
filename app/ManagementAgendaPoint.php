<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManagementAgendaPoint extends Model
{
    protected $table='management_agenda_points';
    protected $fillable = ['points','agenda_type','detail_type'];
}

