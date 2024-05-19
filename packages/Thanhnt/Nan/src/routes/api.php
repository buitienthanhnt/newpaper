<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('routeList', "App\Http\Controllers\PermissionController@methodGetRoutes")->name('routeList');

Route::get('getUserToken', "App\Http\Controllers\ManagerController@getToken")->name('getToken');

Route::post('refreshUserToken', "App\Http\Controllers\ManagerController@refreshUserToken")->name('refreshUserToken');

Route::get('getUserTokenData', "App\Http\Controllers\ManagerController@getTokenData")->name('getTokenData');