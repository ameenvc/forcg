<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManagementAgendaPointList extends Model
{
    protected $table='management_agenda_point_lists';
    protected $fillable = ['points','agenda_type','agenda_point_detail_type'];
}
