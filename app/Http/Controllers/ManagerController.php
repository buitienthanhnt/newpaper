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

    public function __construct(
        Request $request,
        Paper $paper
    )
    {
        $this->request = $request;
        $this->paper = $paper;
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
        $video_contens  = $weekly3_contens = $trendings = $weekly3_contens = $this->paper->orderBy("updated_at", "DESC")->take(3)->get();

        return view("frontend/templates/homeconten", compact("trending_left", "trending_right", "list_center", "most_recent", "most_popular", "trendings", "weekly3_contens", "video_contens"));
    }

    public function pageDetail($page)
    {
        $paper = $this->paper->find($page);
        return view("frontend.templates.paper.paper_detail", compact("paper"));
    }
}
