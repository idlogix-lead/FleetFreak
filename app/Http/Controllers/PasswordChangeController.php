<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordChangeController extends Controller
{
    public function showChangePasswordForm()
    {
        return view('auth.onetimepass');
    }
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|confirmed|min:8',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->flag = 0;  // Set flag to false after changing password
        $user->save();

        Auth::logout();  // Log the user out after password change

        return redirect()->route('login')->with('status', 'Password changed successfully. Please log in again.');
    }
}
