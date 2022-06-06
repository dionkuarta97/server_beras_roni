<?php

use Illuminate\Support\Facades\Route;




Route::prefix('user')->group(function () {
    require(__DIR__ . '/user.php');
});

Route::prefix('category')->group(function () {
    require(__DIR__ . '/category.php');
});

Route::prefix('modal')->group(function () {
    require(__DIR__ . '/modal.php');
});

Route::prefix('penjualan')->group(function () {
    require(__DIR__ . '/penjualan.php');
});
Route::prefix('langganan')->group(function () {
    require(__DIR__ . '/langganan.php');
});
