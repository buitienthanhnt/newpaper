<?php

use Illuminate\Http\Request;
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

Route::get("testJson", function ()
{
    echo(json_encode([
        "a" => 1,
        "b" => 2,
        "c" => "asd"
    ]));
})

?>
