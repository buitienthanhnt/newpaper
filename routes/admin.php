<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::group(["prefix" => "adminhtml"], function(){

    Route::get("/", function(){
        return view("adminhtml/templates/home");
    })->name("admin");

    Route::get("default", function(){
        return view("adminhtml/templates/default");
    });

    Route::prefix('paper')->group(function () {

        Route::get("list", function(){

        })->name("paper_list");

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

    Route::prefix('category')->group(function () {
        Route::get("list", "CategoryController@listCategory")->name("category_admin_list");

        Route::get("create", "CategoryController@createCategory")->name("category_admin_create");

        Route::post("insert","CategoryController@insertCategory")->name("category_admin_insert");

        Route::get("edit/{category_id}", "CategoryController@editCategory")->name("category_admin_edit");

        Route::put("update/{category_id}", function(){

        })->name("category_admin_update");

        Route::any("delete/{category_id}", "CategoryController@deleteCategory")->name("category_admin_delete");
    });

});
