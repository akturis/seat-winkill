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
            'middleware' => 'bouncer:winkill.view,winkill.discord'
        ]);
        Route::post('/', [
            'as'   => 'winkill.update',
            'uses' => 'WinKillController@getDropFromKillmail',
            'middleware' => 'bouncer:winkill.view,winkill.discord'
        ]);

        Route::get('/settings', [
            'as'   => 'winkill.settings',
            'uses' => 'SettingsController@index',
            'middleware' => 'bouncer:winkill.settings'
        ]);        
        Route::post('/settings', [
            'as'   => 'winkill.settings',
            'uses' => 'SettingsController@update',
            'middleware' => 'bouncer:winkill.settings'
        ]);        
        
    });
});
