<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {

    Route::get('/', function () {
        //return redirect('/login');

        return view('welcome');
    });
    
});

Route::group(['middleware' => 'web'], function () {
    Route::auth();

    Route::get('/dashboard', 'DashboardController@index');
});

Route::group(['middleware' => ['web','auth']], function () {
	Route::get('users/{id}/predelete', 'UsersController@predelete');;
	Route::resource('users', 'UsersController');
});

Route::group(['middleware' => ['web','auth']], function () {
	Route::get('invitationcodes/{id}/predelete', 'InvitationcodesController@predelete');;
	Route::resource('invitationcodes', 'InvitationcodesController');
});

Route::group(['middleware' => ['web','auth']], function () {
	Route::get('profile/{id}', 'ProfileController@show');
	Route::get('profile', 'ProfileController@edit');
	Route::post('profile', 'ProfileController@update');
});
