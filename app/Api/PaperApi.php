<?php

namespace App\Api;

use App\Helper\HelperFunction;
use App\Jobs\UpPaperFireBase;
use App\Models\Comment;
use App\Models\Paper;
use App\Models\ViewSource;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\PageTag;
use App\Models\PaperContent;
use App\Models\PaperTimeLine;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Thanhnt\Nan\Helper\LogTha;

class PaperApi extends BaseApi
{
	protected $helperFunction;
	protected $request;
	protected $writerApi;
	/**
	 * @var PaperContent $paperContent
	 */
	protected $paperContent;

	function __construct(
		FirebaseService $firebaseService,
		HelperFunction $helperFunction,
		Request $request,
		WriterApi $writerApi,
		LogTha $logTha,
		PaperContent $paperContent
	) {
		$this->helperFunction = $helperFunction;
		$this->request = $request;
		$this->writerApi = $writerApi;
		$this->paperContent = $paperContent;
		parent::__construct($firebaseService, $logTha);
	}

	/**
	 * @param int $paperId
	 * @return Paper| null
	 */
	public function getDetail(int $paperId)
	{
		$paperDetail = null;
		$paperKey = 'paperDetail_' . $paperId;
		if (Cache::has($paperKey)) {
			$paperDetail = Cache::get($paperKey);
		} else {
			$paperDetail = Paper::find($paperId);
			if ($paperDetail) {
				Cache::put($paperKey, $paperDetail);
			}
		}
		return $paperDetail;
	}

	/**
	 * @param int $paperId
	 * @return Comment| null
	 */
	public function getComment(int $comment_id)
	{
		return Comment::find($comment_id);
	}

	function paperInFirebase(): array
	{
		$userRef = $this->firebaseDatabase->getReference('/newpaper/papers');
		$snapshot = $userRef->getSnapshot();
		$values = $snapshot->getValue() ?: [];
		if ($values) {
			foreach ($values as $key => &$val) {
				$val = $this->formatPaperFirebase($val);
			}
		}
		return $values;
	}

	function paperInHome(): array
	{
		$userRef = $this->firebaseDatabase->getReference('/newpaper/home');
		$snapshot = $userRef->getSnapshot();
		$values = $snapshot->getValue() ?: [];
		if ($values) {
			foreach ($values as $key => &$val) {
				$val = $this->formatPaperFirebase($val);
			}
		}
		return $values;
	}

	public function formatPaperFirebase($paperData)
	{
		if (isset($paperData['id'])) {
			return $paperData;
		}
		return array_values($paperData)[0];
	}

	public function addFirebase($paperId, $hidden = []): array
	{
		$_paper = Paper::find($paperId);
		$paper = $_paper->toArray();
		$paper['categories'] = $_paper->listIdCategories();
		if (!empty($paper)) {
			if (isset($paper['image_path']) && !empty($paper['image_path'])) {
				// upload image of paper to fireStorage
				$firebaseImage = $this->upLoadImageFirebase($paper['image_path'], $this->request->get('type', null));
				if ($firebaseImage) {
					$paper['image_path'] = $firebaseImage;
					$paper['info'] = $_paper->paperInfo();
				} else {
					unset($paper['image_path']);
				}
			}

			if (!$this->request->get('type')) {
				foreach ($hidden as $k) {
					unset($paper[$k]);
				}
			}
			/**
			 * queue for async upload data of paper to firebase
			 * paper_id & paper image path firebase.
			 */
			if (!$this->request->get('type', null)) {
				UpPaperFireBase::dispatch($_paper->id, $firebaseImage ?? null);
			}

			// $this->upFirebaseComments($_paper);
			// $this->upPaperInfo($_paper);
			// $this->addPapersCategory($_paper, $firebaseImage);
			// $this->upContentFireStore($_paper);
			$userRef = $this->firebaseDatabase->getReference("/newpaper/{$this->request->get('type', 'papers')}/" . $_paper->id);
			$userRef->push($paper);
			$snapshot = $userRef->getSnapshot();

			$this->logTha->logFirebase('info', " -> Added for paperId: " . $paper['id'] . " to paperList firebase");

			if (!$this->request->get('type', null)) {
				Cache::put('paper_in_firebase', $this->paperInFirebase());
			} else {
				Cache::put('paper_in_home', $this->paperInHome());
			}
			return [
				'status' => true,
				'value' => $this->formatPaperFirebase($snapshot->getValue())['id']
			];
		}

		return [
			'status' => false,
			'value' => null
		];
	}

	/**
	 * remove paper in list of realtime database.
	 * @param int|string $idInFirebase
	 * @return array
	 */
	public function removePaperInList($idInFirebase)
	{
		try {
			$userRef = $this->firebaseDatabase->getReference('/newpaper/papers/' . $idInFirebase);
			$paperData = $this->formatPaperFirebase($userRef->getSnapshot()->getValue());
			$userRef->remove();
			$this->logTha->logFirebase('info', 'removed paper data in papers firebase database', [
				'paper' => $idInFirebase
			]);
			return $paperData;
		} catch (\Throwable $th) {
			$this->logTha->logFirebase('warning', 'can not remove paper in papers lists firebase: ' . $th->getMessage(), ['line' => $th->getLine()]);
		}
		return null;
	}

	function removePaperCategory($paperData): void
	{
		if ($categories = $paperData['categories']) {
			foreach ($categories as $value) {
				try {
					$userRef = $this->firebaseDatabase->getReference("/newpaper/papersCategory/$value/{$paperData['id']}");
					$userRef->remove();
					$this->logTha->logFirebase('info', 'removed paper data in categories firebase database', [
						'paper' => $paperData['id'],
						'category' => $value
					]);
				} catch (\Throwable $th) {
					$this->logTha->logFirebase('warning', 'can not remove paper in categories firebase: ' . $th->getMessage(), ['line' => $th->getLine()]);
				}
			}
		}
	}

	function removePaperComment($idInFirebase)
	{
		try {
			$userRef = $this->firebaseDatabase->getReference("/newpaper/comments/$idInFirebase");
			$userRef->remove();
			$this->logTha->logFirebase('info', 'removed paper comments in firebase database', [
				'paper' => $idInFirebase,
			]);
		} catch (\Throwable $th) {
			$this->logTha->logFirebase('warning', 'can not remove paper comments: ' . $th->getMessage(), ['line' => $th->getLine()]);
		}
	}

	function removePaperInfo($idInFirebase): void
	{
		try {
			$observer = $this->fireStore->collection('detailInfo')->document($idInFirebase);
			$observer->delete();
			$this->logTha->logFirebase('info', 'removed paper info in firestore', [
				'paper' => $idInFirebase,
			]);
		} catch (\Throwable $th) {
			$this->logTha->logFirebase('warning', 'can not remove paper info in firestore: ' . $th->getMessage(), ['line' => $th->getLine()]);
		}
	}

	function removePaperWriter($paperData)
	{
		try {
			$observer = $this->firebaseDatabase->getReference("/newpaper/writers/{$paperData['writer']}/{$paperData['id']}");
			$observer->remove();
			$this->logTha->logFirebase('info', 'removed paper info in writer', [
				'paper' => $paperData['id'],
				'writer' => $paperData['writer']
			]);
		} catch (\Throwable $th) {
			$this->logTha->logFirebase('warning', 'can not remove paper in writers: ' . $th->getMessage(), ['line' => $th->getLine()]);
		}
	}

	/**
	 * remove all value of paper in firebase
	 * @param int|string $idInFirebase
	 * @return array
	 */
	function removeInFirebase($idInFirebase)
	{
		$this->logTha->logFirebase('info', '<<------- start remove paper in firebase');
		$paperData = $this->removePaperInList($idInFirebase);
		if (empty($paperData)) {
			return [
				'status' => false,
				'value' => null
			];
		}
		$this->removePaperCategory($paperData);
		$this->rmContentFireStore($idInFirebase);
		$this->removePaperComment($idInFirebase);
		$this->removePaperInfo($idInFirebase);
		$this->removePaperWriter($paperData);
		if (isset($paperData['image_path'])) {
			/**
			 * remove image papaper in storage firebase.
			 */
			$this->removeImageFirebase($paperData['image_path']);
		}
		$this->logTha->logFirebase('info', 'end remove paper in firebase ------->>');
		$this->updatePaperCache();
		return [
			'status' => true,
			'value' => $idInFirebase
		];
	}

	function updatePaperCache()
	{
		$userRef = $this->firebaseDatabase->getReference('/newpaper/papers');
		$snapshot = $userRef->getSnapshot();
		Cache::put('paper_in_firebase', $this->paperInFirebase());
	}

	function upSliderImages(array $sliderImages)
	{
		try {
			foreach ($sliderImages as &$value) {
				$value->value = $this->upLoadImageFirebase($value->value);
			}
			return $sliderImages;
		} catch (\Throwable $th) {
			$this->logTha->logFirebase('warning', 'upSliderImages error: ' . $th->getMessage(), ['line' => $th->getLine()]);
		}
	}

	/**
	 * @param int|Paper $paper
	 * @return void
	 */
	function upContentFireStore(Paper $paper)
	{
		// $fireStore = $this->fireStore->collection('newpaper')->document('detailcontent')->snapshot()->data();

		// $this->fireStore->collection('newpaper')->document('detailcontent')->set([
		// 	'12' => '2312312312'
		// ]);

		// $this->fireStore->collection('detailContent')->newDocument()->create([
		// 	'121' => '2312312312'
		// ]);

		// document
		try {
			if (is_numeric($paper)) {
				$paper = $this->getDetail($paper);
			}
			$_paper = $paper->toArray();
			$_paper['tags'] = $paper->to_tag()->getResults()->toArray();
			$_paper['slider_images'] = $this->upSliderImages($paper->sliderImages()->toArray());
			$this->fireStore->collection('detailContent')->document($_paper['id'])->create($_paper);
			$this->logTha->logFirebase('info', "added for paper detail: " . $paper->id . " to document paper detail firebase", [
				'paper_id' => $paper->id
			]);
		} catch (\Throwable $th) {
			$this->logTha->logFirebase('warning', 'add paper detail to firestore error: ' . $th->getMessage(), ['line' => $th->getLine()]);
		}
	}

	/**
	 * @param int|Paper $paper
	 * @return void
	 */
	function upPaperInfo(Paper $paper)
	{
		try {
			if (is_numeric($paper)) {
				$paper = $this->getDetail($paper);
			}
			if (empty($paper)) {
				return;
			}
			$observer = $this->fireStore->collection('detailInfo')->document($paper->id);
			if (!$observer->snapshot()->data()) {
				$observer->create($paper->paperInfo());
			} else {
				$observer->delete();
				$observer->create($paper->paperInfo());
			}
			$this->logTha->logFirebase('info', "added for detail info: " . $paper->id . " to document paper info firebase", ['paper' => $paper->id]);
		} catch (\Throwable $th) {
			$this->logTha->logFirebase('warning', 'add paper info firebase error: ' . $th->getMessage(), ['line' => $th->getLine()]);
		}
	}

	/**
	 * @param int|Paper $paper
	 * @param string|null $image_path
	 * @return void
	 */
	function upPaperWriter($paper, $image_path = null): void
	{
		try {
			if (is_numeric($paper)) {
				$paper = $this->getDetail($paper);
			}
			$_paper = $paper->toArray();
			unset($_paper['conten']);
			$_paper['image_path'] = $image_path;
			$writers = $paper->to_writer()->getResults()->toArray();
			$userRef = $this->firebaseDatabase->getReference('/newpaper/writers/' . $writers['id'] . "/" . $_paper['id']);
			$userRef->push($_paper);
			$this->logTha->logFirebase('info', "added for paper to writer: " . $paper->id . " to realTime database writer firebase", [
				'paper_id' => $paper->id,
				'writer' => $writers['id']
			]);
		} catch (\Throwable $th) {
			$this->logTha->logFirebase('warning', 'add paper to writer firebase realtime error: ' . $th->getMessage(), ['line' => $th->getLine()]);
		}
	}

	function rmContentFireStore($paperId)
	{
		$this->fireStore->collection('detailContent')->document($paperId)->delete();
	}


	/**
	 * @param int|Paper $paper
	 * @param string|null $firebaseImage
	 * @return void
	 */
	public function addPapersCategory(Paper $paper, $firebaseImage = null)
	{
		$_paper = $paper->toArray();
		if ($firebaseImage) {
			$_paper['image_path'] = $firebaseImage;
		} else {
			unset($_paper['image_path']);
		}
		unset($_paper['conten']);
		$paper['categories'] = $paper->listIdCategories();
		if (($paper['categories']) && count($paper['categories'])) {
			foreach ($paper['categories'] as $value) {
				try {
					$userRef = $this->firebaseDatabase->getReference('/newpaper/papersCategory/' . $value . "/{$_paper['id']}");
					$userRef->push($_paper);
					$this->logTha->logFirebase('info', "added for paperId: " . $_paper['id'] . " to paperCategory firebase", [
						'paperId' => $_paper['id'],
						'categoryId' => $value
					]);
				} catch (\Throwable $th) {
					$this->logTha->logFirebase('warning', 'add paper category firebase error: ' . $th->getMessage(), ['line' => $th->getLine()]);
				}
			}
		}
	}

	/**
	 * @param int|Paper $paperId
	 * @return void
	 */
	function upFirebaseComments($paper)
	{
		if (is_numeric($paper)) {
			$paper = $this->getDetail($paper);
		}
		if (empty($paper)) {
			return;
		}
		$commentTree = $paper->getCommentTree(null, 0, 0);
		if (!empty($commentTree) && count($commentTree)) {
			try {
				$userRef = $this->firebaseDatabase->getReference('/newpaper/comments/' . $paper->id)->remove();
				$userRef->push($commentTree);
				$this->logTha->logFirebase('info', "added for comment to list comment firebase", [
					'paper_id' => $paper->id,
				]);
			} catch (\Throwable $th) {
				$this->logTha->logFirebase('warning', 'up paper comment to comment list firebase error: ' . $th->getMessage(), ['line' => $th->getLine()]);
			}
		}
	}

	function pullFirebaseComment()
	{
		$observer = $this->firebaseDatabase->getReference('/newpaper/addComments/');
		$snapshot = $observer->getSnapshot()->getValue();
		if (!empty($snapshot)) {
			Comment::insert($snapshot);
			$observer->remove();
			if ($paperIds = array_unique(array_column($snapshot, 'paper_id'))) {
				foreach ($paperIds as $id) {
					$this->upFirebaseComments($id);
				}
			}
		}
	}

	function pullFirebasePaperLike()
	{
		$observer = $this->firebaseDatabase->getReference('/newpaper/addLike/');
		$snapshot = $observer->getSnapshot()->getValue();
		if (empty($snapshot)) {
			return;
		}
		foreach ($snapshot as $value) {
			if ($paper = $this->getDetail($value['paper_id'])) {
				$viewSource = $paper->viewSource();
				$viewSource->like = $viewSource->like + ($value['type'] == 'like' ? ($value['action'] == 'add' ? 1 : 0) : 0);
				$viewSource->heart = $viewSource->heart + ($value['type'] == 'heart' ? ($value['action'] == 'add' ? 1 : 0) : 0);
				$viewSource->save();
			}
		}
		$observer->remove();

		if ($paperIds = array_unique(array_column($snapshot, 'paper_id'))) {
			foreach ($paperIds as $id) {
				$paper = $this->getDetail($id);
				$this->upPaperInfo($paper);
			}
		}
	}

	function pullFirebaseComLike()
	{
		$observer = $this->firebaseDatabase->getReference('/newpaper/addCommentLike/');
		$snapshot = $observer->getSnapshot()->getValue();
		$paperIds = [];
		if (empty($snapshot)) {
			return;
		}
		foreach ($snapshot as $value) {
			if ($comment = $this->getComment($value['comment_id'])) {
				$comment->like = $comment->like + ($value['type'] == 'like' ? 1 : -1);
				$paperIds[] = $comment->paper_id;
				$comment->save();
			}
		}
		$observer->remove();

		if (count(array_unique($paperIds))) {
			foreach ($paperIds as $id) {
				$paper = $this->getDetail($id);
				$this->upFirebaseComments($paper);
			}
		}
	}

	function mostPopulator()
	{
		$mostView = ViewSource::where('type', ViewSource::TYPE_PAPER)->orderBy('value', 'DESC')->limit(8)->get(['source_id'])->toArray();
		$mostPapers = Paper::find(array_column($mostView, "source_id"))->makeHidden(['conten']);
		foreach ($mostPapers as &$value) {
			$value->image_path = $this->helperFunction->replaceImageUrl($value['image_path']);
			$value->info = $value->paperInfo();
		}
		return $mostPapers;
	}

	function mostRecents()
	{
		$mostRecents = Paper::limit(8)->orderBy('created_at', 'DESC')->get()->makeHidden(['conten']);
		foreach ($mostRecents as &$value) {
			$value->image_path = $this->helperFunction->replaceImageUrl($value['image_path']);
		}
		return $mostRecents;
	}

	function hit()
	{
		$hit = Paper::all()->last()->makeHidden(['conten']);
		$hit->image_path = $this->helperFunction->replaceImageUrl($hit['image_path']);
		return $hit;
	}

	function forward()
	{
		$forward = Paper::all()->random(1)->makeHidden(['conten'])->first();
		$forward->image_path = $this->helperFunction->replaceImageUrl($forward['image_path']);
		/**
		 * get writer
		 */
		$writer = $forward->to_writer()->getResults();
		if ($writer) {
			$writer->image_path = $this->helperFunction->replaceImageUrl($writer->image_path ?: '');
			/**
			 * set writer for data
			 */
			$forward->writer = $writer;
		}
		return $forward;
	}

	function listImages()
	{
		try {
			$listImages = Paper::all()->random(5)->makeHidden(['conten']);
			foreach ($listImages as &$value) {
				$value->image_path = $this->helperFunction->replaceImageUrl($value['image_path']);
			}
			return $listImages;
		} catch (\Throwable $th) {
			//throw $th;
		}
		return null;
	}

	function timeLine()
	{
		$dt = Carbon::now('Asia/Ho_Chi_Minh');
		/**
		 * @var Collection $timeLine
		 */
		$timeLine = $this->paperContent
			->where(PaperContent::ATTR_TYPE, PaperContent::TYPE_TIMELINE)
			->where(PaperContent::ATTR_VALUE, ">=", $dt->toDateTimeString())
			->orderBy(PaperContent::ATTR_VALUE, 'ASC')
			->take(8)
			->get();
		$papers = [];
		foreach ($timeLine as $time) {
			/**
			 * @var PaperContent $time
			 */
			$paper = $time->toPaper()->getResults();
			$paper->image_path = $this->helperFunction->replaceImageUrl($paper->image_path);
			$paper->time = date_format(new DateTime($time->value), "d-m-Y H:i");
			$paper->description = $paper->title;
			$papers[] = $paper;
		}
		return $papers;
	}

	function homeInfo(): array
	{
		$timeLine = $this->timeLine();
		$hit = $this->hit();
		$forward = $this->forward();
		$mostPopulator = $this->mostPopulator();
		$mostRecents = $this->mostRecents();
		$listImages = $this->listImages();

		$tags = $this->tags();
		$writers = $this->writerApi->allWriter();
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

	function upFirebaseHomeInfo(): bool
	{
		try {
			$homeInfo = $this->homeInfo();
			$userRef = $this->firebaseDatabase->getReference('/newpaper/info');
			$snapshot = $userRef->getSnapshot();
			/**
			 * check infoHome data in firebase
			 *  if exist remove.
			 */
			if ($snapshot->getValue()) {
				$userRef->remove();
			}
			$userRef->push($homeInfo);
			return true;
		} catch (\Throwable $th) {
			//throw $th;
		}
		return false;
	}

	function tags(): array
	{
		$tags = PageTag::select('value')->take(8)->get()->toArray();
		return array_column($tags, 'value');
	}

	function searchAll()
	{
		return $this->search($this->request->get('search', $this->request->get('query')));
	}

	function search(string $query)
	{
		$searchValue = strtolower($query);

		$searchPaper = array_column(Paper::where('title', 'LIKE', "%$searchValue%")
			->orWhere('short_conten', 'LIKE', "%$searchValue%")->get('id')->toArray(), 'id') ?: [];

		$searchTags = array_column(PageTag::where('value', 'LIKE', "%$searchValue%")->get('entity_id')->toArray(), 'entity_id') ?: [];

		$allValue = array_unique(array_merge($searchPaper, $searchTags));
		$papers = Paper::whereIn('id', $allValue)->get();
		return $papers;
	}
}
