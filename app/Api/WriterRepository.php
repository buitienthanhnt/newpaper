<?php
namespace App\Api;

use App\Api\Data\Page\PageInfo;
use App\Api\Data\Writer\WriterItem;
use App\Api\Data\Writer\WriterList;
use App\Helper\HelperFunction;
use App\Models\Writer;
use App\Models\WriterInterface;

class WriterRepository{
    protected $writer;
    protected $writerList;
    protected $pageInfo;

    protected $helperFunction;

    function __construct(
        Writer $writer,
        PageInfo $pageInfo,
        WriterList $writerList,
        HelperFunction $helperFunction
    )
    {
        $this->writer = $writer;
        $this->writerList = $writerList;
        $this->pageInfo = $pageInfo;
        $this->helperFunction = $helperFunction;
    }

    /**
     * @param Writer $writer
     * @return WriterItem
     */
    function convertWriterItem($writer) : WriterItem {
        $writerItem = new WriterItem();
        $writerItem->setId($writer->id);
        $writerItem->setName($writer->{WriterInterface::ATTR_NAME});
        $writerItem->setEmail($writer->{WriterInterface::ATTR_EMAIL});
        $writerItem->setPhone($writer->{WriterInterface::ATTR_PHONE});
        $writerItem->setImagePath($this->helperFunction->replaceImageUrl($writer->{WriterInterface::ATTR_IMAGE_PATH} ?: ''));
        $writerItem->setRating($writer->{WriterInterface::ATTR_RATING});
        $writerItem->setActive($writer->{WriterInterface::ATTR_ACTIVE});
        $writerItem->setDateOfBirth($writer->{WriterInterface::ATTR_DATE_OF_BIRTH});
        return $writerItem;
    }

    /**
     * @param $paginateDatas
     * @return PageInfo
     */
    function convertPageInfo($paginateDatas){
        $pageInfo = $this->pageInfo;
        $pageInfo->setCurrentPage($paginateDatas->currentPage());
        $pageInfo->setLastPage($paginateDatas->lastPage());
        $pageInfo->setPageNumber($paginateDatas->perPage());
        $pageInfo->setTotal($paginateDatas->total());
        return $pageInfo;
    }

    /**
     * @param $paginateDatas
     * @return WriterList
     */
    function convertPaginate($paginateDatas){
        $writerList = $this->writerList;
        $items = [];
        foreach ($paginateDatas as $paginateData) {
            $items[] = $this->convertWriterItem($paginateData);
        }
        $writerList->setItems($items);
        $writerList->setPageInfo($this->convertPageInfo($paginateDatas));
        return $writerList;
    }

    /**
     * @return WriterItem[]
     */
    function listWriter()
    {
        return $this->convertPaginate($this->writer->all()->toQuery()->paginate(12));
    }

    /**
     * @param int $writer_id
     * @return WriterItem
     */
    function getById(int $writer_id){
        $writer = Writer::find($writer_id);
        return $this->convertWriterItem($writer);
    }

    function getPaperByWriter(){

    }
}
