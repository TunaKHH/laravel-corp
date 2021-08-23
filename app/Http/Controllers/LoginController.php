<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\LineService;
use Illuminate\Support\Facades\Auth;
use function Deployer\get;


class LoginController extends Controller
{
    protected $lineService;

    public function __construct(LineService $lineService)
    {
        $this->lineService = $lineService;
    }


    public function show(){
        return view('auth.login');
    }

    public function logout(Request $request){
        Auth::logout();


        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }


    public function authenticate(Request $request)
    {
        $account = $request->get('account');
        $password = $request->get('password');
        $credentials = $request->only('account', 'password');# 帳號登入


        # 兩種登入方式
        if (!Auth::attempt($credentials,$request->remember) && !Auth::attempt(['email'=>$account, 'password'=>$password],$request->remember)) {

            return back()->withErrors([
                'account' => '帳號或密碼錯誤',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->route('index');


    }

    public function pageLine()
    {
        $url = $this->lineService->getLoginBaseUrl();
        return view('line')->with('url', $url);
    }

    public function lineLoginCallBack(Request $request)
    {
        try {
            $error = $request->input('error', false);
            if ($error) {
                throw new Exception($request->all());
            }
            $code = $request->input('code', '');
            $response = $this->lineService->getLineToken($code);
            $user_profile = $this->lineService->getUserProfile($response['access_token']);
            echo "<pre>"; print_r($user_profile); echo "</pre>";
        } catch (Exception $ex) {
            Log::error($ex);
        }
    }
}
