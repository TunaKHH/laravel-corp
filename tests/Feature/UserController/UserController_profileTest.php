<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserController_profileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the profile method of UserController.
     *
     * @return void
     */
    public function test_profile_get()
    {
        $response = $this->get('/profile');

        // Check for redirection back to the previous page
        $response->assertRedirect(url()->previous());

        // Check if the session contains the expected error message
        $response->assertSessionHasErrors([
            'message' => 'Your expected error message here'
        ]);
    }
}
