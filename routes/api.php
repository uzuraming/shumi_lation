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



// 会員登録
Route::post('/signup', 'Auth\RegisterController@register')->name('register');
// ログイン
Route::post('/login', 'Auth\LoginController@login')->name('login');

// ログアウト
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

// ログインユーザー
Route::get('/user', 
    function(){
        return Auth::user();
    }
)->name('user');


Route::get('/timelines', 'TimelinesController@index');
// タイムラインの各機能
Route::post('/timelines', 'TimelinesController@store');

Route::get('/works', 'WorksController@index');
Route::get('/works/{id}', 'WorksController@show');
Route::put('/works/{id}', 'WorksController@update');
Route::post('/works', 'WorksController@store');
Route::delete('/works/{id}', 'WorksController@destroy');

Route::get('/works/{id}/comments', 'WorksController@comments');
Route::post('/works/{id}/comments', 'WorksController@comment_store');
Route::delete('/works/{id}/comments/{commentId}/', 'WorksController@comment_destroy');


Route::resource('/users', 'UsersController',  ['only' => ['show']]);
Route::put('/users/{id}/', 'UsersController@update');

Route::post('/users/{id}/send_request', 'ChatRequestController@store');
Route::delete('/users/{id}/send_request', 'ChatRequestController@remove_request');

Route::get('/requests', 'ChatRequestController@index');
Route::delete('/requests/{id}', 'ChatRequestController@refuse_request');
Route::put('/requests/{id}', 'ChatRequestController@accept_request');






Route::post('/users/{id}/follow', 'TimelinesController@index');

Route::post('/users/{id}/follow', 'UserFollowController@store');
Route::delete('/users/{id}/unfollow', 'UserFollowController@destroy');


Route::post('/favorites/{id}', 'FavoriteController@store');
Route::delete('/favorites/{id}', 'FavoriteController@destroy');

Route::get('/favorites', 'FavoriteController@index');


Route::get('/chats', 'ChatController@index');
Route::get('/chats/{id}', 'ChatController@show');
Route::post('/chats/{id}', 'ChatController@send_message');
