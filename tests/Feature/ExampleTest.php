<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRoot()
    {
        $response = $this->get('/');

        $response->assertStatus(302);// 未登入導到登入畫面
    }

    public function testLoginPage()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);// 未登入導到登入畫面
    }

}
