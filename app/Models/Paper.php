<?php

namespace App\Models;

use App\Api\BaseApi;
use App\Helper\ImageUpload;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Paper extends Model implements PaperInterface
{
    use HasFactory;
    use SoftDeletes;
    use ImageUpload;

    const PAGE_TAG = 'page_tag';

    protected $guarded;
    protected $viewSource = null;

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

    /**
     * @return mixed
     */
    function to_contents()
    {
        return $this->hasMany('\App\Models\PaperContent', "paper_id")->getResults();
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

    public function getComments($parentId = null, int $page = 0, int $limit = 4)
    {
        if ($page === 0 && $limit === 0) {
            return $this->hasMany(Comment::class, "paper_id")->where("parent_id", "=", $parentId)->getResults();
        }
        return $this->hasMany(Comment::class, "paper_id")->where("parent_id", "=", $parentId)->limit($limit)->offSet($page * $limit)->getResults();
    }

    function getCommentTree($parentId = null, int $page = 0, int $limit = 4)
    {
        $comments = $this->getComments($parentId, $page, $limit);
        if (count($comments)) {
            foreach ($comments as &$comment) {
                $childrents = $this->getComments($comment->id);
                if (count($childrents)) {
                    $comment->childrents = $this->getCommentTree($comment->id, $page, $limit);
                } else {
                    $comment->childrents = null;
                }
            }
        }
        return $comments;
    }

    function commentCount(): int
    {
        try {
            return count($this->hasMany(Comment::class, "paper_id")->where("parent_id", "=", null)->getResults());
        } catch (\Throwable $th) {
            //throw $th;
        }
        return 1;
    }

    /**
     * get viewSourceInfo of paper
     * @return ViewSource
     */
    function viewSource(): ViewSource
    {
        try {
            if ($this->viewSource) {
                return $this->viewSource;
            }
            $viewSource = $this->hasMany(ViewSource::class, 'source_id')->where('type', '=', ViewSource::PAPER_TYPE)->first();
            $this->viewSource = $viewSource;
            return $viewSource;
        } catch (\Throwable $th) {
            //throw $th;
        }
        return new ViewSource();
    }

    function sliderImages()
    {
        return DB::table('paper_carousel')->where('paper_id', $this->id)->get();
    }

    function viewCount(): string
    {
        $viewSource = $this->viewSource();
        return $viewSource->value ?: 1;
    }

    function paperLike(): string
    {
        $viewSource = $this->viewSource();
        return $viewSource->like ?: '';
    }

    function paperHeart(): string
    {
        $viewSource = $this->viewSource();
        return $viewSource->heart ?: '';
    }

    function paperInfo()
    {
        return [
            'view_count' => $this->viewCount(),
            'comment_count' => $this->commentCount(),
            'like' => $this->paperLike(),
            'heart' => $this->paperHeart(),
        ];
    }

    public function getImagePath(): string
    {
        if ($image_path = $this->image_path) {

            if (file_exists($image_path)) {
                return $image_path;
            }

            $real_path = $this->url_to_real($image_path);
            if (file_exists($real_path)) {
                return $image_path;
            }
        }
        return BaseApi::getDefaultImagePath();
    }

    /**
     * @param false $format
     * @return float|int|null
     */
    function paperPrice($format = false)
    {
        try {
            $price = $this->to_contents()->where(PaperContent::ATTR_TYPE, PaperContent::TYPE_PRICE)->first();
            if ($price) {
                return $price && $price->value ? $format ? number_format($price->value * 1000) : $price->value * 1000 : null;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        return null;
    }
}
