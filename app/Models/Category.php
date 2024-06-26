<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\pageCategory;

class Category extends Model
{
    use HasFactory;

    // public $timestamps = false;
    protected $guarded = [];
    use SoftDeletes;
    protected $_selected = array();
    protected $select_key = "*";
    protected $current_type = "default";

    function getChildrent()
    {
        return $this->hasMany($this, 'parent_id')->getResults();
    }

    public function category_tree_option($category = null)
    {
        $parent_category = '<option value="0">Root category</option>';
        $list_catergory = $this->all()->where("parent_id", "=", 0)->where('type', '=', $this->current_type);
        if ($list_catergory->count()) {
            if ($category) {
                $parent_category .= $this->category_tree($list_catergory, "", $category->parent_id);
            } else {
                $parent_category .= $this->category_tree($list_catergory);
            }
        }
        return $parent_category;
    }

    protected function category_tree($catergory, $begin = "", $selected = null)
    {
        $html = "";
        foreach ($catergory as $cate) {
            $html .= '<option value="' . $cate->id . '" ' . ($this->_selected ? (in_array($cate->id, $this->_selected) ? "selected " : "") : ($selected === $cate->id ? "selected " : "")) . '>' . $begin . $cate->name . '</option>';
            if ($list_catergory = $this->all()->where("parent_id", "=", $cate->id)->where('type', '=', $this->current_type)) {
                if ($list_catergory->count()) {
                    $_be = $begin;
                    $begin .= "___";
                    $html .= $this->category_tree($list_catergory, $begin, $selected);
                    $begin = $_be;
                } else {
                    continue;
                }
            }
        }
        return $html;
    }

    function time_line_option()
    {
        $time_lines = $this->all()->where('type', '=', 'time_line');
        $html = '';
        foreach ($time_lines as $value) {
            $html .= '<option value="' . $value->id . '">' . $value->name . '</option>';
        }
        return $html;
    }

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

    public function to_page_category(): HasMany
    {
        return $this->hasMany(PageCategory::class, "category_id");
    }

    public function get_papers($limit = 4, $offset = 0, $order_by = [], $hidden = [])
    {
        $page_id = array_column($this->to_page_category()->getResults()->toArray(), "page_id");
        $result = null;
        if ($order_by) {
            $result =  Paper::whereIn("id", $page_id)->offset($offset * $limit)->orderBy(...$order_by)->take($limit)->select($this->select_key);
        } else {
            $result =  Paper::whereIn("id", $page_id)->take($limit)->offset($offset * $limit)->select($this->select_key);
        }
        return $result->get();
    }

    function getCategoryTree($root = false)
    {
        if ($root) {
            $cat["name"] = "";
            $cat["id"] = 0;
        } else {
            $cat["name"] = $this->name;
            $cat["id"] = $this->id;
        }
        $childrens = $this->all()->where("parent_id", "=", $root ? 0 : $this->id);
        $values = null;
        if (count($childrens)) {
            $items = null;
            foreach ($childrens as $children) {
                if (!$children->active) {
                    continue;
                }
                $category = $children->getCategoryTree();
                $items[] = $category;
            }
            $cat["items"] = $items;
        } else {
            $cat["items"] = null;
        }
        return $cat;
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
