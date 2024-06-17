<?php

namespace App\Services;

use App\Models\Paper;
use Google\Cloud\Storage\Connection\Rest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartService implements CartServiceInterface
{
	protected $request;

	function __construct(
		Request $request
	) {
		$this->request = $request;
		$this->session_begin();
	}

	function addCart($paper_id, $attribute = [])
	{
		$current_cart = $this->getCart() ?: [];
		$paper = Paper::find($paper_id)->makeHidden('conten')->toArray();
		$current_item = array_filter($this->getCart(), function ($item) use ($paper_id) {
			return $item['id'] == $paper_id;
		});
		$paper['qty'] = $this->request->get('qty');
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

	function clearCart()
	{
		Session::forget(self::KEY);
	}

	public function session_begin(): void
	{
		if (!Session::isStarted()) {
			Session::start();
		}
	}
}
