<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class JWTController extends BaseController
{

  public function NewToken($user) {
    // $user is user object in RedbeanPHP

    $key = env('JWT_PRIVATE');
    $payload = [
      'iss' => 'Medwork.com',
      'aud' => 'Medwork.com',
      'iat' => time(),
      'exp' => time() + 24*60*60
    ];
    $payload['sub'] = $user->email;
    $payload['name'] = $user->name;
    $payload['role'] = $user->role;

    $jwt = JWT::encode($payload, $key, 'HS256');
    return $jwt;
  }

  public function verify($code) {
    $key = env('JWT_PRIVATE');
    $response = [
      'result' => true,
      'payload' => null
    ];
    try {
      $decoded = JWT::decode($code, new Key($key, 'HS256'));
      $response['payload'] = (array) $decoded;
    } catch (\Exception $e) {
      $decoded = $e->getMessage();
      $response['result'] = false;
    }

    return $response;
  }

  public function test() {
    return 'test function';
  }
}
