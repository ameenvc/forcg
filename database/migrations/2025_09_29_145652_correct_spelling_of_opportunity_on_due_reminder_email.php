<?php

use Illuminate\Database\Migrations\Migration;

class CorrectSpellingOfOpportunityOnDueReminderEmail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $record = DB::table('email_notifications')
                    ->where('module','opportunity')
                    ->where('item', 'Opportunity On Due Remainder')
                    ->first();
        if($record){
            // If the record exists, we can choose to either update it or delete it.
            // Here, we'll update it to the correct spelling.
            DB::table('email_notifications')
                ->where('module','opportunity')
                ->where('item', 'Opportunity On Due Remainder')
                ->update(['item' => 'Opportunity On Due Reminder']);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $record = DB::table('email_notifications')
                    ->where('module','opportunity')
                    ->where('item', 'Opportunity On Due Reminder')
                    ->first();
        if($record){
            // If the record exists, we can choose to either update it or delete it.
            // Here, we'll update it back to the incorrect spelling.
            DB::table('email_notifications')
                ->where('module','opportunity')
                ->where('item', 'Opportunity On Due Reminder')
                ->update(['item' => 'Opportunity On Due Remainder']);
        }
    }
}
