<?php

namespace App\Services;

use App\Models\Paper;
use Illuminate\Support\Facades\Session;

class CartService implements CartServiceInterface
{
	function __construct()
	{
		$this->session_begin();
	}

	function addCart($paper_id, $attribute = [])
	{
		$current_cart = $this->getCart() ?: [];
		$paper = Paper::find($paper_id)->makeHidden('conten')->toArray();
		Session::put(self::KEY, [$paper, ...$current_cart]);
		return $this->getCart();
	}

	/**
	 * 
	 */
	function getCart()
	{
		$paper_cart = Session::get(self::KEY);
		return $paper_cart;
	}

	function clearCart() {
		Session::forget(self::KEY);
	}

	public function session_begin(): void
	{
		if (!Session::isStarted()) {
			Session::start();
		}
	}
}
