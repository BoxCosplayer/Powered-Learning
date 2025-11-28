<?php

/**
 * Feature tests validating access control and rendering of the authenticated dashboard.
 *
 * Inputs: HTTP GET requests targeting the dashboard route under different authentication states.
 * Outputs: assertions confirming redirects for guests and visible content for logged-in users.
 */

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Ensures the dashboard is protected and displays personalised data for authenticated users.
 */
class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Guests should be redirected to the login page when accessing the dashboard.
     *
     * Inputs: GET request to /dashboard without an authenticated session.
     * Outputs: redirect response pointing to the login route.
     */
    public function test_guest_is_redirected_to_login_when_accessing_dashboard(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect(route('login'));
    }

    /**
     * Authenticated users can view the dashboard and see their personal details.
     *
     * Inputs: GET request to /dashboard with an authenticated user context.
     * Outputs: successful response containing dashboard headings and the user name.
     */
    public function test_authenticated_user_can_view_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        $response->assertSeeText('Powered Learning Dashboard');
        $response->assertSeeText($user->name);
    }

    /**
     * Authenticated users can access the profile page directly from the dashboard.
     *
     * Inputs: GET request to /dashboard while authenticated.
     * Outputs: successful response containing a link to the profile route.
     */
    public function test_dashboard_contains_link_to_profile_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        $response->assertSee('View profile');
        $response->assertSee('/profile');
    }

    /**
     * Authenticated users can see the config selection form populated from the Python configuration.
     *
     * Inputs: GET request to /dashboard while authenticated.
     * Outputs: successful response containing config.py grouping labels and option keys.
     */
    public function test_dashboard_shows_config_options_from_python_module(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        $response->assertSeeText('Select config-driven values');
        $response->assertSeeText('Assessment weighting');
        $response->assertSee('REVISION_WEIGHT');
    }
}
