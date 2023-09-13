<?php

namespace Tests\Feature;

use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginController_authenticateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 應該可以登入
     *
     * @return void
     */
    public function test_user_can_login()
    {
        $password = 'test';
        // 生成一個使用者
        $user = \App\Models\User::factory()->create(
            [
                'password' => Hash::make($password),
            ]
        );

        // 嘗試登入
        $response = $this->post('login', [
            'account' => $user->account,
            'password' => $password,
        ]);

        // 應該被導向到route('index')
        $response->assertRedirect(route('index'));
        // 應該有登入的 session
        $this->assertAuthenticatedAs($user);
        // 不會有錯誤訊息
        $response->assertSessionHasNoErrors();
    }

    /**
     * 沒有帳號密碼, 應該不能登入
     *
     * @return void
     */
    public function test_user_cannot_login_without_account_and_password()
    {
        $response = $this->post('login');

        // Check for redirection back to the previous page
        $response->assertRedirect(url()->previous());

        // Check if the session contains the expected error message
        $response->assertSessionHasErrors([
            'message' => '帳號或密碼不可為空',
        ]);
    }
    /**
     * 帳號密碼錯誤, 應該不能登入
     *
     * @return void
     */
    public function test_user_cannot_login_with_wrong_account_or_password()
    {
        $password = 'test';
        // 生成一個使用者
        $user = \App\Models\User::factory()->create(
            [
                'password' => Hash::make($password),
            ]
        );

        // 嘗試登入
        $response = $this->post('login', [
            'account' => $user->account,
            'password' => 'wrong_password',
        ]);

        // 應該被導向回上一頁
        $response->assertRedirect(url()->previous());

        // 應該有錯誤訊息
        $response->assertSessionHasErrors([
            'message' => '帳號或密碼錯誤',
        ]);
    }
}
