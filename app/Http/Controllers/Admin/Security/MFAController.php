<?php

namespace App\Http\Controllers\Admin\Security;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ModenaDisable;
use App\Models\AppConfiguration;
use Illuminate\Support\Facades\Auth;

class MFAController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:admin', 'mfa']);
    }

    public function index()
    {
        $models = Modenadisable::first();
        $mfa = AppConfiguration::where('meta_key', 'enable_mfa')->first();
        return view('admin.security.mfa', compact('mfa'))->with('model', $models);
    }

    public function enableDisableMFA(Request $request)
    {
        AppConfiguration::where('meta_key', 'enable_mfa')
            ->update([
                'meta_value' => $request->mfa,
                'updated_by' => Auth::guard('admin')->user()->user->id,
            ]);
        return response()->json([
                'status' => true,
                'mfa' => $request->mfa,
                'message' => 'Updated MFA',
            ]);
    }
}
