<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\EmailChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => ['nullable', Password::defaults()],
        ]);

        $originalEmail = $user->email;
        $emailChanged = $originalEmail !== $validated['email'];

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'email_verified_at' => $emailChanged ? null : $user->email_verified_at,
            'password' => ! empty($validated['password'])
                ? bcrypt($validated['password'])
                : $user->password,
        ]);

        if ($emailChanged) {
            Notification::route('mail', $originalEmail)
                ->notify(new EmailChanged($user, $originalEmail));
        }

        return redirect()->route('profile.edit')->with('status', 'Profile updated');
    }
}
