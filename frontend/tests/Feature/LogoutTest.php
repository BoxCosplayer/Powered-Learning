<?php

/**
 * Feature tests validating logout behaviour for authenticated and guest visitors.
 *
 * Inputs: simulated HTTP requests to the logout endpoint via GET and POST verbs.
 * Outputs: assertions confirming redirection to the home page and authentication state handling.
 */

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Ensures logout redirects home for both guests and authenticated users.
 */
class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Guests reaching logout should be redirected home without errors.
     *
     * Inputs: GET request to /logout while unauthenticated.
     * Outputs: redirect to home and guest state.
     */
    public function test_guest_visiting_logout_is_redirected_home(): void
    {
        $response = $this->get('/logout');

        $response->assertRedirect(route('home'));
        $this->assertGuest();
    }

    /**
     * Guests posting to logout should also be redirected home.
     *
     * Inputs: POST request to /logout while unauthenticated.
     * Outputs: redirect to home and guest state.
     */
    public function test_guest_posting_logout_is_redirected_home(): void
    {
        $response = $this->post('/logout');

        $response->assertRedirect(route('home'));
        $this->assertGuest();
    }

    /**
     * Authenticated users are logged out and redirected home.
     *
     * Inputs: POST request to /logout with an authenticated session.
     * Outputs: redirect to home and guest state after logout.
     */
    public function test_authenticated_user_logs_out_and_redirects_home(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect(route('home'));
        $this->assertGuest();
    }
}
