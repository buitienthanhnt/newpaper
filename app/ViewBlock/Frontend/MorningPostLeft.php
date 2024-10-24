<?php

namespace App\ViewBlock\Frontend;

use App\Models\Paper;
use Illuminate\Contracts\Support\Htmlable;

class MorningPostLeft implements Htmlable
{
	protected $template = "frontend.templates.pageBlock.morningPostLeft";
	function toHtml(): string
	{
		$trendingLeft = Paper::take(3)->orderBy('created_at', "DESC")->get();
		return view($this->template, ['trending_left' => $trendingLeft])->render();
	}
}

?>