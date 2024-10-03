<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageTag extends Model implements PaperTagInterface
{
    use HasFactory;
    protected $table = self::TABLE_NAME;

    public function to_paper($tag_value)
    {
        $tags = $this->where("type", "=", "page_tag")->where("value", "=", $tag_value)->get()->groupBy("entity_id")->keys();
        return $tags;
    }
}
