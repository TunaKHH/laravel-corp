<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    //
    public function show(){
        return view('auth.register');
    }

    public function create(Request $request){// 創帳號

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
