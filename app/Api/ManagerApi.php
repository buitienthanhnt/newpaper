<?php
namespace App\Api;

use App\Api\Data\Other\HomeInfo;
use App\Helper\HelperFunction;

class ManagerApi extends BaseApi
{
	protected $paperApi;
	protected $writerRepository;

	protected $helperFunction;

	function __construct(
		PaperApi $paperApi,
		WriterRepository $writerRepository,
		HelperFunction $helperFunction
	)
	{
		$this->paperApi = $paperApi;
		$this->writerRepository = $writerRepository;
		$this->helperFunction = $helperFunction;
	}

    /**
     * @return HomeInfo
     * @throws \Exception
     */
	function homeInfo()
	{
		$timeLine = $this->paperApi->timeLine();
		$hit = $this->paperApi->hit();
		$forward = $this->paperApi->forward();
		$mostPopulator = $this->paperApi->mostPopulator();
		$mostRecents = $this->paperApi->mostRecents();
		$listImages = $this->paperApi->listImages();
		$tags = $this->paperApi->tags();
		$writers =$this->writerRepository->allWriter();
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

		$homeInfo = new HomeInfo();
        $homeInfo->setHit($hit);
		$homeInfo->setForward($forward);
		$homeInfo->setListImages($listImages);
		$homeInfo->setTimeLine($timeLine);
		$homeInfo->setMostPopulator($mostPopulator);
		$homeInfo->setMostRecents($mostRecents);
		$homeInfo->setSearch($tags);
		$homeInfo->setMap($lineMap);
		$homeInfo->setVideo($video);
		$homeInfo->setWriters($writers);
		return  $homeInfo;
	}

}
