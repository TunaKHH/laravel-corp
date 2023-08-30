<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ValidateRegistration
{
    /**
     * Handle an incoming register request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $messages = [
            'invitation_code.required' => '邀請碼必填',
            'name.required' => '名稱必填',
            'account.required' => '帳號必填',
            'account.unique' => '帳號重複',
            'password.required' => '密碼必填',
            'name.unique' => '名稱重複',
            'name.max' => '名稱字數不得超過255',
            'account.max' => '帳號字數不得超過255',
            'password.max' => '密碼字數不得超過255',
        ];

        $validator = Validator::make($request->all(), [
            'invitation_code' => 'required',
            'name' => 'required|max:255|unique:users',
            'account' => 'required|min:6|max:255|unique:users,email|unique:users,account',
            'password' => 'required|min:8|max:255',
        ], $messages);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // 檢查邀請碼是否有效
        if (!$this->isValiedInvitationCode($request->invitation_code)) {
            return back()->withErrors([
                'error' => ' 邀請碼錯誤',
            ])->withInput();
        }
        return $next($request);
    }

    // 檢查邀請碼是否有效
    public function isValiedInvitationCode($invitation_code)
    {
        if ($invitation_code != env('INVITATION_CODE')) {
            return false;
        }
        return true;
    }

}
