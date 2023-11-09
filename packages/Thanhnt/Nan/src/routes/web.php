<?php

use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return asset('public/assets/pub_image/defaul.PNG');
    // return "Test package google webhook calendar";
    return view('welcome');
});

Route::get('testview', function()  {
    return view('nan::testpack');   // trả về view theo module: moduleName::path/viewName
});

Route::any('token/{demo}', function ($demo) {
    return view('nan::token.demo', ['html' => $demo]);
});