<?php

namespace App\ViewBlock;

use App\Models\Paper;
use Illuminate\Contracts\Support\Htmlable;

class TrendingLeft implements Htmlable
{
	protected $template = "frontend.templates.share.trendingLeft";
	function toHtml(): string
	{
		$trendingLeft = Paper::take(3)->orderBy('created_at', "DESC")->get();
		return view($this->template, ['trending_left' => $trendingLeft])->render();
	}
}

?>