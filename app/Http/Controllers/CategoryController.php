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

    public function createCategory()
    {
        $parent_category = '<option value="0">Root category</option>';
        $list_catergory = $this->category->all()->where("parent_id", "=", 0);
        if ($list_catergory->count()) {
            $html = "";
            $parent_category.= $this->category_tree($list_catergory);
        }
        return view("adminhtml/templates/category/create", ["parent_category" => $parent_category]);
    }

    public function insertCategory()
    {
        $params = $this->request->toArray();
        // $category = $this->category->save([
        //     "name" => $params["name"],
        //     "description" => $params["description"],
        //     "active" => true,
        //     "parent_id" => $params["parent_category"],
        //     "url_alias" => $params["url_alias"],
        //     "image_path" => "",
        // ]);
        // if ($category) {
        //     # code...
        // }
    }

    protected function category_tree($catergory)
    {
        $html = "";
        foreach ($catergory as $cate) {
            $html.='<option value="'.$cate->id.'">'.$cate->name.'</option>';
            if ($list_catergory = $this->category->all()->where("parent_id", "=", $cate->id)) {
                if ($list_catergory->count()) {
                    $html.=$this->category_tree($list_catergory);
                }
            }
        }
        return $html;
    }
}
