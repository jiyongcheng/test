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


Route::get('/pets', 'Mypal\PetController@list')->name('pets.list');
Route::get('/pet/{id}', 'Mypal\PetController@detail')->name('pets.detail')->middleware('auth.jwt');
Route::post('/pet', 'Mypal\PetController@create')->name('pets.create')->middleware('auth.jwt');
Route::put('/pet/{id}', 'Mypal\PetController@edit')->name('pets.update')->middleware('auth.jwt');
Route::delete('/pet/{id}', 'Mypal\PetController@delete')->name('pets.delete')->middleware('auth.jwt');


Route::post('/user', 'UserController@signup');
Route::post('/user/signin', 'UserController@signin');
