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

$router->get('/push',function (){
    event(new App\Events\ExampleEvent('Hi PUSH'));
    return 'Send';
});

$router->group(['prefix'=>'v1/api'],function () use ($router){
    $router->post('login','UsersController@login');
    $router->post('register','UsersController@register');
    $router->group(['middleware' => 'auth'],function () use ($router){
       $router->get('index','MessagesController@index');
       $router->post('send','MessagesController@store');
       $router->get('profile','UsersController@getMyProfile');
       $router->post('schedule','SchedulesController@send');
       $router->get('mySchedule','SchedulesController@viewMySchedule');
    });
});


/**
 * Routes for resource master
 */
//$app->get('master', 'MastersController@all');
//$app->get('master/{id}', 'MastersController@get');
//$app->post('master', 'MastersController@add');
//$app->put('master/{id}', 'MastersController@put');
//$app->delete('master/{id}', 'MastersController@remove');

/**
 * Routes for resource schedule
 */
//$app->get('schedule', 'SchedulesController@all');
//$app->get('schedule/{id}', 'SchedulesController@get');
//$app->post('schedule', 'SchedulesController@add');
//$app->put('schedule/{id}', 'SchedulesController@put');
//$app->delete('schedule/{id}', 'SchedulesController@remove');
