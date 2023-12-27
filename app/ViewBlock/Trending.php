<?php

namespace App\ViewBlock;

use App\Models\Paper;
use Illuminate\Contracts\Support\Htmlable;

class Trending implements Htmlable
{
	protected $template = "frontend.templates.share.trending";

	function toHtml(): string
	{
		$trendings = Paper::all()->random(5);
		return view($this->template, ['trendings' => $trendings])->render();
	}
}


?>