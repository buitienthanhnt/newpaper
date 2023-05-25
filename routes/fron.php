<?php

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

Route::get("login", function(){
    return view("frontend/templates/login");
});

Route::post("loginpost", function(){
    return 123;
})->name("login_post");

Route::get("new_account", function(){
    return view("frontend/templates/new_account");
})->name("new_account");

Route::post("account_post", function(){
    return 123;
})->name("account_post");

Route::get("/{category}.htm", "ManagerController@categoryView")->name("front_category");

Route::get("{alias?}_{page}.html", "ManagerController@pageDetail")->name("front_page_detail");

Route::get("tags/{value}", "ManagerController@tagView")->name("front_tag_view");

?>
