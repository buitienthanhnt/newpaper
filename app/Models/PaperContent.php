<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaperContent extends Model implements PaperContentInterface
{
    use HasFactory;
    protected $guarded = [];

    function toPaper() : BelongsTo {
        return $this->belongsTo(Paper::class, PaperInterface::PRIMARY_ALIAS);
    }

    function getPaper() {
        return $this->toPaper()->getResults();
    }
}
