<?php

namespace Thanhnt\Nan\Helper;

class LogTha
{
	const LOG_PATH = "logs/tha/";
	const EVENT_TYPE = "event";
	const SOURCE_URL_TYPE = "remoteSource";

	/**
	 * @var \Illuminate\Http\Request
	 */
	protected $request;

	/**
	 * @var \Illuminate\Log\Logger
	 */
	protected $logger;

	public function __construct(
		\Illuminate\Http\Request $request,
		\Illuminate\Log\Logger $logger
	) {
		$this->request = $request;	
		$this->logger = $logger;
	}

	public function logEvent(string $type, string $message, array $params = []) : void {
		$this->log(self::EVENT_TYPE, ...func_get_args());
	}

	public function logRemoteSource(string $type, string $message, array $params = []) : void {
		$this->log(self::SOURCE_URL_TYPE, ...func_get_args());
	}

	protected function log(string $logType ,string $type = 'info', string $message = '', array $params = []) : void {
		$channel = $this->logger->build([
			'driver' => 'single',
			'path' => storage_path(self::LOG_PATH.$logType.".log"),
		]);
		$this->logger->stack([$channel])->{$type}("LogEvent:  ".$message, $params);
		// $logger = $this->logger->{$type}("LogEvent:".$message, $params);
	}
}
