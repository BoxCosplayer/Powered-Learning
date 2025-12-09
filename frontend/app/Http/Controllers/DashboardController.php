<?php

/**
 * Controller presenting the authenticated dashboard for Powered Learning users.
 *
 * Inputs: HTTP requests passing through the auth middleware with an authenticated user context.
 * Outputs: HTML response rendering the dashboard Blade view populated with user details.
 */

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;

/**
 * Handles display of the dashboard for logged-in users.
 */
class DashboardController extends Controller
{
    /**
     * Render the dashboard view for the current authenticated user.
     *
     * Inputs: implicit HTTP request context containing the authenticated User model.
     * Outputs: View instance populated with the authenticated user's data for overview and navigation.
     */
    public function __invoke(): View
    {
        /** @var User $user */
        $user = auth()->user();

        return view('dashboard', ['user' => $user]);
    }
}
