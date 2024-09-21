<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\LoginLink;

class LoginController extends Controller
{
    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle the login request and send a login link to the user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);
        
        $user = User::where('email', $request->email)->first();

        if ($user) {
            $token = Str::random(60);

            $user->login_token = $token;
            $user->login_token_created_at = now();

            $user->save();

            $url = URL::temporarySignedRoute(
                'login.token', 
                now()->addMinutes(15), 
                [
                    'email' => $user->email,
                    'token' => $token
                ]
            );

            Mail::to($user)->send(new LoginLink($url));

            return back()->with('status', 'The login link has been sent to your email address successfully.');
        }

        return back()->withErrors([
            'email' => 'No user found with this email address.'
        ]);
    }

    /**
     * Authenticate the user using the provided token from the login link.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
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

        return redirect()->route('login')->withErrors([
            'email' => 'Invalid login link.'
        ]);
    }
}