<?php

use App\Http\Controllers\Api\CartApiControllerInterface;
use App\Http\Controllers\Api\CategoryApiControllerInterface;
use App\Http\Controllers\ExtensionController;
use App\Http\Controllers\ExtensionControllerInterface;
use App\Http\Controllers\PaperFrontControllerInterface;
use App\Http\Controllers\NotificationControllerInterface;
use App\Http\Controllers\Api\CommentApiControllerInterface;
use App\Http\Controllers\Api\PaperApiControllerInterface;
use App\Http\Controllers\Api\WriterApiControllerInterface;
use Berkayk\OneSignal\OneSignalFacade;

// https://github.com/berkayk/laravel-onesignal
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

$extensionController = ExtensionControllerInterface::CONTROLLER_NAME . '@';
$paperFrontController = PaperFrontControllerInterface::CONTROLLER_NAME . '@';
$notificationController = NotificationControllerInterface::CONTROLLER_NAME . '@';
$commentControllerApi = CommentApiControllerInterface::CONTROLLER_NAME . '@';
$paperApiController = PaperApiControllerInterface::CONTROLLER_NAME . '@';
$cartApiController = CartApiControllerInterface::CONTROLLER_NAME . '@';
$categoryApiController = CategoryApiControllerInterface::CONTROLLER_NAME . '@';
$writerApiController = WriterApiControllerInterface::CONTROLLER_NAME . '@';

// https://localhost/laravel1/public/api/homeInfo
Route::get('homeInfo', $extensionController . ExtensionControllerInterface::HOME_INFO);

// https://localhost/laravel1/public/api/search?query=demo
Route::get('search', $extensionController . ExtensionControllerInterface::SEARCH);

// https://localhost/laravel1/public/api/paperMostView (dung cho web)
Route::get('paperMostView', $extensionController . ExtensionControllerInterface::PAPER_MOST_VIEW)->name('api_paper_most_view');

// curl  -X POST \
//   'https://localhost/laravel1/public/api/login' \
//   --header 'Accept: */*' \
//   --header 'Content-Type: application/json' \
//   --data-raw '{
//   "email": "a12@gmail.com",
//   "password": "admin123"
// }'
Route::post('login', $extensionController . ExtensionControllerInterface::LOGIN);

// https://localhost/laravel1/public/api/userInfo
Route::get('userInfo', $extensionController . ExtensionControllerInterface::USER_INFO);

// https://localhost/laravel1/public/api/getPapers
Route::get('getPapers', $paperApiController . PaperApiControllerInterface::LIST_PAPERS);

// https://localhost/laravel1/public/api/getRelatedPaper/1
Route::get("getRelatedPaper/{paper_id}", $paperApiController . PaperApiControllerInterface::PAPER_RELATED);

Route::get('categoryInfo/{category_id}', $categoryApiController . CategoryApiControllerInterface::CATEGORY_INFO);

// https://localhost/laravel1/public/api/getCategoryTree
Route::get("getCategoryTree", $categoryApiController . CategoryApiControllerInterface::CATEGORY_TREE);

// https://localhost/laravel1/public/api/getCategoryTop
Route::get("getCategoryTop", $categoryApiController . CategoryApiControllerInterface::CATEGORY_TOP);

// https://localhost/laravel1/public/api/paperByCategory/2
Route::get("paperByCategory/{category_id}", $categoryApiController . CategoryApiControllerInterface::PAPER_BY_CATEGORY);

// https://localhost/laravel1/public/api/paperComments/1
Route::get('paperComments/{paper_id}', $commentControllerApi . CommentApiControllerInterface::PAPER_COMMENTS);

// https://localhost/laravel1/public/api/commentChildrent/1
Route::get('commentChildrent/{comment_id}', $commentControllerApi . CommentApiControllerInterface::PAPER_COMMENT_CHILDRENT);

// https://localhost/laravel1/public/api/writerList
Route::get('writerList', $writerApiController . WriterApiControllerInterface::WRITER_LIST);

// https://localhost/laravel1/public/api/byWriter/1
Route::get('byWriter/{id}', $writerApiController . WriterApiControllerInterface::PAPER_BY_WRITER);

Route::prefix('paper')->group(function () use ($commentControllerApi, $paperApiController, $cartApiController) {

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
    Route::post("addComment/{paper_id}", $commentControllerApi . CommentApiControllerInterface::PAPER_ADD_COMMENT);

    // curl  -X POST \
    //   'https://localhost/laravel1/public/api/likePaper/1' \
    //   --header 'Accept: */*' \
    //   --header 'Content-Type: application/json' \
    //   --data-raw '{
    //   "name": "a12",
    //   "type": "like",
    //   "action": "add"
    // }'

    // commentReply
    Route::post("replyComment/{comment_id}", $commentControllerApi . CommentApiControllerInterface::PAPER_REPLY_COMMENT);

    Route::post("likeComment/{comment_id?}",  $commentControllerApi . CommentApiControllerInterface::API_COMMENT_LIKE);

    Route::post("likePaper/{paper_id}", $paperApiController . PaperApiControllerInterface::API_PAPER_ADD_LIKE);

    Route::get("detail/{paper_id}", $paperApiController . PaperApiControllerInterface::PAPER_DETAIL);

    Route::post("addCart", $cartApiController . CartApiControllerInterface::ADD_TO_CART);

    Route::get("cart", $cartApiController . CartApiControllerInterface::GET_CART);

    Route::delete("clearCart", $cartApiController . CartApiControllerInterface::CLEAR_CART);

    Route::delete('removeItem/{item_id}', $cartApiController . CartApiControllerInterface::REMOVE_CART_ITEM);

    Route::post('submitOrder', $cartApiController . CartApiControllerInterface::SUBMIT_ORDER);
});

Route::prefix(NotificationControllerInterface::PREFIX)->group(function () use ($notificationController) {
    Route::post("addFcm", $notificationController . NotificationControllerInterface::REGISTER_FCM);

    Route::get('pushMessage', $notificationController . NotificationControllerInterface::PUSH_NOTIFICATION);
});

Route::prefix('test')->group(function () {

    Route::get('constEx', function () {
        dd(ExtensionController::getConstants());
    });

    Route::post('mobile/upimage', "ExtensionController@uploadImageFromMobile");

    Route::get('upFirebaseComments/{paper_id}', "ManagerController@upFirebaseComments");

    Route::get('pullFirebaseComment', "ManagerController@pullFirebaseComment");

    Route::get('pullFirebasePaperLike', "ManagerController@pullFirebasePaperLike");

    Route::get('pullFirebaseComLike', "ManagerController@pullFirebaseComLike");

    Route::get('onesign', function () {
        OneSignalFacade::sendNotificationToAll( // đã chạy: https://github.com/berkayk/laravel-onesignal
            "Some Message",
            'http://localhost/laravel1/public/',
        );
        return 123;
    });
});

// https://viblo.asia/p/huong-dan-trien-khai-desgin-patterns-trong-laravel-Qpmle79rKrd
