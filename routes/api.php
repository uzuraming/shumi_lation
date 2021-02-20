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

Route::resource('/users', 'UsersController',  ['only' => ['show']]);