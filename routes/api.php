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

Route::get('info', "ManagerController@info")->name('info');

Route::get('getpapers', 'ManagerController@apiSourcePapers');

Route::get("getpaperdetail/{paper_id}", "ManagerController@getPaperDetail");

Route::get("getcategorytop", "ManagerController@getCategoryTop");

Route::get("papercategory/{category_id}", "ManagerController@getPaperCategory");

Route::get("getRelatedPaper", "ManagerController@getRelatedPaper");

Route::get("getcategorytree", "ManagerController@getCategoryTree");

Route::get("parseUrl", 'ManagerController@parseUrl');

Route::get('paperComment/{paper_id}', "ManagerController@getPaperComment")->name('getPaperComment');

Route::post("paperAddComment/{paper_id}", "PaperController@addComment")->name("api_paper_add_comment");

Route::post("addLike/{paper_id}", "PaperController@addLike")->name("api_addLike");

Route::get('upFirebaseComments/{paper_id}', "ManagerController@upFirebaseComments")->name('upFirebaseComments');

Route::prefix('notification')->group(function(){
    Route::post("registerFcm", "NotificationController@registerFcm")->name('registerFcm');

    Route::get('push', "NotificationController@push_notification")->name("notification_push");
});

Route::get('mostviewdetail/{page?}', "ManagerController@mostviewdetail")->name("mostviewdetail");

Route::post('mobile/upimage', "ExtensionController@uploadImageFromMobile")->name('uploadImageFromMobile');

Route::get('commentTest', "ManagerController@commentTest")->name('commentTest');

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

Route::prefix('share')->group(function(){
    Route::get("mostPopulator", "ManagerController@mostPopulator")->name('mostPopulator');

    Route::get("likeMost", "ManagerController@likeMost")->name('likeMost');

    Route::get('trending', "ManagerController@trending")->name("trending");
});



// https://viblo.asia/p/huong-dan-trien-khai-desgin-patterns-trong-laravel-Qpmle79rKrd
