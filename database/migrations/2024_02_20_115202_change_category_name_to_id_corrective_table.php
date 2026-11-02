<?php

use App\Categories;
use Illuminate\Database\Migrations\Migration;
use App\Corrective;

class ChangeCategoryNameToIdCorrectiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $correctives = Corrective::all();
        foreach($correctives as $item) {
            $cat = Categories::where('categoryname', $item->category)->first();
            $cat_id = $cat ? $cat->id : 0;
            $item->category = $cat_id;
            $item->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
