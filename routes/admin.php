<?php

use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "adminhtml"], function () {
    $admin = "admin";

    Route::get("login", "AdminController@adminLogin")->name($admin."_login");

    Route::post("loginpost", "AdminController@loginPost")->name($admin."login_post");

    Route::get("/", "AdminController@home")->name($admin)->middleware("adminLogin");

    Route::prefix('paper')->middleware("adminLogin")->group(function () use ($admin) {

        Route::get("list", "PaperController@listPaper")->name($admin . "_paper_list");

        Route::get("create", "PaperController@createPaper")->name($admin . "_paper_create");

        Route::post("insert", "PaperController@insertPaper")->middleware("postPaper")->name($admin . "_paper_save");

        Route::get("edit/{paper_id}", "PaperController@editPaper")->name($admin."_paper_edit");

        Route::post("update/{paper_id}", "PaperController@updatePaper")->name($admin . "_paper_update");

        Route::delete("delete", "PaperController@deletePaper")->name($admin."_paper_delete");
    });

    Route::prefix('writer')->middleware("adminLogin")->group(function () use ($admin) {
        Route::get("/", "WriterController@listOfWriter")->name($admin . "_writer_list");

        Route::get("create", "WriterController@createWriter")->name($admin . "_writer_create");

        Route::post("insert", "WriterController@insertWriter")->name($admin . "_writer_insert");

        Route::get("edit/{writer_id}", "WriterController@editWriter")->name($admin . "_writer_edit");

        Route::post("update/{writer_id}", "WriterController@updateWriter")->name($admin . "_writer_update");

        Route::delete("delete", "WriterController@deleteWriter")->name($admin . "_writer_delete");
    });

    Route::prefix('file')->middleware("adminLogin")->group(function () use ($admin) {

        Route::group(['prefix' => 'manager'], function () {
            \UniSharp\LaravelFilemanager\Lfm::routes();
        });

        Route::get("/", "ImageController@listFile")->name($admin . "_file_list");

        Route::get("add", "ImageController@addFile")->name($admin . "_file_add");

        Route::post("save", "ImageController@saveFile")->name($admin . "_file_save");

        Route::delete("delete", "ImageController@deleteFile")->name($admin . "_file_delete");
    });

    Route::prefix('category')->middleware("adminLogin")->group(function () {
        Route::get("list", "CategoryController@listCategory")->name("category_admin_list");

        Route::get("create", "CategoryController@createCategory")->name("category_admin_create");

        Route::post("insert", "CategoryController@insertCategory")->name("category_admin_insert");

        Route::get("edit/{category_id}", "CategoryController@editCategory")->name("category_admin_edit");

        Route::post("update/{category_id}", "CategoryController@updateCategory")->name("category_admin_update");

        Route::any("delete/{category_id}", "CategoryController@deleteCategory")->name("category_admin_delete");

        Route::get("setup", "CategoryController@setupCategory")->name("category_top_setup");

        Route::post("setup/save", "CategoryController@setupSave")->name("category_setup_save");
    });

    Route::get("default", "AdminController@default")->name($admin."_default");
});
