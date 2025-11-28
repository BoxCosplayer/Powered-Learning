<?php

/**
 * Controller presenting the profile view so authenticated users can review their details.
 *
 * Inputs: HTTP requests routed through the auth middleware containing an authenticated user context.
 * Outputs: HTML response rendering the profile Blade view populated with the current user's data.
 */

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;

/**
 * Handles display of the profile page for logged-in users.
 */
class ProfileController extends Controller
{
    /**
     * Render the profile view for the authenticated user.
     *
     * Inputs: implicit HTTP request context containing the authenticated User model.
     * Outputs: View instance populated with the authenticated user's details for display.
     */
    public function __invoke(): View
    {
        /** @var User $user */
        $user = auth()->user();

        return view('profile', [
            'user' => $user,
        ]);
    }
}
