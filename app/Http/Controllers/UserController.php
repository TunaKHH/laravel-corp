<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @OA\Get(
     *   tags={"User"},
     *   path="/api/users",
     *   summary="Get list of users sorted by deposit",
     *   description="Returns all users sorted in descending order by their deposit",
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(
     *         property="users",
     *         type="array",
     *         description="List of users sorted by deposit",
     *         @OA\Items(ref="#/components/schemas/UserResource")
     *       )
     *     )
     *   )
     * )
     */
    public function index()
    {
        $users = $this->userService->getAllUsersSortedByDeposit();
        return view('user.index', ['users' => $users]);
    }

    public function create()
    {
        return redirect()->route('lunch.index');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('user.edit', ['user' => $user]);
    }

    /**
     * @OA\Put(
     *   tags={"User"},
     *   path="/profile",
     *   summary="更新使用者個人資料",
     *   description="更新使用者個人資料",
     *   @OA\RequestBody(
     *     required=false,
     *     description="使用者資料",
     *     @OA\JsonContent(
     *       required={},
     *       @OA\Property(property="nickname", type="string", example="test"),
     *       @OA\Property(property="email", type="string", example="test@test.com"),
     *       @OA\Property(property="password", type="string", example="test"),
     *       @OA\Property(property="password2", type="string", example="test"),
     *     ),
     *   ),
     *   @OA\Response(response=302, description="重新導向到 /profile, 並帶上 success 訊息"),
     * )
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $result = $this->userService->updateUserProfile($user, $request->all());

        if ($result->isSuccess()) {
            return back()->with('success', '修改成功');
        } else {
            return back()->withErrors($result->getErrors())->withInput();
        }
    }

}
