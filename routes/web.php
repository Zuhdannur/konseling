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

    $router->get('allSchool','MastersController@getListSchool');
    $router->get('allClass/{id}','MastersController@getListClass');

    $router->get('title','ArtikelsController@getTitle');
    $router->post('artikel','ArtikelsController@create');
    $router->post('related','ArtikelsController@getRelatedArtikel');

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
       $router->post('updateDiary/{id}','DiariesController@updateDiary');

        $router->get('diary', 'DiariesController@showMyDiary');
        $router->get('deleteDiary/{id}', 'DiariesController@deleteDiary');
        $router->get('/shareDiary','DiariesController@showMyDiaryToOthers');
       //master

            //School
            $router->post('storeSchool','MastersController@storeSchool');
            $router->get('deleteSchool/{id}','MastersController@destroySchool');


            //Class
            $router->post('storeClass','MastersController@storeClass');
			
			//User
			$router->get('user/{id}','UsersController@destroy');
			$router->get('user','UsersController@getAllUser');
    });
});

/**
 * Routes for resource artikel
 */

