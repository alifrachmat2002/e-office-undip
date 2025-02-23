<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RapatController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome', [
        'title' => 'Dashboard',
    ]);
})
    ->middleware('auth')
    ->name('home');

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth');

Route::patch('/rapat/{rapat}/presensi/peserta/{peserta}', [RapatController::class, 'updatePresensiPeserta'])->middleware('auth');

Route::get('/rapat', [RapatController::class, 'index'])->name('rapat.index');
Route::post('/rapat/store', [RapatController::class, 'store'])->name('rapat.store');
Route::patch('/rapat/update/{id}', [RapatController::class, 'update'])->name('rapat.update');
Route::delete('/rapat/destroy/{id}', [RapatController::class, 'destroy'])->name('rapat.destroy');