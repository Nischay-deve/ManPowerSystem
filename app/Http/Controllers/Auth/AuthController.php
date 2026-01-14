<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Invalid login credentials'
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();

        // ensure $user is an instance of the Eloquent User model before calling model methods
        if (!$user || !($user instanceof User)) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Invalid user'
            ]);
        }

        if (!$user->is_active) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Account is disabled'
            ]);
        }

        $user->update([
            'last_login_at' => now()
        ]);

        return redirect()->intended('/dashboard');
    }


    public function register(Request $request)
    {
        try {
            $validated = $request->validate(
                [
                    'name'     => 'required|string|max:255',
                    'email'    => 'required|email|unique:users,email',
                    'password' => 'required|min:6|confirmed',
                ],
                [
                    'name.required'     => 'Full name is required.',
                    'name.max'          => 'Name cannot exceed 255 characters.',

                    'email.required'    => 'Email address is required.',
                    'email.email'       => 'Please enter a valid email address.',
                    'email.unique'      => 'This email is already registered.',

                    'password.required' => 'Password is required.',
                    'password.min'      => 'Password must be at least 6 characters.',
                    'password.confirmed' => 'Password confirmation does not match.',
                ]
            );

            $validated['password']  = Hash::make($validated['password']);
            $validated['role']      = 'employee';
            $validated['is_active'] = true;

            User::create($validated);

            return redirect()->route('login')
                ->with('success', 'Employee account created successfully. Please login.');
        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return back()
                ->withErrors([
                    'general' => 'Something went wrong while creating the account. Please try again.'
                ])
                ->withInput();
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function forgotPassword()
    {
        return view('authentication.forgot-password-basic');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Later: Mail::send password reset
        return back()->with('success', 'Password reset link sent to your email.');
    }
}
