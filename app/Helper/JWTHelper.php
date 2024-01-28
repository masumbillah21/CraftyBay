<?php

namespace App\Helper;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTHelper
{

    public static function CreateToken($userEmail,$userID){
        $key=env('JWT_KEY');
        $payload=[
            'iss'=>'laravel-demo',
            'iat'=>time(),
            'exp'=>time()+60*60,
            'userEmail'=>$userEmail,
            'userID'=>$userID
        ];
        return JWT::encode($payload,$key,env('JWT_ALGO'));
    }

    public static function DecodeToken($token){
        try {
            if($token==null){
                return "unauthorized";
            }
            else{
                $key=env('JWT_KEY');
                return JWT::decode($token,new Key($key,env('JWT_ALGO')));
            }

        }catch (Exception $exception){
            return "unauthorized";
        }

    }

}