<?php

Route::group([
    'middleware' => 'web',
    'prefix'     => 'admin',
    'as'         => 'admin::',
    'namespace'  => 'Modules\Admin\Http\Controllers'
], function () {
    Route::group(['middleware' => 'auth.admin'], function () {

        Route::get('/', [
            'uses' => 'AdminController@index',
            'as'   => 'dashboard.index'
        ]);

        Route::resource('menus', 'MenuController', ['only' => ['index', 'edit', 'update', 'show']]);

        Route::post('/menus/save-order/{menuId}', [
            'uses' => 'MenuController@saveOrder',
            'as'   => 'menu.save-order'
        ]);

        Route::post('/menus/{menu}/save-item', [
            'uses' => 'MenuController@saveMenuItem',
            'as'   => 'menu.save-item'
        ]);

        Route::post('/menus/{menu}/delete-item/{itemId}', [
            'uses' => 'MenuController@deleteMenuItem',
            'as'   => 'menu.delete-item'
        ]);

        Route::resource('whitelist', 'WhitelistController', [
            'except' => [
                'show'
            ]
        ]);
    });

    //Auth routes --------------------------------------------------------------------------------------------
    Route::get('/login', [
        'uses' => 'Auth\LoginController@showLoginForm',
        'as'   => 'auth.login'
    ]);
    Route::get('/logout', [
        'uses' => 'Auth\LoginController@logout',
        'as'   => 'auth.logout'
    ]);
    Route::get('/auth/reset', [
        'uses' => 'Auth\ResetPasswordController@showResetForm',
        'as'   => 'auth.reset'
    ]);
    Route::get('/auth/request', [
        'uses' => 'Auth\ForgotPasswordController@showLinkRequestForm',
        'as'   => 'auth.request'
    ]);

    /**
     * Custom routes
     */
    Route::post('/switch-active', [
        'uses' => 'SwitchActiveController@switchActive',
        'as'   => 'switch-active'
    ]);

    //custom middleware to disallow common users from authorizing
    Route::group(['middleware' => 'can.admin'], function () {
        Route::post('/login', [
            'uses' => 'Auth\LoginController@login',
            'as'   => 'auth.login'
        ]);
    });

    Route::get('/access-denied', [
        'uses' => 'AdminController@denied',
        'as'   => 'access-denied'
    ]);

    //EOF Auth routes ----------------------------------------------------------------------------------------
});
