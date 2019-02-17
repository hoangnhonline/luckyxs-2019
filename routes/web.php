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
Route::group(['namespace' => 'Auth', 'prefix' => 'auth'], function () {
    Route::get('/login', ['as' => 'auth.login', 'uses' => 'LoginController@showLoginForm']);
    Route::post('/login', ['as' => 'auth.login.post', 'uses' => 'LoginController@processLogin']);
    Route::get('/logout', ['as' => 'auth.logout', 'middleware' => ['web'], 'uses' => 'LoginController@logout']);
    Route::get('/forgot-password', ['as' => 'auth.forgot-password', 'uses' => 'ForgotPasswordController@forgotPass']);
    Route::post('/forgot-password', ['as' => 'auth.forgot-password.post', 'uses' => 'ForgotPasswordController@processForgotPass']);
});
Route::group(['middleware' => ['auth:web', 'role']], function () {
	Route::get('/test', 'TestController@index')->name('test');
    Route::get('/', ['as' => 'dashboard', 'uses' => 'TestController@messagesList']);
    Route::get('/mau', ['as' => 'mau', 'uses' => 'TestController@mau']);
    Route::get('/messages-list', ['as' => 'messages.list', 'uses' => 'TestController@messagesList']);
    Route::get('{id}/messages-detail', ['as' => 'messages.detail', 'uses' => 'TestController@messagesDetail']);
});

Route::match(['get', 'post'], '/botman', 'BotManController@handle');
Route::get('/botman/tinker', 'BotManController@tinker');
Route::post(
    'telegram', 'TelegramController'
);

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
