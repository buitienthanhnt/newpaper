<?php

namespace App\ViewBlock;

use App\Models\Paper;
use App\Models\ViewSource;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\View;

class MostPopulator implements Htmlable
{
	protected $template = 'frontend.templates.share.mostPopulator';
	function toHtml(): string
	{
		try {
			$mostView = ViewSource::where('type', ViewSource::PAPER_TYPE)->orderBy('value', 'desc')->limit(8)->get(['source_id'])->toArray();
			$mostPapers = Paper::find(array_column($mostView, "source_id"));
			$html = view($this->template, ['most_popular' => $mostPapers]);
			return $html;
		} catch (\Throwable $th) {
			//throw $th;
		}

		return '';
	}
}
