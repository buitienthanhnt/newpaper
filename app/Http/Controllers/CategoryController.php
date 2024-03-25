<?php

namespace App\Http\Controllers;

use App\Events\CacheClear;
use App\Models\Category;
use App\Models\ConfigCategory;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use RealRashid\SweetAlert\Facades\Alert;

class CategoryController extends Controller
{
    protected $request;
    protected $category;
    protected $configCategory;
    protected $session;

    public function __construct(
        Request $request,
        Category $category,
        ConfigCategory $configCategory,
        Store $session
    ) {
        $this->request = $request;
        $this->category = $category;
        $this->configCategory = $configCategory;
        $this->session = $session;
    }

    public function listCategory()
    {
        $all_category = $this->category::paginate(8); // php artisan vendor:publish --tag=laravel-pagination
        return view("adminhtml/templates/category/list", ["all_category" => $all_category]);
    }

    public function createCategory()
    {
        // $parent_category = '<option value="0">Root category</option>';
        // $list_catergory = $this->category->all()->where("parent_id", "=", 0);
        // if ($list_catergory->count()) {
        //     $parent_category.= $this->category_tree($list_catergory);
        // }
        if ($this->session->exists("success")) {
            Alert::info($this->session->get("success"), 'Message')->autoClose(2000);
        } elseif ($this->session->exists("error")) {
            Alert::error($this->session->get("error"), 'Message')->autoClose(2000);
        }

        $parent_category = $this->category_tree_option();
        return view("adminhtml/templates/category/create", ["parent_category" => $parent_category]);
    }

    protected function category_tree_option($category = null)
    {
        $parent_category = '<option value="0">Root category</option>';
        $list_catergory = $this->category->all()->where("parent_id", "=", 0);
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
            $html .= '<option value="' . $cate->id . '" ' . ($selected === $cate->id ? "selected" : "") . '>' . $begin . $cate->name . '</option>';
            if ($list_catergory = $this->category->all()->where("parent_id", "=", $cate->id)) {
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

    public function insertCategory()
    {
        $params = $this->request->toArray();
        $category = $this->category;
        $category->name = $params["name"];
        $category->description = $params["description"];
        $category->active = $params["active"];
        $category->parent_id = $this->request->__get("parent_id");
        $category->url_alias = $params["url_alias"] ?: str_replace(" ", '-', strtolower($params['name']));
        $category->type = $this->request->get("type");
        $category->save();

        if ($category) {
            event(new CacheClear(['top_category', 'top_menu_view'])); // gọi vào event: CacheClear
            return back()->with("success", "created new category: $category->name");
        }
    }

    public function editCategory($category_id)
    {
        $category = $this->category::find($category_id);
        $parent_category = $this->category_tree_option($category);

        return view("adminhtml/templates/category/edit", compact("category", "parent_category"));
    }

    public function updateCategory($category_id)
    {
        $category = $this->category::find($category_id);
        $request_params = $this->request->toArray();
        $category->fill($request_params);
        $category->save();
        // return redirect(route("category_admin_list"));
        return redirect()->back()->with("success", "updated success!");
    }

    public function deleteCategory($category_id)
    {
        $category = $this->category::find($category_id);
        if ($category) {
            $category->delete();
            return back();
        }
    }

    public function setupCategory()
    {
        $parent_category = $this->category_tree_option();
        $list_current = [];
        $current_setup = ConfigCategory::where("path", ConfigCategory::TOP_CATEGORY);
        $all_category = Category::all();
        if ($current_setup->count()) {
            $list_current = explode("&", $current_setup->first()->value);
        }
        return view("adminhtml/templates/category/setup", ["parent_category" => $parent_category, "all_category" => $all_category, "list_current" => $list_current]);
    }

    public function setupSave()
    {
        $params = $this->request->toArray();
        $configCategory = $this->configCategory;
        $setup_type = $this->request->get("setup_type");
        if (!$setup_type) {
            $top_category = $configCategory::firstWhere("path", ConfigCategory::TOP_CATEGORY);
            if ($top_category) {
                $configCategory = $configCategory::find($top_category->id);
            }
        }
        $configCategory->fill([
            "path" => $setup_type ? str_replace(" ", "_", $setup_type) : ConfigCategory::TOP_CATEGORY,
            "value" => implode("&", $params["setup_category"]),
            "description" => $params['description'] ?? null
        ]);
        $configCategory->save();

        return redirect()->back();
    }
}
