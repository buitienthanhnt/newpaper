<?php

use App\Http\Controllers\ExtensionControllerInterface;
use App\Http\Controllers\PaperFrontControllerInterface;
use Berkayk\OneSignal\OneSignalFacade; // https://github.com/berkayk/laravel-onesignal
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

$extensionController = ExtensionControllerInterface::CONTROLLER_NAME.'@';
$paperFrontController = PaperFrontControllerInterface::CONTROLLER_NAME.'@';

// https://localhost/laravel1/public/api/homeInfo
Route::get('homeInfo', $extensionController.ExtensionControllerInterface::HOME_INFO);

// https://localhost/laravel1/public/api/getPapers
Route::get('getPapers', $extensionController.ExtensionControllerInterface::LIST_PAPERS);

// https://localhost/laravel1/public/api/getCategoryTree
Route::get("getCategoryTree", $extensionController.ExtensionControllerInterface::CATEGORY_TREE);

// https://localhost/laravel1/public/api/getCategoryTop
Route::get("getCategoryTop", $extensionController.ExtensionControllerInterface::CATEGORY_TOP);

// https://localhost/laravel1/public/api/paperByCategory/2
Route::get("paperByCategory/{category_id}", $extensionController.ExtensionControllerInterface::PAPER_BY_CATEGORY);

// https://localhost/laravel1/public/api/getRelatedPaper/1
Route::get("getRelatedPaper/{paper_id}", $extensionController.ExtensionControllerInterface::PAPER_RELATED);

// https://localhost/laravel1/public/api/paperComments/1
Route::get('paperComments/{paper_id}', $extensionController.ExtensionControllerInterface::PAPER_COMMENTS);

// curl  -X POST \
//   'https://localhost/laravel1/public/api/paperAddComment/1' \
//   --header 'Accept: */*' \
//   --header 'Content-Type: application/json' \
//   --data-raw '{
//   "email": "a1@gmail.com",
//   "name": "a12",
//   "subject": "demo add comment api",
//   "message": "noi dung"
// }'
Route::post("paperAddComment/{paper_id}", $paperFrontController.PaperFrontControllerInterface::FRONT_PAPER_ADD_COMMENT);

// curl  -X POST \
//   'https://localhost/laravel1/public/api/likePaper/1' \
//   --header 'Accept: */*' \
//   --header 'Content-Type: application/json' \
//   --data-raw '{
//   "name": "a12",
//   "type": "like",
//   "action": "add"
// }'
Route::post("likePaper/{paper_id}", $paperFrontController.PaperFrontControllerInterface::FRONT_PAPER_ADD_LIKE);

// https://localhost/laravel1/public/api/search?query=demo
Route::get('search', $extensionController.ExtensionControllerInterface::SEARCH);

// https://localhost/laravel1/public/api/byWriter/1
Route::get('byWriter/{id}', $extensionController.ExtensionControllerInterface::PAPER_BY_WRITER);

Route::get("parseUrl", 'ManagerController@parseUrl');

// https://localhost/laravel1/public/api/paperMostView
Route::get('paperMostView', $extensionController.ExtensionControllerInterface::PAPER_MOST_VIEW)->name('paper_most_view');

// curl  -X POST \
//   'https://localhost/laravel1/public/api/login' \
//   --header 'Accept: */*' \
//   --header 'Content-Type: application/json' \
//   --data-raw '{
//   "email": "a12@gmail.com",
//   "password": "admin123"
// }'
Route::post('login', $extensionController.ExtensionControllerInterface::LOGIN);

// https://localhost/laravel1/public/api/userInfo
Route::get('userInfo', $extensionController.ExtensionControllerInterface::USER_INFO);

Route::prefix('share')->group(function () use($extensionController){

    // https://localhost/laravel1/public/api/share/mostPopulator
    Route::get("mostPopulator", $extensionController.ExtensionControllerInterface::MOST_POPULATOR_HTML)->name('mostPopulator');

    // https://localhost/laravel1/public/api/share/likeMost
    Route::get("likeMost", $extensionController.ExtensionControllerInterface::MOST_LIKE_HTML)->name('likeMost');

    // https://localhost/laravel1/public/api/share/trending
    Route::get('trending', $extensionController.ExtensionControllerInterface::MOST_TRENDING_HTML)->name("trending");
});

Route::prefix('paper')->group(function () {

    Route::get("detail/{paper_id}", "ManagerController@getPaperDetail")->name('api_paperDetail');

    Route::post("like/{comment_id?}", "PaperController@like")->name("api_paper_like");

    Route::get("firebase/{paper_id?}", "ManagerController@firebasePaperDetail")->name("api_paper_detail_firebase");

    Route::post("addCart", "PaperController@addCartApi")->name("api_paper_add_cart");

    Route::get("cart", "PaperController@getCartApi")->name("api_get_cart");

    Route::delete("clearCart", "PaperController@clearCartApi")->name("api_clear_cart");

    Route::delete('removeItem/{id}', "PaperController@removeItemApi")->name("api_remove_item");
});

Route::prefix('notification')->group(function () {
    Route::post("addFcm", "NotificationController@registerFcm")->name('api_addFcm');

    Route::get('push', "NotificationController@push_notification")->name("api_notification_push");
});

Route::post('mobile/upimage', "ExtensionController@uploadImageFromMobile")->name('uploadImageFromMobile');

Route::get('upFirebaseComments/{paper_id}', "ManagerController@upFirebaseComments")->name('upFirebaseComments');

Route::get('pullFirebaseComment', "ManagerController@pullFirebaseComment")->name('pullFirebaseComment');

Route::get('pullFirebasePaperLike', "ManagerController@pullFirebasePaperLike")->name('pullFirebasePaperLike');

Route::get('pullFirebaseComLike', "ManagerController@pullFirebaseComLike")->name('pullFirebaseComLike');

Route::prefix('test')->group(function () {

    Route::get('onesign', function () {
        OneSignalFacade::sendNotificationToAll( // đã chạy: https://github.com/berkayk/laravel-onesignal
            "Some Message",
            'http://localhost/laravel1/public/',
        );
        return 123;
    });
});

// https://viblo.asia/p/huong-dan-trien-khai-desgin-patterns-trong-laravel-Qpmle79rKrd
