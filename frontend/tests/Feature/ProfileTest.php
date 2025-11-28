<?php

/**
 * Feature tests validating access control and rendering of the profile page.
 *
 * Inputs: HTTP GET requests targeting the profile route under different authentication states.
 * Outputs: assertions confirming redirects for guests and visible user details for logged-in users.
 */

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Ensures the profile page is protected and displays account data for authenticated users.
 */
class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Guests should be redirected to the login page when accessing the profile.
     *
     * Inputs: GET request to /profile without an authenticated session.
     * Outputs: redirect response pointing to the login route.
     */
    public function test_guest_is_redirected_to_login_when_accessing_profile(): void
    {
        $response = $this->get('/profile');

        $response->assertRedirect(route('login'));
    }

    /**
     * Authenticated users can view the profile page and see their details.
     *
     * Inputs: GET request to /profile with an authenticated user context.
     * Outputs: successful response containing profile headings and user fields.
     */
    public function test_authenticated_user_can_view_profile(): void
    {
        $user = User::factory()->create([
            'name' => 'Ada Lovelace',
            'email' => 'ada@example.com',
        ]);

        $response = $this->actingAs($user)->get('/profile');

        $response->assertOk();
        $response->assertSeeText('Profile overview');
        $response->assertSeeText('Ada Lovelace');
        $response->assertSeeText('ada@example.com');
    }
}
