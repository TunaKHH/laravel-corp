<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LunchController_recordTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the record method of LunchController.
     *
     * @return void
     */
    public function test_record_get()
    {
        $response = $this->get('/record');

        // Check for redirection back to the previous page
        $response->assertRedirect(url()->previous());

        // Check if the session contains the expected error message
        $response->assertSessionHasErrors([
            'message' => 'Your expected error message here'
        ]);
    }
}
