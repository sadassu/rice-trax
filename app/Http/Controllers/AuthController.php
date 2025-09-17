<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', 'max:255'],
        ]);

        $user = User::create($fields);

        ActivityLog::create([
            'user_id'     => Auth::id(),
            'event'       => 'created',
            'module'      => 'users',
            'description' => 'Created product: ' . $user->name,
            'properties'  => ['user_id' => $user->id],
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->header('User-Agent'),
        ]);

        return redirect()->intended('accounts')->with('message', 'Created new account');
    }

    public function accounts()
    {
        $accounts = User::paginate(5);

        return Inertia::render('Auth/Accounts', ['accounts' => $accounts]);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'max:255']
        ]);

        if (Auth::attempt($fields, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // redirect based on role
            if ($user->role === 'admin') {
                return redirect()->intended('/admin-dashboard');
            }

            return redirect()->intended('/pos');
        }

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
}
