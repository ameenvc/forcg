<?php

namespace App\Http\Controllers;

use App\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{

    public function getLocationsOfADepartment(int $id)
    {
        $locations = Location::where('locationable_id', $id)->where('locationable_type', 'App\Departments')->get();

        return [
            'data' => $locations,
            'error' => null
        ];
    }

    public function getLocationsOfADivision(int $id)
    {
        $locations = Location::where('locationable_id', $id)->where('locationable_type', 'App\Division')->get();

        return [
            'data' => $locations,
            'error' => null
        ];
    }
}
