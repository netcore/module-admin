<?php

use Modules\Admin\Http\Middleware\Admin\canAuthorizeInAdmin;
use Modules\Admin\Http\Middleware\Admin\isAdmin;

Route::group(['middleware' => 'web', 'prefix' => 'admin', 'namespace' => 'Modules\Admin\Http\Controllers'], function()
{
    Route::group(['middleware' => isAdmin::class], function () {

        Route::get('/', [
            'uses' => 'AdminController@index',
            'as'   => 'admin::dashboard.index'
        ]);

        Route::get('/menu', [
            'uses' => 'MenusController@index',
            'as'   => 'admin::menu.index'
        ]);
        Route::get('/menu/{id}', [
            'uses' => 'MenusController@show',
            'as'   => 'admin::menu.show'
        ]);
        Route::post('/menu/{id}/save-order', [
            'uses' => 'MenusController@saveOrder',
            'as'   => 'admin::menu.save-order'
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
    Route::group(['middleware' => canAuthorizeInAdmin::class], function () {
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
