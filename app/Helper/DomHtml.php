<?php
namespace App\Helper;

/**
 *
 */
trait DomHtml
{

    public function loadDom($html_text)
    {
        $doc = new \DOMDocument();
        $internalErrors = libxml_use_internal_errors(true);
        @$doc->loadHTML($html_text);
        libxml_use_internal_errors($internalErrors);
        return $doc;
    }

    public function findByXpath($doc, $type = "", $value = "")
    {
        $nodes = null;
        $xpath = new \DOMXPath($doc);
        if ($type == "class") {
            $nodes = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $value ')]");
        }else {
            $nodes = $xpath->query('//select');
        }
        return $nodes;
    }

    public function getNodeHtml($node)
    {
        $html = $node->ownerDocument->saveHTML($node);
        return $html;
    }

}
?>
