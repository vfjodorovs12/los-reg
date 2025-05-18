<?php

use Illuminate\Support\Facades\Route;
use Vfjodorovs12\LosReg\Http\Controllers\LosRegController;

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/los-reg', [LosRegController::class, 'index'])->name('los-reg.index');
});
