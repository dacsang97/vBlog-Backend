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
$app->get('/posts', 'PostController@index');
$app->get('/posts/{id:[\d]+}', [
    'as' => 'posts.show',
    'uses' => 'PostController@show',
]);
$app->post('/posts', 'PostController@store');