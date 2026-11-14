<?php
use Illuminate\Support\Facades\DB;
use App\Customer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeCustomerInComplaintManagement extends Migration
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
            $compcust = $comp->customer;
            $custid = Customer::where('customer',$compcust)->value('id');
            $comp->customer = $custid ? $custid : $compcust;
            DB::table('complaint')->where('id', $comp->id)->update(['customer' => $comp->customer]);

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
            $compcust = $comp->customer;
            $custname = Customer::where('id',$compcust)->value('customer');
            $comp->customer = $custname ? $custname : '-';
            DB::table('complaint')->where('id', $comp->id)->update(['customer' => $comp->customer]);

        }
    }
}


