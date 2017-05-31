<?php

/**
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
 */

Route::get('/', function () {
	return redirect('home');
});

/**
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
	Route::auth();

	Route::group(['middleware' => ['auth']], function () {
		Route::resource('home', 'HomeController', ['only' => ['index']]);
		Route::resource('profile', 'ProfileController', ['only' => ['index']]);
		Route::resource('tes', 'TesController', ['only' => ['index', 'edit', 'update', 'show']]);
		Route::resource('set', 'SetController', ['only' => 'show']);

		Route::get('timetable/search', 'TimetableController@search');
		Route::post('timetable/search', 'TimetableController@search');
		Route::resource('timetable', 'TimetableController', ['only' => ['index', 'show']]);

		Route::get('task/timetable', 'TaskController@timetable');
		Route::get('task/export/{year}/{term}/{kcxh}', ['as' => 'task.export', 'uses' => 'TaskController@exportStudents']);
		Route::resource('task', 'TaskController', ['only' => ['index', 'show']]);

		Route::put('score/updateStatus/{kcxh}', 'ScoreController@updateStatus');
		Route::post('score/confirm/{kcxh}', 'ScoreController@confirm');
		Route::put('score/batch-update/{kcxh}', ['as' => 'score.batchUpdate', 'uses' => 'ScoreController@batchUpdate']);
		Route::post('score/import/{kcxh}', ['as' => 'score.import', 'uses' => 'ScoreController@import']);
		Route::resource('score', 'ScoreController', ['only' => ['index', 'show', 'edit', 'update']]);

		Route::get('thesis/search', 'ThesisController@showSearchForm');
		Route::get('thesis/searchThesis', 'ThesisController@search');

		Route::get('/home', ['as' => 'home', 'uses' => 'HomeController@index']);
		Route::get('password/change', 'Auth\PasswordController@showChangeForm');
		Route::put('password/change', 'Auth\PasswordController@change');
	});
});
