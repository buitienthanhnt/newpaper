<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $request;
    protected $category;

    public function __construct(
        Request $request,
        Category $category
    )
    {
        $this->request = $request;
        $this->category = $category;
    }

    public function listCategory()
    {
        $all_category = $this->category->all();
        return view("adminhtml/templates/category/list", ["all_category" => $all_category]);
    }

    public function createCategory()
    {
        // $parent_category = '<option value="0">Root category</option>';
        // $list_catergory = $this->category->all()->where("parent_id", "=", 0);
        // if ($list_catergory->count()) {
        //     $parent_category.= $this->category_tree($list_catergory);
        // }
        $parent_category = $this->category_tree_option();
        return view("adminhtml/templates/category/create", ["parent_category" => $parent_category]);
    }

    protected function category_tree_option($category = null)
    {
        $parent_category = '<option value="0">Root category</option>';
        $list_catergory = $this->category->all()->where("parent_id", "=", 0);
        if ($list_catergory->count()) {
            if ($category) {
                $parent_category.= $this->category_tree($list_catergory, "", $category->parent_id);
            }else {
                $parent_category.= $this->category_tree($list_catergory);
            }

        }
        return $parent_category;
    }

    public function insertCategory()
    {
        $params = $this->request->toArray();
        $category = $this->category;
        $category->name = $params["name"];
        $category->description = $params["description"];
        $category->active = $params["active"];
        $category->parent_id = $this->request->__get("parent_category");
        $category->url_alias = $params["url_alias"];
        $category->save();

        if ($category) {
           return back();
        }
    }

    protected function category_tree($catergory, $begin = "", $selected = null)
    {
        $html = "";
        foreach ($catergory as $cate) {
            $html.='<option value="'.$cate->id.'" '.($selected === $cate->id ? "selected" : "").'>'.$begin.$cate->name.'</option>';
            if ($list_catergory = $this->category->all()->where("parent_id", "=", $cate->id)) {
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

    public function editCategory($category_id)
    {
        $category = $this->category::find($category_id);
        $parent_category = $this->category_tree_option($category);

        return view("adminhtml/templates/category/edit", compact("category", "parent_category"));
    }

    public function deleteCategory($category_id)
    {
        $category = $this->category::find($category_id);
        if ($category) {
            $category->delete();
            return back();
        }
    }
}
