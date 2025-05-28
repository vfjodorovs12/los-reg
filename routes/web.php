<?php

use Vfjodorovs12\LosReg\Http\Controllers\LosRegController;

Route::middleware(['web', 'auth'])->prefix('los-reg')->name('los-reg.')->group(function () {
    Route::get('/', [LosRegController::class, 'index'])->name('index');
    Route::get('/unregistered', [LosRegController::class, 'unregistered'])->name('unregistered');

});
