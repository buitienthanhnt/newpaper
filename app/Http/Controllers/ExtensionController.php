<?php

namespace App\Http\Controllers;

use App\Console\Commands\RemoteRource;
use Thanhnt\Nan\Helper\DomHtml;
use App\Helper\ImageUpload;
use App\Models\Category;
use App\Models\Paper;
use App\Models\RemoteSourceHistory;
use App\Models\Writer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Thanhnt\Nan\Helper\RemoteSourceManager;

// https://www.php.net/manual/en/langref.php php
class ExtensionController extends Controller
{
    use DomHtml;
    use ImageUpload;

    protected $request;
    protected $paper;
    protected $category;
    protected $remoteSourceManager;

    public function __construct(
        Request $request,
        Paper $paper,
        Category $category,
        RemoteSourceManager $remoteSourceManager
    ) {
        $this->request = $request;
        $this->paper = $paper;
        $this->category = $category;
        $this->remoteSourceManager = $remoteSourceManager;
    }

    public function source(Request $request)
    {
        $value = $this->remoteSourceManager->source($request);
        if (!$value) {
            return redirect()->back()->with("error", "can not parse source!");
        } else {
            /**
             * get write for
             */
            $writers = Writer::all();
            $values = array_merge($value, [
                "category_option" => $this->category->category_tree_option(),
                "filemanager_url" => url("adminhtml/file/manager") . "?editor=tinymce5",
                "filemanager_url_base" => url("adminhtml/file/manager"),
                "writers" => $writers,
                "request_url" => $request->get("source_request")
            ]);

            return view("adminhtml.templates.papers.create", $values);
        }
    }

    /**
     * @param $doc                       // source for search
     * @param string $class_conten       // for main of conten
     * @param string $class_short_conten // for short conten
     * @return array
     */
    protected function getValueByClassName($doc, $class_conten, $class_short_conten)
    {
        $request = $this->request;
        $title = $this->getTitle($doc);
        $url_alias = str_replace([":", "'", '"', "“", "”", ",", ".", "·", " ", "|", "/", "\\"], "", $this->vn_to_str($title, 1));
        $short_conten_value = "";
        $conten = "";
        try {
            $short_conten = $this->findByXpath($doc, "class", $class_short_conten);
            $short_conten_value = $short_conten[0]->textContent;
        } catch (\Exception $e) {}

        try {
            $nodes = $this->findByXpath($doc, "class", $class_conten); // load content: (image error)
            $conten = $this->getNodeHtml($nodes[0]);
        } catch (\Exception $e) {}

        return [
            "title" => $title,
            "url_alias" => $url_alias,
            "short_conten" => $this->cut_str(trim($short_conten_value), 250, "..."),
            "conten" => $conten,
            "active" => $request->__get("active") ? true : false,
            "show" => $request->__get("show") ? true : false,
            "auto_hide" => $request->__get("auto_hide") ? true : false,
            "show_writer" => $request->__get("show_writer") ? true : false,
            "show_time" => $request->__get("show_time"),
            "image_path" => $request->__get("image_path") ?: "",
            "writer" => $request->get("writer", null)
        ];
    }

    function download()
    {
        $file = public_path() . "/vendor/app-release.apk";

        // $headers = array(
        //       'Content-Type: application/pdf',
        //     );

        return Response::download($file, 'app-release.apk');
    }

    function sendMail()
    {
        try {
            // Mail::to('buisuphu01655@gmail.com')->send(new UserEmail());  // php artisan make:mail UserEmail
            Mail::send(
                'welcome',
                [],
                function ($message) {
                    $message->from('buitienthanhnt@gmail.com', "tha nan");
                    $message->to("thanh.bui@jmango360.com", 'user1');
                    $message->subject("demo by send mail laravel newpaper");
                }
            );
        } catch (\Throwable $th) {
            //throw $th;
            echo ($th->getMessage());
            return;
        }
        return 123;
    }

    /**
     * upload image from mobile(cli4)
     */
    function uploadImageFromMobile(Request $request)
    {
        $res_data = null;
        if ($files = $request->__get("upload_file")) {
            // multi files:
            foreach ($files as $file) {
                $image_upload_path = '';
                $image_upload_path = $this->uploadImage($file, "public/images/cli4Mb");
                $res_data[] = $image_upload_path;
            }
            return [
                'path' => $res_data,
                'code' => 200
            ];
        }
        return [
            'path' => $res_data,
            'code' => 500
        ];
    }
}
