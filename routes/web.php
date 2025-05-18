<?php

use Illuminate\Support\Facades\Route;
use Vfjodorovs12\LosReg\Http\Controllers\LosRegController;

// Современный стиль — указываем полный namespace
Route::middleware(['web', 'auth', 'can:administrator'])
    ->prefix('los-reg')
    ->group(function () {
        Route::get('/unregistered', [\Vfjodorovs12\LosReg\Http\Controllers\LosRegController::class, 'showUnregistered'])->name('los-reg.unregistered');
    });
