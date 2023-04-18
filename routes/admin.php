<?php

use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "adminhtml"], function(){
    $admin = "admin";

    Route::get("/home", function(){
        return view("adminhtml/templates/home");
    })->name("admin");

    Route::get("default", function(){
        return view("adminhtml/templates/default");
    });

    Route::prefix('paper')->group(function ()use($admin) {

        Route::get("list", function(){

        })->name($admin."_paper_list");

        Route::get("create", function(){

        })->name("paper_create");

        Route::post("insert", function(){

        })->name("paper_insert");

        Route::get("paper_edit/{paper_id}", function(){

        })->name("paper_edit");

        Route::put("update/{paper_id}", function(){

        })->name("paper_update");

        Route::delete("delete/{paper_id}", function(){

        })->name("paper_delete");

    });

    Route::prefix('writer')->group(function ()use($admin) {
        Route::get("/", "WriterController@listOfWriter")->name($admin."_writer_list");

        Route::get("create", "WriterController@createWriter")->name($admin."_writer_create");

        Route::post("insert", "WriterController@insertWriter")->name($admin."_writer_insert");
    });

    Route::prefix('category')->group(function () {
        Route::get("list", "CategoryController@listCategory")->name("category_admin_list");

        Route::get("create", "CategoryController@createCategory")->name("category_admin_create");

        Route::post("insert","CategoryController@insertCategory")->name("category_admin_insert");

        Route::get("edit/{category_id}", "CategoryController@editCategory")->name("category_admin_edit");

        Route::post("update/{category_id}", "CategoryController@updateCategory")->name("category_admin_update");

        Route::any("delete/{category_id}", "CategoryController@deleteCategory")->name("category_admin_delete");

        Route::get("setup", "CategoryController@setupCategory")->name("category_top_setup");

        Route::post("setup/save", "CategoryController@setupSave")->name("category_setup_save");
    });

});
