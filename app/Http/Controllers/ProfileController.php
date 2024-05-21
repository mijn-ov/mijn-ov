<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's settings menu.
     */
    public function view(Request $request): View
    {
        return view('profile.view', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function updateView(Request $request): View
    {
        return view('profile.menu-items.update-profile-information-form', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display the user's password form.
     */
    public function updatePassword(Request $request): View
    {
        return view('profile.menu-items.update-password-form', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display the user's deletetion form.
     */
    public function deleteView(Request $request): View
    {
        return view('profile.menu-items.delete-user-form', [
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

        return Redirect::route('profile')->with('status', 'profile-updated');
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
}
