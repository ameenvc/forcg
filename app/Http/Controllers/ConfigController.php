<?php

namespace App\Http\Controllers;

use App\Config;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    public function editProductName(Request $request)
    {
        $request->validate([
            'name' => ['required','string','max:255']
        ]);
        $name = $request->input('name');
        $id = $request->input('id');

        Config::where('id', $id)->update([
            'name' => strtolower($name)
        ]);

        return [
            'data' => null,
            'error' => null
        ];
    }
}
