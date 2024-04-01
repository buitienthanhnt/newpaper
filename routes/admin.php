<?php

use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "adminhtml"], function () {
    $admin = "admin";

    Route::get("/", "AdminController@home")->name($admin)->middleware("adminLogin");

    Route::get("login", "AdminController@adminLogin")->name($admin . "_login");

    Route::post("loginpost", "AdminController@loginPost")->name($admin . "_login_post");

    Route::get('logout', "AdminController@logout")->name($admin . "_logout");

    Route::get("extension", "ExtensionController@source")->name($admin . "_source");

    Route::prefix('rule')->middleware(["adminLogin", "AdminPermission"])->group(function () use ($admin) {
        Route::get('/', "RuleController@list")->name($admin . "_rule_list");

        Route::get("all", "RuleController@allRules")->name($admin . "_rule_all");

        Route::get('/create', "RuleController@create")->name($admin . "_rule_create");

        Route::post('/insert', "RuleController@insert")->name($admin . "_rule_insert");

        Route::get("edit", "RuleController@edit")->name($admin . "_rule_edit");

        Route::delete("delete", "RuleController@delete")->name($admin . "_rule_delete");

        Route::any("addChildren/{parent_id?}", "RuleController@addChildren")->name($admin . "_rule_add_children");
    });

    Route::prefix('permission')->middleware(["adminLogin", "AdminPermission"])->group(function () use ($admin) {
        Route::get('/', "PermissionController@list")->name($admin . "_permission_list");

        Route::get("create", "PermissionController@create")->name($admin . "_permission_create");

        Route::post("insert", "PermissionController@insert")->name($admin . "_permission_insert");

        Route::get("edit/{permission_id}", "PermissionController@edit")->name($admin . "_permission_edit");

        Route::post("update", "PermissionController@update")->name($admin . "_permission_update");

        Route::delete("delete", "PermissionController@delete")->name($admin . "_permission_delete");

        Route::get("detail/{permission_id}", "PermissionController@detail")->name($admin . "_permission_detail");
    });

    Route::prefix('paper')->middleware(["adminLogin", "AdminPermission"])->group(function () use ($admin) {

        Route::get("/", "PaperController@listPaper")->name($admin . "_paper_list");

        Route::get("create", "PaperController@createPaper")->name($admin . "_paper_create");

        Route::get("newbyurl", "PaperController@newByUrl")->name($admin . "_new_by_url");

        Route::post("insert", "PaperController@insertPaper")->middleware("postPaper")->name($admin . "_paper_save");

        Route::get("edit/{paper_id}", "PaperController@editPaper")->name($admin . "_paper_edit");

        Route::post("update/{paper_id}", "PaperController@updatePaper")->name($admin . "_paper_update");

        Route::delete("delete", "PaperController@deletePaper")->name($admin . "_paper_delete");
    });

    Route::prefix('writer')->middleware(["adminLogin", "AdminPermission"])->group(function () use ($admin) {
        Route::get("/", "WriterController@listOfWriter")->name($admin . "_writer_list");

        Route::get("create", "WriterController@createWriter")->name($admin . "_writer_create");

        Route::post("insert", "WriterController@insertWriter")->name($admin . "_writer_insert");

        Route::get("edit/{writer_id}", "WriterController@editWriter")->name($admin . "_writer_edit");

        Route::post("update/{writer_id}", "WriterController@updateWriter")->name($admin . "_writer_update");

        Route::delete("delete", "WriterController@deleteWriter")->name($admin . "_writer_delete");
    });

    Route::prefix('file')->middleware(["adminLogin", "AdminPermission"])->group(function () use ($admin) {

        Route::group(['prefix' => 'manager'], function () {
            \UniSharp\LaravelFilemanager\Lfm::routes();
        });

        Route::get("/", "ImageController@listFile")->name($admin . "_file_list");

        Route::get("add", "ImageController@addFile")->name($admin . "_file_add");

        Route::post("save", "ImageController@saveFile")->name($admin . "_file_save");

        Route::delete("delete", "ImageController@deleteFile")->name($admin . "_file_delete");
    });

    Route::prefix('category')->middleware(["adminLogin", "AdminPermission"])->group(function () {
        Route::get("list", "CategoryController@listCategory")->name("category_admin_list");

        Route::get("create", "CategoryController@createCategory")->name("category_admin_create");

        Route::post("insert", "CategoryController@insertCategory")->name("category_admin_insert");

        Route::get("edit/{category_id}", "CategoryController@editCategory")->name("category_admin_edit");

        Route::post("update/{category_id}", "CategoryController@updateCategory")->name("category_admin_update");

        Route::post("delete/{category_id}", "CategoryController@deleteCategory")->name("category_admin_delete");

        Route::get("setup", "CategoryController@setupCategory")->name("category_top_setup");

        Route::post("setup/save", "CategoryController@setupSave")->name("category_setup_save");
    });

    Route::prefix('user')->middleware(["adminLogin", "AdminPermission"])->group(function () use ($admin) {
        Route::get("listUser", "AdminUserController@listUser")->name($admin . "_user_list");

        Route::get("editUser/{user_id}", "AdminUserController@editUser")->name($admin . "_user_edit");

        Route::post("updateUser/{user_id}", "AdminUserController@updateUser")->name($admin . "_user_update");

        Route::get("createUser", "AdminUserController@createUser")->name($admin . "_user_create");

        Route::post("insetUser", "AdminUserController@insertUser")->name($admin . "_insert_user");

        Route::delete("deleteUser", "AdminUserController@deleteUser")->name($admin . "_user_delete");
    });

    Route::prefix("config")->middleware(["adminLogin", "AdminPermission"])->group(function () use($admin) {
        Route::get("/", "ConfigController@list")->name($admin."_config_list");

        Route::get("create", "ConfigController@create")->name($admin."_config_create");

        Route::post("insert", "ConfigController@insert")->name($admin."_config_insert");

        Route::post("update", "ConfigController@update")->name($admin."_config_update");

        Route::delete("delete", "ConfigController@delete")->name($admin."_config_delete");
    });

    Route::prefix('firebase')->middleware(["adminLogin", 'AdminPermission'])->group(function() use($admin){
        Route::get('/', "FirebaseController@dashboard")->name($admin."_firebase");

        Route::post('addPaper', "FirebaseController@addPaper")->name($admin."_firebase_addPaper");

        Route::delete('deletePaper', "FirebaseController@deletePaper")->name($admin."_firebase_deletePaper");

        Route::get('fireStore', "FirebaseController@fireStore")->name($admin."_firebase_fireStore");

        Route::get('topCategory', "FirebaseController@upCategoryTop")->name($admin."_upCategoryTop");

        Route::get('homeInfo', "FirebaseController@info")->name($admin."_firebase_homeInfo");

        Route::get('setupHome', "FirebaseController@setupHome")->name($admin."_firebase_setupHome");

        Route::post('upFirebase/home', "FirebaseController@upHomeInfo")->name($admin."_firebase_upDefaultHome");
    });

    Route::get("default", "AdminController@default")->name($admin."_default");
});
