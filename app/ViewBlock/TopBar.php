<?php
namespace App\ViewBlock;

use App\Services\CartService;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\View;

class TopBar implements Htmlable
{
    protected $template = 'frontend.templates.page_header';
    protected $cartService;

    function __construct(
        CartService $cartService
    )
    {
        $this->cartService = $cartService;
    }

    function toHtml()
    {
        $cart = $this->cartService->getCart();
        $top_menu_view = View::make($this->template)->with('cart_count', count($cart))->render();
        return $top_menu_view;
    }
}
