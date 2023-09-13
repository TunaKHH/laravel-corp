<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RestaurantMealController_indexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the index method of RestaurantMealController.
     *
     * @return void
     */
    public function test_index_resource()
    {
        $response = $this->resource('restaurantMeal/index');

        // Check for redirection back to the previous page
        $response->assertRedirect(url()->previous());

        // Check if the session contains the expected error message
        $response->assertSessionHasErrors([
            'message' => 'Your expected error message here'
        ]);
    }
}
