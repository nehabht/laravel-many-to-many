<?php

use Illuminate\Support\Facades\Route;


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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::middleware('auth')->prefix('admin')->namespace('Admin')->name('admin.')->group(function (){
    //admin Dashboard
    Route::get('/', 'HomeController@index')->name('dashboard'); //admin Dashboard
    //admin posts
    Route::resource('posts', 'PostController')->parameters([
        'posts' => 'post:slug'
    ]);
    //categories
    Route::resource('categories', 'CategoryController')->parameters([
        'categories' => 'category:slug'
    ])->except(['show', 'create', 'edit']);


});

 

//ultima rotta
Route::get("{any?}", function () {
    return view("guest.home");
})->where("any", ".*");

