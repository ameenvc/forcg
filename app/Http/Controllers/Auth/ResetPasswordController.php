<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Session;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Lang;
use App\Mail\PasswordResetCompleted;
use App\Mail\PasswordResetRejected;
use Illuminate\Support\Facades\Hash;
class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function verifyVerificationcode(Request $request){
        $verificationCode=$request->get('vcode');
        if($verificationCode!==Session::get('verificationcode')){
        Session::flash('msg','Verification  Code Entered is Incorrect please try again');
        Session::flash('status',"");
        return redirect()->back();
        }
        else{
            $verificationemail=Session::get('verificationemail');
            session(['verificationemail'=>""]);
            session(['verificationcode'=>""]);
            return view('auth/passwords/reset')->with('email',$verificationemail);
        }
        
    }
 public function confirmResetPassword(Request $request){
     $verificationemail=$request->email;
     $name="";
     $user=DB::table('users')->where('email',$verificationemail)->get();
     if(!$user->isEmpty())
     {
        DB::table('users')->where('email',$verificationemail)->update(['password'=>bcrypt($request->get('password'))]); 
        $name=$user[0]->name;
     }
  
     $admin=DB::table('admins')->where('email',$verificationemail)->get();
     if(!$admin->isEmpty())
     {
        DB::table('admins')->where('email',$verificationemail)->update(['password'=>bcrypt($request->get('password'))]); 
        $name=$admin[0]->name;
    }

    // $sadmin=DB::table('super_admins')->where('email',$verificationemail)->get();
    //  if(!$sadmin->isEmpty())
    // {
    //     DB::table('super_admins')->where('email',$verificationemail)->update(['password'=>bcrypt($request->get('password'))]); 
    //     $name=$sadmin[0]->name;    
    // }
    
    $maildata= array(
        'value'=>1,
        'line1'=>Lang::get('Dear '.$name),
        'line2'=>Lang::get('Your Password for the QuickMS account '.$verificationemail.' has been successfully changed.'),
    );
    Mail::to($verificationemail)->send(new PasswordResetCompleted($maildata));

    Session::flash('resetsuccess','Password Reset Successfull');
    return redirect()->intended(route('login'));
     
    
} 
 public function SuperadminResetConfrmCancel(){
     $sadmin= DB::table('super_admins')->get();
    if ((Hash::check($_GET['why'], $sadmin[0]->token)))
    {
        // $email= DB::table('super_admins')->get();
        $password=$this->generatePass(6,'0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz');
        DB::table('super_admins')->where('email',$sadmin[0]->email)->update(['password'=>bcrypt($password)]); 
        $maildata= array(
            'value'=>2,
            'line1'=>Lang::get('Dear '.$sadmin[0]->name),
            'line2'=>Lang::get('Your password reset request for QuickMS account '.$sadmin[0]->email.' is approved and new password is '.$password."."),
            'line3'=>Lang::get('Use above password to access your QuickMS account in future.'),
        );
        Mail::to($sadmin[0]->email)->send(new PasswordResetCompleted($maildata));
        DB::table('super_admins')->where('email',$sadmin[0]->email)->update(['token'=>""]); 
        Session::flash('resetmsg','Reset password request of '.$sadmin[0]->name.' confirmed successfully');
        return redirect()->intended(route('login'));
    }
    else{

        $maildata= array(
            'line1'=>Lang::get('Dear '.$sadmin[0]->name),
            'line2'=>Lang::get('Your password reset request for QuickMS account '.$sadmin[0]->email.' is rejected.'),
            'line3'=>Lang::get('Please retry some times later.'),
        );
        Mail::to($sadmin[0]->email)->send(new PasswordResetRejected($maildata));
         session(['conftoken'=>""]);
        session(['canctoken'=>""]);
        session(['verificationemail'=>""]);
        DB::table('super_admins')->where('email',$sadmin[0]->email)->update(['token'=>""]); 
        Session::flash('resetmsg','Reset password request of '.$sadmin[0]->name.' rejected successfully');
        return redirect()->intended(route('login'));
    }
 }

 public function generatePass($len,$key){
     $str='';
     $max=mb_strlen($key,'8bit')-1;
      for($i=0;$i<$len;$i++){
          $str.=$key[random_int(0,$max)];
      }
      return $str;
     }
}
