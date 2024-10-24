<?php

namespace App\ViewBlock\Frontend;

use App\Models\Paper;
use Illuminate\Contracts\Support\Htmlable;

class MorningPostRight implements Htmlable
{
	protected $template = "frontend.templates.pageBlock.morningPostRight";

	function toHtml()
	{
		$trendingRights = Paper::take(5)->orderBy("created_at", "DESC")->get();
		$trendingRights = $trendingRights->take(-2);
		return view($this->template, ['trending_right' => $trendingRights])->render();
	}
}
