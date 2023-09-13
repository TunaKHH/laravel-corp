<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterController_createTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the create method of RegisterController.
     *
     * @return void
     */
    public function test_create_post()
    {
        $response = $this->post('register');

        // Check for redirection back to the previous page
        $response->assertRedirect(url()->previous());

        // Check if the session contains the expected error message
        $response->assertSessionHasErrors([
            'message' => 'Your expected error message here'
        ]);
    }
}
