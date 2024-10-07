<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Paper;

class PaperCategory extends Model
{
    use HasFactory;
    protected $table = "paper_category";

    public function for_category(): BelongsTo
    {
        return $this->belongsTo(Category::class, "category_id");
    }

    public function to_product(): BelongsTo
    {
        return $this->belongsTo(Paper::class, "paper_id");
    }
}
