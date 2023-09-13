<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskController_lockTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the lock method of TaskController.
     *
     * @return void
     */
    public function test_lock_post()
    {
        $response = $this->post('task/lock');

        // Check for redirection back to the previous page
        $response->assertRedirect(url()->previous());

        // Check if the session contains the expected error message
        $response->assertSessionHasErrors([
            'message' => 'Your expected error message here'
        ]);
    }
}
