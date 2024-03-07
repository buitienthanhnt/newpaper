<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Writer extends Model
{
    use HasFactory;
    protected $guarded = [];
    use SoftDeletes;

    function getPapers(): HasMany {
        return $this->hasMany(Paper::class, 'writer');
    }
}
