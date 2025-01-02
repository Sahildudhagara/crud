<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    // Show login page
    public function index()
    {
        // Check if the user is already authenticated
        if (Auth::check()) {
            // Redirect to the category index page if already logged in
            return redirect()->route('category.index');
        }

        // Otherwise, show the login page
        return view('login');
    }

    // Authenticate the user
    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->passes()) {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return redirect()->route('category.index');
            } else {
                return redirect()->route('login')->with('error', 'Either email or password is incorrect');
            }
        } else {
            return redirect()->route('login')->withInput()->withErrors($validator);
        }
    }

    // Show the registration page
    public function register()
    {
        return view('register');
    }

    // Process user registration
    public function processRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        if ($validator->passes()) {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            return redirect()->route('login')->with('success', 'You have registered successfully.');
        } else {
            return redirect()->route('register')->withInput()->withErrors($validator);
        }
    }

    // Logout the user
    public function logout(Request $request)
    {
        Auth::logout(); // Log the user out
        $request->session()->invalidate(); // Invalidate the session
        $request->session()->regenerateToken(); // Regenerate the CSRF token

        return redirect()->route('login')->with('success', 'You have logged out successfully.');
    }
}
