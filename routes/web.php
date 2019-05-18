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



/**
 * Routes for resource user
 */

$router->get('/', function (){
    return 'Welcome To Api';
});
$router->group(['prefix'=>'v1/api'],function () use ($router){
    $router->post('login','UsersController@login');
    $router->post('register','UsersController@register');
    $router->group(['middleware' => 'auth'],function () use ($router){
       $router->get('list',function (){
           return 'LIST';
       });
    });
});

