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

Route::view('/', 'generator');

Route::POST('/generate', 'GenerateNameController@store');

Route::GET('/generate/random', 'GenerateNameController@random');

Route::GET('/generate/personalized', 'GenerateNameController@index');

Route::GET('/env', function () {
    dump(config('app.name'));
    dump(config('app.env'));
    dump(config('app.debug'));
    dump(config('app.url'));
});
