<?php

namespace App\Http\Controllers;

use App\Services\LineService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    protected $lineService;

    public function __construct(LineService $lineService)
    {
        $this->lineService = $lineService;
    }

    public function show()
    {
        return view('auth.login');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * @OA\Post(
     *   tags={"User"},
     *   path="/login",
     *   summary="登入",
     *   @OA\RequestBody(
     *     required=true,
     *     description="登入資料",
     *     @OA\JsonContent(
     *       required={"account","password"},
     *       @OA\Property(property="account", type="string", example="test"),
     *       @OA\Property(property="password", type="string", example="test"),
     *       @OA\Property(property="remember", type="boolean", example="true"),
     *     ),
     *     @OA\MediaType(
     *       mediaType="application/x-www-form-urlencoded",
     *       @OA\Schema(
     *         required={"account","password"},
     *         @OA\Property(property="account", type="string", example="test"),
     *         @OA\Property(property="password", type="string", example="test"),
     *         @OA\Property(property="remember", type="boolean", example="true"),
     *         )
     *       )
     *     ),
     *     @OA\Response(response=200, description="OK")
     *   )
     * )
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('account', 'password'); # 帳號登入

        # 兩種登入方式
        if (!Auth::attempt($credentials, $request->remember)) {
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
            echo "<pre>";
            print_r($user_profile);
            echo "</pre>";
        } catch (Exception $ex) {
            Log::error($ex);
        }
    }
}
