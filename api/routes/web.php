<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
  $results = app('db')->select("SELECT * FROM users");
  // var_dump($results);
  return 'Home page for SNH Medwork API';
});

$router->get('/login', 'UserController@login');
$router->get('/register', 'UserController@register');

$router->get('/ipd-consult', 'ConsultController@addConsult');
$router->get('/consults', 'ConsultController@getConsult');
$router->get('/find-consult-hn', 'ConsultController@getConsultByHN');

$router->group(['prefix' => 'auth', 'middleware' => 'jwt.auth'], function ($router) {
  $router->get('test', 'UserController@test');
});
