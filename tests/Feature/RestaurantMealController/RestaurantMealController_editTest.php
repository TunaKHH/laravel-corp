<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RestaurantMealController_editTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the edit method of RestaurantMealController.
     *
     * @return void
     */
    public function test_edit_resource()
    {
        $response = $this->resource('restaurantMeal/edit');

        // Check for redirection back to the previous page
        $response->assertRedirect(url()->previous());

        // Check if the session contains the expected error message
        $response->assertSessionHasErrors([
            'message' => 'Your expected error message here'
        ]);
    }
}
