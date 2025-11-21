<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\LoginNotification;
use App\Models\User;
use App\Models\UserSocialProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
// use Illuminate\Foundation\Auth\AuthenticatesUsers;/
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    // use AuthenticatesUsers;


    public function signupform()
    {
        return view('theme.authentication-signup');
    }

    public function signup(Request $request)
    {
         
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
             'type' => 'required|',
            'password' => 'required|string|min:8',
        ]);

        $user = new User();
        


        //$user->customer_id = auth()->user()->id;
        //$user->service_provider_id = auth()->user()->id;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->type = $request->type;
        // to store default profile image path:
        $user->image = 'profile_images/default/default.jpeg';
        
        $user->password = Hash::make($request->password);
        $user->save();
        return redirect()->route('login');
    }
    public function LoginForm()
    {
        return view('theme.authentication-signin');
    }
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            
            //sending mail to logged in user:
            //$user = auth()->user();
            //Mail::to($request->email)->send(new LoginNotification($user));

            return redirect()->intended('/');
        } 
        else {
            //dd('Authentication failed');
            return redirect()->back()->withInput()->withErrors(['email' => 'Invalid email or password']);
        }

        
    }

    // public function saveFirebaseToken(Request $request)
    // {
    //     $request->validate([
    //         'token' => 'required|string',
    //     ]);

    //     $user = Auth::user();
    //     $user->firebase_token = $request->token;
    //     $user->save();

    //     return response()->json(['message' => 'Token saved successfully']);
    // }

    // Logout the user
    // public function logout()
    // {
    //     Auth::logout();
    //     // request()->session()->invalidate();
    //     // request()->session()->regenerateToken();
    //     //return response()->json(['status'=> 'you are logged out successfully']);
    //     return redirect('login');
    // }
    //  forget password
    public function showForgotPasswordForm(){
        return view('theme.authentication-forgot-password');
    }
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));
        dd($status);

        if ($status === Password::RESET_LINK_SENT) {
            dd($status);
            return redirect()->back()->with(['success' => __($status)]);
        } else {
            dd($status);
            return redirect()->back()->withErrors(['email' => __($status)]);
        }
        
    }

    // protected function authenticated(Request $request, $user)
    // {
    //     // dd(Session::getId());
    //     // User::where('id',$user->id)->update(['session_id' => Session::getId()]);
    //     // if($user->role->role_name != "superadmin"){
    //     //     $role_cruds = Role_crud::
    //     //     where('role_id',auth()->user()->role_id)
    //     //     ->with('role_name')
    //     //     ->whereHas('role_name',function($query){
    //     //         $query->whereNull('deleted_at');
    //     //     })
    //     //     ->join('roles','roles.id','role_cruds.role_id')
    //     //     ->get();
    //     //     Session::put('role_cruds',$role_cruds);
    //     // }


    //     // return redirect($user->role->home)
    //     // ->with('success',['Logged-In Successfully!']);
    //     return redirect('/customers')
    //     ->with('success',['Logged-In Successfully!']);
    // }

}
