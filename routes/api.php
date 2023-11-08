<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

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

Route::get("parseUrl", 'ManagerController@parseUrl');

Route::get('sdetail', "ManagerController@getAdetail");

Route::get('getStore', "ManagerController@getStores");

Route::prefix('notification')->group(function(){
    Route::post("registerFcm", "NotificationController@registerFcm")->name('registerFcm');

    Route::get('push', "NotificationController@push_notification")->name("notification_push");
});

Route::get("testJson", function (Request $request)
{
    // throw new Exception("Error Processing Request", 401);
    return $request->getContent();
});

Route::post("testPost", function (Request $request)
{
    $params = $request->getContent();
    return $params;
    // throw new Exception("Error Processing Request", 406);
    // throw new HttpException(500, 'opopop');

    return(json_encode([
        "name" => 'tha',
        "id" => 2,
        "title" => "demo for post api",
        'arr' => [23, 34, 45, 56]
    ]));
});

// https://viblo.asia/p/huong-dan-trien-khai-desgin-patterns-trong-laravel-Qpmle79rKrd
