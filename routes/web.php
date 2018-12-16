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

	// Tasks
	Route::get('tasks/workflow', 'TasksController@workflow')->name('tasks.workflow');
	Route::post('tasks/finish/{id}', 'TasksController@finishTask')->name('tasks.finish');

	// Stages
	Route::get('stages/all_with_tasks', 'StagesController@allWithTasks')->name('stages.all_with_tasks');

	// Bills
	Route::get('bills/all_open_credit', 'BillsController@allOpenCredit')->name('bills.all_open_credit');
	Route::get('bills/all_open_debit', 'BillsController@allOpenDebit')->name('bills.all_open_debit');
	Route::post('bills/finish/{id}', 'BillsController@finishBill')->name('bills.finish');

	// Products
	Route::put('products/image/pull/{id}/{index}', 'ProductsController@pullImage')->name('products.image.pull');
	Route::put('products/image/push/{id}/{index}', 'ProductsController@pushImage')->name('products.image.push');
	Route::post('products/image/{id}', 'ProductsController@uploadImage')->name('products.image.upload');
	Route::delete('products/image/{id}/{index}', 'ProductsController@deleteImage')->name('products.image.delete');

	// Resources
	Route::resource('attributes', 'AttributesController')->except(['index','show','create','edit']);
	Route::resource('categories', 'CategoriesController')->only(['create','store','update']);
	Route::resource('products', 'ProductsController');
});

// Authenticated Routes
Route::group(['middleware' => ['auth']], function () {
	Route::get('users/find/{id}', 'UsersController@find')->name('users.find');
	Route::get('users/all', 'UsersController@all')->name('users.all');
	Route::get('users/network/{id?}', 'UsersController@network')->name('users.network');
	Route::get('users/position/{id?}', 'UsersController@getNetworkPosition')->name('users.position');

	// Orders
	Route::get('orders/list', 'OrdersController@list')->name('orders.list');
	Route::get('orders/home/{id}', 'OrdersController@home')->name('orders.home');
	Route::get('orders/cart', 'OrdersController@cart')->name('orders.cart');
	Route::get('orders/current', 'OrdersController@current')->name('orders.current');
	Route::get('orders/delivery', 'OrdersController@delivery')->name('orders.delivery');
	Route::get('orders/payment', 'OrdersController@payment')->name('orders.payment');
	Route::get('orders/payment/billet', 'OrdersController@billet')->name('orders.payment.billet');
	Route::get('orders/payment/card', 'OrdersController@card')->name('orders.payment.card');
	Route::get('orders/checkout', 'OrdersController@checkout')->name('orders.checkout');
	Route::get('orders/checkout/success/{id}', 'OrdersController@success')->name('orders.checkout.success');
	Route::get('orders/card/create', 'OrdersController@createCard')->name('orders.card.create');
	Route::get('orders/find/{id}', 'OrdersController@find')->name('orders.find');
	Route::get('orders/form/address', 'OrdersController@formAddress')->name('orders.form.address');
	Route::get('orders/delivery/cost', 'OrdersController@calcDeliveryCost')->name('orders.delivery.cost');
	Route::put('orders/item/method', 'OrdersController@changeItemMethod')->name('orders.item.method.change');
	Route::put('orders/item/installment', 'OrdersController@changeItemInstallment')->name('orders.item.installment.change');
	Route::post('orders/checkout', 'OrdersController@postCheckout')->name('orders.checkout.post');
	Route::post('orders/item/{product_id}', 'OrdersController@addItem')->name('orders.item.add');
	Route::post('orders/address', 'OrdersController@storeAddress')->name('orders.address.store');
	Route::post('orders/payment/select_card', 'OrdersController@selectCard')->name('orders.payment.select_card');

	// Items
	Route::post('items/store', 'ItemsController@store')->name('items.store');
	Route::post('items/increase/{id}', 'ItemsController@increase')->name('items.increase');
	Route::post('items/decrease/{id}', 'ItemsController@decrease')->name('items.decrease');
	Route::delete('items/destroy/{id}', 'ItemsController@destroy')->name('items.destroy');

	// Cards
	Route::get('cards/all', 'CardsController@all')->name('cards.all');
	Route::get('cards/edit/{id}', 'CardsController@edit')->name('cards.edit');

	// Resources
	Route::resource('cards', 'CardsController')->only(['create','store','update']);
	Route::resource('orders', 'OrdersController');
	Route::resource('users', 'UsersController')->except('store');
});