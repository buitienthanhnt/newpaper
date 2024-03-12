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
use Illuminate\Http\Request;
use Thanhnt\Nan\Helper\LogTha;

class PaperApi extends BaseApi
{
	protected $helperFunction;
	protected $request;
	protected $writerApi;
	protected $logTha;

	function __construct(
		FirebaseService $firebaseService,
		HelperFunction $helperFunction,
		Request $request,
		WriterApi $writerApi,
		LogTha $logTha
	) {
		$this->helperFunction = $helperFunction;
		$this->request = $request;
		$this->writerApi = $writerApi;
		$this->logTha = $logTha;
		parent::__construct($firebaseService);
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
				$firebaseImage = $this->upLoadImageFirebase($paper['image_path']);
				if ($firebaseImage) {
					$paper['image_path'] = $firebaseImage;
					$paper['info'] = $_paper->paperInfo();
				} else {
					unset($paper['image_path']);
				}
			}
			foreach ($hidden as $k) {
				unset($paper[$k]);
			}
			// queue for upload data of paper to firebase
			UpPaperFireBase::dispatch($_paper->id, $firebaseImage ?? null);
			// $this->upFirebaseComments($_paper);
			// $this->upPaperInfo($_paper);
			// $this->addPapersCategory($_paper, $firebaseImage);
			// $this->upContentFireStore($_paper);
			$userRef = $this->firebaseDatabase->getReference('/newpaper/papers/' . $_paper->id);
			$userRef->push($paper);
			$snapshot = $userRef->getSnapshot();

			$this->logTha->logFirebase('info', "added for paperId: " . $paper['id'] . " to paperList firebase");

			Cache::put('paper_in_firebase', $this->paperInFirebase());
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

	function removeInFirebase($idInFirebase)
	{
		try {
			$userRef = $this->firebaseDatabase->getReference('/newpaper/papers/' . $idInFirebase);
			$paperData = $this->formatPaperFirebase($userRef->getSnapshot()->getValue());
			$paperId = $paperData['id'];
			$userRef->remove();
			$this->rmContentFireStore($paperId);
			if (isset($paperData['image_path'])) {
				$this->removeImageFirebase($paperData['image_path']);
			}
			$this->updatePaperCache();
			return [
				'status' => true,
				'value' => $paperId
			];
		} catch (\Throwable $th) {
			//throw $th;
		}
		return false;
	}

	function updatePaperCache()
	{
		$userRef = $this->firebaseDatabase->getReference('/newpaper/papers');
		$snapshot = $userRef->getSnapshot();
		Cache::put('paper_in_firebase', $this->paperInFirebase());
	}

	function upSliderImages(array $sliderImages)
	{
		foreach ($sliderImages as &$value) {
			$value->value = $this->upLoadImageFirebase($value->value);
			Log::alert($value->value);
		}
		return $sliderImages;
	}

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
			Log::error($th->getMessage());
		}
	}

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
			$this->logTha->logFirebase('info', "added for detail info: " . $paper->id . " to document paper info firebase");
			if (!$observer->snapshot()->data()) {
				$observer->create($paper->paperInfo());
			} else {
				$observer->delete();
				$observer->create($paper->paperInfo());
			}
		} catch (\Throwable $th) {
			echo ($th->getMessage());
			//throw $th;
		}
	}

	/**
	 * @param int|Paper $paper
	 * @return void
	 */
	function upPaperWriter($paper): void
	{
		try {
			if (is_numeric($paper)) {
				$paper = $this->getDetail($paper);
			}
			$_paper = $paper->toArray();
			unset($_paper['conten']);
			$writers = $paper->to_writer()->getResults()->toArray();
			$userRef = $this->firebaseDatabase->getReference('/newpaper/writers/' . $writers['id'] . "/" . $_paper['id']);
			$userRef->push($_paper);
			$this->logTha->logFirebase('info', "added for paper writer: " . $paper->id . " to realTime database writer firebase", [
				'paper_id' => $paper->id,
				'writer' => $writers['id']
			]);
		} catch (\Throwable $th) {
			$this->logTha->logError('warning', $th->getMessage());
		}
	}

	function rmContentFireStore($paperId)
	{
		$this->fireStore->collection('detailContent')->document($paperId)->delete();
	}


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
				$userRef = $this->firebaseDatabase->getReference('/newpaper/papersCategory/' . $value);
				$userRef->push($_paper);
				$this->logTha->logFirebase('info', "added for paperId: " . $_paper['id'] . " to paperCategory firebase", [
					'paperId' => $_paper['id'],
					'categoryId' => $value
				]);
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
			$userRef = $this->firebaseDatabase->getReference('/newpaper/comments/' . $paper->id)->remove();
			$userRef->push($commentTree);
			$this->logTha->logFirebase('info', "added for comment list to comment firebase", [
				'paper_id' => $paper->id,
			]);
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

	function demoLog(): void
	{
		Log::alert('demo 123');
	}

	function mostPopulator()
	{
		$mostView = ViewSource::where('type', ViewSource::PAPER_TYPE)->orderBy('value', 'desc')->limit(8)->get(['source_id'])->toArray();
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
		$hits = Paper::all()->random(1)->makeHidden(['conten']);
		foreach ($hits as &$value) {
			$value->image_path = $this->helperFunction->replaceImageUrl($value['image_path']);
		}
		return $hits;
	}

	function listImages()
	{
		$listImages = Paper::all()->random(5)->makeHidden(['conten']);
		foreach ($listImages as &$value) {
			$value->image_path = $this->helperFunction->replaceImageUrl($value['image_path']);
		}
		return $listImages;
	}

	function timeLine()
	{
		$timeLine = Paper::all()->random(6)->sortBy('updated_at')->makeHidden(['conten']);
		foreach ($timeLine as &$value) {
			$value->image_path = $this->helperFunction->replaceImageUrl($value['image_path']);
			$value->time = date_format($value->updated_at, "d-m H:i");
			$value->description = $value->title;
		}
		return $timeLine;
	}

	function homeInfo(): array
	{
		$hit = $this->hit();
		$mostPopulator = $this->mostPopulator();
		$mostRecents = $this->mostRecents();
		$listImages = $this->listImages();
		$timeLine = $this->timeLine();
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
			'data' => [
				'status' => true,
				'code' => 200,
				'hit' => $hit[0],
				'mostPopulator' => $mostPopulator,
				'mostRecents' => $mostRecents,
				'listImages' => $listImages,
				'timeLine' => $timeLine,
				'search' => $tags,
				'writers' => $writers,
				'map' => $lineMap,
				'video' => $video
			],
			'success' => true,
			'error' => null
		];
	}

	function upFirebaseHomeInfo(): bool
	{
		try {
			$homeInfo = $this->homeInfo();
			$userRef = $this->firebaseDatabase->getReference('/newpaper/info');
			$userRef->push($homeInfo);
			$snapshot = $userRef->getSnapshot();
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
		return $this->search($this->request->get('query'));
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
