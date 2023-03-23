<?php

use Illuminate\Support\Facades\Route;

Route::get("/", function(){
    return view("frontend/templates/home");
});

Route::get("homepage", function(){
    return view("frontend/templates/homepage");
});

Route::get("page_str", function(){
    $a = 123;
    return view("frontend/templates/pagestr", ['a' => $a]);
})

?>
