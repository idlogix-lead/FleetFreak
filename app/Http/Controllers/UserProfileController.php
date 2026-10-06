<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserProfileController extends Controller
{
    public function create(){
        $user = Auth::user();
        return view("theme.user-profile",compact("user"));
    }
    public function updateprofile(Request $request){
        $user = User::findOrFail(Auth::id());

        $validatedData = $request->validate([
        'image' => 'nullable|image|max:2048', // Ensure the uploaded file is an image with maximum size 2MB
        // 'CNIC' => 'nullable|string|max:40',
        // 'description' => 'nullable|string|max:255',
        // 'phone_no' => 'nullable|string|max:40'
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('storage/profile_images/uploads'), $imageName);
            $validatedData['image'] = 'profile_images/uploads/' . $imageName; // Set the image path in the profile
        } 
        else {
    // If no image is uploaded, set the default image path
            $validatedData['image'] = 'profile_images/default/default.jpeg';
        }

        $user->update($validatedData);

        return redirect()->back()->with('success', 'Profile updated successfully');
    }

    /** Every user changes their own password here, after confirming the current one (docs/HANDOVER.md §9.12). */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'different:current_password'],
        ]);

        $user = $request->user();
        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()->back()->with('success', 'Password changed successfully.');
    }
}
