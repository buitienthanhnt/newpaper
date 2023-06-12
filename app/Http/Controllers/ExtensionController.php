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
        $html = file_get_contents($request_url);
        $doc = $this->loadDom($html);
        // $nodes = $this->findByXpath($nodes, "class", "relationnews");
        $nodes = $this->findByXpath($doc, "class", "clearfix news-content");
        $html_this = [];
        foreach ($nodes as $node)
        {
            $html_this[] = $this->getNodeHtml($node);
        }
        dd($html_this);
        echo($html_this[0]);

    }
}
