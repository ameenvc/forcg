<?php
use App\Complaint;
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
        $complaints = Complaint::all();
        foreach($complaints as $comp)
        {
            $compcust = $comp->customer;
            $custid = Customer::where('customer',$compcust)->value('id');
            $comp->customer = $custid ? $custid : $compcust;
            $comp->save();

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $complaints = Complaint::all();
        foreach($complaints as $comp)
        {
            $compcust = $comp->customer;
            $custname = Customer::where('id',$compcust)->value('customer');
            $comp->customer = $custname ? $custname : '-';
            $comp->save();

        }
    }
}


