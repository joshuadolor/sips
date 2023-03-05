<?php

// use App\Http\Api\Auth\RegisterController;
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

// Route::group([
//     'namespace' => 'Api/A',
//     'middleware' => ['auth:sanctum'],
// ], function () {
//     Route::get()
// });

Route::group([
    'namespace' => 'App\Http\Api\Auth',
    'prefix' => 'auth',
], function () {
    Route::post('login', 'LoginController@login');
    Route::post('register', 'RegisterController@register');

});

// Route::controller(RegisterController::class)->group(function () {
//     Route::prefix('auth')->group(function () {
//         Route::post('register', 'register');
//         Route::post('login', 'login');
//     });
// });

Route::group([
    'middleware' => 'auth:sanctum',
], function () {
    Route::group([
        'namespace' => 'App\Http\Api',
    ], function () {

        Route::group([
            'prefix' => 'account',
        ], function () {
            Route::get('/', 'AccountController@profile');
            Route::post('/', 'AccountController@store');
            Route::post('/change-password', 'AccountController@changePassword');
            Route::post('/reset-password', 'AccountController@resetPassword');
        });

        Route::group([
            'prefix' => 'resource',
        ], function () {
            Route::get('{resource}', 'ResourceController@index');
            Route::post('{resource}', 'ResourceController@store');
            Route::put('{resource}/{id}', 'ResourceController@update');
        });

        Route::group([
            'prefix' => 'product-movements',
        ], function () {
            Route::get('/', 'ProductMovementController@index');
            Route::post('/', 'ProductMovementController@store');
        });

        Route::group([
            'prefix' => 'sales-entry',
        ], function () {
            Route::get('/', 'SalesController@index');
            Route::post('/', 'SalesController@store');
        });

    });
});
