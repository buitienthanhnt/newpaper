<?php
namespace App\Http\Controllers\Api;

use App\Api\CategoryApi;
use App\Api\Data\Response;
use App\Api\PaperRepository;
use App\Api\ResponseApi;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CategoryApiController extends BaseController implements CategoryApiControllerInterface
{

    protected $request;
    protected $response;
    protected $responseApi;

    protected $categoryApi;
    protected $paperRepository;

    function __construct(
        Request $request,
        Response $response,
        ResponseApi $responseApi,
        CategoryApi $categoryApi,
        PaperRepository $paperRepository
    )
    {
        $this->request = $request;
        $this->response = $response;
        $this->responseApi = $responseApi;
        $this->categoryApi = $categoryApi;
        $this->paperRepository = $paperRepository;
    }
    /**
     * @param int $category_id
     * @return ApiResponse|m.\App\Api\Data\Response.setData
     */
    function getCategoryInfo(int $category_id)
    {
        $responseApi = $this->responseApi;
        return $responseApi->setResponse($this->response->setResponse($this->categoryApi->getCategoryById($category_id)));
    }

    /**
     * @return ApiResponse
     */
    public function getCategoryTree()
    {
        $responseApi = $this->responseApi;
        $responseApi->setResponse($this->response->setResponse($this->categoryApi->getCategoryTree()));
        return $responseApi;
    }

    /**
     * @return ApiResponse
     */
    public function getCategoryTop()
    {
        $responseApi = $this->responseApi;
        $responseApi->setResponse($this->response->setResponse($this->categoryApi->getCategoryTop()));
        return $responseApi;
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

        $responseApi = $this->responseApi;
        $responseData = null;
        if (Cache::has($key) && false) { // nhanh hon ~50% voi du lieu nang.
            $responseData = Cache::get($key);
        } else {
            $responseData = $this->paperRepository->getPaperByCategory($category_id);
            Cache::put($key, $responseData);
        }
        return $responseApi->setResponse($this->response->setResponse($responseData));
    }
}
