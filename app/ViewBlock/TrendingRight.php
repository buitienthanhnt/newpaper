<?php

namespace App\ViewBlock;

use App\Models\Paper;
use Illuminate\Contracts\Support\Htmlable;

class TrendingRight implements Htmlable
{
	protected $template = "frontend.templates.share.trendingRight";

	function toHtml()
	{
		$trendingRights = Paper::take(5)->orderBy("created_at", "DESC")->get();
		$trendingRights = $trendingRights->take(-2);
		return view($this->template, ['trending_right' => $trendingRights])->render();
	}
}
