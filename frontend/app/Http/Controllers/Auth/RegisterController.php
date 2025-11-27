<?php

/**
 * Controller orchestrating user registration for the Powered Learning frontend.
 *
 * Inputs: HTTP requests carrying registration payloads for prospective users.
 * Outputs: HTML views for the registration form and redirects after successful account creation.
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\PasswordStrength;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

/**
 * Facilitates new account creation and immediate authentication.
 */
class RegisterController extends Controller
{
    /**
     * Display the registration form to guest visitors.
     *
     * Inputs: none beyond the current HTTP request context.
     * Outputs: View rendering the registration template.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Validate and persist a new user account, then establish an authenticated session.
     *
     * Inputs: Request containing name (string), email (string), password (string), and password_confirmation (string).
     * Outputs: RedirectResponse directing the user to the home route after sign-up.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'min:8', 'confirmed', new PasswordStrength(), Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        event(new Registered($user));

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }
}
