<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = "customer";
    protected $fillable = [
      'customer',
      'location',
      'country_id',
      'state_id',
      'pin',
      'email',
      'phone',
      'customer_survey_emails',
      'is_deleted',
      //'contact',
      'product',
      'other_country', 
      'other_state',
    ];

    public function country()
    {
        return $this->belongsTo('App\Country', 'country_id', 'id');
    }

     public function state()
    {
        return $this->belongsTo('App\State', 'state_id', 'id');
    }
}
