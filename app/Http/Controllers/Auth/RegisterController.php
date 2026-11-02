<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Admin;
use App\Departments;
use App\UserVerify;
use App\EmployeeDetails;
use Str;
use DB;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use App\Mail\UserWaiting;
use App\Mail\AccountWaiting;
use App\Mail\UserEmailVerificationlink;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed', 'regex:/^(?=.*[a-zA-Z])(?=.*\d).+$/'],
            'employeeid' => ['required', 'string', 'max:255', 'unique:users,employeeid'],
            'profile_pic' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,bmp,tiff', 'max:4096'],
            'deptid' => ['nullable', 'integer'],
            //'signup-terms' => ['required']
        ], [
            'employeeid.unique' => 'The entered Employee ID is already in use. Please enter a unique Employee ID.',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $request = app('request');
        $filename = null;
        if ($request->hasfile('profile_pic')) {
            $avatar = $request->file('profile_pic');
            $filename = $data['employeeid'] . '.' . $avatar->getClientOriginalExtension();
            $destination_path = public_path('storage/profile');
            $avatar->move($destination_path, $filename);
            //  Image::make($avatar)->resize(300, 300)->save( public_path('/uploads/avatars/' . $filename) );
        } else {
            $textColor = 'FFFFFF';
            $backgroundColors = [
                '1F42AD',
                '1F42AD',
                'F5993D',
                'E64C73',
                'AD858F',
                '9952E0',
                '75ABBD',
                'AD661F',
                'C63939',
                '0D80F2',
                'E87DB3',
                '7594F0',
                '7DA1E8'
            ];
            $backgroundColorIndex = array_rand($backgroundColors);
            $image = file_get_contents('https://ui-avatars.com/api/?name=' . rawurlencode(ucwords($data['name'])) . '&color=' . $textColor . '&background=' . $backgroundColors[$backgroundColorIndex] . '&rounded=true&bold=true');
            $filename = (isset($data['employeeid']) && $data['employeeid'] ? $data['employeeid'] : rand(1000, 2000)) . '_' . time() . '.png';
            file_put_contents(public_path('storage/profile') . '/' . $filename, $image);

            // $filename = null;
        }
        $cap = ucwords($data['name']);
        $department = $data['dept'] ? Departments::where('id', $data['dept'])->value('departmentname') : null;
        User::create([
            'name' => $cap,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'employeeid' => $data['employeeid'],
            'profile_picture' => $filename,
            'deptid' => $department ?? null
        ]);

        $employeeDetail = EmployeeDetails::where('emp_number', trim($data['employeeid']))->first();
        if ($employeeDetail) {
            EmployeeDetails::where('emp_number', $employeeDetail->emp_number)->update([
                'emp_name'   => $cap,
                'emp_department' => $data['dept'],
                'is_deleted' => 0,
                'updated_at' => now(),
            ]);
        } else {
            EmployeeDetails::create([
                'emp_number' => trim($data['employeeid']),
                'emp_name' => $cap,
                'emp_position' => '-',
                'emp_department' => $data['dept'],
                'joining_date' => '-',
                'emp_dob' => '-'
            ]);
        }
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        $this->create($request->all());
        $user = User::where('email', $request->email)->first();
        $token = Str::random(64);

        UserVerify::create([
            'user_id' => $user->id,
            'token' => $token
        ]);
        /*------
        try {
            Mail::send('emails.emailVerificationEmail', ['token' => $token], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Email Verification Mail');
            });
            //return redirect($this->redirectTo)->with('resetsuccess', 'Registration Successful. Please verify your email');
        } catch (\Exception $e) {
            Log::info('verification email error is ' . $e->getMessage());
            //return redirect()->back()->with('verifyError', 'A Verification Email error occured. Please Sign Up again.');
        }
        ------*/
        return redirect($this->redirectTo)->with('resetsuccess', 'Registration Successful. Please verify your email')->with('user_id', $user->id);

    }
    public function sendRegistrationEmail(Request $request){
        $user = User::find($request->id);
        if ($user->profile_picture==null) {
            $textColor = 'FFFFFF';
            $backgroundColors = [
                '1F42AD',
                '1F42AD',
                'F5993D',
                'E64C73',
                'AD858F',
                '9952E0',
                '75ABBD',
                'AD661F',
                'C63939',
                '0D80F2',
                'E87DB3',
                '7594F0',
                '7DA1E8'
            ];
            $backgroundColorIndex = array_rand($backgroundColors);
            $image = file_get_contents('https://ui-avatars.com/api/?name=' . rawurlencode(ucwords($user->name)) . '&color=' . $textColor . '&background=' . $backgroundColors[$backgroundColorIndex] . '&rounded=true&bold=true');
            $filename = (isset($user->employeeid) && $user->employeeid ? $user->employeeid : rand(1000, 2000)) . '_' . time() . '.png';
            if (!File::exists(public_path('/storage/profile/'))) {
                File::makeDirectory(public_path() . '/storage/profile/', 0755, true);
            }
            file_put_contents(public_path('storage/profile') . '/' . $filename, $image);
            $user->update(['profile_picture' => $filename]);
        }
        if ($user) {
            try {
                Mail::to($user->email)->send(new AccountWaiting($user));
            } catch (\Exception $e) {
                Log::info('AccountWaiting email error: ' . $e->getMessage());
            }
            try {
                $admin = Admin::pluck('email');
                Mail::to($admin)->send(new UserWaiting($user));
            } catch (\Exception $e) {
                Log::info('UserWaiting email error: ' . $e->getMessage());
            }
        }
        $token = UserVerify::where('user_id', $user->id)->value('token');
        $maildata = array('token' => $token, 'name' => $user->name);
        try {
            Mail::to($user->email)->send(new UserEmailVerificationlink($maildata));
        } catch (\Exception $e) {
            Log::info('UserEmailVerificationlink email error: ' . $e->getMessage());
        }
        return response()->json(['status' => 'ok']);
    }

    /**
     * Check if employee ID is unique
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkEmployeeId(Request $request)
    {
        $employeeId = $request->query('employeeid');
        $excludeUserId = $request->query('exclude_user_id'); // For editing existing users
        $editUserPage = $request->query('edit_user_page');

        if (!$employeeId) {
            return response()->json(['available' => true]);
        }

        if ($editUserPage) {
            $user = User::find($excludeUserId);
            $employeeDetail = EmployeeDetails::where('emp_number', $user->employeeid)->first();

            $query = EmployeeDetails::where('emp_number', $employeeId);
            if ($employeeDetail) {
                $query->where('id', '!=', $employeeDetail->id);
            }
            $exists = $query->exists();

            return response()->json(['available' => !$exists]);
        } else {
            $query = User::where('employeeid', $employeeId);

            // If we're editing a user, exclude their own record from the check
            if ($excludeUserId) {
                $query->where('id', '!=', $excludeUserId);
            }

            $exists = $query->exists();

            return response()->json(['available' => !$exists]);
        }
    }
}
