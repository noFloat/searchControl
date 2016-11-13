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
Route::resource('search', 'Search\SearchController');
Route::any('movies', 'Search\SearchController@getNewMovies');
Route::any('results/{goal}/{cut}', 'Search\SearchController@show_goals')->where('cut', '[0-9]+');