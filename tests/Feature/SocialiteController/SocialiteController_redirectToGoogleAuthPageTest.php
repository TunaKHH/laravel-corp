<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SocialiteController_redirectToGoogleAuthPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the redirectToGoogleAuthPage method of SocialiteController.
     *
     * @return void
     */
    public function test_redirecttogoogleauthpage_get()
    {
        $response = $this->get('/google/auth');

        // Check for redirection back to the previous page
        $response->assertRedirect(url()->previous());

        // Check if the session contains the expected error message
        $response->assertSessionHasErrors([
            'message' => 'Your expected error message here'
        ]);
    }
}
