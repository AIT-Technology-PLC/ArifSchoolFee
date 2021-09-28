<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    public function __construct()
    {
        $this->middleware([
            'isFeatureAccessible:User Management',
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
