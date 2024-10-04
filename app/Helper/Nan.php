<?php

namespace App\Helper;

use Illuminate\Support\Facades\File;

/**
 *
 */
trait Nan
{
    public function create_folder(string $path_folder, $permission = 0777, $recursive = true)
    {
        try {
            $new_path_folder = $this->public_storage_path($path_folder);
            if (!is_dir($new_path_folder)) {
                File::makeDirectory($new_path_folder, $permission, $recursive);
            }
        } catch (\Throwable $th) {
            return false;
        }
        return true;
    }

    public function public_storage_path($path)
    {
        return public_path($path);
    }

    public static function pageCategoryTable()
    {
        return "page_category";
    }

    public function pageTagTable()
    {
        return "page_tag";
    }

    public function pagePriceTable()
    {
        return "price";
    }

    public function permissionRulesTable()
    {
        return "permission_rules";
    }

    public static function userPermissionTable(): string
    {
        return "admin_user_permissions";
    }

    public static function coreConfigTable(): string
    {
        return "core_config";
    }
}
