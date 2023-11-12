<?php

namespace App\Helper\View;

use App\Models\Category;
use App\Models\ConfigCategory;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;

class TopCategory implements Htmlable
{
	function toHtml()
	{
		if (Cache::has('top_menu_view')) {
			$top_menu =  Cache::get('top_menu_view');
			return $top_menu;
		} else {
			$_topcategory = [];
			if (Cache::has('top_category')) {
				$_topcategory = Cache::get('top_category');
			} else {
				try {
					$topcategory = ConfigCategory::where("path", ConfigCategory::TOP_CATEGORY)->firstOr(function () {
						return null;
					});

					if ($topcategory) {
						$_topcategory = Category::find(explode("&", $topcategory->value));
						// View::share("topcategory", $list_category);
					}
				} catch (\Exception $e) {
					return '';
				}
			}
			$top_menu_view = View::make('frontend.templates.share.topCategory')->with('topcategory', $_topcategory)->render();
			Cache::put('top_menu_view', $top_menu_view);
			return $top_menu_view;
		}
	}
}
