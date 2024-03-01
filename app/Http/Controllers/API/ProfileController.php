<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Models\User;
use App\Http\Controllers\Controller;

use App\Models\Profile;
use Illuminate\Database\Eloquent\ModelNotFoundException;



class ProfileController extends Controller
{


    public function showProfile($user_id)
    {
        try {
            $user = User::findOrFail($user_id);
            return response()->json($user);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'User not found'], 404);
        }
    }


    public function updateProfile(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);

        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'. $user->id,
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:300',
            'birthday' => 'nullable|date',

            'avtar' => 'nullable|string|max:300',

            'gender' => 'nullable|string|max:300',



            // Add other fields you want to update here
        ]);

        // Update the user's profile
        $user->update($validatedData);

        return response()->json(['message' => 'Profile updated successfully']);
    }

    // public function updateImage(Request $request, User $user)
    // {
    //     //echo "$request";
    //     $request->validate([
    //         'avtar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    //     ]);

    //     if ($request->hasFile('avtar')) {
    //         $profileImage = $request->file('profile_image');
    //         $imageName = time() . '.' . $profileImage->getClientOriginalExtension();
    //         $profileImage->move(public_path('images'), $imageName);

    //         // Update user's profile image path in the database
    //         $user->profile_image = '/images/' . $imageName;
    //         $user->save();

    //         return response()->json(['message' => 'Profile image updated successfully']);
    //     } else {
    //         return response()->json(['error' => 'Profile image not provided'], 400);
    //     }
    // }

}
