<?php

use App\Http\Controllers\PenerimaController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Auth::routes();

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

// Route::group(['prefix' =>'/'], function () {
//     Route::get('/', [App\Http\Controllers\FrontendController::class])->name('frontend.index');
// });

Route::group(['prefix' => '/admin'], function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('/penban', \App\Http\Controllers\PenerimaController::class);
    Route::resource('/konmah', \App\Http\Controllers\KondisiRumahController::class);


});
