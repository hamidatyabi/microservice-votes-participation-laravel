<?php

use Illuminate\Http\Request;
use \App\Http\Middleware\Authorization;
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

Route::group(['prefix' => 'v1', 'namespace' => 'Api'], function () {

    //UnAuth...
    Route::group(['middleware' => 'guest:api'], function () {

    });
    Route::group(['middleware' => ['auth']], function () {
        Route::group(['prefix' => 'vote'], function () {
            Route::post('push', 'VoteController@push');
        });
    });
    Route::group(['middleware' => ['auth:api', 'active']], function () {
        
    });
});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
