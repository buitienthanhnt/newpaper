<?php

namespace App\Http\Controllers;

use App\Events\ViewCount;
use Thanhnt\Nan\Helper\DomHtml;
use App\Models\Category;
use App\Models\ConfigCategory;
use App\Models\PageTag;
use App\Models\Paper;
use Illuminate\Http\Request;
use App\Helper\HelperFunction;
use App\ViewBlock\LikeMost;
use App\ViewBlock\MostPopulator;
use App\ViewBlock\Trending;
use Illuminate\Support\Facades\Cache;

class ManagerController extends Controller
{
    use DomHtml;

    protected $request;
    protected $paper;
    /**
     * @var \App\Models\Category $category
     */
    protected $category;
    protected $pageTag;
    protected $helperFunction;
    protected $mostPopulator;
    protected $likeMost;
    protected $trending;

    public function __construct(
        Request $request,
        Paper $paper,
        \App\Models\Category $category,
        PageTag $pageTag,
        HelperFunction $helperFunction,
        MostPopulator $mostPopulator,
        LikeMost $likeMost,
        Trending $trending
    ) {
        $this->request = $request;
        $this->paper = $paper;
        $this->category = $category;
        $this->pageTag = $pageTag;
        $this->helperFunction = $helperFunction;
        $this->mostPopulator = $mostPopulator;
        $this->likeMost = $likeMost;
        $this->trending = $trending;
    }

    public function homePage()
    {
        $list_center = [];
        $video_contens = null;
        $center_category = ConfigCategory::where("path", "center_category")->firstOr(function () {
            return null;
        });

        if ($center_category) {
            $list_center = Category::find(explode("&", $center_category->value));
            $list_papers = [];
            foreach ($list_center as $center) {
                $page_category = $center->to_page_category()->getResults()->toArray();
                $list_papers = array_unique([...array_column($page_category, "page_id"), ...$list_papers]);
            }
        }
        $video_contens = $this->paper->orderBy("updated_at", "DESC")->take(3)->get();
        return view("frontend/templates/homeconten", compact("list_center", "video_contens"));
    }

    function search(Request $request) {
        $searchValue = strtolower($request->get('search'));

        $searchPaper = array_column(Paper::where('title', 'LIKE', "%$searchValue%")
                                ->orWhere('short_conten', 'LIKE', "%$searchValue%")->get('id')->toArray(), 'id') ?: [];

        $searchTags = array_column(PageTag::where('value', 'LIKE', "%$searchValue%")->get('entity_id')->toArray(), 'entity_id') ?: [];
        
        $allValue = array_unique(array_merge($searchPaper, $searchTags));
        $papers = Paper::whereIn('id', $allValue)->get();
        return view('frontend.templates.paper.searchResult', compact('papers'));
    }

    public function pageDetail($alias, $page_id)
    {
        $key = 'paper_detail' . $page_id;
        $paper = Cache::remember($key, 15, fn () => $this->paper->find($page_id));

        $category = Cache::remember('category.alias.like', 15, function () {
            return Category::where("url_alias", "like", "today")->get()->first();
        });
        $list_center = Cache::remember('listCenter.alias.like', 15, fn () => Category::where("url_alias", "like", 2)->take(4)->get());
        $papers = Cache::remember('papers_detail' . $page_id, 15, fn () => $category->get_papers(4, 0, $order_by = ["updated_at", "DESC"]));
        $top_paper = $papers->take(2);
        $papers = $papers->diff($top_paper);
        event(new ViewCount($paper));
        return view("frontend.templates.paper.paper_detail", compact("paper", "list_center", "top_paper", "papers"));
    }

    public function categoryView($category_id)
    {
        $category = Category::where("url_alias", "like", $category_id)->get()->first();
        $papers = $category->get_papers(4, 0, $order_by = ["updated_at", "DESC"]);
        event(new ViewCount($category));
        return view("frontend/templates/categories", compact("category", "papers"));
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

    function apiSourcePapers(Request $request)
    {
        // $papers = Paper::paginate($request->get("limit",  4))->orderBy("updated_at", "DESC");
        $papers = $this->paper->orderBy('updated_at', 'desc')->paginate(4);
        $data = $papers->toArray();
        if ($data["data"]) {
            foreach ($data["data"] as &$item) {
                $item["image_path"] = $this->helperFunction->replaceImageUrl($item["image_path"] ?: '');
                $item["short_conten"] = $this->cut_str($item["short_conten"], 90, "...");
                // $item["title"] = $this->cut_str($item["title"], 80, "../");
            }
        }
        return $data;
    }

    public function getPaperDetail($paper_id)
    {
        if (Cache::has("api_detail_$paper_id")) {
            return  Cache::get("api_detail_$paper_id");
        } else {
            $detail = $this->paper->find($paper_id);
            Cache::put("api_detail_$detail->id", $detail);
            return $detail;
        }
    }

    public function getCategoryTop()
    {
        $top_category = ConfigCategory::where("path", "=", ConfigCategory::TOP_CATEGORY);
        $values = Category::whereIn("id", explode("&", $top_category->first()->value))->get()->toArray();
        foreach ($values as &$value) {
            $value["image_path"] = $this->helperFunction->replaceImageUrl($value["image_path"] ?: '');
        }
        return $values;
    }

    public function getPaperCategory($category_id, Request $request)
    {
        $page = $request->get("page", 1);
        $limit = $request->get("limit", 4);
        $key = "paper.category.$category_id.$page.$limit";

        if (Cache::has($key)) { // nhanh hon ~50% voi du lieu nang.
            return Cache::get($key);
        } else {
            /**
             * @var \App\Models\Category $category
             */
            $category = $this->category->find($category_id);
            $papers = $category->setSelectKey(["id", "title", "short_conten", "image_path"])->get_papers($limit, $page - 1)->toArray();
            foreach ($papers as &$value) {
                $value["image_path"] = $this->helperFunction->replaceImageUrl($value["image_path"] ?: '');
            }
            Cache::put($key, $papers);
            return $papers;
        }
    }

    function getRelatedPaper()
    {
        $papers = Paper::all()->random(5)->toArray();
        foreach ($papers as &$value) {
            $value["image_path"] = $this->helperFunction->replaceImageUrl($value["image_path"] ?: '');
        }
        return ['data' => $papers];
    }

    function getCategoryTree(Request $request)
    {
        $category_id = $request->get("category_id", 0);
        if ($category_id) {
            $category = $this->category->find($category_id);
            $categories = $category->getCategoryTree();
        } else {
            $categories = $this->category->getCategoryTree(true);
        }
        return $categories;
    }

    function parseUrl(Request $request)
    {
        $url = $request->get('url', 'tuyen-viet-nam-dau-hong-kong-hlv-troussier-gay-bat-ngo');
        $paper = Paper::where('url_alias', '=', $url)->first();
        return $paper;
    }

    function mostviewdetail(Request $request)
    {
        $papers = Paper::take($request->get('size', 15))->orderBy("updated_at", "ASC")->get(['id', 'title', 'image_path', 'updated_at', 'url_alias']);
        foreach ($papers as &$value) {
            $value->url = route('front_page_detail', ['alias' => $value->url_alias, 'page' => $value->id]);
        }
        return $papers;
    }

    function mostPopulator()
    {
        $mostPopulatorHtml = $this->mostPopulator->toHtml();
        return [
            'code' => 200,
            'dataHtml' => $mostPopulatorHtml
        ];
    }

    function likeMost()
    {
        $likeMostHtml = $this->likeMost->toHtml();
        return [
            'code' => 200,
            'dataHtml' => $likeMostHtml
        ];
    }

    function trending() {
        $trendingHtml = $this->trending->toHtml();
        return [
            'code' => 200,
            'dataHtml' => $trendingHtml
        ];
    }
}
