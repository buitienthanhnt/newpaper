<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderItem;
use App\Models\Paper;
use Google\Cloud\Storage\Connection\Rest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartService implements CartServiceInterface
{
    protected $request;
    protected $order;
    protected $orderItem;
    protected $orderAddress;

    function __construct(
        Request $request,
        Order $order,
        OrderItem $orderItem,
        OrderAddress $orderAddress
    )
    {
        $this->request = $request;
        $this->order = $order;
        $this->orderItem = $orderItem;
        $this->orderAddress = $orderAddress;
        $this->session_begin();
    }

    function addCart($paper_id, $attribute = [])
    {
        $current_cart = $this->getCart() ?: [];
        /**
         * @var Paper $paperObj
         */
        $paperObj = Paper::find($paper_id)->makeHidden('conten');
        $paper = $paperObj->toArray();
        $paper['price'] = $paperObj->paperPrice();
        // $current_item = array_filter($this->getCart(), function ($item) use ($paper_id) {
        // 	return $item['id'] == $paper_id;
        // });
        $paper['qty'] = $this->request->get('qty');
        if (is_array($current_cart)) {
            array_push($current_cart, $paper);
            Session::put(self::KEY, $current_cart);
            Session::save();
        } else {
            Session::forget(self::KEY);
            Session::save();
        }
        return $this->getCart();
    }

    /**
     *
     */
    function getCart()
    {
        $paper_cart = Session::get(self::KEY) ?: [];
        return $paper_cart;
    }

    function submitOrder()
    {
        $cart = $this->getCart();
        if (count($cart)) {
            try {
                $this->order->fill([
                    'status' => "waiting",
                    "email" => $this->request->get('email'),
                    "name" => $this->request->get("name"),
                    "phone" => $this->request->get("phone"),
                    "total" => "22222"
                ])->save();
                if ($order_id = $this->order->id) {
                    // save order address
                    $this->orderAddress->fill([
                        "order_id" => $order_id,
                        "ship_hc" => $this->request->get("ship_hc"),
                        "address_hc" => $this->request->get("address_hc"),
                        "ship_nhc" => $this->request->get("ship_nhc"),
                        "address_nhc" => $this->request->get("address_nhc"),
                        "ship_store" => $this->request->get("ship_store")
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
            } catch
            (\Exception $exception) {
            }
        }
        return [
            "status" => false,
            "message" => '',
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

        public
        function session_begin(): void
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
