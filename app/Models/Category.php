<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\PaperCategory;

class Category extends Model implements CategoryInterface
{
    use HasFactory;
    use SoftDeletes;

    // public $timestamps = false;
    protected $guarded = [];
    protected $_selected = array();
    protected $select_key = "*";
    protected $current_type = "default";

    /**
     * lấy liên kết bảng trung gian
     */
    protected function toPaperCategory(): HasMany
    {
        return $this->hasMany(PaperCategory::class, CategoryInterface::PRIMARY_ALIAS);
    }

    /**
     * lấy danh sách kết quả bảng trung gian.
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getPaperCategories() {
        return $this->toPaperCategory()->getResults();
    }

    /**
     * lấy danh sách id của paper.
     * @return int[]
     */
    public function listIdPapers() {
        return $this->getPaperCategories()->pluck(PaperInterface::PRIMARY_ALIAS)->toArray() ?: [];
    }

    /**
     * lấy danh sách các bài viết của category này.
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getPapers() {
        return Paper::find($this->listIdPapers());
    }

    /**
     * lấy các category con.
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getChildrent()
    {
        return $this->hasMany($this, CategoryInterface::ATTR_PARENT_ID)->getResults();
    }

    /**
     * lấy danh sách <option> của category dạng html
     * hàm này mặc định là lấy type='default'(category)
     * @return string
     */
    public function category_tree_option($category = null)
    {
        $parent_category = '<option value="0">Root category</option>';
        $list_catergory = $this->all()->where(CategoryInterface::ATTR_PARENT_ID, 0)->where(CategoryInterface::ATTR_TYPE, $this->current_type);
        if ($list_catergory->count()) {
            if ($category) {
                $parent_category .= $this->category_tree($list_catergory, "", $category->parent_id);
            } else {
                $parent_category .= $this->category_tree($list_catergory);
            }
        }
        return $parent_category;
    }

    /**
     * @param Category $category
     * @param string $begin
     * @param int $selected
     * @return string
     */
    protected function category_tree($catergory, $begin = "", $selected = null)
    {
        $html = "";
        $prefix = '___';
        foreach ($catergory as $cate) {
            $html .= '<option value="' . $cate->id . '" ' . ($this->_selected ? (in_array($cate->id, $this->_selected) ? "selected " : "") : ($selected === $cate->id ? "selected " : "")) . '>' . $begin . $cate->name . '</option>';
            if ($list_catergory = $cate->getChildrent()) {
                $_be = $begin;
                $begin .= $prefix;
                $html .= $this->category_tree($list_catergory, $begin, $selected);
                $begin = $_be;
            } else {
                continue;
            }
        }
        return $html;
    }

    /**
     * lấy danh sách timeLine dạng html
     * @param int $selected
     * @return string
     */
    function time_line_option($selected = null)
    {
        $time_lines = $this->all()->where('type', '=', 'time_line');
        $html = '';
        foreach ($time_lines as $value) {
            $html .= '<option value="' . $value->id . '" ' . ($selected == $value->id ? 'selected ' : '') . '>' . $value->name . '</option>';
        }
        return $html;
    }

    /**
     * lấy danh sách bài viết có phân trang
     * @param int $limit (số bài 1 trang)
     * @param int offset (trang hiện tại)
     * @param array $order_by (sắp xếp theo vd: ["updated_at", "DESC"])
     */
    public function getPaperPaginate($limit = 4, $offset = 0, $order_by = [])
    {
        $listPaperIds = $this->listIdPapers();
        $result = null;
        if ($order_by) {
            $result =  Paper::whereIn("id", $listPaperIds)->offset($offset * $limit)->orderBy(...$order_by)->take($limit)->select($this->select_key);
        } else {
            $result =  Paper::whereIn("id", $listPaperIds)->take($limit)->offset($offset * $limit)->select($this->select_key);
        }
        return $result->get();
    }

    /**
     * lấy danh sách category tree dạng data
     * @return array
     */
    function getCategoryTree($root = false)
    {
        if ($root) {
            $currentCategory["name"] = "";
            $currentCategory["id"] = 0;
        } else {
            $currentCategory["name"] = $this->name;
            $currentCategory["id"] = $this->id;
        }
        $childrens = Category::all()->where(CategoryInterface::ATTR_PARENT_ID, $root ? 0 : $this->id);
        if (count($childrens)) {
            $items = null;
            foreach ($childrens as $children) {
                if (!$children->active) {
                    continue;
                }
                $category = $children->getCategoryTree();
                $items[] = $category;
            }
            $currentCategory["items"] = $items;
        } else {
            $currentCategory["items"] = null;
        }
        return $currentCategory;
    }
    // ===================================================================

    public function setSelected($_selected = [])
    {
        $this->_selected = $_selected;
        return $this;
    }

    public function setSelectKey($key = [])
    {
        $this->select_key = $key;
        return $this;
    }

    /**
     * @param string $type
     * @return $this
     */
    function setCurrentType(string $type = 'default')
    {
        $this->current_type = $type;
        return $this;
    }
}
