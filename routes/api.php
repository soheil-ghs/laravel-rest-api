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

Route::post('/register', 'Api\UserController@register');
Route::post('/login', 'Api\UserController@login');

Route::group([
    'middleware' => 'auth:api',
    'namespace' => 'Api',
], function () {
    Route::post('logout', 'UserController@logout');

    Route::group([
        'prefix' => 'product'
    ], function () {
        Route::post('/create', 'ProductController@create');
        Route::post('/all', 'ProductController@index');
        Route::put('/update', 'ProductController@update');
        Route::delete('/delete', 'ProductController@destroy');
    });
});
