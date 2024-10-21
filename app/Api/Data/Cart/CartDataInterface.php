<?php
namespace App\Api\Data\Cart;

use App\Api\Data\AttributeInterface;

interface CartDataInterface extends AttributeInterface{
    const ITEMS = 'items';
    const TOTALS = 'totals';
    const COUNT = 'count';

    function setItems($items);

    function getItems();

    function setTotals($totals);

    function getTotals();

    function setCount(int $count);

    /**
     * @return int
     */
    function getCount();
}