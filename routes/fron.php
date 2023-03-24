<?php

use Illuminate\Support\Facades\Route;

Route::get("/", function(){
    return view("frontend/templates/homeconten");
});

Route::get("basepage", function(){
    return view("frontend/templates/home");
});

Route::get("page_str", function(){
    return view("frontend/templates/pagestr");
});

Route::get("home", function ()
{
       return view("frontend/templates/homepage");
})

?>
