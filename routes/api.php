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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('voyages', 'App\Http\Controllers\Api\VoyageController');

Route::post('vessels/{vessel_id}/vessel_opex', 'App\Http\Controllers\Api\VesselController@addVesselOpex');
Route::get('vessels/{vessel_id}/financial_report', 'App\Http\Controllers\Api\VesselController@financial_report');


