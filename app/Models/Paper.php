<?php

namespace App\Models;

use App\Api\BaseApi;
use App\Helper\ImageUpload;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Paper extends Model implements PaperInterface
{
    use HasFactory;
    use SoftDeletes;
    use ImageUpload;

    protected $guarded;
    protected $viewSource = null;
    protected $content = null;

    /**
     * lấy ảnh đại diện của bài viết.
     */
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
     * lấy liên kết bảng trung gian
     */
    protected function toPaperCategory(): HasMany
    {
        return $this->hasMany(PaperCategory::class, Paper::PRIMARY_ALIAS);
    }

    /**
     * lấy danh sách kết quả bảng trung gian.
     * @return Illuminate\Database\Eloquent\Collection
     */
    function getPaperCategories()
    {
        return $this->toPaperCategory()->getResults();
    }

    /**
     * lấy danh sách id của category.
     */
    function listIdCategories(): array
    {
        return $this->getPaperCategories()->pluck(CategoryInterface::PRIMARY_ALIAS)->toArray() ?: [];
    }

    /**
     * lấy danh sách category của bài viết.
     * @return Illuminate\Database\Eloquent\Collection
     */
    function getCategories()
    {
        return Category::find($this->listIdCategories());
    }

    /**
     * lấy các tag của bài viết.
     * tìm 1 khóa chính -> nhiều khóa phụ.
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getTags()
    {
        $tags = $this->hasMany(PaperTag::class, PaperTagInterface::ATTR_ENTITY_ID)->getResults()
            ->where(PaperTagInterface::ATTR_TYPE, PaperTagInterface::TYPE_PAPER);
        return $tags;
    }

    /**
     * lấy tác giả của bài viết.
     * @return Writer
     */
    public function getWriter()
    {
        return $this->belongsTo(Writer::class, PaperInterface::ATTR_WRITER)->getResults();
    }

    /**
     * @return string
     */
    public function writerName(): string
    {
        return $this->getWriter()->name ?? '';
    }

    /**
     * lấy danh sách thành phần nội dung của bài viết.
     * @return Illuminate\Database\Eloquent\Collection
     */
    function getContents()
    {
        if ($this->content) {
            return $this->content;
        }
        $this->content = $this->hasMany(PaperContent::class, PaperInterface::PRIMARY_ALIAS)->getResults();
        return $this->content;
    }

    function sliderImages()
    {
        return $this->getContents()->filter(function ($item) {
            return $item[PaperContentInterface::ATTR_TYPE] === PaperContentInterface::TYPE_TIMELINE;
        });
    }

    function linkComment() {
        return $this->hasMany(Comment::class, PaperInterface::PRIMARY_ALIAS)->where(CommentInterface::ATTR_PARENT_ID, null);
    }

    /**
     * lấy comment con theo paper_id và comment cha.
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getComments($parentId = null, int $page = 0, int $limit = 4)
    {
        if ($page === 0 && $limit === 0) {
            return $this->hasMany(Comment::class, PaperInterface::PRIMARY_ALIAS)->where(CommentInterface::ATTR_PARENT_ID, $parentId)->getResults();
        }
        return $this->hasMany(Comment::class, PaperInterface::PRIMARY_ALIAS)->where(CommentInterface::ATTR_PARENT_ID, $parentId)->limit($limit)->offSet($page * $limit)->getResults();
    }

    /**
     * @return LengthAwarePaginator
     */
    function getCommentPaginate() {
     return $this->linkComment()->paginate(12);   
    }

    /**
     * lấy cây đệ quy tuần tự của comment
     * @return Illuminate\Database\Eloquent\Collection
     */
    function getCommentTree($parentId = null, int $page = 0, int $limit = 4)
    {
        $comments = $this->getComments($parentId, $page, $limit);
        if ($comments->count()) {
            foreach ($comments as &$comment) {
                $childrents = $this->getComments($comment->id);
                if ($childrents->count()) {
                    $comment->childrents = $this->getCommentTree($comment->id, $page, $limit);
                } else {
                    $comment->childrents = null;
                }
            }
        }
        return $comments;
    }

    /**
     * lấy tổng số bình luận của bài viết.
     */
    function commentCount(): int
    {
        try {
            return $this->hasMany(Comment::class, PaperInterface::PRIMARY_ALIAS)->getResults()->count();
        } catch (\Throwable $th) {
            //throw $th;
        }
        return 0;
    }

    /**
     * lấy trạng thái hoạt động của bài đăng.
     * @return ViewSource
     */
    function viewSource(): ViewSource
    {
        try {
            if ($this->viewSource) {
                return $this->viewSource;
            }
            $viewSource = $this->hasMany(ViewSource::class, ViewSourceInterface::ATTR_SOURCE_ID)->where(ViewSourceInterface::ATTR_TYPE, ViewSource::TYPE_PAPER)->first();
            $this->viewSource = $viewSource;
            return $viewSource;
        } catch (\Throwable $th) {
            //throw $th;
        }
        return new ViewSource();
    }

    /**
     * lấy số lượt xem
     */
    function viewCount(): int
    {
        $viewSource = $this->viewSource();
        return $viewSource->value ?: 1;
    }

    /**
     * lấy số lượt like
     */
    function paperLike(): int
    {
        $viewSource = $this->viewSource();
        return $viewSource->like ?: 1;
    }

    /**
     * lấy số lượt thả tim
     */
    function paperHeart(): int
    {
        $viewSource = $this->viewSource();
        return $viewSource->heart ?: 1;
    }

    /**
     * lấy giá(nếu có) đã đổi ra đơn vị vnđ.
     * @param false $format
     * @return float|int|null
     */
    function getPrice($format = false)
    {
        try {
            $price = $this->getContents()->where(PaperContent::ATTR_TYPE, PaperContent::TYPE_PRICE)->first();
            if ($price) {
                return $price && $price->value ? $format ? number_format($price->value * 1000) : $price->value * 1000 : null;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        return null;
    }

    /**
     * lấy thông tin hoạt động bài viết
     * @return array
     */
    function paperInfo(): array
    {
        return [
            'view_count' => $this->viewCount(),
            'comment_count' => $this->commentCount(),
            'like' => $this->paperLike(),
            'heart' => $this->paperHeart(),
        ];
    }

    /**
     * @return string
     */
    function getUrl(): string
    {
        return route(
            'front_paper_detail',
            [
                'alias' => $this->{PaperInterface::ATTR_URL_ALIAS},
                'paper_id' => $this->id
            ]
        );
    }

    /**
     * @param array $ids
     * @return mixed
     */
    function getPaperByIds(array $ids = []){
        return $this->find($ids);
    }

    /**
     * @return mixed
     */
    function getRelatedItems(){
        $paperIds = [];
        $categories = $this->getCategories();
        foreach ($categories as $category) {
            $paperIds = array_merge($category->listIdPapers(), $paperIds);
        }
        return $this->getPaperByIds(array_unique($paperIds));
    }

    function getUpdatedAt() : string {
        return date('M d, Y', strtotime($this->updated_at));
        return '';
    }
}
