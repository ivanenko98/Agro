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


    /** INVESTORS */

    /** show investors list */
    Route::get('/investor', 'InvestorController@index');
    /** show blocks of investor */
    Route::get('/investor/{investor}', 'InvestorController@show');
    /** save new investor */
    Route::post('/investor', 'InvestorController@store');
    /** update investor */
    Route::put('/investor/{investor}', 'InvestorController@update');
    /** delete investor */
    Route::delete('/investor/{elevator}', 'InvestorController@destroy');


    /** SHIPMENTS */

    /** show shipments list */
    Route::get('/shipment', 'ShipmentController@index');
    /** show blocks of shipment */
    Route::get('/shipment/{shipment}', 'ShipmentController@show');
    /** save new shipment */
    Route::post('/shipment', 'ShipmentController@store');
    /** update shipment */
    Route::put('/shipment/{shipment}', 'ShipmentController@update');
    /** delete shipment */
    Route::delete('/shipment/{elevator}', 'ShipmentController@destroy');


    /** RENTS */

    /** show rents list */
    Route::get('/rent', 'RentController@index');
    /** show blocks of rent */
    Route::get('/rent/{rent}', 'RentController@show');
    /** save new rent */
    Route::post('/rent', 'RentController@store');
    /** update rent */
    Route::put('/rent/{rent}', 'RentController@update');
    /** delete rent */
    Route::delete('/rent/{elevator}', 'RentController@destroy');

});