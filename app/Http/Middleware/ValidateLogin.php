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
        logger('in ValidateLogin');
        logger(var_dump($account));
        logger(var_dump($password));
        logger('in ValidateLogin 2');

        if (empty($account) ||
            empty($password)
        ) {
            logger('in ValidateLogin 3');

            return back()->withErrors([
                'message' => '帳號或密碼不可為空',
            ])->withInput();
        }
        logger('in ValidateLogin 4');

        return $next($request);
    }
}
