<?php

use Illuminate\Support\Facades\Route;
use Vfjodorovs12\LosReg\LosRegController;

// Группировка маршрутов под middleware SEAT (если нужно)
Route::group(['middleware' => ['web', 'auth']], function () {
    // Пример маршрута для списка незарегистрированных персонажей
    Route::get('/losreg/unregistered', [LosRegController::class, 'showUnregistered'])->name('losreg.unregistered');
    // Добавь свои маршруты ниже
    // Route::get('/losreg/что_угодно', [LosRegController::class, 'yourMethod']);
});
