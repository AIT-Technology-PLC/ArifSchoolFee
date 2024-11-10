<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Mail\SendEmail;

class ForgetPasswordController extends Controller
{
    protected $redirectTo = RouteServiceProvider::HOME;

    public function index()
    {
        return view('auth.recover');
    }

    public function edit(Request $request)
    {
        $token = $request->query('token');

        $user = User::where('reset_token', $token)->first();

        if (!$user) {
            return view('auth.recover')->with('failedMessage', 'Invalid or expired token.');
        }
        
        return view('auth.reset-password', ['token' => $token]);
    }

    public function update(Request $request)
    {
        if ($request->filled('email')) {    
            $request->validate(['email' => 'required|email|max:30']);

            $user = User::where('email', $request->email)->first();
    
            if ($user) {
                $token = Str::random(60);

                $user->update(['reset_token' => $token]);
    
                Mail::to($user->email)->send(new SendEmail($user, 'Reset Password'));
    
                return redirect()->back()->with('successMessage', 'Password reset link sent to your email.');
            }
    
            return redirect()->back()->with('failedMessage', 'Email address not found.');
        }
        
        if($request->filled('password') && $request->filled('token')) {
            $request->validate([
                'token' => 'required', 
                'password' => 'required|string|min:8|confirmed'
            ]);

            $user = User::where('reset_token', $request->token)->first();

            $user->password = Hash::make($request->password);
            $user->reset_token = null;
            $user->save();

            return redirect()->route('login')->with('successMessage', 'Password reset Successfully');
        }

        return redirect()->back()->with('failedMessage', 'Please provide the required filed values!');
    }
}
