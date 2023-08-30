<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    //
    public function show()
    {
        return view('auth.register');
    }

    /**
     * @OA\Post(
     *   tags={"User"},
     *   path="/register",
     *   summary="註冊帳號",
     *   @OA\RequestBody(
     *     required=true,
     *     description="註冊資料",
     *   ),
     *   @OA\Response(response=200, description="OK")
     * )
     */
    public function create(Request $request)
    {
        // 建立帳號
        $this->userService->register($request->all());
        // 重新生成 session id
        $request->session()->regenerate();
        // 跳轉到首頁
        return redirect()->route('index');
    }
}
