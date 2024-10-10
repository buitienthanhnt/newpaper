<?php

namespace App\ViewBlock;

use App\Models\Paper;
use Illuminate\Contracts\Support\Htmlable;

class LikeMost implements Htmlable
{
	protected $template = "frontend.templates.share.likeMost";
	function toHtml(): string
	{
		$papers = Paper::all();
		$likeMost = $papers->random($papers->count() >= 8 ? 8 : $papers->count());
		return view($this->template, ['weekly3_contens' => $likeMost])->render();
	}
}
