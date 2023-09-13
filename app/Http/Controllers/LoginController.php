<?php

namespace App\Http\Controllers;

use App\Services\LineService;
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

        // 驗證帳號密碼
        if (!Auth::attempt($credentials, $request->remember)) {
            return back()->withErrors([
                'message' => '帳號或密碼錯誤',
            ]);
        }
        // 重新生成 session id
        $request->session()->regenerate();
        return redirect()->route('index');
    }

}
