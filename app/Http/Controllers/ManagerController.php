<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ConfigCategory;
use App\Models\Paper;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class ManagerController extends Controller
{
    protected $request;
    protected $paper;
    protected $category;

    public function __construct(
        Request $request,
        Paper $paper,
        Category $category
    )
    {
        $this->request = $request;
        $this->paper = $paper;
        $this->category = $category;
    }

    public function homePage()
    {
        $list_center = []; $list_center_conten = []; $most_recent = null; $most_popular = null; $trendings= null; $weekly3_contens = null; $video_contens = null;
        $trending_left = $this->paper->take(3)->get();
        $trending_right = $this->paper->orderBy("updated_at", "DESC")->take(2)->get();
        $center_category = ConfigCategory::where("path", "center_category")->firstOr(function(){return null;});
        if ($center_category) {
            $list_center = Category::find(explode("&", $center_category->value));
            $list_papers = [];
            foreach ($list_center as $center) {
                $page_category = $center->to_page_category()->getResults()->toArray();
                $list_papers = array_unique([...array_column($page_category, "page_id"), ...$list_papers]);
            }
            if ($list_papers) {
                $list_center_conten = $this->paper->whereIn("id", $list_papers)->get();
            }
        }

        $most_recent = $this->paper->orderBy("updated_at", "ASC")->take(3)->get();
        $most_popular =  $this->paper->all();
        $video_contens = $trendings = $this->paper->orderBy("updated_at", "DESC")->take(3)->get();
        $weekly3_contens = $this->paper->orderBy("updated_at", "DESC")->get();

        return view("frontend/templates/homeconten", compact("trending_left", "trending_right", "list_center", "most_recent", "most_popular", "trendings", "weekly3_contens", "video_contens"));
    }

    public function pageDetail($page)
    {
        $paper = $this->paper->find($page);
        return view("frontend.templates.paper.paper_detail", compact("paper"));
    }

    public function categoryView($category_id)
    {
        $category = Category::where("url_alias", "like", $category_id)->get()->first();

        $list_center = []; $list_center_conten = []; $most_recent = null; $most_popular = null; $trendings= null; $weekly3_contens = null; $video_contens = null;
        $trending_left = $this->paper->take(3)->get();
        $trending_right = $this->paper->orderBy("updated_at", "DESC")->take(2)->get();
        $center_category = ConfigCategory::where("path", "center_category")->firstOr(function(){return null;});
        if ($center_category) {
            $list_center = Category::find(explode("&", $center_category->value));
            $list_papers = [];
            foreach ($list_center as $center) {
                $page_category = $center->to_page_category()->getResults()->toArray();
                $list_papers = array_unique([...array_column($page_category, "page_id"), ...$list_papers]);
            }
            if ($list_papers) {
                $list_center_conten = $this->paper->whereIn("id", $list_papers)->get();
            }
        }

        $most_recent = $this->paper->orderBy("updated_at", "ASC")->take(3)->get();
        $most_popular =  $this->paper->all();
        $weekly3_contens = $this->paper->orderBy("updated_at", "DESC")->get();
        $list_center = Category::where("url_alias", "like", $category_id)->get();
        return view("frontend/templates/categories", compact("category", "trending_left", "trending_right", "list_center", "most_recent", "most_popular", "weekly3_contens"));
    }
}
