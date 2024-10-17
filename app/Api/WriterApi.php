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

    protected $writerRepository;
    protected $paperRepository;

    function __construct(
        HelperFunction $helperFunction,
        Writer $writer,
        Request $request,
        WriterRepository $writerRepository,
        PaperRepository $paperRepository
    ) {
        $this->writer = $writer;
        $this->helperFunction = $helperFunction;
        $this->request = $request;
        $this->writerRepository = $writerRepository;
        $this->paperRepository = $paperRepository;
    }

    function allWriter()
    {
       return $this->writerRepository->allWriter();
    }

    function getPapers($writer_id)
    {
        /**
         * @var Writer $writer
         */
        $writer = $this->writer->find($writer_id);
        $papers = $writer->getPaperWithPaginate();
        return $this->paperRepository->convertPaperPaginate($papers);
    }
}
