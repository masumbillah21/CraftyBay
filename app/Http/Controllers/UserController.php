<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Mail\OTPEmail;
use App\Helper\JWTHelper;
use Illuminate\Http\Request;
use App\Helper\CraftyJsonResponse;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function userLogin(Request $request){
          
        try{
            $request->validate([
                'email'=>'required|email|lowercase',
            ]);

            $userEmail = $request->input('email');
    
            $otp = rand(100000,999999);

            User::updateOrCreate(['email' => $userEmail], ['email'=>$userEmail,'otp'=>$otp]);
    
            //send email with otp
            Mail::to($request->email)->send(new OTPEmail($otp));

            $token = JWTHelper::CreateToken($request->email,0);
    
            return CraftyJsonResponse::response("success","OTP sent to your email.", $token);

        }catch(Exception $exception){
            return CraftyJsonResponse::response("error",$exception->getMessage());
        }

    }

    public function verifyOTP(Request $request){
        try{
            $request->validate([
                'otp'=>'required',
            ]);
            if ($request->header('token') == null) {
                return CraftyJsonResponse::response("error","Unauthorized.", null, 401);
            }

            $token = $request->header('token');
            $tokenData = JWTHelper::DecodeToken($token);

            $userEmail = $tokenData['userEmail'];
            $otp = $request->input('otp');

            $user = User::where('email', $userEmail)->where('otp', $otp)->first();

            if($user->otp == $otp){
                $token = JWTHelper::CreateToken($userEmail,$user->id);
                return CraftyJsonResponse::response("success","OTP verified.", $token);
            }else{
                return CraftyJsonResponse::response("error","Invalid OTP.");
            }
        }catch(Exception $exception){
            return CraftyJsonResponse::response("error",$exception->getMessage());
        }
    }

}
