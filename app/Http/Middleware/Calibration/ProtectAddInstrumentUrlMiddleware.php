<?php

namespace App\Http\Middleware\Calibration;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Support\Facades\Gate;

use Closure;

class ProtectAddInstrumentUrlMiddleware
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
        $addCalibration = Gate::allows('add-calibration');
        if ($addCalibration) {
            return $next($request);
        } else {
            Session::flash('errmsg', 'You are not authorized to perform this action. Contact Admin');
            return redirect('/user/calibrationmgmt');
        }
    }
}
