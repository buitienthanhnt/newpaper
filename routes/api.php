<?php

use Berkayk\OneSignal\OneSignalFacade; // https://github.com/berkayk/laravel-onesignal
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

Route::get("getpaperdetail/{paper_id}", "ManagerController@getPaperDetail")->name('api_paperDetail');

Route::get("getcategorytop", "ManagerController@getCategoryTop");

Route::get("papercategory/{category_id}", "ManagerController@getPaperCategory");

Route::get("getRelatedPaper", "ManagerController@getRelatedPaper");

Route::get("getcategorytree", "ManagerController@getCategoryTree");

Route::get("parseUrl", 'ManagerController@parseUrl');

Route::get('paperComment/{paper_id}', "ManagerController@getPaperComment")->name('getPaperComment');

Route::post("paperAddComment/{paper_id}", "PaperController@addComment")->name("api_paper_add_comment");

Route::post("addLike/{paper_id}", "PaperController@addLike")->name("api_addLike");

Route::get('upFirebaseComments/{paper_id}', "ManagerController@upFirebaseComments")->name('upFirebaseComments');

Route::prefix('notification')->group(function () {
    Route::post("addFcm", "NotificationController@registerFcm")->name('api_addFcm');

    Route::get('push', "NotificationController@push_notification")->name("api_notification_push");
});

Route::get('mostviewdetail/{page?}', "ManagerController@mostviewdetail")->name("mostviewdetail");

Route::post('mobile/upimage', "ExtensionController@uploadImageFromMobile")->name('uploadImageFromMobile');

Route::get('pullFirebaseComment', "ManagerController@pullFirebaseComment")->name('pullFirebaseComment');

Route::get('pullFirebasePaperLike', "ManagerController@pullFirebasePaperLike")->name('pullFirebasePaperLike');

Route::get('pullFirebaseComLike', "ManagerController@pullFirebaseComLike")->name('pullFirebaseComLike');

Route::get('search', "ManagerController@searchApi")->name('api_search');

Route::get('byWriter/{id}', "ManagerController@byWriter")->name('api_search_byWriter');

Route::prefix('test')->group(function () {

    Route::get('onesign', function () {
        OneSignalFacade::sendNotificationToAll( // đã chạy: https://github.com/berkayk/laravel-onesignal
            "Some Message",
            'http://localhost/laravel1/public/',
        );
        return 123;
    });

    Route::get('homeInfo', "FirebaseController@info");

    Route::get('getWriters', function () {
        return [
            [
                'id' => 1,
                'name' => 'tha',
                'age' => 12
            ],
            [
                'id' => 2,
                'name' => 'demo',
                'age' => 22
            ],
            [
                'id' => 3,
                'name' => 'ppp',
                'age' => 23
            ]
        ];
    })->name('api_writers');

    Route::post("testPost", function (Request $request) {
        $params = $request->getContent();
        return $params;
        // throw new Exception("Error Processing Request", 406);
        // throw new HttpException(500, 'opopop');

        return (json_encode([
            "name" => 'tha',
            "id" => 2,
            "title" => "demo for post api",
            'arr' => [23, 34, 45, 56]
        ]));
    });
});

Route::prefix('share')->group(function () {
    Route::get("mostPopulator", "ManagerController@mostPopulator")->name('mostPopulator');

    Route::get("likeMost", "ManagerController@likeMost")->name('likeMost');

    Route::get('trending', "ManagerController@trending")->name("trending");
});

Route::prefix('paper')->group(function () {
    Route::post("like/{comment_id?}", "PaperController@like")->name("api_paper_like");

    Route::get("firebase/{paper_id?}", "ManagerController@firebasePaperDetail")->name("api_paper_detail_firebase");
});

Route::post('login', "ManagerController@loginApi")->name('api_login');

Route::get('userInfo', "ManagerController@getUserInfo")->name('api_user_info');

// https://viblo.asia/p/huong-dan-trien-khai-desgin-patterns-trong-laravel-Qpmle79rKrd
