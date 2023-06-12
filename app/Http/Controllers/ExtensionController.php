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

    public function __construct(
        Paper $paper,
        Request $request
    )
    {
        $this->request = $request;
        $this->paper = $paper;
    }

    public function source(Request $request)
    {
        $request_url = "https://soha.vn/da-den-luc-dien-thoai-xiaomi-can-co-giao-dien-moi-thay-vi-cu-mai-hoc-theo-iphone-20230612081713615.htm"; // clearfix news-content
        // $request_url = "https://viblo.asia/p/23-cau-truy-van-huu-ich-trong-elasticsearch-phan-1-Ljy5VoMbKra"; // article-content__body my-2 flex-fill

        $html = file_get_contents($request_url);
        $doc = $this->loadDom($html);  // for error: utf-8

        // for soha.vn
        $value = $this->get_soha_value($doc);
        // for soha.vn
        
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
            "short_conten" => $short_conten_value,
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
