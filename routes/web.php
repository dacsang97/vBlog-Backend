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

// Authenticate API

$app->post('auth/sign_in', 'AuthController@signIn');

// Post API

$app->group(['prefix'=>'posts'], function() use ($app) {
    $app->get('/', 'PostController@index');
    $app->get('/{id:[\d]+}', [
        'as' => 'posts.show',
        'uses' => 'PostController@show',
    ]);
    $app->post('/', 'PostController@store');
    $app->put('/{id:[\d]+}', 'PostController@update');
    $app->delete('/{id:[\d]+}', 'PostController@destroy');
});

$app->group(['prefix' => 'users'], function() use($app) {
    $app->get('/', 'UserController@index');
    $app->get('/{id:[\d]+}', ['middleware' => 'auth', 'uses' => 'UserController@show']);
});