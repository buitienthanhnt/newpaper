<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('routeList', "App\Http\Controllers\PermissionController@methodGetRoutes")->name('routeList');