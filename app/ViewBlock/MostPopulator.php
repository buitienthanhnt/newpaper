<?php

namespace App\ViewBlock;

use App\Models\Paper;
use App\Models\ViewSource;
use App\Models\ViewSourceInterface;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\View;

class MostPopulator implements Htmlable
{
    protected $template = 'frontend.templates.share.mostPopulator';

    function toHtml(): string
    {
        try {
            $mostView = ViewSource::where(ViewSourceInterface::ATTR_TYPE, ViewSource::TYPE_PAPER)
                                  ->orderBy(ViewSourceInterface::ATTR_VALUE, 'desc')
                                  ->limit(8)
                                  ->pluck(ViewSourceInterface::ATTR_SOURCE_ID)
                                  ->toArray();
            return count($mostView) ? view($this->template, ['most_popular' => Paper::find($mostView)])->render() : '';
        } catch (\Throwable $th) {
            //throw $th;
        }
        return '';
    }

    // public static function __callStatic($methodName, $arguments)
    // {
    // 	return static::connection()->$methodName(...$arguments);
    // }
}
