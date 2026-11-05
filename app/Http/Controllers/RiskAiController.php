<?php

namespace App\Http\Controllers;

use App\Services\RiskAiService;
use App\Services\RiskOrganizationKnowledge;
use Illuminate\Http\Request;

class RiskAiController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:web,admin', 'mfa', 'throttle:10,1']);
    }

    public function draft(Request $request, RiskAiService $service, RiskOrganizationKnowledge $knowledge)
    {
        abort_unless(config('risk_ai.enabled'), 404);
        $context = $request->validate([
            'action' => 'nullable|in:suggest,draft,review,register_review',
            'total_matching' => 'required_if:action,register_review|integer|min:1|max:1000000',
            'risks' => 'required_if:action,register_review|array|min:1|max:50',
            'risks.*.id' => 'required|string|max:190',
            'risks.*.name' => 'required|string|max:190',
            'risks.*.rating' => 'nullable|in:High,Moderate,Low',
            'risks.*.group' => 'nullable|string|max:190',
            'risks.*.area' => 'nullable|string|max:190',
            'risks.*.response' => 'nullable|string|max:500',
            'risks.*.stage' => 'nullable|string|max:190',
            'risks.*.owner_assigned' => 'required|boolean',
            'selected_risk_name' => 'nullable|string|max:190',
            'concern' => 'required|string|min:3|max:10000',
            'risk_description' => 'nullable|string|max:10000',
            'risk_drivers' => 'nullable|string|max:10000',
            'risk_group' => 'nullable|string|max:190',
            'risk_area' => 'nullable|string|max:190',
            'risk_source' => 'nullable|string|max:190',
            'existing_controls' => 'nullable|string|max:10000',
            'impact' => 'nullable|integer|between:1,3|required_with:likelihood',
            'likelihood' => 'nullable|integer|between:1,3|required_with:impact',
        ]);
        // Organization context is resolved server-side; browser-supplied organization data is ignored.
        try {
            $organization = $knowledge->context($context);
            if ($organization) $context['organization'] = $organization;
            $action = $context['action'] ?? 'draft';
            if ($action === 'register_review') $result = $service->registerReview($context);
            elseif ($action === 'review') $result = $service->review($context);
            elseif ($action === 'suggest') $result = $service->suggestions($context);
            else $result = $service->draft($context);
            return response()->json($knowledge->complete($context, $result))->header('Cache-Control', 'no-store');
        } catch (\Exception $exception) {
            // Never return upstream responses or credentials to the browser.
            return response()->json(['message' => 'Risk assistance is temporarily unavailable. Please try again or contact your administrator.'], 502);
        }
    }
}
