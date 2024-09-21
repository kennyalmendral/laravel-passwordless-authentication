<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use App\Mail\LoginLink;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        $user = User::where('email', $request->email)->first();

        if ($user) {
            $token = Str::random(60);
            $user->login_token = $token;
            $user->login_token_created_at = now();
            $user->save();

            $url = URL::temporarySignedRoute(
                'login.token', 
                now()->addMinutes(15), 
                ['email' => $user->email, 'token' => $token]
            );

            Mail::to($user)->send(new LoginLink($url));

            return back()->with('status', 'We have emailed your login link!');
        }

        return back()->withErrors(['email' => 'No user found with this email address.']);
    }

    public function authenticateToken(Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(401, 'Invalid or expired login link.');
        }

        $user = User::where('email', $request->email)
                    ->where('login_token', $request->token)
                    ->where('login_token_created_at', '>', now()->subMinutes(15))
                    ->first();

        if ($user) {
            Auth::login($user);

            $user->login_token = null;
            $user->login_token_created_at = null;
            $user->save();
            
            return redirect()->intended('/dashboard');
        }

        return redirect()->route('login')->withErrors(['email' => 'Invalid login link.']);
    }
}