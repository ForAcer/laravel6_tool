<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['middleware' => 'auth.admin:api'], function () {
	Route::prefix('v1')->namespace('V1')->group(function () {
		Route::post('login/dologin', ['as' => 'v1.login.dologin', 'uses' => 'LoginController@doLogin']);
	});

//	Route::prefix('v2')->namespace('V2')->group(function () {
//		Route::get('user', 'LoginController@doLogin');
//	});
});
