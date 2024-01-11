<?php
namespace App\Api;

use App\Helper\ImageUpload;
use App\Services\FirebaseService;
use Google\Cloud\Storage\Connection\Rest;
use Illuminate\Support\Str;

class BaseApi
{
    use ImageUpload;

    const STORERAGE_BUGKET = 'newpaper';

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

    function upLoadImageFirebase(string $image_link)
    {
        $firebaseFolder = self::STORERAGE_BUGKET . '/';
        $real_path = $this->url_to_real($image_link);
        if (empty($real_path)) {
            return null;
        }
        $image = fopen($real_path, 'r');
        try {
            $fileType = explode('.', $image_link);
            $fileType = $fileType[count($fileType) - 1];
            /**
             * @var \Kreait\Firebase\Contract\Storage $storage
             */
            $storage = $this->firebase->createStorage();
            $bucket = $storage->getBucket();

            // upload 1 file lên store
            $response = $bucket->upload($image, ['name' => $firebaseFolder . Str::random(10) . '.' . $fileType]);
            $uri = $response->info()['mediaLink'];
            return str_replace(Rest::DEFAULT_API_ENDPOINT . '/download/storage/v1', 'https://firebasestorage.googleapis.com/v0', $uri);
        } catch (\Throwable $th) {
            // echo ($th->getMessage());
        }
        return null;
    }

    function removeImageFirebase(string $url_path)
    {
        try {
            /**
             * @var \Kreait\Firebase\Contract\Storage $storage
             */
            $storage = $this->firebase->createStorage();
            $fileName = '/' . self::STORERAGE_BUGKET . explode("/" . self::STORERAGE_BUGKET . "/", parse_url(urldecode($url_path))['path'], 2)[1];
            $storage->getBucket()->object($fileName)->delete();
            return true;
        } catch (\Throwable $th) {
            //throw $th;
        }
        return false;
    }
}
