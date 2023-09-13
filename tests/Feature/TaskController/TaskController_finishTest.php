<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskController_finishTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the finish method of TaskController.
     *
     * @return void
     */
    public function test_finish_post()
    {
        $response = $this->post('task/finish/{task}');

        // Check for redirection back to the previous page
        $response->assertRedirect(url()->previous());

        // Check if the session contains the expected error message
        $response->assertSessionHasErrors([
            'message' => 'Your expected error message here'
        ]);
    }
}
