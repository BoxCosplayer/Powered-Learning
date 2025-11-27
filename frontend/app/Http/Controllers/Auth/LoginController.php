<?php

/**
 * Controller managing web guard authentication for Powered Learning, covering login display and session teardown.
 *
 * Inputs: HTTP requests carrying credential payloads or logout intents.
 * Outputs: HTML responses for the login form and redirects after authentication state changes.
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * Handles user login and logout flows.
 */
class LoginController extends Controller
{
    /**
     * Show the login form to guest users.
     *
     * Inputs: none beyond the current HTTP request context.
     * Outputs: View rendering the authentication login template.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Attempt to authenticate a user using email and password credentials.
     *
     * Inputs: Request containing email (string), password (string), and remember (boolean) fields.
     * Outputs: RedirectResponse to the intended destination on success, defaulting to the dashboard; validation errors on failure.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['sometimes', 'boolean'],
        ]);

        $remember = $request->boolean('remember');

        if (!Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']], $remember)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Log the current user out and invalidate their session.
     *
     * Inputs: Request providing the active session context.
     * Outputs: RedirectResponse returning the user to the home page; guests are redirected without error.
     */
    public function destroy(Request $request): RedirectResponse
    {
        if (!Auth::check()) {
            return redirect()->route('home');
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
