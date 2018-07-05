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

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('register', 'UsersController@create')->name('register');
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

// Default Routes
Route::get('/', 'ProductsController@shop')->name('index');
Route::get('home', 'HomeController@appIndex')->name('home');
Route::get('status/all', 'StatusController@all')->name('status.all');
Route::get('categories/all', 'CategoriesController@all')->name('categories.all');

Route::get('products/desc/{id}', 'ProductsController@desc')->name('products.desc');
Route::get('products/shop', 'ProductsController@shop')->name('products.shop');
Route::get('products/find/{id}', 'ProductsController@find')->name('products.find');
Route::get('products/all', 'ProductsController@all')->name('products.all');

Route::post('users', 'UsersController@store')->name('users.store');

// Admin Routes
Route::group(['middleware' => ['auth', 'can:access-admin']], function () {
	// Attributes
	Route::get('attributes/create/{product_id}', 'AttributesController@create')->name('attributes.create');
	Route::get('attributes/edit/{id}', 'AttributesController@edit')->name('attributes.edit');

	// Categories
	Route::get('categories/edit/{id}', 'CategoriesController@edit')->name('categories.edit');

	// Products
	Route::put('products/image/pull/{id}/{index}', 'ProductsController@pullImage')->name('products.image.pull');
	Route::put('products/image/push/{id}/{index}', 'ProductsController@pushImage')->name('products.image.push');
	Route::post('products/image/{id}', 'ProductsController@uploadImage')->name('products.image.upload');
	Route::delete('products/image/{id}/{index}', 'ProductsController@deleteImage')->name('products.image.delete');

	// Orders
	Route::get('orders/current', 'OrdersController@current')->name('orders.current');

	// Resources
	Route::resource('attributes', 'AttributesController')->except(['index','show','create','edit']);
	Route::resource('categories', 'CategoriesController')->only(['create','store','update']);
	Route::resource('products', 'ProductsController');
	Route::resource('Orders', 'OrdersController');
	Route::resource('Items', 'ItemsController');
});

// Authenticated Routes
Route::group(['middleware' => ['auth']], function () {
	Route::get('users/find/{id}', 'UsersController@find')->name('users.find');
	Route::get('users/all', 'UsersController@all')->name('users.all');

	Route::resource('orders', 'OrdersController');
	Route::resource('users', 'UsersController')->except('store');
});