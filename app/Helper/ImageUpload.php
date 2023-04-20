<?php

namespace App\Helper;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

/**
 * php artisan storage:link
 */
trait ImageUpload
{
    use Nan;

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @param string $save_folder
     * @param string $resize_path
     * @return string|bool
     */
    public function uploadImage(\Symfony\Component\HttpFoundation\File\UploadedFile $file, $save_folder = "public/images", $resize_path = null)
    {
        if ($file) {
            try {
                // $upload_file = $file->store("public/images/writer");  // lưu trực tiếp với tên file tải lên.
                $file_name = $file->getClientOriginalName();
                $file_type = $file->getClientOriginalExtension();
                $upload_name = random_int(1, 10000)."-".Str::random(8)."-".$file_name;

                $upload_file = $file->storeAs($save_folder, $upload_name);
                if (strpos($upload_file, "public") === 0) {
                    $upload_file = "storage/".explode("/", $upload_file, 2)[1];
                }
                if ($upload_file) {
                    $file_path = asset($upload_file);
                    if ($resize_path) {
                        return $this->resize($resize_path, $upload_file);
                    }
                    return $file_path;
                }
            } catch (\Throwable $th) {
                return false;
            }
        }
        return false;
    }

    public function resize(string $save_folder = null, string $file_path, $width=400, $height=400, $file_name = null)
    {
        if (!$save_folder) {$save_folder = "/storage/resize";}else {
            $save_folder = "/storage/".$save_folder;
        }
        if (!$this->create_folder($save_folder)) {return;}

        if (!$file_name) {
            $file_name = Str::random(12).str_replace(dirname($file_path)."/", "",$file_path);
        }

        $real_image_path = $this->public_storage_path($file_path);
        $image = Image::make($real_image_path);
        $save_file_name = $this->public_storage_path($save_folder)."/".$file_name;

        $image->fit($width, $height)->save($save_file_name);  // lưu ý các folder phải tồn tại(nó không tạo folder tự động)
        return ["file_name" => $file_name, "real_file_path" => $save_file_name, "file_path" => asset($save_folder."/".$file_name)];
    }

}
