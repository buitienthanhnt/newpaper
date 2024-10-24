<?php

namespace App\ViewBlock\Frontend;

use App\Models\Category;
use App\Models\ConfigCategory;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;

class CenterCategory implements Htmlable
{
    protected $template = 'frontend.templates.pageBlock.centerCategory';

    function toHtml()
    {
        $centerCategory = [];
        if (Cache::has('center_category')) {
            $centerCategory = Cache::get('center_category');
        } else {
            $center_category = ConfigCategory::where("path", ConfigCategory::CENTER_CATEGORY)->firstOr(function () {
                return null;
            });
            if ($center_category) {
                $centerCategory = Category::find(explode("&", $center_category->value));
                Cache::put('center_category', $centerCategory);
            }
        }
        $center_menu_view = View::make($this->template)->with('list_center', $centerCategory)->render();
        return $center_menu_view;
    }

    function setTemplate(string $template)
    {
        $this->template = $template;
        return $this;
    }
}
