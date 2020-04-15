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

Route::get('myadmin/login', ['as' => 'admin.login', 'uses' => 'LoginController@index']);
Route::post('myadmin/doLogin', ['as' => 'admin.dologin', 'uses' => 'LoginController@doLogin']);   //执行登录
Route::get('myadmin/captcha/flat', ['as' => 'admin.captcha.flat', 'uses' => 'LoginController@getCaptcha']);
Route::get('myadmin/other', 'LoginController@other')->name('admin.other');   //测试

Route::group(['middleware' => 'auth.admin:admin'], function () {
	Route::post('clearcache', ['as' => 'admin.clear.cache', 'uses' => 'LoginController@clearCache']);
	Route::post('logout', ['as' => 'admin.logout', 'uses' => 'LoginController@logout']);   //执行登出
	Route::get('dashborad', ['as' => 'admin.main.index', 'uses' => 'MainController@index']);
	Route::get('main/index', ['as' => 'admin.main.home', 'uses' => 'MainController@home']);

	//权限管理
	Route::get('menu/index', ['as' => 'admin.menu.index', 'uses' => 'MenuController@index']);
	Route::get('menu/editmenu', ['as' => 'admin.menu.editmenu', 'uses' => 'MenuController@editmenu']);
	Route::post('menu/savemenu', ['as' => 'admin.menu.savemenu', 'uses' => 'MenuController@savemenu']);
	Route::post('menu/deletemenu', ['as' => 'admin.menu.deletemenu', 'uses' => 'MenuController@deletemenu']);
	Route::get('group/index', ['as' => 'admin.role.index', 'uses' => 'GroupController@index']);
	Route::get('group/grouplist', ['as' => 'admin.role.grouplist', 'uses' => 'GroupController@grouplist']);
	Route::get('group/editgroup', ['as' => 'admin.role.editgroup', 'uses' => 'GroupController@editgroup']);
	Route::get('group/getMenuTreeData', ['as' => 'admin.role.getMenuTreeData', 'uses' => 'GroupController@getMenuTreeData']);
	Route::post('group/savegroup', ['as' => 'admin.role.savegroup', 'uses' => 'GroupController@savegroup']);
	Route::post('group/deletegroup', ['as' => 'admin.role.deletegroup', 'uses' => 'GroupController@deletegroup']);
	Route::get('group/users', ['as' => 'admin.role.users', 'uses' => 'GroupController@users']);
	Route::get('group/userlist', ['as' => 'admin.role.userlist', 'uses' => 'GroupController@userlist']);
	Route::get('group/edituser', ['as' => 'admin.role.edituser', 'uses' => 'GroupController@edituser']);
	Route::post('group/saveuser', ['as' => 'admin.role.saveuser', 'uses' => 'GroupController@saveuser']);
	Route::post('group/deleteuser', ['as' => 'admin.role.deleteuser', 'uses' => 'GroupController@deleteuser']);

	//系统管理
	Route::get('system/changeinfo', ['as' => 'admin.system.changeinfo', 'uses' => 'SystemController@changeinfo']);
	Route::post('system/saveinfo', ['as' => 'admin.system.saveinfo', 'uses' => 'SystemController@saveinfo']);
	Route::get('system/logs', ['as' => 'admin.system.logs', 'uses' => 'SystemController@logs']);
	Route::get('system/logslist', ['as' => 'admin.system.logslist', 'uses' => 'SystemController@logslist']);
});

