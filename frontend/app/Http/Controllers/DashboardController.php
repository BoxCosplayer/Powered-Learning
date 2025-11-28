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
     * Outputs: View instance populated with the authenticated user's data and tunable parameter defaults.
     */
    public function __invoke(): View
    {
        /** @var User $user */
        $user = auth()->user();

        return view('dashboard', [
            'user' => $user,
            'tunableParameters' => $this->defaultSessionParameters(),
        ]);
    }

    /**
     * Provide the default values the dashboard form should display for session tuning.
     *
     * Inputs: none.
     * Outputs: associative array containing tunable parameter metadata (key, label, helper copy, default numeric value).
     */
    private function defaultSessionParameters(): array
    {
        return [
            [
                'key' => 'SESSION_TIME_MINUTES',
                'label' => 'Session time (minutes)',
                'helper' => 'Length of each focused session before a rest.',
                'value' => 45,
                'type' => 'number',
            ],
            [
                'key' => 'BREAK_TIME_MINUTES',
                'label' => 'Break time (minutes)',
                'helper' => 'Duration of the pause between study sessions.',
                'value' => 15,
                'type' => 'number',
            ],
            [
                'key' => 'SESSION_COUNT',
                'label' => 'Total sessions',
                'helper' => 'How many sessions to schedule for this run.',
                'value' => 8,
                'type' => 'number',
            ],
            [
                'key' => 'SHOTS',
                'label' => 'Shots',
                'helper' => 'Number of practice attempts per subject rotation.',
                'value' => 8,
                'type' => 'number',
            ],
        ];
    }
}
