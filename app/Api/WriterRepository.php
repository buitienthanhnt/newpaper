<?php
namespace App\Api;

use App\Api\Data\Writer\WriterItem;
use App\Helper\HelperFunction;
use App\Models\Writer;
use App\Models\WriterInterface;

class WriterRepository{
    protected $writer;

    protected $helperFunction;

    function __construct(
        Writer $writer,
        HelperFunction $helperFunction
    )
    {
        $this->writer = $writer;
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
     * @return WriterItem[]
     */
    function allWriter()
    {
        $allData = null;
        foreach ($this->writer->all() as $writer) {
            $allData[] = $this->convertWriterItem($writer);
        }
        return $allData;
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
