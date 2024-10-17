<?php

namespace App\Api;

use App\Api\Data\Writer\WriterItem;
use App\Helper\HelperFunction;
use App\Models\ConfigCategory;
use App\Models\Writer;
use App\Models\WriterInterface;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Thanhnt\Nan\Helper\LogTha;

final class WriterApi extends BaseApi
{
    protected $writer;
    protected $helperFunction;
    protected $request;

    function __construct(
        HelperFunction $helperFunction,
        FirebaseService $firebaseService,
        Writer $writer,
        Request $request,
        LogTha $logTha
    ) {
        $this->writer = $writer;
        $this->helperFunction = $helperFunction;
        $this->request = $request;
        parent::__construct($firebaseService, $logTha);
    }

    function convertWriterItem($writer) : WriterItem {
        $writerItem = new WriterItem();
        $writerItem->setId($writer->id());
        $writerItem->setName($writer->{WriterInterface::ATTR_NAME});
        $writerItem->setEmail($writer->{WriterInterface::ATTR_EMAIL});
        $writerItem->setPhone($writer->{WriterInterface::ATTR_PHONE});
        $writerItem->setImagePath($this->helperFunction->replaceImageUrl($writer->{WriterInterface::ATTR_IMAGE_PATH} ?: ''));
        $writerItem->setRating($writer->{WriterInterface::ATTR_RATING});
        $writerItem->setActive($writer->{WriterInterface::ATTR_ACTIVE});
        $writerItem->setDateOfBirth($writer->{WriterInterface::ATTR_DATE_OF_BIRTH});
        return $writerItem;
    }

    function allWriter()
    {
        $allData = null;
        foreach (Writer::all() as $writer) {
            $allData = $this->convertWriterItem($writer);
        }
        return $allData;
    }

    function getPapers($writer_id)
    {
        $writer = $this->writer->find($writer_id);
        $p = $this->request->get('p', 1) -1 ;
        $limit = $this->request->get('limit', 12);
        $sort_by = $this->request->get('sort_by', 'created_at');
        $sort_direction = $this->request->get('direction', 'DESC') === "DESC";
        $papers = $writer->getPapers()->getResults()->sortBy($sort_by, SORT_REGULAR, $sort_direction)->skip($limit * $p)->take($limit)->makeHidden(['conten']);
        return [
            'writer' => $writer,
            'papers' => $papers,
        ];
    }
}
