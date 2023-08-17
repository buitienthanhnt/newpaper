<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|    https://viblo.asia/p/xay-dung-api-voi-laravel-djeZ1RjGlWz
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('getpapers', 'ManagerController@apiSourcePapers');

Route::get("getpaperdetail/{paper_id}", "ManagerController@getPaperDetail");

Route::get("getcategorytop", "ManagerController@getCategoryTop");

Route::get("papercategory/{category_id}", "ManagerController@getPaperCategory");

Route::get("getRelatedPaper", "ManagerController@getRelatedPaper");

Route::get("getcategorytree", "ManagerController@getCategoryTree");

Route::get("testJson", function ()
{
    echo(json_encode([
        "a" => 1,
        "b" => 2,
        "c" => "asd"
    ]));
});
