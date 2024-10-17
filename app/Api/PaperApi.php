<?php

namespace App\Api;

use App\Api\Data\Paper\PaperItem;
use App\Api\Data\Response as ApiResponse;
use App\Helper\HelperFunction;
use App\Models\Paper;
use App\Models\PaperInterface;
use App\Models\ViewSource;
use App\Models\PaperTag;
use App\Models\PaperContent;
use App\Models\PaperTagInterface;
use App\Models\ViewSourceInterface;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class PaperApi extends BaseApi
{
    protected $helperFunction;
    protected $request;
    /**
     * @var PaperContent $paperContent
     */
    protected $paperContent;

    protected $paperRepository;

    protected $paper;

    protected $apiResponse;


    function __construct(
        HelperFunction $helperFunction,
        Request $request,
        PaperContent $paperContent,
        PaperRepository $paperRepository,
        Paper $paper,
        ApiResponse $apiResponse
    )
    {
        $this->helperFunction = $helperFunction;
        $this->request = $request;
        $this->paperContent = $paperContent;
        $this->paperRepository = $paperRepository;
        $this->paper = $paper;
        $this->apiResponse = $apiResponse;
    }

    /**
     * lấy các bài viết nhiều lượt xem nhất.
     * dựa trên viewSource
     */
    function mostPopulator()
    {
        $mostView = ViewSource::where(ViewSourceInterface::ATTR_TYPE, ViewSource::TYPE_PAPER)->orderBy(ViewSourceInterface::ATTR_VALUE, 'DESC')->limit(8)->pluck(ViewSourceInterface::ATTR_SOURCE_ID);
        $mostPopulator = [];
        foreach (Paper::find($mostView->toArray()) as $item) {
            $mostPopulator[] = $this->paperRepository->convertPaperItem($item);
        }
        return $mostPopulator;
    }

    /**
     * lấy 8 bài viết tạo mới nhất.
     */
    function mostRecents()
    {
        $mostRecentData = null;
        $mostRecents = Paper::limit(8)->orderBy('created_at', 'DESC')->get();
        foreach ($mostRecents as $item) {
            $mostRecentData[] = $this->paperRepository->convertPaperItem($item);
        }
        return $mostRecentData;
    }

    /**
     * lấy bài viết mới nhất.
     */
    function hit()
    {
        $hit = Paper::all()->last();
        return $this->paperRepository->convertPaperItem($hit);
    }

    /**
     * lấy ngẫu nhiên 1 bài viết.
     */
    function forward()
    {
        $forward = Paper::all()->random(1)->first();
        return $this->paperRepository->convertPaperItem($forward);
    }

    /**
     * lấy ngẫu nhiên 5 bài viết
     */
    function listImages()
    {
        $listImagesData = null;
        try {
            $listImages = Paper::all()->random(5);
            foreach ($listImages as $item) {
                $listImagesData[] = $this->paperRepository->convertPaperItem($item);
            }
            return $listImagesData;
        } catch (\Throwable $th) {
            //throw $th;
        }
        return null;
    }

    /**
     * lấy bài viết kiểu timeline(sự kiện sắp diễn ra).
     */
    function timeLine()
    {
        $dt = Carbon::now('Asia/Ho_Chi_Minh');
        /**
         * @var Collection $timeLine
         */
        $timeLine = $this->paperContent
            ->where(PaperContent::ATTR_TYPE, PaperContent::TYPE_TIMELINE)
            ->where(PaperContent::ATTR_VALUE, ">=", $dt->toDateTimeString())
            ->orderBy(PaperContent::ATTR_VALUE, 'ASC')
            ->take(8)
            ->get();
        $papers = [];
        foreach ($timeLine as $time) {
            /**
             * @var PaperContent $time
             */
            $paper = $time->getPaper();
            $paper->image_path = $this->helperFunction->replaceImageUrl($paper->image_path);
            $paper->time = date_format(new DateTime($time->value), "d-m-Y H:i");
            $paper->description = $paper->title;
            $papers[] = $paper;
        }
        return $papers;
    }

    /**
     * @return string[]
     */
    function tags(): array
    {
        $tags = PaperTag::all()->unique(PaperTagInterface::ATTR_VALUE)->take(8)->pluck(PaperTagInterface::ATTR_VALUE)->toArray();
        return $tags;
    }

    /**
     * @return PaperItem[]
     */
    function searchAll()
    {
        return $this->search($this->request->get('search', $this->request->get('query')));
    }

    /**
     * @param string $query
     * @return PaperItem[]
     */
    function search(string $query)
    {
        $searchValues = [];
        $queryValue = strtolower($query);
        $searchPaper = array_column(Paper::where(PaperInterface::ATTR_TITLE, 'LIKE', "%$queryValue%")
            ->orWhere(PaperInterface::ATTR_SHORT_CONTENT, 'LIKE', "%$queryValue%")->get('id')->toArray(), 'id') ?: [];

        $searchTags = array_column(PaperTag::where(PaperTagInterface::ATTR_VALUE, 'LIKE', "%$queryValue%")->get('entity_id')->toArray(), 'entity_id') ?: [];
        $allValue = array_unique(array_merge($searchPaper, $searchTags));
        foreach (Paper::find($allValue) as $paper) {
            $searchValues[] = $this->paperRepository->convertPaperItem($paper);
        }
        return $searchValues;
    }

    /**
     * @param int $paper_id
     * @return ApiResponse|m.\App\Api\Data\Response.setData
     */
    function getRelatedPaper(int $paper_id)
    {
        /**
         * @var Paper $paper
         */
        $paper = $this->paper->find($paper_id);
        $data = [];
        foreach ($paper->getRelatedItems()->random(5) as $item) {
            $data[] = $this->paperRepository->convertPaperItem($item);
        }
        return $this->apiResponse->setResponse($data);
    }
}
