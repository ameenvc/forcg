<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateDefaultValueForProcessVendorCategoryNameInCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('categories')->where('categoryname','Process Non- Conformity')->update(['is_process_cat' => 'yes']);
        DB::table('categories')->where('categoryname','Vendor Non- Conformity')->update(['is_vendor_cat' => 'yes']);        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('categories')->where('categoryname','Process Non- Conformity')->update(['is_process_cat' => 'no']);
        DB::table('categories')->where('categoryname','Vendor Non- Conformity')->update(['is_vendor_cat' => 'no']);
    }
}
