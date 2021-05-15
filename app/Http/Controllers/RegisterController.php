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
            'name.required'=>'本名必填',
            'account.required'=>'帳號必填',
            'password.required'=>'密碼必填',

            'name.max'=>'你他媽名字有這麼長?字數不得超過255',
            'account.max'=>'你他媽帳號有這麼長?字數不得超過255',
            'password.max'=>'你他媽帳號有這麼長?字數不得超過255',
        ];

        $validator = Validator::make($request->all(), [
            'invitation_code' => 'required',
            'name' => 'required|max:255',
            'account' => 'required|max:255',
            'password' => 'required|max:255',
        ],$messages);

        if( $validator->fails() ){
            return back()->withErrors($validator)->withInput();
        }

        $invitation_code = $request->invitation_code;

        if( $invitation_code != env('INVITATION_CODE')){// 檢查邀請碼
            return back()->withErrors([
                'error' => ' 邀請碼錯誤',
            ]);
        }

        $user = User::where('account', $request->account)->first();
        if( !empty($user) ){// 檢查帳號有無重複
            return back()->withErrors([
                'error' => ' 帳號重複',
            ]);
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
