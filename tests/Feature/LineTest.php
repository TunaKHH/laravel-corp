<?php

namespace Tests\Feature;

use Tests\TestCase;

class LineTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(302); // 302 是轉址 會跳轉到登入頁面 或 後台首頁
    }
}
