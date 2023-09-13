<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskOrderController_storeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the store method of TaskOrderController.
     *
     * @return void
     */
    public function test_store_resource()
    {
        $response = $this->resource('taskOrder/store');

        // Check for redirection back to the previous page
        $response->assertRedirect(url()->previous());

        // Check if the session contains the expected error message
        $response->assertSessionHasErrors([
            'message' => 'Your expected error message here'
        ]);
    }
}
