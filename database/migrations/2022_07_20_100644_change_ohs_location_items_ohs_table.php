<?php

use Illuminate\Database\Migrations\Migration;
use App\Ohs;
use App\Location;

class ChangeOhsLocationItemsOhsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $ohs = Ohs::all();
        $arr = [];
        foreach ($ohs as $item) {
            if (!intval($item->location) && $item->location) {
                $arr[] = [$item->location, $item->department];
            }
        }
        $arr = array_map("unserialize", array_unique(array_map("serialize", $arr)));
        foreach ($arr as $item) {
            Location::create([
                'name' => $item[0],
                'locationable_id' => $item[1],
                'locationable_type' => 'App\Departments'
            ]);
        }
        foreach ($ohs as $item) {
            $location = Location::where('locationable_id', $item->department)
                ->where('locationable_type', 'App\Departments')
                ->where('name', $item->location)->value('id');
            if ($location != null) {
                Ohs::where('department', $item->department)->where('location', $item->location)->update([
                    'location' => $location
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
