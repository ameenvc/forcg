<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\State;

class CountryController extends Controller
{
    public function getState(int $id)
    {
        $states = State::where('country_id', $id)->get();

        return [
            'id' => $id,
            'data' => $states,
            'error' => null
        ];
    }
}
