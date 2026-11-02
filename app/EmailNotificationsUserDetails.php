<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailNotificationsUserDetails extends Model
{
    protected $table = 'email_notifications_user_details';
    protected $fillable = ['notification_id','user_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
