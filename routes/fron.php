<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

Route::get("/", "ManagerController@homePage")->name("/");

Route::get("basepage", function(){
    return view("frontend/templates/home");
});

Route::get("page_str", function(){
    return view("frontend/templates/pagestr");
});

Route::get("home", function (){
    return view("frontend/templates/homepage");
});

Route::get("playSound", function(){
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
    return($sound);
});

Route::get("download/file", "ExtensionController@download")->name('download');

Route::prefix("user")->group(function(){

    Route::get("create", "UserController@createAccount")->name("account_create");

    Route::post('add', "UserController@accountAdd")->name("account_add");

    Route::get('login', "UserController@loginPage")->name("user_login");

    Route::get('detail', "UserController@detail")->name("user_detail");

    Route::get('edit/{id}', "UserController@loginPage")->name("user_edit");

    Route::put('update/{id}', "UserController@loginPage")->name("user_update");

    Route::post("loginPost", "UserController@login")->name("login_post");

    Route::get('logout', "UserController@logout")->name("user_logout");
});

Route::get("/{category}.htm", "ManagerController@categoryView")->name("front_category");

Route::get("{alias?}_{page}.html", "ManagerController@pageDetail")->name("front_page_detail");

Route::get("tags/{value}", "ManagerController@tagView")->name("front_tag_view");

Route::get("load_more", "ManagerController@load_more")->name("load_more");

Route::prefix('paper')->group(function () {
    Route::post("comment/{paper_id}", "PaperController@addComment")->name("paper_add_comment");

    Route::post('commentReply/{comment_id?}', "PaperController@replyComment")->name("paper_reply_comment");

    Route::post("like/{comment_id?}", "PaperController@like")->name("paper_like");
});

// php artisan cache:table
// php artisan migrate
Route::prefix('cache')->group(function () {
    Route::get('create', function () {
        $status = Cache::put('tha', [
            'a' => 123,
            'b' => 'demo data'
        ]);
        return $status;
    });

    Route::get('get', function () {
        $value = Cache::get('tha', 123123123);
        return $value;
    });
});

Route::get("sendmail", "ExtensionController@sendMail");

Route::get('firebase', "UserController@addFireBaseData");
