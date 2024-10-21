<?php
namespace App\Api;

use App\Api\Data\Cart\CartItem;
use App\Api\Data\ConvertCart;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderItem;
use App\Models\Paper;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartApi{
    const KEY = 'cart_session';

    protected $request;
    protected $order;
    protected $orderItem;
    protected $orderAddress;

    protected $convertCart;

    function __construct(
        Request $request,
        Order $order,
        OrderItem $orderItem,
        OrderAddress $orderAddress,
        ConvertCart $convertCart
    ) {
        $this->request = $request;
        $this->order = $order;
        $this->orderItem = $orderItem;
        $this->orderAddress = $orderAddress;
        $this->convertCart = $convertCart;
        $this->session_begin();
    }

    function addCart($paper, int $qty)
    {
        $current_cart = $this->getCart();
        if (!$current_cart) {
            $current_cart = $this->convertCart->convertCartData($current_cart);
        }
        /**
         * @var Paper $paperObj
         */
        $cartItem = $this->convertCart->convertCartItem($paper, $qty);
        $currentCartItems = $current_cart->getItems() ?: [];

        try {
            $_existItem = array_filter($currentCartItems, function ($item) use ($paper) {
                return $paper->id == $item->getValueId();
            });
            if (!$_existItem) {
                $currentCartItems[] = $cartItem;
            }else {
                foreach ($currentCartItems as &$value) {
                    if ($paper->id === $value->getValueId()) {
                        $value->setQty($value->getQty() + $qty);
                    }
                }
            }
            $current_cart->setItems($currentCartItems);
            $newCart = $this->convertCart->convertCartData($current_cart);
            $this->saveCart($newCart);
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage(), 500);
        }
        return $this->getCart();
    }

    function removeItem($item_id){
        $cartData = $this->getCart();
        $cart_items = $cartData->getItems();
        $new_items = array_filter($cart_items, function (CartItem $item)use($item_id){
            return $item->getValueId() != $item_id;
        });
        $cartData->setItems(array_values($new_items));
        $this->saveCart($this->convertCart->convertCartData($cartData));
        return $this->getCart();
    }

    /**
     *
     */
    function getCart()
    {
        $paper_cart = Session::get(self::KEY) ?: null;
        return $this->convertCart->convertCartData($paper_cart);
    }

    function submitOrder()
    {
        $cart = $this->getCart();
        if (count($cart->getItems())) {
            $ship_store = $this->request->get('ship_store');
            if (!$ship_store) {
                $ship_hc = $this->request->get("ship_hc");
                $ship_nhc = $this->request->get("ship_nhc");
                if (!$ship_hc && !$ship_nhc) {
                    return [
                        "status" => false,
                        "message" => 'shipping type not define',
                        "order_id" => null
                    ];
                }
                $address_hc = $this->request->get("address_hc");
                $address_nhc = $this->request->get("address_nhc");
                if (!$address_hc && !$address_nhc) {
                    return [
                        "status" => false,
                        "message" => 'shipping address not define',
                        "order_id" => null
                    ];
                }
            }
            try {
                $this->order->fill([
                    'status' => "waiting",
                    "email" => $this->request->get('email'),
                    "name" => $this->request->get("name"),
                    "phone" => $this->request->get("phone"),
                    "thanh_toan" => $this->request->get("thanhtoan"),
                    "omx" => $this->request->get("omx", '0000000'),
                    "total" => array_sum(array_map(fn($i) => $i['price'] * $i['qty'], $cart))
                ])->save();
                if ($order_id = $this->order->id) {
                    // save order address
                    $this->orderAddress->fill([
                        "order_id" => $order_id,
                        "ship_hc" => boolval($this->request->get("ship_hc")),
                        "address_hc" => $this->request->get("address_hc"),
                        "ship_nhc" => boolval($this->request->get("ship_nhc")),
                        "address_nhc" => $this->request->get("address_nhc"),
                        "ship_store" => boolval($this->request->get("ship_store"))
                    ])->save();

                    // save for order items
                    foreach ($cart as $item) {
                        $order_item = [
                            "paper_id" => $item['id'],
                            "title" => $item['title'],
                            "qty" => $item["qty"],
                            "order_id" => $order_id,
                            "price" => $item['price'],
                            "url" => $item['url_alias'],
                            "image_path" => $item['image_path']
                        ];
                        $this->orderItem->create($order_item);
                    }
                }
                $this->clearCart();
                return [
                    "status" => true,
                    "message" => 'created new order',
                    "order_id" => $order_id
                ];
            } catch (\Exception $exception) {
                dd($exception);
            }
        }
        return [
            "status" => false,
            "message" => 'order can not register, please try again!',
            "order_id" => null
        ];
    }

    function xoaItem($id)
    {
        $cart = $this->getCart();
        unset($cart[$id]);
        $this->saveCart($cart);
    }

    function clearCart()
    {
        Session::forget(self::KEY);
        Session::save();
        return $this->getCart();
    }

    public function session_begin(): void
    {
        if (!Session::isStarted()) {
            Session::start();
        }
    }

    function saveCart($cart)
    {
        Session::put(self::KEY, $cart);
        Session::save();
    }
}
