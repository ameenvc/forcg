<?php

namespace App\Http\Middleware\Opportunity;

use Closure;
use Illuminate\Http\Request;
use App\Opportunity;
use App\Models\Opportunityapprover;
use Illuminate\Support\Facades\Session;

class ProtectOpportunityInitialReviewUrlMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->id) {
            $id = $request->id;
        }

        if($request->posted) {
            $id = $request->posted;
        }

        $opportunity = Opportunity::with('opportunityapprover')->where('id', $id)->first();

        if($opportunity->status != 'Review Pending') {
            Session::flash('errmsg', 'This Opportunity already approved or rejected');
            return redirect('/user/opportunity');
        }

        if(optional($opportunity->opportunityapprover)->email == auth()->user()->email) {
            return $next($request);
        } elseif($opportunity->opportunityapprover == null) {
            $opportunityapproversEmail = Opportunityapprover::get()->pluck('email')->toArray();
            if(in_array(auth()->user()->email, $opportunityapproversEmail)){
                return $next($request);
            } else {
                Session::flash('errmsg', 'This Opportunity Approver has been reassigned to another approver');
                return redirect('/user/opportunity');
            }
        } else {
            Session::flash('errmsg', 'This Opportunity Approver has been reassigned to another approver');
            return redirect('/user/opportunity');
        }
    }
}
