<?php
namespace App\Api;

use App\Helper\HelperFunction;
use App\Models\Paper;
use App\Models\PaperContent;
use App\Models\PaperContentInterface;
use App\Models\Writer;
use Carbon\Carbon;
use DateTime;

class ManagerApi extends BaseApi
{
	protected $paperApi;

	protected $helperFunction;

	function __construct(
		PaperApi $paperApi,
		HelperFunction $helperFunction
	)
	{
		$this->paperApi = $paperApi;
		$this->helperFunction = $helperFunction;
	}

	function homeInfo(): array
	{
		$timeLine = $this->paperApi->timeLine();
		$hit = $this->paperApi->hit();
		$forward = $this->paperApi->forward();
		$mostPopulator = $this->paperApi->mostPopulator();
		$mostRecents = $this->paperApi->mostRecents();
		$listImages = $this->paperApi->listImages();

		$tags = $this->paperApi->tags();
		$writers = Writer::all();
		$lineMap = [
			"data" => [
				"labels" => ["Jan", "Feb", "March", "April", "May", "June", 'nan'],
				"datasets" => [
					[
						"data" => [
							random_int(1, 100),
							random_int(1, 100),
							random_int(1, 100),
							random_int(1, 100),
							random_int(1, 100),
							random_int(1, 100),
							random_int(1, 100)
						]
					]
				]
			],
			"yAxisLabel" => "$",
			"yAxisSuffix" => "đ",
			"bezier" => true,
			"yAxisInterval" => 1,
			"chartConfig" => [
				"backgroundColor" => "#e26a00",
				"backgroundGradientFrom" => "#ff7cc0", // 82baff
				"backgroundGradientTo" => "#82baff",   // ffa726
				"decimalPlaces" => 1, // số chữ số sau dấu phẩy.
			]
		];
		$video = [
			"videoId" => "_l5V2aWyTfI",
			"height" => 220,
			"title" => "This snippet renders a Youtube video"
		];

		return [
			'message' => 'get home info success',
			'status' => true,
			'code' => 200,
			'hit' => $hit,
			'forward' => $forward,
			'mostPopulator' => $mostPopulator,
			'mostRecents' => $mostRecents,
			'listImages' => $listImages,
			'timeLine' => $timeLine,
			'search' => $tags,
			'writers' => $writers,
			'map' => $lineMap,
			'video' => $video
		];
	}
}

?>