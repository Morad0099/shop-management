<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CustomAuthController extends Controller
{
    /**
     * Handle the login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            $user = DB::selectOne('SELECT id, password FROM users WHERE email = ? LIMIT 1', [$request->input('email')]);

            if ($user && Hash::check($request->input('password'), $user->password)) {
                Auth::loginUsingId($user->id);
                return redirect()->intended('dashboard');
            }

            return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
        } catch (\Exception $e) {
            Log::error('Database error: ' . $e->getMessage());
            return back()->withErrors(['email' => 'An error occurred. Please try again later.']);
        }
    }
}
