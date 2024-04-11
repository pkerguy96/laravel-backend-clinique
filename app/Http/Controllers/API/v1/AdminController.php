<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function storeprofile(Request $request)
    {
        $request->validate([
            'picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:5000',
        ]);
        $user = auth()->user();
        if ($user->profile_picture) {
            // Delete the existing profile picture
            Storage::disk('public')->delete('profile_pictures/' . $user->profile_picture);
        }
        if ($request->hasFile('picture') && $request->file('picture')->isValid()) {
            $extension = $request->file('picture')->getClientOriginalExtension();
            $fileName = 'user_' . $user->id . '_' . Str::random(10) . '.' . $extension;

            $request->file('picture')->storeAs('profile_pictures', $fileName, 'public');

            $user->profile_picture = $fileName;
            $user->save();

            return response()->json(['message' => 'Profile picture uploaded successfully']);
        }
        return response()->json(['message' => 'Failed to upload profile picture'], 400);
    }

    public function ModifyProfile(Request $request)
    {
        try {
            $userid = Auth::id();
            $user = user::findorfail($userid);
            $oldProfilePicture = $user->profile_picture;
            if ($request->hasFile('picture') && $request->file('picture')->isValid()) {
                $request->validate([
                    'picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:5000',
                ]);
                if ($oldProfilePicture) {
                    // Delete the existing profile picture
                    Storage::disk('public')->delete('profile_pictures/' . $user->profile_picture);
                }
                $extension = $request->file('picture')->getClientOriginalExtension();
                $fileName = 'user_' . $user->id . '_' . Str::random(10) . '.' . $extension;
                $request->file('picture')->storeAs('profile_pictures', $fileName, 'public');
                $user->profile_picture = $fileName;
            }
            $url = asset("storage/profile_pictures/"  . $user->profile_picture);

            // Update user attributes
            $user->nom = $request->name;
            $user->email = $request->email;
            //Save the user model to update the database
            $user->save();
            return response()->json([
                'message' => 'Profile  Updated successfully',
                'data' => $user,
                'profile' => $url
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'Error' => $th,

            ], 500);
        }
    }
    public function getpicture()
    {
        $user = Auth()->user();
        if (!$user || !$user->profile_picture) {
            return response()->json(['message' => 'No profile picture was found']);
        } else {

            $url = Storage::disk('public')->url($user->profile_picture);
            return response()->json(['url' => $url]);
        }
    }
}
