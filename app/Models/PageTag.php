<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageTag extends Model
{
    use HasFactory;
    protected $table = "page_tag";

    public function to_paper($tag_value)
    {
        $tags = $this->where("type", "=", "page_tag")->where("value", "=", $tag_value)->get()->groupBy("entity_id")->keys();
        return $tags;
    }
}
