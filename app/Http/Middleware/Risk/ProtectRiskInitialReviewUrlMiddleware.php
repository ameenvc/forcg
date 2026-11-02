<?php

namespace App\Http\Middleware\Risk;

use Closure;
use Illuminate\Http\Request;
use App\Risk;
use App\Riskreviewer;
use Session;

class ProtectRiskInitialReviewUrlMiddleware
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
        if($request->val) {
            $id = $request->val;
        }

        if($request->posted){
            $id = $request->posted;
        }

        $risk = Risk::with('riskreviewer')->where('Rid', $id)->first();

        if((optional($risk->riskreviewer)->email == auth()->user()->email)) {
            return $next($request);
        } elseif($risk->riskreviewer == null) {
            $riskreviewersEmail = Riskreviewer::get()->pluck('email')->toArray();
            if(in_array(auth()->user()->email, $riskreviewersEmail)){
                return $next($request);
            } else {
                Session::flash('errmsg', 'This risk review has been reassigned to another reviewer');
                return redirect('/user/riskmgmt');
            }
        } else {
            Session::flash('errmsg', 'This risk review has been reassigned to another reviewer');
            return redirect('/user/riskmgmt');
        }

    }
}
