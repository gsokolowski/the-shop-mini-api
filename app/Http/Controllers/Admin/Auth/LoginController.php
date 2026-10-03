<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        // Show the form (GET /admin/login)
        return view('admin.auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        //Validate email + password.
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Auth::attempt($credentials, $remember)  Laravel’s session guard:
        // Looks up the user by email via the users provider
        // Checks the password with the hashed cast
        // On success, stores the user id in the session
        // If “remember” is checked, sets a long-lived remember cookie
        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            // On failure → back to login with error, keep email/remember.
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => 'These credentials do not match our records.']);
        }

        // On success (session fixation protection).
        $request->session()->regenerate();

        // Admin gate after login
        if (! Auth::user()->is_admin) {
            // log him out as he is not an admin
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // redirect to login page
            return redirect()
                ->route('admin.login')
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'You do not have admin access.']);
        }

        // on success go to dashboard
        return redirect()->intended(route('admin.dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
