<?php

namespace App\Http\Controllers;

use App\Helper\DomHtml;
use App\Models\Category;
use App\Models\Paper;
use App\Models\Writer;
use Illuminate\Http\Request;

class ExtensionController extends Controller
{
    use DomHtml;

    protected $request;
    protected $paper;
    protected $category;

    const SOURCE = [
        "soha.vn" => "get_soha_value",
        "vietnamnet.vn" => "get_vietnamnet_value",
        "github.com" => "get_gitgub_value",
        "tienphong.vn" => "tienphong_vn",
        "dantri.com.vn" => "get_dantri_value" // host => function
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
                $html = file_get_contents($request_url);
                $doc = $this->loadDom($html);  // for load html text to dom
            } else {
                dd("input url not found");
            }
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage(), 1);
        }

        if (method_exists($this, str_replace(".", "_", $type))){
            $value = $this->{$type}($doc);
        }else{
            $value = call_user_func_array([$this, $type], [$doc]);
        }

        if (!$value) {
            return redirect()->back()->with("error", "can not parse source!");
        }else {
            $writers = Writer::all();
            $values = array_merge($value, [
                "category_option" => $this->category->category_tree_option(),
                "filemanager_url" => url("adminhtml/file/manager") . "?editor=tinymce5",
                "filemanager_url_base" => url("adminhtml/file/manager"),
                "writers" => $writers
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
        return $this->getValueByClassName($doc, "e-magazine__body", "e-magazine__sapo");
    }

    public function get_gitgub_value($doc){
        // for: github.com
        return $this->getValueByClassName($doc, "js-quote-selection-container", "js-issue-title markdown-title");
    }

    public function tienphong_vn($doc){
        // for: tienphong.vn
        return $this->getValueByClassName($doc, "article__body cms-body", "article__sapo cms-desc");
    }

    protected function check_type($request)
    {
        if ($request) {
            try {
                $url_values = parse_url($request);
                if (in_array($url_values["host"], array_keys(self::SOURCE))) {
                    return method_exists($this, str_replace(".", "", $url_values["host"])) ? $url_values["host"] : self::SOURCE[$url_values["host"]];
                }
            } catch (\Throwable $th) {}
        }
        return false;
    }

    protected function getValueByClassName($doc, $class_conten, $class_short_conten)
    {
        $nodes = $this->findByXpath($doc, "class", $class_conten); // load content: (image error)
        $title = $this->getTitle($doc);
        $url_alias = str_replace([":", "'", '"', "“", "”", ",", ".", "·", " "], "", $this->vn_to_str($title, 1));
        $short_conten = $this->findByXpath($doc, "class", $class_short_conten);
        $short_conten_value = $short_conten[0]->textContent;
        $conten = $this->getNodeHtml($nodes[0]);

        $request = $this->request;
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
}
