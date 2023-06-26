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

    public function category_tree_option($category = null)
    {
        $parent_category = '<option value="0">Root category</option>';
        $list_catergory = $this->all()->where("parent_id", "=", 0);
        if ($list_catergory->count()) {
            if ($category) {
                $parent_category.= $this->category_tree($list_catergory, "", $category->parent_id);
            }else {
                $parent_category.= $this->category_tree($list_catergory);
            }

        }
        return $parent_category;
    }

    protected function category_tree($catergory, $begin = "", $selected = null)
    {
        $html = "";
        foreach ($catergory as $cate) {
            $html.='<option value="'.$cate->id.'" '.($this->_selected ? (in_array($cate->id, $this->_selected) ? "selected " : "") : ($selected === $cate->id ? "selected " : "")).'>'.$begin.$cate->name.'</option>';
            if ($list_catergory = $this->all()->where("parent_id", "=", $cate->id)) {
                if ($list_catergory->count()) {
                    $_be = $begin;
                    $begin.="___";
                    $html.=$this->category_tree($list_catergory, $begin, $selected);
                    $begin = $_be;
                }else {
                    continue;
                }
            }
        }
        return $html;
    }

    public function setSelected($_selected = [])
    {
        $this->_selected = $_selected;
        return $this;
    }

    public function to_page_category(): HasMany
    {
        return $this->hasMany(PageCategory::class, "category_id");
    }

    public function get_papers($limit = 4, $offset = 1, $order_by = [])
    {
        $page_id = array_column($this->to_page_category()->getResults()->toArray(), "page_id");
        $result = null;
        if ($limit) {
            if ($order_by) {
                $result =  Paper::whereIn("id", $page_id)->offset($offset*$limit)->orderBy(...$order_by)->take($limit);
            }
            $result =  Paper::whereIn("id", $page_id)->take($limit)->offset($offset*$limit);
        }else {
            $result = Paper::whereIn("id", $page_id)->offset($offset*$limit);
        }
        return $result->get();
    }

}
