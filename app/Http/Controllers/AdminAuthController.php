<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            /** @var User $user */
            $user = Auth::user();
            if ($user && method_exists($user, 'hasRole') && $user->hasRole('admin')) {
                return redirect()->route('admin.dashboard');
            } else {
                Auth::logout();
                return back()->with('error', 'Access denied. Only admins can log in here.');
            }
        }
        return back()->with('error', 'Invalid credentials.');
    }
}
