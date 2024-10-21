<?php
namespace App\Http\Controllers\Api;

interface CartApiControllerInterface{
    const CONTROLLER_NAME = 'Api\CartApiController';

    const ADD_TO_CART = 'addToCart';

    function addToCart();
}