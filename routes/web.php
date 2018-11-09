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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('ajax-pagination', array('as' => 'ajax.pagination', 'uses' => 'HomeController@ajaxPagination'));
Route::get('/', function () {
    $products = App\Product::all();
    return view('welcome', compact('products'));
});
Route::post('charge', 'PurchasesController@store');

Route::get('subscribe', 'SubscribeController@index');

Route::post('subscribe', 'SubscribeController@store');
Route::delete('subscribe', 'SubscribeController@destroy');

Route::post('stripe/webhooks', 'WebhookController@handle');

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::view('/da', 'da');
Route::post('allposts', 'PostController@allPosts')->name('allposts');