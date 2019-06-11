<?php

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
$router->group(['prefix'=>'api/v1/'], function () use ($router){

    $router->post('users', ['uses' => 'UserController@create']);
    $router->post('login',['uses' => 'AuthController@authenticate']);

});

$router->group(['prefix'=>'api/v1/', 'middleware' => 'jwt.auth'], function () use ($router){
    $router->get('users', ['uses'=>'UserController@showAllUsers']);
    $router->get('users/{id}', ['uses' => 'UserController@showOneUser']);

    $router->post('users', ['uses' => 'UserController@create']);

    $router->post('authors', ['uses' => 'AuthorController@create']);

    $router->delete('users/{id}', ['uses' => 'UserController@delete']);

    $router->put('users/{id}', ['uses' => 'UserController@update']);
});
