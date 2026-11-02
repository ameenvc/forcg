<?php
use App\Customer;
use App\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeProductInCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $customers = Customer::all();
        foreach($customers as $c)
        {
            $p = explode(',',$c->product);
            $result = [];
            foreach($p as $pd){
                $pid = Product::where('product',$pd)->value('id');
                $result[] = $pid ? $pid : $pd;
            }
            $c->product = implode(',',$result);
            $c->save();

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $customers = Customer::all();
        foreach($customers as $c)
        {
            $p = explode(',',$c->product);
            foreach($p as $pd){
                $pname = Product::where('id',$pd)->value('product');
                $result[] = $pname ? $pname : '-';
            }
            $c->product = implode(',',$result);
            $c->save();

        }
    }
}
