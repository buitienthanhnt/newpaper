<?php

namespace Thanhnt\Nan\Helper;

require_once('vendor/autoload.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

final class TokenManager
{
	private $exp_time = 15 * 60;
	private $serect_key = 'laravel1.com';

	protected $request;

	function __construct(Request $request)
	{
		$this->request = $request;
	}

    /**
     * @param array|null $data
     * @param int $iat
     * @param int $nbf
     * @param int $exp
     * @param string $type
     * @return array
     */
	function getToken(array $data = null, int $iat = 0, int $nbf = 0, int $exp = 0, $type = 'HS256'): array
	{
		$iat = $iat ?: time();
		$exp = $exp ?: $iat + $this->exp_time;
		try {
			$payload = array(
				'iss' => $data, // data cần mã hóa lưu trữ.
				'iat' => $iat,  // thời gian bắt đầu token.
				'nbf' => $nbf,  // thời gian token bắt đầu có thể decode(có hiệu lực so với iat).
				'exp' => $exp,  // thời gian token hết hạn.
				// 'uId' => $UiD
			);
			$jwt = JWT::encode($payload, $this->get_serect_key(), $type);
			return [
				'status' => true,
				'type' => 'HS256',
				'value' => $jwt
			];
		} catch (\Throwable $th) {
			// echo ($th->getMessage());
		}
		return [
			'status' => false,
			'type' => 'HS256',
			'value' => null
		];
	}

    /**
     * @param string $token
     * @param string $type
     * @return array
     */
	function getTokenData(string $token = '', $type = 'HS256') : array
	{
		$token = $token ?: $request_token = $this->request->header('token');
		if ($token) {
			try {
				$decode = JWT::decode($token, new Key($this->get_serect_key(), $type));
				if (is_object($decode) && $data = $decode->iss) {
					return (array) $data;
				}
			} catch (\Throwable $th) {
				// echo ($th->getMessage());
			}
		}
		return [];
	}

	/**
	 * @return string
	 */
	private function get_serect_key(): string
	{
		return $this->serect_key;
	}
}
