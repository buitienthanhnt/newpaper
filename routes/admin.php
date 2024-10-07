<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminControllerInterface;
use App\Http\Controllers\WriterControllerInterface;
use App\Http\Controllers\CategoryControllerInterface;
use App\Http\Controllers\PaperControllerInterface;
use App\Http\Controllers\PermissionControllerInterface;
use App\Http\Controllers\AdminUserControllerInterface;
use App\Http\Controllers\ConfigControllerInterface;
use App\Http\Controllers\OrderControllerInterface;
use App\Http\Controllers\ImageControllerInterface;
use App\Http\Controllers\FirebaseControllerInterface;

Route::group(["prefix" => "adminhtml"], function () {
    $admin = AdminControllerInterface::ADMIN;
    $admin_controller = AdminControllerInterface::CONTROLLER_NAME.'@';

    Route::get("/", $admin_controller.AdminControllerInterface::ADMIN_HOME)->name($admin)->middleware("adminLogin");

    Route::get("login", $admin_controller.AdminControllerInterface::ADMIN_LOGIN)->name($admin . "_login");

    Route::post("loginpost", $admin_controller.AdminControllerInterface::LOGIN_POST)->name($admin . "_login_post");

    Route::get('logout', $admin_controller.AdminControllerInterface::LOGOUT)->name($admin . "_logout");

    $middlewareAdminPermission = ["adminLogin", "AdminPermission"];

    Route::prefix(WriterControllerInterface::PREFIX)->middleware($middlewareAdminPermission)->group(function () use ($admin) {
        $writerController = WriterControllerInterface::CONTROLLER_NAME.'@';

        Route::get("/", $writerController.WriterControllerInterface::LIST_WRITER)->name($admin . "_list_writer");

        Route::get("create", $writerController.WriterControllerInterface::CREATE_WRITER)->name($admin . "_create_writer");

        Route::post("insert", $writerController.WriterControllerInterface::INSERT_WRITER)->name($admin . "_insert_writer");

        Route::get("edit/{writer_id}", $writerController.WriterControllerInterface::EDIT_WRITER)->name($admin . "_edit_writer");

        Route::post("update/{writer_id}", $writerController.WriterControllerInterface::UPDATE_WRITER)->name($admin . "_update_writer");

        Route::delete("delete", $writerController.WriterControllerInterface::DELETE_WRITER)->name($admin . "_delete_writer");
    });

    Route::prefix(CategoryControllerInterface::PREFIX)->middleware($middlewareAdminPermission)->group(function () use($admin) {
        $categoryController = CategoryControllerInterface::CONTROLLER_NAME.'@';

        Route::get("/", $categoryController.CategoryControllerInterface::LISTCATEGORY)->name($admin ."_list_category");

        Route::get("create", $categoryController.CategoryControllerInterface::CREATE_CATEGORY)->name($admin ."_create_category");

        Route::post("insert", $categoryController.CategoryControllerInterface::INSERT_CATEGORY)->name($admin ."_insert_category");

        Route::get("edit/{category_id}", $categoryController.CategoryControllerInterface::EDIT_CATEGORY)->name($admin ."_edit_category");

        Route::post("update/{category_id}", $categoryController.CategoryControllerInterface::UPDATE_CATEGORY)->name($admin ."_update_category");

        Route::post("delete/{category_id}", $categoryController.CategoryControllerInterface::DELETE_CATEGORY)->name($admin ."_delete_category");

        Route::get("setup", $categoryController.CategoryControllerInterface::SETUP_CATEGORY)->name($admin ."_setup_category");

        Route::post("setup/save", $categoryController.CategoryControllerInterface::SETUP_SAVE)->name($admin ."_setup_save_category");
    });

    Route::prefix(PaperControllerInterface::PREFIX)->middleware($middlewareAdminPermission)->group(function () use ($admin) {
        $paperController = PaperControllerInterface::CONTROLLER_NAME.'@';

        Route::get("/", $paperController.PaperControllerInterface::LIST_PAPER)->name($admin . "_list_paper");

        Route::get("create", $paperController.PaperControllerInterface::CREATE_PAPER)->name($admin . "_create_paper");

        Route::post("insert", $paperController.PaperControllerInterface::INSERT_PAPER)->middleware("postPaper")->name($admin . "_save_paper");

        Route::get("edit/{paper_id}", $paperController.PaperControllerInterface::EDIT_PAPER)->name($admin . "_edit_paper");

        Route::post("update/{paper_id}", $paperController.PaperControllerInterface::UPDATE_PAPER)->name($admin . "_update_paper");

        Route::delete("delete", $paperController.PaperControllerInterface::DELETE_PAPER)->name($admin . "_delete_paper");

        Route::get("newbyurl", $paperController.PaperControllerInterface::NEW_BY_URL)->name($admin . "_new_by_url");

        Route::get("sourcePaper", $paperController.PaperControllerInterface::SOURCE_PAPER)->name($admin . "_source_paper");
    });

    Route::prefix(PermissionControllerInterface::PREFIX)->middleware($middlewareAdminPermission)->group(function () use ($admin) {
        $permissionController = PermissionControllerInterface::CONTROLLER_NAME.'@';

        Route::get('/', $permissionController.PermissionControllerInterface::LIST_PERMISSION)->name($admin . "_list_permission");

        Route::get("create", $permissionController.PermissionControllerInterface::CREATE_PREMISSION)->name($admin . "_create_permission");

        Route::post("insert", $permissionController.PermissionControllerInterface::INSERT_PERMISSION)->name($admin . "_insert_permission");

        Route::get("edit/{permission_id}", $permissionController.PermissionControllerInterface::EDIT_PERMISSION)->name($admin . "_edit_permission");

        Route::post("update", $permissionController.PermissionControllerInterface::UPDATE_PERMISSION)->name($admin . "_update_permisison");

        Route::delete("delete", $permissionController.PermissionControllerInterface::DELETE_PERMISSION)->name($admin . "_delete_permission");

        Route::get("detail/{permission_id}", $permissionController.PermissionControllerInterface::DETAIL_PERMISSION)->name($admin . "_detail_permission");
    });

    Route::prefix(AdminUserControllerInterface::PREFIX)->middleware($middlewareAdminPermission)->group(function () use ($admin) {
        $adminUserController = AdminUserControllerInterface::CONTROLLER_NAME.'@';

        Route::get("listUser", $adminUserController.AdminUserControllerInterface::LIST_ADMIN_USER)->name($admin . "_list_user");

        Route::get("createUser", $adminUserController.AdminUserControllerInterface::CREATE_ADMIN_USER)->name($admin . "_create_user");

        Route::post("insetUser", $adminUserController.AdminUserControllerInterface::INSERT_ADMIN_USER)->name($admin . "_insert_user");

        Route::get("editUser/{user_id}", $adminUserController.AdminUserControllerInterface::EDIT_ADMIN_USER)->name($admin . "_edit_user");

        Route::post("updateUser/{user_id}", $adminUserController.AdminUserControllerInterface::UPDATE_ADMIN_USER)->name($admin . "_update_user");

        Route::delete("deleteUser", $adminUserController.AdminUserControllerInterface::DELETE_ADMIN_USER)->name($admin . "_delete_user");
    });

    Route::prefix(ConfigControllerInterface::PREFIX)->middleware($middlewareAdminPermission)->group(function () use ($admin) {
        $configController = ConfigControllerInterface::CONTROLLER_NAME.'@';

        Route::get("/", $configController.ConfigControllerInterface::LIST_CONFIG)->name($admin . "_list_config");

        Route::get("create", $configController.ConfigControllerInterface::CREATE_CONFIG)->name($admin . "_create_config");

        Route::post("insert", $configController.ConfigControllerInterface::INSERT_CONFIG)->name($admin . "_insert_config");

        Route::post("update", $configController.ConfigControllerInterface::UPDATE_CONFIG)->name($admin . "_update_config");

        Route::delete("delete", $configController.ConfigControllerInterface::DELETE_CONFIG)->name($admin . "_delete_config");
    });

    Route::prefix(OrderControllerInterface::PREFIX)->middleware($middlewareAdminPermission)->group(function () use ($admin) {
        $orderController = OrderControllerInterface::CONTROLLER_NAME.'@';

        Route::get("/", $orderController.OrderControllerInterface::LIST_ORDER)->name($admin . "_list_order");

        Route::get("info/{order_id}", $orderController.OrderControllerInterface::DETAIL_ORDER)->name($admin."_detail_order");
    });

    Route::prefix(ImageControllerInterface::PREFIX)->middleware($middlewareAdminPermission)->group(function () use ($admin){
        $imageController = ImageControllerInterface::CONTROLLER_NAME.'@';

        Route::get("/", $imageController.ImageControllerInterface::LIST_FILE)->name($admin . "_list_file");

        Route::get("add", $imageController.ImageControllerInterface::ADD_FILE)->name($admin . "_add_file");

        Route::post("save", $imageController.ImageControllerInterface::SAVE_FILE)->name($admin . "_save_file");

        Route::delete("delete", $imageController.ImageControllerInterface::DELETE_FILE)->name($admin . "_delete_file");

        Route::group(['prefix' => 'manager'], function () {
            \UniSharp\LaravelFilemanager\Lfm::routes();
        });
    });

    Route::prefix(FirebaseControllerInterface::PREFIX)->middleware($middlewareAdminPermission)->group(function () use ($admin) {
        $firebaseController = FirebaseControllerInterface::CONTROLLER_NAME.'@';

        Route::get('/', $firebaseController.FirebaseControllerInterface::DASH_BOARD)->name($admin . "_firebase_dashboard");

        Route::post('uploadPaper', $firebaseController.FirebaseControllerInterface::FIREBASE_UPLOAD_PAPER)->name($admin . "_firebase_upload_paper");

        Route::delete('deletePaper', $firebaseController.FirebaseControllerInterface::FIREBASE_DELETE_PAPER)->name($admin . "_firebase_delete_paper");

        Route::get('setupHome', $firebaseController.FirebaseControllerInterface::FIREBASE_SETUP_HOME_INFO)->name($admin . "_firebase_setup_home_info");

        Route::post('uploadHomeInfo', $firebaseController.FirebaseControllerInterface::FIREBASE_UPLOAD_HOME_INFO)->name($admin . "_firebase_upload_home_info");

        Route::post('uploadCategoryTop', $firebaseController.FirebaseControllerInterface::FIREBASE_UPLOAD_CATEGORY_TOP)->name($admin . "_firebase_upload_category_top");

        Route::post('uploadCategoryTree', $firebaseController.FirebaseControllerInterface::FIREBASE_UPLOAD_CATEGORY_TREE)->name($admin . "_firebase_upload_category_tree");

    });

    Route::get("default", $admin_controller.AdminControllerInterface::ADMIN_DEFAULT)->name($admin . "_default");
});
