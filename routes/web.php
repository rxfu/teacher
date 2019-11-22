<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', function () {
	return redirect()->route('home');
});

Route::auth();

Route::middleware('auth')->group(function () {
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

	Route::get('dcxm/list', 'DcxmController@getList');
	Route::get('dcxm/jsyj/{id}', 'DcxmController@getOpinion');
	Route::post('dcxm/jsyj/{id}', 'DcxmController@postOpinion');
	Route::get('dcxm/pdf/{id}', 'DcxmController@getPdf');
	Route::get('dcxm/zmcl/{id}', 'DcxmController@getFile');
	Route::get('dcxm/pslb', 'DcxmController@getPslist');
	Route::get('dcxm/xmps/{id}', 'DcxmController@getXmps');
	Route::post('dcxm/xmps/{id}', 'DcxmController@postXmps');

	Route::get('/home', ['as' => 'home', 'uses' => 'HomeController@index']);
	Route::get('password/change', 'Auth\PasswordController@showChangeForm');
	Route::put('password/change', 'Auth\PasswordController@change');
	Route::resource('tksq', 'TksqController');
});