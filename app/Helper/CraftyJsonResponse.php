<?php
namespace App\Helper;
class CraftyJsonResponse
{
    public static function response($status, $message, $data = null, $code = 200)
    {

        $res = [
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ];
        
        return response()->json([
            $res
        ], $code);
    }
}
