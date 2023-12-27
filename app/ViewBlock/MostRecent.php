<?php

namespace App\ViewBlock;

use App\Models\Paper;
use Illuminate\Contracts\Support\Htmlable;

class MostRecent implements Htmlable
{
	protected $template = 'frontend.templates.share.mostRecent';

	function toHtml(): string
	{
		$mostRecents = Paper::limit(3)->orderBy('created_at', 'DESC')->get();
		$html = view($this->template, ['most_recent' => $mostRecents])->render();
		return $html;
	}
}


?>