<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model implements CommentInterface
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];
    protected $table = self::TABLE_NAME;

    public function getChildrent() {
        return $this->hasMany($this, "parent_id")->getResults();
    }
}
