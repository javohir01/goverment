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
    'middleware' => 'api',
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
        Route::get('/{id}', 'Api\CitizenController@show');
        Route::put('/{id}', 'Api\CitizenController@update');
        Route::delete('/{id}', 'Api\CitizenController@destroy');
        Route::post('/passport', 'Api\CitizenController@passport');
    });
    Route::group(['prefix' => 'report'], function () {
        Route::get('report', 'Api\ReportController@report');
        Route::get('districts/{id}', 'Api\ReportController@districts');
        Route::get('socialStatuses', 'Api\ResourceController@socialStatuses');
    });
});
Route::group(['prefix' => 'resources'], function () {
    Route::get('regions', 'Api\ResourceController@regions');
    Route::get('districts', 'Api\ResourceController@districts');
    Route::get('socialStatuses', 'Api\ResourceController@socialStatuses');
});

Route::group(['prefix' => 'applications'], function () {
    Route::get('/', 'Api\ApplicationController@index');
    Route::post('/store', 'Api\ApplicationController@store');
    Route::put('/rejected/{id}', 'Api\ApplicationController@rejected');
    Route::put('/confirmed/{id}', 'Api\ApplicationController@confirmed');
    Route::get('/{id}', 'Api\ApplicationController@show');
    Route::delete('/{id}', 'Api\ApplicationController@destroy');
    Route::post('/send-sms', 'Api\SmsController@sendSms');
    Route::post('/confirm-sms', 'Api\SmsController@confirmSms');
    Route::post('/get-number', 'Api\ApplicationController@getNumber');
    Route::post('/check', 'Api\ApplicationController@check');

});

