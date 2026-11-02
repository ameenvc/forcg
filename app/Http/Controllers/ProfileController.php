<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use DB;
use App\User;
use App\Admin;
use App\Models\AppConfiguration;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:web,admin', 'mfa']);
    }

    public function edit(Request $request)
    {
        $models = DB::table('modenadisable')->First();
        $user_id = User::where('email',Auth::user()->email)->value('id'); //this logic is used to combat both admin guard and user guard
        $users = DB::table('users')->where('id',$user_id)->get();
        $layout = $request->g == 'user'
            ? 'layouts.backend'
            : 'admin.nav';
        return view('profile.edit', compact('layout', 'users'))->with('model', $models);
    }

    public function update(Request $request)
    {
        $models = DB::table('modenadisable')->First();
        $validator = Validator::make($request->all(), [
            'profile_pic' => 'nullable|mimes:jpeg,png,jpg',
        ],[
            'profile_pic.mimes' => 'The profile picture must be a file of type: jpeg, png, jpg',
        ]);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()->with('model', $models);
        } else {
            $id = User::where('email',Auth::user()->email)->value('id');
            if ($request->hasfile('profile_pic')) {
                $avatar = $request->file('profile_pic');
                $filename = $id . '_' . time() . '.' . $avatar->getClientOriginalExtension();
                $destination_path = public_path('storage/profile');
                $avatar->move($destination_path, $filename);
                Admin::where('email',Auth::user()->email)->update(['profile_picture' => $filename]);
                User::where('id',$id)->update(['profile_picture'=>$filename]);
            }
            session()->flash('msg', 'Profile Updated Successfully');
            return redirect()->back();
        }
    }

    public function removeAvatar()
    {
        $id = User::where('email',Auth::user()->email)->value('id');
        $data = User::where('id',$id)->first();
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
        User::where('id',$id)->update(['profile_picture' => $filename]);
        Admin::where('email',$data->email)->update(['profile_picture' => $filename]);
        session()->flash('msg', 'Image deleted successfully');
        return redirect()->route('user.profile');
    }

    public function passwordAndSecurity(Request $request)
    {
        $models = DB::table('modenadisable')->First();
        $layout = $request->g == 'user'
            ? 'layouts.backend'
            : 'admin.nav';
        $mfa = AppConfiguration::where('meta_key', 'enable_mfa')->first();

        return view('profile.password_and_security', compact('layout', 'mfa'))->with('model', $models);
    }

    public function changePassword(Request $request)
    {
        $models = DB::table('modenadisable')->First();
        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error", "Your current password does not matches with the password you provided. Please try again.");
        }

        if (strcmp($request->get('current-password'), $request->get('new-password')) == 0) {
            //Current password and new password are same
            return redirect()->back()->with("error", "New Password cannot be same as your current password. Please choose a different password.");
        }

        $validatedData = $request->validate([
            'current-password' => 'required',
            'new-password' => 'required|string|min:6|confirmed',
        ]);
        // Change Password

        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();

        if (Auth::guard('admin')->check()) {
            $users = DB::table('users')
            ->where('email', Auth::user()->email)
            ->get();
            if (!empty($users)) {
                DB::table('users')
                    ->where('email', Auth::user()->email)
                    ->update(['password' => bcrypt($request->get('new-password'))]);
            }
        }
        return redirect()
            ->back()
            ->with('model', $models)
            ->with('success', 'Password changed successfully !');
    }
}
