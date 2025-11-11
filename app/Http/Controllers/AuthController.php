<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name'                  => ['required', 'max:255'],
            'email'                 => ['required', 'email', 'max:255', 'unique:users'],
            'password'              => ['required', 'confirmed', 'max:255'],
            'role'                  => ['required', 'in:admin,manager,cashier'],
        ]);

        $user = User::create([
            'name'     => $fields['name'],
            'email'    => $fields['email'],
            'password' => bcrypt($fields['password']), // hash password
            'role'     => $fields['role'], // assign role
        ]);

        ActivityLog::create([
            'user_id'     => Auth::id(),
            'event'       => 'created',
            'module'      => 'users',
            'description' => 'Created user: ' . $user->name . ' with role ' . $user->role,
            'properties'  => ['user_id' => $user->id],
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->header('User-Agent'),
        ]);

        return redirect()->intended('accounts')->with('message', 'Created new account');
    }

    public function accounts()
    {
        $accounts = User::paginate(10);

        return Inertia::render('Auth/Accounts', ['accounts' => $accounts]);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'max:255']
        ]);

        $key = 'login-attempts:' . $request->ip(); // per IP throttling

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'email' => "Too many login attempts. Please try again in $seconds seconds."
            ])->onlyInput('email');
        }

        if (Auth::attempt($fields, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Clear attempts on successful login
            RateLimiter::clear($key);

            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->intended('/admin-dashboard');
            }

            return redirect()->intended('/pos');
        }

        // Increment attempts after a failed login
        RateLimiter::hit($key, 60);

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function changePassword(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $fields = $request->validate([
            'user_id'  => ['required', 'exists:users,id'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::findOrFail($fields['user_id']);

        $user->update([
            'password' => bcrypt($fields['password']),
        ]);

        ActivityLog::create([
            'user_id'     => Auth::id(),
            'event'       => 'updated',
            'module'      => 'users',
            'description' => 'Changed password for user: ' . $user->name,
            'properties'  => ['user_id' => $user->id],
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->header('User-Agent'),
        ]);

        return redirect()->back()->with('message', 'Password updated successfully.');
    }

    public function deleteUser(User $user)
    {
        // Ensure only admins can perform this action
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        // Prevent admin from deleting themselves (optional)
        if (Auth::id() === $user->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        // Store user data for logging before deletion
        $deletedUserData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ];

        // Delete the user
        $user->delete();

        // Log the activity
        ActivityLog::create([
            'user_id'    => Auth::id(),
            'event'      => 'delete',
            'module'     => 'User Management',
            'description' => 'Deleted user: ' . $deletedUserData['name'] . ' (ID: ' . $deletedUserData['id'] . ')',
            'properties' => $deletedUserData,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->back()->with('success', 'User deleted successfully.');
    }
}
