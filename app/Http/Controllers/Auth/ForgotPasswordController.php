<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
// use Illuminate\Notifications\Notification;
use App\Http\Controllers\Controller;
// use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPassword;
use Illuminate\Support\Facades\Lang;
use DB;
use Session;
class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function verifyEmails(Request $request){
        $verifyemail=$this->veryfyEmail($request->email);
        if($verifyemail==0){
           Session::flash('error','Entered Email is Invalid');
           return redirect()->back();
        }
        if($verifyemail==2){
            $token1 = openssl_random_pseudo_bytes(16);
            $token1 = bin2hex($token1);
            // session(['conftoken'=>$token1]);
            DB::table('super_admins')->where('email',$request->email)->update(['token'=>bcrypt($token1)]); 
            $token1 = "why=".$token1;
            $token2 = openssl_random_pseudo_bytes(16);
            $token2 = bin2hex($token2);
            // session(['canctoken'=>$token2]);
            $token2 = "why=".$token2;
            //$confmail="admin@setilas.com";
            $confmail="admin@cgbod.com";
            session(['verificationemail'=>$request->email]);
            $data= array(
                'value'=>'2',
                'line1'=>Lang::get('Dear '.$confmail),
                'line2'=>Lang::get('You are receiving this email because user Client Control with email '.$request->email.' for server '.$request->getHttpHost().' requested for password reset'),
                'line3'=>Lang::get('Please Choose one of the below option to accept or reject the request'),
                'line4'=>url(config('app.url').route('confirmed',$token1,false)),
                'line5'=>url(config('app.url').route('confirmed',$token2,false)),
            );
            Mail::to($confmail)->send(new ForgotPassword($data));
            Session::flash('resetsuccess','Please wait untill you receive confirmation mail');
            return redirect()->intended(route('login'));
        }
        $verificationcode=$this->generateVerification();
        session(['verificationcode'=>$verificationcode]);
        session(['verificationemail'=>$request->email]);
        $data= array(
            'value'=>'1',
            'line1'=>Lang::get('Dear '.$request->email),
            'line2'=>Lang::get('You are receiving this email because we received a password reset request for your account.'),
            'line3' =>Lang::get('Use Verification code: '.$verificationcode.' to reset your password.', ['count' => config('auth.passwords.users.expire')]),  
            'line4' =>Lang::get('If you did not request a password reset, no further action is required.'),
        );
        
           Mail::to($request->email)->send(new ForgotPassword($data));
           Session::flash('status','We have mailed you password verification code');
           return redirect()->back();
        
        }
    public function generateVerification(){
        //String from which otp will be generated;
        $generator=152437689;
        $otp="";
        for($i=1;$i<=6;$i++){
            $otp.=substr($generator,(rand()%(strlen($generator))),1);
        }
        return $otp;
    }

    public function veryfyEmail($email){
        $user=DB::table('users')->where('email',$email)->get();
        if(!$user->isEmpty())
        return 1;
        $sadmin=DB::table('super_admins')->where('email',$email)->get();
        if(!$sadmin->isEmpty())
        return 2;
        return 0;
    }
}
