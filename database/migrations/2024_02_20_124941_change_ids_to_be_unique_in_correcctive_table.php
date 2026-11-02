<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChangeIdsToBeUniqueInCorrecctiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $corrective_categories = DB::table('corrective')->groupBy('category')->pluck('category')->toArray();
        $corrective_categories = DB::table('categories')->whereIn('id', $corrective_categories)->pluck('categoryname')->toArray();

        for ($i = 0; $i < count($corrective_categories); $i++) {

            $cat = DB::table('categories')->where('categoryname', $corrective_categories[$i])->first();

            $corrective_cat_id = $cat ? $cat->id : 0;

            $correctives = DB::table('corrective')->select('id', 'count', 'issueid', 'year', 'postdate', 'category')->where('category', $corrective_cat_id)->orderBy('id', 'asc')->get();

            for ($j = 0; $j < count($correctives); $j++) {

                $post_date_different_format = Carbon::createFromFormat('d-m-Y h:i:s A', $correctives[$j]['postdate'])->format('Y-m-d H:i:s');

                $the_year = date("Y", strtotime($post_date_different_format));

                $the_year = substr($the_year, -2);

                DB::table('corrective')->where('category', $corrective_cat_id)->where('id', $correctives[$j]['id'])->update([
                    'count' => $j + 1,
                    'issueid' => strtoupper(substr($corrective_categories[$i], 0, 3)) . ($j + 1) . '/' . $corrective_cat_id . '/' . $the_year,
                ]);

            }
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
