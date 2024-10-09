<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageTag extends Model implements PaperTagInterface
{
    use HasFactory;
    protected $table = self::TABLE_NAME;

    /**
     * 
     */
    public function to_paper($tag_value)
    {
        $tags = $this->where(self::ATTR_TYPE, self::TYPE_PAPER)->where(self::ATTR_VALUE, $tag_value)->get()->groupBy(self::ATTR_ENTITY_ID)->keys();
        return $tags;
    }
}
