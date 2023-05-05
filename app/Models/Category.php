<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;

    // public $timestamps = false;
    protected $guarded = [];
    use SoftDeletes;

    public function category_tree_html()
    {
        # code...
    }

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
            $html.='<option value="'.$cate->id.'" '.($selected === $cate->id ? "selected" : "").'>'.$begin.$cate->name.'</option>';
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
}
