<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () { return redirect()->route('saas.tenants.index'); });
Route::get('/login', 'Saas\AuthController@form')->name('saas.login');
Route::post('/login', 'Saas\AuthController@login')->middleware('throttle:5,1');
Route::middleware('saas.auth')->group(function () {
    Route::get('/settings/cloudflare', 'Saas\CloudflareController@index')->name('saas.cloudflare.index');
    Route::post('/settings/cloudflare', 'Saas\CloudflareController@save')->middleware('throttle:10,1')->name('saas.cloudflare.save');
    Route::post('/settings/cloudflare/test', 'Saas\CloudflareController@test')->middleware('throttle:5,1')->name('saas.cloudflare.test');
    Route::get('/tenants', 'Saas\TenantController@index')->name('saas.tenants.index');
    Route::post('/tenants', 'Saas\TenantController@store')->name('saas.tenants.store');
    Route::post('/tenants/{id}/operations', 'Saas\TenantController@operation')->middleware('throttle:10,1')->name('saas.tenants.operation');
    Route::patch('/tenants/{id}', 'Saas\TenantController@update')->name('saas.tenants.update');
    Route::post('/logout', 'Saas\AuthController@logout')->name('saas.logout');
});
