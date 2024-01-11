<?php

namespace App\Api;

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
					} else {
						unset($paper['image_path']);
					}
				}
				$this->upContentFireStore($paper);
				foreach ($hidden as $k) {
					unset($paper[$k]);
				}
				$this->addPapersCategory($paper);
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
				$userRef = $this->firebaseDatabase->getReference('/newpaper/papersCategory/' .$value);
				$userRef->push($paper);
			}
		}
	}
}
