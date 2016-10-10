<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

//Auth::routes();
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout');

// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');


Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function() {
    Route::get('social/{provider}', ['as' => 'social.login', 'uses' => 'SocialiteController@redirectToProvider'])
        ->where(['provider' => '[a-zA-Z]+']);
    Route::get('social/callback/{provider}', ['as' => 'social.callback', 'uses' => 'SocialiteController@handleProviderCallback'])
        ->where(['provider' => '[a-zA-Z]+']);

    //Account confirmation
    Route::get('account-confirm/{id}/{token}', ['as' => 'account.confirm', 'uses' => 'RegisterController@accountConfirm']);
});

Route::group(['prefix' => 'users'], function() {
    Route::get('account', ['as' => 'user.account', 'uses' => 'UserController@index']);
});

//Categories
Route::resource('category', 'CategoryController');
Route::resource('metaCategory', 'MetaCategoryController');
