<?php

namespace App\Http\Controllers;

use App\Helper\ImageUpload;
use App\Models\ImageModel;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    //
    use ImageUpload;

    protected $request;

    public function __construct(
        Request $request
    )
    {
        $this->request = $request;
    }

    public function deleteFile()
    {
        // $url = "http://laravel1.com/storage/images/all/5299-B5LkucU8-TTT-3840-1682000804-1682040272.jpg";
        // $file_path = $this->urlpath_to_real($url);

        // $url = "storage/images/all/5299-B5LkucU8-TTT-3840-1682000804-1682040272.jpg";
        // $file_path = $this->real_to_url($url);

        $url = $this->request->__get("path");
        $this->delete_file($url);
        // return $file_path;
    }

    public function addFile()
    {
        return view("adminhtml.templates.files.add");
    }

    public function saveFile()
    {

        $request = $this->request;
        $save_image = $request->__get("save_image");
        if ($save_image) {
            $image_saves = $this->uploadImage($save_image, "public/images/all", "images/resize/all");
            dd($image_saves);
            ImageModel::firstOrCreate([
                "name" => $request->__get("file_name"),
                "file_name" => $image_saves["file_name"] ?? null,
                "file_path" => $image_saves["file_path"] ?? null,
                "file_url" => $image_saves["url_path"] ?? null,
                "resize_name" => $image_saves["resize_name"] ?? null,
                "resize_path" => $image_saves["resize_path"] ?? null,
                "resize_url" => $image_saves["resize_url"] ?? null
            ]);
            
        }
    }
}
