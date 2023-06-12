<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
// use Illuminate\Database\Eloquent\Relations\HasOne;
// use Illuminate\Database\Eloquent\Relations\MorphOne;

class Paper extends Model
{
    use HasFactory;
    use SoftDeletes;
    const PAGE_TAG = "page_tag";
    protected $guarded;

    public function to_category(): HasMany
    {
        return $this->hasMany("\App\Models\pageCategory", "page_id");
    }

    public function to_tag(): HasMany
    {
        return $this->hasMany(PageTag::class, "entity_id");
    }

    public function to_writer(): BelongsTo
    {
        return $this->belongsTo(Writer::class, "writer");
    }

    public function save_new($value)
    {
        try {
            $this->fill($value)->save();
            return true;
        } catch (\Throwable $th) {
            //throw $th;
        }
        return false;
    }
}
