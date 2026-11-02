<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManagementAgendaType extends Model
{
    protected $table='management_agenda_types';
    protected $fillable = ['name'];
}
