<?php
namespace App\Http\Middleware;

use Closure;
use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTMiddleware
{
  public function handle($request, Closure $next, $guard = null)
  {
    $token = $request->header('Authorization');

    if (!$token) {
      // Unauthorized response if token not there
      return response()->json([
        'error' => 'Token not provided.',
      ], 401);
    }
    try {
      // $credentials = JWT::decode($token, env('JWT_PRIVATE'), ['HS256']);
      $credentials = JWT::decode($token, new Key(env('JWT_PRIVATE'), 'HS256'));
    } catch (ExpiredException $e) {
      return response()->json([
        'error' => 'Provided token is expired.',
      ], 400);
    } catch (Exception $e) {
      return response()->json([
        'error' => 'An error while decoding token.',
        'msg' => $e->getMessage()
      ], 400);
    }
    $user = $credentials->sub;
    // Now let's put the user in the request class so that you can grab it from there
    $request->auth = $credentials;
    return $next($request);
  }
}
