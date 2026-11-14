<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () { return redirect()->route('saas.tenants.index'); });
Route::get('/login', 'Saas\AuthController@form')->name('saas.login');
Route::post('/login', 'Saas\AuthController@login')->middleware('throttle:5,1');
Route::middleware('saas.auth')->group(function () {
    Route::get('/tenants', 'Saas\TenantController@index')->name('saas.tenants.index');
    Route::post('/tenants', 'Saas\TenantController@store')->name('saas.tenants.store');
    Route::patch('/tenants/{id}', 'Saas\TenantController@update')->name('saas.tenants.update');
    Route::post('/logout', 'Saas\AuthController@logout')->name('saas.logout');
});
