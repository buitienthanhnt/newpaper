<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Thanhnt\Nan\Helper\TokenManager;

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

Route::get('getToken', function (Request $request) {
    $token = new TokenManager($request);
    // $token_value = $token->getToken(['id' => 1, 'name' => 'nan', 'street' => '21b natial']);
    $token_value = $token->getToken([]);
    return $token_value;
});

Route::get('getTokenData', function (Request $request) {
    $token = new TokenManager($request);
    $tokenString = '';
    $token_value = $token->getTokenData($tokenString);
    return ($token_value);
});