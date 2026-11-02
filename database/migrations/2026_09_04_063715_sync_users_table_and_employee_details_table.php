<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SyncUsersTableAndEmployeeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $users = DB::table('users')->get();
        foreach ($users as $user) {

            $employee = DB::table('employee_details')->where('emp_number', $user->employeeid)->first();
            $department = DB::table('departments')->where('departmentname', $user->deptid)->first();

            if ($employee) {
                DB::table('employee_details')->where('emp_number', $user->employeeid)->update(
                    [
                        'emp_name'   => $user->name,
                        'emp_department' => $department ? $department->id : '-',
                        'is_deleted' => 0,
                        'updated_at' => now(),
                    ]
                );
            } else {
                DB::table('employee_details')->insert(
                    [
                        'emp_number' => $user->employeeid,
                        'emp_name'   => $user->name,
                        'emp_position' => '-',
                        'emp_department' => $department ? $department->id : '-',
                        'joining_date' => '-',
                        'emp_dob' => '-',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
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
        //
    }
}
