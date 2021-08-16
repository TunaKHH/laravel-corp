<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
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
    public function profile(){
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
    public function update(Request $request, User $user)
    {

        $messages = [
            'nickname.max'=>'暱稱字數不得超過255',
            'email.max'=>'信箱字數不得超過255',
            'password.max'=>'密碼字數不得超過255',
        ];
        $validator = Validator::make($request->all(), [
            'nickname' => 'max:255',
            'email' => 'max:255',
            'password' => 'max:255',
        ],$messages);

        if( $validator->fails() ){
            return back()->withErrors($validator,'errors')->withInput();
        }
        if( $request->has('password') ){// 有填入密碼欄位
            if(  $request->password != $request->password2 ){// 檢查密碼是否一致
                return back()->withErrors([
                    'errors' => ' 兩次密碼不一致',
                ])->withInput();
            }
            $user->password = Hash::make($request->password);
        }
        $user->email = $request->email;
        $user->nickname = $request->nickname;
        $user->save();
        $user->fresh();
        return view('user.edit', ['user' => $user]);
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
