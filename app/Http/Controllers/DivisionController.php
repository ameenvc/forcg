<?php

namespace App\Http\Controllers;

use App\Division;
use App\Location;

class DivisionController extends Controller
{

    public function getDivisionOfALocation(int $id)
    {
        $location = Location::find($id);
        $division = Division::where('id', $location->locationable_id)->first();
        
        return [
            'data' => $division,
            'error' => null
        ];
    }
}
