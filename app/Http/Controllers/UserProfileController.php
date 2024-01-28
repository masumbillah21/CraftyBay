<?php

namespace App\Http\Controllers;

use App\Helper\JWTHelper;
use App\Models\UserProfile;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helper\CraftyJsonResponse;

class UserProfileController extends Controller
{

    function createProfile(Request $request) {
        try{
            $request->validate([
                'first_name' => 'required|max:20',
                'last_name' => 'required|max:20',
                'phone' => 'required|min:11|max:11|digits:11',
                'address' => 'required|max:20',
                'city' => 'required|max:20',
                'shipping_address' => 'required|max:100',
            ]);
    
            $token = $request->header('token');
            $tokenData = JWTHelper::DecodeToken($token);
    
            $userId =  $tokenData->userID;
    
            UserProfile::updateOrCreate(
                ['user_id' => $userId],
                [
                    'first_name' => $request->input('first_name'),
                    'last_name' => $request->input('last_name'),
                    'phone' => $request->input('phone'),
                    'address' => $request->input('address'),
                    'city' => $request->input('city'),
                    'shipping_address' => $request->input('shipping_address'),
                ]
            );
    
            return CraftyJsonResponse::response('success', 'Request Success', '');
        }catch(Exception $exception){
            return CraftyJsonResponse::response("error",$exception->getMessage());
        }
    }
    
    public function readProfile(Request $request): JsonResponse
    {

        $token = $request->header('token');
        $tokenData = JWTHelper::DecodeToken($token);

        $userId =  $tokenData->userID;

        $userProfile = UserProfile::where('user_id', $userId)->first(); 
        
        if($userProfile == null){
            return CraftyJsonResponse::response('success', 'Request Success', '');
        }

        return CraftyJsonResponse::response('success', 'Request Success', $userProfile);
    }
}
