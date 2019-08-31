<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
    /* Routes That don't need autherisatino */

    // User toures
    Route::post('login', 'UserController@login');
    Route::post('register', 'UserController@register');
    // Products page and single product products routes
    Route::get('/products', 'ProductController@index');
    Route::get('/products/{product}', 'ProductController@show');
    // To get the categories of products
    Route::get('/categories', 'CategoryController@index');

    //  In order to be able to use formData object to upload filse 
    Route::post('/upload-file', 'ProductController@uploadFile');
    
    /* Routes that need authorisation */
    
    Route::group(['middleware' => 'auth:api'], function(){
        // categories routes
        Route::resource('/categories', 'CategoryController')->except('index');
        // products routes except index and show
        Route::resource('/products', 'ProductController')->except(['index','show']);
    });
