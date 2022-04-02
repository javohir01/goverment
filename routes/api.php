<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group([
    'prefix' => 'auth'
], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::apiResource('users', 'UsersController');

});
Route::middleware(['auth:api'])->group(function () {
    Route::group(['prefix' => 'citizens'], function () {
        Route::get('/', 'Api\CitizenController@index');
        Route::post('/store', 'Api\CitizenController@store');
        Route::get('/show/{id}', 'Api\CitizenController@show');
        Route::put('/update/{id}', 'Api\CitizenController@update');
    });
});

Route::group(['prefix' => 'resources'], function () {
    Route::get('regions', 'Api\ResourceController@regions');
    Route::get('districts', 'Api\ResourceController@districts');
});
