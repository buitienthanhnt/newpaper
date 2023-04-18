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
    /**
     * @return string|bool
     */
    public function uploadImage(Request $request)
    {
        // dd(public_path());
        // $this->resize("");
        // exit();
        $save_folder = "public/images/writer";
        if ($file = $request->__get("image_post")) {
            try {
                // $upload_file = $file->store("public/images/writer");  // luu truc tiep
                $file_name = $file->getClientOriginalName();
                $file_type = $file->getClientOriginalExtension();
                $upload_name = random_int(1, 10000)."-".Str::random(8)."-".$file_name;

                $upload_file = $file->storeAs($save_folder, $upload_name);
                if (strpos($upload_file, "public") === 0) {
                    $upload_file = "storage/".explode("/", $upload_file, 2)[1];
                }
                if ($upload_file) {
                    $file_path = asset($upload_file);
                    // $this->resize($file_path);
                    return $file_path;
                }
            } catch (\Throwable $th) {
            //    throw \Exception::getMessage();
            }
        }
        return false;
    }

    public function resize($path)
    {
        $real_image_path = public_path("/storage/images/writer/135-0a3wlqNW-giay4.jpg");
        $image = Image::make($real_image_path);
        // asset("storage/images/writer/large.jpg");
        // $savedPath = public_path("/storage/images/resize/writer/135-0a3wlqNW-giay4.jpg");
        $image->fit(600, 600)->save(public_path("/images/resize/writer/135-0a3wlqNW-lager-giay4.jpg")); // luu y cac folder can co san.
        $image->fit(400, 400)->save(public_path("/images/resize/writer/135-0a3wlqNW-medium-giay4.jpg"));
        $image->fit(150, 150)->save(public_path("/images/resize/writer/135-0a3wlqNW-thumbnail-giay4.jpg"));
        return 'Done';
        // /var/www/html/laravel1/public/storage/images/writer/135-0a3wlqNW-giay4.jpg
    }
}
