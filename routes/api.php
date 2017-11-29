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


    /** TSP */

    /** show optimal paths */
    Route::post('/tsp', 'TspController@index');


    /** REGIONS */

    /** show regions list */
    Route::get('/region', 'RegionController@index');
    /** show blocks of region */
    Route::get('/region/{region}', 'RegionController@show');
    /** save new region */
    Route::post('/region', 'RegionController@store');
    /** update region */
    Route::put('/region/{region}', 'RegionController@update');
    /** delete region */
    Route::delete('/region/{region}', 'RegionController@destroy');


    /** FIELDS */

    /** show fields list */
    Route::get('/field', 'FieldController@index');
    /** show blocks of field */
    Route::get('/field/{field}', 'FieldController@show');
    /** save new field */
    Route::post('/field', 'FieldController@store');
    /** update field */
    Route::put('/field/{field}', 'FieldController@update');
    /** delete field */
    Route::delete('/field/{field}', 'FieldController@destroy');


    /** OPFE */

    /** show optimal place for elevators */
    Route::get('/opfe', 'OPFEController@index');
    Route::post('/inarea', 'OPFEController@inArea');
});