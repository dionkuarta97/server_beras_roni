<?php

use App\Http\Controllers\CategoryController;
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


Route::post('/create', [CategoryController::class, 'createCategory']);
Route::get('/all', [CategoryController::class, 'getAllCategory']);
Route::get('/select', [CategoryController::class, 'getCategorySelect']);
Route::patch('/update/{id}', [CategoryController::class, 'updateCategory']);
Route::delete('/delete/{id}', [CategoryController::class, 'deleteCategory']);
