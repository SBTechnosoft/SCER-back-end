<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
	echo "Sample222";
    return view('welcome');
});
// Route::get('/controllerDemo', function () {
    // return view('Sample.SampleController');
// });

// Route::controller('collection','SampleController');
Route::resource('Sample2', 'SampleController2');

