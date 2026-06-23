<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'first_name'    => 'required|string|max:100',
            'last_name'     => 'required|string|max:100',
            'email'         => 'required|email|unique:users,email,' . $user->id,
            'nationality'   => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date',
        ]);

        $user->update($data);

        return back()->with('profile_success', 'Profil mis à jour avec succès.');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate(['photo' => 'required|image|max:2048']);
        $user = Auth::user();

        $path = $request->file('photo')->store("avatars/{$user->uuid}", 's3');
        $user->update(['profile_photo_url' => Storage::disk('s3')->url($path)]);

        return back()->with('profile_success', 'Photo mise à jour.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('password_success', 'Mot de passe modifié avec succès.');
    }

    public function updatePin(Request $request)
    {
        $request->validate([
            'pin'              => 'required|digits:6|confirmed',
            'pin_confirmation' => 'required|digits:6',
        ]);

        Auth::user()->update(['pin_hash' => Hash::make($request->pin)]);

        return back()->with('pin_success', 'PIN mis à jour avec succès.');
    }
}
