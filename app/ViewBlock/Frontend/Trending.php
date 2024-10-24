<?php
namespace App\ViewBlock\Frontend;

use App\Models\Paper;
use Illuminate\Contracts\Support\Htmlable;

class Trending implements Htmlable
{
	protected $template = "frontend.templates.share.trending";

	function toHtml(): string
	{
		$papers = Paper::all();
		$trendings = $papers->random($papers->count() >= 5 ? 5 : $papers->count());
		return view($this->template, ['trendings' => $trendings, "title" => "Trending News"])->render();
	}
}


?>