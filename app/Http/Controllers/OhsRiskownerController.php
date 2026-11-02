<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeOhsRiskownerRequest;
use App\OhsRiskowner;
use Illuminate\Http\Request;

class OhsRiskownerController extends Controller
{

    public function ajaxChangeRiskOwners(ChangeOhsRiskownerRequest $request)
    {
        $ohs_id = $request->input('ohs_id');
        $users = $request->input('users');
        $data = [];
        OhsRiskowner::where('ohs_id', $ohs_id)->delete();
        foreach($users as $user) {
            $data[] = [
                'user_id' => $user,
                'ohs_id' => $ohs_id
            ];
        }
        OhsRiskowner::insert($data);

        return [
            'data' => null,
            'error' => null
        ];

    }
}
