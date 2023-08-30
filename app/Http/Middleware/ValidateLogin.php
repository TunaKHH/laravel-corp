<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $account = $request->input('account');
        $password = $request->input('password');

        if (empty($account) || empty($password)) {
            return back()->withErrors([
                'message' => '帳號或密碼不可為空',
            ])->withInput();
        }
        return $next($request);
    }
}
