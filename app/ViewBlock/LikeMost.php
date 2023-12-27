<?php

namespace App\ViewBlock;

use App\Models\Paper;
use Illuminate\Contracts\Support\Htmlable;

class LikeMost implements Htmlable
{
	protected $template = "frontend.templates.share.likeMost";
	function toHtml(): string
	{
		$likeMost = Paper::all()->random(8);
		return view($this->template, ['weekly3_contens' => $likeMost])->render();
	}
}
