<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EmailNotification
 * @package App
 */
/**
 * @property mixed module
 * @property mixed item
 * @property mixed description
 * @property mixed email_content
 * @property mixed user_email_notifications
 * @property mixed email_send_to_users
 * @property mixed frequently
 */
class EmailNotification extends Model
{
    /**
     * ------------------------------
     * Frequently constant
     * ------------------------------
     */
    const DAILY_STATUS = 'daily';
    const WEEKLY_STATUS = 'weekly';
    const MONTHLY_STATUS = 'monthly';
    const YEARLY_STATUS = 'yearly';
    /**
     * ------------------------------
     * Table name
     * ------------------------------
     */
    protected $table = "email_notifications";

    /**
     * ------------------------------
     * Fillable fields
     * ------------------------------
     */
    protected $fillable = [
        'module', 'item', 'description', 'email_content',
        'user_email_notifications', 'email_send_to_users', 'frequently'
    ];
}
