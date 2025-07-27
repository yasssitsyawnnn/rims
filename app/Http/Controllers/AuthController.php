<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login()
    {
        $publicStoragePath = public_path('storage');

        if (!file_exists($publicStoragePath)) {
            Artisan::call('storage:link');
        }

        return view('auth.login');
    }

    function loginPost(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $usercheck = User::where('email', $request->email)->first();
        if(isset($usercheck)){
            if($usercheck->status == '1'){
                $credentials = $request->only('email', 'password');
                if(Auth::attempt($credentials)){
                    return redirect()->intended(route('dashboard'));
                }else{
                    return redirect()->route('login')->with('delete', 'Invalid email or password');
                }
            }
            else{
                return redirect()->route('login')->with('delete', 'Your account is deactivated, please contact Director/Staff.');
            }
        }else{
            return redirect()->route('login')->with('delete', 'Invalid email or password');
        }        
        
    }

    function logout(){
        Session::flush();
        Auth::logout();
        return redirect()->route('login');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{6,}$/',
            'confirm_password' => 'required|same:password',
        ]);

        if(Hash::check($request->current_password,$user->password)){
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            return redirect()->route('profile.index')->with('success', 'Password updated successfully.');
        }else{
            return redirect()->route('profile.index')->with('err', 'Invalid current password.');            
        }        
    }

    public function forgot_password(){
        return view('auth.forgot_password');
    }

    public function forgotPasswordPost(Request $request){

        $request->validate([
            'email' => "required|email|exists:users",
        ]);

        DB::table('password_reset_tokens')
        ->where('email', $request->email)
        ->delete();

        $token = Str::random(64);

        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);

        if(env('MAIL_USERNAME') != null && env('MAIL_PASSWORD') != null){
            Mail::send("auth.fp_email", ['token' => $token, 'email' => $request->email], function ($message) use ($request){
                $message->to($request->email);
                $message->subject("Reset Password");
            });

            return redirect()->route('forgot_password')->with('status', 'Email sent to reset the password!.');
        }else{
            return redirect()->route('forgot_password')->with('delete', 'Set Up Email Sending Details From .ENV For Automated Mail');
        }
    }

    public function resetPassword($token, $email){
        return view('auth.new_password', compact('token', 'email'));
    }

    public function resetPasswordPost(Request $request){
        $request->validate([
            'email' => "required|email|exists:users",
            'password' => "required|string|min:6|regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{6,}$/|confirmed",
            'password_confirmation' => "required|same:password",
        ]);

        $updatePassword = DB::table('password_reset_tokens')
        ->where([
            "email" => $request->email,
            "token" => $request->token,
        ])->first();

        if(!$updatePassword){
            return redirect()->route('login')->with('delete', 'Invalid data!.');
        }
        else{
            User::where('email', $request->email)->update(["password" => Hash::make($request->password)]);
            
            DB::table('password_reset_tokens')->where(["email" => $request->email])->delete();

            return redirect()->route('login');
        }
    }
}