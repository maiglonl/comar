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

	Route::resource('attributes', 'AttributesController')->only(['store','update','destroy']);

	Route::get('products/find/{id}', 'ProductsController@find')->name('products.find');
	Route::get('products/all', 'ProductsController@all')->name('products.all');
	Route::post('products/image/{id}', 'ProductsController@uploadImage')->name('products.image.upload');
	Route::put('products/image/pull/{id}/{index}', 'ProductsController@pullImage')->name('products.image.pull');
	Route::put('products/image/push/{id}/{index}', 'ProductsController@pushImage')->name('products.image.push');
	Route::delete('products/image/{id}/{index}', 'ProductsController@deleteImage')->name('products.image.delete');
	Route::resource('products', 'ProductsController');

	Route::get('users/find/{id}', 'UsersController@find')->name('users.find');
	Route::get('users/all', 'UsersController@all')->name('users.all');
	Route::resource('users', 'UsersController');

});