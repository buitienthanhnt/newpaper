<?php
namespace App\Api\Data\Cart;

use App\Api\Data\Attribute;

class CartData extends Attribute implements CartDataInterface
{
    function setItems($items)
    {
        return $this->setData(self::ITEMS, $items);
    }

    function getItems()
    {
        return $this->getData(self::ITEMS);
    }

    function setTotals($totals)
    {
        return $this->setData(self::TOTALS, $totals);
    }

    function getTotals()
    {
        return $this->getData(self::TOTALS);
    }

    function setCount(int $count)
    {
        return $this->setData(self::COUNT, $count);
    }

    function getCount()
    {
        return $this->getData(self::COUNT);
    }
}
