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

Route::get("/{category}.htm", function($category){
    return $category;

})->name("front_category");

?>
