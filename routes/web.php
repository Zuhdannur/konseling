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
    return 'Welcome To Api visit https://master-konseling.herokuapp.com for more information';
});

$router->group(['prefix'=>'v1/api'],function () use ($router){
    $router->post('login','UsersController@login');
    $router->post('register','UsersController@register');
    $router->group(['middleware' => 'auth'],function () use ($router){
        //Message
       $router->get('index','MessagesController@index');
       $router->post('send','MessagesController@store');

       //profile
       $router->get('profile','UsersController@getMyProfile');
       $router->post('profile','UsersController@updateProfile');

       //schedule
       $router->post('schedule','SchedulesController@send');
       $router->get('mySchedule','SchedulesController@viewMySchedule');

       //Diary
        $router->post('diary','DiariesController@store');

       //master

            //School
            $router->get('allSchool','MastersController@getListSchool');
            $router->post('storeSchool','MastersController@storeSchool');
            $router->get('deleteSchool/{id}','MastersController@destroySchool');


            //Class
            $router->get('allClass/{id}','MastersController@getListClass');
            $router->post('storeClass','MastersController@storeClass');

    });
});
