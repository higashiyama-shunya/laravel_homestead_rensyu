<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//後ろに->middleware('auth:sanctum');とついているのはそのAPIに行く前にauth:sanctumというミドルウェアを一度介してから行くということ。
//今回だとログイン認証が間に挟まれる。ログインしてトークンを送って要れば問題なく入れるが、ログインをしていなくてトークンが送っていない場合エラーになる。
Route::post('/createChatRoom', 'App\Http\Controllers\ChatController@createChatRoom')->middleware('auth:sanctum');
Route::post('/getChatRoom', 'App\Http\Controllers\ChatController@getChatRoom')->middleware('auth:sanctum');
Route::post('/joinChatRoom', 'App\Http\Controllers\ChatController@joinChatRoom')->middleware('auth:sanctum');
Route::post('/exitChatRoom', 'App\Http\Controllers\ChatController@exitChatRoom')->middleware('auth:sanctum');
Route::post('/deleteChatRoom', 'App\Http\Controllers\ChatController@deleteChatRoom')->middleware('auth:sanctum');
Route::post('/getChat', 'App\Http\Controllers\ChatController@getChat')->middleware('auth:sanctum');
Route::post('/addChat', 'App\Http\Controllers\ChatController@addChat')->middleware('auth:sanctum');
Route::post('/deleteChat', 'App\Http\Controllers\ChatController@deleteChat')->middleware('auth:sanctum');
Route::get('/getAllChatRoom', 'App\Http\Controllers\ChatController@getAllChatRoom');




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', 'App\Http\Controllers\Api\RegisterController@register');
Route::post('/login', 'App\Http\Controllers\Api\LoginController@login');
