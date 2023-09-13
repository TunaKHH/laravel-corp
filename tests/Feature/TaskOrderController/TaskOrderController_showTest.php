<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskOrderController_showTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the show method of TaskOrderController.
     *
     * @return void
     */
    public function test_show_resource()
    {
        $response = $this->resource('taskOrder/show');

        // Check for redirection back to the previous page
        $response->assertRedirect(url()->previous());

        // Check if the session contains the expected error message
        $response->assertSessionHasErrors([
            'message' => 'Your expected error message here'
        ]);
    }
}
