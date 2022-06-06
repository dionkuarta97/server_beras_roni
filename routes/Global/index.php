<?php

use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;



Route::post('/login', [UsersController::class, 'login']);
