<?php
namespace App\Http\Controllers\Api;

use App\Api\Data\Response;
use App\Api\ResponseApi;
use App\Http\Controllers\Controller;
use App\Http\Exception\FormValidationException;
use App\Http\Validation\PaperLikeForm;
use App\Models\ViewSource;
use Illuminate\Http\Request;

class PaperApiController extends Controller implements PaperApiControllerInterface
{
    protected $request;

    protected $response;
    protected $responseApi;

    protected $paperLikeForm;

    function __construct(
        Request $request,
        Response $response,
        ResponseApi $responseApi,
        PaperLikeForm $paperLikeForm
    )
    {
        $this->request = $request;
        $this->response = $response;
        $this->responseApi = $responseApi;
        $this->paperLikeForm = $paperLikeForm;
    }

    function addPaperLike(int $paper_id) {
        $params = $this->request->toArray();
        try {
            $this->paperLikeForm->validate($params);
            $paperSource = ViewSource::where(ViewSource::ATTR_TYPE, ViewSource::TYPE_PAPER)->where(ViewSource::ATTR_SOURCE_ID, $paper_id)->first();
            if (empty($paperSource)) {
                ViewSource::firstOrCreate([
                    ViewSource::ATTR_TYPE => ViewSource::TYPE_PAPER, // type= paper|category(category chua ho tro.)
                    ViewSource::ATTR_SOURCE_ID => $paper_id,
                    ViewSource::ATTR_VALUE => 1,
                    ViewSource::ATTR_HEART => $params[ViewSource::PARAM_TYPE] === ViewSource::ATTR_HEART ? 1 : 0,
                    ViewSource::ATTR_LIKE => $params[ViewSource::PARAM_TYPE] === ViewSource::ATTR_HEART ? 1 : 0
                ]);
            } else {
                if ($params[ViewSource::PARAM_TYPE] === ViewSource::ATTR_LIKE) {
                    $paperSource->like = $params[ViewSource::PARAM_ACTION] === ViewSource::ACTION_VAL_ADD ? $paperSource->like + 1 : $paperSource->like - 1;
                } elseif ($params[ViewSource::PARAM_TYPE] === ViewSource::ATTR_HEART) {
                    $paperSource->heart = $params[ViewSource::PARAM_ACTION] === ViewSource::ACTION_VAL_ADD ? $paperSource->heart + 1 : $paperSource->heart - 1;
                }
                $paperSource->save();
            }
        }catch(FormValidationException $e){
            return $this->responseApi->setStatusCode(400)->setResponse($this->response->setMessage($e->getFullMessage()));
        }
         catch (\Throwable $th) {
            return $this->responseApi->setStatusCode($th->getCode())->setResponse($this->response->setMessage($th->getMessage()));
        }
        return $this->responseApi->setResponse($this->response->setMessage('đã thích!!'));
    }
}
