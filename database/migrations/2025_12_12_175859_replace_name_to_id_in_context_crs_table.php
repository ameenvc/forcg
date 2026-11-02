<?php

use App\Changecategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\User;
use App\Departments;
use App\ContextCrs;
use App\Priority;
use Illuminate\Support\Facades\DB;

class ReplaceNameToIdInContextCrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {

            // Cache lookups for performance
            $usersByName = User::withTrashed()
                ->get()
                ->keyBy(function ($u) {
                    return strtolower(trim($u->name));
                });

            $departmentsByName = Departments::all()
                ->keyBy(function ($d) {
                    return strtolower(trim($d->departmentname));
                });
            
            $priorities = Priority::all()
                ->keyBy(function ($p) {
                    return strtolower(trim($p->name));
                });

            $categories = Changecategory::all()
                ->keyBy(function ($c) {
                    return strtolower(trim($c->category));
                } );

            ContextCrs::chunk(100, function ($records) use ($usersByName, $departmentsByName,$priorities, $categories) {

                foreach ($records as $row) {

                    $update = [];

                    // Department
                    if (!is_numeric($row->department)) {
                        $deptKey = strtolower(trim($row->department));
                        if (isset($departmentsByName[$deptKey])) {
                            $update['department'] = $departmentsByName[$deptKey]->id;
                        }
                    }
                    /* ---------- Priority ---------- */
                    if (!empty($row->priority) && !is_numeric($row->priority)) {
                        $key = strtolower(trim($row->priority));
                        if (isset($priorities[$key])) {
                            $update['priority'] = $priorities[$key]->id;
                        }
                    }
                    // User-based fields
                    $userFields = [
                        'reviewed_by',
                        'approved_by',
                        'appr_authority',
                        'rev_authority',
                        'initiator',
                        'closed_by',
                    ];

                    foreach ($userFields as $field) {
                        if (!empty($row->$field) && !is_numeric($row->$field)) {
                            $userKey = strtolower(trim($row->$field));
                            if (isset($usersByName[$userKey])) {
                                $update[$field] = $usersByName[$userKey]->id;
                            }
                        }
                    }
                    /* ---------- Category ---------- */
                    if (!empty($row->category)) {
                        $parts = array_map('trim', explode(',', $row->category));

                        $finalValues = [];
                        $hasResolved = false;

                        foreach ($parts as $part) {

                            if (is_numeric($part)) {
                                $finalValues[] = $part;
                                $hasResolved = true;
                                continue;
                            }

                            $key = strtolower($part);

                            if (isset($categories[$key])) {
                                $finalValues[] = $categories[$key]->id;
                                $hasResolved = true;
                            } else {
                                $finalValues[] = $part;
                            }
                        }

                        if ($hasResolved) {
                            $update['category'] = implode(',', array_unique($finalValues));
                        }
                    }

                    if (!empty($update)) {
                        ContextCrs::where('id', $row->id)->update($update);
                    }
                }
            });
        });
    }
    
}
