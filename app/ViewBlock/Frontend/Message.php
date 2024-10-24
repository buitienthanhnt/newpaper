<?php

namespace App\ViewBlock\Frontend;

use App\Models\Paper;
use Illuminate\Contracts\Support\Htmlable;

class Message implements Htmlable
{
	protected $template = "frontend.templates.pageBlock.message";

	function toHtml(): string
	{
		$papers = Paper::all();
		$likes = $papers->random($papers->count() >= 6 ? 6 : $papers->count());
		return view($this->template, ['likes' => $likes, 'title' => 'Like most'])->render();
	}
}