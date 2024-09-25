<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaperContent extends Model
{
    use HasFactory;
    const ATTR_TYPE = 'type';
    const ATTR_VALUE = 'value';

    const TYPE_TIMELINE = 'timeline';
    const TYPE_IMAGE = 'image';
    const TYPE_SLIDER = 'slider_data';
    const TYPE_PRICE = 'price';
    const TYPE_CONTEN = 'conten';

    protected $guarded = [];

    function toPaper() : BelongsTo {
        return $this->belongsTo(Paper::class, 'paper_id');
    }
}
