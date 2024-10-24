<?php

namespace App\ViewBlock\Frontend;

use App\Models\Paper;
use Illuminate\Contracts\Support\Htmlable;

class MostRecent implements Htmlable
{
	protected $template = 'frontend.templates.pageBlock.mostRecent';

	function toHtml(): string
	{
		$mostRecents = Paper::limit(3)->orderBy('created_at', 'DESC')->get();
		$html = view($this->template, ['most_recent' => $mostRecents, 'title' => 'Most Recent'])->render();
		return $html;
	}
}


?>