<?php

namespace App\Api;

use App\Jobs\UpPaperFireBase;
use App\Models\Comment;
use App\Models\Paper;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Thanhnt\Nan\Helper\LogTha;

class PaperApi extends BaseApi
{
	function __construct(
		FirebaseService $firebaseService
	) {
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
			UpPaperFireBase::dispatch($_paper->id);
			// $this->upFirebaseComments($_paper);
			// $this->upPaperInfo($_paper);
			// $this->addPapersCategory($paper);
			// $this->upContentFireStore($paper);
			$userRef = $this->firebaseDatabase->getReference('/newpaper/papers/' . $_paper->id);
			$userRef->push($paper);
			$snapshot = $userRef->getSnapshot();
			Cache::put('paper_in_firebase', $snapshot->getValue());
			return [
				'status' => true,
				'value' => array_key_last($snapshot->getValue())
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
		Cache::put('paper_in_firebase', $snapshot->getValue());
	}

	function upContentFireStore($paper)
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
				$paper = $this->getDetail($paper)->toArray();
			}
			$this->fireStore->collection('detailContent')->document($paper['id'])->create($paper);
		} catch (\Throwable $th) {
			//throw $th;
		}
	}

	function upPaperInfo($paper)
	{
		try {
			if (is_numeric($paper)) {
				$paper = $this->getDetail($paper);
			}
			$this->fireStore->collection('detailInfo')->document($paper->id)->create($paper->paperInfo());
		} catch (\Throwable $th) {
			//throw $th;
		}
	}

	function rmContentFireStore($paperId)
	{
		$this->fireStore->collection('detailContent')->document($paperId)->delete();
	}

	function addPapersCategory($paper = null)
	{
		if (!is_array($paper)) {
			$paper = $this->getDetail($paper)->toArray();
		}
		if (count($paper['categories'])) {
			foreach ($paper['categories'] as $value) {
				$userRef = $this->firebaseDatabase->getReference('/newpaper/papersCategory/' . $value);
				$userRef->push($paper);
			}
		}
	}

	function upFirebaseComments($paper)
	{
		if (is_numeric($paper)) {
			$paper = $this->getDetail((int) $paper);
		}
		$commentTree = $paper->getCommentTree(null, 0, 0);
		if (count($commentTree)) {
			$userRef = $this->firebaseDatabase->getReference('/newpaper/comments/' . $paper->id)->remove();
			$userRef->push($commentTree);
		}
	}

	function pullFirebaseComment()
	{
		$observer = $this->firebaseDatabase->getReference('/newpaper/addComments/');
		$snapshot = $observer->getSnapshot()->getValue();
		Comment::insert($snapshot);
		if (!empty($snapshot)) {
			$observer->remove();
		}
		if ($paperIds = array_unique(array_column($snapshot, 'paper_id'))) {
			foreach ($paperIds as $id) {
				$this->upFirebaseComments($id);
			}
		}
	}

	function pullFirebasePaperLike()
	{
		$observer = $this->firebaseDatabase->getReference('/newpaper/addLike/');
		$snapshot = $observer->getSnapshot()->getValue();
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
}
