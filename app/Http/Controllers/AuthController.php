<?php

namespace App\Http\Controllers;

use App\Http\Models\CleanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Lcobucci\JWT\Claim\Validatable;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    protected $cleanModel;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => 'login']);
        $this->cleanModel = new CleanModel();
    }

    public function login(Request $request)
    {
        $user = $this->cleanModel->first();

        if(!$token = Auth::guard('api')->fromUser($user)){
            return response()->json(['error'=>'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json(Auth::guard('api')->user());
    }

    public function logout()
    {
        Auth::guard('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }


    public function refresh()
    {
        return $this->respondWithToken(Auth::guard('api')->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60
        ]);
    }

}
