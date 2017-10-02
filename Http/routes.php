<?php

Route::group(['middleware' => 'web', 'prefix' => 'admin', 'namespace' => 'Modules\Admin\Http\Controllers'], function()
{
    Route::group(['middleware' => 'auth.admin'], function () {

        Route::get('/', [
            'uses' => 'AdminController@index',
            'as'   => 'admin::dashboards.index'
        ]);

        Route::get('/menus', [
            'uses' => 'MenusController@index',
            'as'   => 'admin::menus.index'
        ]);
        Route::get('/menus/{id}', [
            'uses' => 'MenusController@show',
            'as'   => 'admin::menus.show'
        ]);
        Route::post('/menus/{id}/save-order', [
            'uses' => 'MenusController@saveOrder',
            'as'   => 'admin::menus.save-order'
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
