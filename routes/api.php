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


Route::post('/register', 'Auth\RegisterController@register');
Route::post('/login', 'Auth\LoginController@login');

Route::group(['middleware' => 'auth:api'], function() {

    /** FARMERS */

    /** show farmers list */
    Route::get('/farmer', 'FarmerController@index');
    /** show blocks of farmer */
    Route::get('/farmer/{farmer}', 'FarmerController@show');
    /** save new farmer */
    Route::post('/farmer', 'FarmerController@store');
    /** update farmer */
    Route::put('/farmer/{farmer}', 'FarmerController@update');
    /** delete farmer */
    Route::delete('/farmer/{farmer}', 'FarmerController@destroy');


    /** ELEVATORS */

    /** show elevators list */
    Route::get('/elevator', 'ElevatorController@index');
    /** show blocks of elevator */
    Route::get('/elevator/{elevator}', 'ElevatorController@show');
    /** save new elevator */
    Route::post('/elevator', 'ElevatorController@store');
    /** update elevator */
    Route::put('/elevator/{elevator}', 'ElevatorController@update');
    /** delete elevator */
    Route::delete('/elevator/{elevator}', 'ElevatorController@destroy');

    /** FACTORIES */

    /** show factories list */
    Route::get('/factory', 'FactoryController@index');
    /** show blocks of factory */
    Route::get('/factory/{factory}', 'FactoryController@show');
    /** save new factory */
    Route::post('/factory', 'FactoryController@store');
    /** update factory */
    Route::put('/factory/{factory}', 'FactoryController@update');
    /** delete factory */
    Route::delete('/factory/{factory}', 'FactoryController@destroy');

    /** TOOLS */

    /** show factories list */
    Route::get('/tool', 'ToolController@index');
    /** show blocks of factory */
    Route::get('/tool/{tool}', 'ToolController@show');
    /** save new factory */
    Route::post('/tool', 'ToolController@store');
    /** update factory */
    Route::put('/tool/{tool}', 'ToolController@update');
    /** delete factory */
    Route::delete('/tool/{tool}', 'ToolController@destroy');

});