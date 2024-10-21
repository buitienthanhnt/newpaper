<?php
namespace App\Http\Controllers\Api;

use App\Api\CartApi;
use App\Api\Data\Response;
use App\Api\ResponseApi;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class CartApiController extends BaseController implements CartApiControllerInterface
{
    protected $request;
    protected $cartApi;

    protected $responseApi;
    protected $response;

    function __construct(
        Request $request,
        CartApi $cartApi,
        ResponseApi $responseApi,
        Response $response
    )
    {
        $this->request = $request;
        $this->cartApi = $cartApi;
        $this->responseApi = $responseApi;
        $this->response = $response;
    }

    function addToCart()
    {
        $cartData = $this->cartApi->addCart($this->request->get('paper_id'), $this->request->get('qty'));
        return $this->responseApi->setResponse($this->response->setResponse($cartData));
    }

    function getCart() {
        $cartData = $this->cartApi->getCart();
        return $this->responseApi->setResponse($this->response->setResponse($cartData));
    }

    function clearCart(){
        $this->cartApi->clearCart();
        return $this->responseApi->setResponse($this->response->setMessage('cart cleared!'));
    }
}
