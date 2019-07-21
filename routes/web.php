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

$router->get('/', function () {
    return 'Welcome To Api visit https://master-konseling.herokuapp.com for more information';
});
$router->get('/test', 'SchedulesController@notification');
$router->group(['prefix'=>'v1/api'], function () use ($router) {
    $router->post('login', 'UsersController@login');
    $router->post('register', 'UsersController@register');

    $router->get('title', 'ArtikelsController@getTitle');
    $router->post('artikel', 'ArtikelsController@create');
    $router->post('related', 'ArtikelsController@getRelatedArtikel');
    $router->post('relatedCount', 'ArtikelsController@getRelatedArtikelCount');

    $router->get('profile/{id}', 'UsersController@getMyProfile');

    $router->get('sekolah', 'SekolahsController@all');
    $router->get('sekolah/{id}', 'SekolahsController@get');
    $router->post('sekolah', 'SekolahsController@add');
    $router->put('sekolah/{id}', 'SekolahsController@put');
    $router->delete('sekolah/{id}', 'SekolahsController@remove');

    $router->get('kelas', 'KelasController@all');
    $router->get('kelas/{id}', 'KelasController@get');
    $router->post('kelas', 'KelasController@add');
    $router->put('kelas/{id}', 'KelasController@put');
    $router->delete('kelas/{id}', 'KelasController@remove');

    $router->get('user', 'UsersController@getAllUser');

    $router->group(['middleware' => 'auth'], function () use ($router) {
        
        //Message
        $router->get('index', 'MessagesController@index');
        $router->post('send', 'MessagesController@store');

        //profile
        $router->post('profile', 'UsersController@updateProfile');
        $router->post('updateImage', 'UsersController@updateImageProfile');

        //schedule
        $router->post('schedule', 'SchedulesController@send');
        $router->post('updateSchedule', 'SchedulesController@updateSchedule');
        $router->post('mySchedule/{id}', 'SchedulesController@viewMySchedule');
        $router->post('mySchedulePageCount/{id}', 'SchedulesController@mySchedulePageCount');
        $router->post('readStudentSchedule', 'SchedulesController@studentSchedule');

        $router->post('mySchedulePageCount/', 'SchedulesController@mySchedulePageCount');
        $router->post('mySchedule', 'SchedulesController@viewMySchedule');
        $router->post('mySchedule', 'SchedulesController@getPengajuanByStatus');
        $router->post('mySchedulePage', 'SchedulesController@getPengajuanByStatusPageCount');
        $router->get('expired/{id}', 'SchedulesController@deleteSchedule');

        $router->delete('schedule/{id}', 'SchedulesController@deleteDirectSchedule');

        $router->post('scheduleDirect/{id}', 'SchedulesController@postScheduleDirect');
        $router->post('scheduleDirectCount/{id}', 'SchedulesController@postScheduleDirectCount');

        //Diary
        $router->post('diary', 'DiariesController@store');
        $router->post('updateDiary', 'DiariesController@updateDiary');

        $router->get('diary', 'DiariesController@showMyDiary');
        $router->get('diaryPageCount', 'DiariesController@showMyDiaryPageCount');
        $router->get('deleteDiary/{id}', 'DiariesController@deleteDiary');
        $router->get('/shareDiary', 'DiariesController@showMyDiaryToOthers');
        $router->get('/shareDiaryCount', 'DiariesController@showMyDiaryToOthersPageCount');
        //master

        //School
        $router->get('deleteSchool/{id}', 'MastersController@destroySchool');
            
        //User
        $router->delete('user/{id}', 'UsersController@destroy');
        // $router->get('user', 'UsersController@getAllUser');
            
        //Favorite Artikels
        $router->post('favorit', 'ArtikelsController@storeFavorite');
        $router->get('favorit', 'ArtikelsController@getMyFavorite');
        $router->get('favoritCount', 'ArtikelsController@getMyFavoriteCount');
        $router->get('favorit/{id}', 'ArtikelsController@removeMyFavorit');
    });
});
