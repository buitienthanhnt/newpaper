<?php

namespace App\Helper;

/**
 *
 */
trait DomHtml
{

    /**
     * load html dom for read
     *
     * @param string $html_text
     */
    public function loadDom($html_text)
    {
        $doc = new \DOMDocument();
        $internalErrors = libxml_use_internal_errors(true);
        @$doc->loadHTML(mb_convert_encoding($html_text, 'HTML-ENTITIES', 'UTF-8'));
        libxml_use_internal_errors($internalErrors);
        return $doc;
    }

    /**
     * get element by element type or class
     *
     * @param [type] $doc
     * @param string $type
     * @param string $value
     * @return mixed
     */
    public function findByXpath($doc, $type = "", $value = "")
    {
        $nodes = null;
        $xpath = new \DOMXPath($doc);
        if ($type == "class") {
            $nodes = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $value ')]");
        } else {
            $nodes = $xpath->query('//select');
        }
        return $nodes;
    }

    /**
     * get all html element conten
     *
     * @param [type] $node
     * @return string
     */
    public function getNodeHtml($node)
    {
        $html = $node->ownerDocument->saveHTML($node);
        return $html;
    }

    /**
     * get document title for all.
     *
     * @param [type] $doc
     * @return string
     */
    public function getTitle($doc)
    {
        return $doc->getElementsByTagName('title')[0]->textContent;
    }

    /**
     * chuyển tiếng Việt sang không dấu.
     *
     * @param string $str
     * @param integer $low
     * @return string
     */
    function vn_to_str($str, $low = 0)
    {

        $unicode = array(

            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',

            'd' => 'đ',

            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',

            'i' => 'í|ì|ỉ|ĩ|ị',

            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',

            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',

            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',

            'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',

            'D' => 'Đ',

            'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',

            'I' => 'Í|Ì|Ỉ|Ĩ|Ị',

            'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',

            'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',

            'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',

        );

        foreach ($unicode as $nonUnicode => $uni) {
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }

        $remove_char = ["?" => "", " " => "-"];
        if ($remove_char) {
            foreach ($remove_char as $key => $value) {
                $str = str_replace($key, $value, $str);
            }
        }

        return !$low ? $str : strtolower($str);
    }

    /**
     * Undocumented function 
     * $ss = cut_str("Mới nhất vụ tấn công 2 trụ sở xã ở Đắk Lắk: Người dân hỗ trợ công an bắt giữ", 100, "...");
     *
     * @param string $str
     * @param integer $len
     * @param string $noi
     * @return string
     */
    public function cut_str($str = "", $len = 0, $noi = "")
    {
        $result = "";
        if ($str && $len) {
            if (strlen($str) + strlen($noi) < $len) {
                $result = $str + $noi;
            } elseif (strlen($str) == $len) {
                $result = $str;
            } else {
                $arrs = array_filter(explode(" ", $str));
                $i = 0;
                while (strlen($result) + strlen($arrs[$i]) + 1 + strlen($noi) <= $len) {
                    $result .= ($i == 0 ? "" : " ") . $arrs[$i];
                    $i++;
                }
                $result .= $noi;
            }
        }
        return $result;
    }
}
