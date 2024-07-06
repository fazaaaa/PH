<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JenisBantuanController;
use App\Http\Controllers\KlasifikasiController;
use App\Http\Controllers\KondisiController;
use App\Http\Controllers\PekerjaanController;
use App\Http\Controllers\PendidikanController;
use App\Http\Controllers\PendudukController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RwController;
use App\Http\Controllers\RwMenuController;
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
// Route::get('/', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard.index');
Route::get('/income-decision', [KlasifikasiController::class, 'predict']);
Route::get('/', function () {
    return view('welcome');
})->name('index');

// LURAH

Route::get('/caripenduduk', [PendudukController::class, 'cari'])->name('penduduk.cari');
Route::get('/cetakpenduduk', [PendudukController::class, 'cetakpenduduk'])->name('penduduk.cetakpenduduk');
Route::get('/cetakklasifikasi', [PendudukController::class, 'cetakklasifikasi'])->name('penduduk.cetakklasifikasi');



// Route::get('/', function () {
//     return view('auth.login');
// });


// Route::middleware(['auth'])->group(function () {
Route::middleware(['auth','checkRole:kph,rw'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Penduduk
    Route::get('/penduduk', [PendudukController::class, 'index'])->name('penduduk.index');
    Route::get('/addpenduduk', [PendudukController::class, 'create'])->name('penduduk.add');
    Route::post('/storependuduk', [PendudukController::class, 'store'])->name('penduduk.store');
    // Route::get('/cetak-penduduk', [PendudukController::class, 'cetakPenduduk']);


    // Pekerjaan
    Route::get('/pekerjaan', [PekerjaanController::class, 'index'])->name('pekerjaan.index');
    Route::get('/addpekerjaan', [PekerjaanController::class, 'create'])->name('pekerjaan.add');
    Route::post('/storepekerjaan', [PekerjaanController::class, 'store'])->name('pekerjaan.store');
    

    // Kondisi
    Route::get('/kondisi', [KondisiController::class, 'index'])->name('kondisi.index');
    Route::get('/addkondisi', [KondisiController::class, 'create'])->name('kondisi.add');
    Route::post('/storekondisi', [KondisiController::class, 'store'])->name('kondisi.store');

    
    // Klasifikasi
    Route::get('/klasifikasi', [KlasifikasiController::class, 'predict'])->name('klasifikasi.index');
    
});

Route::middleware(['auth','checkRole:kph'])->group(function () {
    
    Route::get('/editpenduduk/{id}', [PendudukController::class, 'edit'])->name('penduduk.edit');
    Route::put('/updatependuduk{id}', [PendudukController::class, 'update'])->name('penduduk.update');
    Route::delete('/delpenduduk/{id}', [PendudukController::class, 'destroy'])->name('penduduk.delete');

    Route::get('/editpekerjaan/{id}', [PekerjaanController::class, 'edit'])->name('pekerjaan.edit');
    Route::put('/updatepekerjaan/{id}', [PekerjaanController::class, 'update'])->name('pekerjaan.update');
    Route::delete('/delpekerjaan/{id}', [PekerjaanController::class, 'destroy'])->name('pekerjaan.delete');

    Route::get('/editkondisi/{id}', [KondisiController::class, 'edit'])->name('kondisi.edit');
    Route::put('/updatekondisi/{id}', [KondisiController::class, 'update'])->name('kondisi.update');
    Route::delete('/delkondisi/{id}', [KondisiController::class, 'destroy'])->name('kondisi.delete');


    Route::get('/addklasifikasi', [KlasifikasiController::class, 'create'])->name('klasifikasi.add');
    Route::post('/storeklasifikasi', [KlasifikasiController::class, 'store'])->name('klasifikasi.store');
    Route::get('/editklasifikasi/{id}', [KlasifikasiController::class, 'edit'])->name('klasifikasi.edit');
    Route::put('/updateklasifikasi{id}', [KlasifikasiController::class, 'update'])->name('klasifikasi.update');
    Route::delete('/delklasifikasi/{id}', [KlasifikasiController::class, 'destroy'])->name('klasifikasi.delete');

    // Jenisbantuan
    Route::get('/jenisbantuan', [JenisBantuanController::class, 'index'])->name('jenisbantuan.index');
    Route::get('/addjenisbantuan', [JenisBantuanController::class, 'create'])->name('jenisbantuan.add');
    Route::post('/storejenisbantuan', [JenisBantuanController::class, 'store'])->name('jenisbantuan.store');
    Route::delete('/deljenisbantuan/{id}', [JenisBantuanController::class, 'destroy'])->name('jenisbantuan.delete');

    // RWMENU
    Route::get('/rwmenu', [RwMenuController::class, 'index'])->name('rwmenu.index');
    Route::get('/addrwmenu', [RwMenuController::class, 'create'])->name('rwmenu.add');
    Route::post('/storerwmenu', [RwMenuController::class, 'store'])->name('rwmenu.store');
    Route::get('/editrwmenu/{id}', [RwMenuController::class, 'edit'])->name('rwmenu.edit');
    Route::put('/updaterwmenu{id}', [RwMenuController::class, 'update'])->name('rwmenu.update');
    Route::delete('/delrwmenu/{id}', [RwMenuController::class, 'destroy'])->name('rwmenu.delete');
});
// });

require __DIR__ . '/auth.php';
