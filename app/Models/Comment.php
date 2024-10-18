<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Pagination\LengthAwarePaginator;

class Comment extends Model implements CommentInterface
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];
    protected $table = self::TABLE_NAME;

    public function linkChildrent(){
        return $this->hasMany($this, CommentInterface::ATTR_PARENT_ID);
    }

    public function getChildrent() {
        return $this->linkChildrent()->getResults();
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getChildrentPaginate() {
        return $this->linkChildrent()->paginate(12);
    }

    function getChildrentCount() : int {
        return $this->linkChildrent()->getResults()->count();
        return 0;
    }
}
