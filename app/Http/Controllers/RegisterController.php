<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    //
    public function show(){
        return view('auth.register');
    }

    public function create(Request $request){// 創帳號
        $messages = [
            'invitation_code.required'=>'邀請碼必填',
            'name.required'=>'名稱必填',
            'account.required'=>'帳號必填',
            'account.unique'=>'帳號重複',
            'password.required'=>'密碼必填',
            'name.unique'=>'名稱重複',
            'name.max'=>'名稱有這麼長?字數不得超過255',
            'account.max'=>'帳號有這麼長?字數不得超過255',
            'password.max'=>'帳號有這麼長?字數不得超過255',
        ];

        $validator = Validator::make($request->all(), [
            'invitation_code' => 'required',
            'name' => 'required|max:255|unique:users',
            'account' => 'required|min:6|max:255|unique:users,email|unique:users,account',
            'password' => 'required|min:8|max:255',
        ],$messages);

        if( $validator->fails() ){
            return back()->withErrors($validator)->withInput();
        }

        $invitation_code = $request->invitation_code;

        if( $invitation_code != env('INVITATION_CODE')){// 檢查邀請碼
            return back()->withErrors([
                'error' => ' 邀請碼錯誤',
            ])->withInput();
        }

        $user  = new User;
        $user->account = $request->account;
        $user->password = Hash::make($request->password);
        $user->name = $request->name;
        $user->save();

        $request->session()->regenerate();

        return redirect()->route('index');
    }
}
