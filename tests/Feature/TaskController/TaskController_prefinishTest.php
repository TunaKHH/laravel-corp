<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskController_prefinishTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the prefinish method of TaskController.
     *
     * @return void
     */
    public function test_prefinish_put()
    {
        $response = $this->put('task/finish/{task}');

        // Check for redirection back to the previous page
        $response->assertRedirect(url()->previous());

        // Check if the session contains the expected error message
        $response->assertSessionHasErrors([
            'message' => 'Your expected error message here'
        ]);
    }
}
