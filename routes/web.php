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

Route::get('/', 'TimelinesController@index');

// ユーザ登録
Route::get('signup', 'Auth\RegisterController@showRegistrationForm')->name('signup.get');
Route::post('signup', 'Auth\RegisterController@register')->name('signup.post');

// 認証
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login')->name('login.post');
Route::get('logout', 'Auth\LoginController@logout')->name('logout.get');

// 認証済みのユーザーのみが来れる場所
Route::group(['middleware' => ['auth']], function () {

    // ユーザーフォローのルーティング
    Route::group(['prefix' => 'users/{id}'], function () {
        Route::post('follow', 'UserFollowController@store')->name('user.follow');
        Route::delete('unfollow', 'UserFollowController@destroy')->name('user.unfollow');
        Route::get('followings', 'UsersController@followings')->name('users.followings');
        Route::get('followers', 'UsersController@followers')->name('users.followers');
        Route::get('works', 'UsersController@works')->name('users.works');
        Route::get('works/{workId}', 'UsersController@worksShow')->name('users.works_show');
        Route::post('works/{workId}/comments', 'UsersController@commentStore')->name('users.comment_store');
        Route::delete('works/{workId}/comments/{commentId}', 'UsersController@commentDestroy')->name('users.comment_destroy');

        Route::post('send_request', 'ChatRequestController@store')->name('users.send_request');
    });
    // ユーザーの各機能
    Route::resource('users', 'UsersController',  ['only' => ['show', 'edit', 'update', 'destroy']]);
    // タイムラインの各機能
    Route::resource('timeline', 'TimelinesController', ['only' => ['create', 'store', 'destroy']]);

    // 作品の各機能
    Route::resource('works', 'WorksController', ['only' => ['create', 'store', 'edit','update','destroy']]);

    // お気に入りするルーティング
    Route::post('follow/{id}', 'FavoriteController@store')->name('favorite.store');;
    Route::delete('unfollow/{id}', 'FavoriteController@destroy')->name('favorite.delete');


    // chat requestにかんするルーティング
    Route::get('chat_requests', 'ChatRequestController@index')->name('chat_requests.index');
    Route::get('chat_requests/{id}', 'ChatRequestController@show')->name('chat_requests.show');
    Route::put('chat_requests/{id}', 'ChatRequestController@accept_request')->name('chat_requests.accept_request');
    Route::delete('chat_requests/{id}', 'ChatRequestController@destroy')->name('chat_requests.destroy');






});