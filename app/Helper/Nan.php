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

    public function pageCategoryTable()
    {
        return "page_category";
    }

    public function pageTagTable()
    {
        return "page_tag";
    }
}
