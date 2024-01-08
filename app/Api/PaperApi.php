<?php

namespace App\Api;

use App\Models\Paper;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Database\Snapshot;

class PaperApi
{
	protected $firebase;
	protected $firebaseDatabase;

	function __construct(
		FirebaseService $firebaseService
	) {
		$this->firebase = $firebaseService->firebase;
		$this->firebaseDatabase = $this->firebase->createDatabase();
	}

	function paperInFirebase(): array
	{
		$userRef = $this->firebaseDatabase->getReference('/newpaper/papers');
		$snapshot = $userRef->getSnapshot();
		return $snapshot->getValue() ?: [];
	}

	public function addFirebase($paperId, $hidden = ['conten']): array
	{
		try {
			$paper = Paper::find($paperId)->makeHidden($hidden)->toArray();
			if (!empty($paper)) {
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
			$paperId = $userRef->getSnapshot()->getValue()['id'];
			$userRef->remove();
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

	function updatePaperCache() {
		$userRef = $this->firebaseDatabase->getReference('/newpaper/papers');
		$snapshot = $userRef->getSnapshot();
		Cache::put('paper_in_firebase', $snapshot->getValue());
	}
}
