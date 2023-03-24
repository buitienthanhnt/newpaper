<?php

use Illuminate\Support\Facades\Route;

Route::get("/", function(){
    return view("frontend/templates/home");
});

Route::get("homepage", function(){
    return view("frontend/templates/homepage");
});

Route::get("page_str", function(){
    return view("frontend/templates/pagestr");
});

Route::get("ajax", function(){
    return view("frontend/templates/home");
});

Route::post("ajax_post", function(){
    echo json_encode(["a" => 13123]);
    // return view("frontend/templates/home");
})->name("ajax_post")

?>
