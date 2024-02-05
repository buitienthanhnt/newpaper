<?php

namespace App\Api;

use App\Models\Comment;
use App\Models\Paper;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\Cache;

class PaperApi extends BaseApi
{
	function __construct(
		FirebaseService $firebaseService
	) {
		parent::__construct($firebaseService);
	}

	public function getDetail(int $paperId): Paper | null
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

	function paperInFirebase(): array
	{
		$userRef = $this->firebaseDatabase->getReference('/newpaper/papers');
		$snapshot = $userRef->getSnapshot();
		return $snapshot->getValue() ?: [];
	}

	public function addFirebase($paperId, $hidden = []): array
	{
		try {
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
				$this->upContentFireStore($paper);
				foreach ($hidden as $k) {
					unset($paper[$k]);
				}
				$this->addPapersCategory($paper);
				$this->upFirebaseComments($_paper);
				$userRef = $this->firebaseDatabase->getReference('/newpaper/papers');
				$userRef->push($paper);
				$snapshot = $userRef->getSnapshot();
				Cache::put('paper_in_firebase', $snapshot->getValue());
				return [
					'status' => true,
					'value' => array_key_last($snapshot->getValue())
				];
			}
		} catch (\Throwable $exception) {
			// Log::error($exception->getMessage());
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
			$paperData = $userRef->getSnapshot()->getValue();
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
		$this->fireStore->collection('detailContent')->document($paper['id'])->create($paper);
	}

	function rmContentFireStore($paperId)
	{
		$this->fireStore->collection('detailContent')->document($paperId)->delete();
	}

	function addPapersCategory($paper)
	{
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
				
			}
		}
	}
}
