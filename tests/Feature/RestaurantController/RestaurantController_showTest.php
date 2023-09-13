<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RestaurantController_showTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the show method of RestaurantController.
     *
     * @return void
     */
    public function test_show_resource()
    {
        $response = $this->resource('restaurant/show');

        // Check for redirection back to the previous page
        $response->assertRedirect(url()->previous());

        // Check if the session contains the expected error message
        $response->assertSessionHasErrors([
            'message' => 'Your expected error message here'
        ]);
    }
}
