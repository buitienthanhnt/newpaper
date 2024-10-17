<?php

namespace App\Api;

use App\Api\Data\Page\PageInfo;
use App\Api\Data\Paper\Conten;
use App\Api\Data\Paper\Info;
use App\Api\Data\Paper\PaperDetail;
use App\Api\Data\Paper\PaperItem;
use App\Api\Data\Paper\PaperList;
use App\Api\Data\Paper\Tag;
use App\Helper\HelperFunction;
use App\Models\Category;
use App\Models\Paper;
use App\Models\PaperContentInterface;
use App\Models\PaperInterface;
use App\Models\PaperTag;
use App\Models\PaperTagInterface;
use App\Models\ViewSource;
use App\Models\ViewSourceInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;

class PaperRepository
{
	protected $paper;
	protected $category;
	protected $paperList;
	protected $pageInfo;

	protected $helperFunction;

	function __construct(
		Paper $paper,
		Category $category,
		PaperList $paperList,
		PageInfo $pageInfo,
		HelperFunction $helperFunction
	) {
		$this->paper = $paper;
		$this->category = $category;
		$this->paperList = $paperList;
		$this->pageInfo = $pageInfo;
		$this->helperFunction = $helperFunction;
	}

    /**
     * @param PaperTag[] $tags
     * @return array
     */
	function convertTags($tags)
	{
		$values = [];
		foreach ($tags as $tag) {
			$_tag = new Tag();
			$_tag->setId($tag->id);
			$_tag->setEntityId($tag->{PaperTagInterface::ATTR_ENTITY_ID});
			$_tag->setValue($tag->{PaperTagInterface::ATTR_VALUE});
			$_tag->setType($tag->{PaperTagInterface::ATTR_TYPE});
			$values[] = $_tag;
		}
		return $values;
	}

    /**
     * @param Paper[] $data
     * @return PaperItem[]
     */
	function formatSug($data)
	{
		$paperItems = [];
		foreach ($data as $item) {
			$paperItems[] = $this->convertPaperItem($item);
		}
		return $paperItems;
	}

	function convertSliderdata(string $sliderJsons)
	{
		$sliders = json_decode($sliderJsons, true);
		foreach ($sliders as &$value) {
			$value['value'] = $this->helperFunction->replaceImageUrl($value['image_path']);
		}
		return json_encode($sliders) ?: $sliderJsons;
	}

	/**
	 * @param \App\Models\PaperContent[] $contens
	 */
	protected function covertContentData($contens)
	{
		if (empty($contens)) {
			return null;
		}
		$return_data = [];
		foreach ($contens as $value) {
			$conten = new Conten();
			$conten->setId($value->id);
			$conten->setType($value->{PaperContentInterface::ATTR_TYPE});
			$conten->setKey($value->{PaperContentInterface::ATTR_KEY});
			$conten->setDependValue($value->{PaperContentInterface::ATTR_DEPEND_VALUE} ?: '');
			$conten->setPaperId($value->{PaperContentInterface::ATTR_PAPER_ID});
			switch ($value->{PaperContentInterface::ATTR_TYPE}) {
				case 'image':
					$conten->setValue($this->helperFunction->replaceImageUrl($value->{PaperContentInterface::ATTR_VALUE} ?? ''));
					break;
				case 'slider_data':
					$conten->setValue($this->convertSliderdata($value->{PaperContentInterface::ATTR_VALUE}));
					break;
				default:
					$conten->setValue($value->{PaperContentInterface::ATTR_VALUE});
			}
			$return_data[] = $conten;
		}
		return $return_data;
	}

	/**
	 * @param Paper $paper
	 * @return \App\Api\Data\Paper\Info
	 */
	protected function convertPaperInfo($paper)
	{
		/**
		 * setInfo data
		 */
		$info = new Info();
		$info->setViewCount($paper->viewCount());
		$info->setCommentCount($paper->commentCount());
		$info->setLike($paper->paperLike());
		$info->setHeart($paper->paperHeart());
		return $info;
	}

	/**
	 * @param Paper $paper
	 * @return PaperDetail
	 */
	function convertPaperDetailApi($paper)
	{
		$response = new PaperDetail();
		$response->setId($paper->id);
		$response->setTitle($paper->{PaperInterface::ATTR_TITLE});
		$response->setCreatedAt($paper->created_at);
		$response->setUpdatedAt($paper->updated_at);
		$response->setShortContent($paper->{PaperInterface::ATTR_SHORT_CONTENT});
		$response->setImage($this->helperFunction->replaceImageUrl($paper->{PaperInterface::ATTR_IMAGE_PATH}));
		$response->setUrl($paper->getUrl());
		$response->setActive($paper->{PaperInterface::ATTR_ACTIVE});
		$response->setContents($this->covertContentData($paper->getContents()));
		$response->setSuggest($this->formatSug(Paper::all()->random(4)));
		$response->setTags($this->convertTags($paper->getTags()));
		$response->setInfo($this->convertPaperInfo($paper));
		return $response;
	}

    /**
     * @param int $id
     * @return PaperDetail
     */
	function getById(int $id): PaperDetail
	{
		$response = null;
		if (Cache::has("api_detail_$id")) {
			$response =  Cache::get("api_detail_$id");
		} else {
			/**
			 * @var Paper $detail
			 */
			$detail = $this->paper->find($id);
			$response = $this->convertPaperDetailApi($detail);
			Cache::put("api_detail_$detail->id", $response);
		}
		return $response;
	}

    /**
     * @param Paper $paper
     * @return PaperItem
     */
	function convertPaperItem(Paper $paper): PaperItem
	{
		/**
		 * @var Paper $item
		 */
		$paperItem = new PaperItem();
		$paperItem->setId($paper->id);
		$paperItem->setUrl($paper->getUrl());
		$paperItem->setActive(PaperInterface::ATTR_ACTIVE);
		$paperItem->setCreatedAt($paper->created_at);
		$paperItem->setImage($this->helperFunction->replaceImageUrl($paper->image_path ?: ''));
		$paperItem->setShortContent($paper->{PaperInterface::ATTR_SHORT_CONTENT});
		$paperItem->setTitle($paper->{PaperInterface::ATTR_TITLE});
		$paperItem->setInfo($this->convertPaperInfo($paper));
		return $paperItem;
	}

	function convertPageInfo($paginateDatas){
        $pageInfo = $this->pageInfo;
        $pageInfo->setCurrentPage($paginateDatas->currentPage());
        $pageInfo->setLastPage($paginateDatas->lastPage());
        $pageInfo->setPageNumber($paginateDatas->perPage());
        $pageInfo->setTotal($paginateDatas->total());
        return $pageInfo;
    }

    /**
     * @param LengthAwarePaginator $paginateDatas
     * @return PaperList
     */
	function convertPaperPaginate($paginateDatas){
        $listData = null;
        foreach ($paginateDatas as $item) {
            $listData[] = $this->convertPaperItem($item);
        }
        $paperList = $this->paperList;
        $paperList->setItems($listData);
        $paperList->setPageInfo($this->convertPageInfo($paginateDatas));
        return $paperList;
    }

    /**
     * @return PaperList
     */
	function paperAll()
	{
		$listData = null;
		$papers = $this->paper->orderBy('updated_at', 'desc')->paginate(12);
		return $this->convertPaperPaginate($papers);
	}

    /**
     * @param int $category_id
     * @return PaperList
     */
    function getPaperByCategory(int $category_id){
        /**
         * @var Category $category
         */
        $category = $this->category->find($category_id);
        $papers = $category->getPaperByCategory();
        return $this->convertPaperPaginate($papers);
    }

    function mostPaperViews(){
        $mostView = ViewSource::where(ViewSourceInterface::ATTR_TYPE, ViewSource::TYPE_PAPER)
            ->orderBy(ViewSourceInterface::ATTR_VALUE, 'desc')
            ->limit(8)
            ->pluck(ViewSourceInterface::ATTR_SOURCE_ID)
            ->toArray();
        return Paper::find($mostView);
    }
}
