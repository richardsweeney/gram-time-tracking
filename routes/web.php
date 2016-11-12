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

Auth::routes();

Route::get('/', 'ShiftController@index');
Route::post('/', 'ShiftController@add');
Route::get('/{id}', 'ShiftController@edit')->where('id', '[0-9]+');
Route::put('/{id}', 'ShiftController@update')->where('id', '[0-9]+');
Route::delete('/{id}', 'ShiftController@delete')->where('id', '[0-9]+');

Route::get('/shifts', 'ShiftController@shifts');
Route::post('/export/{id}', 'ShiftController@export')->where('id', '[0-9]+');
