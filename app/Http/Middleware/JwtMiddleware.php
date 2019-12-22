<?php
namespace App\Http\Middleware;

use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

use Illuminate\Support\Str;

use App\User;

class JwtMiddleware
{
    
    public function handle($request, Closure $next, $guard = null)
    {
        $authorization = $request->header('Authorization', '');
        
        $bearer = Str::startsWith($authorization, 'Bearer ');
        $token = Str::substr($authorization, 7);
        
        if(!$token) {
            // Unauthorized response if token not there
            return response()->json([
                'status' => false,
                'code' => 401,
                'message' => 'Token not found. Please login to continue.'
            ], 401);
        }

        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        }
        catch(ExpiredException $e) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => 'Login token has been expired. Login back to continue.'
            ], 400);
        }
        catch(Exception $e) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => 'Invalid JWT token provided.'
            ], 400);
        }

        $request->authUser = User::find($credentials->sub);
        return $next($request);
    }


}