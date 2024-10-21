<?php
namespace App\Api\Data;

use App\Api\Data\Cart\CartData;
use App\Api\Data\Cart\CartItem;
use App\Models\Paper;
use App\Models\PaperInterface;

class ConvertCart
{
    function __construct(

    )
    {
        
    }

    /**
     * @param Paper $value
     */
    function convertCartItem($value, int $qty){
        $cartItem = new CartItem();
        $cartItem->setValueTitle($value->{PaperInterface::ATTR_TITLE});
        $cartItem->setValueAlias($value->{PaperInterface::ATTR_URL_ALIAS});
        $cartItem->setValueImagePath($value->getImagePath());
        $cartItem->setValueId($value->id);
        $cartItem->setQty($qty);
        $cartItem->setValuePrice($value->getPrice());
        $cartItem->setValuePriceFormat($value->getPrice(true));
        return $cartItem;
    }

    /**
     * @param CartData|null $cartData
     * @return CartData
     */
    function convertCartData($_cartData){
        $cartData = new CartData();
        if ($_cartData) {
            $cartItems = $_cartData->getItems();
            $cartData->setItems($cartItems);
            $cartData->setTotals(1);
            $cartData->setCount(count($cartItems));
        }
        return $cartData;
    }
    
}
