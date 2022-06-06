<?php

use App\Http\Controllers\BerasKelolaController;
use App\Http\Controllers\ModalController;
use App\Http\Controllers\ModalDatangController;
use App\Http\Controllers\ModalKelolaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('/create', [ModalController::class, 'createModal']);
Route::get('/all', [ModalController::class, 'getAllModal']);
Route::get('/detail/{id}', [ModalController::class, 'getDetailModal']);
Route::patch('/update/{id}', [ModalController::class, 'updateModal']);
Route::delete('/delete/{id}', [ModalController::class, 'deleteModal']);
Route::get('/select/{idCategory}', [ModalController::class, 'getModalSelect']);

Route::prefix('modal_datang')->group(function () {
    Route::post('/create', [ModalDatangController::class, 'createModalDatang']);
    Route::get('/all/{idModal}', [ModalDatangController::class, 'getModalDatang']);
    Route::patch('/update/{id}', [ModalDatangController::class, 'updateModalDatang']);
});
Route::prefix('beras_kelola')->group(function () {
    Route::post('/create', [BerasKelolaController::class, 'createBerasKelola']);
    Route::get('/all/{idModal}', [BerasKelolaController::class, 'getBerasKelola']);
    Route::get('/campuran', [BerasKelolaController::class, 'getBerasCampuran']);
    Route::post('/create/campuran', [BerasKelolaController::class, 'createCampuran']);
});
Route::prefix('modal_kelola')->group(function () {
    Route::post('/create', [ModalKelolaController::class, 'createModalKelola']);
    Route::post('/create/campuran', [ModalKelolaController::class, 'createModalKelolaCampuran']);
});
