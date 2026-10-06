<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * The first-login page: an agent created with a temporary password sets their own (AfterAuthentication sends them
 * here). Nobody else may use it; it doesn't ask for the current password, so everyone else changes theirs on the
 * profile page, which does (docs/HANDOVER.md §9.12).
 */
class PasswordChangeController extends Controller
{
    public function showChangePasswordForm()
    {
        if (! Auth::user()->mustChangePassword()) {
            return redirect()->route('user-profile.create');
        }

        return view('auth.onetimepass');
    }
    public function updatePassword(Request $request)
    {
        if (! Auth::user()->mustChangePassword()) {
            return redirect()->route('user-profile.create')->with('error', 'Change your password from your profile page.');
        }

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
