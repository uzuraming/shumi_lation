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

Auth::routes(['verify' => true, 'login'=>false, 'register'=>false]);




// 会員登録
Route::post('/signup', 'Auth\RegisterController@register')->name('register');
// ログイン
Route::post('/login', 'Auth\LoginController@login')->name('login');

// ログアウト
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');//ユーザー認証を作成した時に自動的に作成されるルートを下記のように変更



// ログインユーザー
Route::get('/user', 
    function(){
        return Auth::user();
    }
)->name('user');


Route::get('/timelines', 'TimelinesController@index');


Route::get('/works', 'WorksController@index');
Route::get('/works/search', 'WorksController@search_word');
Route::get('/works/{id}', 'WorksController@show');


Route::get('/works/{id}/comments', 'WorksController@comments');
Route::post('/works/{id}/comments', 'WorksController@comment_store');
Route::delete('/works/{id}/comments/{commentId}/', 'WorksController@comment_destroy');


Route::resource('/users', 'UsersController',  ['only' => ['show']]);








Route::group(['middleware' => ['auth','verified']], function () {

    Route::get('/chats', 'ChatController@index');
    Route::get('/chats/{id}', 'ChatController@show');
    Route::post('/chats/{id}', 'ChatController@send_message');

    Route::post('/users/{id}/follow', 'TimelinesController@index');

    Route::post('/users/{id}/follow', 'UserFollowController@store');
    Route::delete('/users/{id}/unfollow', 'UserFollowController@destroy');


    Route::post('/favorites/{id}', 'FavoriteController@store');
    Route::delete('/favorites/{id}', 'FavoriteController@destroy');

    Route::get('/favorites', 'FavoriteController@index');

    // ブックマーク
    Route::post('/bookmarks/{id}', 'BookmarksController@store');
    Route::delete('/bookmarks/{id}', 'BookmarksController@destroy');

    Route::get('/bookmarks', 'BookmarksController@index');

    // 


    Route::put('/users/{id}/', 'UsersController@update');

    Route::post('/users/{id}/send_request', 'ChatRequestController@store');
    Route::delete('/users/{id}/send_request', 'ChatRequestController@remove_request');

    Route::get('/requests', 'ChatRequestController@index');
    Route::delete('/requests/{id}', 'ChatRequestController@refuse_request');
    Route::put('/requests/{id}', 'ChatRequestController@accept_request');


    Route::put('/works/{id}', 'WorksController@update');
    Route::post('/works', 'WorksController@store');
    Route::delete('/works/{id}', 'WorksController@destroy');

    // タイムラインの各機能
    Route::post('/timelines', 'TimelinesController@store');


});