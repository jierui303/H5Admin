<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use App\Http\Models\CleanModel;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\JWT;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Support\Facades\Auth;

class LoginApiSign
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        try {

            if (!$user = \Tymon\JWTAuth\Facades\JWTAuth::parseToken()->authenticate()) {  //获取到用户数据，并赋值给$user
                return response()->json([
                    'errcode' => 1004,
                    'errmsg' => 'user not found'
                ], 404);
            }

            $user = $user->toArray();

            //如果想向控制器里传入用户信息，将数据添加到$request里面
            $request->attributes->add($user);//添加参数  在控制器中获取直接用
            return $next($request);

        } catch (TokenExpiredException $e) {

            return response()->json([
                'errcode' => 1003,
                'errmsg' => 'token 过期' , //token已过期
            ]);

        } catch (TokenInvalidException $e) {

            return response()->json([
                'errcode' => 1002,
                'errmsg' => 'token 无效',  //token无效
            ]);

        } catch (JWTException $e) {

            return response()->json([
                'errcode' => 1001,
                'errmsg' => '缺少token' , //token为空
            ]);

        }


    }
}
