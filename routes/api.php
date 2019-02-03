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


Route::get('/services', 'SpalopiaApi@getServices');
Route::get('/schedules', 'SpalopiaApi@getAllSchedules');
Route::get('/getSchedulesByDay', 'SpalopiaApi@getSchedulesByDay');
Route::get('/sendReservation', 'SpalopiaApi@sendReservation');
