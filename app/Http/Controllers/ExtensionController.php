<?php

namespace App\Http\Controllers;

use App\Helper\DomHtml;
use Illuminate\Http\Request;

class ExtensionController extends Controller
{
    //
    use DomHtml;

    public function source(Request $request)
    {
        $request_url = "https://soha.vn/da-den-luc-dien-thoai-xiaomi-can-co-giao-dien-moi-thay-vi-cu-mai-hoc-theo-iphone-20230612081713615.htm";
        $request_url = "https://viblo.asia/p/23-cau-truy-van-huu-ich-trong-elasticsearch-phan-1-Ljy5VoMbKra";

        $html = file_get_contents($request_url);
        // $doc = $this->loadDom($html);
        $doc = $this->loadDom(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));  // for error: utf-8
        $nodes = $this->findByXpath($doc, "class", "article-content__body my-2 flex-fill");
        $html_this = [];
        foreach ($nodes as $node)
        {
            $html_this[] = $this->getNodeHtml($node);
        }
        // dd($html_this);
        echo($html_this[0]);

    }
}
