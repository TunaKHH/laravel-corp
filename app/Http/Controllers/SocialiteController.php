<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return 301
     */
    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback()
    {
        // 若已經登入，則導回首頁
        if (Auth::check()) {
            return redirect()->route('index');
        }
        $googleUser = Socialite::driver('google')->stateless()->user();
        if (!$googleUser->email) {
            // 處理沒有email的情況，可能重定向回登入頁面並顯示錯誤信息
            return redirect()->route('login')->with('error', 'Google帳戶沒有提供郵箱');
        }
        // dd($googleUser->email);
        // 檢查是否有帳號
        // 沒有就建立帳號
        // 有就登入並更新token
        $user = User::where('account', $googleUser->email)->first();
        if ($user) {
            $user->google_id = $googleUser->id;
            $user->google_token = $googleUser->token;
            $user->google_refresh_token = $googleUser->refreshToken;
            $user->save();
            Auth::login($user);
            return redirect()->route('index');
        }
        // 建立帳號
        $user = new User;
        $user->account = $googleUser->email;
        $user->google_id = $googleUser->id;
        $user->name = $googleUser->name;
        $user->nickname = $googleUser->nickname;
        $user->google_token = $googleUser->token;
        $user->google_refresh_token = $googleUser->refreshToken;
        $user->save();

        // $user = User::updateOrCreate([
        //     'account' => $googleUser->email,
        // ], [
        //     'google_id' => $googleUser->id, // 檢查是否有google帳號
        //     'name' => $googleUser->name,
        //     'nickname' => $googleUser->nickname,
        //     'google_token' => $googleUser->token,
        //     'google_refresh_token' => $googleUser->refreshToken,
        // ]);
        // 登入
        Auth::login($user);
        // 導回首頁
        return redirect()->route('index');
    }
}
