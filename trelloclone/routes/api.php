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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

//Route::get('/column', 'App\Http\Controllers\ColumnController@index');
//Route::post('/column/store', 'App\Http\Controllers\ColumnController@store');
//Route::put('/column/{column}', 'App\Http\Controllers\ColumnController@update');
//Route::delete('/column/{column}', 'App\Http\Controllers\ColumnController@destroy');
//
//Route::get('/column/{column_id}/tasks', 'App\Http\Controllers\TaskController@index');
//Route::post('tasks/store', 'App\Http\Controllers\TaskController@store');
//Route::delete('tasks/{id}', 'App\Http\Controllers\TaskController@destroy');
//Route::put('tasks/{id}', 'App\Http\Controllers\TaskController@update');

Route::resource('/column', 'App\Http\Controllers\ColumnController');
Route::resource('/task', 'App\Http\Controllers\TaskController');
Route::get('/column/{column}/tasks', 'App\Http\Controllers\ColumnController@tasks');





