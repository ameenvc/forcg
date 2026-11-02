<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Carbon\Carbon;
use App\Admin;
use App\ClientDetail;
use Jenssegers\Agent\Agent;
use Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }



    public function showLoginForm()
    {
        session(['verificationemail' => ""]);
        session(['verificationcode' => ""]);
        session(['app_name' => ""]);
        Session::flash('resetmsg', "");
        $a = 0;
        $logo = "";
        $applogo = "";
        $totalusers = DB::table('users')->get();
        $totaladmins = DB::table('admins')->get();

        session(['curcompsize' => count($totalusers) + count($totaladmins)]);
        $compsize = DB::table('companysize')->where('id', 1)->First();
        session(['compsize' => $compsize->compsize]);
        if (count($totaladmins) > 0) {
            $logo = $totaladmins[0]->name . '.' . 'png';
            $a = 1;
            $applogo = $totaladmins[0]->appname;
            session(['app' => 1]);
            session(['app_name' => $totaladmins[0]->appname]);
            session(['appname_to_display' => $totaladmins[0]->appname_to_display]);
        } else {
            session(['app' => 0]);
            $a = 0;
        }
        $client_logos = ClientDetail::first();  
        return view('auth.login')->with('a', $a)
                                 ->with('logoname', $logo)
                                 ->with('applogo', $applogo)
                                 ->with('client_logos', $client_logos);
    }

    // public function login(Request $request)
    // {
    //     //Validate the form data
    //     $this->validate($request, [
    //         'email' => 'required|email',
    //         'password' => 'required|min:6'

    //     ]);
    //     //Attempt to log super admin in
    //     if (Auth::guard('superadmin')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
    //         return redirect()->intended('/superadmin/home');
    //     }

    //     //Attempt to log the user in
    //     elseif (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
    //         $logperperson = DB::table('companysize')->where('id', 1)->pluck('loginsperperson')->First();
    //         $admin = DB::table('admins')->where('email', $request->get('email'))->get();
    //         if ($logperperson == 0) {
    //             Auth::logout();
    //             return redirect()->back()->with('error', 'Login is Currently blocked please checkback later');
    //         } else if ($admin[0]->logdevices < $logperperson) {

    //             $name = $admin[0]->name;
    //             $email = $admin[0]->email;
    //             $agent = new Agent();
    //             $type = "admin";
    //             $last_login = Carbon::now()->toDateTimeString();
    //             $last_login = Carbon::parse($last_login);
    //             $last_ip = $request->getClientIp();
    //             $browser = $agent->browser() . " (ver: " . $agent->version($agent->browser()) . ")";
    //             if ($agent->isDesktop())
    //                 $device = "Desktop" . "(" . $agent->platform() . "-" . $agent->version($agent->platform()) . ")";
    //             elseif ($agent->isMobile())
    //                 $device = "Mobile" . "(" . $agent->platform() . "-" . $agent->version($agent->platform()) . ")";
    //             elseif ($agent->isTablet())
    //                 $device = "Tablet" . "(" . $agent->platform() . "-" . $agent->version($agent->platform()) . ")";
    //             $data1 = array('name' => $name, 'email' => $email, 'last_login_date' => $last_login->format('d-m-Y'), 'last_login_time' => $last_login->format('g:i A'), 'last_ip' => $last_ip, 'type' => $type, 'browser' => $browser, 'device' => $device);
    //             DB::table('viewlogs')->insert($data1);
    //             $data2 = array('name' => $name, 'email' => $email, 'last_login_date' => $last_login->format('d-m-Y'), 'last_login_time' => $last_login->format('g:i A'), 'last_ip' => $last_ip, 'type' => 'user', 'browser' => $browser, 'device' => $device);
    //             DB::table('viewlogs')->insert($data2);
    //             $logdevices = DB::table('admins')->where('email', $request->get('email'))->pluck('logdevices')->First();
    //             $logdevices = $logdevices + 1;
    //             DB::table('admins')->where('email', $request->get('email'))->update(['logdevices' => $logdevices]);
    //             DB::table('users')->where('email', $request->get('email'))->update(['logdevices' => $logdevices]);
    //             return redirect()->intended('/admin/home');
    //         } else if ($admin[0]->logdevices >= $logperperson) {
    //             auth('admin')->logoutOtherDevices(request('password'));
    //             $name = $admin[0]->name;
    //             $email = $admin[0]->email;
    //             $agent = new Agent();
    //             $type = "admin";
    //             $last_login = Carbon::now()->toDateTimeString();
    //             $last_login = Carbon::parse($last_login);
    //             $last_ip = $request->getClientIp();
    //             $browser = $agent->browser() . " (ver: " . $agent->version($agent->browser()) . ")";
    //             if ($agent->isDesktop())
    //                 $device = "Desktop" . "(" . $agent->platform() . "-" . $agent->version($agent->platform()) . ")";
    //             elseif ($agent->isMobile())
    //                 $device = "Mobile" . "(" . $agent->platform() . "-" . $agent->version($agent->platform()) . ")";
    //             elseif ($agent->isTablet())
    //                 $device = "Tablet" . "(" . $agent->platform() . "-" . $agent->version($agent->platform()) . ")";
    //             $data1 = array('name' => $name, 'email' => $email, 'last_login_date' => $last_login->format('d-m-Y'), 'last_login_time' => $last_login->format('g:i A'), 'last_ip' => $last_ip, 'type' => $type, 'browser' => $browser, 'device' => $device);
    //             DB::table('viewlogs')->insert($data1);
    //             $data2 = array('name' => $name, 'email' => $email, 'last_login_date' => $last_login->format('d-m-Y'), 'last_login_time' => $last_login->format('g:i A'), 'last_ip' => $last_ip, 'type' => 'user', 'browser' => $browser, 'device' => $device);
    //             DB::table('viewlogs')->insert($data2);
    //             DB::table('admins')->where('email', $request->get('email'))->update(['logdevices' => 1]);
    //             DB::table('users')->where('email', $request->get('email'))->update(['logdevices' => 1]);
    //             return redirect()->intended('/admin/home');
    //         }
    //     }



    //     //issue   User Registration Approval
    //     //thease codes are blongs to limiting logins per person for defferent persons
    //     else if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
    //         $logperperson = DB::table('companysize')->where('id', 1)->pluck('loginsperperson')->First();
    //         //checking whether approval is given to the user or not
    //         $user = DB::table('users')->where('email', $request->get('email'))->get();
    //         $admin = Admin::where('email', $user[0]->email)->first();

    //         if ($admin == null && $user[0]->is_email_verified == 0) {
    //             Auth::logout();
    //             return redirect()->back()->with('error', 'Please verify your account');
    //         }
    //         if ($user[0]->approved == 0) {
    //             Auth::logout();
    //             return redirect()->back()->with('error', 'Your account is waiting for our administrator approval Please check back later');
    //         } else if ($logperperson == 0) {
    //             Auth::logout();
    //             return redirect()->back()->with('error', 'Login is Currently blocked please checkback later');
    //         } else if ($user[0]->logdevices < $logperperson) {
    //             $name = $user[0]->name;
    //             $email = $user[0]->email;
    //             $agent = new Agent();
    //             $type = "user";
    //             $last_login = Carbon::now()->toDateTimeString();
    //             $last_login = Carbon::parse($last_login);
    //             $last_ip = $request->getClientIp();
    //             $browser = $agent->browser() . " (ver: " . $agent->version($agent->browser()) . ")";
    //             if ($agent->isDesktop())
    //                 $device = "Desktop" . "(" . $agent->platform() . "-" . $agent->version($agent->platform()) . ")";
    //             elseif ($agent->isMobile())
    //                 $device = "Mobile" . "(" . $agent->platform() . "-" . $agent->version($agent->platform()) . ")";
    //             elseif ($agent->isTablet())
    //                 $device = "Tablet" . "(" . $agent->platform() . "-" . $agent->version($agent->platform()) . ")";
    //             $data = array('name' => $user[0]->name, 'email' => $user[0]->email, 'last_login_date' => $last_login->format('d-m-Y'), 'last_login_time' => $last_login->format('g:i A'), 'last_ip' => $last_ip, 'type' => $type, 'browser' => $browser, 'device' => $device);
    //             DB::table('viewlogs')->insert($data);
    //             $logdevices = DB::table('users')->where('email', $user[0]->email)->pluck('logdevices')->First();
    //             $logdevices = $logdevices + 1;
    //             DB::table('users')->where('email', $user[0]->email)->update(['logdevices' => $logdevices]);
    //             return redirect()->intended('/user/dashboard');
    //         } else if ($user[0]->logdevices >= $logperperson) {
    //             Auth::logoutOtherDevices(request('password'));
    //             $agent = new Agent();
    //             $type = "user";
    //             $last_login = Carbon::now()->toDateTimeString();
    //             $last_login = Carbon::parse($last_login);
    //             $last_ip = $request->getClientIp();
    //             $browser = $agent->browser() . " (ver: " . $agent->version($agent->browser()) . ")";
    //             if ($agent->isDesktop())
    //                 $device = "Desktop" . "(" . $agent->platform() . "-" . $agent->version($agent->platform()) . ")";
    //             elseif ($agent->isMobile())
    //                 $device = "Mobile" . "(" . $agent->platform() . "-" . $agent->version($agent->platform()) . ")";
    //             elseif ($agent->isTablet())
    //                 $device = "Tablet" . "(" . $agent->platform() . "-" . $agent->version($agent->platform()) . ")";
    //             $data = array('name' => $user[0]->name, 'email' => $user[0]->email, 'last_login_date' => $last_login->format('d-m-Y'), 'last_login_time' => $last_login->format('g:i A'), 'last_ip' => $last_ip, 'type' => $type, 'browser' => $browser, 'device' => $device);
    //             DB::table('viewlogs')->insert($data);
    //             DB::table('users')->where('email', $user[0]->email)->update(['logdevices' => 1]);
    //             return redirect()->intended('/user/dashboard');
    //         }
    //         //if approval is given
    //     } else {
    //         return redirect()->back()->withInput($request->only('email', 'remember'))->with('error', 'User Credentials does not match');
    //     }
    // }

    public function login(Request $request)
    {
        // Validate the form data
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);
        Auth::logout();  
        session()->flush();
        $guards = ['superadmin', 'admin', 'web'];

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->attempt([
                'email' => $request->email, 'password' => $request->password], 
                $request->remember)) {
                return $this->handleLoginSuccess($guard, $request);
            }
        }

        return redirect()->back()->withInput($request->only('email', 'remember'))->with('error', 'User Credentials do not match');
    }

    private function handleLoginSuccess($guard, Request $request)
    {
        Auth::shouldUse($guard);
        if ($guard === 'superadmin') {
            return redirect()->intended('/superadmin/home');
        }

        $user = DB::table('users')
            ->where('email', $request->email)
            ->first();

        $logPerPerson = DB::table('companysize')->where('id', 1)->value('loginsperperson');

        if ($guard === 'web' && (!$user || !$user->is_email_verified)) {
            Auth::logout();
            return redirect()->back()->with('error', 'Please verify your account');
        }

        if ($user->approved == 0) {
            Auth::logout();
            return redirect()->back()->with('error', 'Your account is waiting for approval');
        }

        if ($logPerPerson == 0) {
            Auth::logout();
            return redirect()->back()->with('error', 'Login is currently blocked');
        }

        return $this->logDeviceAndRedirect($guard, $user, $logPerPerson, $request);
    }

    private function logDeviceAndRedirect($guard, $user, $logPerPerson, Request $request)
    {
        $agent = new Agent();
        $deviceType = $agent->isDesktop() ? 'Desktop' : ($agent->isMobile() ? 'Mobile' : 'Tablet');
        $device = $deviceType . " (" . $agent->platform() . "-" . $agent->version($agent->platform()) . ")";
        $browser = $agent->browser() . " (ver: " . $agent->version($agent->browser()) . ")";
        $lastLogin = Carbon::now();
        $lastIp = $request->getClientIp();

        if ($user->logdevices < $logPerPerson) {
            DB::table('viewlogs')->insert([
                'name' => $user->name,
                'email' => $user->email,
                'last_login_date' => $lastLogin->format('d-m-Y'),
                'last_login_time' => $lastLogin->format('g:i A'),
                'last_ip' => $lastIp,
                'type' => $guard,
                'browser' => $browser,
                'device' => $device,
            ]);

            DB::table('users')
                ->where('email', $user->email)
                ->increment('logdevices');
            if(DB::table('admins')->where('email', $user->email)->exists()){
                DB::table('admins')
                    ->where('email', $user->email)
                    ->increment('logdevices');
            }
            return redirect()->intended('/user/home');
        } else {
            Auth::logoutOtherDevices(request('password'));

            DB::table('users')
                ->where('email', $user->email)
                ->update(['logdevices' => 1]);
            if(DB::table('admins')->where('email', $user->email)->exists()){
                DB::table('admins')
                    ->where('email', $user->email)
                    ->update(['logdevices' => 1]);
            }
            return redirect()->intended('/user/home');
        }
    }

}
