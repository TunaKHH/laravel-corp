<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RestaurantPhotoController_updateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the update method of RestaurantPhotoController.
     *
     * @return void
     */
    public function test_update_resource()
    {
        $response = $this->resource('restaurantPhoto/update');

        // Check for redirection back to the previous page
        $response->assertRedirect(url()->previous());

        // Check if the session contains the expected error message
        $response->assertSessionHasErrors([
            'message' => 'Your expected error message here'
        ]);
    }
}
