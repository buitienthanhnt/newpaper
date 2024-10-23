<?php

namespace App\Http\Controllers\Frontend;

use App\Api\CategoryApi;
use App\Api\CommentApi;
use App\Api\Data\Response as ApiResponse;
use App\Api\ManagerApi;
use App\Api\PaperApi;
use App\Api\PaperRepository;
use App\Api\ResponseApi;
use App\Api\UserApi;
use App\Api\WriterApi;
use App\Helper\HelperFunction;
use App\Services\CartService;
use Thanhnt\Nan\Helper\DomHtml;
use App\Helper\ImageUpload;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Paper;
use App\Models\User;
use App\ViewBlock\LikeMost;
use App\ViewBlock\MostPopulator;
use App\ViewBlock\Trending;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
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
    protected $commentApi;
    protected $userApi;

    protected $tokenManager;
    protected $helperFunction;

    protected $mostPopulator;
    protected $likeMost;
    protected $trending;

    protected $cartService;

    protected $paperRepository;
    protected $apiResponse;
    protected $responseApi;

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
        ManagerApi $managerApi,
        CommentApi $commentApi,
        UserApi $userApi,
        ResponseApi $responseApi
    ) {
        $this->request = $request;
        $this->paper = $paper;
        $this->category = $category;
        $this->remoteSourceManager = $remoteSourceManager;
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
        $this->userApi = $userApi;
        $this->paperApi = $paperApi; // new for api
        $this->writerApi = $writerApi;
        $this->commentApi = $commentApi;
        $this->responseApi = $responseApi;
    }

    /**
     * @return ApiResponse|m.\App\Api\Data\Response.setData
     * @throws \Exception
     */
    public function homeInfo()
    {
        $apiResponse = $this->apiResponse;
        try {
            $apiResponse->setMessage('newst home data api!');
            return $this->responseApi->setResponse($apiResponse->setResponse($this->managerApi->homeInfo()));
        } catch (\Throwable $th) {
            return $this->responseApi->setResponse($apiResponse->setMessage($th->getMessage()))->setStatusCode(500);
        }
    }

    /**
     * @return ApiResponse
     */
    public function search()
    {
        return $this->apiResponse->setResponse($this->paperApi->searchAll());
    }

    public function getUserInfo()
    {
        return $this->userApi->getUserInfo();
    }

    function login()
    {
       return $this->userApi->logIn();
    }

    // =============================================================================

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
