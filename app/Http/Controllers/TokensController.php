<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mockery\Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\User;
use Tymon\JWTAuth\JWT;
use Validator;

class TokensController extends Controller
{
    //
    public function login(Request $request){
       $credential=$request->only('email', 'password');
       //validar data
        $validator= \Validator::make($credential,[
           'email' => 'required|email',
            'password'=> 'required'
        ]);
        if($validator->fails()){
            return response()->json([
                'success'=> false,
                'message' => 'Wrong validation',
                'erros'=>$validator->errors() // show errors
            ],422);
        }
        $token=JWTAuth::attempt($credential);
        if($token){
            return response()->json([
                'success'=>true,
                'message' =>'successfull',
                'token'=> $token,
                'user'=>User::where('email', '=',$credential['email'])->get()->first()
            ],200);
        }else{

            return response()->json([
                'success'=> false,
                'message' => 'Wrong validation',
                'erros'=>$validator->errors() // show all errors
            ],401);
            //401 => no autorizado
        }

    }

    public function refreshToken(){
        $token=JWTAuth::getToken();
        try {
            $token = JWTAuth::refresh($token);
            return response()->json([
                'success' => true,
                'token' => $token
            ], 200);

        }catch (TokenExpiredException $exception){
            return response()->json([
                'success'=> false,
                'message' => 'Need to login again pleas (expired)',

            ],422 );
        }catch (TokenBlacklistedException $exception){
            return response()->json([
                'success'=> false,
                'message' => 'Need to login again (blacklisted)',
            ],422);
        }

    }

    public function logout(){
        $token = JWTAuth::getToken();
        try {
            JWTAuth::invalidate($token);
            return response()->json([
                "success" => true,
                "message" => "logout successfull"
            ], 200);
         } catch (JWTException $exception) {
            return response()->json([
                "success" => false,
                "message" => "Failed logout"
            ], 422);
        }
    }

    public function getUsers(){
        $usuarios=User::all();
        return response()->json([
            "success"=>true,
            "usuarios"=> $usuarios

        ],200);
    }
}
