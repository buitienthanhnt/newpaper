<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    //
    /**
     * @var Order $order
     */
    protected $order;

    function __construct(
        Order $order
    )
    {
        $this->order = $order;
    }

    function lists() : View {
        $orders = $this->order->paginate(8);
        return view("adminhtml.templates.orders.list", ["orders" => $orders]);
    }

    function info($order_id) : View {
        /**
         * @var Order $order
         */
        $order = $this->order->find($order_id);
        return view("adminhtml.templates.orders.detail", ['order' => $order]);
    }
}
