<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Vfjodorovs12\LosReg\Http\Controllers', 'middleware' => ['web', 'auth']], function () {
    Route::get('/los-reg', 'LosRegController@index')->name('los-reg.index');
});
