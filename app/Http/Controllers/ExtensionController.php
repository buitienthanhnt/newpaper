<?php

namespace App\Http\Controllers;

use App\Helper\DomHtml;
use App\Helper\ImageUpload;
use App\Models\Category;
use App\Models\Paper;
use App\Models\RemoteSourceHistory;
use App\Models\Writer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;

// https://www.php.net/manual/en/langref.php php
class ExtensionController extends Controller
{
    use DomHtml;
    use ImageUpload;

    protected $request;
    protected $paper;
    protected $category;

    const SOURCE = [
        "soha.vn"           => "get_soha_value",
        "vietnamnet.vn"     => "get_vietnamnet_value",
        "github.com"        => "get_gitgub_value",
        "tienphong.vn"      => "tienphong_vn",
        "tuoitre.vn"        => "get_tuoitre_vn",
        "giaoducthoidai.vn" => "get_giaoducthoidai_vn",
        "viblo.asia"        => "get_viblo_asia",
        "dinhnt.com"        => "get_dinhnt_com",
        "xuanthulab.net"    => "get_xuanthulab_net",
        "hoclaptrinh.vn"    => "get_hoclaptrinh_vn",
        "sql.js.org"        => "get_sql_js_org",
        "vtc.vn"            => "get_vtc_vn",
        "www.qdnd.vn"       => "get_www_qdnd_vn",
        "www.delftstack.com" => "get_www_delftstack_com",
        "niithanoi.edu.vn"  => "get_niithanoi_edu_vn",
        "techmaster.vn"     => "get_techmaster_vn",
        "kienthuc.net.vn"   => "get_kienthuc_net_vn",
        "www.thivien.net"   => "get_www_thivien_net",
        "dantri.com.vn"     => "get_dantri_value", // host => function
        "topdev.vn"         => "get_topdev_vn" // host => function
    ];

    public function __construct(
        Request $request,
        Paper $paper,
        Category $category
    ) {
        $this->request = $request;
        $this->paper = $paper;
        $this->category = $category;
    }

    public function source(Request $request)
    {
        $request_url = $this->request->get("source_request");
        if (!strlen(str_replace(" ", "", $request_url))) {
            dd("input url not found");
        }
        try {
            /**
             * check type of source request for get conten value.
             */
            $type = $this->check_type($request_url);
            if ($type) {

                $arrContextOptions = array( // https://www.php.net/manual/en/context.http.php
                    "ssl" => array(
                        // skip error "Failed to enable crypto" + "SSL operation failed with code 1."
                        "verify_peer" => false,
                        "verify_peer_name" => false,
                    ),
                    // skyp error "failed to open stream: operation failed" + "Redirection limit reached"
                    'http' => array(
                        'max_redirects' => 101,
                        'ignore_errors' => '1'
                    ),

                );

                $html = file_get_contents($request_url, false, stream_context_create($arrContextOptions));
                $doc = $this->loadDom($html);  // for load html text to dom
            } else {
                return redirect()->back()->with("error", "input url not found");
            }
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage(), 1);
        }

        if (method_exists($this, str_replace(".", "_", $type))) { // kiểm tra xem class có tồn tại function có tên như biến $type không.
            $value = $this->{$type}($doc);                       // gọi vào hàm có trong class thông qua tên là 1 biến số.
        } else {
            $value = call_user_func_array([$this, $type], [$doc]); // gọi bằng call_user_func_array() với class, method, param truyền vào.
        }

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
                "request_url" => $request_url
            ]);

            return view("adminhtml.templates.papers.create", $values);
        }
    }

    public function get_soha_value($doc)
    {
        // for: soha.vn
        return $this->getValueByClassName($doc, "clearfix news-content", "news-sapo");
    }

    function get_vietnamnet_value($doc)
    {
        // for: vietnamnet.vn
        return $this->getValueByClassName($doc, "maincontent main-content", "content-detail-sapo");
    }

    function get_dantri_value($doc): array
    {
        // for: dantri.com.vn
        // return $this->getValueByClassName($doc, "e-magazine__body", "e-magazine__sapo");
        return $this->getValueByClassName($doc, "singular-content", "e-magazine__sapo");
    }

    public function get_gitgub_value($doc)
    {
        // for: github.com
        return $this->getValueByClassName($doc, "js-quote-selection-container", "js-issue-title markdown-title");
    }

    public function tienphong_vn($doc)
    {
        // for: tienphong.vn
        return $this->getValueByClassName($doc, "article__body cms-body", "article__sapo cms-desc");
    }

    public function get_giaoducthoidai_vn($doc)
    {
        return $this->getValueByClassName($doc, "article__body cms-body", "article__sapo cms-desc");
    }
    public function get_tuoitre_vn($doc)
    {
        return $this->getValueByClassName($doc, "detail-cmain", "detail-sapo");
    }

    function get_dinhnt_com($doc)
    {
        return $this->getValueByClassName($doc, "blog-single-1x", "overlay-text text-left");
    }

    function get_xuanthulab_net($doc)
    {
        //
        return $this->getValueByClassName($doc, "main-post", "overlay-text text-left");
    }

    function get_hoclaptrinh_vn($doc)
    {
        // https://hoclaptrinh.vn/tutorial/hoc-sqlite-co-ban-va-nang-cao/su-dung-sqlite-voi-php
        return $this->getValueByClassName($doc, "question-detail-wrapper", "");
    }

    function get_sql_js_org($doc)
    { // https://sql.js.org/#/ not work.
        return $this->getValueByClassName($doc, "content", "");
    }

    function get_viblo_asia($doc)
    {
        $conten = $this->getValueByClassName($doc, "md-contents", "");
        /**
         * format image for display in the view viblo_asia
         */
        $conten['conten'] = preg_replace('/(<canvas).*<\/canvas>/', '', $conten['conten']);
        return $conten;
    }

    function get_www_delftstack_com($doc)
    {
        return $this->getValueByClassName($doc, "col-md-9 col-sm-9 main-content", "");
    }

    function get_vtc_vn($doc)
    {
        return $this->getValueByClassName($doc, "edittor-content box-cont mt15 clearfix", "font18 bold inline-nb");
    }

    function get_www_qdnd_vn($doc)
    {
        return $this->getValueByClassName($doc, "post-content", "");
    }

    function get_quantrimang_com($doc)
    {
        return $this->getValueByClassName($doc, "content-detail", "");
    }

    function get_niithanoi_edu_vn($doc)
    {
        return call_user_func(fn () => $this->getValueByClassName($doc, "noidung TextSize noidungList", ""));
    }

    function get_techmaster_vn($doc)
    {
        return call_user_func(fn () => $this->getValueByClassName($doc, "techmaster-font-open-sans post-content", ""));
    }

    function get_kienthuc_net_vn($doc)
    {
        return call_user_func(fn () => $this->getValueByClassName($doc, "cms-body", "sapo cms-desc"));
    }

    // get_www_thivien_net
    function get_www_thivien_net($doc)
    {
        return call_user_func(fn () => $this->getValueByClassName($doc, "poem-content", "page-header"));
    }

    public function get_topdev_vn($doc)
    {
        return call_user_func(fn()=>$this->getValueByClassName($doc, "td-post-content", "entry-title"));
    }

    protected function check_type($request)
    {
        if ($request) {
            try {
                $url_values = parse_url($request);
                if (in_array($url_values["host"], array_keys(self::SOURCE))) {
                    return method_exists($this, str_replace(".", "", $url_values["host"])) ? $url_values["host"] : self::SOURCE[$url_values["host"]];
                }
            } catch (\Throwable $th) {
            }
        }
        return false;
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
        $url_alias = str_replace([":", "'", '"', "“", "”", ",", ".", "·", " ", "|"], "", $this->vn_to_str($title, 1));
        $short_conten_value = "";
        $conten = "";
        try {
            $short_conten = $this->findByXpath($doc, "class", $class_short_conten);
            $short_conten_value = $short_conten[0]->textContent;
        } catch (\Exception $e) {
        }

        try {
            $nodes = $this->findByXpath($doc, "class", $class_conten); // load content: (image error)
            $conten = $this->getNodeHtml($nodes[0]);
        } catch (\Exception $e) {
        }

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

            Mail::send('welcome', [], function ($message) {
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
    function uploadImageFromMobile(Request $request) {
        if ($file = $request->__get("upload_file")){
            $image_upload_path = '';
            $image_upload_path = $this->uploadImage($file, "public/images/cli4Mb", "images/resize/cli4Mb");
            return [
                'path' => $image_upload_path,
                'code' => 200
            ];
        }

        return [
            'path' => null,
            'code' => 500
        ];
    }
}
