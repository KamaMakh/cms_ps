<?php

use Illuminate\Http\Request;
use \App\Models\Site;

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
Route::namespace('API')->group(function () {
    /* sites */
    Route::get('sites', 'SiteController@index');
    Route::get('sites/{site}', 'SiteController@show');
    Route::post('sites', 'SiteController@store');
    Route::put('sites/{site}', 'SiteController@update');
    Route::delete('sites/{site}', 'SiteController@delete');

    Route::namespace('Site')->group(function () {
        Route::prefix('sites/{site}')->group(function () {
            /* Site menus */
            Route::get('menus', 'MenuController@index');
            Route::get('menus/{menu}', 'MenuController@show');
            Route::post('menus', 'MenuController@store');
            Route::put('menus/{menu}', 'MenuController@update');
            Route::delete('menus/{menu}', 'MenuController@delete');

            /* Site menus -> pages */
            Route::get('menus/{menu}/pages/{page}', 'MenuController@pageShow');
            Route::post('menus/{menu}/pages', 'MenuController@pageStore');
            Route::put('menus/{menu}/pages/{page}', 'MenuController@pageUpdate');
            Route::delete('menus/{menu}/pages/{page}', 'MenuController@pageDelete');

            /* Site images */
            Route::get('images', 'ImageController@index');
            //Route::get('images/{image}', 'ImageController@show');
            Route::post('images', 'ImageController@store');
            //Route::put('images/{image}', 'ImageController@update');
            //Route::delete('menus/{image}', 'ImageController@delete');

            /* Site menus -> pages -> vars */
            Route::get('menus/{menu}/pages/{page}/variables', 'MenuController@pageVariables');
            Route::get('menus/{menu}/pages/{page}/variables/{variable}', 'MenuController@pageVariableShow');
            Route::put('menus/{menu}/pages/{page}/variables/{variable}', 'MenuController@pageVariableUpdate');

            /* Site templates */
            Route::get('templates/pages', 'TemplateController@pages');
        });
    });

    /* designs */
    Route::get('designs', 'DesignController@index');
    Route::get('designs/{design}', 'DesignController@show');
    Route::post('designs', 'DesignController@store');
    Route::put('designs/{design}', 'DesignController@update');
    Route::delete('designs/{design}', 'DesignController@delete');
    Route::namespace('Design')->group(function () {
        Route::prefix('designs/{design}')->group(function () {
            /* Design variables */
            Route::get('variables', 'VariableController@index');
            Route::get('variables/types', 'VariableController@types');
            Route::get('variables/{variable}', 'VariableController@show');
            Route::post('variables', 'VariableController@store');
            Route::put('variables/{variable}', 'VariableController@update');
            Route::delete('variables/{variable}', 'VariableController@delete');

        });
    });

    /* templates */
    Route::get('templates', 'TemplateController@index');
    Route::get('templates/{template}', 'TemplateController@show');
    Route::post('templates', 'TemplateController@store');
    Route::put('templates/{template}', 'TemplateController@update');
    Route::delete('templates/{template}', 'TemplateController@delete');

    /* variables */
    Route::get('variables', 'VariableController@index');
    Route::get('variables/{variable}', 'VariableController@show');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
