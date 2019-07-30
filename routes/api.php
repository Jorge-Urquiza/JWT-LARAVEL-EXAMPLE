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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1'], function(){
    Route::post('/auth/login', 'TokensController@login');
    Route::post('/auth/refresh', 'TokensController@refreshToken');
    Route::get('/auth/logout', 'TokensController@logout');
    Route::get('/lista', 'TokensController@getUsers');
});


/*
 *
 * Route::group(['middleware'=> ['jwt.auth'], 'prefix' => 'v1'], function(){
    Route::post('/auth/refresh', 'TokensController@refreshToken');
    Route::get('/auth/expire', 'TokensController@expireToken'); // same as logout

});

 */