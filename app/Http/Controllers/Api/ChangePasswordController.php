<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ChangePasswordController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('RolePermissions');

    }
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string',
            'password' => 'required|string|confirmed|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 400);
        }

        $user = Auth::user();
        // Ensure user is authenticated

        // Check if old password matches
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['error' => 'Old password does not match.'], 400);
        }

        // Check if new password is different from old password
        if (Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'New password must be different from the old password.'], 400);
        }

        // Update user's password
        $user->password = Hash::make($request->password);
        $user->flag = false; // Set first_login to false after changing password
        $user->save();

        return response()->json(['message' => 'Password changed successfully.'], 200);
    }
}
