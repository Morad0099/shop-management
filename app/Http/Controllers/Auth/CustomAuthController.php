<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

        $user = User::where('email', $request->input('email'))->first();

        if ($user && Hash::check($request->input('password'), $user->password)) {
            Auth::login($user, $request->boolean('remember'));

            return redirect()->intended('dashboard'); // Adjust the redirect URL as needed
        }

        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
    }

}
