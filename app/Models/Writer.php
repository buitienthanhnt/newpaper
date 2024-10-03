<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Writer extends Model implements WriterInterface
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];

    function getPapers(): HasMany {
        return $this->hasMany(Paper::class, 'writer');
    }
}
