<?php
namespace App\Http\Controllers\Api;

interface CartApiControllerInterface{
    const CONTROLLER_NAME = 'Api\CartApiController';

    const ADD_TO_CART = 'addToCart';
    const GET_CART = 'getCart';
    const CLEAR_CART = 'clearCart';

    function addToCart();

    function getCart();

    function clearCart();
}