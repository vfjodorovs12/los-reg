<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'can:administrator'])
    ->namespace('Modules\LosReg\Http\Controllers')
    ->prefix('los-reg')
    ->group(function () {
        Route::get('/unregistered', 'LosRegController@showUnregistered')->name('los-reg.unregistered');
    });
