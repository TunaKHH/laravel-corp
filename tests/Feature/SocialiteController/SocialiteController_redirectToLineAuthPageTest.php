<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SocialiteController_redirectToLineAuthPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the redirectToLineAuthPage method of SocialiteController.
     *
     * @return void
     */
    public function test_redirecttolineauthpage_get()
    {
        $response = $this->get('/line/auth');

        // Check for redirection back to the previous page
        $response->assertRedirect(url()->previous());

        // Check if the session contains the expected error message
        $response->assertSessionHasErrors([
            'message' => 'Your expected error message here'
        ]);
    }
}
