<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = auth()->user();

        // Users table only
        $fullName = trim((string)($user->full_name ?? ''));
        if ($fullName === '') {
            $fullName = trim((string)($user->username ?? 'Profile'));
        }

        $email = $user->email ?? null;
        $username = $user->username ?? null;

        // status from is_active (if column exists)
        $status = ((int)($user->is_active ?? 1) === 1) ? 'Active' : 'Inactive';

        // last login (if column exists)
        $lastLogin = $user->last_login_at ?? $user->last_login ?? null;

        // default avatar (users table has no image column)
        $photoUrl = asset('assets/images/users/default-avatar.png');

        return view('profile.show', compact(
            'user',
            'photoUrl',
            'fullName',
            'email',
            'username',
            'status',
            'lastLogin'
        ));
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password'     => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'new_password.confirmed' => 'New password and confirm password do not match.',
        ]);

        // Your users table uses password_hash
        if (!Hash::check($request->current_password, (string)$user->password_hash)) {
            return back()
                ->withErrors(['current_password' => 'Current password is incorrect.'])
                ->withInput();
        }

        // prevent same password
        if (Hash::check($request->new_password, (string)$user->password_hash)) {
            return back()
                ->withErrors(['new_password' => 'New password cannot be same as current password.'])
                ->withInput();
        }

        $user->update([
            'password_hash' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }
}
