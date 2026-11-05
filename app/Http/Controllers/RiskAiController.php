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
            'action' => 'nullable|in:suggest,draft',
            'selected_risk_name' => 'nullable|string|max:190',
            'concern' => 'required|string|min:3|max:10000',
            'risk_group' => 'nullable|string|max:190',
            'risk_area' => 'nullable|string|max:190',
            'risk_source' => 'nullable|string|max:190',
            'existing_controls' => 'nullable|string|max:10000',
            'impact' => 'nullable|integer|between:1,3|required_with:likelihood',
            'likelihood' => 'nullable|integer|between:1,3|required_with:impact',
        ]);
        // Draft only from supplied form text: no database reads or record writes.
        try {
            $result = ($context['action'] ?? 'draft') === 'suggest' ? $service->suggestions($context) : $service->draft($context);
            return response()->json($result)->header('Cache-Control', 'no-store');
        } catch (\Exception $exception) {
            // Never return upstream responses or credentials to the browser.
            return response()->json(['message' => 'Risk assistance is temporarily unavailable. Please try again or contact your administrator.'], 502);
        }
    }
}
