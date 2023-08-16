<?php

namespace App\Http\Controllers;

use App\Helper\DomHtml;
use App\Models\Category;
use App\Models\ConfigCategory;
use App\Models\PageTag;
use App\Models\Paper;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class ManagerController extends Controller
{
    use DomHtml;

    protected $request;
    protected $paper;
    protected $category;
    protected $pageTag;
    const URI = "192.168.100.210";                  // jm-destop
    const URI2 = "192.168.1.150/laravel1/public";   // mochi-m4700

    public function __construct(
        Request $request,
        Paper $paper,
        Category $category,
        PageTag $pageTag
    )
    {
        $this->request = $request;
        $this->paper = $paper;
        $this->category = $category;
        $this->pageTag = $pageTag;
    }

    public function homePage()
    {
        $list_center = []; $list_center_conten = []; $most_recent = null; $most_popular = null; $trendings= null; $weekly3_contens = null; $video_contens = null;
        $trending_left = $this->paper->orderBy("updated_at", "DESC")->take(3)->get();
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
        $most_popular =  $this->paper->take(8)->get();
        $video_contens = $this->paper->orderBy("updated_at", "DESC")->take(3)->get();
        $weekly3_contens = $trendings = $this->paper->orderBy("updated_at", "DESC")->take(8)->get();

        return view("frontend/templates/homeconten", compact("trending_left", "trending_right", "list_center", "most_recent", "most_popular", "trendings", "weekly3_contens", "video_contens"));
    }

    public function pageDetail($alias, $page)
    {
        $paper = $this->paper->find($page);
        $category = Category::where("url_alias", "like", "today")->get()->first(); // lys
        $list_center = Category::where("url_alias", "like", 2)->take(4)->get();
        $papers = $category->get_papers(4, 0, $order_by = ["updated_at", "DESC"]);
        $top_paper = $papers->take(2);
        $papers = $papers->diff($top_paper);
        return view("frontend.templates.paper.paper_detail", compact("paper", "list_center", "top_paper", "papers"));
    }

    public function categoryView($category_id)
    {
        $category = Category::where("url_alias", "like", $category_id)->get()->first();

        $list_center = []; $list_center_conten = []; $most_recent = null; $most_popular = null; $trendings= null; $weekly3_contens = null; $video_contens = null;
        $trending_left = $this->paper->orderBy("updated_at", "DESC")->take(3)->get();
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
        $most_popular =  $this->paper->take(6)->get();
        $weekly3_contens = $this->paper->take(8)->orderBy("updated_at", "DESC")->get();
        $list_center = Category::where("url_alias", "like", $category_id)->take(4)->get();
        $papers = $category->get_papers(4, 0, $order_by = ["updated_at", "DESC"]);
        $top_paper = $papers->take(2);
        $papers = $papers->diff($top_paper);
        return view("frontend/templates/categories", compact("category", "top_paper", "papers", "trending_left", "trending_right", "list_center", "most_recent", "most_popular", "weekly3_contens"));
    }

    public function tagView($value)
    {
        $papers = null;
        $paper_ids = $this->pageTag->to_paper($value);
        if ($paper_ids) {
            $papers = $this->paper::whereIn("id", $paper_ids)->get();
        }
        $trending_left = $papers->first();
        return view("frontend/templates/tags", ["tag" => $value, "papers" => $papers, "trending_left" => [$trending_left]]);
    }

    public function load_more()
    {
        $request = $this->request;
        $type = $request->get("type");
        $page = $request->get("page");
        if ($type) {
            $category = $this->category->where("url_alias", "like", $type)->first();
            $papers = $category->get_papers(4, $page);
        }
        $data = view("frontend/templates/paper/component/list_category_paper", ['papers' => $papers])->render();

        return response(json_encode([
            "code" => 200,
            "data" => $data
        ]));
    }

    function apiSourcePapers(Request $request) {
        // $papers = Paper::paginate($request->get("limit",  4))->orderBy("updated_at", "DESC");
        $papers = $this->paper->orderBy('updated_at', 'desc')->paginate(4);
        $data = $papers->toArray();
        if ($data["data"]) {
            foreach ($data["data"] as &$item) {
                $asset_path = "/newpaper/public/assets/";   // http:://192.168.100.210/newpaper/public/asset/pub_image/defaul.PNG
                // $item["image_path"] = $item["image_path"] ? str_replace("localhost", self::URI, $item["image_path"]) : "http://".self::URI.$asset_path."pub_image/defaul.PNG";     // windown jmm-desk
                $item["image_path"] = $item["image_path"] ? str_replace("laravel1.com", self::URI2, $item["image_path"]) : "http://".self::URI2."/assets/pub_image/defaul.PNG"; // ubuntu m4700

                $item["short_conten"] = $this->cut_str($item["short_conten"], 90, "...");
//                 $item["title"] = $this->cut_str($item["title"], 80, "../");
            }
        }
        return $data;
    }

    public function getPaperDetail($paper_id)
    {
        return $this->paper->find($paper_id);
    }

    public function getCategoryTop()
    {
        $top_category = ConfigCategory::where("path", "=", ConfigCategory::TOP_CATEGORY);
        $values = Category::whereIn("id", explode("&", $top_category->first()->value))->get()->toArray();
        $asset_path = "/newpaper/public/assets/";
        foreach ($values as &$value) {
            $value["image_path"] = $value["image_path"] ? str_replace("laravel1.com", self::URI2, $value["image_path"]) : "http://".self::URI2."/assets/pub_image/defaul.PNG"; // ubuntu m4700
            // $value["image_path"] = $value["image_path"] ? str_replace("localhost", self::URI, $value["image_path"]) : "http://".self::URI.$asset_path."pub_image/defaul.PNG";     // windown jmm-desk
        }
        return $values;
    }

    public function getPaperCategory($category_id, Request $request)
    {
        $category = $this->category->find($category_id);
        $papers = $category->setSelectKey(["id", "title", "short_conten", "image_path"])->get_papers($request->get("limit", 4), $request->get("page", 1) -1)->toArray();
        $asset_path = "/newpaper/public/assets/";
        foreach ($papers as &$value) {
            $value["image_path"] = $value["image_path"] ? str_replace("laravel1.com", self::URI2, $value["image_path"]) : "http://".self::URI2."/assets/pub_image/defaul.PNG";  // ubutnu m4700
            // $value["image_path"] = $value["image_path"] ? str_replace("localhost", self::URI, $value["image_path"]) : "http://".self::URI.$asset_path."pub_image/defaul.PNG";      // windown jmm-desk
        }
        return $papers;
    }
}
