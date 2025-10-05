<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.login-agriculture');
    }

    /**
     * Handle login attempt
     */
    public function login(Request $request)
    {
        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Store additional session data like your legacy system
            session([
                'user_id' => $user->staff_id,
                'username' => $user->username,
                'role' => $user->role->role ?? 'staff',
                'full_name' => $user->first_name . ' ' . $user->last_name,
                'position' => $user->position,
            ]);
            
            // Log the login activity
            DB::insert("
                INSERT INTO activity_logs (staff_id, action, action_type, details, timestamp) 
                VALUES (?, 'User logged in', 'login', 'User logged into the system', NOW())
            ", [$user->staff_id]);

            return redirect()->intended(route('dashboard'));
        }

        return back()->with('error', 'Invalid username or password');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        if (Auth::check()) {
            $user_id = Auth::user()->staff_id;
            
            // Log the logout activity
            DB::insert("
                INSERT INTO activity_logs (staff_id, action, action_type, details, timestamp) 
                VALUES (?, 'User logged out', 'login', 'User logged out of the system', NOW())
            ", [$user_id]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}