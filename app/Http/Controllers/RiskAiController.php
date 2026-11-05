<?php

namespace App\Http\Controllers;

use App\Services\RiskAiService;
use Illuminate\Http\Request;

class RiskAiController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:web,admin', 'mfa', 'throttle:10,1']);
    }

    public function draft(Request $request, RiskAiService $service)
    {
        abort_unless(config('risk_ai.enabled'), 404);
        $context = $request->validate([
            'concern' => 'required|string|min:10|max:10000',
            'risk_group' => 'nullable|string|max:190',
            'risk_area' => 'nullable|string|max:190',
            'risk_source' => 'nullable|string|max:190',
            'existing_controls' => 'nullable|string|max:10000',
            'impact' => 'nullable|integer|between:1,3|required_with:likelihood',
            'likelihood' => 'nullable|integer|between:1,3|required_with:impact',
        ]);
        // Draft only from supplied form text: no database reads or record writes.
        try {
            return response()->json($service->draft($context))->header('Cache-Control', 'no-store');
        } catch (\Exception $exception) {
            // Never return upstream responses or credentials to the browser.
            return response()->json(['message' => 'Risk assistance is unavailable. Check provider configuration or retry.'], 502);
        }
    }
}
