<?php
namespace App\Http\Controllers;

interface OrderControllerInterface{
    const CONTROLLER_NAME = 'OrderController';

    const LIST_ORDER = 'listOrder';
    const DETAIL_ORDER = 'detailOrder';

    public function listOrder();

    /**
     * @param int $order_id
     * @return mixed
     */
    public function detailOrder(int $order_id);
}
