<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use OpenApi\Annotations as OA;

class UserController extends Controller
{
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
        $users = User::all()->sortBy('deposit')->reverse();
        return view('user.index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect()->route('lunch.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function profile()
    {
        $user = Auth::user();
//        dd($user);
        return view('user.edit', ['user' => $user]);
    }

//    public function edit(User $user)
//    {
//        // TODO 加入管理員權限 or 自己才能訪問
//        return view('user.edit', ['user' => $user]);
//    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $messages = [
            'nickname.max' => '暱稱字數不得超過255',
            'email.max' => '信箱字數不得超過255',
            'email.email' => '信箱規則錯誤',
            'email.unique' => '信箱重複',
            'password.max' => '密碼字數不得超過255',
        ];
        $validator = Validator::make($request->all(), [
            'nickname' => 'max:255',
            Rule::unique('users', 'account')->ignore(Auth::id()),
            'email' => 'nullable|email|max:255|unique:users,email',
            'password' => 'max:255',
        ], $messages);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        if ($request->get('password') && $request->get('password2')) { // 有填入密碼欄位
            if ($request->password != $request->password2) { // 檢查密碼是否一致
                return back()->withErrors([
                    'errors' => ' 兩次密碼不一致',
                ])->withInput();
            }
            $user->password = Hash::make($request->password);
        }
        // 如果原本沒有設定信箱，就設定
        if (!isset($user->email)) {
            $user->email = $request->email;
        }
        $user->nickname = $request->nickname;
        $user->line_id = $request->line_id;
        $user->save();
        $user->fresh();
        return back()->with('success', '修改成功');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
