<?php

namespace App\Http\Controllers;

use App\Api\PaperApi;
use App\Api\WriterApi;
use App\Events\ViewCount;
use App\Helper\HelperFunction;
use App\Services\CartService;
use Thanhnt\Nan\Helper\DomHtml;
use App\Helper\ImageUpload;
use App\Models\Category;
use App\Models\CategoryInterface;
use App\Models\ConfigCategory;
use App\Models\Paper;
use App\Models\User;
use App\Models\Writer;
use App\ViewBlock\LikeMost;
use App\ViewBlock\MostPopulator;
use App\ViewBlock\Trending;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use ReflectionClass;
use Thanhnt\Nan\Helper\RemoteSourceManager;
use Thanhnt\Nan\Helper\StringHelper;
use Thanhnt\Nan\Helper\TokenManager;

// https://www.php.net/manual/en/langref.php php
class ExtensionController extends Controller implements ExtensionControllerInterface
{
    use DomHtml;
    use ImageUpload;
    use StringHelper;

    protected $user;
    protected $request;
    protected $paper;
    protected $category;
    protected $remoteSourceManager;

    protected $paperApi;
    protected $writerApi;
    protected $tokenManager;
    protected $helperFunction;

    protected $mostPopulator;
    protected $likeMost;
    protected $trending;

    protected $cartService;

    public function __construct(
        Request $request,
        Paper $paper,
        Category $category,
        RemoteSourceManager $remoteSourceManager,
        PaperApi $paperApi,
        WriterApi $writerApi,
        HelperFunction $helperFunction,
        User $user,
        TokenManager $tokenManager,
        MostPopulator $mostPopulator,
        LikeMost $likeMost,
        Trending $trending,
        CartService $cartService
    ) {
        $this->request = $request;
        $this->paper = $paper;
        $this->category = $category;
        $this->remoteSourceManager = $remoteSourceManager;
        $this->paperApi = $paperApi; // new for api
        $this->writerApi = $writerApi;
        $this->helperFunction = $helperFunction;
        $this->user = $user;
        $this->tokenManager = $tokenManager;
        $this->mostPopulator = $mostPopulator;
        $this->likeMost = $likeMost;
        $this->trending = $trending;
        $this->cartService = $cartService;
    }

    public function homeInfo()
    {
        return $this->paperApi->homeInfo();
    }

    public function listPapers()
    {
        // not use ->makeHidden() with paginate
        $papers = $this->paper->orderBy('updated_at', 'desc')->paginate(12);
        if ($papers->count()) {
            foreach ($papers as &$item) {
                $item->image_path = $this->helperFunction->replaceImageUrl($item->image_path ?: '');
                $item->short_conten = $this->cut_str($item->short_conten, 90, "...");
                // $item["title"] = $this->cut_str($item["title"], 80, "../");
                $item->info = [
                    'view_count' => $item->viewCount(),
                    'comment_count' => $item->commentCount(),
                    'like' => $item->paperLike(),
                    'heart' => $item->paperHeart(),
                ];
            }
        }
        return $papers;
    }

    public function getCategoryTree()
    {
        $category_id = $this->request->get("category_id", 0);
        if ($category_id) {
            $category = $this->category->find($category_id);
            $categories = $category->getCategoryTree();
        } else {
            $categories = $this->category->getCategoryTree(true);
        }
        return $categories;
    }

    public function getCategoryTop()
    {
        $top_category = ConfigCategory::where("path", "=", ConfigCategory::TOP_CATEGORY);
        $values = Category::whereIn(CategoryInterface::ATTR_PRIMARY, explode("&", $top_category->first()->value))->get()->toArray();
        foreach ($values as &$value) {
            $value["image_path"] = $this->helperFunction->replaceImageUrl($value["image_path"] ?: '');
        }
        return $values;
    }

    function getPaperCategory($category_id)
    {
        $request = $this->request;
        $page = $request->get("page", 1);
        $limit = $request->get("limit", 12);
        $key = "paper.category.$category_id.$page.$limit";

        if (Cache::has($key)) { // nhanh hon ~50% voi du lieu nang.
            return Cache::get($key);
        } else {
            /**
             * @var \App\Models\Category $category
             */
            $category = $this->category->find($category_id);
            $papers = $category->setSelectKey(["id", "title", "short_conten", "image_path"])->getPaperPaginate($limit, $page - 1, ['updated_at', 'desc'], ['conten']);
            foreach ($papers as &$value) {
                $value->image_path = $this->helperFunction->replaceImageUrl($value->image_path ?: '');
                $value->info = [
                    'view_count' => $value->viewCount(),
                    'comment_count' => $value->commentCount(),
                    'like' => $value->paperLike(),
                    'heart' => $value->paperHeart(),
                ];
            }
            $papers = $papers->toArray();
            Cache::put($key, $papers);
            return $papers;
        }
    }

    public function getRelatedPaper($paper_id)
    {
        $papers = null;

        try {
            $papers = Paper::all()->random(5)->toArray();
            foreach ($papers as &$value) {
                $value["image_path"] = $this->helperFunction->replaceImageUrl($value["image_path"] ?: '');
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        return ['data' => $papers];
    }

    public function getCommentsOfPaper($paper_id)
    {
        $request = $this->request;
        $paper = $this->paper->find($paper_id);
        if ($request->get('all')) {
            $comments = $paper->getCommentTree($request->get('parent_id', null), 0, 0);
        } else {
            $comments = $paper->getCommentTree($request->get('parent_id', null), $request->get('p', 0), $request->get('limit', 4));
        }
        return [
            'success' => true,
            'data' => $comments,
            'errors' => null
        ];
    }

    public function search()
    {
        return $this->paperApi->searchAll();
    }

    public function getPaperByWriter(int $writer_id)
    {
        $papers = $this->writerApi->getPapers($writer_id);
        return $papers;
    }

    public function getPaperMostView()
    {
        $request = $this->request;
        $papers = Paper::take($request->get('size', 15))->orderBy("updated_at", "ASC")->get(['id', 'title', 'image_path', 'updated_at', 'url_alias']);
        foreach ($papers as &$value) {
            $value->url = route('front_paper_detail', ['alias' => $value->url_alias, 'paper_id' => $value->id]);
            $value->image_path = $value->getImagePath();
        }
        return $papers;
    }

    function login()
    {
        $request = $this->request;
        $email = $request->get('email');
        $password = $request->get('password');
        if (!($email && $password)) {
            return response([
                "message" => "invalid email or password"
            ], 400);
        }
        if (Auth::check()) {
            return response([
                "message" => "người dùng đã đăng nhập, không thể thực hiện thêm!"
            ], 403);
        }

        if (Auth::attempt([
            'email' => $email,
            "password" => $password
        ])) {
            $user = $this->user->where("email", $email)->first();
            Auth::login($user);
            $userData = $user->toArray();
            $userData["sid"] = Session::getId();

            $token = $this->tokenManager->getToken($userData);
            $refreshToken = $this->tokenManager->getRefreshToken($userData);
            return response([
                "message" => "login success!!!",
                "userData" => $user,
                "token" => $token,
                "refresh_token" => $refreshToken
            ], 200);
        }
        return response([
            'message' => "login fail, error email or password",
        ], 400);
    }

    public function getUserInfo()
    {
        $tokenData = (array) $this->tokenManager->getTokenData()['iss'];
        if (isset($tokenData['id']) && $userId = $tokenData['id']) {
            return response([
                'message' => null,
                'userData' => Auth::user()
            ], 200);
        }
        return response([
            'message' => null,
            'userData' => null
        ], 200);
    }

    /**
     * @param int $paper_id
     */
    public function getPaperDetail(int $paper_id)
    {
        // TODO: Implement getPaperDetail() method.
        $covertContentData = function ($datas) {
            if (empty($datas)) {
                return null;
            }
            $convertSliderdata = function ($slider_datas) {
                foreach ($slider_datas as &$value) {
                    $value['value'] = $this->helperFunction->replaceImageUrl($value['image_path']);
                }
                return $slider_datas;
            };

            $return_data = [];
            for ($i = 0; $i < count($datas); $i++) {
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
            $detail->contents = $covertContentData($detail->getContents()->toArray());
            $detail->suggest = $this->formatSug(Paper::all()->random(4)->makeHidden('conten')->toArray());
            $detail->info = $detail->paperInfo();
            $detail->tags = $detail->getTags();
            $detail->slider_images = array_map(function ($item) {
                $item['value'] = $this->helperFunction->replaceImageUrl($item['value']);
                return $item;
            }, $detail->sliderImages()->toArray());
            $detail->url = $this->helperFunction->replaceImageUrl(route('front_paper_detail', ['alias' => $detail->url_alias, 'paper_id' => $detail->id]));
            Cache::put("api_detail_$detail->id", $detail);
            event(new ViewCount($detail));
            return $detail;
        }
    }

    /**
     * thêm 1 item vào cart
     */
    function addToCart()
    {
        $cartData = $this->cartService->addCart($this->request->get('id'));
        return $cartData;
    }

    /**
     * lấy cart data
     */
    function getCart()
    {
        return $this->cartService->getCart();
    }

    /**
     * xóa hết item trong cart
     */
    function clearCart()
    {
        $this->cartService->clearCart();
        return $this->getCart();
    }

    /**
     * xóa 1 item trong cart.
     * @param int $item_id
     */
    function removeCartItem($item_id)
    {
        $this->cartService->xoaItem($item_id);
        return $this->cartService->getCart();
    }

    static function getConstants()
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }

    // ==============================================================

    function formatSug($data)
    {
        return array_chunk(array_map(function ($item) {
            $item['image_path'] = $this->helperFunction->replaceImageUrl($item['image_path']);
            return $item;
        }, $data), 2);
    }

    function download()
    {
        $file = public_path() . "/vendor/app-release.apk";
        // $headers = array('Content-Type: application/pdf');
        return Response::download($file, 'app-release.apk');
    }

    function sendMail()
    {
        try {
            // Mail::to('buisuphu01655@gmail.com')->send(new UserEmail());  // php artisan make:mail UserEmail
            Mail::send(
                'welcome',
                [],
                function ($message) {
                    $message->from('buitienthanhnt@gmail.com', "tha nan");
                    $message->to("thanh.bui@jmango360.com", 'user1');
                    $message->subject("demo by send mail laravel newpaper");
                }
            );
        } catch (\Throwable $th) {
            //throw $th;
            echo ($th->getMessage());
            return;
        }
        return 123;
    }

    /**
     * upload image from mobile(cli4)
     */
    function uploadImageFromMobile(Request $request)
    {
        $res_data = null;
        if ($files = $request->__get("upload_file")) {
            // multi files:
            foreach ($files as $file) {
                $image_upload_path = '';
                $image_upload_path = $this->uploadImage($file, "public/images/cli4Mb");
                $res_data[] = $image_upload_path;
            }
            return [
                'path' => $res_data,
                'code' => 200
            ];
        }
        return [
            'path' => $res_data,
            'code' => 500
        ];
    }

    function obser(Request $request)
    {
        $template = $request->get('type', 'observObj');
        return view("frontend.templates.test.knockout.$template");
    }
}
