<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\GovernanceHubComment;

class AddReadByUserToGovernanceHubCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('governance_hub_comments', function (Blueprint $table) {
             $table->longText('read_by_user')->after('record_id');
        });
       
        $comments = GovernanceHubComment::all();
        foreach($comments as $comment){
            $comment->read_by_user = 0;
            $comment->save();
        }    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('governance_hub_comments', function (Blueprint $table) {
            $table->dropColumn('read_by_user');
        });
    }
}
