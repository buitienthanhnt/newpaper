<?php

namespace App\Http\Controllers;

use App\Helper\DomHtml;
use App\Models\Paper;
use Illuminate\Http\Request;

class ExtensionController extends Controller
{
    use DomHtml;

    protected $request;
    protected $paper;

    const SOHA_VN = "soha.vn";
    const VIETNAMNET_VN = "vietnamnet.vn";

    public function __construct(
        Paper $paper,
        Request $request
    ) {
        $this->request = $request;
        $this->paper = $paper;
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
            }else {
                dd("input url not found");
            }
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage(), 1);
        }

        $value = [];
        switch ($type) {
            case self::SOHA_VN: // for soha.vn
                $value = $this->get_soha_value($doc);
                break;
            case self::VIETNAMNET_VN: // for vietnamnet.vn
                $value = $this->get_vietnamnet_value($doc);
                break;
            default:
                // $value = $this->get_soha_value($doc);
                break;
        }

        $check = $this->paper->save_new($value);
        return $check ? "save success" : "save error";
    }

    public function get_soha_value($doc)
    {
        // for soha.vn
        $nodes = $this->findByXpath($doc, "class", "clearfix news-content");
        $title = $this->getTitle($doc);
        $url_alias = $this->vn_to_str($title, 1);
        $short_conten = $this->findByXpath($doc, "class", "news-sapo");
        $short_conten_value = $short_conten[0]->textContent;
        $conten = $this->getNodeHtml($nodes[0]);
        // for soha.vn

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

    function get_vietnamnet_value($doc)
    {
        // for vietnamnet.vn
        $nodes = $this->findByXpath($doc, "class", "maincontent main-content"); // load content
        $title = $this->getTitle($doc);
        $url_alias = $this->vn_to_str($title, 1);
        $short_conten = $this->findByXpath($doc, "class", "content-detail-sapo");
        $short_conten_value = $short_conten[0]->textContent;
        $conten = $this->getNodeHtml($nodes[0]);
        // for vietnamnet.vn

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

    function check_type($request)
    {
        if ($request) {
            try {
                $url_values = parse_url($request);
                switch ($url_values["host"]) {
                    case self::SOHA_VN:
                        return self::SOHA_VN;
                        break;
                    case self::VIETNAMNET_VN:
                        return self::VIETNAMNET_VN;
                        break;
                    default:
                        # code...
                        break;
                }
            } catch (\Throwable $th) {}
        }
        return false;
    }
}
