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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'app', 'as' => 'app.', 'middleware' => ['auth']], function () {
	Route::get('/home', 'HomeController@appIndex')->name('home');
	
	Route::get('products/find', 'ProductsController@find')->name('products.find');
	Route::get('products/all', 'ProductsController@all')->name('products.all');
	Route::resource('products', 'ProductsController');

	Route::get('users/find', 'UsersController@find')->name('users.find');
	Route::get('users/all', 'UsersController@all')->name('users.all');
	Route::resource('users', 'UsersController');
});