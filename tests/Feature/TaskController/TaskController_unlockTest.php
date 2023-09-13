<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskController_unlockTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the unlock method of TaskController.
     *
     * @return void
     */
    public function test_unlock_post()
    {
        $response = $this->post('task/unlock');

        // Check for redirection back to the previous page
        $response->assertRedirect(url()->previous());

        // Check if the session contains the expected error message
        $response->assertSessionHasErrors([
            'message' => 'Your expected error message here'
        ]);
    }
}
