<?php

/**
 * Feature tests covering user login flow and post-authentication redirection.
 *
 * Inputs: HTTP POST requests to the login endpoint with valid credentials.
 * Outputs: assertions verifying redirects to the dashboard and authenticated sessions.
 */

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Confirms successful login directs users to the dashboard.
 */
class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Users providing valid credentials are redirected to the dashboard and authenticated.
     *
     * Inputs: POST request containing a known email and matching password.
     * Outputs: redirect to the dashboard route and an authenticated user session.
     */
    public function test_successful_login_redirects_to_dashboard(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('super-secure'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'super-secure',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }
}
