<?php

namespace App\Http\Controllers\Api;

use App\Api\Data\Response;
use App\Api\ResponseApi;
use App\Api\WriterApi;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class WriterApiController extends BaseController implements WriterApiControllerInterface
{

    protected $request;
    protected $response;
    protected $responseApi;

    protected $writerApi;

    function __construct(
        Request $request,
        Response $response,
        ResponseApi $responseApi,
        WriterApi $writerApi
    ) {
        $this->request = $request;
        $this->response = $response;
        $this->responseApi = $responseApi;
        $this->writerApi = $writerApi;
    }

    /**
     * @param int $writer_id
     * @return ApiResponse|m.\App\Api\Data\Response.setData
     */
    public function getPaperByWriter(int $writer_id)
    {
        $responseApi = $this->responseApi;
        return $responseApi->setResponse($this->writerApi->getPapers($writer_id));
    }

    /**
     * @return ApiResponse
     */
    public function getWriterList()
    {
        return $this->responseApi->setResponse($this->writerApi->listWriter());
        // TODO: Implement getWriterList() method.
    }
}
