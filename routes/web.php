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

    /**
    * Routes for resource sekolah
    */
    $router->get('sekolah', 'SekolahsController@all');
    $router->get('sekolah/{id}', 'SekolahsController@get');
    $router->post('sekolah', 'SekolahsController@add');
    $router->put('sekolah/{id}', 'SekolahsController@put');
    $router->delete('sekolah/{id}', 'SekolahsController@remove');

    /**
    * Routes for resource kelas
    */
    $router->get('kelas', 'KelasController@all');
    $router->get('kelas/{id}', 'KelasController@get');
    $router->post('kelas', 'KelasController@add');
    $router->put('kelas/{id}', 'KelasController@put');
    $router->delete('kelas/{id}', 'KelasController@remove');

    //For Develpment Purposes
    $router->get('user', 'UsersController@all');

    $router->group(['middleware' => 'auth'], function () use ($router) {
        
        //Message
        $router->get('index', 'MessagesController@index');
        $router->post('send', 'MessagesController@store');

        //profile
        $router->post('profile', 'UsersController@updateProfile');
        $router->post('updateImage', 'UsersController@updateImageProfile');

        //schedule
        // $router->post('schedule', 'SchedulesController@send');
        // $router->post('updateSchedule', 'SchedulesController@updateSchedule');
        // $router->post('mySchedule/{id}', 'SchedulesController@viewMySchedule');
        // $router->post('mySchedulePageCount/{id}', 'SchedulesController@mySchedulePageCount');
        // $router->post('readStudentSchedule', 'SchedulesController@studentSchedule');

        // $router->post('mySchedulePageCount/', 'SchedulesController@mySchedulePageCount');
        // $router->post('mySchedule', 'SchedulesController@viewMySchedule');
        // $router->post('mySchedule', 'SchedulesController@getPengajuanByStatus');
        // $router->post('mySchedulePage', 'SchedulesController@getPengajuanByStatusPageCount');
        // $router->get('expired/{id}', 'SchedulesController@deleteSchedule');

        // $router->delete('schedule/{id}', 'SchedulesController@deleteDirectSchedule');

        // $router->post('scheduleDirect/{id}', 'SchedulesController@postScheduleDirect');
        // $router->post('scheduleDirectCount/{id}', 'SchedulesController@postScheduleDirectCount');

        /**
         * Routes for resource schedule
         */
        $router->post('schedule', 'SchedulesController@add');
        $router->get('schedule', 'SchedulesController@all');
        $router->get('schedule/{id}', 'SchedulesController@get');
        $router->put('schedule', 'SchedulesController@put');
        $router->delete('schedule/{id}', 'SchedulesController@remove');
        $router->get('schedulePageCount', 'SchedulesController@count');

        // $router->get('scheduleSiswaAktif', 'SchedulesController@aktif');
        // $router->get('scheduleSiswaPending', 'SchedulesController@pending');
        // $router->get('scheduleSiswaRiwayat', 'SchedulesController@riwayat');
        $router->post('scheduleCancel', 'SchedulesController@cancel');

        $router->get('scheduleReceive', 'SchedulesController@receive');
        $router->get('scheduleReceiveCount', 'SchedulesController@receiveCount');
        $router->post('scheduleAccept', 'SchedulesController@accept');

        $router->put('scheduleFinish', 'SchedulesController@finish');
        /**
         * Routes for resource diary
         */
        $router->get('diary', 'DiariesController@all');
        $router->post('diary', 'DiariesController@add');
        $router->put('diary', 'DiariesController@put');
        $router->delete('diary/{id}', 'DiariesController@remove');
        $router->get('diaryPageCount', 'DiariesController@diaryCount');

        $router->get('shareDiary', 'DiariesController@readDiary');
        $router->get('shareDiaryCount', 'DiariesController@readDiaryCount');

        /**
         * Routes for resource user
         */
        $router->get('user/{id}', 'UsersController@get');
        $router->put('user', 'UsersController@put');
        $router->delete('user/{id}', 'UsersController@remove');

        /**
         * Routes for resource notifikasi
         */
        $router->get('notifikasi', 'NotifikasisController@all');
        $router->get('notifikasi/{id}', 'NotifikasisController@get');
        $router->post('notifikasi', 'NotifikasisController@add');
        $router->put('notifikasi/{id}', 'NotifikasisController@put');
        $router->delete('notifikasi/{id}', 'NotifikasisController@remove');
        $router->delete('notifikasi', 'NotifikasisController@removeAll');
        $router->get('notifikasiPageCount', 'NotifikasisController@notifikasiCount');
        
        $router->post('updateRead', 'NotifikasisController@read');
            
        //Favorite Artikels
        $router->post('favorit', 'ArtikelsController@storeFavorite');
        $router->get('favorit', 'ArtikelsController@getMyFavorite');
        $router->get('favoritCount', 'ArtikelsController@getMyFavoriteCount');
        $router->get('favorit/{id}', 'ArtikelsController@removeMyFavorit');
    });
});
/**
 * Routes for resource riwayat
 */
$app->get('riwayat', 'RiwayatsController@all');
$app->get('riwayat/{id}', 'RiwayatsController@get');
$app->post('riwayat', 'RiwayatsController@add');
$app->put('riwayat/{id}', 'RiwayatsController@put');
$app->delete('riwayat/{id}', 'RiwayatsController@remove');
