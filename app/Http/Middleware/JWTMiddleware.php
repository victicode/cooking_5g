<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
       try	{
        $user = JWTAuth::parseToken()->authenticate();
       } catch(Exception $e){
					if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
						$newToken = JWTAuth::parseToken()->refresh();
						return response()->json(['status' => 'Token is Invalid', 'new_token' => $newToken]);
					}
					if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
						return response()->json(['status' => 'Token is Expired']);
					}
       		return response()->json(['status' => 'Token not found']);
       }
			 return $next($request);
    }
}
