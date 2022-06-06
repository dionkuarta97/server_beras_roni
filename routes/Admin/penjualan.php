<?php

use App\Http\Controllers\PenjualanController;
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


Route::post('/create/campuran', [PenjualanController::class, 'createPenjualanCampuran']);
Route::get('/{id}', [PenjualanController::class, 'detailPenjualanCampuran']);
Route::get('/', [PenjualanController::class, 'getAllPenjualan']);
Route::put('/update/{id}', [PenjualanController::class, 'updatePenjualan']);

Route::prefix('modal_penjualan')->group(function () {
    Route::post('/create', [PenjualanController::class, 'createModalPenjualan']);
});
