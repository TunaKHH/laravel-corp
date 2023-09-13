<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserSocialAccount;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return 301
     */
    public function redirectToGoogleAuthPage()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Redirect the user to the Line authentication page.
     *
     * @return 301
     */
    public function redirectToLineAuthPage()
    {
        return Socialite::driver('line')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handleGoogleLoginCallback()
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
        // 檢查是否有社交帳號
        // 沒有就建立user 後再建立社交帳號
        // 有就登入並更新token
        $userSocial = UserSocialAccount::where('provider', 'google')
            ->where('provider_id', $googleUser->id)
            ->first();
        // 若沒有社交帳號，則建立帳號
        if (!isset($userSocial)) {
            // 建立一般帳號
            $user = new User;
            $user->email = $googleUser->email;
            $user->google_id = $googleUser->id;
            $user->name = $googleUser->name;
            $user->nickname = $googleUser->nickname;
            $user->google_token = $googleUser->token;
            $user->google_refresh_token = $googleUser->refreshToken;
            $user->save();
            // 建立社交帳號
            $userSocial = new UserSocialAccount;
            $userSocial->user_id = $user->id;
            $userSocial->provider = 'google';
            $userSocial->provider_id = $googleUser->id;
            $userSocial->token = $googleUser->token;
            $userSocial->refresh_token = $googleUser->refreshToken;
            $userSocial->save();
        } else {
            // 更新token
            $userSocial->token = $googleUser->token;
            $userSocial->refresh_token = $googleUser->refreshToken;
            $userSocial->save();
        }
        // 登入
        Auth::login($userSocial->user ?? $user); // 若有userSocial->user，則使用userSocial->user，否則使用$user
        // 導回首頁
        return redirect()->route('index');
    }

    /**
     * Obtain the user information from Line.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handleLineLoginCallback()
    {
        // 若已經登入，則導回首頁
        if (Auth::check()) {
            return redirect()->route('index');
        }
        // 取得line user
        $lineUser = Socialite::driver('line')->stateless()->user();

        // 檢查是否有社交帳號
        // 沒有就建立user 後再建立社交帳號
        // 有就登入
        $userSocial = UserSocialAccount::where('provider', 'line')
            ->where('provider_id', $lineUser->id)
            ->first();
        // 若沒有社交帳號，則建立帳號
        if (!isset($userSocial)) {
            // 建立一般帳號
            $user = new User;
            $user->email = $lineUser->email ?? null;
            $user->name = $lineUser->name;
            $user->nickname = $lineUser->nickname;
            $user->save();
            // 建立社交帳號
            $userSocial = new UserSocialAccount;
            $userSocial->user_id = $user->id;
            $userSocial->provider = 'line';
            $userSocial->provider_id = $lineUser->id;
            $userSocial->save();
        }
        // 登入
        Auth::login($userSocial->user ?? $user); // 若有userSocial->user，則使用userSocial->user，否則使用$user
        // 導回首頁
        return redirect()->route('index');
    }
}
