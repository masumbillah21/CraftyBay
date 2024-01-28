<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helper\CraftyJsonResponse;
use Symfony\Component\HttpFoundation\Response;

class HasApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->header('token') == null || $request->header('token') == '') {
            return CraftyJsonResponse::response("error","Unauthorized.", null, 401);
        }
        
        return $next($request);
    }
}
