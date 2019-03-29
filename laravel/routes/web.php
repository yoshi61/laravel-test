<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/today-API', 'ApisController@todayAPI');
Route::get('/search-API', 'ApisController@searchAPI');
Route::get('/add-remind-API', 'ApisController@addRemindAPI');
