<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function updatePhoto(Request $request)
{
    $request->validate([
        'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $user = auth()->user();

    if ($request->hasFile('profile_photo')) {
        // Hapus foto lama jika ada
        if ($user->profile_photo_path) {
            Storage::delete('public/' . $user->profile_photo_path);
        }

        $path = $request->file('profile_photo')->store('profile-photos', 'public');
        $user->profile_photo_path = $path;
        $user->save();
    }

    return redirect()->back()->with('success', 'Foto profil berhasil diperbarui.');
}
}
