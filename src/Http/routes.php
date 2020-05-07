<?php

Route::group([
    'namespace' => 'Seat\Akturis\WinKill\Http\Controllers',
    'prefix' => 'winkill'
], function () {
    Route::group([
        'middleware' => ['web', 'auth', 'locale'],
    ], function () {
        Route::get('/', [
            'as'   => 'winkill.view',
            'uses' => 'WinKillController@index',
            'middleware' => 'bouncer:winkill.view'
        ]);
        Route::post('/', [
            'as'   => 'winkill.update',
            'uses' => 'WinKillController@getDropFromKillmail',
            'middleware' => 'bouncer:winkill.view'
        ]);
    });
});
