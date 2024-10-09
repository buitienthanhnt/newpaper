<?php

namespace App\Http\Controllers;

use App\Api\PaperApi;
use App\Api\WriterApi;
use App\Events\ViewCount;
use Thanhnt\Nan\Helper\DomHtml;
use App\Models\Category;
use App\Models\ConfigCategory;
use App\Models\PageTag;
use App\Models\Paper;
use Illuminate\Http\Request;
use App\Helper\HelperFunction;
use App\Models\PaperInterface;
use App\Models\User;
use App\ViewBlock\LikeMost;
use App\ViewBlock\MostPopulator;
use App\ViewBlock\Trending;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Thanhnt\Nan\Helper\TokenManager;

class ManagerController extends Controller implements ManagerControllerInterface
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
    protected $paperApi;
    protected $writerApi;
    protected $tokenManager;
    protected $user;
    protected $auth;

    public function __construct(
        Request $request,
        Paper $paper,
        \App\Models\Category $category,
        PageTag $pageTag,
        HelperFunction $helperFunction,
        MostPopulator $mostPopulator,
        LikeMost $likeMost,
        Trending $trending,
        PaperApi $paperApi,
        WriterApi $writerApi,
        TokenManager $tokenManager,
        User $user,
        Auth $auth
    ) {
        $this->request = $request;
        $this->paper = $paper;
        $this->category = $category;
        $this->pageTag = $pageTag;
        $this->helperFunction = $helperFunction;
        $this->mostPopulator = $mostPopulator;
        $this->likeMost = $likeMost;
        $this->trending = $trending;
        $this->paperApi = $paperApi;
        $this->writerApi = $writerApi;
        $this->tokenManager = $tokenManager;
        $this->user = $user;
        $this->auth = $auth;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
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
                $list_papers = array_unique([...array_column($page_category, Paper::PRIMARY_ALIAS), ...$list_papers]);
            }
        }
        // $video_contens = $this->paper->orderBy("updated_at", "DESC")->take(3)->get();
        // $video_contens = [
        //     ['url' => "https://www.youtube.com/embed/lhYztX6cdg8", "title" => "demo 1"],
        //     ['url' => "https://www.youtube.com/embed/o7o60G-jN54", "title" => "demo 2"],
        //     ['url' => "https://www.youtube.com/embed/EJi1k_Yunco", "title" => "demo 3"],
        //     ['url' => "https://www.youtube.com/embed/9GaIAYhGTE0", "title" => "demo 4"],
        //     ['url' => "https://www.youtube.com/embed/sKdpqk7o5ac", "title" => "demo 5"],
        //     ['url' => "https://www.youtube.com/embed/lovblkkDVDU", "title" => "demo 6"],
        // ];
        return view("frontend/templates/homeContent", compact("list_center", "video_contens"));
    }

    /**
     * @param string $category_alias
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|mixed
     */
    public function categoryView($category_alias)
    {
        $category = Category::where("url_alias", "like", $category_alias)->get()->first();
        $papers = $category->get_papers(4, 0, $order_by = ["updated_at", "DESC"]);
        event(new ViewCount($category));
        return view("frontend/templates/categories", compact("category", "papers"));
    }

    /**
     * @param $alias
     * @param $page_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function paperDetail($alias, $page_id)
    {
        $key = 'paper_detail' . $page_id;
        $paper = Cache::remember($key, 15, fn () => $this->paper->find($page_id));
        $category = Cache::remember('category.alias.like', 15, function () {
            return Category::all()->random(1)->first();
        });

        $list_center = Cache::remember('listCenter.alias.like', 15, fn () => Category::where("url_alias", "like", 2)->take(4)->get());
        $papers = Cache::remember('papers_detail' . $page_id, 15, fn () => $category->get_papers(4, 0, $order_by = ["updated_at", "DESC"]));
        $top_paper = $papers->take(2);
        $papers = $papers->diff($top_paper);
        event(new ViewCount($paper));
        return view("frontend.templates.paper.paper_detail", compact("paper", "list_center", "top_paper", "papers"));
    }

    /**
     * @param string $tag
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|mixed
     */
    public function tagView($tag)
    {
        $papers = null;
        $paper_ids = $this->pageTag->to_paper($tag);
        if ($paper_ids) {
            $papers = $this->paper::whereIn(PaperInterface::ATTR_PRIMARY, $paper_ids)->get();
        }
        $trending_left = $papers->first();
        return view("frontend/templates/tags", ["tag" => $tag, "papers" => $papers, "trending_left" => [$trending_left]]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    function search()
    {
        $papers = $this->paperApi->searchAll();
        return view('frontend.templates.paper.searchResult', compact('papers'));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function loadMore()
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

    function formatSug($data)
    {
        return array_chunk(array_map(function ($item) {
            $item['image_path'] = $this->helperFunction->replaceImageUrl($item['image_path']);
            return $item;
        }, $data), 2);
    }

    public function getPaperDetail($paper_id)
    {
        $covertContentData = function($datas){
            if (empty($datas)) {
                return null;
            }
            $convertSliderdata = function($slider_datas){
                foreach ($slider_datas as &$value) {
                    $value['value'] = $this->helperFunction->replaceImageUrl($value['image_path']);
                }
                return $slider_datas;
            };

            $return_data = [];
            for ($i=0; $i < count($datas); $i++) {
                switch ($datas[$i]['type']) {
                    case 'image':
                        $return_data[] = [
                            ...$datas[$i],
                            'value' => $this->helperFunction->replaceImageUrl($datas[$i]['value'] ?? '')
                        ];
                        break;
                    case 'slider_data':
                        $return_data[] = [
                            ...$datas[$i],
                            'value' => $convertSliderdata(json_decode($datas[$i]['value'], true))
                        ];
                        break;
                    default:
                        $return_data[] = $datas[$i];
                        break;
                }
            }
            return $return_data;
        };

        if (Cache::has("api_detail_$paper_id")) {
            $paper =  Cache::get("api_detail_$paper_id");
            event(new ViewCount($paper));
            return $paper;
        } else {
            /**
             * @var Paper $detail
             */
            $detail = $this->paper->find($paper_id);
            $detail->contents = $covertContentData($detail->to_contents()->toArray());
            $detail->suggest = $this->formatSug(Paper::all()->random(4)->makeHidden('conten')->toArray());
            $detail->info = $detail->paperInfo();
            $detail->tags = $detail->to_tag()->getResults();
            $detail->slider_images = array_map(function ($item) {
                $item->value = $this->helperFunction->replaceImageUrl($item->value);
                return $item;
            }, $detail->sliderImages()->toArray());
            $detail->url = $this->helperFunction->replaceImageUrl(route('front_paper_detail', ['alias' => $detail->url_alias, 'page' => $detail->id]));
            Cache::put("api_detail_$detail->id", $detail);
            event(new ViewCount($detail));
            return $detail;
        }
    }

    function parseUrl(Request $request)
    {
        $url = $request->get('url', 'tuyen-viet-nam-dau-hong-kong-hlv-troussier-gay-bat-ngo');
        $paper = Paper::where('url_alias', '=', $url)->first();
        return $paper;
    }

    // {{url}}/api/upFirebaseComments/122
    function upFirebaseComments($paper_id, Request $request)
    {
        $paper = $this->paper->find($paper_id);
        $this->paperApi->upFirebaseComments($paper);
        return [
            'success' => true,
            'errors' => null
        ];
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

    function trending()
    {
        $trendingHtml = $this->trending->toHtml();
        return [
            'code' => 200,
            'dataHtml' => $trendingHtml
        ];
    }

    // pullFirebaseComment
    function pullFirebaseComment()
    {
        $this->paperApi->pullFirebaseComment();
    }

    // {{url}}/api/pullFirebasePaperLike
    function pullFirebasePaperLike()
    {
        $this->paperApi->pullFirebasePaperLike();
    }

    // {{url}}/api/pullFirebaseComLike
    function pullFirebaseComLike()
    {
        $this->paperApi->pullFirebaseComLike();
    }

    function getToken(Request $request): \Illuminate\Http\Response
    {
        if ($request->get('api_key', null) !== $this->tokenManager->get_serect_key()) {
            return response([
                'message' => "api key not found"
            ], 400);
        }

        return response([
            'message' => 'success',
            'token' => $this->tokenManager->getToken([
                'sid' => Session::getId()
            ]),
            'refresh_token' => $this->tokenManager->getRefreshToken([
                'sid' => Session::getId()
            ])
        ]);
    }

    function refreshUserToken(Request $request): \Illuminate\Http\Response
    {
        $refreshToken = $request->get('refresh_token', null);
        if ($refreshToken) {
            $refreshTokenData = $this->tokenManager->getTokenData($refreshToken);
            if (empty($refreshTokenData) || !isset($refreshTokenData['iss'])) {
                return response([
                    'message' => 'refresh token fail!'
                ], 402);
            }

            $dataValue = (array) $refreshTokenData['iss'];
            return response([
                'message' => 'success for refreshToken',
                'token' => $this->tokenManager->getToken(
                    $dataValue
                ),
                'refresh_token' => $this->tokenManager->getRefreshToken(
                    $dataValue
                )
            ], 200);
        }

        return response([
            'message' => 'refresh token fail!'
        ], 402);
    }

    function getTokenData()
    {
        $token = $this->tokenManager->getTokenAuthor();
        if (empty($token)) {
            return response()->json([
                'message' => 'token Authorization is missing. Please set token and try again!'
            ], 403);
        }
        if ($value = $this->tokenManager->getTokenData($token)) {
            $value['iat'] = date("Y-m-d H:i:s", $value['iat']);
            $value['exp'] = date("Y-m-d H:i:s", $value['exp']);
            return ["value" => $value];
        }

        return response()->json([
            'message' => 'token expire. Please refresh token and try again!'
        ], 401);
    }
}
