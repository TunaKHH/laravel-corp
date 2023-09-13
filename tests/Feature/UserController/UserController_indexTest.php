<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserController_indexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the index method of UserController.
     *
     * @return void
     */
    public function test_index_resource()
    {
        $response = $this->resource('user/index');

        // Check for redirection back to the previous page
        $response->assertRedirect(url()->previous());

        // Check if the session contains the expected error message
        $response->assertSessionHasErrors([
            'message' => 'Your expected error message here'
        ]);
    }
}
