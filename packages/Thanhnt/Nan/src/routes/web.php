<?php

use App\Jobs\PaperJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Thanhnt\Nan\Helper\TokenManager;
use App\ViewBlock\TopCategory;

Route::get('/test', function () {
    $top = new TopCategory();
    echo($top->setTemplate('')->toHtml());
    // return asset('public/assets/pub_image/defaul.PNG');
    // return "Test package google webhook calendar";
    return view('welcome');
});

Route::get('testview', function () {
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

Route::get('log', function (Request $request) {
    // ghi lỗi: Log file
    // Log::stack(['tha'])->info('Something happened!'); // ghi log vào chanel(khai báo trong config): tha với path="storage/logs/tha/
    // Log::error('error demo by tha nan');
    // Log::warning('warning demo by tha nan');
    // Log::info('Showing the user profile for user: {id}', ['id' => 12312313]); // [2023-11-13 08:49:31] local.INFO: Showing the user profile for user: {id} {"id":12312313}

    // tạo 1 chanel mà không qua config
    $channel = Log::build([
        'driver' => 'single',
        'path' => storage_path('logs/tha/nan1.log'),
    ]);
    Log::stack([$channel])->info('Something happened in custom log file!');

    return 'demo log';
});

Route::get('dispath', function(){
    PaperJob::dispatch();
    return 123;
});
