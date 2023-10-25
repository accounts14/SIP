<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/admin/login/{role}', 'AuthController@login')->where('role', 'superadmin|admin');

Route::middleware(['auth:api'])->prefix('adm')->group(function () {
    // Web 
    Route::get('products', 'WebProductController@index');
    // location
    // id only : /api/locations/{type}?id=1
    // name only : /api/locations/{type}?name=YourName
    // id & name : /api/locations/{type}?id=1&name=YourName
    Route::get('locations/{type}', 'LocationsController@getByType')
    ->where('type', 'province|city|district|subdistrict')
    ->name('locations.get');
});
