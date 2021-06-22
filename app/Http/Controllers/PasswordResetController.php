<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    public function __construct()
    {
        $this->middleware([
            '\App\Http\Middleware\AllowOnlyEnabledFeatures:User Management',
            'password.confirm',
        ]);
    }

    public function edit()
    {
        return view('auth.passwords.reset');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->password = Hash::make($request->password);

        $user->save();

        return redirect()->back()->with('successMessage', 'Your password was changed successfully!');
    }
}
