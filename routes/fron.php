<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManagerControllerInterface;
use App\Http\Controllers\UserControllerInterface;
use App\Http\Controllers\PaperFrontControllerInterface;

$managerController = ManagerControllerInterface::CONTROLLER_NAME.'@';

Route::get("/", $managerController.ManagerControllerInterface::HOME_PAGE)->name("/");

Route::get("/{category}.htm", $managerController.ManagerControllerInterface::FORNT_CATEFORY_VIEW)->name("front_category");

Route::get("{alias?}_{page}.html", $managerController.ManagerControllerInterface::FRONT_PAPER_DETAIL)->name("front_paper_detail");

Route::get("tags/{value}", $managerController.ManagerControllerInterface::FRONT_TAG_VIEW)->name("front_tag_view");

Route::get("search", $managerController.ManagerControllerInterface::FRONT_SEARCH)->name("front_search_all");

Route::get("loadMore", $managerController.ManagerControllerInterface::LOAD_MORE)->name("front_load_more");

Route::prefix(UserControllerInterface::PREFIX)->group(function () {
    $userController = UserControllerInterface::CONTROLLER_NAME.'@';

    Route::get("register", $userController.UserControllerInterface::FRONT_REGISTER_ACCOUNT)->name("front_register_account");

    Route::post('registerPost', $userController.UserControllerInterface::FRONT_ADD_ACCOUNT)->name("front_add_account");

    Route::get('login', $userController.UserControllerInterface::FRONT_LOGIN_PAGE)->name("front_login_page");

    Route::post("loginPost", $userController.UserControllerInterface::FRONT_LOGIN_POST)->name("front_login_post");

    Route::get('detail', $userController.UserControllerInterface::FRONT_USER_DETAIL)->name("front_user_detail");

    Route::get('logout', $userController.UserControllerInterface::FRONT_USER_LOGOUT)->name("front_user_logout");

//    Route::get('edit/{id}', "UserController@loginPage")->name("user_edit");

//    Route::put('update/{id}', "UserController@loginPage")->name("user_update");
});

Route::prefix(PaperFrontControllerInterface::PREFIX)->group(function () {
    $paperFrontController = PaperFrontControllerInterface::CONTROLLER_NAME.'@';

    Route::post("addLike/{paper_id}", $paperFrontController.PaperFrontControllerInterface::FRONT_PAPER_ADD_LIKE)->name("front_paper_add_like");

    Route::get('commentContent/{paper_id}/{p}', $paperFrontController.PaperFrontControllerInterface::FRONT_PAPER_COMMENTS)->name('front_paper_comment');

    Route::post("comment/{paper_id}", $paperFrontController.PaperFrontControllerInterface::FRONT_PAPER_ADD_COMMENT)->name("front_paper_add_comment");

    Route::post('commentReply/{comment_id?}', $paperFrontController.PaperFrontControllerInterface::FRONT_PAPER_REPLY_COMMENT)->name("front_paper_reply_comment");

    Route::post("commentLike/{comment_id?}", $paperFrontController.PaperFrontControllerInterface::FRONT_COMMENT_LIKE)->name("front_comment_like");

    Route::post("addCart", $paperFrontController.PaperFrontControllerInterface::FRONT_ADD_CART)->name('front_add_cart');

    Route::get("cart", $paperFrontController.PaperFrontControllerInterface::FRONT_VIEW_CART)->name('front_view_cart');

    Route::any("deleteItem/{item_id}", $paperFrontController.PaperFrontControllerInterface::FRONT_DELETE_CART_ITEM)->name('front_delete_cart_item');

    Route::any("clearCart", $paperFrontController.PaperFrontControllerInterface::FRONT_CLEAR_CART)->name('front_clear_cart');

    Route::get('checkout', $paperFrontController.PaperFrontControllerInterface::FRONT_CHECKOUT)->name("front_checkout");

    Route::Post('checkoutPost', $paperFrontController.PaperFrontControllerInterface::FRONT_CHECKOUT_POST)->name("front_checkout_post");

    Route::get('byType/{type}', $paperFrontController.PaperFrontControllerInterface::FRONT_PAPER_BY_TYPE)->name("front_paper_by_type");

    Route::get('morePaperByType/{type}', $paperFrontController.PaperFrontControllerInterface::FRONT_MORE_PAPER_BY_TYPE)->name("front_more_paper_by_type");
});

Route::prefix('test')->group(function (): void {
    Route::get("obser", "ExtensionController@obser");
});

Route::get("download/file", "ExtensionController@download")->name('download');

Route::get("sendmail", "ExtensionController@sendMail");

Route::get('verifyPassword', "UserController@verifyPassword");

Route::get('upLoadImage', "UserController@upLoadImage");

Route::get("playSound", function () {
    $sound = <<<SOUND
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Document</title>
        </head>
        <body>
            <audio controls>
                <source src="http://192.168.100.156/newpaper/public/Hinh_Bong_Que_Nha.mp3" type="audio/ogg">
            </audio>
        </body>
        </html>
    SOUND;
    return ($sound);
});
