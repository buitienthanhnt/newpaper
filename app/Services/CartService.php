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
		$paperObj = Paper::find($paper_id)->makeHidden('conten');
		$paper = $paperObj->toArray();
		$paper['price'] = $paperObj->paperPrice();
		// $current_item = array_filter($this->getCart(), function ($item) use ($paper_id) {
		// 	return $item['id'] == $paper_id;
		// });
		$paper['qty'] = $this->request->get('qty');
		if (is_array($current_cart)) {
			array_push($current_cart, $paper);
			Session::put(self::KEY, $current_cart);
			Session::save();
		}else {
			Session::forget(self::KEY);
			Session::save();
		}
		return $this->getCart();
	}

	/**
	 * 
	 */
	function getCart()
	{
		$paper_cart = Session::get(self::KEY) ?: [];
		return $paper_cart;
	}

	function clearCart()
	{
		Session::forget(self::KEY);
		Session::save();
		return $this->getCart();
	}

	public function session_begin(): void
	{
		if (!Session::isStarted()) {
			Session::start();
		}
	}
}
