<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Rule extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "rule";
    protected $guarded = [];

    /**
     * hasOne tìm 1 -> 1.
     */
    function toParent() : HasOne {
        return $this->hasOne($this, "parent_id");
    }

    function getParent() {
        $parent = $this->toParent();
        return $parent->getResults() ?: null;
    }
}
