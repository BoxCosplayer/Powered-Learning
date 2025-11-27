<?php

/**
 * Feature tests validating the registration page and sign-up workflow.
 *
 * Inputs: simulated HTTP requests hitting the registration endpoints with user payloads.
 * Outputs: assertions confirming view availability, database persistence, and authentication state after sign-up.
 */

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Exercises registration behaviour for guest visitors.
 */
class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Ensure guests can view the registration form.
     *
     * Inputs: none directly; issues a GET request to the registration route.
     * Outputs: assertions that the form renders with expected messaging.
     */
    public function test_guest_can_view_registration_form(): void
    {
        $response = $this->get('/auth/register');

        $response->assertOk();
        $response->assertSee('Create your Powered Learning account');
    }

    /**
     * Ensure a guest can register successfully and becomes authenticated.
     *
     * Inputs: POST payload containing name (string), email (string), password (string), and password_confirmation (string).
     * Outputs: assertions covering redirect destination, stored user record, and authenticated state.
     */
    public function test_guest_can_register_and_is_authenticated(): void
    {
        $response = $this->post('/auth/register', [
            'name' => 'Jess Scholar',
            'email' => 'jess.scholar@example.test',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'jess.scholar@example.test',
            'name' => 'Jess Scholar',
        ]);

        $response->assertRedirect(route('home'));
    }

    /**
     * Ensure the password must contain more than one character category.
     *
     * Inputs: POST payload containing a password limited to lowercase letters alongside matching confirmation.
     * Outputs: assertions confirming validation errors and lack of authentication.
     */
    public function test_password_requires_multiple_character_types(): void
    {
        $response = $this->from('/auth/register')->post('/auth/register', [
            'name' => 'Jess Scholar',
            'email' => 'jess.scholar@example.test',
            'password' => 'onlylowercase',
            'password_confirmation' => 'onlylowercase',
        ]);

        $response->assertRedirect('/auth/register');
        $response->assertSessionHasErrors(['password']);
        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'jess.scholar@example.test']);
    }

    /**
     * Ensure the name length constraint is enforced.
     *
     * Inputs: POST payload containing an overly long name string.
     * Outputs: assertion confirming validation prevents persistence.
     */
    public function test_name_cannot_exceed_length_limit(): void
    {
        $longName = str_repeat('a', 260);

        $response = $this->from('/auth/register')->post('/auth/register', [
            'name' => $longName,
            'email' => 'long.name@example.test',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertRedirect('/auth/register');
        $response->assertSessionHasErrors(['name']);
        $this->assertGuest();
    }
}
