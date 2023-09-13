<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginController_showTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 應該可以顯示登入頁面
     *
     * @return void
     */
    public function test_show_get()
    {
        $response = $this->get('login');

        // http status code 應該是 200
        $response->assertStatus(200);
        // 應該可以看到登入頁面
        $response->assertViewIs('auth.login');
    }
}
