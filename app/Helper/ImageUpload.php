<?php

namespace App\Helper;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

/**
 *
 */
trait ImageUpload
{
    /**
     * @return string|bool
     */
    public function uploadImage(Request $request)
    {
        // echo public_path();
        // exit();
        if ($file = $request->__get("image_post")) {
            try {
                $upload_file = $file->store("public/images/writer");
                if (strpos($upload_file, "public") === 0) {
                    $upload_file = "storage/".explode("/", $upload_file, 2)[1];
                }
                if ($upload_file) {
                    $file_path = asset($upload_file);
                    $this->resize($file_path);
                    return $file_path;
                }
            } catch (\Throwable $th) {
                throw $th->__toString();
            }
        }
        return false;
    }

    public function resize($path)
    {
        $image = Image::make($path);
        // asset("storage/images/writer/large.jpg");
        $image->fit(600, 600)->save(asset("storage/images/writer/large.jpg"));
        $image->fit(400, 400)->save(asset("storage/images/writer/medium.jpg"));
        $image->fit(150, 150)->save(asset("storage/images/writer/thumbnail.jpg"));
        return 'Done';
    }
}
