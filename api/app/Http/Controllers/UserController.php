<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
require __DIR__.'/../../../rb.php';
use R as R;
use App\Http\Controllers\JWTController;

class UserController extends Controller
{
  /**
* Retrieve the user for the given ID.
*
* @param  int  $id
* @return Response
*/
  public function register(Request $request)
  {
    $res = [
      'result' => true,
      'data' => null,
      'error' => null
    ];

    if (!$request->input('email') || !$request->input('password') || !$request->input('name')) {
      $res['error'] = 'Need email, password, and name to register!';
      $res['result'] = false;
      return $res;
    }
    
    R::setup(env('DB_URL'), env('DB_USERNAME'), env('DB_PASSWORD'));
    $user = R::find('users', 'email = ?', [$request->input('email')]);
    if ($user != null) {
      $res['error'] = 'This email is already exists!';
      $res['result'] = false;
      return $res;
    }
    
    $newUser = R::dispense('users');
    $newUser->email = $request->input('email');
    $newUser->password = password_hash($request->input('password'), PASSWORD_DEFAULT);
    $newUser->name = $request->input('name');
    $newId = R::store($newUser);
    // echo JWTController::NewToken('test');
    $JWT = new JWTController();
    $decoded = $JWT->verify(env('TEST_JWT'));
    $res['data'] = json_encode($decoded);
    return $res;
    // echo $request->input('email').'<br>';
  }


  public function login(Request $request)
  {
    $res = [
      'result' => true,
      'data' => null,
      'error' => null
    ];

    if (!$request->input('email') || !$request->input('password')) {
      $res['error'] = 'Need email, password to login!';
      $res['result'] = false;
      return $res;
    }
    
    R::setup(env('DB_URL'), env('DB_USERNAME'), env('DB_PASSWORD'));
    $user = R::findOne('users', 'email = ?', [$request->input('email')]);
    if ($user == null) {
      $res['error'] = 'This email is not exists!';
      $res['result'] = false;
      return $res;
    }

    if (!password_verify($request->input('password'), $user->password)) {
      $res['error'] = 'Wrong password';
      $res['result'] = false;
      return $res;
    }

    $JWT = new JWTController();
    $res['data'] = $JWT->NewToken($user); 
    return $res;
  }
    
  public function test(Request $request)
  {
    return [
      'email' => $request->auth->sub
    ];
  }
}
