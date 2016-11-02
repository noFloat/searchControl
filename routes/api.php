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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

// Route::resource('/search', 'Search\SearchController', [
// 	    'names' => [
// 	        'index'  => 'search.index',
// 	        'edit'   => 'search.edit',
// 	        'show'   => 'search.show',
// 	        'update' => 'search.update',
// 	        'create' => 'search.create',
// 	        'store'  => 'search.store',
// 	    ],
// ]);