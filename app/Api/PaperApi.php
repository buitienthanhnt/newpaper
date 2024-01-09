<?php

namespace App\Api;

use App\Helper\ImageUpload;
use App\Models\Paper;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Database\Snapshot;
use Google\Cloud\Storage\Connection\Rest;
use Illuminate\Support\Str;

class PaperApi
{
	use ImageUpload;

	protected $firebase;
	protected $firebaseDatabase;
	protected $fireStore;

	function __construct(
		FirebaseService $firebaseService
	) {
		$this->firebase = $firebaseService->firebase;
		$this->firebaseDatabase = $this->firebase->createDatabase();
		$this->fireStore = $firebaseService->fireStore;
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
			$paper = Paper::find($paperId)->toArray();
			if (!empty($paper)) {
				if (isset($paper['image_path']) && !empty($paper['image_path'])) {
					$firebaseImage = $this->upLoadImageFirebase($paper['image_path']);
					if ($firebaseImage) {
						$paper['image_path'] = $firebaseImage;
					}else {
						unset($paper['image_path']);
					}
				}

				$this->upContentFireStore($paper);
				$paper = array_diff_key($paper, $hidden);

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
			$this->rmContentFireStore($paperId);
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

	function upLoadImageFirebase(string $image_link)
	{
		$firebaseFolder = 'demo/';
		$real_path = $this->url_to_real($image_link);
		if (empty($real_path)) {
			return null;
		}
		$image = fopen($real_path, 'r');
		try {
			/**
			 * @var Kreait\Firebase\Contract\Storage $storage
			 */
			$storage = $this->firebase->createStorage();
			$bucket = $storage->getBucket();

			// upload 1 file lên store
			$response = $bucket->upload($image, ['name' => $firebaseFolder . Str::random(10) . '.' . explode('.', $image_link, 2)[1]]);
			$uri = $response->info()['mediaLink'];
			return str_replace(Rest::DEFAULT_API_ENDPOINT . '/download/storage/v1', 'https://firebasestorage.googleapis.com/v0', $uri);
		} catch (\Throwable $th) {
			// echo ($th->getMessage());
		}
		return null;
	}

	function upContentFireStore($paper)
	{
		$fireStore = $this->fireStore->collection('newpaper')->document('detailcontent')->snapshot()->data();

		// $this->fireStore->collection('newpaper')->document('detailcontent')->set([
		// 	'12' => '2312312312'
		// ]);

		// $this->fireStore->collection('detailContent')->newDocument()->create([
		// 	'121' => '2312312312'
		// ]);

		// document
		$this->fireStore->collection('detailContent')->document($paper['id'])->create($paper);
	}

	function rmContentFireStore($paperId) {
		$this->fireStore->collection('detailContent')->document($paperId)->delete();
	}
}
