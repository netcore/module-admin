<?php

Route::group(['middleware' => 'web', 'prefix' => 'admin', 'namespace' => 'Modules\Admin\Http\Controllers'], function()
{
    Route::group(['middleware' => 'auth.admin'], function () {

        Route::get('/', [
            'uses' => 'AdminController@index',
            'as'   => 'admin::dashboard.index'
        ]);

        Route::get('/menus', [
            'uses' => 'MenuController@index',
            'as'   => 'admin::menu.index'
        ]);

        Route::get('/menus/{id}', [
            'uses' => 'MenuController@show',
            'as'   => 'admin::menu.show'
        ]);

        Route::post('/menus/{id}/save-order', [
            'uses' => 'MenuController@saveOrder',
            'as'   => 'admin::menu.save-order'
        ]);

        Route::post('/menus/{id}/save-item', [
            'uses' => 'MenuController@saveMenuItem',
            'as'   => 'admin::menu.save-item'
        ]);

        Route::post('/menus/{id}/delete-item/{itemId}', [
            'uses' => 'MenuController@deleteMenuItem',
            'as'   => 'admin::menu.delete-item'
        ]);
    });

    //Auth routes --------------------------------------------------------------------------------------------
    Route::get('/login',[
        'uses' => 'Auth\LoginController@showLoginForm',
        'as'   => 'admin::auth.login'
    ]);
    Route::get('/logout',[
        'uses' => 'Auth\LoginController@logout',
        'as'   => 'admin::auth.logout'
    ]);
    Route::get('/auth/reset',[
        'uses' => 'Auth\ResetPasswordController@showResetForm',
        'as'   => 'admin::auth.reset'
    ]);
    Route::get('/auth/request',[
        'uses' => 'Auth\ForgotPasswordController@showLinkRequestForm',
        'as'   => 'admin::auth.request'
    ]);

    /**
     * Custom routes
     */
    Route::post('/switch-active', [
        'uses' => 'SwitchActiveController@switchActive',
        'as'   => 'admin::switch-active'
    ]);


    //custom middleware to disallow common users from authorizing
    Route::group(['middleware' => 'can.admin'], function () {
        Route::post('/login',[
            'uses' => 'Auth\LoginController@login',
            'as'   => 'admin::auth.login'
        ]);
        Route::post('/auth/reset',[
            'uses' => 'Auth\ResetPasswordController@reset',
            'as'   => 'admin::auth.reset'
        ]);
        Route::post('/auth/request',[
            'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail',
            'as'   => 'admin::auth.request'
        ]);
    });

    //EOF Auth routes ----------------------------------------------------------------------------------------
});
