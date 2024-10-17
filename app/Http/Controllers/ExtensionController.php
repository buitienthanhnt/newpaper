<?php

namespace App\Http\Controllers;

use App\Api\CategoryApi;
use App\Api\Data\Paper\Conten;
use App\Api\Data\Paper\Info;
use App\Api\Data\Paper\PaperDetail;
use App\Api\Data\Paper\PaperItem;
use App\Api\Data\Paper\Tag;
use App\Api\Data\Response as ApiResponse;
use App\Api\ManagerApi;
use App\Api\PaperApi;
use App\Api\PaperRepository;
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
use App\Models\PaperContentInterface;
use App\Models\PaperInterface;
use App\Models\PaperTagInterface;
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
    protected $managerApi;
    protected $categoryApi;

    protected $tokenManager;
    protected $helperFunction;

    protected $mostPopulator;
    protected $likeMost;
    protected $trending;

    protected $cartService;

    protected $paperRepository;
    protected $apiResponse;

    public function __construct(
        Request $request,
        Paper $paper,
        Category $category,
        RemoteSourceManager $remoteSourceManager,
        PaperApi $paperApi,
        WriterApi $writerApi,
        CategoryApi $categoryApi,
        HelperFunction $helperFunction,
        User $user,
        TokenManager $tokenManager,
        MostPopulator $mostPopulator,
        LikeMost $likeMost,
        Trending $trending,
        CartService $cartService,
        PaperRepository $paperRepository,
        ApiResponse $apiResponse,
        ManagerApi $managerApi
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
        $this->paperRepository = $paperRepository;
        $this->apiResponse = $apiResponse;
        $this->managerApi = $managerApi;
        $this->categoryApi = $categoryApi;
    }

    /**
     * @return ApiResponse|m.\App\Api\Data\Response.setData
     * @throws \Exception
     */
    public function homeInfo()
    {
        $apiResponse = $this->apiResponse;
        $apiResponse->setMessage('newst home data api!');
        return $apiResponse->setResponse($this->managerApi->homeInfo());
    }

    /**
     * @return ApiResponse
     */
    public function listPapers()
    {
        $apiResponse = $this->apiResponse;
        $apiResponse->setResponse($this->paperRepository->paperAll());
        return $apiResponse;
    }

    /**
     * @param int $paper_id
     */
    public function getPaperDetail(int $paper_id)
    {
        $apiResponse = $this->apiResponse;
        $apiResponse->setResponse($this->paperRepository->getById($paper_id));
        return $apiResponse;
    }

    /**
     * lấy bài viết theo thể loại.
     * @param int $category_id
     * @return ApiResponse
     */
    function getPaperCategory($category_id)
    {
        $request = $this->request;
        $page = $request->get("page", 1);
        $limit = $request->get("limit", 12);
        $key = "paper.category.$category_id.$page.$limit";

        $apiResponse = $this->apiResponse;
        $responseData = null;
        if (Cache::has($key) && false) { // nhanh hon ~50% voi du lieu nang.
            $responseData = Cache::get($key);
        } else {
           $responseData = $this->paperRepository->getPaperByCategory($category_id);
            Cache::put($key, $responseData);
        }
        return $apiResponse->setResponse($responseData);
    }

    /**
     * @param int $category_id
     * @return ApiResponse|m.\App\Api\Data\Response.setData
     */
    function getCategoryInfo(int $category_id){
        $apiResponse = $this->apiResponse;
        return $apiResponse->setResponse($this->categoryApi->getCategoryById($category_id));
    }

    /**
     * @return ApiResponse
     */
    public function getCategoryTree()
    {
        $apiResponse = $this->apiResponse;
        $apiResponse->setResponse($this->categoryApi->getCategoryTree());
        return $apiResponse;
    }

    /**
     * @return ApiResponse
     */
    public function getCategoryTop()
    {
        $apiResponse = $this->apiResponse;
        $apiResponse->setResponse($this->categoryApi->getCategoryTop());
        return $apiResponse;
    }

    /**
     * @param int $writer_id
     * @return ApiResponse|m.\App\Api\Data\Response.setData
     */
    public function getPaperByWriter(int $writer_id)
    {
        $apiResponse = $this->apiResponse;
        return $apiResponse->setResponse($this->writerApi->getPapers($writer_id));
    }

    /**
     * @param int $paper_id
     * @return ApiResponse
     */
    public function getRelatedPaper(int $paper_id)
    {
        return $this->paperApi->getRelatedPaper($paper_id);
    }

    /**
     * @return ApiResponse
     */
    public function search()
    {
        return $this->apiResponse->setResponse($this->paperApi->searchAll());
    }

    /**
     * @return ApiResponse
     */
    public function getWriterList()
    {
        return $this->apiResponse->setResponse($this->writerApi->listWriter());
        // TODO: Implement getWriterList() method.
    }

    // =============================================================================

    public function getCommentsOfPaper($paper_id)
    {
        $request = $this->request;
        /**
         * @var Paper $paper
         */
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
