<?php

use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "adminhtml"], function(){

    Route::get("/", function(){
        return view("adminhtml/templates/home");
    });

});


?>
