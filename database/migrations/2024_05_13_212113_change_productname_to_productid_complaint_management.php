<?php
use Illuminate\Support\Facades\DB;
use App\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeProductnameToProductidComplaintManagement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $complaints = DB::table('complaint')->get();
        foreach($complaints as $comp)
        {
            $comproduct = $comp->product;
            $productid = Product::where('product',$comproduct)->value('id');
            $comp->product = $productid ? $productid : $comproduct;
            DB::table('complaint')->where('id', $comp->id)->update(['product' => $comp->product]);

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $complaints = DB::table('complaint')->get();
        foreach($complaints as $comp)
        {
            $comproduct = $comp->product;
            $productname = Product::where('id',$comproduct)->value('product');
            $comp->product = $productname ? $productname : '-';
            DB::table('complaint')->where('id', $comp->id)->update(['product' => $comp->product]);

        }
    }
}
