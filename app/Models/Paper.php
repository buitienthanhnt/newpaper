<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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

    function listIdCategories(): array
    {
        return array_column($this->to_category()->get(["category_id"])->toArray(), "category_id") ?: [];
    }

    /**
     * tìm 1 khóa chính -> nhiều khóa phụ.
     */
    public function to_tag(): HasMany
    {
        return $this->hasMany(PageTag::class, "entity_id");
    }

    public function to_writer(): BelongsTo
    {
        return $this->belongsTo(Writer::class, "writer");
    }

    public function writerName(): string
    {
        return $this->to_writer()->getResults()->name ?? '';
    }

    public function save_new($value)
    {
        if ($value) {
            try {
                $this->fill($value)->save();
                return true;
            } catch (\Throwable $th) {
                throw new \Exception($th->getMessage(), 1);
            }
        }
        return false;
    }

    public function getComments()
    {
        $comments = $this->hasMany(Comment::class, "paper_id")->where("parent_id", "=", null)->getResults();
        return $comments;
    }

    function commentCount(): int
    {
        try {
            return count($this->getComments());
        } catch (\Throwable $th) {
            //throw $th;
        }
        return 1;
    }

    function viewCount(): int
    {
        try {
            return $this->hasMany(ViewSource::class, 'source_id')->where('type', '=', ViewSource::PAPER_TYPE)->get()->first()->value;
        } catch (\Throwable $th) {
            //throw $th;
        }
        return 1;
    }
}
