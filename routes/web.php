<?php

use Illuminate\Support\Facades\Route;
use Vfjodorovs12\LosReg\LosRegController;

Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('/losreg/unregistered', [LosRegController::class, 'showUnregistered'])->name('losreg.unregistered');
});
