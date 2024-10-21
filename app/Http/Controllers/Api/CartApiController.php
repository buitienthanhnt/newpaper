<?php

namespace App\Http\Controllers\Api;

use App\Api\CartApi;
use App\Api\Data\Cart\CartItem;
use App\Api\Data\Response;
use App\Api\ResponseApi;
use App\Http\Controllers\BaseController;
use App\Models\Paper;
use Illuminate\Http\Request;
use Exception;

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
        $paper = Paper::find($this->request->get('paper_id'));
        try {
            if (!$paper){
                throw new Exception("this source not found", 400);
            }
            if (!$paper->getPrice()){
                throw new Exception("this source cant't add to cart", 400);
            }
            $cartData = $this->cartApi->addCart($paper, $this->request->get('qty'));
            return $this->responseApi->setResponse($this->response->setResponse($cartData));
        } catch (Exception $e) {
            return $this->responseApi->setStatusCode($e->getCode())->setResponse($this->response->setMessage($e->getMessage()));
        }
    }

    function getCart()
    {
        $cartData = $this->cartApi->getCart();
        return $this->responseApi->setResponse($this->response->setResponse($cartData));
    }

    function clearCart()
    {
        $this->cartApi->clearCart();
        return $this->responseApi->setResponse($this->response->setMessage('cart cleared!'));
    }

    public function removeItem($item_id)
    {
        $cartData = $this->cartApi->getCart();
        $listItems = $cartData->getItems();
        $checkItem = array_filter($listItems, function (CartItem $item) use($item_id){
            return $item->getValueId() === (int) $item_id;
        });
        if (!count($checkItem)){
            throw new Exception("the source is not exist!", 400);
        }
        return $this->responseApi->setResponse($this->response->setResponse($this->cartApi->removeItem($item_id))->setMessage("removed item: '".current($checkItem)->getValueTitle()."'!"));
        // TODO: Implement removeItem() method.
    }
}
