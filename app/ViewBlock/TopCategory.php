<?php

namespace App\ViewBlock;

use App\Models\Category;
use App\Models\ConfigCategory;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;

class TopCategory implements Htmlable
{
    protected $template = 'frontend.templates.share.topCategory';

    function toHtml()
    {
        $_topcategory = [];
        if (Cache::has(ConfigCategory::TOP_CATEGORY)) {
            $_topcategory = Cache::get(ConfigCategory::TOP_CATEGORY);
        } else {
            try {
                $topcategory = ConfigCategory::where("path", ConfigCategory::TOP_CATEGORY)->firstOr(function () {
                    return null;
                });
                if ($topcategory) {
                    $_topcategory = Category::find(explode("&", $topcategory->value));
                    Cache::put(ConfigCategory::TOP_CATEGORY, $_topcategory);
                }
            } catch (\Exception $e) {
                return '';
            }
        }
        $top_menu_view = View::make($this->template)->with('topcategory', $_topcategory)->render();
        return $top_menu_view;
    }

    function setTemplate(string $template): TopCategory
    {
        $this->template = $template;
        return $this;
    }
}
