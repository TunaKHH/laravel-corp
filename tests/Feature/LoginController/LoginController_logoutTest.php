<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LoginController_logoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the logout method of LoginController.
     *
     * @return void
     */
    public function test_logout_post()
    {
        // Create or retrieve a user model instance
        $user = User::factory()->create();

        // Simulate user login
        $response = $this->actingAs($user)->post('logout');

        // Check for redirection to the root directory
        $response->assertRedirect('/');
    }
}
