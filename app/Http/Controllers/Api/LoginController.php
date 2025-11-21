<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',

        ]);

        if ($validator->fails()) {

            return response()->json([

                'success' => false,
                'message' => $validator->errors()->first(),

            ]);

        }

        $user = User::create([

            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => $request->input('user_type'),

        ]);

        $token = $user->createToken('token')->plainTextToken;

        return response()->json([

            'success' => true,
            'message' => 'User register successfully',
            'token' => $token,

        ]);

    }
    // public function login(Request $request)

    // {
    //     $request->validate([
    //         'email' => 'required|string|email',
    //         'password' => 'required|string',

    //     ]);

    //     if (!Auth::attempt($request->only('email', 'password'))) {
    //         return response()->json(['message' => 'Invalid email or password'], 401);
    //     }

    //     $user = Auth::user();
    //     $token = $user->createToken('authToken')->plainTextToken;

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'User login successful.',
    //         'user' => $user,
    //         'token' => $token,
    //     ]);
    // }

    public function login(Request $request)
    {
        // return $request;
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
            'firebase_token' => 'required|string', // Ensure firebase_token is required
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 401);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid email or password'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('authToken')->plainTextToken;

        // Update user's firebase_token
        $user->firebase_token = $request->firebase_token;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User login successful.',
            'user' => $user,
            'token' => $token,
        ]);

        if ($user->flag) {
            return response()->json([
                'success' => true,
                'message' => 'First login successful. Please change your password.',
                'user' => $user,
                'token' => $token,
                'flag' => true,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'User login successful.',
            'user' => $user,
            'token' => $token,
            'flag' => false,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

}
